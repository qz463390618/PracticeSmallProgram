// pages/home/home.js
//ES6引入文件
import {Home} from 'home-model.js';
var home = new Home();//实例化 首页 对象
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  //初始化页面生命周期函数
  onLoad:function(){
    this._loadData();
  },

  //加载所有数据
  _loadData:function(){
    var id = 1;
    var data = home.getBannerData(id,(res)=>{
      console.log(res);
    });
  },

  callBack:function(res){
    console.log(res);
  }

  
})