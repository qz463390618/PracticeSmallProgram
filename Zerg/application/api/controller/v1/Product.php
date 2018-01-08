<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-08
 * 时间: 下午 2:15
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ProductException;

class Product
{
    public function getRecent($count = 15)
    {
        (new Count()) -> goCheck();
        $products = ProductModel::getMostRecent($count);
        if($products -> isEmpty())
        {
            throw  new ProductException();
        }
        //临时隐藏某个值
        $products = $products -> hidden(['summary']);
        return $products;
    }

    public function getAllInCategory($id)
    {
        (new IDMustBePostiveInt()) -> goCheck();
        $products = ProductModel::getProductByCategoryID($id);
        if($products -> isEmpty())
        {
            throw new ProductException();
        }
        return $products;
    }
}