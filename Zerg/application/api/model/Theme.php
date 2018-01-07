<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/7
 * 创建时间: 20:37
 * 文件名:Theme.php
 */

namespace app\api\model;


class Theme extends  BaseModel
{
    //需要隐藏的字段
    protected $hidden = ['delete_time','update_time','topic_img_id','head_img_id'];

    //与img表一对一的关系定义
    public function topicImg()
    {
        return $this -> belongsTo('Image','topic_img_id','id');
    }

    public function headImg()
    {
        return $this -> belongsTo('Image','head_img_id','id');
    }

    public function products()
    {
        return $this -> belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static function getThemeWithProducts($id)
    {
        $theme = self::with(['products','topicImg','headImg'])->find($id);
        return $theme;
    }
}
