$(function(){

    //判断浏览器本地是否有token令牌和过期
    if(!window.base.getLocalStorage('token')){
        window.location.href = '/pages/login.html';
    }

    //当前页数
    var pageIndex=1,
        //数据是否未加载完
        moreDataFlag=true,
        //每页读取的个数
        size = 10,
        countPage;
    getCategories(pageIndex);
    getListCount();

    /*
    * 获取数据 分页
    * params:
    * pageIndex - {int} 分页下表  1开始
    */
    function getCategories(pageIndex){
        var params = {
            url:'category/page_all',
            data:{page:pageIndex,size:size},
            tokenFlag:true,
            sCallback:function(res){
                var str = getCategoryHtmlStr(res);
                //$('#table-content').append(str);
                $('#table-content').html(str);
            }
        };
        window.base.getData(params);
    }

    /*拼接字符串*/
    function getCategoryHtmlStr(res){
        var data = res.data;
        if(data){
            var len = data.length;
            var str = '',item;
            if(len > 0){
                for (var i = 0; i< len ; i++){
                    item = data[i];
                    str += '<tr>' +
                        '<td>'+item.id+'</td>' +
                        '<td>'+item.name+'</td>' +
                        '<td>'+item.create_time+'</td>' +
                        '<td><span class="category-btn detail" data-id="'+item.id+'">详情</span>' +
                        '<span data-id="'+item.id+'" class="category-btn done">查看分类所有商品</span>' +
                        '<span data-id="'+item.id+'" class="category-btn unstock">删除</span></td>' +
                        '</tr>';
                }
            }else{
                ctrlLoadMoreBtn();
                moreDataFlag=false;
            }
            return str;
        }
        return '';
    }

    /*控制加载更多按钮的显示*/
    function ctrlLoadMoreBtn(){
        if(moreDataFlag) {
            $('.load-more').hide().next().show();
        }
    }

    /*获取这个列表的总页数*/
    function getListCount(){
        var params = {
            url:'category/count_page',
            data:{size:size},
            tokenFlag:true,
            sCallback:function(res){
                countPage = res;
                $(".paging").Page({
                    Pages:countPage,
                    PageOn:function(e){
                        pageIndex = e;
                        getCategories(pageIndex);
                    },
                    JumpOn:function(e){
                        pageIndex = e;
                        getCategories(pageIndex);
                    },
                    ActiveClass:"paging-selecte",
                });
            }
        };
        window.base.getData(params);
    }


});