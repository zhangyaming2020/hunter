	$(function(){
    		//swiper 初始化
    			var _time='';
    			var _color=['#fd3a5e','#963cb2','#de1120'] //banner的颜色
		    	var swiper = new Swiper('.swiper-container', {
			        pagination: '.swiper-pagination',
			        paginationClickable: '.swiper-pagination',
			        spaceBetween: 1,
			        effect: 'fade',
			        autoplayDisableOnInteraction : false,
			        speed:3000,
			        autoplay : 2000
			    });

			    _time=setInterval(function(){
			    	var index=swiper.activeIndex;
			    	$('.banner').css('background-color',_color[index]);
			    },800)
			    
    		//点击切换颜色
    		$('.swiper-pagination span').click(function(){
//  			clearInterval(_time);
    			$('.banner').css('background-color',_color[$(this).index()]);
    		})

//			头部下拉菜单
			$('.welocome_main .right li').hover(function(){
				if($(this).hasClass('none')){
					return false;
				}
				$(this).addClass('active').find('ul').show();
			},function(){
				$(this).removeClass('active').find('ul').hide();
			})
					
			
//			banner上面的左侧子导航
		$('.b_left').hover(function(){
			$('.b_left .content_right').css('display','block');	
		},function(){
			$('.b_left .content_right').css('display','none');
			$('.nav_left>a').css({'background':_bg,'color':_Acolor});
			
		});

		var _opacity=$('.nav_left').css('opacity');
		var pa=$('.nav_left a').find('img:first-child').css('padding-left');
		var _bg=$('.nav_left').css('background');
		var _Acolor=$('.nav_left>a').css('color');
		$('.nav_left>a').hover(function(){
			$('.nav_left>a').find('img:first-child').css({'padding-left':pa});
			$('.nav_left>a').css({'color':'#999999','background':_bg});
			$(this).css({'background':'#fff','color':'#000000'});
			$(this).find('img:first-child').css({'padding-left':10,'transition':0.5+'s'});
			$('.b_left .content_right li').css('display','none');
			$('.b_left .content_right li').eq($(this).index()).css('display','block');
		},function(){
			var _this=$(this);
			$(this).find('img:first-child').css({'padding-left':pa});
//			$(this).css('background','#fff');
			$('.b_left .content_right').mouseover(function(){
				$('.nav_left>a').css('background',_bg);
				_this.css('background','#fff');
			})
			
		});
		
//	input placehold	;
		var _pla=$('.search input').attr('placeholder');
		$('.search input').focus(function(){
			$(this).attr('placeholder','');
		})
		
		$('.search input').blur(function(){
				$(this).attr('placeholder',_pla);
			})


    	})
    	
//	产品列表
$('.all.product').hover(function(){
	$('.b_left.product').show();
	$('.all.product>img').addClass('active');
},function(){
	$('.b_left.product').hide();
	$('.all.product>img').removeClass('active');
})

//产品分类的更多

var off1=true;
$('.p_sort .pinpai_head .move').click(function(){
	
	if(off1){
		$(this).text('更多-');
		$(this).parents('.p_sort_head dd').css('height','auto');
	}else{
		$(this).text('更多+');
		$(this).parents('.p_sort_head dd').css('height',33);
	}
	off1=!off1;
})

var off=true;

$('.pinpai dd span').click(function(){
	if(off){
		$(this).text('更多-');
		$(this).parents('.pinpai dd').css('height','auto');

	}else{
		$(this).text('更多+');
		$(this).parents('.pinpai dd').css('height',32); 
	}
	off=!off;
});




