<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/14
 * 创建时间: 19:01
 * 文件名:Order.php
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id','delete_time','update_time'];
    protected $autoWriteTimestamp = true;

    //读取器
    public function getSnapItemsAttr($value)
    {
        if(empty($value))
        {
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value)
    {
        if(empty($value))
        {
            return null;
        }
        return json_decode($value);
    }

    public static function getSummaryByUser($uid,$page = 1,$size = 15)
    {
        //Paginator::
        $paginData = self::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($size,true,['page' => $page]);//分页查询
        return $paginData;
    }

}
