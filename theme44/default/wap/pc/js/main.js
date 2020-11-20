	$(function(){
    		//swiper 初始化
    			var _time='';
    			var _color=['#ff0066','#de1120','#ffb13a'] //banner的颜色
		    	var swiper = new Swiper('.swiper-container', {
			        pagination: '.swiper-pagination',
			        paginationClickable: '.swiper-pagination',
			        spaceBetween: 1,
			        effect: 'fade',
			        autoplayDisableOnInteraction : false,
			        speed:2000,
			        autoplay : 2000
			    });

			    _time=setInterval(function(){
			    	var index=swiper.activeIndex;
			    	$('.banner').css('background-color',_color[index]);
			    },1000)
			    
    		//点击切换颜色
    		$('.swiper-pagination span').click(function(){
//  			clearInterval(_time);
    			$('.banner').css('background-color',_color[$(this).index()]);
    		})

//			头部下拉菜单
			$('.welocome_main .right li').hover(function(){
				if($(this).index()==4||$(this).index()==0){
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
		});
		var pa=$('.nav_left a').find('img:first-child').css('padding-left');
		$('.nav_left a').hover(function(){
			$('.nav_left a').find('img:first-child').css({'padding-left':pa});
			
			$(this).find('img:first-child').css({'padding-left':10,'transition':0.5+'s'});
			$('.b_left .content_right li').css('display','none');
			$('.b_left .content_right li').eq($(this).index()).css('display','block');
			
		},function(){
			$(this).find('img:first-child').css({'padding-left':pa});
		})
		
//	input placehold	;
		var _pla=$('.search input').attr('placeholder');
		$('.search input').focus(function(){
			$(this).attr('placeholder','');
		})
		
		$('.search input').blur(function(){
				$(this).attr('placeholder',_pla);
			})


    	})
    	