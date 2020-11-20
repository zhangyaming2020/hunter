<?php if (!defined('THINK_PATH')) exit();?>
<?php if($search['project_id']): else: ?>
	<!--<!doctype html>-->

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

	var URL = '/hunter/jradmin.php/Report';

	var SELF = '/hunter/jradmin.php?m=Admin&c=report&a=index&type=1';

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

</div><?php endif; ?>--><?php endif; ?>
<script type="text/javascript" src="/hunter/theme/admin/hunter/js/customs/resume.js"></script>
<style>
	body{
		background:white;
	}
	.dialog_open{color:#069}
	.clearfix{clear:none !important;}
.ajax_pagination a { display: inline-block; padding: 5px 10px; color: #333; border: 1px solid #bbb; background: url(../images/buttonbg5.png) repeat-x bottom left; }
.ajax_pagination a { -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; }
.ajax_pagination a { -moz-box-shadow: 1px 1px 0 #f7f7f7; -webkit-box-shadow: 1px 1px 0 #f7f7f7; box-shadow: 1px 1px 0 #f7f7f7; }
.ajax_pagination a:hover { -moz-box-shadow: inset 1px 1px 3px #eee; -webkit-box-shadow: inset 1px 1px 3px #eee; }
.ajax_pagination a:hover { text-decoration: none; background: #eee; box-shadow: inset 1px 1px 3px #eee; }
.ajax_pagination a.disabled { color: #999; border: 1px solid #ccc; }
.ajax_pagination a.disabled:hover { background: url(../images/buttonbg5.png) repeat-x bottom left; -moz-box-shadow: none; -webkit-box-shadow: none; box-shadow: none; }
.ajax_pagination a.current { background: #333 url(../images/buttonbg3.png) repeat-x top left; color: #fff; border: 1px solid #405a87; }
.ajax_pagination a.current:hover { -moz-box-shadow: none; -webkit-box-shadow: none; box-shadow: none; }

</style>
<div class="left">
	<a href="javascript:;" class="reloadButton" <?php if($search['project_id']): else: ?>onclick="parent.refresh_page();"<?php endif; ?>>刷新</a>
	<?php if($search['project_id']): ?><a href="javascript:;"   style="color:#fff;" data-editor="1"    class="J_showdialog  addNewButton" data-uri="<?php echo U('report/add',array('project_id'=>$search['project_id']));?>" data-title="新增报告" data-id="add" data-width="610" data-height="300">新增报告</a><?php endif; ?>
	
	
	
	
	<ul class="submenu" id="reportState">
		<li id="selectObj" class="select">
			<a href="javascript:;">我的推荐报告<span></span></a>
		</li>
		<span id="reportmenudata" style="border:none;"><li class="current" dataid="-2"><a href="javascript:;">全部(205)</a></li><li dataid="0"><a href="javascript:;">待推荐(16)</a></li><li dataid="1"><a href="javascript:;">待面试(13)</a></li><li dataid="2"><a href="javascript:;">面试中(66)</a></li><li dataid="3"><a href="javascript:;">成功推荐(77)</a></li><li dataid="-1"><a href="javascript:;">推荐失败(24)</a></li><li dataid="-3"><a href="javascript:;">不予推荐(9)</a></li></span>
	</ul>
	<div id="selectObjDiv" style="display:none; width:auto; position: absolute; background: #f7f7f7; BORDER: #ccc 1px solid; z-index: 300;">
		<iframe id="selectObjResult" src="" frameborder="0" width="220" height="280" style="OVERFLOW-Y: auto;"></iframe>
	</div>

	<div class="sTableOptions">
		<div class="form_default">
			<form id="projectReportSearchFrom">
				<input type="hidden" id="objType" name="objType" value="2">
				<input type="hidden" id="objId" name="objId" value="1">
				<input type="hidden" name="state" id="report_state" value="-2"> 项目名称/客户名称/候选人名称关键字：
				<input type="text" name="keyword" id="reportSearchKeyword" class="sf" style="width:180px;">
				<select name="sort" id="sort">
					<option value="0">默认排序</option>
					<option value="1">按跟进时间排序</option>
					<option value="2">按项目排序</option>
				</select>
				<input type="button" id="searchFromBtn" class="button button_red" value=" 查询 ">
			</form>
		</div>
	</div>
	<!--sTableOptions-->
	<div id="projectReportPage">

		<table width="100%" class="sTable2" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<td width="30" align="center">编号</td>
					<td style="min-width:100px;" align="center">推荐报告</td>
					<td style="min-width:100px;">客户</td>
					<td width="60" align="center">客户所有者</td>
					<td style="min-width:100px;">项目</td>
					<td width="60" align="center">项目所有者</td>
					<td width="50" align="center">期望薪金</td>
					<td width="40" align="center">推荐人</td>
					<td width="120">推荐时间</td>
					<td width="50" align="center">负责人</td>
					<td width="60" align="center">当前状态</td>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; $cus_id=M('project')->where(array('id'=>$val['pro_id']))->getField('custom_id');$cus_name="未知";$cus_id&&$cus_name=M('custom')->where(array('id'=>$cus_id))->getField('cutaname');$pro_name=M('project')->where(array('id'=>$val['pro_id']))->getField('prtaname');$res_name=M('resume')->where(array('id'=>$val['res_id']))->getField('resuname');?>
				<tr>
					<td align="center"><?php echo ($val["id"]); ?></td>
					<td>
						<a href="javascript:;" style="cursor:pointer;color:#069;" class="dialog_detail"  data-uri="<?php echo U('report/detail', array('status'=>$val['status'],'id'=>$val['id']));?>" data-title="<?php echo $res_name;?>" data-id="detail" data-width="860" data-height="492"><?php echo $res_name;?></a>

					</td>
					<td>
						<a   href="javascript:;" style="cursor:pointer;color:#069;" class="dialog_detail"  data-uri="/jradmin.php?m=admin&c=custom&a=detail&type=1&id=<?php echo $cus_id;?>" data-title="<?php echo $cus_name;?>" data-id="detail_<?php echo time();?>" data-width="1000" data-height="500"><?php echo $cus_name;?></a>
					</td>
					<td align="center">admin</td>
					<td>
						<a href="javascript:;"   style="cursor:pointer;color:#069;" class="dialog_open"  data-uri="/jradmin.php?m=admin&c=project&a=detail&id=<?php echo ($val['pro_id']); ?>&custom_id=<?php echo $cus_id;?>" data-title="<?php echo $pro_name;?>" data-id="project_detail" data-width="1000" data-height="500"><?php echo $pro_name;?></a>
					</td>
					<td align="center">管理员</td>
					<td align="center"><?php echo ($val["rereexpectmoney"]); ?>K</td>
					<td align="center">管理员</td>
					<td><?php echo (date('Y-m-d H:i:s',$val["add_time"])); ?></td>
					<td align="center">管理员</td>
					<td align="center">

						<font color="#FF8000"><?php echo ($status[$val['status']]); ?>..</font>

					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>

				
			</tbody>
		</table>

		<br clear="all">
		<div class="<?php if($search['project_id']): ?>ajax_pagination<?php else: ?>pagination<?php endif; ?>">
		<?php if($list): echo ($page); ?>
		<?php else: ?>
		暂未数据<?php endif; ?>
		</div><!--left-->
	</div>
</div>
<?php if($search['project_id']): else: ?>
	
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
</script><?php endif; endif; ?>