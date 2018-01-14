<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/14
 * 创建时间: 21:51
 * 文件名:Pay.php
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' =>'getPerOrder']
    ];

    public function getPerOrder($id = '')
    {
        (new IDMustBePostiveInt()) -> goCheck();
    }
}
