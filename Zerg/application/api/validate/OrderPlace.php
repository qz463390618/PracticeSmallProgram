<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-12
 * 时间: 下午 2:27
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use app\lib\exception\ProductException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts'
    ];
    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];
    protected function checkProducts($values)
    {
        if(!is_array($values))
        {
            throw new ProductException([
                'msg' => '商品参数不正确'
            ]);
        }
        if(empty($values))
        {
            throw new ProductException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach($values as $value)
        {
            $this -> checkProduct($value);
        }
        return true;
    }

    protected function checkProduct($value)
    {
        $validate = new BaseValidate($this -> singleRule);
        $result = $validate -> check($value);
        if(!$result)
        {
            throw new ParameterException([
                'msg' => '商品列表参数错误'
            ]);
        }
    }
}