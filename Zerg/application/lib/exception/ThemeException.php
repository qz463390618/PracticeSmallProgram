<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/7
 * 创建时间: 21:31
 * 文件名:ThemeException.php
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '指定的主题不存在,请检查主题ID';
    public $errorCode = 30000;
}
