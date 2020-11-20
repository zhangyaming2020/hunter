$(function(){
	// 幻灯	
	var showSlide = document.getElementById('bbanner');
	if(showSlide){
		new Swipe(showSlide, {
			speed:500,
			auto:3000,
			callback: function(){
				var lis = $(this.element).next("ol").children();
				lis.removeClass("on").eq(this.index).addClass("on");
			}
		});
	}

	var brand = document.getElementById('brand');
	if(brand){
		new Swipe(brand, {
			speed:500,
			auto:0,
			callback: function(){
				var lis = $(this.element).next("ol").children();
				lis.removeClass("on").eq(this.index).addClass("on");
			}
		});
	}


	// 下拉加载
	if($('#drop-load').length){
		var flag = {
			list:{
				into : true,
				result : true
			},
			appraise:{
				into : true,
				result : true
			}
		}
		var obj = $('#drop-load');
		
		$(window).on('load', function(){
			$(window).on('scroll', function(){
				console.info(target)
				var scrollTop = document.documentElement.scrollTop||document.body.scrollTop, clientHeight = document.documentElement.clientHeight, target = obj.get(0).offsetTop-obj.outerHeight(true);
				// 触发加载更多
				if(scrollTop+clientHeight>=target && flag.list.into && flag.list.result && flag.appraise.into && flag.appraise.result){

					// 首页专属推荐
					if($('#scene').length){
					      flag.appraise.into = false;
						   var pages=$("#pages").val();
		             	   var data=jQuery.param({page:pages});
		             	   $.ajax({
				           	   type :'get',
				           	   data :data,
				           	   url  :'/home_tj_product_more/',
				           	   dataType:'json',
				           	   success:function(d)
				           	   {
				           	   	    flag.appraise.into = true;
				           	   	    $("#lists").append(d.list);
				                    $("#pages").val(d.page);
				                    if(d.is_last){
				                       flag.appraise.result = false;	 
				           	   	  	   $("#drop-load").append('<h3>The End</h3>');
				           	   	    }
				           	   }
				           })
					}

					// 主题馆
					if($('#lists_content').length){
					      flag.appraise.into = false;
						   var pages=$("#pages").val();
						   var sortid=$("#sortid").val();
		             	   var data=jQuery.param({page:pages,sortid:sortid});
		             	   $.ajax({
				           	   type :'get',
				           	   data :data,
				           	   url  :'/category_list/',
				           	   dataType:'json',
				           	   success:function(d)
				           	   {
				           	   	    flag.appraise.into = true;
				           	   	    $("#lists_content").append(d.list);
				                    $("#pages").val(d.page);
				                    if(d.is_last){
				                       flag.appraise.result = false;	 
				           	   	  	   $("#drop-load").append('<h3>The End</h3>');
				           	   	    }
				           	   }
				           })
					}
				}
			});	

			$(".category").on('click',function(){
            	flag.appraise.result=true;
                  var _this=$(this);
                  var cid=_this.attr("cid");
                  var data=jQuery.param({cid:cid});
                  $.ajax({
                     type :'post',
                     data :data,
                     url  :'/category/',
                     success:function(d)
                     {
                          $("#lists_content").html(d);
                          _this.addClass("current").siblings().removeClass("current");
                          $("#sortid").val(cid);
                          $("#pages").val(2);
                     }
                  })

            })
		})	
	}
})