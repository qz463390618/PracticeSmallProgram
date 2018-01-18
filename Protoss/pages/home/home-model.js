//使用ES6构建面向对象的js代码  ES6(2016)
import {Base} from '../../utils/base.js';
class Home extends Base{
  constructor(){
    super();
  }

  getBannerData(id,callBack){
    var params = {
      url:'banner/'+id,
      sCallBack:function(res){
        callBack && callBack(res.items);
      }
    };
    this.request(params);
    // wx.request({
    //   url: "http://zero.com/api/v1/banner/" + id,
    //   method:'GET',
    //   success:function(res){
    //     //return res;
    //     callBack(res);
    //   }
    // })
  }
}

export {Home};