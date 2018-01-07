<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/3
 * 创建时间: 21:36
 * 文件名:BaseException.php
 */

namespace app\lib\exception;


use think\Exception;

class BaseException extends Exception
{
    //HTTP 状态码
    public $code = 400;

    //具体错误信息
    public $msg = '参数错误';

    //自定义的错误码
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if(!is_array($params))
        {
            return ;
            //throw new Exception('参数必须是数组');
        }
        if(array_key_exists('code',$params))
        {
            $this -> code = $params['code'];
        }
        if(array_key_exists('msg',$params))
        {
            $this -> msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params))
        {
            $this -> errorCode = $params['errorCode'];
        }
    }
}
