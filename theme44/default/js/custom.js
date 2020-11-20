$(document).ready(function(){
  	/*layui.use('layer', function() {
		var layer = layui.layer;
	})*/
//返回上一步
	//$(".retrun-prev").click(function(){window.history.go(-1);});
	// //浏览器返回监听
//	 $(document).ready(function(e) { 
//		var counter = 0;
//		if (window.history && window.history.pushState) {
//						 $(window).on('popstate', function () {
//										window.history.pushState('forward', null, '#');
//										window.history.forward(1);
//									  content(getCookie('store_info'),getCookie('store_id'));
//							});
//		  }
//
//		  window.history.pushState('forward', null, '#'); //在IE中必须得有这两行
//		  window.history.forward(1);
//	});
	//$(document).delegate('.store-li','click',function(){
//		$(".store-ul").removeClass("active");
//		var this_location = $(this).find(".store-location-name").text();
//		$(".header-location-name").text(this_location);
//	});

	//品牌分类
	$(".lassify-brand-li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
//		$(".lassify-synthesis-li").removeClass("active");
//		$(".trademark-tier").removeClass("active");
	});
	//栏目分类全部
	$(document).delegate('.click-show-all','click',function(){
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
	
	//购物车数量加减
	//$(".goods-number-add").click(function(){
//		var _index =$().parents('.').index;alert(_index)
//		var _input =$(this).siblings(".goods-number-input");
//		var _val = $(_input).val();
//		var _stock =$('.stock').text();
//		var _int = parseInt(_val)+1;
//		var _stock=$(this).parents('.num-wrap').attr('data-stock');//
////		if(_int>_stock){
////			//layer.msg('库存不足',{time:800});
////			}
////		else{
//			//alert($(_input))
//		    $(_input).val(_int);
//			var id =$(this).parent().data('id');
//	        var n=$(this).siblings(".goods-number-input").val();
//			update_cart(id,n);all_price();
//			//}
//	});
		
	//$(".goods-number-subtract").click(function(){
//		var _input =$(this).siblings(".goods-number-input");
//		var _val = $(_input).val();
//		var _int = parseInt(_val)-1;
//		if(_val > 1){
//			$(_input).val(_int);
//			var id =$(this).parent().data('id');
//	        var n=$(_input).val();
//			update_cart(id,n);all_price();
//		}
//		else return;	
//	});
	//购物车价格总结
	function all_price(){
		var sum=0.00;
		$('.vehicle-ul li').find("input[name=single-check]").parent('.active').each(function(){
			var n =$(this).parents('.vehicle-li').find(".num-wrap-input").val();
			var p =$(this).parents('.vehicle-li').find(".per_price").text();
			sum +=n*parseFloat(p);
			})
			$('.account').html(sum.toFixed(2));
	}
	//更新购物车
	function update_cart(cart_id,num){
		var data={cid:cart_id,n:num};
		$.post("index.php?s=/ItemWap-update_cart",data,function(msg){
			
		})
	}

	////图文详情
//	$(".image-text-li").click(function(){
//		var _index = $(this).index();
//		$(this).addClass("active").siblings().removeClass("active");
//		$(".image-text-content").eq(_index).addClass("active").siblings().removeClass("active");
//	});
	
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
	
	////详情收藏
//	var COLLECT = 0;
//	$(".collect").click(function(){
//		
//	var login_info ="{$is_login}";
//		alert(login_info)
//		$(this).toggleClass("active");
//		COLLECT++;
//		if(COLLECT % 2 != 0){
//			$(this).find("h4 i").removeClass("fa-heart-o").addClass("fa-heart");
//		}else{
//			$(this).find("h4 i").addClass("fa-heart-o").removeClass("fa-heart");
//		}
//		
//	});
	//
//	//分类一级导航
//	$(".classify-one-nav-li").click(function(){
//		$(this).addClass("active").siblings().removeClass("active");
//	});
	
	$(window).load(function(){
		
		//分类内容高度控制
//		var _height = $(".classify-flex").height();
//		$(".left-classify-wrap").css({"height":_height});
//		$(".right-classify-content").css({"height":_height});
//			var _win_height = $(window).height();
//			var _footer_height = $(".footer").height();
//			var left_height = $(".left-classify-li").height();
//			$(".left-classify-ul").css({"height":left_height});
//			$(".left-classify-wrap").css({"height":(_win_height - _footer_height - 50)});
		
		//分类产品图片控制
		var _width = $(".right-classify-image").width();
		$(".right-classify-image").css({"height":_width});
		
		//购物车收货地址调整行高
		var addressHeight = $(".address-name").height();
		$(".address-icon").css({"height":addressHeight});
		
		//充值
		var _recharge = $(".recharge-max-wrap").height();
		$(".recharge-space").css({"height":_recharge});


		//相关商品
		var correlation_len = $(".correlation-li").length;
		var correlation_wigth = $(".correlation-li").width();
		var total_width = correlation_len * correlation_wigth;
		$(".correlation-ul").css({"width":total_width});
	});
	
	/*//分类排序
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
			/!*var cid =$('.left-classify-ul').find('.active').attr('data-id');
			//追加数据
			$.post("index.php?s=/ItemWap-search_form",{cate_id:cid},function(msg){
				$('.filtrate-content').html(msg);
			})*!/
			$(".filtrate-box").addClass("active");
			$(".classify-rank").removeClass("active");
			$(".lassify-nav-tier").removeClass("active");
			RAND = 0;
			SYNTHESIS = 0;
			$(".classify-rank-tier").eq(0).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
			$(".classify-rank-tier").eq(1).find("i").addClass("fa-angle-down").removeClass("fa-angle-up");
//			$(".lassify-synthesis-li").removeClass("active");
//			$(".lassify-brand-li").removeClass("active");
			
		}
	});*/
	
	
	
	//综合排序
	$(".lassify-synthesis-li").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	});

	//筛选
	$(document).delegate(".filtrate-li","click",function(){
		var _sib = $(this).siblings(".filtrate-li");
		$(this).find(".trademark").addClass("active");
	});

	//重置
	$(".replacement-tier.reset").click(function(){
		$(".filtrate-input").val("");
		$(".trademark-tier").removeClass("active");
	});

	//关闭筛选弹窗
	$(".close-filtrate-unll").click(function(){
		$(".filtrate-box").removeClass("active");

	});

	$(".replacement-tier.active").click(function(){
		/*var arr_key=new Array();
	    var arr_val=new Array();
		$('.choice').each(function(){
			arr_key.push($(this).data('field'));
			arr_val.push($(this).data('id'));
		})
	    var ke =arr_key.join(',');
	    var va =arr_val.join(',');
		var cid=$('.left-classify-ul').find('.active').attr('data-id');
		  $.post("index.php?s=/ItemWap-item_list",{k:ke,v:va,cate_id:cid},function(msg){
		    $(".right-classify-content .right-classify-ul").html(msg);


	     })*/
        $(".filtrate-box").removeClass("active");
	});

	//筛选高亮
	$(document).delegate(".trademark-tier","click",function(){
		$(this).addClass("active").siblings().removeClass("active");
		var arr=new Array();
		$(this).parent().find('.active').each(function(){
			arr.push($(this).data('id'));
			})
		$(this).parent().attr('data-id',arr.join('-'))
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
//	$(".left-classify-li").click(function(){
//		var _index = $(this).index();
//		$(this).addClass("active").siblings().removeClass("active");
//		$(".right-classify-tier").eq(_index).addClass("active").siblings().removeClass("active");
//		$(".right-classify-content").scrollTop(0);
//		content();
//	});
	
	
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
	
	//$(".verification").click(function(){ 
//		if($(this).prop("disabled") == false){
//			settime(this);
//		}else{
//			return false;
//		}
//	});
	
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
	
//	//购物车
//	var SINGLE_CHECK = $("input[name='single-check']");//数据复选框
//	var VEHICLE_LI= $(".vehicle-li").length;//购物车数据
//	var _FIGURE = 0;//计算已选商品
//	
//	//遍历选中复选框
//	for(var i = 0; i < SINGLE_CHECK.length; i++){
//		if(SINGLE_CHECK[i].checked){
//			_FIGURE++;
//		}
//	}
//	
//	//购物车全选
//	$("input[name='check-all']").click(function(){
//		var _par = $(this).parent(".footer-check-input");//全选
//		var _singlecheck = $("input[name='single-check']");//门店 
//		var _parentcheck = $("input[name='parent-check']");//商品 
//		if(this.checked){
//			$("input[name='parent-check']").prop("checked",true);
//			$("input[name='single-check']").prop("checked",true);
//			$(_par).addClass("active");
//			$(".footer-check-txt").addClass("active");
//			$(_par).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
//			
//			$(_singlecheck).siblings("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
//			$(_singlecheck).parent(".vehicle-parent-input").addClass("active");
//			
//			$(_parentcheck).siblings("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
//			$(_parentcheck).parent(".vehicle-parent-input").addClass("active");
//			
//			_FIGURE = VEHICLE_LI;
//		}else{
//			$("input[name='parent-check']").prop("checked",false);
//			$("input[name='single-check']").prop("checked",false);
//			$(_par).removeClass("active");
//			$(".footer-check-txt").removeClass("active");
//			$(_par).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
//			
//			$(_singlecheck).siblings("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
//			$(_singlecheck).parent(".vehicle-parent-input").removeClass("active");
//			
//			$(_parentcheck).siblings("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
//			$(_parentcheck).parent(".vehicle-parent-input").removeClass("active");
//			
//			_FIGURE = 0;
//		}
//		
//		all_price();
//	});
	
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
	
	////单选
//	$("input[name='single-check']").click(function(){
//		var _thispar = $(this).parent(".vehicle-parent-input");//订单层input框
//		var _pars = $(this).parents(".vehicle-tier");//订单层
//		var _lilen = $(_pars).find(".vehicle-li");//订单数据层
//		var _input = $(_pars).find("input[name='single-check']");//订单层复选框
//		var _parinput = $(_pars).find("input[name='parent-check']");//门店复选框
//		var _inputnum = 0;
//		var _outlet = $(_parinput).parent(".vehicle-parent-input");
//		
//		if(this.checked){
//			$(_thispar).addClass("active");
//			$(_thispar).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
//			
//			_FIGURE++;
//		}else{
//			$(_thispar).removeClass("active");
//			$(_thispar).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
//			
//			_FIGURE--;
//		}
//		
//		for(var i = 0; i < _input.length; i++){
//			if(_input[i].checked){
//				_inputnum++;
//			}
//		}
//		
//		if(_inputnum != _lilen.length){
//			$(_parinput).prop("checked",false);
//			$(_parinput).parent(".vehicle-parent-input").removeClass("active");
//			$(_outlet).find("i").addClass("fa-circle-thin").removeClass("fa-check-circle");
//		}else{
//			$(_parinput).prop("checked",true);
//			$(_parinput).parent(".vehicle-parent-input").addClass("active");
//			$(_outlet).find("i").removeClass("fa-circle-thin").addClass("fa-check-circle");
//		}
//		
//		if(_FIGURE != VEHICLE_LI){
//			$(".footer-check-input").removeClass("active");
//			$("input[name='check-all']").prop("checked",false);
//			$(".footer-check-input i").addClass("fa-circle-thin").removeClass("fa-check-circle");
//		}else{
//			$(".footer-check-input").addClass("active");
//			$("input[name='check-all']").prop("checked",true);
//			$(".footer-check-input i").removeClass("fa-circle-thin").addClass("fa-check-circle");
//		}
//		all_price();
//	});
	

	

    
   // //打开详情客服弹窗
//    $(".kefu").click(function(){
//    	$(".customer").addClass("active");
//    	$(".customer-content-tier.one").show().siblings(".customer-content-tier.two").hide();
//    });
//    
//    //取消关闭详情客服弹窗
//    $(".customer-abolish").click(function(){
//    	$(".customer").removeClass("active");
//    	$(".customer-content-tier.one").hide();
//    });
//    
//    //空白关闭详情客服弹窗
//    $(".close-customer,.illustrate-btn").click(function(){
//    	$(".customer").removeClass("active");
//    	$(".customer-content-tier.one").hide();
//    	$(".customer-content-tier.two").hide();
//    });
    
    //七天退换货打开弹窗
//  $(".commodity-state-tier").click(function(){
//   	$(".customer").addClass("active");
//  	$(".customer-content-tier.two").show().siblings(".customer-content-tier.one").hide();
//  });

	$(".commodity-state-tier,.clici-right-icon").click(function(){
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
   // $(".star-icon").click(function(){
//    	$(this).toggleClass("active");
//    });

    //优惠券
    $(".discount-nav-name").click(function(){
    	var _index  = $(this).index();
    	$(this).addClass("active").siblings().removeClass("active");
    	$(".discount-tier").eq(_index).addClass("active").siblings().removeClass("active");
    });
    
   // //选择默认地址
//	$(".location-li input").click(function(){
//		var _par = $(this).parents(".location-li");
//		var _sib = $(_par).siblings(".location-li");
//		var txt1 = "默认地址";
//		var txt2 = "设置为默认地址";
//		
//		if(this.checked){
//			$(_par).find(".operate-check").addClass("active");
//			$(_par).find(".operate-default-txt").addClass("active").text(txt1);
//			$(_par).find(".operate-check i").addClass("fa-check-circle").removeClass("fa-circle-thin");
//			
//			$(_sib).find(".operate-check").removeClass("active");
//			$(_sib).find(".operate-default-txt").removeClass("active").text(txt2);
//			$(_sib).find(".operate-check i").removeClass("fa-check-circle").addClass("fa-circle-thin");
//		}else{
//			$(_par).find(".operate-check").removeClass("active");
//			$(_par).find(".operate-default-txt").removeClass("active");
//			$(_par).find(".operate-check i").addClass("fa-circle-thin").removeClass("fa-check-circle");
//		}
//	});
	
	
	//删除收藏
	$(".designation-delete").click(function(){
		if(confirm("您确定要删除商品吗？") == true){
			$(this).parents(".integral-commodity").remove();
		}
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
	
//	$(".relieve-bank-btn").click(function(){
//		if(confirm("您确定要解绑此银行卡吗？") == true){
//			$(this).parents(".bank-bjimg").remove();
//		}
//	});
	
	$(".bank-bjimg").click(function(){
		$(this).addClass("active").siblings().removeClass("active");
	});
	
	////右侧重复导航
//	$(".repetition").click(function(){
//		$(".repetition-wrap").addClass("active");
//	});
//	
//	$(".repetition-return").click(function(){
//		$(".repetition-wrap").removeClass("active");
//	});
    
	
	
	//取得cookie  
	function getCookie(name) {  
	 var nameEQ = name + "=";  
	 var ca = document.cookie.split(';');    //把cookie分割成组  如c[0] name=test,
	
	 for(var i=0;i < ca.length;i++) {  
		var c = ca[i];                   //取得字符串  
		while (c.charAt(0)==' ') {        //判断一下字符串有没有前导空格  
		c = c.substring(1,c.length);      //有的话，从第二位开始取  
		}
		if (c.indexOf(nameEQ) == 0) {    //如果含有我们要的name  截取初始位：nameEQ.length;结束位：c.length)
		return unescape(c.substring(nameEQ.length,c.length));    //解码并截取我们要值  
		}  
	 }  //exit; 
	 return false;  
	}  
	  
	//清除cookie  
	function clearCookie(name) {  
	 setCookie(name, "", -1);  
	}  
	  
	//设置cookie  
	function setCookie(name, value, seconds) {
	 seconds = seconds || 0;   //seconds有值就直接赋值，没有为0，这个根php不一样。  
	 var expires = "";  
	 if (seconds != 0 ) {      //设置cookie生存时间  
	 var date = new Date();  
	 date.setTime(date.getTime()+(seconds*1000));  //date.getTime()函数用于使用当地时间返回当前Date对象中的时间值。该时间值表示从1970年1月1日午夜开始，到当前Date对象时，所经过的毫秒数，以格林威治时间为准。
	 expires = "; expires="+date.toGMTString();  
	 }  
	 document.cookie = name+"="+escape(value)+expires+"; path=/";   //转码并赋值  
	}
	
});
	
//自定义Jqurey插件
(function ($) {
    $.fn.extend({
        //提交表单
        "ti_jiao": function (url,fun) {
            this.click(function(){
                var data = $("form").serialize();
                $.ajax({
                    type:"post",
                    url:url,
                    data:data,
                    dataType:'json',
                    async:false,
                    success:function(msg){
                        fun(msg);
                    }
                });
            })
        },
        //加载列表
        "append_html": function (url,data='') {
            var _this = this;
            $.ajax({
                type:"post",
                url:url,
                data:data,
                async:false,
                success:function(msg){
                    _this.html(msg);
                }
            });
        },
        //为空页面判断及返回列表数
        "li_empty": function () {
            var nums = this.length;
            if(nums > 0) $('.empty-box').hide();
            return nums;
        },
    });
})(jQuery);
