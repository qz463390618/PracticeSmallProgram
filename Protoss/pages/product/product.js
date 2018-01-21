// pages/product/product.js
import {Product} from 'product-model.js';
var product = new Product();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:null
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.data.id = options.id;
    this._loadData();
  },

  //加载所有数据
  _loadData:function()
  {
    product.getDetailInfo(this.data.id,(res)=>{
      this.setData({
        'productInfo': res
      });
    });
  }
})