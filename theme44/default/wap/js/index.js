
//头部的固定

$(function(){
	
	var a=$('.head').outerHeight();
	var b=$('.public_head').outerHeight();
	$('.top_height').css('height',a||b)

	//        后面加的关于我们样式
		
         $('.footer a li').click(function(){
				$('.footer .about_we').find('img').attr('src','theme/default/wap/images/about.png');
				$('.footer .about_we').find('p').css('color','#979797');

			}) 
			
			$('.footer .about_we').click(function(){

				$(this).find('img').attr('src','theme/default/wap/images/about_active.png');
				$(this).find('p').css('color','#da0000');
				
			})
			
			

});
