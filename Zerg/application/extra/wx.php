<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/8
 * 创建时间: 20:22
 * 文件名:wx.php
 */
return [
    //小程序app_id
    'app_id' =>'wxaedb6d0305046f35',
    //小程序 app_secret
    'app_secret' =>'9581bef762dc77bd5234ab0b392fceb1',
    //微信使用code换取用户openid及session_key的url地址
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
];
