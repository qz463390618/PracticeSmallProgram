<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-08
 * 时间: 下午 3:17
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden = ['delete_time','update_time','topic_img_id',];
    public function Img()
    {
        return $this -> belongsTo('Image','topic_img_id','id');
    }

}