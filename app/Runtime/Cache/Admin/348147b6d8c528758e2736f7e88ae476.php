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

	var URL = '/hunter/jradmin.php/qun';

	var SELF = '/hunter/jradmin.php?m=admin&c=qun&a=index&menuid=557';

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
<link rel="stylesheet" type="text/css" href="<?php /hunter;?>/theme/admin/css/style.css" />
<style>
	body{
		background:white;
	}
</style>

	
    <div class="left">
        <a href="javascript:;" class="reloadButton" onclick="parent.refresh_page();">刷新</a>
        
        <a  href="javascript:;"  style="color:#fff;"    class="J_showdialog  addNewButton"  data-uri="<?php echo U('Qun/add');?>" data-title="新增客户" data-id="add" data-width="" data-height="" >新增群</a>
        
      
        
        <ul class="submenu">
            <li dataid="58" class="<?php if($search['funnel'] == -1): ?>current<?php endif; ?>"><a href="<?php echo U('custom/index',array('list_type'=>$type,'funnel'=>-1));?>">全部  (<?php echo ($status_nums[-1]); ?>)</a></li>	
            
            <?php if(is_array($funnel)): $i = 0; $__LIST__ = $funnel;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li dataid="58" class="<?php if($search['funnel'] == $val['id']): ?>current<?php endif; ?>"><a href="<?php echo U('custom/index',array('list_type'=>$type,'funnel'=>$val['id']));?>"><?php echo ($val["name"]); ?> (<?php echo ($status_nums[$val['id']]); ?>)</a></li><?php endforeach; endif; else: echo "" ;endif; ?>
           
           
        </ul>

        <div class="sTableOptions">
        	<div class="searchform form_default">
            	<form id="searchFrom">
        		<input type="hidden" name="m" value="admin" />
                <input type="hidden" name="c" value="custom" />
                <input type="hidden" name="a" value="index" />
                <input type="hidden" name="list_type" value="<?php echo ($type); ?>" />
            	<input type="hidden" value="0" name="state" id="customerState">
            	
            	<input type="hidden" value="" name="ispublic" id="ispublic">
            	关键字：<input type="text" name="cutaname" id="customerSearchKeyword" class="sf" style="width:180px;" value="<?php echo ($search['uname']); ?>">
            	<select name="contractState" id="contractState">
            		<option value="">群状态</option>
                  <option value="0">待审核</option>
                  <option value="1">审核通过</option>
                  <option value="2">审核拒绝</option>
                </select>
                
                <select class="J_cate_place_select mr10" data-pid="0" data-uri="<?php echo U('place/ajax_getchilds');?>" data-selected="<?php echo ($spid); ?>"></select>
				<input type="hidden" name="pid" id="J_pid" />
                <!--<select name="sort" id="sort">
                  <option value="0">默认排序</option>
                  <option value="1">按跟进时间排序</option>
                </select>-->
                <input type="submit" name="search" id="searchFromBtn" class="button button_red" value=" 查询 ">
                </form>
            </div>
            <?php if($type == 1): ?><div class="pp">
		        
		        <a class="button series_delete delete"   data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('custom/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" ><span><?php echo L('delete');?></span></a>
        		 <a class="button recover" style="display:none;"><span>恢复</span></a>
		        
		    </div><?php endif; ?>
		</div><!--sTableOptions-->
        <div id="listpage">



<div class="J_tablelist" data-acturi="<?php echo U('qun/ajax_edit');?>">
<table width="100%" class="sTable2" cellspacing="0" cellpadding="0" >
    <thead>
        <tr>
            <td align="center" width="50"><input type="checkbox" class="J_checkall checkall"></td>
            <td width="160">logo</td>
            <td width="100">群名称</td>
            <td width="60">群类型</td>
            <td width="120">所在地区</td>
            <td width="80">创建者</td>
            <td width="150">创建时间</td>
            <td width="60">状态</td>
            <th width=100><?php echo L('operations_manage');?></th>
    	</tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
            <td align="center"><input type="checkbox" class="J_checkitem" <?php if(in_array($val['id'],$resu)): ?>checked<?php endif; ?> value="<?php echo ($val["id"]); ?>"></td>
           
            </td>
            <td><img src="<?php echo attach($val['logo'],'logo');?>" style="width:50px;height:50px;border-radius:50%;" /></td>
            <td><span data-tdtype="edit" data-field="name" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["name"]); ?></span></td>
            <td><?php echo ($val["t_name"]); ?></td>
            <td><?php echo ($val["p_name"]); ?></td>
            <td>
            	<?php if($val['creator_id']): echo ($val["creator_id"]); ?>
            		<?php else: ?>
            		管理员<?php endif; ?>
            </td>
            <td><?php echo (date('Y-m-d H:i:s',$val["add_time"])); ?></td>
	         <td >
	                <img data-tdtype="toggle" data-field="status" data-id="<?php echo ($val["id"]); ?>" data-value="<?php echo ($val["status"]); ?>" src="/hunter/theme/admin//images/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" />
	         </td>
	         <td align="center">
                    <a href="javascript:;" class="J_showdialog" data-uri="<?php echo U('qun/edit', array('id'=>$val['id'],'depart_id'=>$val['depart_id']));?>" data-title="<?php echo L('edit');?> - <?php echo ($val["username"]); ?>"  data-id="edit" data-width="600" data-height="60"><?php echo L('edit');?></a> |
                    <a href="javascript:;" class="J_confirmurl" data-uri="<?php echo U('qun/delete', array('id'=>$val['id']));?>" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['username']);?>"><?php echo L('delete');?></a>
                </td>
       	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>
</div>
<br clear="all">
<div class="pagination">
<?php echo ($page); ?>
</div><!--left-->
    
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
<script src="/hunter/theme/admin/js/kindeditor/kindeditor.js"></script>
<script>
	$(function(){
		$('.J_cate_place_select').cate_select({top_option:lang.all,field:'J_pid',target_class:'J_cate_place_select'}); //分类联动
		
	})
</script>
</body>
</html>