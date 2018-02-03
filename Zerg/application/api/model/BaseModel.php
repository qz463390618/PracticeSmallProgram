<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    //定义读取器
    protected function prefixImgUrl($value,$data)
    {
        $finalUrl = $value;
        if($data['from'] == 1)
        {
            $finalUrl = config('setting.img_prefix').$value;
            //return config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }

    /**
     * 获取总页数
     * @param int $size  每页显示的个数
     */
    public static  function countPage($size = 10)
    {
        $countLimit = self::count();
        if(!$countLimit){
            return 0;
        }
        return ceil($countLimit / $size); //进一取整
    }
}
