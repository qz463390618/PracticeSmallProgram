// pages/theme/theme.js
//引入分类模型
import {Theme} from 'theme-model.js';
var theme = new Theme();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:null,
    name:null
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.data.id = options.id;
    this.data.name = options.name;
    this._loadData();
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    wx.setNavigationBarTitle({
      title: this.data.name,
    })
  },

  //加载所有数据
  _loadData:function(){
    theme.getProductsData(this.data.id,(res)=>{
      this.setData({
        'themeInfo':res
      });
    });
  }
})