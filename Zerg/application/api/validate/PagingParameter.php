<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-18
 * 时间: 下午 1:56
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' =>'isPositiveInteger',
        'size' =>'isPositiveInteger',
    ];

    protected $message = [
        'page' =>'分页参数必须是正整数',
        'size' =>'分页参数必须是正整数',
    ];
}