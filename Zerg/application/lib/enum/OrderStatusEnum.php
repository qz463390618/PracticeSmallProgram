<?php
/**
 * 使用编辑器 PhpStorm.
 * 用户: 曾伟峰
 * 日期: 2018-01-15
 * 时间: 下午 1:45
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //待支付
    const UNPAID = 1;

    //已支付
    const  PAID = 2;

    //已发货
    const DELIVERED = 3;

    //已支付,但库存不足
    const PAID_BUT_OUT_OF = 4;
}