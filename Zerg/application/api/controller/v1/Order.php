<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/11
 * 创建时间: 21:18
 * 文件名:Order.php
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\validate\PagingParameter;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;

class Order extends BaseController
{
    //用户在选择商品后,向api提交包含他所选择商品的信息
    //api在接收到信息后,需要检查订单相关商品的库存量\
    //有库存,把订单数据存入数据库中 = 下单成功了,返回客户端消息,告诉客户端可以支付了
    //调用我们的支付接口,进行支付
    //还需要进行库存量检测
    //服务器这边就可以调用微信的支付接口进行支付
    //小程序根据服务器返回的结果拉起微信支付
    //微信返回给我们一个支付结果
    //成功:也需要进行库存量的检测
    //成功:进行库存量的扣除, 失败:返回一个支付失败的结果


    /**
     * 做一次库存量检测
     * 创建订单
     * 减库存  --预扣除库存
     * if pay 真正的减少库存
     * 在一定时间内120min没有去支付这个订单,我们需要还原库存
     *  实现:在php里写一个定时器,每隔1min去遍历数据库,找到那些超时的订单,把这些订单给还原到库存  linux crontab
     *
     *  实现:任务队列
     *      订单任务加入到任务队列中,  redis
     */




    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' =>'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser'],
    ];

    //创建订单
    public function placeOrder()
    {
        (new OrderPlace()) -> goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order -> place($uid,$products);
        return $status;
    }

    //查询用户所有订单,并且分页
    public function getSummaryByUser($page = 1,$size = 15)
    {
        (new PagingParameter()) -> goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$size);
        if($pagingOrders ->isEmpty())
        {
            return [
                'data' => null,
                'current_page' => $pagingOrders -> getCurrentPage()
            ];
        }
        $data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrders -> getCurrentPage()
        ];
    }

    //获取某个订单的详情
    public function getDetail($id)
    {
        (new IDMustBePostiveInt()) -> goCheck();
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail -> hidden(['prepay_id']);
    }

    public function del()
    {
        $order = new OrderModel();
        $order -> where('id','=','3') -> delete();
        return 'succes';
    }

    /**
     * 获取订单全部简要信息(分页)
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\execption\ParameterExecption
     */
    public function getSummary($page = 1,$size = 20)
    {
        (new PagingParameter()) -> goCheck();
        $pagingOrders = OrderModel::getSummaryByPage($page,$size);
        if($pagingOrders -> isEmpty()){
            return [
                'current_page' =>$pagingOrders->getCurrentPage(),
                'data' => []
            ];
        }
        //$data = $pagingOrders->toArray();
        //var_dump($data);die;
        //$snam_address = $pagingOrders -> data -> snap_address;
        $data = $pagingOrders->hidden(['snap_items'])->toArray();
        return [
            'current_page' =>$pagingOrders->getCurrentPage(),
            'data' => $data,
            //'address' => $snam_address
        ];
    }

    public function delivery($id)
    {
        (new IDMustBePostiveInt()) -> goCheck();
        $order = new OrderService();
        $success = $order -> delivery($id);
        if($success){
            return new SuccessMessage();
        }
    }
}
