﻿<?php
return array(
	//'配置项'=>'配置值'
 
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'mtrc', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '8hBsNitm3V', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'jrkj_', // 数据库表前缀
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
	
	'TAG_NESTED_LEVEL' =>3, 
    'LOAD_EXT_CONFIG' => 'zdq',
     
	'TMPL_CACHE_ON' => false,//禁止模板编译缓存 
	'HTML_CACHE_ON' => false,//禁止静态缓存    
	

	'sms' => array(
		'password' => 'jianzhou', //短信用户名
		'account' => 'sdk_xlw', //短信密码
		'sign' => '【全民英雄】',
	),
	'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增

	//模板替换
	'TMPL_PARSE_STRING'  =>array(
		 '__PUBLIC_DEFAULT__' => __ROOT__.'/theme/default/', // 前台的样式文件目录
		 '__PUBLIC_ADMIN__' => __ROOT__.'/theme/admin/', // 后台的样式文件目录
		 //'__JS__'     => '/Public/JS/', // 增加新的JS类库路径替换规则
		 '__UPLOAD__' => __ROOT__.'/data/attachment', // 增加新的上传路径替换规则
	),
	
	'URL_CASE_INSENSITIVE' =>true,		//大小写不敏感
	'st_encryption_key' => 'lrioucskteorne',	//加密密钥
	'LANG_SWITCH_ON' => true,		// 开启语言包功能
	'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
	'LANG_LIST' => 'en-us,zh-cn,zh-tw', //必须写可允许的语言列表
	'VAR_LANGUAGE'     => 'l', // 默认语言切换变量
    'DATA_PATH' => __ROOT__.'./data/',
	'adboard_allow_type' => array(
		'image' => '图片',
		'text' => '文字',
		'code' => '代码',
		'flash' => 'Flash',
	),
	'THINK_EMAIL' => array(
		
		'SMTP_HOST' => 'smtp.qq.com', //SMTP服务器
		
		'SMTP_PORT' => '465', //SMTP服务器端口
		
		'SMTP_USER' => '3124633839@qq.com', //SMTP服务器用户名
		
		'SMTP_PASS' => 'ypuwfsxuaxfwdehi', //SMTP服务器密码
		
		'FROM_EMAIL' => '3124633839@qq.com',
		
		'FROM_NAME' => '', //发件人名称
		
		'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
		
		'REPLY_NAME' => '', //回复名称（留空则为发件人名称）
		
		'SESSION_EXPIRE'=>'72',
	), 
	'/'=> '/jradim.php',
	
    'MAIL_ADDRESS'=>'2900220776@qq.com', // 邮箱地址
    'MAIL_SMTP'=>'smtp.qq.com', // 邮箱SMTP服务器
    'MAIL_LOGINNAME'=>'2900220776', // 邮箱登录帐号
    'MAIL_PASSWORD'=>'mifengcn12', // 邮箱密码
	
	/*项目配置*/
    'apply_city_list' => array(
        1 => '北京',
        2 => '上海',
        3 => '广州',
    ),
    'apply_age_list' => array(
        1 => '3-6岁',
        2 => '7-9岁',
        3 => '10-12岁',
    ),
	'share_href' => array(
        'weibo' => 'http://service.weibo.com/share/share.php?appkey=3925223668',
        'qq_frends' => 'http://connect.qq.com/widget/shareqq/index.html?desc=&summary=&site=baidu',
        'qq_room'=>'https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?desc=&summary=&site='
	),
	'API_KEY'=>'NI&u#+lFA0y@;$6Wj=5(~9',
	'URL_CASE_INSENSITIVE' =>true,		//大小写不敏感
	'st_encryption_key' => 'lrioucskteorne',	//加密密钥
	'userid_encryption_key' => 'HDPY_YB@#$!User_ID',//用户ID加密密钥

);