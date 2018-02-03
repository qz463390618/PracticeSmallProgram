<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/2
 * 创建时间: 22:24
 * 文件名:Banner.php
 */

namespace app\api\model;


class Banner extends BaseModel
{

    //php think optimize:schema   生成数据库缓存信息



    //自定义表名
    //protected $table = 'banner_item';
    protected $hidden = ['delete_time','update_time'];
    public function items()
    {
        return $this -> hasMany('BannerItem','banner_id','id');
    }
    public static function getBannerByID($id)
    {
        $banner = self::with(['items','items.img'])->find($id);
        return $banner;
        /*//TODO:根据 ID号 获取Banner信息
        try
        {
            1/0;

        }catch (Exception $e)
        {
            //TODO:可以记录日志
            throw $e;
        }
        return 'this is banner info';
        return null;
        //直接使用sql语句进行查询数据
        //$result = Db::query('select * from banner_item where banner_id = ?',[$id]);

        //查询构建器使用
        $result = Db::table('banner_item')->where('banner_id' ,'=' , $id)
        ->select();
        //where('字段名','表达式','查询条件')
        //表达式 / 数组法 / 闭包

        //where闭包写法
        $result = Db::table('banner_item')
            ->where(function ($query) use ($id){
                $query -> where('banner_id' ,'=' , $id);
            })
            ->select();
        //var_dump($result);die;
        return $result;*/
    }
}
