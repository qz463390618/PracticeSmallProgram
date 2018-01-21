//使用ES6构建面向对象的js代码  ES6(2016)
import {Base} from '../../utils/base.js';
class Home extends Base{
  constructor(){
    super();
  }

  //获取轮播图信息
  getBannerData(id,callback){
    var param = {
      url:'banner/'+id,
      sCallback:function(res){
        callback && callback(res.items);
      }
    };
    this.request(param);
  }
  //获取主题信息
  getThemeData(callback){
    var param = {
      url:'theme?ids=1,2,3',
      sCallback:function(res){
        callback && callback(res);
      }
    };
    this.request(param);
  }

  //获取商品列表信息
  getProductsData(callback){
    var param = {
      url:'product/recent',
      sCallback:function(res){
        callback && callback(res);
      }
    }
    this.request(param);
  }
  

}

export {Home};