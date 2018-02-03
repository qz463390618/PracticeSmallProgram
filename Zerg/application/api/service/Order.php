<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/12
 * 创建时间: 20:37
 * 文件名:Order.php
 */

namespace app\api\service;

use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\Order as OrderModel;
use app\api\model\UserAddress;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;


/**
 * 订单类
 * 订单做了以下简化：
 * 创建订单时会检测库存量，但并不会预扣除库存量，因为这需要队列支持
 * 未支付的订单再次支付时可能会出现库存不足的情况
 * 所以，项目采用3次检测
 * 1. 创建订单时检测库存
 * 2. 支付前检测库存
 * 3. 支付成功后检测库存
 */
class Order
{
    // 订单的商品列表, 也就是客户端传递过来的products参数
    protected $oProducts;

    //真实的商品信息(包括库存量)
    protected $products;

    protected $uid;

    //下单接口
    public function place($uid,$oProducts)
    {
        //oProducts 与 products 做对比
        //products 从数据库中查出来
        $this -> oProducts = $oProducts;
        $this -> products = $this -> getProductsByOrder($oProducts);
        $this -> uid = $uid;
        $status = $this -> getOrderStatus();
        if(!$status['pass'])
        {
            $status['order_id'] = -1;
            return $status;
        }
        //开始创建订单
        $orderSnap= $this -> snapOrder($status);
        $order = $this -> createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    //创建订单
    private function createOrder($snap)
    {
        Db::startTrans();
        try{
            $orderNo = $this -> makeOrderNo();
            $order = new \app\api\model\Order();
            $order -> user_id = $this ->uid;
            $order -> order_no = $orderNo;
            $order -> total_price = $snap['orderPrice'];
            $order -> total_count = $snap['totalCount'];
            $order -> snap_img = $snap['snapImg'];
            $order -> snap_name = $snap['snapName'];
            $order -> snap_address = $snap['snapAddress'];
            $order -> snap_items = json_encode($snap['pStatus']);

            $order -> save();

            $orderID = $order -> id;
            $create_time = $order -> create_time;
            foreach($this -> oProducts as &$p)
            {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct -> saveAll($this -> oProducts);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        } catch (Exception $e){
            Db::rollback();
            throw $e;
        }

    }

    //生成订单号
    public static function makeOrderNo()
    {
        $yCode = ['A','B','C','D','E','F','G','H','I','J'];
        $orderSn = $yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) . date('d') . substr(time() ,-5) . substr(microtime(),2,5) . sprintf('%02d',rand(0,99));
        return $orderSn;
    }

    //生成订单快照
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0, //订单总价
            'totalCount' => 0, //订单商品数量
            'pStatus' => [],   // 商品状态
            'snapAddress' => '', //收货地址
            'snapName' => '',   //订单名
            'snapImg' => ''     //订单图片
        ];
        $snap['orderPrice'] = $status['orderPrice'];//订单总价
        $snap['totalCount'] = $status['totalCount'];//订单总价
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this -> getUserAddress());
        $snap['snapName'] = $this -> products[0]['name'];
        $snap['snapImg'] = $this -> products[0]['main_img_url'];
        if(count($this ->products) > 1)
        {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    //获取用户收货地址
    private function getUserAddress()
    {
        $userAddres = UserAddress::where('user_id','=',$this -> uid)->find();
        if(!$userAddres)
        {
            throw new UserException([
                'msg' => '用户收货地址不存在,下单失败',
                'errorCode' => 60001
            ]);
        }
        return $userAddres -> toArray();
    }

    //根据订单信息查找真实的商品信息
    private function getProductsByOrder($oProducts)
    {
        $oPIDs = [];
        foreach($oProducts as $oProduct)
        {
            array_push($oPIDs,$oProduct['product_id']);
        }
        $products = Product::all($oPIDs)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();
        return $products;
    }

    //检测订单库存
    public function checkOrderStock($orderID)
    {
        $oProducts = OrderProduct::where('order_id','=',$orderID)
            ->select();
        $this -> oProducts = $oProducts;
        $this -> products = $this ->getProductsByOrder($this -> oProducts);
        $status = $this ->getOrderStatus();
        return $status;
    }


    //获取订单状态
    private function getOrderStatus()
    {
        $status = [
            'pass' => true, //订单库存量是否通过
            'orderPrice' => 0, //商品价格的总和
            'totalCount' => 0, //订单商品的总数量
            'pStatusArray' => [],//所有商品的详细信息
        ];

        foreach($this -> oProducts as $oProduct )
        {
            $pStatus = $this -> getProductStatus($oProduct['product_id'],$oProduct['count'],$this -> products);
            if(!$pStatus['havaStock'])
            {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['counts'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    //获取真实商品的数据
    private function getProductStatus($oPID,$oCount,$products)
    {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'havaStock' => false,
            'counts' => 0,
            'price' => 0,
            'name' => null,
            'totalPrice' => 0,
            'main_img_url' => null
        ];
        for($i = 0; $i < count($products);$i++)
        {
            if($oPID == $products[$i]['id'])
            {
                $pIndex = $i;
            }
        }

        if($pIndex == -1)
        {
            //客户端传递的productID有可能根本不存在
            throw new OrderException([
                'msg' => 'id为'.$oPID.'的商品不存在,创建订单失败',
            ]);
        }else{
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            //$pStatus['count'] = $product['count'];
            $pStatus['counts'] = $oCount;
            $pStatus['price'] = $product['price'];
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            $pStatus['main_img_url'] = $product['main_img_url'];
            $pStatus['havaStock'] = ($product['stock'] - $oCount) >= 0 ? true : false;

        }
        return $pStatus;
    }

    public function delivery($orderID,$jumpPage = '')
    {
        $order = OrderModel::where('id','=',$orderID)->find();
        if(!$order){
            throw new OrderException();
        }
        if($order -> status != OrderStatusEnum::PAID){
            throw new OrderException([
                'mag' => '还没付款呢,想干吗?或者你已经更新过订单了,不要在刷了',
                'errorCode' => 80002,
                'code' => 403,
            ]);
        }
        $order -> status = OrderStatusEnum::DELIVERED;
        $order -> save();
        $message = new DeliveryMessage();
        return $message -> sendDeliveryMessage($order,$jumpPage);
    }
}
