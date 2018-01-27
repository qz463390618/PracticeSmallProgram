//app.js
import {Token} from './utils/token.js';
App({
  //小程序初始化调用
  onLaunch:function(){
    var token = new Token();
    token.verify();
  },
})