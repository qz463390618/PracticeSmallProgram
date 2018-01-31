<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-16
 * 时间: 下午 5:57
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\model\Product as ProductModel;
use app\api\service\Order as OrderService;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
class WxNotify extends \WxPayNotify
{

    public function NotifyProcess($data, &$msg)
    {
        //判断支付成功
        //file_put_contents('./a.php',json_encode($data));
        if($data['result_code'] == 'SUCCESS')
        {
            //取出订单号
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try{
                $order = OrderModel::where('order_no','=',$orderNo)
                    //->lock(true)   //给数据表加锁
                    ->find();
                if($order -> status == 1)
                {
                    $service = new OrderService();
                    $stockStatus = $service -> checkOrderStock($order -> id);
                    if($stockStatus['pass'])
                    {
                        $this -> updateOrderStatus($order -> id,true);
                        $this -> reduceStock($stockStatus);
                    }else{
                        $this -> updateOrderStatus($order -> id,false);
                    }
                }
                Db::commit();
                return true;
            }catch (Exception $e){
                Db::rollback();
                file_put_contents('./a.php',$e);
                Log::error($e);
                return false;
            }
        }else{
            return true;
        }


    }

    private function updateOrderStatus($orderID,$success)
    {
        $status = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id','=',$orderID)->update([
            'status' => $status
        ]);
    }

    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus)
        {
            ProductModel::where('id','=',$singlePStatus['id'])
                ->setDec('stock',$singlePStatus['counts']);
        }
    }
}
