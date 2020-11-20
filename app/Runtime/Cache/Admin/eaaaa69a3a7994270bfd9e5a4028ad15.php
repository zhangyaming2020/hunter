<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="/hunter/theme/admin/css/style.css" />
    <link rel="shortcut icon" href="/theme/admin/images/head.png">
    <title>牧通人才网管理系统</title>
    <script>
	var URL = '/hunter/jradmin.php/Index';
	var SELF = '/hunter/jradmin.php?m=Admin&c=index&a=index';
	var ROOT_PATH = '/hunter';
	var APP	 =	 '/hunter/jradmin.php';
	//语言项目
	var lang = new Object();
	<?php $_result=json_decode(L('js_lang_st'),true);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?>
	</script>
	<script src="theme/default/js/jquery-1.11.2.min.js"></script> 
<link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/style.css" />
<link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/plugins/showLoading.css" />
<link href="http://ee.headhunterclub.com/js/plugins/tipsy/css/tipsy.css" rel="stylesheet" type="text/css" />
<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/ie9.css"/>
<![endif]-->

<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/ie8.css"/>
<![endif]-->

<!--[if IE 7]>
    <link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/ie7.css"/>
<![endif]-->
<script type="text/javascript" src="/hunter/theme/admin/hunter/js/jquery-1.7.min.js"></script>

<script type="text/javascript" src="/hunter/theme/admin/hunter/js/customs/general.js"></script>

<script type="text/javascript" src="http://ee.headhunterclub.com/js/plugins/tipsy/jquery.tipsy.js"></script>
<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
<![endif]-->
<style>
	.subnav{padding:0px !important;}
	.maincontent{    padding: 20px 15px;
    overflow: hidden;}
    .sidebar .current{width:220px;height:33px;}
    #accordion .content:last-child{padding:0px !important;}
	body{background:white;}
</style>
</head>

