import {Base} from '../../utils/base.js';

class Cart extends Base{
 constructor(){
   super();
   this._storageKeyName = 'cart';
 }

 /**
  * 加入购物车
  * 如果之前没有这样的商品,则直接添加一条新的记录,数量为counts
  * 如果有,则只将相应数量 + counts
  * @params:
  * item - {obj} 商品对象
  * counts - {int} 商品数量
  */
 add(item,counts){
  var cartData = this.getCartDataFromLocal();
  var isHasInfo = this._isHasThatOne(item.id,cartData);
  if(isHasInfo.index == -1){
    item.counts = counts;
    item.selectStatus = true;//设置选中状态
    cartData.push(item);
  }else{
    cartData[isHasInfo.index].counts += counts;
  }
  wx.setStorageSync(this._storageKeyName, cartData);
 }

  //从微信缓存中获取购物车数据
 getCartDataFromLocal(flag){
   var res = wx.getStorageSync(this._storageKeyName);
   if(!res){
     res = [];
   }
   if(flag){
     var newRes = [];
     for (let i = 0; i < res.length; i++){
       if(res[i].selectStatus){
         newRes.push(res[i]);
       }
     }
     res = newRes;
   }
   return res;
 }

  /*
   * 判断某个商品是否已经被添加到购物车中,并且返回
   * 这个商品的数据以及所在数组中的序号
  
   */
 _isHasThatOne(id,arr){
    var item,
        result = { 'index': -1 };
    for(var i = 0;i < arr.length; i++){
      item = arr[i];
      if(item.id == id){
        result = {
          'index': i,
          'data':item
        };
        break;
      }
    }
    return result;
 }

  /*
   * 计算购物车内商品总数量
   * falg true 考虑商品选择状态
   */
  getCartTotalCounts(flag){
    var data = this.getCartDataFromLocal();
    var counts = 0;
    for(let i = 0; i < data.length; i++){
      if (flag){
        if(data[i].selectStatus){
          counts += data[i].counts;
        }
      }else{
        counts += data[i].counts;
      }
    }
    return counts;
  }

  /**
   * 修改商品数量
   * params:
   * id - {int} 商品id
   * counts - {int} 数量
   */
  _changeCounts(id,counts){
    var cartData = this.getCartDataFromLocal();
    var hasInfo = this._isHasThatOne(id,cartData);
    if(hasInfo.index != -1){
      if(hasInfo.data.counts > 1){
        cartData[hasInfo.index].counts += counts;
      }
    }
    wx.setStorageSync(this._storageKeyName, cartData) //更新本地缓存
  }

  /**
   * 增加商品数目
   */
  addCounts(id) {
    this._changeCounts(id,1);
  }

  /**
   * 减少商品数目
   */
  cutCounts(id){
    this._changeCounts(id,-1);
  }

  /**
   * 删除商品
   */
  delete(ids){
    if(!(ids instanceof Array)){
      ids = [ids];
    }
    var cartData = this.getCartDataFromLocal();
    for(let i = 0; i < ids.length;i ++){
      var hasInfo = this._isHasThatOne(ids[i], cartData);
      if(hasInfo.index != -1){
        cartData.splice(hasInfo.index,1);//删除数组中的某一项
      }
    }
    wx.setStorageSync(this._storageKeyName, cartData) //更新本地缓存
  }

  /**
   * 本地缓存 保存 更新
   */
  execSetStorageSync(data) {
    wx.setStorageSync(this._storageKeyName,data)
  }
}

export {Cart};