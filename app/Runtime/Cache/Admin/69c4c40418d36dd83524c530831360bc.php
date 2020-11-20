<?php if (!defined('THINK_PATH')) exit(); if($search['custom_id'] or $search['resume_id']): else: ?>
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

	var URL = '/hunter/jradmin.php/Project';

	var SELF = '/hunter/jradmin.php?m=Admin&c=project&a=index&type=1';

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
<style>
	body{
		background:white;
	}
	.dialog_open{color:#069}
	
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
	<?php if($search['is_tab'] != 1 and $search['is_add'] != 1): ?><a href="javascript:;" class="reloadButton" <?php if($search['custom_id'] or $search['resume_id']): else: ?>onclick="parent.refresh_page();"<?php endif; ?>>刷新</a>
		<?php if($search['type'] != 2): ?><a href="javascript:;"   style="color:#fff;"    class="J_showdialog  addNewButton" data-uri="<?php echo U('Project/add',array('custom_id'=>$search['custom_id']));?>" data-title="新增项目" data-id="add" data-editor="1" data-width="700" data-height="500">新增项目</a><?php endif; endif; ?>
	
	
	<ul class="submenu" id="submenu">
		<?php if($search['resume_id']): ?><li class="current" dataid="-4">
		<?php if($search['is_tab'] != 1): ?><a href="javascript:;">我的项目</a><?php endif; ?>
		</li>
		<?php else: ?>
		<li  <?php if($search['status'] == -4): ?>class="current"<?php endif; ?> dataid="-4">
			<a href="<?php echo U('project/index',array('prtaFunnel'=>-4,'type'=>$search['type']));?>">启用中 (<?php echo ($status_nums[-4]); ?>)</a>
		</li>
		<?php if(is_array($status)): $i = 0; $__LIST__ = $status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li  <?php if($search['status'] == $val['id']): ?>class="current"<?php endif; ?> dataid="<?php echo ($val["id"]); ?>">
			<a href="<?php echo U('project/index',array('prtaFunnel'=>$val['id'],'type'=>$search['type']));?>"><?php echo ($val["name"]); ?> (<?php echo ($status_nums[$val['id']]); ?>)</a>
		</li><?php endforeach; endif; else: echo "" ;endif; ?>
		<li  <?php if($search['status'] == -3): ?>class="current"<?php endif; ?>  dataid="-1">
			<a href="<?php echo U('project/index',array('prtaFunnel'=>-3,'type'=>$search['type']));?>">未启用 (<?php echo ($status_nums[-3]); ?>)</a>
		</li>
		<li  <?php if($search['status'] == -1): ?>class="current"<?php endif; ?> dataid="-2">
			<a href="<?php echo U('project/index',array('prtaFunnel'=>-1,'type'=>$search['type']));?>">已完成 (<?php echo ($status_nums[-1]); ?>)</a>
		</li>
		<li dataid="0" <?php if($search['status'] == -2): ?>class="current"<?php endif; ?>>
			<a href="<?php echo U('project/index',array('prtaFunnel'=>-2,'type'=>$search['type']));?>">全部 (<?php echo ($status_nums[-2]); ?>)</a>
		</li>
		<!--<li dataid="-3">
			<a href="javascript:;">已删除 (24)</a>
		</li>--><?php endif; ?>
		
	</ul>
	<?php if($search['is_tab'] != 1): ?><div class="sTableOptions">
		<div class="form_default">
			<form id="searchFrom">
				<input type="hidden" value="-4" name="state" id="projectState">
				<input type="hidden" value="" name="custId"> 
				<?php if($search['custom_id']): ?>项目名称关键字：
					<?php else: ?>
					项目名称/客户名称/项目编号关键字：<?php endif; ?>
				<input type="text" name="keyword" id="projectSearchKeyword" class="sf" style="width:180px;">
				<select name="level" id="level">
					<option value="0">职位级别</option>

					<option value="73">总经理</option>

					<option value="74">总监</option>

					<option value="75">经理</option>

					<option value="76">主管</option>

					<option value="77">普通</option>

					<option value="2877">总裁级</option>

				</select>
				<select name="type" id="type">
					<option value="0">职位类别</option>

					<option value="68">销售</option>

					<option value="69">计算机/互联网/通信/电子</option>

					<option value="70">投融资</option>

					<option value="71">风险/稽核/法务</option>

					<option value="72">资产/财富/PEVC</option>

					<option value="2807">行长/客户经理/产品经理</option>

					<option value="2808">财务/税务/成本</option>

					<option value="2809">股票/典当/理财顾问</option>

					<option value="2810">汽车/工程/机械 技术人员</option>

					<option value="2811">生产运营</option>

					<option value="2812">质量</option>

					<option value="2813">采购供应链</option>

					<option value="2814">市场营销</option>

					<option value="2815">HR/招聘/培训/绩效/其他</option>

					<option value="2816">行政后勤</option>

					<option value="2817">咨询顾问（猎头、咨询）</option>

					<option value="2823">uuuu</option>

					<option value="2848">V型</option>

					<option value="2883">电力</option>

					<option value="2887">电商</option>

				</select>
				<?php if($search['custom_id']): ?><select name="sort" id="sort">
						<option value="1">选择用户</option>
						<option value="0">管理员</option>
						<option value="2">按跟进时间排序</option>
					</select>
					<?php else: ?>
					<label style="float:none;"><input type="checkbox" id="myCustomerProject" name="myCustomerProject" value="1">显示我的客户的项目</label><?php endif; ?>
				
				<select name="sort" id="sort">
					<option value="0">默认排序</option>
					<option value="1">按客户排序</option>
					<option value="2">按跟进时间排序</option>
				</select>
				<input type="button" id="searchFromBtn" class="button button_red" value=" 查询 ">
			</form>
		</div>
	</div><?php endif; ?>
		
	<!--sTableOptions-->
	<div id="listpage">

		<table width="100%" class="sTable2" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<td width="80" align="center" style="min-width:24px;">项目编号</td>
					<td width="100">项目名称</td>
					<?php if($search['custom_id']): else: ?>
					<td width="110">所属客户</td><?php endif; ?>
					<td width="60">招聘人数</td>
					<td width="60">工作地点</td>
					<td width="60">职位类别</td>
					<td width="60">职位级别</td>
					<td width="60">候选人数</td>
					<td width="70">offer数量</td>
					<td width="60">报告数量</td>
					<td width="60" id="ownerText">所有者</td>

					<td width="100">创建时间</td>
					<?php if($search['resume_id'] and $search['is_tab'] != 1): ?><td width="100">请选择</td>
					<?php else: endif; ?>
				</tr>
			</thead>
			<tbody>

				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
					<td align="center"><?php echo ($val["prtacode"]); ?></td>
					<td>
						<a href="javascript:;"  style="cursor:pointer;color:#069;" class="<?php if($search['custom_id'] or $search['resume_id']): else: ?>dialog_open<?php endif; ?>"  data-uri="<?php echo U('project/detail', array('id'=>$val['id'],'custom_id'=>$val['custom_id']));?>" data-title="<?php echo ($val["prtaname"]); ?>" data-id="project_detail" data-width="1000" data-height="500"><?php echo ($val["prtaname"]); ?></a>

					</td>
					<?php if($search['custom_id']): else: ?>
					<td>
						<a  href="javascript:;" style="cursor:pointer;color:#069;" class="dialog_detail"  data-uri="<?php echo U('custom/detail', array('id'=>$val['custom_id']));?>" data-title="<?php echo ($val["cutaname"]); ?>" data-id="detail" data-width="1000" data-height="500"><?php echo ($val["cutaname"]); ?></a>
						
					
					</td><?php endif; ?>
					
					<td><?php echo ($val["prtapositionnum"]); ?></td>
					<td><?php echo ($val["prtaworkcity"]); ?></td>
					<td><?php echo M('dic')->where(array('id'=>$val['prtatype']))->getField('name');?></td>
					<td><?php echo M('dic')->where(array('id'=>$val['prtalevel']))->getField('name');?></td>
					<td>
						<a href="javascript:;"  class="">6</a>
					</td>
					<td>
						<a href="javascript:;"  class="">0</a>
					</td>
					<td>
						<a href="javascript:;"  class="">4</a>
					</td>
					<td><?php echo ($val["username"]); ?></td>

					<td><?php echo (date('Y-m-d H:i:s',$val["add_time"])); ?></td>
					<?php if($search['resume_id'] and $search['is_tab'] != 1): ?><td width="100">
					<a href="javascript:void(0);" style="color: #069;cursor:pointer;" class="J_confirmurl" data-uri="<?php echo u('candidate/resume_project', array('id'=>$val['id'],'resume_id'=>$search['resume_id']));?>" data-acttype="ajax" data-msg="确定选择该项目吗">选择</a></td>
					</td>
					<?php else: endif; ?>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>

		<br clear="all">
		
		
		<div class="<?php if($search['custom_id'] or $search['resume_id']): ?>ajax_pagination<?php else: ?>pagination<?php endif; ?>">
		<?php if($list): echo ($page); ?>
		<?php else: ?>
		暂未数据<?php endif; ?>
		</div><!--left-->
	</div>
</div>
<?php if($search['custom_id'] or $search['resume_id']): else: ?>
	
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