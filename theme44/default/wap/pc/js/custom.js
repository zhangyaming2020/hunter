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
	
	
});

