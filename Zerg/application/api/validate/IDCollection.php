<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/7
 * 创建时间: 20:56
 * 文件名:IDCollection.php
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];
    protected $message = [
        'ids' => 'ids参数必须是以逗号分隔的多个正整数',
    ];
    protected function checkIDs($value,$rule = '',$data = '',$field = '')
    {
        $values = explode(',',$value);
        if(empty($values))
        {
            return false;
        }
        foreach($values as $id)
        {
            if(!$this -> isPositiveInteger($id))
            {
                return false;
            }
        }
        return true;
    }
}
