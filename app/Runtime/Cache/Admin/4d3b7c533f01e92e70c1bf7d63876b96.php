<?php if (!defined('THINK_PATH')) exit();?><!--<!doctype html>-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link rel="shortcut icon" href="/theme/admin/images/head.png">
<link rel="stylesheet" media="screen" href="http://ee.headhunterclub.com/css/style.css" />
<link rel="stylesheet" media="screen" href="theme/admin/css/style.css" />

</style>
<title><?php echo L('website_manage');?></title>

	<script>

	var URL = '/hunter/jradmin.php/Index';

	var SELF = '/hunter/jradmin.php?m=Admin&c=index&a=panel';

	var ROOT_PATH = '/hunter';

	var APP	 =	 '/hunter/jradmin.php';

	//语言项目

	var lang = new Object();

    <?php $_result=json_decode(L('js_lang_st'),true);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?>

	</script>

<script>
$(function() {
	var elm = $('.shortbar');
	var startPos = $(elm).offset().top;
	$.event.add(window, "scroll", function() {
		var p = $(window).scrollTop();
		if (p > startPos) {
			elm.addClass('sortbar-fixed');
		} else {
		    elm.removeClass('sortbar-fixed');

		}
	});
});
</script>
<style>
	.sortbar-fixed {
    margin: 0 auto;
    width: 100%;
    position: fixed!important;
    _position: absolute!important;
    z-index: 20000;
    top: 0;
    left: 0px;
    right: 0px;
</style> 
</head>



<body>

<div id="J_ajax_loading" class="ajax_loading" style="display:none;"><?php echo L('ajax_loading');?></div>
<!--
<?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav">

    <div class="content_menu ib_a blue line_x">

        <?php if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?>  

            <?php if(empty($val["dialog"])): ?><a href="<?php echo U($val['controller_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="add <?php echo ($val["class"]); ?>"><em><?php echo L($val['name']);?></em></a>

            <?php else: ?>

                <?php
 $size = explode('|',$val['dialog']); ?>

                <a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo U($val['controller_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" data-title="<?php echo ($val["name"]); ?>" data-id="<?php echo ($val["action_name"]); ?>" data-width="<?php echo ($size[0]); ?>" data-height="<?php echo ($size[1]); ?>"><em><?php echo ($val["name"]); ?></em></a>　<?php endif; endforeach; endif; else: echo "" ;endif; endif; ?>

    </div>

</div><?php endif; ?>-->

<link href="/hunter/theme/admin/css/style.css" rel="stylesheet"/>
<style type="text/css">
	.shares{overflow:hidden; zoom:1;}
	.shares li{float:left; width:49%; display:inline;}
	.shares li span{color:#999; font-size:10px;}
	.shares li a{padding-left:15px; background: url("/images/arrow.png") 0px 5px no-repeat;}
	.sharesWidth li{width:33%;}
	</style>
	<div class="two_third maincontent_inner ">
	    	<div class="left">
	        
	        	<div class="notification msginfo">
	            	<h3>远扬猎头管理系统（演示版）</h3>
	            	<p>欢迎您：<b><?php echo ($admin['username']); ?><!--(manager)--></b> <font color="#ff0000">(<?php echo ($admin['rolename']); ?>)</font> </p>
	            	
	            	<!--<p>所属部门：总公司</p>-->
	            	
	            	
	            	<p>登录次数：9254次&nbsp;&nbsp;最后登录时间：2019-01-09 13:23&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:;" onclick="showLogs();">登录日志</a></p>
	            </div>
	            
	            <div id="myCounts">
	
	
	
	<div class="reporter">
		<b>我的统计(总数)：</b> 客户(70)&nbsp;&nbsp;简历(1984)&nbsp;&nbsp;项目(150)&nbsp;&nbsp;报告(201)&nbsp;&nbsp;offer(79)&nbsp;&nbsp;跟进(1432)&nbsp;&nbsp;寻访(16)
		<a href="javascript:;" onclick="moreBraph();">&gt;&gt;更多</a>
	</div></div>
	            
	            <!-- START WIDGET LIST -->
	            <ul class="widgetlist">
	                
	                <li><a href="javascript:;" id="newCustomer"   class="J_showdialog" data-uri="<?php echo U('custom/add');?>" data-title="新增客户" data-id="add" data-width="900" data-height="500"><img src="http://ee.headhunterclub.com/images/plus_64.png" alt="新增客户"><span>新增客户</span></a></li>
	                
	                
	                <li><a href="javascript:;"    class="J_showdialog" data-uri="<?php echo U('project/add');?>" data-title="新增项目" data-id="add" data-width="900" data-height="500" id="newProject"><img src="http://ee.headhunterclub.com/images/tag_add_64.png" alt="新增项目"><span>新增项目</span></a></li>
	                
	                <li><a href="javascript:;" onclick="$('#emailLink').click();"><img src="http://ee.headhunterclub.com/images/mail.png" alt="邮件中心"><span>邮件中心</span></a></li>
	                <li><a href="javascript:;" onclick="$('#calendarLink').click();"><img src="http://ee.headhunterclub.com/images/calendar_64.png" alt="我的日历"><span>我的日历</span></a></li>
	            </ul>
	            <!-- END WIDGET LIST -->
	            <div class="clear"></div>
	            
	            <!-- shares -->
	            <div id="shares" class="tabs2 ui-tabs ui-widget ui-widget-content ui-corner-all" style="margin-top:-10px;">
	                <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
	                    
	                    <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#tabs-88">list</a></li>
	                    
	                    <li class="ui-state-default ui-corner-top"><a href="#tabs-89">共享资料</a></li>
	                    
	                    <li class="ui-state-default ui-corner-top ui-state-hover"><a href="#tabs-90">内部资料</a></li>
	                    
	                    <li class="ui-state-default ui-corner-top"><a href="#tabs-2827">jjjj</a></li>
	                    
	                    <li class="ui-state-default ui-corner-top"><a href="#tabs-2879">共享项目</a></li>
	                    
	                    <div style="float:right; margin:8px 10px 0 0;">
	                    	<a class="iconlink2" href="javascript:;" id="createShareFile">上传文档</a>
	                    	<a class="iconlink2" href="javascript:;" id="createShareArticle">新增文章</a>
	                    </div>
	                </ul>
	                
	                <div id="tabs-88" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="padding:10px;">
	                    
	                    <ul class="shares sharesWidth">
	                    	
	                    	<li><a href="javascript:sharePreview('b98ec92d-2c8e-441b-b2f1-f51100ae95bd','test')">test</a>&nbsp;&nbsp;<span>18-5-9 16:37</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('21b0fb79-950f-4a74-826c-8382ecb86941','aaaaaaaaaaa')">aaaaaaaaaaa</a>&nbsp;&nbsp;<span>17-9-11 15:16</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('3768f4b0-8d84-4548-b899-f9fa63cd7e30','益田集团')">益田集团</a>&nbsp;&nbsp;<span>14-5-16 1:5</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('3dea26bf-bc60-4ce1-aa93-f9cc464c291f','欢迎使用远扬猎头管理系统企业版')">欢迎使用远扬猎头管理系统企业版</a>&nbsp;&nbsp;<span>14-4-23 23:7</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('39635b40-fee4-49a5-ad2c-07ce554b4f24','益田集团通讯录汇总表')">益田集团通讯录汇总表</a>&nbsp;&nbsp;<span>13-11-13 18:15</span></li>
	                    	
	                    </ul>
	                    
	                    
	                </div>
	                
	                <div id="tabs-89" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" style="padding:10px;">
	                    
	                    <ul class="shares sharesWidth">
	                    	
	                    	<li><a href="javascript:sharePreview('dfe290e3-6b24-47b1-a04c-cf09b8d0a3aa','附带的答案是')">附带的答案是</a>&nbsp;&nbsp;<span>17-5-16 22:27</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('76e5febe-1d52-40bb-9751-e427fa42906d','aaaaaaaaaaa')">aaaaaaaaaaa</a>&nbsp;&nbsp;<span>16-10-10 12:9</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('097d258b-cbce-496c-a99a-bccd26435d75','对于纺织行业人才培养的思考')">对于纺织行业人才培养的思考</a>&nbsp;&nbsp;<span>16-7-31 13:16</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('6e582feb-af2d-4f7e-9f13-55ebd43f5422','华侨城通讯录')">华侨城通讯录</a>&nbsp;&nbsp;<span>14-7-3 16:47</span></li>
	                    	
	                    </ul>
	                    
	                    
	                </div>
	                
	                <div id="tabs-90" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" style="padding:10px;">
	                    
	                    <ul class="shares sharesWidth">
	                    	
	                    	<li><a href="javascript:sharePreview('4b482fbb-5947-43ac-a1a7-d52bddb2405a','软件研发高级总工')">软件研发高级总工</a>&nbsp;&nbsp;<span>17-12-5 14:39</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('a79c859e-a2d3-454d-b8f6-236188555ae3','安抚')">安抚</a>&nbsp;&nbsp;<span>17-11-6 14:14</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('2a2b782b-7c9d-4a2a-96ca-83fce04ba0dc','共享资料内部')">共享资料内部</a>&nbsp;&nbsp;<span>17-9-25 0:56</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('0d6bbd35-7fee-4f15-ba63-a7f9ec5750e2','同时')">同时</a>&nbsp;&nbsp;<span>15-8-27 22:34</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('852c31fa-4ea9-43ee-a8b9-71d046a190bb','圭圭')">圭圭</a>&nbsp;&nbsp;<span>15-5-14 17:28</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('bddc2deb-fcb4-44a3-a144-31bd4847e775','abc')">abc</a>&nbsp;&nbsp;<span>13-7-15 9:20</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('2af83496-bf90-49ae-93f2-5cbef36fe8b1','test11')">test11</a>&nbsp;&nbsp;<span>13-7-12 15:54</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('227c55c3-7df7-494b-b975-b29292f58c7d','test10')">test10</a>&nbsp;&nbsp;<span>13-7-12 15:54</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('7e268084-672a-4839-a7a3-164b49fec190','test9')">test9</a>&nbsp;&nbsp;<span>13-7-12 15:54</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('9960270b-d772-4169-992a-af8a09eda573','test8')">test8</a>&nbsp;&nbsp;<span>13-7-12 15:54</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('ba1c2619-41b6-486b-a506-7cd28cdfe667','test7')">test7</a>&nbsp;&nbsp;<span>13-7-12 15:53</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('cf4076f8-1259-4614-8741-ffad9ffc633b','test6')">test6</a>&nbsp;&nbsp;<span>13-7-12 15:53</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('bb9f2f33-4c3d-4d24-bea2-6814b0688d69','test5')">test5</a>&nbsp;&nbsp;<span>13-7-12 15:53</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('ab46623f-43c7-4b6a-b41f-6da47d8c5564','test4')">test4</a>&nbsp;&nbsp;<span>13-7-12 15:53</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('4c1fbd86-017e-42c7-8ede-e35f4d690738','test3')">test3</a>&nbsp;&nbsp;<span>13-7-12 15:53</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('f02b8a02-ad42-40a9-b243-318a38703b99','test1')">test1</a>&nbsp;&nbsp;<span>13-7-12 15:52</span></li>
	                    	
	                    </ul>
	                    
	                    
	                </div>
	                
	                <div id="tabs-2827" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" style="padding:10px;">
	                    
	                    
	                    <p>没有jjjj数据.</p>
	                    
	                </div>
	                
	                <div id="tabs-2879" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" style="padding:10px;">
	                    
	                    <ul class="shares sharesWidth">
	                    	
	                    	<li><a href="javascript:sharePreview('14479d98-5910-471e-ba23-6431fd0a396e','12312')">12312</a>&nbsp;&nbsp;<span>18-3-7 10:52</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('fb04f33b-0c17-4a00-ac4c-d5043083939a','需求项目经理一名，求推荐')">需求项目经理一名，求推荐</a>&nbsp;&nbsp;<span>17-11-6 14:50</span></li>
	                    	
	                    	<li><a href="javascript:sharePreview('a0ada41b-2237-499a-83b5-4bb071f5e388','项目经理')">项目经理</a>&nbsp;&nbsp;<span>17-11-6 14:45</span></li>
	                    	
	                    </ul>
	                    
	                    
	                </div>
	                
	            </div>
	            <!-- shares end -->
	            
	            <div class="clear"></div>
	            
	            <div class="widgetbox">
	            	<h3 class=""><span>待面试</span></h3>
	                <div class="content nopadding ohidden">
	                	<table cellpadding="0" cellspacing="0" class="sTable3" width="100%">
	                        <thead>
	                            <tr>
	                                <td>客户名称</td>
	                                <td>项目名称</td>
	                                <td>候选人</td>
	                                <td align="right">面试时间</td>
	                                <td>推荐人</td>
	                                <td>负责人</td>
	                                <td>创建时间</td>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('上海飒拉商业有限公司',24);">上海飒拉商业有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('区域陈列师-上海飒拉商业有限公司',37);">区域陈列师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('丁丁-推荐报告',58);">丁丁</a></td>
	                                <td align="right">2014-8-9 10:52</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2014-8-30 10:51</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('腾讯',22);">腾讯</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('fddasfasf-腾讯',36);">fddasfasf</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('杜-推荐报告',56);">杜</a></td>
	                                <td align="right">2014-8-19 1:50</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2014-8-19 1:50</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('上海飒拉商业有限公司',24);">上海飒拉商业有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('区域陈列师-上海飒拉商业有限公司',37);">区域陈列师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('丁丁-推荐报告',58);">丁丁</a></td>
	                                <td align="right">2014-8-23 10:52</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2014-8-30 10:52</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('zhonghaijijin',44);">zhonghaijijin</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('LLL-zhonghaijijin',61);">LLL</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('彭女士-推荐报告',86);">彭女士</a></td>
	                                <td align="right">2015-3-10 1:3</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2015-3-15 1:0</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中国电信',15);">中国电信</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('測試2-中国电信',76);">測試2</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('缪志柏-推荐报告',98);">缪志柏</a></td>
	                                <td align="right">2015-10-8 15:25</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2015-10-30 15:25</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('深圳燃气',41);">深圳燃气</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('区域经理-深圳燃气',57);">区域经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('南京师范大学王振江-推荐报告',103);">南京师范大学王振江</a></td>
	                                <td align="right">2016-4-13 21:39</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2016-4-17 21:40</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('大通证券股份有限公司1',60);">大通证券股份有限公司1</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('1111112305-大通证券股份有限公司1',77);">1111112305</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('常敏-目前不找工作-已加微信-推荐报告',109);">常敏-目前不找工作-已加微信</a></td>
	                                <td align="right">2016-6-23 16:9</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2016-6-23 16:7</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('深圳电信',2);">深圳电信</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('急需平面设计工程师-深圳电信',96);">急需平面设计工程师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('杨女士-推荐报告',117);">杨女士</a></td>
	                                <td align="right">2016-8-8 20:32</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2016-8-8 20:32</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('集团有限公司',68);">集团有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('龙城项目-集团有限公司',100);">龙城项目</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('16721662--男-32岁-本科-北京-目前离职，随时可以谈新机会-猎聘网简历-推荐报告',119);">16721662--男-32岁-本科-北京-目前离职，随时可以谈新机会-猎聘网简历</a></td>
	                                <td align="right">2016-9-22 9:54</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2016-9-19 9:52</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('集团有限公司',68);">集团有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('龙城项目-集团有限公司',100);">龙城项目</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('16721662--男-32岁-本科-北京-目前离职，随时可以谈新机会-猎聘网简历-推荐报告',119);">16721662--男-32岁-本科-北京-目前离职，随时可以谈新机会-猎聘网简历</a></td>
	                                <td align="right">2016-11-11 11:49</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2016-11-11 11:49</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('先路人',59);">先路人</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('ewrwerwer-先路人',110);">ewrwerwer</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('table12 - 副本-推荐报告',137);">table12 - 副本</a></td>
	                                <td align="right">2016-12-23 14:1</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2016-12-23 14:3</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('先路人',59);">先路人</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('ewrwerwer-先路人',110);">ewrwerwer</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('table12 - 副本-推荐报告',135);">table12 - 副本</a></td>
	                                <td align="right">2016-12-23 18:12</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2016-12-22 18:14</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('阳新公司',81);">阳新公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('业务经理-阳新公司',108);">业务经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('table12 - 副本-推荐报告',134);">table12 - 副本</a></td>
	                                <td align="right">2017-1-6 16:24</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2017-1-6 16:24</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('青岛云芝管理咨询有限公司',92);">青岛云芝管理咨询有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('测试1-青岛云芝管理咨询有限公司',118);">测试1</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('汤女士-推荐报告',145);">汤女士</a></td>
	                                <td align="right">2017-4-19 14:57</td>
	                                <td>管理员</td>
	                                <td>vg</td>
	                                <td>2017-4-18 14:53</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('违法未昂',90);">违法未昂</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('灌灌灌灌-违法未昂',121);">灌灌灌灌</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('胡建军-推荐报告',150);">胡建军</a></td>
	                                <td align="right">2017-6-5 10:30</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2017-5-26 11:44</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('123',124);">123</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('飞天小魔女-123',143);">飞天小魔女</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('速速-推荐报告',201);">速速</a></td>
	                                <td align="right">2019-1-17 14:11</td>
	                                <td>管理员</td>
	                                <td>管理员</td>
	                                <td>2019-1-8 14:11</td>
	                            </tr>
	                            
	                            
	                        </tbody>
	                    </table>
	                </div><!-- content -->
	
	            </div><!-- widgetbox2 -->
	            
	            <div class="widgetbox">
	            	<h3><span>待处理offer</span></h3>
	                <div class="content nopadding ohidden">
	                	<table cellpadding="0" cellspacing="0" class="sTable3" width="100%">
	                        <thead>
	                            <tr>
	                                <td>客户名称</td>
	                                <td>项目名称</td>
	                                <td>候选人</td>
	                                <td align="right">年薪</td>
	                                <td>当前状态</td>
	                                <td>创建人</td>
	                                <td>创建时间</td>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中铁',5);">中铁</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('HR Manager-中铁',14);">HR Manager</a></td>
	                                <td><a href="javascript:;" onclick="offerDetail('sdfsdf-offer',4);">sdfsdf</a></td>
	                                <td align="right">11.00</td>
	                                <td>
	                                	
							           	<font color="#ff6600">候选人、客户协商中</font>
							           	
										
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2013-10-18 14:27</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('1111',119);">1111</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('HH112-1111',158);">HH112</a></td>
	                                <td><a href="javascript:;" onclick="offerDetail('张三-offer',75);">张三</a></td>
	                                <td align="right">0.00</td>
	                                <td>
	                                	
							           	<font color="#ff6600">候选人、客户协商中</font>
							           	
										
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-12-12 11:30</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('深圳天天广告有限公司',137);">深圳天天广告有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('人事总监-深圳天天广告有限公司',165);">人事总监</a></td>
	                                <td><a href="javascript:;" onclick="offerDetail('tang11-offer',78);">tang11</a></td>
	                                <td align="right">100.00</td>
	                                <td>
	                                	
							           	<font color="#ff6600">候选人、客户协商中</font>
							           	
										
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-1-3 10:12</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('武汉某电力公司',134);">武汉某电力公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('电力工程师-武汉某电力公司',164);">电力工程师</a></td>
	                                <td><a href="javascript:;" onclick="offerDetail('速速-offer',82);">速速</a></td>
	                                <td align="right">11000.00</td>
	                                <td>
	                                	
							           	<font color="#ff6600">候选人、客户协商中</font>
							           	
										
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-3-6 16:47</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('深圳天天广告有限公司',137);">深圳天天广告有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('人事总监-深圳天天广告有限公司',165);">人事总监</a></td>
	                                <td><a href="javascript:;" onclick="offerDetail('苏苏-offer',84);">苏苏</a></td>
	                                <td align="right">11120.00</td>
	                                <td>
	                                	
							           	<font color="#ff6600">候选人、客户协商中</font>
							           	
										
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-4-2 22:8</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('深圳天天广告有限公司',137);">深圳天天广告有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('人事总监-深圳天天广告有限公司',165);">人事总监</a></td>
	                                <td><a href="javascript:;" onclick="offerDetail('速速-offer',85);">速速</a></td>
	                                <td align="right">1110.00</td>
	                                <td>
	                                	
							           	<font color="#ff6600">候选人、客户协商中</font>
							           	
										
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-4-2 22:18</td>
	                            </tr>
	                            
	                            
	                        </tbody>
	                    </table>
	                </div><!-- content -->
	
	            </div><!-- widgetbox2 -->
	            
	            <div class="widgetbox">
	            	<h3><span>待处理推荐报告</span></h3>
	                <div class="content nopadding ohidden">
	                	<table cellpadding="0" cellspacing="0" class="sTable3" width="100%">
	                        <thead>
	                            <tr>
	                                <td>客户名称</td>
	                                <td>项目名称</td>
	                                <td>候选人</td>
	                                <td align="right">期望年薪</td>
	                                <td>当前状态</td>
	                                <td>推荐人</td>
	                                <td>推荐时间</td>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('太平洋公司',10);">太平洋公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('客服-太平洋公司',15);">客服</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('一休哥-推荐报告',18);">一休哥</a></td>
	                                <td align="right">5000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2013-10-30 14:16</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('太平洋公司',10);">太平洋公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('CRM-太平洋公司',16);">CRM</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('lucene-推荐报告',24);">lucene</a></td>
	                                <td align="right">100000</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-2-8 16:54</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('asdfasdf',6);">asdfasdf</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('项目一-asdfasdf',7);">项目一</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('应聘公司：-推荐报告',26);">应聘公司：</a></td>
	                                <td align="right">1000000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-2-13 12:28</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('wanke ',19);">wanke </a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('人事经理-wanke ',21);">人事经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('宫丽莉3-推荐报告',27);">宫丽莉3</a></td>
	                                <td align="right">20000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-3-27 17:11</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中国电信000',11);">中国电信000</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('总经理-中国电信000',17);">总经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('张丽平-推荐报告',28);">张丽平</a></td>
	                                <td align="right">1</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-4-4 16:50</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('中国测试公司1',18);">中国测试公司1</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('问问热热热-中国测试公司1',24);">问问热热热</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('杨先生-推荐报告',29);">杨先生</a></td>
	                                <td align="right">20万</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-4-23 22:42</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中国移动',13);">中国移动</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('111111-中国移动',30);">111111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('邹鹏-推荐报告',40);">邹鹏</a></td>
	                                <td align="right">20-30</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-6-6 22:35</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('wanke ',19);">wanke </a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('人事经理-wanke ',21);">人事经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('【测试用例05】已评估过旧简历（含发表论文）-推荐报告',41);">【测试用例05】已评估过旧简历（含发表论文）</a></td>
	                                <td align="right">30万/年</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-6-10 14:6</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中铁',5);">中铁</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('HR Manager-中铁',14);">HR Manager</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('张三-推荐报告',42);">张三</a></td>
	                                <td align="right">1100000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-6-10 14:9</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('wanke ',19);">wanke </a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('人事经理-wanke ',21);">人事经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('何玉龙1-推荐报告',43);">何玉龙1</a></td>
	                                <td align="right">20w</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-6-11 18:7</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中国测试公司1',18);">中国测试公司1</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('测试公司-中国测试公司1',33);">测试公司</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('韩薇简历-(201461521463)-推荐报告',44);">韩薇简历-(201461521463)</a></td>
	                                <td align="right">50</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-6-26 17:34</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('中国测试公司1',18);">中国测试公司1</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('测试公司-中国测试公司1',33);">测试公司</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('搜索-推荐报告',45);">搜索</a></td>
	                                <td align="right">30000</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-7-4 15:7</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中国测试公司1',18);">中国测试公司1</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('和君合伙人-中国测试公司1',34);">和君合伙人</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('123-推荐报告',46);">123</a></td>
	                                <td align="right"></td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-7-11 16:31</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('多岁的风格',4);">多岁的风格</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('项目五-多岁的风格',12);">项目五</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('杨先生-推荐报告',47);">杨先生</a></td>
	                                <td align="right">100000</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>助理顾问</td>
	                                <td>2014-7-22 17:23</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('太平洋公司',10);">太平洋公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('texst1111-太平洋公司',35);">texst1111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('李晓立-推荐报告',49);">李晓立</a></td>
	                                <td align="right"> 啊发生大幅</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-7-28 14:54</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('太平洋公司',10);">太平洋公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('texst1111-太平洋公司',35);">texst1111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('午饭晚饭-推荐报告',51);">午饭晚饭</a></td>
	                                <td align="right">阿呆沙发</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-7-28 15:59</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('太平洋公司',10);">太平洋公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('texst1111-太平洋公司',35);">texst1111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('【测试用例09】猎头推荐简历格式01-推荐报告',53);">【测试用例09】猎头推荐简历格式01</a></td>
	                                <td align="right">啊是否</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-7-28 16:7</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('太平洋公司',10);">太平洋公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('texst1111-太平洋公司',35);">texst1111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('我问问-推荐报告',54);">我问问</a></td>
	                                <td align="right">1</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-7-28 16:10</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('太平洋公司',10);">太平洋公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('texst1111-太平洋公司',35);">texst1111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('최대협-推荐报告',55);">최대협</a></td>
	                                <td align="right">2</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-7-28 16:11</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('腾讯',22);">腾讯</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('fddasfasf-腾讯',36);">fddasfasf</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('杜-推荐报告',56);">杜</a></td>
	                                <td align="right">5555</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-8-19 1:50</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('上海飒拉商业有限公司',24);">上海飒拉商业有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('区域陈列师-上海飒拉商业有限公司',37);">区域陈列师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('陈超-推荐报告',57);">陈超</a></td>
	                                <td align="right">1</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-8-29 7:52</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('上海飒拉商业有限公司',24);">上海飒拉商业有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('区域陈列师-上海飒拉商业有限公司',37);">区域陈列师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('丁丁-推荐报告',58);">丁丁</a></td>
	                                <td align="right">11</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-8-30 10:51</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('上海飒拉商业有限公司',24);">上海飒拉商业有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('区域陈列师-上海飒拉商业有限公司',37);">区域陈列师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('BBB-推荐报告',59);">BBB</a></td>
	                                <td align="right">1</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-9-16 14:42</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('1212121',25);">1212121</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('总经理-1212121',38);">总经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('问问-推荐报告',60);">问问</a></td>
	                                <td align="right">100000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-9-17 14:37</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('1212121',25);">1212121</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('总经理-1212121',38);">总经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('丁丁-推荐报告',62);">丁丁</a></td>
	                                <td align="right">1</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-10-9 10:24</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('北就四方荣达',28);">北就四方荣达</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('版权总监-北就四方荣达',42);">版权总监</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('51job_陳盛祥(87984777)-推荐报告',64);">51job_陳盛祥(87984777)</a></td>
	                                <td align="right">33332s</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>助理顾问</td>
	                                <td>2014-11-4 10:54</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中国电力',14);">中国电力</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('总经理-中国电力',51);">总经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('李建华-推荐报告',74);">李建华</a></td>
	                                <td align="right">15</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2014-12-2 17:3</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('太平洋公司',10);">太平洋公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('texst1111-太平洋公司',35);">texst1111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('.net简历-推荐报告',77);">.net简历</a></td>
	                                <td align="right">1</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-1-5 15:47</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('欧菲光',35);">欧菲光</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('产品经理-欧菲光',52);">产品经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('森誉钱艳磊-推荐报告',78);">森誉钱艳磊</a></td>
	                                <td align="right">10000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-1-12 14:35</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('万达长沙',40);">万达长沙</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('陕西周至工地1-万达长沙',59);">陕西周至工地1</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('彭女士-推荐报告',84);">彭女士</a></td>
	                                <td align="right">f</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-1-30 14:4</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('zhonghaijijin',44);">zhonghaijijin</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('qudaojingli-zhonghaijijin',60);">qudaojingli</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('江泽民-推荐报告',85);">江泽民</a></td>
	                                <td align="right">3000</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-1-30 16:58</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('zhonghaijijin',44);">zhonghaijijin</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('LLL-zhonghaijijin',61);">LLL</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('彭女士-推荐报告',86);">彭女士</a></td>
	                                <td align="right">4654465</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-1-30 17:6</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('zhonghaijijin',44);">zhonghaijijin</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('LLL-zhonghaijijin',61);">LLL</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('彭女士-推荐报告',87);">彭女士</a></td>
	                                <td align="right">4654465</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-1-30 17:6</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('zhonghaijijin',44);">zhonghaijijin</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('wwwwww-zhonghaijijin',62);">wwwwww</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('罗艳青简历-推荐报告',88);">罗艳青简历</a></td>
	                                <td align="right">300000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-2-16 14:51</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('合肥工业大学',47);">合肥工业大学</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('东莞-合肥工业大学',64);">东莞</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('11-推荐报告',90);">11</a></td>
	                                <td align="right"></td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-4-7 14:10</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('天缘',50);">天缘</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('天缘-天缘',66);">天缘</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('智 丁玉亮4.2-推荐报告',93);">智 丁玉亮4.2</a></td>
	                                <td align="right">3500</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-4-21 9:23</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('万达长沙',40);">万达长沙</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('总经理助理-万达长沙',71);">总经理助理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('李-推荐报告',96);">李</a></td>
	                                <td align="right">15</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-6-4 12:35</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('效益公司',57);">效益公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('招聘经理11111-效益公司',73);">招聘经理11111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('黄晨-推荐报告',97);">黄晨</a></td>
	                                <td align="right">20万</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-6-5 17:6</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中国电信',15);">中国电信</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('測試2-中国电信',76);">測試2</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('缪志柏-推荐报告',98);">缪志柏</a></td>
	                                <td align="right">5000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-8-26 15:10</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail(' 金水国税',39);"> 金水国税</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('磊- 金水国税',55);">磊</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('白翎轩翊-18087315017-男-2015_07_02-BSHXILIP-推荐报告',100);">白翎轩翊-18087315017-男-2015_07_02-BSHXILIP</a></td>
	                                <td align="right">80</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-8-29 17:41</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('大通证券股份有限公司1',60);">大通证券股份有限公司1</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('保代-大通证券股份有限公司1',80);">保代</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('白翎轩翊-18087315017-男-2015_07_02-BSHXILIP-推荐报告',101);">白翎轩翊-18087315017-男-2015_07_02-BSHXILIP</a></td>
	                                <td align="right">80</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-9-1 13:15</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('深圳燃气',41);">深圳燃气</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('区域经理-深圳燃气',57);">区域经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('南京师范大学王振江-推荐报告',103);">南京师范大学王振江</a></td>
	                                <td align="right">11</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2015-12-1 20:18</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中国电信',15);">中国电信</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('測試項目-中国电信',75);">測試項目</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('51job_刘丰年(57382882)-推荐报告',106);">51job_刘丰年(57382882)</a></td>
	                                <td align="right">30w</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-2-29 18:59</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('大通证券股份有限公司1',60);">大通证券股份有限公司1</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('1111112305-大通证券股份有限公司1',77);">1111112305</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('常敏-目前不找工作-已加微信-推荐报告',109);">常敏-目前不找工作-已加微信</a></td>
	                                <td align="right">东方闪电</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-6-23 16:6</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('大通证券股份有限公司1',60);">大通证券股份有限公司1</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('1111112305-大通证券股份有限公司1',77);">1111112305</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('陈志军-推荐报告',110);">陈志军</a></td>
	                                <td align="right">待定</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-6-23 20:46</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('深圳电信',2);">深圳电信</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('急需平面设计工程师-深圳电信',96);">急需平面设计工程师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('陈志军-推荐报告',116);">陈志军</a></td>
	                                <td align="right">3.5</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-8-4 16:25</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('深圳电信',2);">深圳电信</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('急需平面设计工程师-深圳电信',96);">急需平面设计工程师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('杨女士-推荐报告',117);">杨女士</a></td>
	                                <td align="right">5554</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-8-4 16:42</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('集团有限公司',68);">集团有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('龙城项目-集团有限公司',100);">龙城项目</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('16721662--男-32岁-本科-北京-目前离职，随时可以谈新机会-猎聘网简历-推荐报告',119);">16721662--男-32岁-本科-北京-目前离职，随时可以谈新机会-猎聘网简历</a></td>
	                                <td align="right">587578</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-9-1 9:39</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('集团有限公司',68);">集团有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('犯罪的依法-集团有限公司',102);">犯罪的依法</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('陈美凤-推荐报告',122);">陈美凤</a></td>
	                                <td align="right">fsdfsd</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-10-24 21:26</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('天缘',50);">天缘</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('产品经理-天缘',98);">产品经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('秦俊杰kgk-推荐报告',124);">秦俊杰kgk</a></td>
	                                <td align="right">30-40</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-10-25 21:2</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('test',78);">test</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('testb-test',105);">testb</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('秦俊杰kgk-推荐报告',125);">秦俊杰kgk</a></td>
	                                <td align="right">34</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>顾问</td>
	                                <td>2016-10-26 17:3</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('阳新公司',81);">阳新公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('业务经理-阳新公司',108);">业务经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('table12 - 副本-推荐报告',134);">table12 - 副本</a></td>
	                                <td align="right">88</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-12-22 18:5</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('先路人',59);">先路人</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('ewrwerwer-先路人',110);">ewrwerwer</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('table12 - 副本-推荐报告',135);">table12 - 副本</a></td>
	                                <td align="right">243</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-12-22 18:13</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('122344444',80);">122344444</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('22222-122344444',107);">22222</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('未知-推荐报告',136);">未知</a></td>
	                                <td align="right">11</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-12-23 13:44</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('先路人',59);">先路人</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('ewrwerwer-先路人',110);">ewrwerwer</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('table12 - 副本-推荐报告',137);">table12 - 副本</a></td>
	                                <td align="right"></td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2016-12-23 14:2</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('阳新公司',81);">阳新公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('业务经理-阳新公司',108);">业务经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('董明哲--电话未通-推荐报告',139);">董明哲--电话未通</a></td>
	                                <td align="right">23</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-1-6 17:6</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('中国电信34343',91);">中国电信34343</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('财务总监-中国电信34343',114);">财务总监</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('FICO CV_Qian Jun-推荐报告',142);">FICO CV_Qian Jun</a></td>
	                                <td align="right">23</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-2-20 10:28</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('青岛云芝管理咨询有限公司',92);">青岛云芝管理咨询有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('财务经理-青岛云芝管理咨询有限公司',117);">财务经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('吴振涛-推荐报告',144);">吴振涛</a></td>
	                                <td align="right">50000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-3-26 20:19</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('青岛云芝管理咨询有限公司',92);">青岛云芝管理咨询有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('测试1-青岛云芝管理咨询有限公司',118);">测试1</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('汤女士-推荐报告',145);">汤女士</a></td>
	                                <td align="right">10000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-3-29 14:47</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('青岛云芝管理咨询有限公司',92);">青岛云芝管理咨询有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('财务经理-青岛云芝管理咨询有限公司',117);">财务经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('胡建军-推荐报告',146);">胡建军</a></td>
	                                <td align="right"></td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-4-19 22:23</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('违法未昂',90);">违法未昂</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('灌灌灌灌-违法未昂',121);">灌灌灌灌</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('胡建军-推荐报告',150);">胡建军</a></td>
	                                <td align="right">100万</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-5-26 11:40</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('DT有限公司',121);">DT有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('线程做相册-DT有限公司',140);">线程做相册</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('0清爽10-应届生-推荐报告',157);">0清爽10-应届生</a></td>
	                                <td align="right">100</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-9-26 10:0</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('123',124);">123</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('飞天小魔女-123',143);">飞天小魔女</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('复古风格-推荐报告',159);">复古风格</a></td>
	                                <td align="right">`11</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-9-27 18:34</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('123',124);">123</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('飞天小魔女-123',143);">飞天小魔女</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('0清爽10-应届生-推荐报告',160);">0清爽10-应届生</a></td>
	                                <td align="right"></td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-10-10 20:52</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('1111',119);">1111</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('java-1111',145);">java</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('智联招聘_李继伟_猎头助理_中文_20170401_05232672-推荐报告',162);">智联招聘_李继伟_猎头助理_中文_20170401_05232672</a></td>
	                                <td align="right">23</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>部门经理</td>
	                                <td>2017-10-11 18:12</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('DT有限公司',121);">DT有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('某某某-DT有限公司',147);">某某某</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('阎姝-推荐报告',163);">阎姝</a></td>
	                                <td align="right">10K</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-10-17 14:45</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('1212121',25);">1212121</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('上山打老虎-1212121',50);">上山打老虎</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('张三-推荐报告',181);">张三</a></td>
	                                <td align="right">7000</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-11-30 19:6</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('深圳天天广告有限公司',137);">深圳天天广告有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('人事总监-深圳天天广告有限公司',165);">人事总监</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('7330707259-推荐报告',185);">7330707259</a></td>
	                                <td align="right">8000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-12-17 15:19</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('深圳天天广告有限公司',137);">深圳天天广告有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('人事总监-深圳天天广告有限公司',165);">人事总监</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('7354670913-推荐报告',187);">7354670913</a></td>
	                                <td align="right">1000000.00</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2017-12-27 11:30</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('深圳天天广告有限公司',137);">深圳天天广告有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('人事总监-深圳天天广告有限公司',165);">人事总监</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('7356410413-推荐报告',189);">7356410413</a></td>
	                                <td align="right">eee</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-1-18 12:3</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('武汉某电力公司',134);">武汉某电力公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('电力工程师-武汉某电力公司',164);">电力工程师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('左静飞dbe26afaZ02c97722-unity3d程序员-深圳市神马壹佰科技有限公司-男-24岁-本科-1年以上工作经验-猎聘网简历-推荐报告',191);">左静飞dbe26afaZ02c97722-unity3d程序员-深圳市神马壹佰科技有限公司-男-24岁-本科-1年以上工作经验-猎聘网简历</a></td>
	                                <td align="right">123</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-1-23 11:56</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('深圳3号sdf',129);">深圳3号sdf</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('111-深圳3号sdf',169);">111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('郭莹彦简历-推荐报告',196);">郭莹彦简历</a></td>
	                                <td align="right"></td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-3-2 11:23</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('深圳3号sdf',129);">深圳3号sdf</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('111-深圳3号sdf',169);">111</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('郭莹彦简历-推荐报告',197);">郭莹彦简历</a></td>
	                                <td align="right">5677</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-3-6 9:48</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('123',124);">123</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('飞天小魔女-123',143);">飞天小魔女</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('速速-推荐报告',201);">速速</a></td>
	                                <td align="right">130000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-3-6 16:48</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('武汉某电力公司',134);">武汉某电力公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('电力工程师-武汉某电力公司',164);">电力工程师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('速速-推荐报告',203);">速速</a></td>
	                                <td align="right">234242</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-3-8 9:38</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('山东海风临沂制造公司',147);">山东海风临沂制造公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('IT总监-山东海风临沂制造公司',171);">IT总监</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('胡晓冬女士_20171124-FJL001032525367-推荐报告',204);">胡晓冬女士_20171124-FJL001032525367</a></td>
	                                <td align="right">6200</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-3-13 14:8</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('小米',148);">小米</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('招人、攻城狮-小米',173);">招人、攻城狮</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('Jack-推荐报告',206);">Jack</a></td>
	                                <td align="right">1</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-3-21 11:3</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('小米',148);">小米</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('招人、攻城狮-小米',173);">招人、攻城狮</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('性别-推荐报告',207);">性别</a></td>
	                                <td align="right">11111</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-4-2 21:56</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('全称阿里巴巴公司',94);">全称阿里巴巴公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('首钢-全称阿里巴巴公司',176);">首钢</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('丁先生-推荐报告',210);">丁先生</a></td>
	                                <td align="right">6000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-4-21 10:7</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('全称阿里巴巴公司',94);">全称阿里巴巴公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('中级结构-全称阿里巴巴公司',174);">中级结构</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('机场接机岗-姚慧-推荐报告',211);">机场接机岗-姚慧</a></td>
	                                <td align="right">88</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-4-26 17:15</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('小米',148);">小米</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('CTO-小米',178);">CTO</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('未知-推荐报告',213);">未知</a></td>
	                                <td align="right">22</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-5-12 13:32</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('全称阿里巴巴公司',94);">全称阿里巴巴公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('中级结构-全称阿里巴巴公司',174);">中级结构</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('黄雄斌婚姻状况-推荐报告',215);">黄雄斌婚姻状况</a></td>
	                                <td align="right">1</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-5-14 17:15</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('公司全称公司全222称公司全称公司全称公司全称公司全称公司全称公司全称',153);">公司全称公司全222称公司全称公司全称公司全称公司全称公司全称公司全称</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('二建二建-公司全称公司全222称公司全称公司全称公司全称公司全称公司全称公司全称',180);">二建二建</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('Jack项-推荐报告',217);">Jack项</a></td>
	                                <td align="right">120000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-6-11 23:22</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('天源有限公司',49);">天源有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('2-天源有限公司',181);">2</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('13585953159-女-上海_上海-it互联网_电子通信_金融_财务_食品_饮料_烟酒_日化_地产_销售_市场_贸易_零售_经理--本科_硕士-推荐报告',218);">13585953159-女-上海_上海-it互联网_电子通信_金融_财务_食品_饮料_烟酒_日化_地产_销售_市场_贸易_零售_经理--本科_硕士</a></td>
	                                <td align="right">100</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-8-8 17:31</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('广州特猎商务服务有限公司',157);">广州特猎商务服务有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('中级结构工程师-广州特猎商务服务有限公司',186);">中级结构工程师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('13585953159-女-上海_上海-it互联网_电子通信_金融_财务_食品_饮料_烟酒_日化_地产_销售_市场_贸易_零售_经理--本科_硕士-推荐报告',219);">13585953159-女-上海_上海-it互联网_电子通信_金融_财务_食品_饮料_烟酒_日化_地产_销售_市场_贸易_零售_经理--本科_硕士</a></td>
	                                <td align="right">1000</td>
	                                <td>
	                                	
							           	<font color="#0080C0">待面试</font>
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-9-26 14:54</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('广州特猎商务服务有限公司',157);">广州特猎商务服务有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('中级结构工程师-广州特猎商务服务有限公司',186);">中级结构工程师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('杨工2-推荐报告',220);">杨工2</a></td>
	                                <td align="right"></td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-9-26 14:59</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('深圳大大',161);">深圳大大</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('123-深圳大大',188);">123</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('黄巍-推荐报告',222);">黄巍</a></td>
	                                <td align="right">50000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2018-11-12 14:29</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('广州特猎商务服务有限公司',157);">广州特猎商务服务有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('中级结构工程师-广州特猎商务服务有限公司',186);">中级结构工程师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('吕先生-推荐报告',223);">吕先生</a></td>
	                                <td align="right">9000</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2019-1-8 16:23</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('广州特猎商务服务有限公司',157);">广州特猎商务服务有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('中级结构工程师-广州特猎商务服务有限公司',186);">中级结构工程师</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('智联招聘_周生_招聘经理_中文_20190107_1546844066237-推荐报告',225);">智联招聘_周生_招聘经理_中文_20190107_1546844066237</a></td>
	                                <td align="right">5000</td>
	                                <td>
	                                	<font color="#808080">待推荐</font>
							           	
							           	
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2019-1-8 16:45</td>
	                            </tr>
	                            
	                            <tr class="even">
	                                <td><a href="javascript:;" onclick="customerDetail('宁波海亚特滚子有限公司',155);">宁波海亚特滚子有限公司</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('海亚特人力资源经理-宁波海亚特滚子有限公司',184);">海亚特人力资源经理</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('邹先生-推荐报告',226);">邹先生</a></td>
	                                <td align="right">6000</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2019-1-9 10:16</td>
	                            </tr>
	                            
	                            <tr>
	                                <td><a href="javascript:;" onclick="customerDetail('dscds',160);">dscds</a></td>
	                                <td><a href="javascript:;" onclick="projectDetail('test2-dscds',189);">test2</a></td>
	                                <td><a href="javascript:;" onclick="projectReportDetail('15000-25000元/月-推荐报告',227);">15000-25000元/月</a></td>
	                                <td align="right">5200</td>
	                                <td>
	                                	
							           	
							           	<font color="#FF8000">面试中..</font>
							           	
							           	
	                                </td>
	                                <td>管理员</td>
	                                <td>2019-1-9 10:28</td>
	                            </tr>
	                            
	                            
	                        </tbody>
	                    </table>
	                </div><!-- content -->
	
	            </div><!-- widgetbox2 -->
	            
	        </div><!-- left -->            
	    </div><!-- two_third -->
	    
	    <div class="one_third last">
	
	    	<div class="right">
	        	<div class="widgetbox3">
	                <h3><span>公告&amp;通知<a class="iconlink2" href="javascript:;" onclick="noticeManager();" style="float:right;">公告管理</a></span></h3>
	                <div class="content messagelist">
		                <ul>
		                	
		                	
		                	<li style="word-break:break-all; cursor: pointer;" onclick="noticeDetail(' 人力资源管理系统是一套构建JavaJ2E上的猎头公司办公业务管理软件',15);">
		                		<a href="javascript:;" style="color:#006699;"> 人力资源管理系统是一套构建JavaJ2E上的猎头公司办公业务管理软件</a>
		                		<span>管理员&nbsp;&nbsp;2018-4-28 8:39</span>
		                	</li>
		                	
		                	<li style="word-break:break-all; cursor: pointer;" onclick="noticeDetail('2018年五一节假日放假通知',12);">
		                		<a href="javascript:;" style="color:#006699;">2018年五一节假日放假通知</a>
		                		<span>管理员&nbsp;&nbsp;2017-5-24 19:18</span>
		                	</li>
		                	
		                	<li style="word-break:break-all; cursor: pointer;" onclick="noticeDetail('关于五一出游活动的通知',10);">
		                		<a href="javascript:;" style="color:#006699;">关于五一出游活动的通知</a>
		                		<span>管理员&nbsp;&nbsp;2017-5-24 19:17</span>
		                	</li>
		                	
		                	<li style="word-break:break-all; cursor: pointer;" onclick="noticeDetail('欢迎测试使用人力资源管理系统',3);">
		                		<a href="javascript:;" style="color:#006699;">欢迎测试使用人力资源管理系统</a>
		                		<span>管理员&nbsp;&nbsp;2013-11-5 16:37</span>
		                	</li>
		                	
		                </ul>
	                </div>
	            </div>
	           
	            <div class="widgetbox">
	                <h3 class=""><span>本月收入预计</span></h3>
	                <div class="content">
	                    <h1 class="prize">0.00</h1>
	                    <p>业绩提成（注：当前结算设置为：<font color="#ff0000">到帐即结算</font>）</p>
	                	
	                    <br>
						
						<h1 class="prize">0.00</h1>
	                    <p>扣除，可在提成明细中查看相关明细。</p>
	                	
	                    <br>
	                    
	                	<div class="one_half bright">
	                    	<h2 class="prize">0.00</h2>
	                        <small>基本工资</small>
	                    </div><!--one_half-->
	                    
	                    <div class="one_half last">
	                    	<h2 class="prize">0.00</h2>
	                        <small>收入预计总合</small>
	
	                    </div><!--one_half--> 
	                    
	                </div><!-- content -->
	            </div><!-- widgetbox -->
	            
	            <div id="myTasks">
	
	
	
	<div class="accordion ui-accordion ui-widget ui-helper-reset ui-accordion-icons" id="deskTasks" role="tablist">
		
			<h3 class="ui-accordion-header ui-helper-reset ui-state-default ui-state-active ui-corner-top" role="tab" aria-expanded="true" aria-selected="true" tabindex="0"><span class="ui-icon ui-icon-triangle-1-s"></span><a href="javascript:;">
		    	我的任务
		    	
		    	
		    	
		    	
		    	
		    	（2017-05-01至2020-05-31）
		    </a></h3>
		    <div class="content ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" role="tabpanel" style="height: 286px;">
		        
		        	<p>测试任务</p>
		        
		        <div class="progress">
		              业绩(任务：150000，已完成：418629.22)
		            <div class="bar2"><div class="value greenbar" style="width: 100%;"><small>279.09%</small></div></div>
		        </div>
		        
		        <div class="progress">
		        	客户(任务：50家，已完成：18)
		            <div class="bar2"><div class="value redbar" style="width: 36.0%;"><small>36.0%</small></div></div>
		        </div>
		        
		        
		        <div class="progress">
		        	上传简历(任务：50份，已完成：523)
		            <div class="bar2"><div class="value greenbar" style="width: 100%;"><small>1046.0%</small></div></div>
		        </div>
		        
		        
		        <div class="progress">
		        	推荐报告(任务：50份，已完成：66)
		            <div class="bar2"><div class="value greenbar" style="width: 100%;"><small>132.0%</small></div></div>
		        </div>
		        
		        
		        <div class="progress">
		        	offer(任务：50个，已完成：32)
		            <div class="bar2"><div class="value bluebar" style="width: 64.0%;"><small>64.0%</small></div></div>
		        </div>
		        
		    </div>
		
		
	    
	    	<h3 class="ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" role="tab" aria-expanded="false" aria-selected="false" tabindex="-1"><span class="ui-icon ui-icon-triangle-1-e"></span><a href="javascript:;">
	    	团队任务
	    	
	    	
	    	
	    	
	    	
	    	（2018-02-01至2019-02-28）
	    	</a></h3>
	    	<div class="content ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" role="tabpanel" style="height: 286px; display: none;">
		        
		        	<p>555555</p>
		        
		        <div class="progress">
		              业绩(任务：500000，已完成：16799.99)
		            <div class="bar2"><div class="value redbar" style="width: 3.36%;"><small>3.36%</small></div></div>
		        </div>
		        
		        <div class="progress">
		        	客户(任务：20家，已完成：18)
		            <div class="bar2"><div class="value bluebar" style="width: 90.0%;"><small>90.0%</small></div></div>
		        </div>
		        
		        
		        <div class="progress">
		        	上传简历(任务：5000份，已完成：246)
		            <div class="bar2"><div class="value redbar" style="width: 4.92%;"><small>4.92%</small></div></div>
		        </div>
		        
		        
		        <div class="progress">
		        	推荐报告(任务：200份，已完成：31)
		            <div class="bar2"><div class="value redbar" style="width: 15.5%;"><small>15.5%</small></div></div>
		        </div>
		        
		        
		        <div class="progress">
		        	offer(任务：50个，已完成：11)
		            <div class="bar2"><div class="value redbar" style="width: 22.0%;"><small>22.0%</small></div></div>
		        </div>
		        
		    </div>
	    
	    	<h3 class="ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" role="tab" aria-expanded="false" aria-selected="false" tabindex="-1"><span class="ui-icon ui-icon-triangle-1-e"></span><a href="javascript:;">
	    	团队任务
	    	
	    	
	    	
	    	
	    	
	    	（2018-05-01至2021-05-31）
	    	</a></h3>
	    	<div class="content ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" role="tabpanel" style="height: 286px; display: none;">
		        
		        	<p>本月业绩目标</p>
		        
		        <div class="progress">
		              业绩(任务：500000，已完成：10499.99)
		            <div class="bar2"><div class="value redbar" style="width: 2.1%;"><small>2.1%</small></div></div>
		        </div>
		        
		        <div class="progress">
		        	客户(任务：10家，已完成：10)
		            <div class="bar2"><div class="value greenbar" style="width: 100.0%;"><small>100.0%</small></div></div>
		        </div>
		        
		        
		        <div class="progress">
		        	上传简历(任务：400份，已完成：227)
		            <div class="bar2"><div class="value bluebar" style="width: 56.75%;"><small>56.75%</small></div></div>
		        </div>
		        
		        
		        <div class="progress">
		        	推荐报告(任务：20份，已完成：14)
		            <div class="bar2"><div class="value bluebar" style="width: 70.0%;"><small>70.0%</small></div></div>
		        </div>
		        
		        
		        <div class="progress">
		        	offer(任务：5个，已完成：3)
		            <div class="bar2"><div class="value bluebar" style="width: 60.0%;"><small>60.0%</small></div></div>
		        </div>
		        
		    </div>
	    
	    
	</div></div>
	            
	            <p>&nbsp;</p>
	            
	    	</div><!--right-->
	    </div><!--one_third last-->
	    
	    <br clear="all">

<script type="text/javascript" src="/hunter/theme/admin/js/jquery.js"></script>
<script src="/hunter/theme/admin/js/jquery/plugins/jquery.tools.min.js"></script>
<script src="/hunter/theme/admin/js/jquery/plugins/formvalidator.js"></script>
<script src="/hunter/theme/admin/js/pinphp.js"></script>
<script src="/hunter/theme/admin/js/admin.js"></script>
<script type="text/javascript" src="/theme/admin/js/laydate.js"></script>
<link type="text/css" rel="stylesheet" href="/theme/admin/css/laydate.css">
<link type="text/css" rel="stylesheet" href="/theme/admin/css/laydate(1).css">
<script>
//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script>

<?php if(isset($list_table)): ?><script src="/hunter/theme/admin/js/jquery/plugins/listTable.js"></script>

<script src="/hunter/theme/admin/js/kindeditor/kindeditor.js"></script>

<script>
var editor;  //全局变量
$(function(){
	 
$('.J_tablelist').listTable();
editor =KindEditor.create('.info', {
		uploadJson : '<?php echo U("attachment/editer_upload");?>',
		fileManagerJson : '<?php echo U("attachment/editer_manager");?>',
		allowFileManager : true,
		 items : [
                    'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link','fullscreen']

});

});
</script><?php endif; ?>
<script src="/theme/admin/js/jquery/plugins/listTable.js"></script>

<script src="/theme/admin/js/kindeditor/kindeditor.js"></script>

<script>
var editor;  //全局变量
$(function(){
	 
$('.J_tablelist').listTable();
editor =KindEditor.create('.info', {
		uploadJson : '/jradmin.php?m=Admin&c=attachment&a=editer_upload',
		fileManagerJson : '/jradmin.php?m=Admin&c=attachment&a=editer_manager',
		allowFileManager : true,
		 items : [
                    'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link','fullscreen']

});

});
</script><script src="/theme/admin/js/kindeditor/kindeditor.js"></script>