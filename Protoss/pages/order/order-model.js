import {Base} from '../../utils/base.js';

class Order extends Base{
  constructor(){
    super();
    this._storageKeyName = 'newOrder';
  }
  
  //生成订单
  doOrder(orderProducts,callback){
    var that = this;
    var param = {
      url:'order?XDEBUG_SESSION_START=18450',
      type:'post',
      data:{products:orderProducts},
      sCallback:function(data){
        that.execSetStorageSync(true);
        callback && callback(data);
      },
      eCallback:function(){

      }
    };
    this.request(param);
  }

  /**
   * 本地缓存  保存  更新
   */
  execSetStorageSync(data){
    wx.setStorageSync(this._storageKeyName, data);
  }

  /**
   * 拉起微信支付
   * params:
   * orderNumber - {int} 订单id
   * return:
   * callback - {obj} 回调方法,返回参数 可能值 0:商品缺货等原因导致订单不能支付;1:支付失败或者支付取消; 2:支付成功
   */
  execPay(orderNumber,callback){
    var allParams = {
      url :'pay/pre_order',
      type:'post',
      data:{id:orderNumber},
      sCallback:function(data){
        var timeStmp = data.timeStamp;
        if(timeStmp){ //可以支付
          wx.requestPayment({
            'timeStamp':timeStmp.toString(), //时间戳
            'nonceStr':data.nonceStr, //随机字符串
            'package':data.package, //
            'signType':data.signType, //签名算法
            'paySign':data.paySign, //前面 
            success:function(){
              callback && callback(2);
            },
            fail:function(){
              callback && callback(1);
            }
          });
        }else{
          callback && callback(0)
        }
      },
    };
    this.request(allParams);
  }

  //获取订单的具体内容
  getOrderInfoById(id,callback){
    var that = this;
    var allParams = {
      url:'order/'+id,
      sCallback:function(data){
        callback && callback(data);
      },
      eCallback:function(){

      }
    };
    this.request(allParams);
  }

  //获取所有订单,pageIndex 从 1开始
  getOrders(pageIndex,callback){
    var allParams = {
      url:'order/by_user',
      data:{page:pageIndex},
      type:'get',
      sCallback:function(data){
        callback && callback(data);
      }
    };
    this.request(allParams);
  }

  //是否有新的订单
  hasNewOrder(){
    var flag = wx.getStorageSync(this._storageKeyName);
    return flag = true;
  }
}

export {Order};