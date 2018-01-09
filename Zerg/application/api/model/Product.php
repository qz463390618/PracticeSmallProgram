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

    //获取器,给图片字段加上域名
    public function getMainImgUrlAttr($value,$data)
    {
        return $this -> prefixImgUrl($value,$data);
    }

    //定义商品介绍图片关联关系
    public function imgs()
    {
        return $this ->hasMany('ProductImage','product_id','id');
    }

    //定义商品描述关联关系
    public function properties()
    {
        return $this ->hasMany('ProductProperty','product_id','id');
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

    public static function getProductDetail($id)
    {
        $product = self::with([
                'imgs' => function($query){
                    $query->with(['imgUrl'])->order('order','asc');
                }
            ])
            ->with(['properties'])
            ->find($id);
        return $product;
    }
}
