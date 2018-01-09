<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-09
 * 时间: 上午 9:46
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效Token';
    public $errorCode = 10001;
}