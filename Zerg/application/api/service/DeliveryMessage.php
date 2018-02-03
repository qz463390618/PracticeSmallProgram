<?php
/**
 * 用 PhpStorm编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/2/1
 * 创建时间: 15:46
 * 文件名: DeliveryMessage.php
 */

namespace app\api\service;


use app\api\model\User;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;

class DeliveryMessage extends WxMessage
{
    const DELIVERY_MSG_ID='BhsTBtz8FdWmWH5oJm_ZfB8x8vFePiDiMFGoxwrKgMg';//发货订单编号

    public function sendDeliveryMessage($order,$tplJumpPage = '')
    {
        if(!$order){
            throw new OrderException();
        }

        $this -> tplID = self::DELIVERY_MSG_ID;
        $this -> fromID = $order->perpay_id;
        $this -> page = $tplJumpPage;
        $this -> prepareMessageData($order);
        $this -> emphasisKeyWord = 'keyword2.npw';
        return parent::sendMessage($this -> getUserOpenID($order->user_id));
    }

    private function prepareMessageData($order)
    {
        $dt = new \DateTime();
        $data = [
            'keyword1' => [
                'value' => '顺风速运'
            ],
            'keyword2' => [
                'value' =>$order -> snap_name,
                'color' => '#274088'
            ],
            'keyword3' => [
                'value' =>$dt -> format('Y-m-d H:i'),
            ],
            'keyword4' => [
                'value' =>$order -> order_no,
            ],

        ];
        $this -> data = $data;
        //return $data;
    }

    private  function getUserOpenID($uid)
    {
        $user = User::get($uid);
        if(!$user){
            throw  new UserException();
        }
        return $user -> openid;
    }
}