$(document).ready(function(){
	// 图片上下滚动
	var count = $("#imageMenu li").length-6; /* 显示 6 个 li标签内容 */
	var interval = $("#imageMenu li:first").width();
	var curIndex = 0;
	
	$('.scrollbutton').click(function(){
		if( $(this).hasClass('disabled') ) return false;
		
		
		if ($(this).hasClass('smallImgDown')) --curIndex;
		else ++curIndex;
		$('.scrollbutton').removeClass('disabled');
		if (curIndex == 0) $('.smallImgUp').addClass('disabled');
		if (count+curIndex==0) $('.smallImgDown').addClass('disabled');
		
		$("#imageMenu ul").stop(false, true).animate({"marginLeft" : curIndex*interval + "px"}, 600);
	
	});	
	// 解决 ie6 select框 问题
	$.fn.decorateIframe = function(options) {
        if ($.browser.msie && $.browser.version < 7) {
            var opts = $.extend({}, $.fn.decorateIframe.defaults, options);
            $(this).each(function() {
                var $myThis = $(this);
                //创建一个IFRAME
                var divIframe = $("<iframe />");
                divIframe.attr("id", opts.iframeId);
                divIframe.css("position", "absolute");
                divIframe.css("display", "none");
                divIframe.css("display", "block");
                divIframe.css("z-index", opts.iframeZIndex);
                divIframe.css("border");
                divIframe.css("top", "0");
                divIframe.css("left", "0");
                if (opts.width == 0) {
                    divIframe.css("width", $myThis.width() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                if (opts.height == 0) {
                    divIframe.css("height", $myThis.height() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                divIframe.css("filter", "mask(color=#fff)");
                $myThis.append(divIframe);
            });
        }
    }
    $.fn.decorateIframe.defaults = {
        iframeId: "decorateIframe1",
        iframeZIndex: -1,
        width: 0,
        height: 0
    }
    //放大镜视窗
    $("#bigView").decorateIframe();
    //点击到中图
    var midChangeHandler = null;
	
    $("#imageMenu li img").bind("mouseover", function(){
		if ($(this).attr("id") != "onlickImg") {
			window.clearTimeout(midChangeHandler);
			midChange($(this).attr("src").replace("small", "mid"));
			//$(this).css({ "border": "3px solid #959595" });
			$("#imageMenu li").removeAttr("id");
			$(this).parent().attr("id", "onlickImg");
		}
	})
//  .bind("click", function(){
//		if ($(this).attr("id") != "onlickImg") {
//			midChange($(this).attr("src").replace("small", "mid"));
//			$("#imageMenu li").removeAttr("id");
//			$(this).parent().attr("id", "onlickImg");
//		}
//	})
//	.bind("mouseout", function(){
//		if($(this).attr("id") != "onlickImg"){
//			$(this).removeAttr("style");
//			midChangeHandler = window.setTimeout(function(){
//				midChange($("#onlickImg img").attr("src").replace("small", "mid"));
//			}, 1);
//		}
//	});
    function midChange(src) {
        $("#midimg").attr("src", src).load(function() {
            changeViewImg();
        });
    }
    //大视窗看图
    function mouseover(e) {
        if ($("#winSelector").css("display") == "none") {
            $("#winSelector,#bigView").show();
        }
        $("#winSelector").css(fixedPosition(e));
        e.stopPropagation();
    }
    function mouseOut(e) {
        if ($("#winSelector").css("display") != "none") {
            $("#winSelector,#bigView").hide();
        }
        e.stopPropagation();
    }
    $("#midimg").mouseover(mouseover); //中图事件
    $("#midimg,#winSelector").mousemove(mouseover).mouseout(mouseOut); //选择器事件

    var $divWidth = $("#winSelector").width(); //选择器宽度
    var $divHeight = $("#winSelector").height(); //选择器高度
    var $imgWidth = $("#midimg").width(); //中图宽度
    var $imgHeight = $("#midimg").height(); //中图高度
    var $viewImgWidth = $viewImgHeight = $height = null; //IE加载后才能得到 大图宽度 大图高度 大图视窗高度

    function changeViewImg() {
        $("#bigView img").attr("src", $("#midimg").attr("src").replace("mid", "big"));
    }
    changeViewImg();
    $("#bigView").scrollLeft(0).scrollTop(0);
    function fixedPosition(e) {
        if (e == null) {
            return;
        }
        var $imgLeft = $("#midimg").offset().left; //中图左边距
        var $imgTop = $("#midimg").offset().top; //中图上边距
        X = e.pageX - $imgLeft - $divWidth / 2; //selector顶点坐标 X
        Y = e.pageY - $imgTop - $divHeight / 2; //selector顶点坐标 Y
        X = X < 0 ? 0 : X;
        Y = Y < 0 ? 0 : Y;
        X = X + $divWidth > $imgWidth ? $imgWidth - $divWidth : X;
        Y = Y + $divHeight > $imgHeight ? $imgHeight - $divHeight : Y;

        if ($viewImgWidth == null) {
            $viewImgWidth = $("#bigView img").outerWidth();
            $viewImgHeight = $("#bigView img").height();
            if ($viewImgWidth < 200 || $viewImgHeight < 200) {
                $viewImgWidth = $viewImgHeight = 800;
            }
            $height = $divHeight * $viewImgHeight / $imgHeight;
            $("#bigView").width($divWidth * $viewImgWidth / $imgWidth);
            $("#bigView").height($height);
        }
        var scrollX = X * $viewImgWidth / $imgWidth;
        var scrollY = Y * $viewImgHeight / $imgHeight;
        $("#bigView img").css({ "left": scrollX * -1, "top": scrollY * -1 });
        $("#bigView").css({ "top": 0, "left": 420 });

        return { left: X, top: Y };
    }
});


//图文详情
$(".detail_pingjia .nav1 li").click(function(){
	$(this).addClass('active').siblings().removeClass('active');
	$('.son>div').hide();
	$('.son>div').eq($(this).index()).show();
})
$('.son .son_2_1 li').click(function(){
	$(this).addClass('active').siblings().removeClass('active');
//	$('.son_wrap>ul').hide();
//	$('.son_wrap>ul').eq($(this).index()).show();
})

//产品列表的箭头
$('.p_sort_body dd a').click(function(){
	$(this).toggleClass('act'); 
})

//关于我们
$('.about_left>li>a').click(function(){
	$('.about_left>li').removeClass('active');
	$(this).parent().addClass('active');
//	$(this).siblings().slideToggle(500);
})


//function radio(){
//			alert(111)
//			var a=$('.p-z-main div<input');
//			a.click(function(){
//			$(this).parent().css('outline','solid 4px #85a1d5');
//				
//			})
//		}
//		
//		radio();
//			
//			
//
