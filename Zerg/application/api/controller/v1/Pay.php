<?php
/**
 * 用 PhpStorm 编辑器
 * 用户: 曾伟峰
 * 创建日期: 2018/1/14
 * 创建时间: 21:51
 * 文件名:Pay.php
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePostiveInt;
use app\api\service\Pay as PayService;
class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' =>'getPerOrder']
    ];

    public function getPerOrder($id = '')
    {
        (new IDMustBePostiveInt()) -> goCheck();
        $pay = new PayService($id);
        return $pay ->pay();
    }

    //接收微信的通知
    public function receiveNotify()
    {
        //通知频率为 15/15/30/180/1800/1800/1800/1800/3600  单位:秒

        // 1.检查库存量,超卖
        // 2.更新这个订单的status状态
        // 3.减库存
        // 如果成功处理,我们返回微信成功处理的信息  否者,我们需要返回没有成功处理信息

        //特点 :post请求;  xml格式;  不能携带参数;
        $notify = new WxNotify();
        $notify -> Handle();

    }

    //接收微信的通知
    public function receiveNotify1()
    {
        //通知频率为 15/15/30/180/1800/1800/1800/1800/3600  单位:秒

        // 1.检查库存量,超卖
        // 2.更新这个订单的status状态
        // 3.减库存
        // 如果成功处理,我们返回微信成功处理的信息  否者,我们需要返回没有成功处理信息

        //特点 :post请求;  xml格式;  不能携带参数;
        //$notify = new WxNotify();
        //$notify -> Handle();


        $xmlData = file_get_contents('php://input');
        file_put_contents('./a.php',$xmlData);
        $result = curl_post_raw('http://zero.com/api/v1/pay/re_notify?XDEBUG_SESSION_START=11254',$xmlData);

    }
}
