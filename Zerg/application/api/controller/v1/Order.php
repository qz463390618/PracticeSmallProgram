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
use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;

class Order extends BaseController
{
    //用户在选择商品后,向api提交包含他所选择商品的信息
    //api在接收到信息后,需要检查订单相关商品的库存量\
    //有库存,把订单数据存入数据库中 = 下单成功了,返回客户端消息,告诉客户端可以支付了
    //调用我们的支付接口,进行支付
    //还需要进行库存量检测
    //服务器这边就可以调用微信的支付接口进行支付
    //微信返回给我们一个支付结果
    //成功:也需要进行库存量的检测
    //成功:进行库存量的扣除, 失败:返回一个支付失败的结果
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' =>'placeOrder']
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








    public function del()
    {
        $order = new \app\api\model\Order();
        $order -> where('id','=','3') -> delete();
        return 'succes';
    }
}
