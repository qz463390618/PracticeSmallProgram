<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/2
 * 创建时间: 20:38
 * 文件名:IDMustBePostiveInt.php
 */

namespace app\api\validate;



class IDMustBePostiveInt extends BaseValidate
{
    protected $rule = [
      'id' => 'require|isPositiveInteger'
    ];
    protected $message = [
        'id' => 'id必须是正整数'
    ];

}
