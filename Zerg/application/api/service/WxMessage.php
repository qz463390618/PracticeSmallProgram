<?php
/**
 * 用 PhpStorm编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/2/1
 * 创建时间: 16:20
 * 文件名: WxMessage.php
 */

namespace app\api\service;


use think\Exception;

class WxMessage
{
    private $sendUrl = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=%s";
    private $touser;
    private $color = 'black';

    protected $tplID;
    protected $page;
    protected $formID;
    protected $data;
    protected $emphsisKeyWord;


    public function __construct()
    {
        $accessToken = new AccessToken();
        $token = $accessToken -> get();
        $this -> sendUrl = sprintf($this ->sendUrl,$token);
    }

    protected function sendMessage($openId)
    {
        $data = [
            'touser' => $openId,
            'template_id' => $this -> tplID,
            'page' => $this -> page,
            'formID' => $this -> formID,
            'data' => $this -> data,
            'emphsis_keyword' => $this -> emphsisKeyWord,
        ];
        $result = curl_post($this -> sendUrl,$data);
        $result = json_decode($result,true);
        if($result['errcode'] == 0)
        {
            return true;
        }else{
            throw new Exception('模版消息发送失败'.$result['errmsg']);
        }

    }
}