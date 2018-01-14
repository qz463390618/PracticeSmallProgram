<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/14
 * 创建时间: 22:01
 * 文件名:Pay.php
 */

namespace app\api\service;


use think\Exception;

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
        $orderID = $this -> orderID;
    }

    public function pay()
    {

    }
}
