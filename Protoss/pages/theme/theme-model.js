import {Base} from '../../utils/base.js';

class Theme extends Base{
  constructor(){
    super();
  }

  //获取主题下的商品列表
  /* id:对应主题的id号 */
  getProductsData(id,callback){
    var param = {
      'url':'theme/'+id,
      sCallback:function(res){
        callback && callback(res);
      }
    };
    this.request(param);
  }
}

export {Theme};