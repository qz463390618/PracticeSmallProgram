
import { Config } from '../utils/config.js';
import {Token} from './token.js';

class Base{
  constructor(){
    this.baseRequestUrl = Config.restUrl; 
  }

  //重新封装 wx.request()方法
  request(params){
    var that = this;
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
        var code = res.statusCode.toString();
        var startChar = code.charAr(0);
        if(startChar == '2'){
          params.sCallback && params.sCallback(res.data);
        }else{ //这里指的是这次请求到了服务器内部,但是因为一些传入参数或者一些问题而出现的出错
          if(code == '401'){ //令牌出现问题
            //重新获取完令牌后,再次去调用用户所访问的路由
            that._refetch(params);
          }
          params.eCallback && params.eCallback(res.data);
        }
      },
      //失败返回提示
      fail:function(err){ //指的是此次调用根本不成功的 没有进入服务器中
        console.log(err);
      }
    })
  }

  _refetch(params){
    var token = new Token();
    token.getTokenFromServer((token) => {
      this.request(params)
    });
  }



  //获得元素上的绑定的值
  getDataSet(event,key){
    return event.currentTarget.dataset[key];
  }

}

export {Base};