<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2017/12/28
 * 创建时间: 22:11
 * 文件名:Banner.php
 */

namespace app\api\controller\v1;

//引入验证Validate
use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\TestValidate;
use app\lib\exception\BannerMissException;

class Banner
{
    /**
     * 获取指定的id的banner的信息
     * @url /banner/:id
     * @http GET
     * @id  banner的id号
     *
     */
    public function getBanner($id)
    {
        //AOP  面向切面编程

        //验证器,验证id是不是一个正整数
        (new IDMustBePostiveInt()) -> goCheck();
        $banner = BannerModel::getBannerByID($id);
        /*需要隐藏的字段
        $banner -> hidden(['delete_time']);
        需要显示的字段
        $banner -> validate(['id']);*/
        if(!$banner)
        {
            throw new BannerMissException();
            //throw new Exception('内部错误');
        }
        //return json($banner);
        return $banner;
    }




    public function test()
    {
        /*
         * 验证分为;独立验证和验证器
         *
         * */

        $data = [
            'name' => 'vendor55555',
            'email' => 'venterqq.com'
        ];
        $validate  = new TestValidate();
        $result = $validate -> batch() ->  check($data);

        var_dump($result);
        var_dump( $validate -> getError());
    }

}
