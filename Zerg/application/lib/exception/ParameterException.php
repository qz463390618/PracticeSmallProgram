<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/6
 * 创建时间: 15:37
 * 文件名:ParameterException.php
 */

namespace app\lib\exception;


class ParameterException extends  BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000 ;
}
