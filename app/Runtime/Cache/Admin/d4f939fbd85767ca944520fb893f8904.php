<?php if (!defined('THINK_PATH')) exit();?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noarchive"/>
<meta name="Baiduspider" content="noarchive"/>
<meta name="googlebot" content="noarchive"/>
<title>牧通人才网管理系统</title>
<link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/style.css" />
<link rel="shortcut icon" href="/theme/admin/images/head.png">
<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/ie9.css"/>
<![endif]-->

<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/ie8.css"/>
<![endif]-->

<!--[if IE 7]>
    <link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/ie7.css"/>
<![endif]-->
<script type="text/javascript" src="http://ee.headhunterclub.com/js/jquery-1.7.min.js"></script>
<script>
$(function(){
	var bro=$.browser;
    var bro_msie=bro.msie;
    var ie_ver=bro.version;
    if(bro_msie && (ie_ver=="6.0" || ie_ver=="7.0" )){
    	$("body").html("<div class=\"notification msgalert\"><p>本系统只支持IE8以上内核浏览器使用。推荐使用 IE9 或 <a href='https://www.google.com/intl/zh-CN/chrome/browser/?hl=zh-CN&brand=CHMI' target='_blank'>Google Chrome 浏览器</a>。</p></div>");
    }
    
	$('.username, .password').focusout(function(){
		if($(this).val() != '') {
			$(this).css({backgroundPosition: "0 -32px"});	
		} else {
			$(this).css({backgroundPosition: "0 0"});	
		}
	});
	
	$('.username, .password').focusin(function(){
		if($(this).val() == '') {
			$(this).css({backgroundPosition: "0 -32px"});	
		}
	});
	$("#username").focus();
})
function check(){
	var obj=$("#username");
	if(obj.val().length==0){
		alert("用户名不能为空！");
		obj.focus();
		return false;
	}
	obj=$("#password");
	if(obj.val().length==0){
		alert("密码不能为空！");
		obj.focus();
		return false;
	}
	return true;
}
//if(self!=top)top.location=self.location;

</script>
</head>

<body>

<div class="loginlogo">
	<img src="theme/admin/css/bgimg/lietou.png" alt="猎头管理系统（演示版） Version 2.3 企业版" id="logo" width="181" height="54" />
</div><!--loginlogo-->

<div class="notification notifyError loginNotify"><span style='font-size:15px; color:#fff;'>您正在使用牧通人才网管理系统<br /></span></div>

<form id="loginform"  action="<?php echo U('index/login');?>" method="post" onsubmit="return check();">
<div class="loginbox">
	<div class="loginbox_inner">
    	<div class="loginbox_content">

            <input type="text" name="username" id="username" class="username" />
            <input type="password" name="password" id="password" class="password" />
            <button name="submit" class="submit">登录</button>
        </div><!--loginbox_content-->
    </div><!--loginbox_inner-->
</div><!--loginbox-->

<!--div class="loginoption">
	<a href="" class="cant">Can't access your account?</a>

    <input type="checkbox" name="remember" /> Remember me on this computer.
</div--><!--loginoption-->
</form>
</body>
</html>