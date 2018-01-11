<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/11
 * 创建时间: 21:56
 * 文件名:BaseController.php
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    //前置方法,验证用户是否包含权限
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    //前置方法,验证用户是否只有普通用户权限
    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }
}
