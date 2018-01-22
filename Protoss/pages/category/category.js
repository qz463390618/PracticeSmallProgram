// pages/category/category.js
import {Category} from './category-model.js';
var category = new Category();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    currentMenuIndex : 0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this._loadData();
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },


  //加载所有数据
  _loadData:function(){
    category.getCategoryType((categoryData) =>{
      this.setData({
        'categoryTypeArr': categoryData,
      });
      //一定在回调函数里面在调用获取分类商品才能正常获取到第一个分类的id
      category.getProductsByCategory(categoryData[0].id, (data) => {
        var dataObj = {
          'products':data,
          'topImgUrl': categoryData[0].img.url,
          'title': categoryData[0].name
        };
        this.setData({
          'categoryProducts': dataObj
        });
      });
    }); 
  },
  
  //点击分类名时查询数据
  onCategoryItemTap:function(event){
    var index = category.getDataSet(event,'index');
    this.setData({
      'currentMenuIndex':index
    });
    console.log(this.data.currentMenuIndex);
    category.getProductsByCategory(this.data.categoryTypeArr[index].id, (data) => {
      var dataObj = {
        'products': data,
        'topImgUrl': this.data.categoryTypeArr[index].img.url,
        'title': this.data.categoryTypeArr[index].name
      };
      this.setData({
        'categoryProducts': dataObj
      });
    });
  },

  //跳转商品详情
  onProductsItemTap: function (event) {
    var id = category.getDataSet(event, 'id');
    wx.navigateTo({
      url: '../product/product?id=' + id,//页面跳转路径
    });
  },

})