<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/2
 * 创建时间: 21:19
 * 文件名:BaseValidate.php
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        //获取http传入的参数
        $request = Request::instance();
        $params = $request -> param();
        //对这些参数来检验
        $result = $this ->batch() ->  check($params);
        if(!$result)
        {
            $e = new ParameterException([
                'msg' => $this -> error
            ]);
            throw $e;
        }else
        {
            return true;
        }
    }

    protected function isPositiveInteger($value,$rule = '',$data = '',$field = '')
    {
        //判断value是不是一个数字,判断value是不是整型,判断value大于0
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0  )
        {
            return true;
        }else{
            return false;
            //return $field.'必须是正整数';
        }
    }

    protected function isEmpty($value,$rule = '',$data = '',$field = '')
    {
        if(empty($value))
        {
            return flase;
        }else{
            return true;
        }
    }
}
