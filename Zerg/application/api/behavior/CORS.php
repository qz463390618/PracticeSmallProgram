<?php
/**
 * 用 PhpStorm编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/2/2
 * 创建时间: 8:28
 * 文件名: CORS.php
 */

namespace app\api\behavior;


class CORS
{
    public function appInit(&$params)
    {
        header('Access-Control-Allow-Origin:*');
        //header('Access-Control-Allow-Headers:token,Origin,X-Requested-With,Content-Type,Accept');
        header('Access-Control-Allow-Headers:token,Origin,X-Requested-With,Content-Type,Accept');
        header('Access-Control-Allow-Method:POST,GET');
        if(request() -> isOptions())
        {
            exit();
        }
    }
}