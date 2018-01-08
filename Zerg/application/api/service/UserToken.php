<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-08
 * 时间: 下午 5:27
 */

namespace app\api\service;


use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;
class UserToken
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        $this -> code = $code;
        $this -> wxAppID = config('wx.app_id');
        $this -> wxAppSecret = config('wx.app_secret');
        /*
         * sprintf()函数说明
         * 把百分号（%）符号替换成一个作为参数进行传递的变量：
         * */
        //拼接微信获取openid路由
        $this -> wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this ->wxAppSecret,$this -> code);
    }

    /**
     * @param $code
     * @throws Exception
     * 登录
     *
     */
    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if(empty($wxResult))
        {
            throw new Exception('获取session_key及openID时异常,微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail)
            {
                $this -> processLoginError($wxResult);
            }else{
                $this -> granToken($wxResult);
            }
        }
    }

    /**
     * 生成令牌
     */
    private function granToken($wxResult)
    {
        //拿到openID
        $openID = $wxResult['openid'];
        //查看数据库这个openID是否存在
        $user = UserModel::getByOpenID($openID);
        if($user)
        {
            $uid = $user -> id;
        }else{
            $uid = $this -> newUser($openID);
        }
        //如果存在这个用户已经生成,如果不存在则新增一条user记录
        //生成令牌 准备缓存数据,写入缓存
        $cachedValue = $this -> prepareCachedValue($wxResult,$uid);
        //把令牌返回到客户端去
        //key:令牌
        //value:wxResult,uid,scope
    }
    private  function saveToCache($cachedValue)
    {
        $key = generateToken();
    }

    private function prepareCachedValue($wxResult,$uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = 16;
        return $cachedValue;
    }

    //插入user一条数据
    private function newUser($openID)
    {
        $user = UserModel::create([
            'openid' => $openID,
        ]);
        return $user -> id;
    }
    // 处理微信登陆异常
    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}
