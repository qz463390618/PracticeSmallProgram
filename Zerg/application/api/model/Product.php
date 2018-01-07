<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/7
 * 创建时间: 20:36
 * 文件名:Product.php
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = ['delete_time','update_time','from','create_time'];
}
