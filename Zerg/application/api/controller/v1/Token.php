<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-08
 * 时间: 下午 4:51
 */

namespace app\api\controller\v1;


use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\validate\AppTokenGet;
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

    /**
     * 第三方应用获取令牌
     * @url /app_token?
     * @post ac=:ac se=:secret
     */
    public function getAppToken($ac='',$se='')
    {
        /*
        //允许所有的域来访问我们的API
        header('Access-Control-Allow-Origin:*');
        //允许来访问我们的API,header头可以携带的东西
        header('Access-Control-Allow-Headers:token,Origin,X-Requested-With,Content-Type,Accept');
        header('Access-Control-Allow-Methods:POST,GET');
        */
        (new AppTokenGet()) -> goCheck();
        $app = new AppToken();
        $token = $app -> get($ac,$se);
        return [
            'token' => $token
        ];
    }
}
