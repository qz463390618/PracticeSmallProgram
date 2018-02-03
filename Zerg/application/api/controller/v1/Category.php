<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-08
 * 时间: 下午 3:16
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Category as CategoryModel;
use app\api\validate\PagingParameter;
use app\lib\exception\CategoryException;

class Category extends BaseController
{
    //小程序获取所有分类
    public function getAllCategories()
    {
        $categories = CategoryModel::all([],'img');
        if($categories -> isEmpty())
        {
            throw new CategoryException();
        }
        return $categories;
    }

    //分页查询所有分类
    public function getSummary($page = 1,$size = 1)
    {
        (new PagingParameter()) ->goCheck();
        $pagingCategories = CategoryModel::getSummaryByPage($page,$size);
        return $pagingCategories;
    }

    //获取总页数
    public function getListCount($size){

        $count = CategoryModel::countPage($size);
        return $count;
    }
}