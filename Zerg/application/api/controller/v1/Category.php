<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-08
 * 时间: 下午 3:16
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    public function getAllCatrgories()
    {
        $categories = CategoryModel::all([],'img');
        if($categories -> isEmpty())
        {
            throw new CategoryException();
        }
        return $categories;
    }


}