<?php
/**
 * 用 PhpStorm编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/2/1
 * 创建时间: 17:17
 * 文件名: AccessToken.php
 */

namespace app\api\service;


use think\Exception;

class AccessToken
{
    private $tokenUrl;
    const TOKEN_CACHE_KEY = 'access';
    const TOKEN_EXPIRE_IN = 7000;

    public function __construct()
    {
        $url = config('wx.access_token_url');
        $url = sprintf($url,config('wx.app_id'),config('wx.app_secret'));
        $this -> tokenUrl = $url;
    }

    /**
     * 建议用户规模小时每次直接去微信服务器取最新的token
     * 但微信access_token接口获取是有限制的 2000/1天
     */
    public function get()
    {
        $token = $this -> getFromCache();
        if(!$token)
        {
            return $this -> getFromWxServer();
        }else{
            return $token;
        }
    }

    //从缓存中获取access_token
    private function getFromCache()
    {
        $token = cache(self::TOKEN_CACHE_KEY);
        if(!$token)
        {
            return $token;
        }
        return null;
    }

    //从微信接口获取access_token
    private function getFromWxServer()
    {
        $token = curl_get($this -> tokenUrl);
        $token = json_decode($token,true);
        if(!$token)
        {
            throw new Exception('获取AccessToken异常');
        }
        if(!empty($token['errcode']))
        {
            throw new Exception($token['errmsg']);
        }

        $this -> saveToCache($token);
        return $token['access_token'];
    }

    private function saveToCache($token)
    {
        cache(self::TOKEN_CACHE_KEY,$token,self::TOKEN_EXPIRE_IN);
    }
}