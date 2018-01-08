<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-08
 * 时间: 下午 5:27
 */

namespace app\api\model;


class User extends BaseModel
{
    public static function getByOpenID($openID)
    {
        $user =  self::where('openid','=',$openID)->find();
        return $user;
    }
}
