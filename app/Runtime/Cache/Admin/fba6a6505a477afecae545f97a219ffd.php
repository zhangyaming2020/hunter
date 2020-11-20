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

	var URL = '/hunter/jradmin.php/Admin';

	var SELF = '/hunter/jradmin.php?m=Admin&c=admin&a=index&menuid=61';

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
<style>
	.subnav{padding:0px !important;}
	.maincontent{    padding: 20px 15px;
    overflow: hidden;}
    .sidebar .current{width:220px;height:33px;}
    #accordion .content:last-child{padding:0px !important;}
	body{background:white;}
</style>
<a href="javascript:;" class="J_showdialog btn" style="color:#fff;" data-uri="<?php echo U('admin/add');?>" data-title="添加"  data-id="add" data-width="600" data-height="60">添加</a>
<!--管理员管理-->
<div class="pad_lr_10">
    <div class="J_tablelist table_list" data-acturi="<?php echo U('qun/ajax_edit');?>">
        <table width="100%" cellspacing="0">
            <thead>
            <tr>
                <th width="40"><input type="checkbox" name="checkall" class="J_checkall"></th>
                <th width="40">ID</th>
                <th><?php echo L('admin_username');?></th>
                <th>昵称</th>
                <th><?php echo L('admininrole');?></th>
                <th><?php echo L('lasttime');?></th>
      			<th><?php echo L('lastip');?></th>
                <th><?php echo L('status');?></th>
                <th width=100><?php echo L('operations_manage');?></th>
            </tr>
            </thead>
    	    <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                <td align="center"><?php echo ($val["id"]); ?></td>
                <td align="center"><!--<span data-tdtype="edit" data-field="username" class="tdedit" data-id="<?php echo ($val["id"]); ?>">--><?php echo ($val["username"]); ?></td>
                 <td align="center"><?php echo ($val["nickname"]); ?></td>
                <td align="center"><?php echo ($val["role"]["name"]); ?></td>
                <td align="center"><?php echo (date('Y-m-d H:i:s',$val["last_time"])); ?></td>
                <td align="center"><?php echo ($val["last_ip"]); ?></td>
                <td align="center">
                    <img data-tdtype="toggle" data-field="status" data-id="<?php echo ($val["id"]); ?>" data-value="<?php echo ($val["status"]); ?>" src="/hunter/theme/admin//images/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" />
                </td>
                <td align="center">
                    <a href="javascript:;" class="J_showdialog" data-uri="<?php echo U('admin/edit', array('id'=>$val['id'],'depart_id'=>$val['depart_id']));?>" data-title="<?php echo L('edit');?> - <?php echo ($val["username"]); ?>"  data-id="edit" data-width="600" data-height="60"><?php echo L('edit');?></a> |
                    <a href="javascript:;" class="J_confirmurl" data-uri="<?php echo U('admin/delete', array('id'=>$val['id']));?>" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['username']);?>"><?php echo L('delete');?></a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    	   </tbody>
        </table>
    </div>
    <div class="songkebor">
		<label class="select_all mr10"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
    	<input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('admin/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" />
		<div id="pages"><?php echo ($page); ?></div>
    </div>
</div>

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
</body>
</html>