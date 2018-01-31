
import {Comfig} from './config.js';
import { Base } from './base.js';
class Address extends Base{
  constructor(){
    super();
  }
  setAddressInfo(res){
    var province = res.provinceName || res.province;
    var city = res.cityName || res.city;
    var county = res.countyName || res.country;
    var detail = res.detailInfo || res.detail;

    var totalDetail = city + county + detail;
    if(!this.isCenterCity(province)){
      totalDetail = province + totalDetail;
    }
    return totalDetail;
  }

  // 是否为直辖市
  isCenterCity(name){
    var centerCitys = ['北京市','天津市','上海市','重庆市'];
    var flag = centerCitys.indexOf(name) >= 0;
    return flag;
  }

  //更新保存地址
  submitAddress(data,callback){
    data = this._setUpAddress(data);
    console.log(data);
    var param = {
      url:'address',
      type:'POST',
      data:data,
      sCallback:function(res){
        callback && callback(true,res);
      },
      eCallback:function(res){
        callback && callback(false, res);
      }
    };
    this.request(param);
  }

  // 保存地址
  _setUpAddress(res){
    var fromData = {
      name:res.userName,
      province:res.provinceName,
      city: res.cityName,
      country:res.countyName,
      mobile:res.telNumber,
      detail:res.detailInfo
    };
    return fromData;
  }

  // 获得我自己的收货地址
  getAddress(callback){
    var that = this;
    var param = {
      url:'address',
      sCallback:function(res){
        if(res){
          res.totalDetail = that.setAddressInfo(res);
          callback && callback(res);
        }
      }
    };
    this.request(param);
  }
}

export {Address};