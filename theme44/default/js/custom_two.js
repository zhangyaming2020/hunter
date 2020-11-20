$(document).ready(function(){
	
	//返回上一步
	$(".retrun-prev").click(function(){window.history.go(-1);});
	
	//首页选择门店
	$(".header-location").click(function(){
		$(".store-ul").toggleClass("active");
	});
	
	$(".store-li").click(function(){
		$(".store-ul").removeClass("active");
		var this_location = $(this).find(".store-location-name").text();
		$(".header-location-name").text(this_location);
	});
	
	//栏目分类全部
	$(".click-show-all").click(function(){
		$(".programa-box").toggleClass("active");
	});
	
	//栏目分类无缝滚动
	
		var $scroll = $(".front-page-ul");
		var $Length = $scroll.find('li').length;
		var $moveto;
		if($Length > 1){
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
	
	
	//详情选择规格
	$(".specification-li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	});
	
	//详情数量加减
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
	
	//图文详情
	$(".image-text-li").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".image-text-content").eq(_index).addClass("active").siblings().removeClass("active");
	});
	
	//详情评价
	$(".estimate-nav-li").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".appraise-tier").eq(_index).addClass("active").siblings().removeClass("active");
	});
	
	//晒图查看大图
	$(".bask-img-tier").click(function(){
		$(".popup-wrap").addClass("active");
		$("body").addClass("body-popup-wrap");
	});
	
	$(".closo-popup-wrap").click(function(){
		$(".popup-wrap").removeClass("active");
		$("body").removeClass("body-popup-wrap");
	});
	
	//详情收藏
	var COLLECT = 0;
	$(".collect").click(function(){
		$(this).toggleClass("active");
		COLLECT++;
		if(COLLECT % 2 != 0){
			$(this).find("h4 i").removeClass("fa-heart-o").addClass("fa-heart");
		}else{
			$(this).find("h4 i").addClass("fa-heart-o").removeClass("fa-heart");
		}
		
	});
	
	//分类一级导航
	$(".classify-one-nav-li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	});
	
	$(window).load(function(){
		
		//分类内容高度控制
		var _height = $(".classify-flex").height();
		$(".left-classify-wrap").css({"height":_height});
		$(".right-classify-content").css({"height":_height});
		
		//分类产品图片控制
		var _width = $(".right-classify-image").width();
		$(".right-classify-image").css({"height":_width});
		
		//购物车收货地址调整行高
		var addressHeight = $(".address-name").height();
		$(".address-icon").css({"height":addressHeight});
		
		//充值
		var _recharge = $(".recharge-max-wrap").height();
		$(".recharge-space").css({"height":_recharge});
		
		//秒杀
		var _seckill = $(".seckill-top-box").height();
		$(".seckill-top-space").css({"height":_seckill});
		
		//相关商品
		var correlation_len = $(".correlation-li").length;
		var correlation_wigth = $(".correlation-li").width();
		var total_width = correlation_len * correlation_wigth;
		$(".correlation-ul").css({"width":total_width});
		
	});
	
	//分类排序
	var RAND = 0;//品牌
	var SYNTHESIS = 0;//综合排序
	$(".classify-rank-tier").click(function(){
		var _index = $(this).index();
		var _i = $(this).find(".classify-rank-name span i");
		$(this).addClass("active").siblings().removeClass("active");
		
		if(_index == 0){
			RAND++;
			$(".classify-rank").addClass("active");	
			if(RAND % 2 != 0){
				$(_i).removeClass("fa-angle-down").addClass("fa-angle-up");
				$(".lassify-brand").addClass("active");	
			}else{
				$(_i).addClass("fa-angle-down").removeClass("fa-angle-up");
				$(".lassify-brand").removeClass("active");	
			}
			SYNTHESIS = 0;
			$(".lassify_synthesis").removeClass("active");
			$(".classify-rank-tier").eq(1).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
		}else if(_index == 1){
			SYNTHESIS++;
			$(".classify-rank").addClass("active");
			if(SYNTHESIS % 2 != 0){
				$(_i).removeClass("fa-angle-down").addClass("fa-angle-up");
				$(".lassify_synthesis").addClass("active");
			}else{
				$(_i).addClass("fa-angle-down").removeClass("fa-angle-up");
				$(".lassify_synthesis").removeClass("active");
			}
			RAND = 0;
			$(".lassify-brand").removeClass("active");
			$(".classify-rank-tier").eq(0).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
		}else if(_index == 2){
			$(".filtrate-box").addClass("active");
			$(".classify-rank").removeClass("active");
			$(".lassify-nav-tier").removeClass("active");
			RAND = 0;
			SYNTHESIS = 0;
			$(".classify-rank-tier").eq(0).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
			$(".classify-rank-tier").eq(1).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
			$(".lassify-synthesis-li").removeClass("active");
			$(".lassify-brand-li").removeClass("active");
		}
	});
	
	//品牌分类
	$(".lassify-brand-li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		$(".lassify-synthesis-li").removeClass("active");
		$(".trademark-tier").removeClass("active");
	});
	
	//综合排序
	$(".lassify-synthesis-li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		$(".lassify-brand-li").removeClass("active");
		$(".trademark-tier").removeClass("active");
	});
	
	//筛选
	$(".filtrate-li").click(function(){
		var _sib = $(this).siblings(".filtrate-li");
		$(this).find(".trademark").slideDown(300);
	});
	
	//重置
	$(".replacement-tier.reset").click(function(){
		$(".filtrate-input").val("");
		$(".trademark-tier").removeClass("active");
	});
	
	//关闭筛选弹窗
	$(".close-filtrate-unll").click(function(){
		$(".filtrate-box").removeClass("active");
		$(".trademark").hide();
	});
	
	$(".replacement-tier.active").click(function(){
		$(".filtrate-box").removeClass("active");
		$(".trademark").hide();
	});
	
	//筛选高亮
	$(".trademark-tier").click(function(){
		$(this).addClass("active");
	});
	
	//搜索input
	$('.classify-search-inner input').each(function(){  
		var txt = $(this).val();  
		$(this).focus(function(){  
			if(txt === $(this).val()) $(this).val("");  
		}).blur(function(){  
			if($(this).val() == "") $(this).val(txt);  
		});  
	}); 
	
	//左侧导航
	$(".left-classify-li").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".right-classify-tier").eq(_index).addClass("active").siblings().removeClass("active");
		$(".right-classify-content").scrollTop(0);
	});
	
	//登录选项卡
	$(".enter-nav-name").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".enter-form").eq(_index).addClass("active").siblings().removeClass("active");
	});
	
	//显示密码
	var _EYE = 0;
	$(".show-eye").click(function(){
		_EYE++;
		if(_EYE % 2 != 0){
			$("#form-pass").attr("type","text");
			$(this).addClass("active");
		}else{
			$("#form-pass").attr("type","password");
			$(this).removeClass("active");
		}
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
	
	$(".verification").click(function(){ 
		if($(this).prop("disabled") == false){
			settime(this);
		}else{
			return false;
		}
	});
	
	//手机号快捷登录
	$(".agreement-check input").click(function(){
		if(this.checked){
			$(".agreement-check,.agreement-txt").addClass("active");
			$(".enter-form-btn button").attr("disabled",false);
			$(".enter-form-btn").removeClass("check");
			$(".agreement-check i").addClass("fa-check-circle").removeClass("fa-circle-thin");
		}else{
			$(".agreement-check,.agreement-txt").removeClass("active");
			$(".enter-form-btn button").attr("disabled",true);
			$(".enter-form-btn").addClass("check");
			$(".agreement-check i").removeClass("fa-check-circle").addClass("fa-circle-thin");
		}
	});
	
	//购物车
	var SINGLE_CHECK = $("input[name='single-check']");//数据复选框
	var VEHICLE_LI= $(".vehicle-li").length;//购物车数据
	var _FIGURE = 0;//计算已选商品
	
	//遍历选中复选框
	for(var i = 0; i < SINGLE_CHECK.length; i++){
		if(SINGLE_CHECK[i].checked){
			_FIGURE++;
		}
	}
	
	//购物车全选
	$("input[name='check-all']").click(function(){
		var _par = $(this).parent(".footer-check-input");//全选
		var _singlecheck = $("input[name='single-check']");//门店 
		var _parentcheck = $("input[name='parent-check']");//商品 
		
		if(this.checked){
			$("input[name='parent-check']").prop("checked",true);
			$("input[name='single-check']").prop("checked",true);
			$(_par).addClass("active");
			$(".footer-check-txt").addClass("active");
			$(_par).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
			
			$(_singlecheck).siblings("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
			$(_singlecheck).parent(".vehicle-parent-input").addClass("active");
			
			$(_parentcheck).siblings("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
			$(_parentcheck).parent(".vehicle-parent-input").addClass("active");
			
			_FIGURE = VEHICLE_LI;
		}else{
			$("input[name='parent-check']").prop("checked",false);
			$("input[name='single-check']").prop("checked",false);
			$(_par).removeClass("active");
			$(".footer-check-txt").removeClass("active");
			$(_par).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
			
			$(_singlecheck).siblings("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
			$(_singlecheck).parent(".vehicle-parent-input").removeClass("active");
			
			$(_parentcheck).siblings("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
			$(_parentcheck).parent(".vehicle-parent-input").removeClass("active");
			
			_FIGURE = 0;
		}
	});
	
	//门店
	$("input[name='parent-check']").click(function(){
		var _par = $(this).parents(".vehicle-tier");//门店层
		var _thispar = $(this).parent(".vehicle-parent-input");//门店ingput框
		var _check = $(_par).find("input[name='single-check']");//订单单选框
		var _checkpar = $(_check).parents(".vehicle-li");//订单层
		var _vehicleli = $(_checkpar).find(".vehicle-parent-input");//订单层input框
		var _input = $("input[name='single-check']");
		var _input_num = 0;
		
		if(this.checked){
			$(_thispar).addClass("active");
			$(_thispar).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
			
			$(_check).prop("checked",true);
			$(_vehicleli).addClass("active");
			$(_vehicleli).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
			$(_par).find("input[name='single-check']").prop("checked",true);
		}else{
			$(_thispar).removeClass("active");
			$(_thispar).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
			
			$(_check).prop("checked",false);
			$(_vehicleli).removeClass("active");
			$(_vehicleli).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
			$(_par).find("input[name='single-check']").prop("checked",false);
		}
		
		for(var i = 0; i < _input.length; i++){
			if(_input[i].checked){
				_input_num++;
			}
		}
		_FIGURE = _input_num;
		
		if(_FIGURE != VEHICLE_LI){
			$(".footer-check-input").removeClass("active");
			$("input[name='check-all']").prop("checked",false);
			$(".footer-check-input i").addClass("fa-circle-thin").removeClass("fa-check-circle");
		}else{
			$(".footer-check-input").addClass("active");
			$("input[name='check-all']").prop("checked",true);
			$(".footer-check-input i").removeClass("fa-circle-thin").addClass("fa-check-circle");
		}
	});
	
	//单选
	$("input[name='single-check']").click(function(){
		var _thispar = $(this).parent(".vehicle-parent-input");//订单层input框
		var _pars = $(this).parents(".vehicle-tier");//订单层
		var _lilen = $(_pars).find(".vehicle-li");//订单数据层
		var _input = $(_pars).find("input[name='single-check']");//订单层复选框
		var _parinput = $(_pars).find("input[name='parent-check']");//门店复选框
		var _inputnum = 0;
		var _outlet = $(_parinput).parent(".vehicle-parent-input");
		
		if(this.checked){
			$(_thispar).addClass("active");
			$(_thispar).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
			
			_FIGURE++;
		}else{
			$(_thispar).removeClass("active");
			$(_thispar).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
			
			_FIGURE--;
		}
		
		for(var i = 0; i < _input.length; i++){
			if(_input[i].checked){
				_inputnum++;
			}
		}
		
		if(_inputnum != _lilen.length){
			$(_parinput).prop("checked",false);
			$(_parinput).parent(".vehicle-parent-input").removeClass("active");
			$(_outlet).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
		}else{
			$(_parinput).prop("checked",true);
			$(_parinput).parent(".vehicle-parent-input").addClass("active");
			$(_outlet).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
		}
		
		if(_FIGURE != VEHICLE_LI){
			$(".footer-check-input").removeClass("active");
			$("input[name='check-all']").prop("checked",false);
			$(".footer-check-input i").addClass("fa-circle-thin").removeClass("fa-check-circle");
		}else{
			$(".footer-check-input").addClass("active");
			$("input[name='check-all']").prop("checked",true);
			$(".footer-check-input i").removeClass("fa-circle-thin").addClass("fa-check-circle");
		}
	});
	
	//删除购物车数据
	$(".vehicle-delete").click(function(){
		var _par = $(this).parents(".vehicle-tier");//门店层
		var _partier = $(this).parents(".vehicle-li");//购物车数据层
		var _thisinput = $(_partier).find("input[name='single-check']");//购物车数据层input
		
		if($(_thisinput).prop("checked") == true){
			if(confirm("您确定要删除宝贝吗？")){
				$(_partier).remove();
				_FIGURE--;
				VEHICLE_LI--;
				if($(_par).find(".vehicle-li").length == 0){
					$(_par).remove();
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			alert("您没有选中商品！");
		}
	});
	
	//配送方式
	$(".distribution").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	});
	
	//是否开发票
	$("input[name='whether']").click(function(){
		
		if(this.checked){
			$(this).parent(".whether-input").addClass("active");
			$(this).siblings("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
			$(".whether-txt").addClass("active");
			$(".whether-txt").text("是");
			$(".commercial-ul").addClass("active");
		}else{
			$(this).parent(".whether-input").removeClass("active");
			$(this).siblings("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
			$(".whether-txt").removeClass("active");
			$(".whether-txt").text("否");
			$(".commercial-ul").removeClass("active");
		}
	});
	
	//发票类型
	$("input[name = 'mold']").click(function(){
		var _par = $(this).parents(".commercial-radio-wrap");
		var _sib = $(_par).siblings(".commercial-radio-wrap");
		if(this.checked){
			$(_par).find(".commercial-units").addClass("active");
			$(_par).find(".commercial-units-txt").addClass("active");
			$(_par).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
			
			$(_sib).find(".commercial-units").removeClass("active");
			$(_sib).find(".commercial-units-txt").removeClass("active");
			$(_sib).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
		}
	});
	
	//购物车领取优惠券弹窗
	$(".coupon-txt").click(function(){
		$(".popup-wrap").addClass("active");
//		$(".content").addClass("active");
		$(".discount-ul").eq(0).addClass("active").siblings().removeClass("active");
	});
	
	//购物车可用优惠券弹窗
	$(".coupon-name").click(function(){
		$(".popup-wrap").addClass("active");
//		$(".content").addClass("active");
		$(".discount-ul").eq(1).addClass("active").siblings().removeClass("active");
	});
	
	//关闭购物车优惠券弹窗
	$(".null-close-popup,.discount-li").click(function(){
		$(".popup-wrap").removeClass("active");
		$(".content").removeClass("active");
	});
	
	//选中优惠券状态
	$(".discount-li").click(function(){
		var _sib = $(this).siblings(".discount-li");
		$(this).find(".vt_tick").addClass("active");
		$(_sib).find(".vt_tick").removeClass("active");
	});
	
	//支付方式
	$("input[name = 'pay']").click(function(){
		var _par = $(this).parents(".pay-select-li");
		var _sib = $(_par).siblings(".pay-select-li");
		
		if(this.checked){
			$(_par).find(".pay-input-inner").addClass("active");
			$(_par).find("i").removeClass("fa-circle-o").addClass("fa-check-circle");
			
			$(_sib).find("i").addClass("fa-circle-o").removeClass("fa-check-circle");
			$(_sib).find(".pay-input-inner").removeClass("active");
		}else{
			$(_sib).find(".pay-input-inner").removeClass("active");
		}
	});
	
	//余额支付弹窗
	$(".pay-select-btn button").click(function(){
		if($("#yue").prop("checked") == true){
			$(".pay-window").addClass("active");
		}else{
			return false;
		}
		$(".pay-button.empty").attr("disabled",true);
	});
	
	//取消余额支付弹窗
	$(".pay-button.cancel").click(function(){
		$(".pay-window").removeClass("active");
		ONTROL = 0;
		$(".pass-input").val("");
		$(".pay-button.figure").attr("disabled",false);
		$(".pay-button.empty").attr("disabled",true);
		$(txt).text(ONTROL);
	});
	
	//密码
	var ONTROL = 0;
	var PASS_ONE = $(".pass-input").eq(0);
	var PASS_TWO = $(".pass-input").eq(1);
	var PASS_THREE = $(".pass-input").eq(2);
	var PASS_FOUR = $(".pass-input").eq(3);
	var PASS_FIVE = $(".pass-input").eq(4);
	var PASS_SIX = $(".pass-input").eq(5);
	
	$(".pay-button.figure").click(function(){
		var num = $(this).val();
		ONTROL++;
		if(ONTROL == 1){
			$(PASS_ONE).val(num);
			$(".pay-button.empty").attr("disabled",false);
		}else if(ONTROL == 2){
			$(PASS_TWO).val(num);
		}else if(ONTROL == 3){
			$(PASS_THREE).val(num);
		}else if(ONTROL == 4){
			$(PASS_FOUR).val(num);
		}else if(ONTROL == 5){
			$(PASS_FIVE).val(num);
		}else if(ONTROL == 6){
			$(PASS_SIX).val(num);
			$(".pay-button.figure").attr("disabled",true);
		}
	});
	
	//清空密码
	$(".pay-button.empty").click(function(){
		ONTROL--;
		if(ONTROL == 5){
			$(PASS_SIX).val("");
			$(".pay-button.figure").attr("disabled",false);
		}else if(ONTROL == 4){
			$(PASS_FIVE).val("");
		}else if(ONTROL == 3){
			$(PASS_FOUR).val("");
		}else if(ONTROL == 2){
			$(PASS_THREE).val("");
		}else if(ONTROL == 1){
			$(PASS_TWO).val("");
		}else if(ONTROL == 0){
			$(PASS_ONE).val("");
			$(".pay-button.empty").attr("disabled",true);
		}
	});
	
	//新增收货地址默认地址开关
	$("input[name = 'form-check']").click(function(){
		if(this.checked){
			$(".location-form-dot").animate({"left":"0.4rem"},300);
			$(".location-form-check").addClass("active");
		}else{
			$(".location-form-dot").animate({"left":"0.1rem"},300);
			$(".location-form-check").removeClass("active");
		}
	});
    
    //打开详情客服弹窗
    $(".kefu").click(function(){
    	$(".customer").addClass("active");
    	$(".customer-content-tier.one").show().siblings(".customer-content-tier.two").hide();
    });
    
    //取消关闭详情客服弹窗
    $(".customer-abolish").click(function(){
    	$(".customer").removeClass("active");
    	$(".customer-content-tier.one").hide();
    });
    
    //空白关闭详情客服弹窗
    $(".close-customer,.illustrate-btn").click(function(){
    	$(".customer").removeClass("active");
    	$(".customer-content-tier.one").hide();
    	$(".customer-content-tier.two").hide();
    });
    
    //七天退换货打开弹窗
//  $(".commodity-state-tier").click(function(){
//   	$(".customer").addClass("active");
//  	$(".customer-content-tier.two").show().siblings(".customer-content-tier.one").hide();
//  });

	$(".commodity-state-tier").click(function(){
		$(".seven-wrap").addClass("active");
	});
	
	$(".close-seven").click(function(){
		$(".seven-wrap").removeClass("active");
	});
    
    //热门搜索
    $(".hot-data-li").click(function(){
    	$(this).addClass("active").siblings().removeClass("active");
    });
    
    //快购地点选项
    $(".laction-select-li").click(function(){
    	var _sib = $(this).siblings(".laction-select-li");
    	$(this).find(".laction-select-icon").addClass("active");
    	$(_sib).find(".laction-select-icon").removeClass("active");
    });
    
    //快购选择地点
    $(".filtrate-location").click(function(){
    	$(".filtrate-location-select").toggleClass("active");
    });
    
    $(".store-li").click(function(){
    	var _txt = $(this).find(".store-location-name").text();
    	$(".filtrate-location-name").text(_txt);
    	$(".filtrate-location-select").removeClass("active");
    });
    
    //快购分类
    var RANK_ONE = 0;//全部分类
    var RANK_TWO = 0;//综合排序
    $(".filtrate-classify-li").click(function(){
    	var _index = $(this).index();
    	$(this).addClass("active").siblings().removeClass("active");
    	if(_index == 0){
    		RANK_ONE++;
    		RANK_TWO = 0;
    		if(RANK_ONE % 2 != 0){
    			$(this).find("i").removeClass("fa-angle-down").addClass("fa-angle-up");
    			$(".filtrate-classify-rank-tier").eq(0).addClass("active");
    		}else{
    			$(this).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
    			$(".filtrate-classify-rank-tier").eq(0).removeClass("active");
    		}
    		$(".filtrate-classify-rank").addClass("active");
    		$(".filtrate-classify-rank-tier").eq(1).removeClass("active");
    		$(".filtrate-classify-li").eq(1).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
    	}else if(_index == 1){
    		RANK_TWO++;
    		RANK_ONE = 0;
    		if(RANK_TWO %2 != 0){
    			$(this).find("i").removeClass("fa-angle-down").addClass("fa-angle-up");
    			$(".filtrate-classify-rank-tier").eq(1).addClass("active");
    		}else{
    			$(this).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
    			$(".filtrate-classify-rank-tier").eq(1).removeClass("active");
    		}
    		$(".filtrate-classify-rank").addClass("active");
    		$(".filtrate-classify-rank-tier").eq(0).removeClass("active");
    		$(".filtrate-classify-li").eq(0).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
    	}else if(_index == 2){
    		RANK_TWO = 0;
    		RANK_ONE = 0;
    		$(".filtrate-box").addClass("active");
    		$(".filtrate-classify-li").eq(0).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
			$(".filtrate-classify-li").eq(1).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
    		$(".filtrate-classify-rank").removeClass("active");
    		$(".filtrate-classify-rank-tier").removeClass("active");
    		$(".lassify-synthesis-li").removeClass("active");
			$(".lassify-brand-li").removeClass("active");
    	}
    });
    
    //打开个人中心分享弹窗
    $(".click-share,.share-icon").click(function(){
    	$(".pay-window").addClass("active");
    	$(".content").addClass("active");
    });
    
    //关闭个人中心分享弹窗
    $(".clock-close").click(function(){
    	$(".pay-window").removeClass("active");
    	$(".content").removeClass("active");
    });
    
    //个人中心取消订单
    $(".click-cancel").click(function(){
    	if(confirm("您确定要取消订单吗？") == true){
    		$(this).parents(".state-data-tier").remove();
    	}
    });
    
    //评价删除上传图片
    $(".close-img").click(function(){
    	$(".close-evaluate").toggleClass("active");
    });
    
    $(".close-evaluate").click(function(){
    	$(this).parent(".close-img").remove();
    });
    
    //星星
    $(".star-icon").click(function(){
    	$(this).toggleClass("active");
    });
    
    //个人中心积分商城
    $(".integral-nav-name").click(function(){
    	var _index  = $(this).index();
    	$(this).addClass("active").siblings().removeClass("active");
    	$(".integral-tier").eq(_index).addClass("active").siblings().removeClass("active");
    	$("body").scrollTop(0);
    });
    
    //优惠券
    $(".discount-nav-name").click(function(){
    	var _index  = $(this).index();
    	$(this).addClass("active").siblings().removeClass("active");
    	$(".discount-tier").eq(_index).addClass("active").siblings().removeClass("active");
    });
    
    //选择默认地址
	$(".location-li input").click(function(){
		var _par = $(this).parents(".location-li");
		var _sib = $(_par).siblings(".location-li");
		var txt1 = "默认地址";
		var txt2 = "设置为默认地址";
		
		if(this.checked){
			$(_par).find(".operate-check").addClass("active");
			$(_par).find(".operate-default-txt").addClass("active").text(txt1);
			$(_par).find(".operate-check i").addClass("fa-check-circle").removeClass("fa-circle-thin");
			
			$(_sib).find(".operate-check").removeClass("active");
			$(_sib).find(".operate-default-txt").removeClass("active").text(txt2);
			$(_sib).find(".operate-check i").removeClass("fa-check-circle").addClass("fa-circle-thin");
		}else{
			$(_par).find(".operate-check").removeClass("active");
			$(_par).find(".operate-default-txt").removeClass("active");
			$(_par).find(".operate-check i").addClass("fa-circle-thin").removeClass("fa-check-circle");
		}
	});
	
	//删除地址
	$(".delete").click(function(){
		$(this).parents(".location-li").remove();	
	});
	
	//删除收藏
	$(".designation-delete").click(function(){
		if(confirm("您确定要删除商品吗？") == true){
			$(this).parents(".integral-commodity").remove();
		}
	});
	
	//充值
	$(".recharge-select-tier").click(function(){
		var _sib = $(this).siblings(".recharge-select-tier");
		var _txt = $(this).find(".recharge-nominal span").text();
		$(".recharge-txt input").val(_txt);
		$(this).find(".recharge-nominal").addClass("active");
		$(_sib).find(".recharge-nominal").removeClass("active");
	});
	
	$(".present-li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		var txt = $(this).find(".present-money span").text();
		$(".recharge-txt input").val(txt);
	});
	
	//我的资料更换头像
	$(".add-photo-icon").click(function(){
		$(".pay-window").addClass("active");
	});
	
	$(".close-photograph-btn,.null-close-window").click(function(){
		$(".pay-window").removeClass("active");
	});
	
	//我的资料性别设置
	$("input[name = 'gender']").click(function(){
		var _par = $(this).parents(".gender-tier");
		var _sib = $(_par).siblings(".gender-tier");
		if(this.checked){
			$(_par).addClass("active");
			$(_par).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
			
			$(_sib).removeClass("active");
			$(_sib).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
		}
	});
	
	//解绑银行卡
	var BANKNUM = 0;
	$(".clock-relieve-bank").click(function(){
		BANKNUM++;
		if(BANKNUM % 2 != 0){
			$(this).text("取消");
			$(".relieve-bank-btn").addClass("active");
		}else{
			$(this).text("解绑");
			$(".relieve-bank-btn").removeClass("active");
		}
	});
	
	$(".relieve-bank-btn").click(function(){
		if(confirm("您确定要解绑此银行卡吗？") == true){
			$(this).parents(".bank-bjimg").remove();
		}
	});
	
	$(".bank-bjimg").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	});
	
	//秒杀
	$(".seckill-li").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".seckill-data-tier").eq(_index).addClass("active").siblings().removeClass("active");
		$("body").scrollTo(0);
	});
	
	//右侧重复导航
	$(".repetition").click(function(){
		$(".repetition-wrap").addClass("active");
	});
	
	$(".repetition-return").click(function(){
		$(".repetition-wrap").removeClass("active");
	});
	
	//意见反馈
	$(".idea-li").click(function(){
		$(this).toggleClass("active");
	});
	
	$(".tickling-textarea textarea").keyup(function(){
		var txt = $(this).val().length;
		for(var i = 0; i <= txt; i++){
			$(".textarea-length span").text(300-txt);
		}
	});
	
    
    //异步调用百度js  
    function map_load() {  
        var load = document.createElement("script");  
        load.src = "http://api.map.baidu.com/api?v=1.4&callback=map_init";  
        document.body.appendChild(load);  
    }  
    window.onload = map_load;
	
});
