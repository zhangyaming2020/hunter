$(document).ready(function(){
	
	//打开子隐藏导航
	$(".header-nav-li").hover(
		function(){
			$(this).find(".child-nav-ul").slideDown(300);
			$(this).find(".header-nav-link-icon").addClass("active");
		},
		function(){
			$(this).find(".child-nav-ul").slideUp(300);
			$(this).find(".header-nav-link-icon").removeClass("active");
		}
	);
	
	//顶部隐藏导航
	$(".child-nav-li,.user-ky-li").hover(
		function(){$(this).addClass("active");},
		function(){$(this).removeClass("active");}
	);
	
	//打开用户子隐藏导航
	$(".user-my").hover(
		function(){$(this).find(".user-ky-link").slideDown(300);},
		function(){$(this).find(".user-ky-link").slideUp(300);}
	);
	
	//返回顶部
	$(window).scroll(function(){
		var bodyheight = document.documentElement.scrollTop || document.body.scrollTop;
		if(bodyheight > 300){
			$(".return-top").fadeIn(300);
		}else{
			$(".return-top").fadeOut(300);
		}
	});
	
	$(".return-top").click(function(){
		$('html, body').animate({scrollTop:0}, 'slow'); 
		return false; 
	});
	
	//页脚二维码
	$(".ttention-icon").hover(
		function(){
			$(this).find(".code-wrap").slideDown();
		},
		function(){
			$(this).find(".code-wrap").slideUp();
		}
	);
	
	//相关阅读图片效果
	$(".correlation-data-tier").hover(
		function(){
			$(this).find(".correlation-img").addClass("active");
		},
		function(){
			$(this).find(".correlation-img").removeClass("active");
		}
	);
	
	$(".albedo-data-tier").hover(
		function(){
			$(this).find(".albedo-img").addClass("active");
		},
		function(){
			$(this).find(".albedo-img").removeClass("active");
		}
	);
	
	//广告栏无缝滚动
	$(function(){
		var $scroll = $(".village .village-ul");
		var $Length = $scroll.find('li').length;
		var $moveto;
		if($Length > 8){
			$scroll.hover(
				function (){
					clearInterval($moveto);
				},
				function (){
					$moveto = setInterval(function () {
						var $height = $scroll.find('li').height()+1;
						$scroll.find("li:first").animate({ marginTop: -$height + 'px' }, 600, 
					function () {
						$scroll.find("li:first").css('marginTop', 0).appendTo($scroll); 
					});}, 3000);
				}
			).trigger('mouseleave');
		}
	});
	
	//评论
	$(".plun").click(function(){
		$(".criticism-rorm").slideDown();
	});
	
	//走村串寨图片效果
	$(".hot-li").hover(
		function(){$(this).find(".hot-li-img").addClass("active");},
		function(){$(this).find(".hot-li-img").removeClass("active");}
	);
	
	//特色民宿收藏
	$(".map-evaluate").click(function(){
		$(this).addClass("active");
		$(this).find(".map-evaluate-icon i").removeClass("fa-heart-o").addClass("fa-heart");
		$(".map-evaluate-txt").text("已收藏");
	});
	
	$(".collect-btn").click(function(){
		$(this).addClass("active");
		$(this).find(".collect-btn-icon i").removeClass("fa-heart-o").addClass("fa-heart");
		$(".collect-btn-txt").text("已收藏");
	});
	
	$(".cshouc-btn").click(function(){
		$(this).addClass("active");
		$(this).find(".enshrine-icon i").removeClass("fa-heart-o").addClass("fa-heart");
		$(".enshrine-txt").text("已收藏");
	});
	
	//预定房间数量
	$(".goods-number-add").click(function(){//加
		var _input =$(this).siblings(".goods-number-input");
		var _val = $(_input).val();
		var _int = parseInt(_val)+1;
		$(_input).val(_int);
	});
	
	$(".goods-number-subtract").click(function(){
		var _input =$(this).siblings(".goods-number-input");
		var _val = $(_input).val();
		var _int = parseInt(_val)-1;
		if(_val > 0)
			$(_input).val(_int);
		else return;	
	});
	
	$(".goods-number-add,.goods-number-subtract").mousedown(function(){
		$(this).addClass("active");
	});
	
	$(".goods-number-add,.goods-number-subtract").mouseup(function(){
		$(this).removeClass("active");
	});
	
	//民宿列表条件查询
	var NUM_ONE = 0;//价格控制器
	var NUM_TWO = 0;//销量控制器
	var NUM_THREE = 0;//评价控制器
	$(".condition-btn-a").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		
		if(_index == 0){
			NUM_ONE = 0;
			NUM_TWO = 0;
			NUM_THREE = 0;
		}else if(_index == 1){
			NUM_ONE++;
			NUM_TWO = 0;
			NUM_THREE = 0;
			if(NUM_ONE % 2 != 0)
				$(this).find(".condition-btn-icon i").removeClass("fa-caret-up").addClass("fa-caret-down");
			else
				$(this).find(".condition-btn-icon i").addClass("fa-caret-up").removeClass("fa-caret-down");
		}else if(_index == 2){
			NUM_TWO++;
			NUM_ONE = 0;
			NUM_THREE = 0;
			if(NUM_TWO % 2 != 0)
				$(this).find(".condition-btn-icon i").removeClass("fa-caret-up").addClass("fa-caret-down");
			else
				$(this).find(".condition-btn-icon i").addClass("fa-caret-up").removeClass("fa-caret-down");
		}else if(_index == 3){
			NUM_THREE++;
			NUM_ONE = 0;
			NUM_TWO = 0;
			if(NUM_THREE % 2 != 0)
				$(this).find(".condition-btn-icon i").removeClass("fa-caret-up").addClass("fa-caret-down");
			else
				$(this).find(".condition-btn-icon i").addClass("fa-caret-up").removeClass("fa-caret-down");
		}
	});
	
	//大图轮播
	var PAGE_NUMBER = 1;//控制器
	$(".max-image .next").click(function(){
		var len = $(".max-image-li").length;//总页数
		var page = $(".present-num");//当前页
		if(PAGE_NUMBER != len){
			PAGE_NUMBER++;
			$(".max-image-li").eq(PAGE_NUMBER-1).addClass("active").siblings().removeClass("active");
			$(page).text(PAGE_NUMBER);
		}else{
			return false;
		}
	});
	
	//集市详情规格选项
	$(".standard-select-tier").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	});
	
	$(".max-image .prev").click(function(){
		var len = $(".max-image-li").length;//总页数
		var page = $(".present-num");//当前页
		if(PAGE_NUMBER != 1){
			PAGE_NUMBER--;
			$(".max-image-li").eq(PAGE_NUMBER-1).addClass("active").siblings().removeClass("active");
			$(page).text(PAGE_NUMBER);
		}else{
			return false;
		}
	});
	
	//大图返回上一步
	$(".retunr-link").click(function(){
		window.history.go(-1);
	});
	
	//集市商品描述商品评论
	$(".describe-nav-tier").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".describe-tier").eq(_index).addClass("active").siblings().removeClass("active");
	});
	
	//购物车单个删除
	var _CHECKED = $("input[name = 'check']");//复选框
	var _LENGTH = $(".table-wrap-li").length;//购物车订单数量
	var _FIGURE =  0;//计算已选商品
	//遍历选中复选框
	for(var i = 0; i < _CHECKED.length; i++){
		if(_CHECKED[i].checked){
			_FIGURE++;
		}
	}
	
	$(".delece-btn-icon").click(function(){
		var _par = $(this).parents(".table-wrap-li");
		var _input = $(_par).find("input[name = 'check']");
		if($(_input).prop("checked") == true){
			_FIGURE--;
			if(confirm("您确定要删除该订单吗？")){
				$(_par).remove();
				_LENGTH--;
			}else{
				return false;
			}
		}else{
			alert("您没有选中要删除的商品！");
		}
	});
	
	//单选
	$("input[name = 'check']").click(function(){
		if(this.checked){
			if(_FIGURE != _LENGTH){
				_FIGURE++;
			}else{
				return;
			}
		}else{
			if(_FIGURE != 0){
				_FIGURE--;
			}
		}
		
		if(_FIGURE != _LENGTH){
			$("input[name = 'check-all']").prop("checked",false);
		}else{
			$("input[name = 'check-all']").prop("checked",true);
		}
	});
	
	//批量删除
	$(".all-delete").click(function(){
		if(_FIGURE != 0){
			if(confirm("您确定要删除该订单吗？")){
				$("input[name='check']").each(function(){
					if(this.checked){
						$(this).parents(".table-wrap-li").remove();
						_LENGTH -= _FIGURE;
						_FIGURE = 0;
					}
				});
			}else{
				return false;
			}
		}else{
			alert("您没有选中要删除的商品！");
		}
	});
	
	//购物车全选
	$("input[name='check-all']").click(function(){
		if(this.checked){
			$("input[name='check']").prop("checked",true);
			_FIGURE = _LENGTH;
		}else{
			$("input[name='check']").prop("checked",false);
			_FIGURE = 0;
		}
	});
	
	//更换封面
	$(".change-img").hover(
		function(){
			$(".change-img-ul").slideDown(300);
		},
		function(){
			$(".change-img-ul").slideUp(300);
		}
	);
	
	$(".change-img-li img").click(function(){
		$(".folk-left-img img").attr("src",$(this).attr("src"));
	});
	
	//个人中心导航选项卡
	$(".urse-centre-li").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".urse-content-tier").eq(_index).addClass("active").siblings().removeClass("active");
	});
	
	//获取验证码倒计时
	var countdown=60; 
	function settime(obj) { 
	    if (countdown == 0) {    
	        obj.value="获取验证码"; 
	        countdown = 60; 
	        return;
	    } else { 
	        obj.value = countdown + "秒"; 
	        countdown--; 
	    } 
		setTimeout(function(){settime(obj)},1000); 
	} 
	
	$(".urse-form-verify").click(function(){
		if($(this).prop("disabled") == false){
			settime(this);
		}else{
			return false;
		}
	});
	
	//删除头像
	$(".close-image").click(function(){
		$(this).parents(".urse-photo-box").remove();
	});
	
	//个人中心收藏新闻
	$(".urse-news-txt").hover(
		function(){$(this).addClass("active");},
		function(){$(this).removeClass("active");}
	);
	
	//删除新闻收藏
	$(".urse-news-delete").click(function(){
		$(this).parent(".urse-news-li").remove();
	});
	
	//删除产品收藏
	$(".delete-product-collect-li").click(function(){
		$(this).parent(".product-collect-li").remove();
	});
	
	//地址管理设置默认地址
	$(".urse-location-input").click(function(){
		var _txt1 = "默认地址";
		var _txt2 = "设为默认地址";
		var _par = $(this).parents(".urse-location-li");
		var _sib = $(_par).siblings(".urse-location-li");
		
		if(this.checked){
			$(_par).find(".urse-location-default").text(_txt1);
			$(_sib).find(".urse-location-default").text(_txt2);
		}
	});
	
	//删除地址
	$(".urse-location-delete").click(function(){
		$(this).parents(".urse-location-li").remove();
	});
	
	//修改地址弹窗
	$(".urse-location-alter").click(function(){
		$(".popup").animate({"left":"0"},300);
	});
	
	//关闭弹窗
	$(".close-popup").click(function(){
		$(".popup").animate({"left":"710px"},300);
	});
	
	//我的订单删除订单
	$(".not-pay-delete").click(function(){
		$(this).parents(".not-pay-li").remove();
	});
	
	//异步调用百度js  
    function map_load() {  
        var load = document.createElement("script");  
        load.src = "http://api.map.baidu.com/api?v=1.4&callback=map_init";  
        document.body.appendChild(load);  
    }  
    window.onload = map_load; 
});                                          
