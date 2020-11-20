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

	var URL = '/hunter/jradmin.php/Custom';

	var SELF = '/hunter/jradmin.php?m=Admin&c=custom&a=index&menuid=397&list_type=1';

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
	body{
		background:white;
	}
</style>

	
    <div class="left">
        <a href="javascript:;" class="reloadButton" onclick="parent.refresh_page();">刷新</a>
        <?php if($type != 3): ?><a href="javascript:;" style="color:#fff;" class="addNewButton" id="exportCustomer">导出客户</a>
        
        
        <a  href="javascript:;"  style="color:#fff;"    class="J_showdialog  addNewButton"  data-uri="<?php echo U('Custom/add');?>" data-title="新增客户" data-id="add" data-width="900" data-height="500">新增客户</a>
        
        
        <a href="javascript:;"  style="color:#fff;"  class="dialog_open  addNewButton"   data-uri="<?php echo U('Custom/import');?>" data-title="导入客户" data-id="import" data-width="480" data-height="365"  id="newCustomer">导入客户</a><?php endif; ?>
        
        <ul class="submenu">
        	<li class="current" dataid="0"><a href="javascript:;">全部 (70)</a></li>
            
            <li dataid="58"><a href="javascript:;">已合作 (18)</a></li>
            
            <li dataid="59"><a href="javascript:;">待确定合同 (19)</a></li>
            
            <li dataid="60"><a href="javascript:;">有意向，跟进中 (19)</a></li>
            
            <li dataid="61"><a href="javascript:;">暂缓 (4)</a></li>
            
            <li dataid="62"><a href="javascript:;">不考虑 (2)</a></li>
            
            <li dataid="-1"><a href="javascript:;">已删除 (13)</a></li>
        </ul>

        <div class="sTableOptions">
        	<div class="searchform form_default">
            	<form id="searchFrom">
            	<input type="hidden" value="0" name="state" id="customerState">
            	<input type="hidden" value="" name="ispublic" id="ispublic">
            	关键字：<input type="text" name="keyword" id="customerSearchKeyword" class="sf" style="width:180px;">
            	<select name="contractState" id="contractState">
                  <option value="0">合同状态</option>
                  <option value="1">已审核</option>
                  <option value="2">待审核</option>
                  <option value="3">已过期</option>
                  <option value="4">已作废</option>
                  <option value="5">无合同</option>
                  <option value="6">有合同待审核</option>
                </select>
                
                 
                <a href="javascript:;" style="margin:10px 10px 0 0;" id="keyHelp" original-title="与该企业的合同状态：<br></a><span class='contract_green'>已审核：</span>合同已审核，且在有效期内<br/><span class='contract_red'>待审核：</span>合同已添加，但未审核<br/><span class='contract_brown'>已过期：</span>合同已审核，但已过期<br/><span class='contract_gray'>已作废：</span>合同已作废<br/><span style='color:#ff0;'>无合同：</span>从未签署过合同<br/><span style='color:#f0f;'>有合同待审核：</span>有未审核的合同<br/>"><img src="../../images/help.gif"></a>
                <select name="province" id="province">
                  <option value="0">所在省</option>
                  
                  <option value="95">北京</option>
                  
                  <option value="96">上海</option>
                  
                  <option value="97">天津</option>
                  
                  <option value="98">重庆</option>
                  
                  <option value="99">安徽</option>
                  
                  <option value="100">福建</option>
                  
                  <option value="101">甘肃</option>
                  
                  <option value="102">广东</option>
                  
                  <option value="103">广西</option>
                  
                  <option value="104">贵州</option>
                  
                  <option value="105">海南</option>
                  
                  <option value="106">河北</option>
                  
                  <option value="107">黑龙江</option>
                  
                  <option value="108">河南</option>
                  
                  <option value="109">湖北</option>
                  
                  <option value="110">湖南</option>
                  
                  <option value="111">江苏</option>
                  
                  <option value="112">江西</option>
                  
                  <option value="113">吉林</option>
                  
                  <option value="114">辽宁</option>
                  
                  <option value="115">宁夏</option>
                  
                  <option value="116">青海</option>
                  
                  <option value="117">山东</option>
                  
                  <option value="118">山西</option>
                  
                  <option value="119">陕西</option>
                  
                  <option value="120">四川</option>
                  
                  <option value="121">云南</option>
                  
                  <option value="122">浙江</option>
                  
                  <option value="123">新疆</option>
                  
                  <option value="124">内蒙古</option>
                  
                  <option value="125">西藏</option>
                  
                  <option value="129">国外</option>
                  
                </select>
                <select name="city" id="city" style="display:none">
                  <option value="0">所在城市</option>
                </select>
                <select name="type" id="type">
                  <option value="0">客户类型</option>
                  
                  <option value="14">重点客户</option>
                  
                  <option value="15">一般客户222</option>
                  
                  <option value="31">潜在客户</option>
                  
                  <option value="978">同行合作客户</option>
                  
                  <option value="2791">个人客户</option>
                  
                  <option value="2841">私人客户</option>
                  
                  <option value="2842">企业客户</option>
                  
                </select>
                <select name="industry" id="industry">
                  <option value="0">所属行业</option>
                  
                  <option value="52">消费品</option>
                  
                  <option value="53">医疗健康</option>
                  
                  <option value="54">其他</option>
                  
                  <option value="2828">也一样</option>
                  
                  <option value="2849">个</option>
                  
                  <option value="2850">IT/互联网</option>
                  
                  <option value="2878">金融</option>
                  
                  <option value="2881">电力</option>
                  
                </select>
                <select name="important" id="important">
                  <option value="0">重要程度</option>
                  
                  <option value="40">很重要</option>
                  
                  <option value="41">一般</option>
                  
                  <option value="42">十万火急</option>
                  
                  <option value="2851">55</option>
                  
                </select>
                <select name="sort" id="sort">
                  <option value="0">默认排序</option>
                  <option value="1">按跟进时间排序</option>
                </select>
                <input type="button" id="searchFromBtn" class="button button_red" value=" 查询 ">
                </form>
            </div>
            <div class="pp">
		        
		        <a class="button series_delete delete"   data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('custom/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" ><span><?php echo L('delete');?></span></a>
        		 <a class="button recover" style="display:none;"><span>恢复</span></a>
		        
		    </div>
		</div><!--sTableOptions-->
        <div id="listpage">



<form id="listFrom">
<table width="100%" class="sTable2" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <td align="center" width="50"><input type="checkbox" class="J_checkall checkall"></td>
            <td style="min-width:200px;">客户名称</td>
            <td width="80">有合同待审核</td>
            <td width="120">所在地区</td>
            <td width="60">客户类型</td>
            <td width="200">所属行业</td>
            <td width="60">重要程度</td>
            <td width="80">合同有效期</td>
            <td width="100">客户漏斗</td>
            <td width="150">创建时间</td>
    	</tr>
    </thead>
    <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
            <td align="center"><input type="checkbox" class="J_checkitem" <?php if(in_array($val['id'],$resu)): ?>checked<?php endif; ?> value="<?php echo ($val["id"]); ?>"></td>
            <td>
            <a  href="javascript:;" style="cursor:pointer;color:#069;" class="dialog_detail"  data-uri="<?php echo U('custom/detail', array('type'=> $type,'id'=>$val['id']));?>" data-title="<?php echo ($val["cutaname"]); ?>" data-id="detail_<?php echo time();?>" data-width="1000" data-height="500"><?php echo ($val["cutaname"]); ?></a>
            <p style="color:#777;">这家伙说要等两天再签合同（管理员 2018-12-25 14:45）</p>
            </td>
            <td align="center" style="color:#00f;"></td>
            <td>广东&nbsp;&nbsp;深圳</td>
            <td>重点客户</td>
            <td>消费品</td>
            <td>很重要</td>
            <td>2018-11-30</td>
            <td>已合作</td>
            <td><?php echo (date('Y-m-d H:i:s',$val["add_time"])); ?></td>
       	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>
</form>
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

</body>
</html>