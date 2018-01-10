<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-09
 * 时间: 上午 9:20
 */

namespace app\api\service;


use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        //32个字符组成一组随机字符串
        $randChars = getRandChar(32);
        //用三组字符串,进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //salt 盐
        $salt =config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrenTokenVar($key)
    {
        //实例化Request对象,并且获取head头里面值
        $token = Request::instance()
                ->header('token');
        $vars = Cache::get($token);
        //var_dump($vars);die;
        if(!$vars)
        {
            throw new TokenException();
        }else{
            if(!is_array($vars))
            {
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars))
            {
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }



    public static function getCurrentUid()
    {
        $uid = self::getCurrenTokenVar('uid');
        //echo $uid;die;
        return $uid;
    }
}
