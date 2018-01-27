// pages/cart/cart.js
import {Cart} from 'cart-model.js';
var cart = new Cart();
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 当页面被隐藏的时候
   */
  onHide: function () {
    cart.execSetStorageSync( this.data.cartData)
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var cartData = cart.getCartDataFromLocal();
    //var countsInfo = cart.getCartTotalCounts(true);
    var cal = this._calTotalAccountAndCounts(cartData);
    this.setData({
      'selectedTypeCounts': cal.selectedTypeCounts,
      'selectedCounts': cal.selectedCounts,
      'account':cal.account,
      'cartData' : cartData
    });
  },

  //计算订单总金额
  _calTotalAccountAndCounts:function(data){
    var len = data.length;
    var account = 0;//计算的总价格  排除掉未选择的商品
    var selectedCounts = 0; //商品数量的总和
    var selectedTypeCounts = 0; //商品类目的总和
    let multiple = 100;

    for (let i = 0;i < len; i++){
      if(data[i].selectStatus){
        account += data[i].counts * multiple * Number(data[i].price) * multiple;
        selectedCounts += data[i].counts;
        selectedTypeCounts++;
      }
    }
    return {
      'selectedCounts': selectedCounts,
      'selectedTypeCounts' : selectedTypeCounts,
      'account': account / (multiple * multiple)
    };
  },

  /**
   * 商品选中按钮 事件
   */
  toggleSelect:function(event){
    var id = cart.getDataSet(event, 'id');
    var status = cart.getDataSet(event,'status');
    var index = this._getProductIndexByID(id);

    //修改状态
    this.data.cartData[index].selectStatus = !status;
    //调用重新计算方法
    this._resetCartData();
  },
  /**
   * 全选按钮事件
   */
  toggleSelectAll:function(event){
    var status = cart.getDataSet(event,'status') == 'true';
    var data = this.data.cartData;
    var len = data.length;
    for (let i = 0;i < len; i++){
      data[i].selectStatus = !status;
    }
    //调用重新计算方法
    this._resetCartData();
  },
  //加载数据
  _loadData:function(){

  },

  /**
   * 根据商品 id 得到商品所在微信缓存的下标
   */
  _getProductIndexByID:function(id){
    var data = this.data.cartData;
    var len = data.length;
    for(let i = 0;i<len;i++){
      if(data[i].id == id){
        return i;
      }
    }
  },

  /**
   * 重新计算总金额和商品总数
   */
  _resetCartData:function(){
    var newData = this._calTotalAccountAndCounts(this.data.cartData);
    this.setData({
      'selectedTypeCounts': newData.selectedTypeCounts,
      'selectedCounts': newData.selectedCounts,
      'account': newData.account,
      'cartData': this.data.cartData
    });
  },

  //修改商品数量
  changeCounts:function(event){
    var id = cart.getDataSet(event, 'id');
    var type = cart.getDataSet(event,'type');
    var index = this._getProductIndexByID(id);
    var counts = 1;
    
    if(type == 'add'){
      cart.addCounts(id);
    }else{
      counts = -1;
      cart.cutCounts(id);
    }
    this.data.cartData[index].counts += counts;
    //调用重新计算方法
    this._resetCartData();
  },

  //删除商品
  delete:function(event){
    var id = cart.getDataSet(event,'id');
    var index = this._getProductIndexByID(id);

    this.data.cartData.splice(index,1);//删除某一项商品
    //调用重新计算方法
    this._resetCartData();
    cart.delete(id);
  },

  //查看商品详情
  onProductsItemTap:function(event){

  },


  //创建订单
  submitOrder:function(event){
    wx.navigateTo({
      url: '../order/order?account=' + this.data.account +'&from=cart',
    })
  },
})