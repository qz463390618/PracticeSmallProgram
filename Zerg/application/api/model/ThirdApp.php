<?php
/**
 * 用 PhpStorm编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/2/1
 * 创建时间: 10:49
 * 文件名: ThirdApp.php
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
    public static function check($ac,$se){
        $app = self::where('app_id','=',$ac)
                ->where('app_secret','=',$se)
                ->find();
        return $app;
    }
}