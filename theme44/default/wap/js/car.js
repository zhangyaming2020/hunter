/********

 	@ 购物车相关js

	@ design by candy

	@ date 2013-10-09

*******************/

$(function(){



	//增加or减少数量

	$(".decrease,.increase").on({'click':function(){

		var  uid = $(this).parent().parent().parent().parent().find(".p-pur").data("uid");

//		if(uid==""){

//			window.location.href="?m=Home&c=memberWap&a=login"; 

//		}

		change_num($(this));

    }})

	

	 function change_num(_this) { 

	var inv=_this.parent().data("inv");  //库存

	var quota=_this.parent().data("quota");  //限购 	

    var _html=_this.parent().find(".sy_num");

		var className=_this.attr("class");

		var num=_html.html();

		if(className == 'increase'){if(num>=0)num++;}//增加数量

		else if(className == 'decrease'){if(num>1)num--;}//减少数量

		if(num==0){

			num=1;

		}

				if(inv!=0&&num>inv){

					alert('超过购买数量');

					num=inv;

				}

				if(quota!=0&&num>quota){

					alert('达到限购数量');

					num=quota;

				}

		_html.html(num)//将增加或者减少后的数量赋值给传到数量文本框

		var  id = _this.parent().parent().find(".p-pur").data("id");

		 var  price = _this.parent().parent().find(".p-pur").data("price");

		var did =   _this.parent().parent().find(".p-pur").data("did");;

		 var totalprice = (num * price).toFixed(2) ;
		  _this.parent().parent().parent().find(".totalprice").html(totalprice);    //改变价格

        var params={id:id,did:did,num:num,price:price,totalprice:totalprice};

//      console.log(params);

		var str = jQuery.param(params);

		$.ajax({

			type: "POST",

			url: "?m=Home&c=indexWap&a=ajax_addShopcar",

			data: str,

			success: function(msg){

//				console.log(msg);

			$(".nums").html(msg.nums);

			$(".prices").html('￥'+msg.prices);

			$(".totalprice").html(msg.totalprice);

			}

		});

    }

	

   //改变商品总价和数量

    function change_shopcar_price(){

    	  var totalprice=0; var nums=0 ;

    	  $("input[name='o_id[]']:checked").each(function(){

    	  var price  = $(this).parent().parent().find(".p-pur").data("price");

//  	  $(this).parent().parent().find(".clearfix").attr("id");

    	  var num =$(this).parent().parent().find(".p-pur").find(".sy_num").html();

    	  	  num=parseInt(num);

    	  	  totalprice += (price*num);      //更新产品的总价

    	  	  nums += num;

//		console.log(totalprice); 

//		console.log(price);

//		console.log(num);

    	  })

    	  $(".prices").html("￥"+totalprice.toFixed(2));

    	  $(".nums").html(nums);

    }



	//单个or批量删除

	$("#all_checked").click(function(){

		if($(this).prop('checked')){

			$(".check-wrapper input:checkbox").prop('checked', true);

//			$('#but_btn').removeClass('no_pay');

			change_shopcar_price();

		}else{

			$(".check-wrapper input:checkbox").prop('checked', false);

//			$('#but_btn').addClass('no_pay');

			change_shopcar_price();

		}

	})

	

	var ele = $('.ckc_1');

	ele.each(function(index){

		var _this = $(this);

		_this.find('input[type="checkbox"]').on('click', function(){

            change_shopcar_price();

//			if($(this).prop('checked')){

//				if($('#but_btn').hasClass('no_pay')){

//					$('#but_btn').removeClass('no_pay');

//				};

//			}else{

//				for(var i=0;i<ele.length;i++){

//					if(ele.length-1==0){

//						$('#but_btn').addClass('no_pay');

//						break;

//					}

//					if(i!=index){

//						if(ele.eq(i).find('input[type="checkbox"]').prop('checked')){

//							break;

//						}							

//					}

//					if(i==ele.length-1){

//						if(!ele.eq(i).find('input[type="checkbox"]').prop('checked')){

//							if($('#but_btn').not('no_pay')){

//								$('#but_btn').addClass('no_pay');

//							};

//						}

//					}

//				}

//			}

		});

	});

	

//	$('#but_btn').on('click', function(){

//		var ele = $('.ckc_1');

//		for(var i=0;i<ele.length;i++){

//			// 有选中退出循环

//			if(ele.eq(i).find('input[type="checkbox"]').prop('checked')){

//				break;

//			}

//			

//			// 最后一个无选中

//			if(i==ele.length-1){

//				if(!ele.eq(ele.lenth-1).find('input[type="checkbox"]').prop('checked')){

//					return false;

//				}

//			}			

//		}

//	});

	

//	//新增收货地址

//$("#add_adress").click(function(){

//	$(".add_adrbox").show("slow")

//	})

//$(".cancel").click(function(){

//	$(".add_adrbox").hide("slow")

//	})

//

//$("#s_name").blur(function(){

//	var s_name=$("#s_name").val();

//	if(s_name==""){$("#s_name").siblings("em").text("请您填写收货人姓名").css("color","#900");return false;}else{$("#s_name").siblings("em").text("");return true;}

//	})

//$("#s_adress").blur(function(){

//	var s_adress=$("#s_adress").val();

//	if(s_adress==""){$("#s_adress").siblings("em").text("请您填写您的收获地址").css("color","#900");return false;}else{$("#s_adress").siblings("em").text("");return true;}

//	})

//

//$(".cancel").click(function(){

//	$(".add_adrbox").hide("slow")

//	})

//

//

//$("#but_btn").live({'click':function(){ 

//			 $.ajax({

//					type: "POST",

//					url: "/shopcar/yz",

//					data: "",

//					success: function(msg)

//					{

//					   if(msg==0){ 

//					   	    showLogin('#popup-car');   

//                          return false;

//					   }else if(msg==1){

//						    $('#car_id').attr('action','/shopcar/accounts/');  

//							document.shop_car.submit();

//					   }

//					}

//				});

//	           return false;								   

//        }

//	})

//



    $(".del,.all_del").live({'click':function(){

		var params="";

		var className=$(this).attr("class");

		var $ck=$(".check-wrapper input[type=checkbox]:checked");//获取被选中的物品 

		if(className=='del'){

//			var n=$(this).parents(".delete_div").find(".check-wrapper").length;//获取每个店铺购买商品数

//			if(n==1){$(this).parents(".delete_div").remove()}else if(n>1){$(this).parents(".check-wrapper").remove()}//单个删除完毕

//          var o_id = $(this).parents("p").attr('id');

//          params={id:o_id,pi:1};

//          

		}else if(className=="all_del"){

			var chk_value =[]; 

			$ck.each(function(e){

				chk_value.push($(this).val());   

				var a=$(this).parents(".check-wrapper").length;//获取购物车中每个商铺被选中的物品个数

				var b=$(this).parents(".delete_div").find(".check-wrapper").length;//获取购物车中每个商铺的所有物品个数

				if(a==b){$(this).parents(".delete_div").remove()}else{

					$(this).parents(".delete_li").remove();

//					$(this).parents(".ckc_1").remove();

					};

			})

			params={id:chk_value,pi:2};

//			console.log(params);

	  	}

	  	var strs = jQuery.param(params);

	  	$.ajax({

				type: "POST", 

				url: "?m=Home&c=memberWap&a=ajax_myShopcar_del",

				async: false,

				data: strs,

				success: function(msg){

//					console.log(msg);

		

				}

		}); 



	}})



















})