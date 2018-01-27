<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-08
 * 时间: 下午 4:51
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;
class Token
{
    //获取令牌方法
    public function getToken($code = '')
    {
        (new TokenGet()) ->goCheck();
        $ut = new UserToken($code);
        $token = $ut -> get();
        return [
            'token' => $token
        ];
    }

    //校验令牌是否有效
    public function verifyToken($token = '')
    {
        if(!$token){
            throw new ParameterException([
                'token不能为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return ['isValid' => $valid];
    }
}
