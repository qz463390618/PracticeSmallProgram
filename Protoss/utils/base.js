
import {Config} from '../utils/config.js';

class Base{
  constructor(){
    this.baseRequestUrl = Config.restUrl; 
  }

  request(params){
    var url = this.baseRequestUrl + params.url;
    if (!params.type){
      params.type = 'GET';
    }
    wx.request({
      url: url,
      data:params.data,
      method: params.type,
      header:{
        'content-type':'applicatuion/json',
        'token':wx.getStorageSync('token'),//获取微信缓存
      },
      //成功返回数据
      success:function(res){
        // if (params.sCallBack){
        //   params.sCallBack(res);
        // }
        params.sCallBack && params.sCallBack(res.data);
      },
      //失败返回提示
      fail:function(err){
        console.log(err);
      }
    })
  }
}

export {Base};