<body class="bodygrey" onLoad="dwr.engine.setActiveReverseAjax(true);dwr.engine.setNotifyServerOnPageUnload(true);dwr.engine.setErrorHandler(function(){});">
<iframe id="yyhiddeniframe" style="display: none;"></iframe>
<div id="updatePatchPop"></div>
<div id="updatePluginPatchPop"></div>
<div class="headerspace"></div>
<div class="header">

    <form id="search" action="" method="post">
    	<input type="text" id="headSearchKeyword" title="快速查询简历" /><button class="searchbutton"></button>
    </form>
    
    <div class="topheader">
        <ul class="notebutton">
            <li class="note" id="msgList">
                <a href="javascript:;" class="messagenotify" title="跟进提醒与待办事项">
                    <span class="wrap">
                        <span class="thicon msgicon"></span>
                        <span class="count" id="msgNum" style="display:none;">0</span>
                    </span>
                </a>
            </li>
            <li class="note">
            	<a href="javascript:;" class="friendnotify" title="用户">
                	<span class="wrap">
                    	<span class="thicon friendicon"></span>
                        <span class="count" id="messageNum" style="background: green; display:none;">0</span>
                        <script>pushMessage(0, 0);</script>
                    </span>
                </a>
            </li>
            
            <li class="note">
            	<a href="javascript:;" class="tasknotify" title="排行榜">
                	<span class="wraptask">
                    	<span class="thicon taskicon">排行榜</span>
                    </span>
                </a>
            </li>
            
            <li class="note">
            	<a href="javascript:;" class="servicenotify" title="插件&服务">
                	<span class="wraptask" style="padding: 7px 32px 7px 26px;">
                    	<span class="thicon serviceicon" style="width:30px;">服务</span>
                    </span>
                </a>
            </li>
            <li class="note">
            	<a href="javascript:;" class="mailnotify" title="邮件中心"  onClick="$('#emailLink').click();">
                	<span class="wrap">
                    	<span class="thicon mailicon"></span>
                        <span id="emailNum" class="count" style="display:none;">0</span>
                    </span>
                </a>
            </li>
        </ul>
    </div>

    <!-- logo -->
	<a href="#"><img src="theme/admin/css/bgimg/lietou.png" alt="远扬猎头管理系统（演示版） Version 2.3 企业版" id="logo" width="181" height="54" /></a>
    
    <div class="tabmenu">
    	<ul  class="nav white" id="J_tmenu">
        	<li class="current" menu="desktopmenu"><a href="<?php echo U('index/index');?>" class="dashboard"><span>桌面</span></a></li>
        	
            <!--<li menu="customer/menu"><a href="customer/list" class="elements"><span>客户</span></a>
            	<ul class="subnav">
                	
                	<li id="customerLink"><a href="customer/list"><span>我的客户</span></a></li>
                	
                    <li><a href="customer/sharelist"><span>共享客户</span></a></li>
                    <li><a href="customer/list?public=1"><span>公共客户库</span></a></li>
                    
                    <li><a href="customer/otherlist"><span>其它人员的客户</span></a></li>
                    
                </ul>
          	</li>
            <li menu="project/menu"><a href="project/list" class="project"><span>项目</span></a>
            	<ul class="subnav">
                	<li id="projectLink"><a href="project/list"><span>我的项目</span></a></li>
                    <li><a href="project/sharelist"><span>我协助的项目</span></a></li>
                    
                    <li><a href="project/otherlist"><span>其它人员的项目</span></a></li>
                    <li><a href="project/othersharelist"><span>其它人员协助的项目</span></a></li>
                    
                    <li><a href="project/report/allist"><span>推荐报告</span></a></li>
                    <li><a href="offer/allist"><span>offer</span></a></li>
                </ul>
          	</li>
            <li menu="resume/menu"><a href="resume/list?type=1" class="users"><span>简历</span></a>
            	<ul class="subnav">
                    <li id="resumeLink" keyword=""><a href="resume/list?type=1"><span>所有简历</span></a></li>
                    <li><a href="resume/list"><span>我的简历库</span></a></li>
                    
                    <li><a href="resume/list?handle=myapply"><span>我下载的简历</span></a></li>
			        <li><a href="resume/list?handle=apply"><span>别人向我申请下载的简历</span></a></li>
			        
			        <li><a href="resume/list?handle=favorite"><span>我收藏的简历</span></a></li>
                    <li><a href="resume/list?type=-2"><span>未完善简历</span></a></li>
                    <li><a href="resume/list?type=-1"><span>已删简历</span></a></li>
                    <li><a href="project/excavate/person/all"><span>定向寻猎</span></a></li>
                </ul>
          	</li>
            <li menu="finance/menu"><a href="finance/list" class="finance"><span>财务</span></a>
            	<ul class="subnav">
                	<li><a href="finance/list"><span>应收款</span></a></li>
                    <li><a href="finance/list?state=4"><span>应付款</span></a></li>
                    <li><a href="finance/pushdetail"><span>提成明细</span></a></li>
                    <li><a href="finance/pushCount"><span>提成统计</span></a></li>
                    
                    <li><a href="finance/financeCount"><span>财务统计</span></a></li>
                    
                </ul>
            </li>
            <li menu="office/menu"><a href="customer/contract/allist" class="office"><span>办公</span></a>
            	<ul class="subnav">
                    <li><a href="customer/contract/allist"><span>合同管理</span></a></li>
                    <li><a href="office/share/list?type=1"><span>共享中心</span></a></li>
                    <li id="calendarLink"><a href="office/calendar"><span>日历</span></a></li>
                    <li id="emailLink"><a href="office/email/list"><span>邮件中心</span></a></li>
                </ul>
            </li>
            <li menu="plugin/reportor/menu" id="reports"><a href="plugin/reportor/base" class="reports"><span>报表</span></a>
            	<ul class="subnav">
                    <li><a href="plugin/reportor/base"><span>报表概况</span></a></li>
                    
                    <li><a href="plugin/reportor/resumeKeywords"><span>简历关键字查询记录</span></a></li>
                    
                    <li><a href="plugin/reportor/customer"><span>客户报表</span></a></li>
                    <li><a href="plugin/reportor/project"><span>项目报表</span></a></li>
                    <li><a href="plugin/reportor/resume"><span>简历报表</span></a></li>
                    
                    <li><a href="finance/financeCount"><span>财务报表</span></a></li>
                    <li><a href="plugin/reportor/operate"><span>公司经营分析</span></a></li>
                    
                    <li><a href="plugin/reportor/task"><span>任务报表</span></a></li>
                </ul>
            </li>
            
            <li menu="setting/menu"><a href="setting/base" class="setting"><span>设置</span></a>
            	<ul class="subnav">
            		
                	<li><a href="setting/base"><span>基本设置</span></a></li>
                	
                	
                	<li><a href="plugin/business/base"><span>门户设置</span></a></li>
                	
                	
                    <li><a href="setting/department/list"><span>部门设置</span></a></li>
                    <li><a href="setting/user/list"><span>员工设置</span></a></li>
                    <li><a href="setting/dict/list"><span>字典配置</span></a></li>
                    <li><a href="setting/bank/list"><span>银行帐户</span></a></li>
                    <li><a href="setting/resumemail/list"><span>排除简历邮箱域名</span></a></li>
                    <li><a href="setting/macinfo/list"><span>MAC信息设置</span></a></li>
                    <li><a href="setting/backup/input"><span>系统备份/恢复</span></a></li>
                    
                    
                    <li><a href="setting/task/list"><span>任务设置</span></a></li>
                    
                </ul>
            </li>
            -->
        	<?php if(is_array($top_menus)): $i = 0; $__LIST__ = $top_menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li  class="top_menu"><a  href="javascript:;" data-id="<?php echo ($val["id"]); ?>" hidefocus="true" style="outline:none;" class="<?php echo ($val["icon"]); ?>"><span><?php echo L($val['name']);?></span></a>
            	<!--<ul class="subnav">
            		
                	<li><a href="setting/base"><span>基本设置</span></a></li>
                	
                	
                	<li><a href="plugin/business/base"><span>门户设置</span></a></li>
                	
                	
                    <li><a href="setting/department/list"><span>部门设置</span></a></li>
                    <li><a href="setting/user/list"><span>员工设置</span></a></li>
                    <li><a href="setting/dict/list"><span>字典配置</span></a></li>
                    <li><a href="setting/bank/list"><span>银行帐户</span></a></li>
                    <li><a href="setting/resumemail/list"><span>排除简历邮箱域名</span></a></li>
                    <li><a href="setting/macinfo/list"><span>MAC信息设置</span></a></li>
                    <li><a href="setting/backup/input"><span>系统备份/恢复</span></a></li>
                    
                    
                    <li><a href="setting/task/list"><span>任务设置</span></a></li>
                    
               </ul>-->
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    	
    	
    	</ul>

    </div><!-- tabmenu -->
    
    <div class="accountinfo">
    	
    		<img src="http://ee.headhunterclub.com/images/avatar.png" width="50" height="50" alt="管理员" />
    	
    	
        <div class="info">
        	<h3><?php echo $_SESSION['admin']['username'];?></h3>
            <p>
            	<a href="javascript:;" onclick="modify();">设置</a> <a href="<?php echo U('index/logout');?>">退出</a>
            </p>
        </div><!-- info -->
    </div><!-- accountinfo -->
