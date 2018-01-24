// pages/product/product.js
import {Product} from 'product-model.js';
import { Cart } from '../cart/cart-model.js';
var product = new Product();
var cart = new Cart();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:null,
    countsArray:[1,2,3,4,5,6,7,8,9,10],
    productCounts:1,
    currentTabsIndex:0,
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
      console.log(cart.getCartTotalCounts());
      this.setData({
        'cartTotalCounts': cart.getCartTotalCounts(),
        'productInfo': res
      });
    });
  },

  //当选项卡的值发生改变事的函数
  bindPickerChange:function(event){
    var index = event.detail.value;
    var selectedCount = this.data.countsArray[index];
    this.setData({
      'productCounts':selectedCount
    });
  },
  
  //当点击选项卡时的函数
  onTapsItemTab:function(event){
    var index = product.getDataSet(event,'index');
    this.setData({
      currentTabsIndex:index
    });
  },

  //添加商品到购物车
  onAddingToCartTap:function(event){
    this.addToCart();
    //var counts = this.data.cartTotalCount + this.data.productCounts;
    this.setData({
      'cartTotalCounts': cart.getCartTotalCounts()
    });
  },
  //添加商品到购物车
  addToCart:function(){
    var tempObj = {};
    var keys = ['id','name','main_img_url','price'];
    for (var key in this.data.productInfo){
      if(keys.indexOf(key) >= 0){
        tempObj[key] = this.data.productInfo[key];
      }
    }
    cart.add(tempObj,this.data.productCounts);
  },
  
  //跳转到购物车页面
  onCartTap:function(event){
    wx.switchTab({
      url: '/pages/cart/cart',
    })
  },
})