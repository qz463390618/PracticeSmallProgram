<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-09
 * 时间: 下午 4:58
 */

namespace app\api\controller\v1;


use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address
{
    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();
        //根据token获取uid
        //根据uid查找用户数据,判断用户是否存在,如果不存在抛出异常,
        //获取用户从客户端提交来的地址信息
        //根据用户地址信息是否存在,从而判断是添加地址还是更新地址
        $uid = TokenService::getCurrentUid();

        $user = UserModel::get($uid);
        if(!$user)
        {
            throw new UserException();
        }
        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user -> address;
        if(!$userAddress)
        {
            //如果查询用户不存在地址属性则新增
            $user -> address()->save($dataArray);
        }else{
            $user -> address -> save($dataArray);
        }
        return json(new SuccessMessage(),201);
    }
}
