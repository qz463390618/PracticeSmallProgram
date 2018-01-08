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
    protected $hidden = ['delete_time','update_time','from','create_time','pivot'];

    public function getMainImgUrlAttr($value,$data)
    {
        return $this -> prefixImgUrl($value,$data);
    }

    public static function getMostRecent($count)
    {
        $products = self::limit($count)
                    ->order('create_time desc')
                    ->select();
        return $products;
    }

    public static function getProductByCategoryID($categoryID)
    {
        $products = self::where('category_id','=',$categoryID)->select();
        return $products;
    }
}
