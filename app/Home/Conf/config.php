<?php

return array(
	//'配置项'=>'配置值'
	"LOAD_EXT_FILE"=>"templet", 
	//模板替换
	'TMPL_PARSE_STRING'  =>array(
		 '__STATIC__' => __ROOT__.'/theme/default/', // 后台的样式文件目录
	),

	
	'URL_PATHINFO_DEPR'=>'-',

	'URL_MODEL' => 3,
    'pagesize' => 8,
	'tech' => '技术支持：<a style=" color:#fff" href="http://www.0791jr.com" target="_blank">嘉瑞科技</a>',
	'ERROR_PAGE' => '/404.html',
	
	// 配置邮件发送服务器
    'MAIL_HOST' =>'smtp.qq.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'2900220776@qq.com',//你的邮箱名
    'MAIL_FROM' =>'2900220776@qq.com',//发件人地址
    'MAIL_FROMNAME'=>'全民英雄',//发件人姓名
    'MAIL_PASSWORD' =>'mifengcn12',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
	'CONSUME'=>array('','余额','微信','银联','支付宝'),
	'INTEGRAL'=>array('','购物返积分','推荐人首单返积分 ','注册送积分','兑换礼品','兑换优惠券','补全资料','评价送积分','签到送积分'),
	
);