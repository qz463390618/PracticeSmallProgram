
import { Comfig } from './config.js';
class Toekn{
  constructor(){
    this.verifyUrl = Config.resUrl + 'token/verify';
    this.tokenUrl = Config.resUrl + 'token/user';
  }

  /**
   * 检测令牌是否有效
   */
  verify(){
    var token = wx.getStorageSync('token');
    if(!token){
      this.getTokenFromServer();
    }else{
      this._verifyFromServer(token);
    }
  }

  //从服务器中获取token
  getTokenFromServer(callback){
    var that = this;
    var params = {
      url:'token/user?code='
    };
    wx.login({
      success:function(res){
        wx.request({
          url:that.tokenUrl,//在构造函数中定义了
          method:'POST',
          data:{
            code:res.code
          },
          success:function(res){
            wx.setStorageSync('token',res.data.token);
            callback && callback(res.data.token);
          }
        });
      }
    });
  }
  
  //携带令牌去服务器中校验令牌
  _verifyFromServer(token){
    var that = this;
    wx.request({
      url:that.verifyUrl,
      method:'POST',
      data:{
        token:token
      },
      success:function(res){
        var valid = res.data.isValid;
        if (!valid){
          that.getTokenFromServer();
        }
      }
    });
  }
}

export {Token};