$(document).ready(function(){
	
	//删除订单
	$(".x-title-state-btn").click(function(){
		var fig = confirm("您确定要删除订单吗？");
		
		if(fig == true){
			$(this).parents(".x-order-tier-inner").css({"display":"none"});
		}else{
			return false;
		}
	});
	
	//促销商品效果
	$(".x-sp-li").hover(
		function(){
			$(this).find(".x-sp-li-img img").addClass("active");
			$(this).find(".x-sp-li-name").addClass("active");
			$(this).find(".x-sp-li-standard").addClass("active");
		},
		function(){
			$(this).find(".x-sp-li-img img").removeClass("active");
			$(this).find(".x-sp-li-name").removeClass("active");
			$(this).find(".x-sp-li-standard").removeClass("active");
		}
	);
	
	//账户安全指数
	$(window).load(function(){
		var _exponent = $(".x-parson-txt").text();
		$(".x-parson-line i").animate({"width":_exponent},800);
	});
	
	//表单状态
	$(".x-informatio-input").focus(function(){
		$(this).css({
			"-webkit-transition":"all 0.2s",
			"-moz-transition":"all 0.2s",
			"-o-transition":"all 0.2s",
			"-ms-transition":"all 0.2s",
			"transition":"all 0.2s",
			"border":"1px solid #abaaaa"
		});
	});
	
	$(".x-informatio-input").blur(function(){
		$(this).css({
			"-webkit-transition":"all 0.2s",
			"-moz-transition":"all 0.2s",
			"-o-transition":"all 0.2s",
			"-ms-transition":"all 0.2s",
			"transition":"all 0.2s",
			"border":"1px solid #dcdcdc"
		});
	});
	
	//添加地址
	$(".x-site-add").hover(
		function(){
			$(this).parents(".x-site-tier").addClass("active");
			$(this).find(".x-site-add-p2").addClass("active");
			$(this).find(".x-site-add-p1").addClass("active");
		},
		function(){
			$(this).parents(".x-site-tier").removeClass("active");
			$(this).find(".x-site-add-p2").removeClass("active");
			$(this).find(".x-site-add-p1").removeClass("active");
		}
	);
	 
//	$(".x-site-add,.x-bji").click(function(){
//		$(".x-location-box").slideDown(300);
//	});
//	$(".x-nickname-amend").click(function(){
//		$(".x-location-box").slideDown(300);
//	});
	
	//删除地址
//	$(".x-nickname-delete").click(function(){
//		var txt = confirm("您确定要删除地址吗？");
//		if(txt == true){
//			$(this).parents(".x-site-tier").remove();
//		}else{
//			return;
//		}
//		
//	});
	
	//默认地址
//	$(".x-default").click(function(){
//		var con = confirm("您确定设置该地址为默认地址吗？");
//		var txt = "默认地址";
//		var txt2 = "设为默认地址";
//		var par = $(this).parents(".x-site-tier");
//		var sib = $(par).siblings(".x-site-tier");
//		
//		if(con == true){
//			$(this).text(txt);
//			$(this).parents(".x-site-tier").addClass("active").siblings().removeClass("active");
//			$(this).addClass("active");
//			$(sib).find(".x-default").removeClass("active").text(txt2);
//		}else{
//			return;
//		}
//		
//	});
	
	//购物车订单数量加减
	$(".x-add-btn,.x-subtract-btn").mousedown(function(){
		$(this).addClass("active");
	});
	
	$(".x-add-btn,.x-subtract-btn").mouseup(function(){
		$(this).removeClass("active");
	});
	
	//购物车加
	$(".x-subtract-btn").click(function(){
		var _input = $(this).siblings(".x-input-number");
		var _val = $(_input).val();
		var inp = parseInt(_val)+1;
		$(_input).val(inp);
	});
	
	//购物车减
	$(".x-add-btn").click(function(){
		var _input = $(this).siblings(".x-input-number");
		var _val = $(_input).val();
		var inp = parseInt(_val)-1;
		if(_val > 1){
			$(_input).val(inp);
		}else{
			return;
		}
		
	});
	
	//全选
	$("input[name='all']").click(function(){
		if(this.checked){
			$("input:checkbox[name='product']").prop("checked", 'true');
		}else{
			$("input:checkbox[name='product']").removeAttr("checked");
		}
	});
	
	$("input:checkbox[name='product']").click(function(){
		var checks = $("input:checkbox[name='product']");
		_len = 0;
		for(i=0;i<checks.length;i++){
	        if(checks[i].checked)
	            _len++;
	    }
		
		if(_len != checks.length){
			$("input[name='all']").removeAttr("checked");
		}else{
			$("input[name='all']").prop("checked", 'true');
		}
	});
	
	//支付
	$(".x-terrace-li").click(function(){
		var _index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".x-bank-tier").eq(_index).addClass("active").siblings().removeClass("active");
	});
	
	//二维码支付
	$(".x-click-btn").click(function(){
		$(this).find(".x-code").slideDown(300);
	});
	$(".x-click-btn").mouseleave(function(){
		$(this).find(".x-code").css({"display":"none"});
	});
	
	//
	$(".x-suspect-conmtent li").hover(
		function(){
			$(this).find(".x-suspect-img img").addClass("active");
			$(this).find(".x-suspect-p1").addClass("active");
			$(this).find(".x-suspect-p2").addClass("active");
		},
		function(){
			$(this).find(".x-suspect-img img").removeClass("active");
			$(this).find(".x-suspect-p1").removeClass("active");
			$(this).find(".x-suspect-p2").removeClass("active");
		}
	);
	
	$(".x-enshrine-li a").hover(
		function(){
			$(this).find(".x-enshrine-img img").addClass("active");
			$(this).find(".x-enshrine-name").addClass("active");
		},
		function(){
			$(this).find(".x-enshrine-img img").removeClass("active");
			$(this).find(".x-enshrine-name").removeClass("active");
		}
	);
	
	//按钮按下效果
	$(".x-discount-btn2,.x-discount-btn1").mousedown(function(){
		$(this).css({"background-color":"#df8602"});
	});
	
	$(".x-discount-btn2,.x-discount-btn1").mouseup(function(){
		$(this).css({"background-color":"#ff9900"});
	});
	
	//选择优惠券
	var _SRCOLL = $(".x-discount-no").eq(0);
	var _NUM = 0;
	var _LEN = $(".x-discount-no li").length;
	var _HEIGHT = 60;
	var tis = $(".x-tis");
	var remark = $(".x-remark-span");
	
	$(window).load(function(){
		$(tis).text("您有" + _LEN + "张优惠券可用！");
	});
	
	$(".x-discount-btn2").click(function(){
		if(_NUM < (_LEN -1))
		{
			_NUM++;
			_height = _HEIGHT * _NUM;
			$(_SRCOLL).animate({"marginTop":"-" + _height + "px"});
		}
		$(remark).text(_NUM + 1);
	});
	$(".x-discount-btn1").click(function(){
		if(_NUM != 0)
		{
			_NUM--;
			_height = _HEIGHT * _NUM;
			$(_SRCOLL).animate({"marginTop":"-" + _height + "px"});
		}
		$(remark).text(_NUM + 1);
	});
	
	//优惠券
	$(".x-discount-no li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	});
	
	/*返回上一步*/
	$(".x-erturn-page").click(function(){
		window.history.go(-1);
	});
	
	//评价
	$(".x-evaluate-anv").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		var _index = $(this).index();
		$(".x-evaluate-inner").eq(_index).show().siblings().hide();
	});
	
	$(".x-review-icon span").click(function(){
		$(this).toggleClass("active");
	});
	
	//回复
	$(".x-review-reply button").click(function(){
		$(this).toggleClass("active");
		
		var _par = $(this).parents(".x-review-form");
		var _chi = $(_par).find(".x-review-input");
		$(_chi).slideToggle();
	});
	$("#text_area").keyup(function(){
		var 
		_val = document.getElementById("text_area").value,
		_txt = $(".x-font-num");
		for(var i = 0; i < _val.length; i++)
		{
			_txt.text("" + 150 - _val.length); 
		}
	});
	
	//立即充值
	$(".x-recharge").click(function(){
		$(".x-recharge-from").slideDown();
	});
	
	//充值选项
	$(".x-pay-input input").click(function(){
		var _par = $(this).parents("label");
		if(this.checked){
			$(this).parent(".x-pay-input").addClass("active");
			$(_par).siblings().find(".x-pay-input").removeClass("active");
		}else{
			$(this).parent(".x-pay-input").removeClass("active");
		}
	});
	
	//添加会员卡
	$(".x-vip-add").click(function(){
		$(".x-vip-inner").slideDown();
	});
	
	//优惠券
	$(".x-security").click(function(){
		var 
		_this = $(this),
		t1 = $(this).find(".x-security-left"),
		t2 = $(this).find(".x-security-left p"),
		t3 = $(this).find(".x-nominal"),
		t4 = $(this).find(".x-explain p");
		
		$(_this).toggleClass("active");
		$(t1).toggleClass("active");
		$(t2).toggleClass("active");
		$(t3).toggleClass("active");
		$(t4).toggleClass("active");
	});
	
	//可使用-已使用-过期
	$(".x-exceed-nav li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
		
		var _index = $(this).index();
		$(".x-exxeed-tier").eq(_index).fadeIn(300).siblings().fadeOut(0);
	});

});

