<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/3
 * 创建时间: 21:40
 * 文件名:BannerMissException.php
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的banner不存在';
    public $errorCode = 40000;
}
