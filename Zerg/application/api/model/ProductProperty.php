<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-09
 * 时间: 下午 1:43
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = ['delete_time','product_id','id'];
}