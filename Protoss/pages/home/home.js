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
    //获取banner信息(轮播图)
    home.getBannerData(id,(res)=>{
      this.setData({
        'bannerArr':res
      });
    });

    //获取主题信息
    home.getThemeData((res)=>{
      this.setData({
        'themeArr':res
      });
    });

    //获取商品列表数据
    home.getProductsData((res)=>{
      this.setData({
        'productsArr':res
      });
    });
  },

  //回调方法  无用了,留着做记录
  callBack:function(res){
    console.log(res);
  },

  //跳转商品详情
  onProductsItemTap:function(event){
    var id = home.getDataSet(event,'id');
    wx.navigateTo({
      url: '../product/product?id=' + id,//页面跳转路径
    });
  },

  //跳转专题
  onThemesItemTap:function(event){
    var id = home.getDataSet(event, 'id');
    var name = home.getDataSet(event, 'name');
    wx.navigateTo({
      url: '../theme/theme?id=' + id + '&name=' + name,
    })
  }
  
})