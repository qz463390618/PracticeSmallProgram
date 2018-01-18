//使用ES6构建面向对象的js代码  ES6(2016)
class Home{
  constructor(){

  }

  getBannerData(id,callBack){
    wx.request({
      url: "http://zero.com/api/v1/banner/" + id,
      method:'GET',
      success:function(res){
        //return res;
        callBack(res);
      }
    })
  }
}

export {Home};