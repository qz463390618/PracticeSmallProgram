<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/12
 * 创建时间: 21:37
 * 文件名:OrderException.php
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单不存在,请检查ID';
    public $errorCode = 80000;
}
