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
        ':id'   => ['order/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['order/hello', ['method' => 'post']],
    ],

];
*/


//php think optimize:route


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
//Route::get('api/:version/category/all','api/:version.Category/getAllCategories');
//分页获取所有分类
Route::get('api/:version/category/page_all','api/:version.Category/getSummary');

//分类
Route::group('api/:version/category',function(){
    //小程序获取所有的分类
    Route::get('all','api/:version.Category/getAllCategories');
    //cms获取所有的分类并分页
    Route::get('page_all','api/:version.Category/getSummary');
    //获取分类页的总页数
    Route::get('count_page','api/:version.Category/getListCount');
});

//获取Token
Route::post('api/:version/token/user','api/:version.Token/getToken');
//获取第三方应用Token
Route::post('api/:version/token/app','api/:version.Token/getAppToken');
//验证Token
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');
//新增或修改地址
Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/address','api/:version.Address/getUserAddress');

//下单
Route::post('api/:version/order','api/:version.Order/placeOrder');
//获取某个订单的详情
Route::get('api/:version/order/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);
//获取用户所有订单
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/paginate','api/:version.Order/getSummary');
Route::post('api/:version/order/delivery','api/:version.Order/delivery');

//预订单
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPerOrder');
//微信回调地址
Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');

Route::post('api/:version/pay/re_notify','api/:version.Pay/redirectNotify');