</div>

<!--<div id="loading">
<div class="sidebar">
	<div id="accordion"></div>
</div><!-- leftmenu -->
<div id="loading">
	<div class="sidebar">
		<div id="accordion"  data-uri="<?php echo U('index/left');?>">
			<div class="content" style="display: block;">
				<h3 class="open">常用功能导航</h3>
				<ul class="leftmenu" style="display: block;">
					<li class="sub_menu current">
						<a href="<?php echo U('index/index');?>" class="home">我的桌面</a>
					</li>
				
					<li class="sub_menu">
						<a class="customer" href="javascript:;" data-uri="<?php echo U('custom/index',array('list_type'=>1));?>" data-id="397" hidefocus="true">我的客户</a>
					
					</li>
				
					<li class="sub_menu">
						<a class="customer-gx" href="javascript:;" data-uri="<?php echo U('custom/index',array('list_type'=>3));?>" data-id="543" hidefocus="true">共享客户</a>
					
					
					</li>
					<li class="sub_menu">
						<a class="project" href="javascript:;" data-uri="<?php echo U('project/index',array('type'=>1));?>" data-id="254" hidefocus="true">我的项目</a>
					</li>
					<li class="sub_menu">
						<a class="resume" href="javascript:;" data-uri="<?php echo U('resum/index',array('type'=>1));?>" data-id="149" hidefocus="true">所有简历</a>
					</li>
					<li class="sub_menu">
						<a class="report" href="javascript:;" data-uri="<?php echo U('report/index',array('type'=>1));?>" data-id="212" hidefocus="true">推荐报告</a>
					</li>
					<li class="sub_menu">
						<a class="offer" href="javascript:;" data-uri="<?php echo U('offer/index',array('type'=>1));?>" data-id="541" hidefocus="true">offer</a>
					</li>
					<li class="sub_menu">
						<a href="javascript:;" class="task">我的任务</a>
					</li>
				
					<!--<li>
						<a href="finance/list" class="finance-in">应收款</a>
					</li>
					<li>
						<a href="finance/list?state=4" class="finance-out">应付款</a>
					</li>
					<li>
						<a href="finance/pushdetail" class="finance-mx">提成明细</a>
					</li>
					<li>
						<a href="finance/pushCount" class="tctj">提成统计</a>
					</li>-->
				</ul>
			    <!--<?php if(is_array($left_menu)): $i = 0; $__LIST__ = $left_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><h3 class="f14"><span class="J_switchs cu on" title="<?php echo L('expand_or_contract');?>"></span><?php echo ($val["name"]); ?></h3>
			    <ul class="leftmenu" style="display: block;">
			        <?php if(is_array($val['sub'])): $i = 0; $__LIST__ = $val['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sval): $mod = ($i % 2 );++$i; if($sval['id'] == 455): ?><li class="sub_menu  current"><a class="diao" href="<?php echo U('Index/index');?>" hidefocus="true"><?php echo ($sval["name"]); ?></a></li>
			        <?php else: ?>
			    	<li class="sub_menu"><a class="diao" href="javascript:;" data-uri="<?php echo U($sval['controller_name'].'/'.$sval['action_name'], array('menuid'=>$sval['id'])); echo ($sval["data"]); ?>" data-id="<?php echo ($sval["id"]); ?>" hidefocus="true"><?php echo ($sval["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			       
			    </ul><?php endforeach; endif; else: echo "" ;endif; ?>-->
			</div>
		</div>
	</div>
</div>

<div class="maincontent">
	<div id="J_rframe" class="rframe_b">
        	<iframe id="rframe_0" src="<?php echo U('index/panel');?>" frameborder="0" scrolling="auto" style="height:700px;width:100%;"></iframe>
    </div>
</div>
	<br />
<div id="bar_bottom">
	<a class="float_left" href="javascript:;" id="show_desktop" title="桌面" onClick="gotoDisktop();">
      <img src="http://ee.headhunterclub.com/images/icon_22_desktop.png" />
    </a>
    <ul id="window_bar">
    </ul>
    <a class="float_right" href="javascript:;" id="suggestLink" title="请提出您宝贵的意见或建议。"><img src="http://ee.headhunterclub.com/images/suggest.png" /></a>
</div><!-- footer -->
</body>

<!--预览-->
	<!--查看大图-->
	<div class="popup-win">
		<div class="popup-inner">
			<div class="vertical-auto popup-image"><img src=""></div>
		</div>
	</div>
	<!--查看大图-->
<script>
	
$.dialog.open("http://ee.headhunterclub.com/resume/apply/show/"+id,{
		title :	'申请详情',
		width : '550px',
		height:	'350px',
		id	:	"resume_apply_show_" + id,
		lock  :	false,
		cancelVal: '关闭',
		cancel: true
	});
</script>

<script src="/hunter/theme/admin//js/pinphp.js"></script>
<script src="/hunter/theme/admin//js/index.js"></script>
<script src="/hunter/theme/admin/js/admin.js"></script>
<script>
//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);

</script>
<script>
	//顶部菜单点击
    $('#J_tmenu a').live('click', function(){
        var data_id = $(this).attr('data-id');
        //改变样式
        $(this).parent().addClass("current").siblings().removeClass("current");
        //改变左侧
        $('#J_lmenu').load($('#J_lmenu').attr('data-uri'), {menuid:data_id});
        $('#accordion').load($('#accordion').attr('data-uri'), {menuid:data_id});
//      //显示左侧菜单，当点击顶部时，展开左侧
//      $('#J_lmenu').parent().removeClass('left_menu_on');
//      $('html').removeClass('on');
//      $('#J_lmoc').removeClass('close').data('clicknum', 0);
    });
	//左侧菜单点击
	$('.sub_menu').eq(0).addClass('current');
  $('.sub_menu a').live('click', function(){
          data_uri = $(this).attr('data-uri');
          data_id = $(this).attr('data-id');
          $(this).parents('.sub_menu').addClass('current').siblings().removeClass('current');
		$('#rframe_0').attr('src', data_uri);
  });
  //刷新
	function refresh_page(){
		$('#J_rframe iframe:visible')[0].contentWindow.location.reload();
		
	}
document.onclick = function(e) {　　　　　　　　
     dropbox_hide();
}

function dropbox_hide(){
	$(".dropbox").hide();　
}
</script>
</html>