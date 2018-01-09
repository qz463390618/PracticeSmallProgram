<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-09
 * 时间: 上午 11:56
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = ['img_id','delete_time','product_id'];

    public function imgUrl()
    {
        return $this -> belongsTo('Image','img_id','id');
    }
}