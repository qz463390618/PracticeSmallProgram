<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-08
 * 时间: 下午 2:20
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15',
    ];
    protected $message = [
        'count.between' => 'count只能在 1 - 15 之间',
        'count.isPositiveInteger' =>'count必须是正整数'
    ];
}