<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/14
 * 创建时间: 22:01
 * 文件名:Pay.php
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;


    public function __construct($orderID)
    {
        if(!$orderID)
        {
            throw new Exception('订单号不允许为NULL');
        }
        $this -> orderID = $orderID;
    }

    public function pay()
    {
        //订单号可能根本不存在

        //订单号存在,但是订单号和当前用户不匹配
        //订单有可能支付过,
        //进行库存量检测
        $this -> checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService -> checkOrderStock($this -> orderID);
        if(!$status['pass'])
        {
            return $status;
        }
        return $this -> makeWxPreOrder($status['orderPrice']);
    }

    //生成微信订单   预订单
    private function makeWxPreOrder($totalPrice)
    {
        //openid
        $openid = TokenService::getCurrenTokenVar('openid');
        if(!$openid)
        {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        //订单号
        $wxOrderData -> SetOut_trade_no($this -> orderNO);
        //订单类型
        $wxOrderData -> SetTrade_type('JSAPI');
        //订单总金额
        $wxOrderData -> SetTotal_fee($totalPrice*100);
        //商品描述
        $wxOrderData -> SetBody(config('setting.shop_name'));
        //用户标识
        $wxOrderData -> SetOpenid($openid);
        //通知地址
        $wxOrderData ->SetNotify_url('http://qq.com');
        return $this -> getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS')
        {
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','');
        }
        return null;
    }



    //检测订单
    private function checkOrderValid()
    {
        $order = OrderModel::where('id','=',$this -> orderID)->find();
        //$order = OrderModel::where('id','=',$this -> orderID)
            //->find();
        if(!$order)
        {
            throw new OrderException();
        }

        if(!Token::isValidOperate($order -> user_id))
        {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }

        if($order -> status != OrderStatusEnum::UNPAID)
        {
            throw new OrderException([
                'msg' => '订单已支付过了的',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        $this -> orderNO = $order -> order_no;
        return true;
    }
}
