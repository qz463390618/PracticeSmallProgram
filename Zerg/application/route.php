<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

/*
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
*/

//轮播图接口
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
//首页主题推荐
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
//主题详情
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');
//获取某个分类里的所有商品
Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
//新品推荐
Route::get('api/:version/product/recent','api/:version.Product/getRecent');
Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id' => '\d+']);
//获取所有分类
Route::get('api/:version/category/all','api/:version.Category/getAllCatrgories');
//获取Token
Route::post('api/:version/token/user','api/:version.Token/getToken');
//新增或修改地址
Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');

//下单
Route::post('api/:version/order','api/:version.Order/placeOrder');

//预订单
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPerOrder');


//测试删除
Route::post('api/:version/order/del','api/:version.Order/del');
