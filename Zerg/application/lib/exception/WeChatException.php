<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/8
 * 创建时间: 21:02
 * 文件名:WeChatException.php
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400;
    public $msg = '微信服务接口失败';
    public $errorCode = 999;
}
