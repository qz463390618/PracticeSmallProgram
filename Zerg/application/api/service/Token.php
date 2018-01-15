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
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;

class Token
{
    //生成token
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

    //获取 token中某一个值 传入要获取的键名
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

    //获取token 中的 uid值
    public static function getCurrentUid()
    {
        $uid = self::getCurrenTokenVar('uid');
        //echo $uid;die;
        return $uid;
    }

    //检测是否含有权限,通用权限 用户和cms管理员都能访问权限
    public static function needPrimaryScope()
    {
        $scope = self::getCurrenTokenVar('scope');
        if($scope)
        {
            if($scope >= ScopeEnum::User)
            {
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    //检测只能有用户权限  只有用户能访问权限
    public static function needExclusiveScope()
    {
        $scope = self::getCurrenTokenVar('scope');
        if($scope)
        {
            if($scope == ScopeEnum::User)
            {
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    //检测是否是一个合法的操作
    public static function isValidOperate($checkedUID)
    {
        if(!$checkedUID)
        {
            throw new Exception('检测UID时必须传入一个被检查的UID');
        }
        $currentOperateUID = self::getCurrentUid();//请求用户的uid
        if($currentOperateUID == $checkedUID)
        {
            return true;
        }
        return false;
    }
}
