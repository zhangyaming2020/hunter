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

	var URL = '/hunter/jradmin.php/Setting';

	var SELF = '/hunter/jradmin.php?m=Admin&c=setting&a=index&menuid=148';

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
<!--网站设置-->
<div class="pad_lr_10">
	<form id="info_form" action="<?php echo u('setting/edit');?>" method="post">
	<table width="100%" class="table_form">
        <tr>
            <th width="150">版权所有 :</th>
            <td><input type="text" name="setting[site_name]" class="input-text" size="50" value="<?php echo C('pin_site_name');?>"></td>
        </tr>
        <tr>
            <th><?php echo L('site_name');?>|<?php echo L('seo_title');?> :</th>
            <td><input type="text" name="setting[site_title]" class="input-text" size="50" value="<?php echo C('pin_site_title');?>"></td>
        </tr>
        <tr>
            <th><?php echo L('seo_keys');?> :</th>
            <td><input type="text" name="setting[site_keyword]" class="input-text" size="50" value="<?php echo C('pin_site_keyword');?>"></td>
        </tr>
        <tr>
            <th><?php echo L('seo_desc');?> :</th>
            <td><textarea rows="3" cols="50" name="setting[site_description]"><?php echo C('pin_site_description');?></textarea></td>
        </tr>
        <tr>
            <th><?php echo L('site_icp');?> :</th>
            <td><input type="text" name="setting[site_icp]" class="input-text" size="50" value="<?php echo C('pin_site_icp');?>"></td>
        </tr>
        <tr>
            <th><?php echo L('statistics_code');?> :</th>
            <td><textarea rows="3" cols="50" name="setting[statistics_code]"><?php echo C('pin_statistics_code');?></textarea></td>
        </tr>
		<tr>
            <th>网址 :</th>
            <td><input type="text" name="setting[site_url]" class="input-text" size="50" value="<?php echo C('pin_site_url');?>">不带http://</td>
        </tr>
		<tr>
            <th>邮箱 :</th>
            <td><input type="text" name="setting[email]" class="input-text" size="50" value="<?php echo C('pin_email');?>"></td>
        </tr>
		<tr>
            <th>电话 :</th>
            <td><input type="text" name="setting[tel]" class="input-text" size="50" value="<?php echo C('pin_tel');?>"></td>
        </tr>
		<tr>
            <th>手机 :</th>
            <td><input type="text" name="setting[mobile]" class="input-text" size="50" value="<?php echo C('pin_mobile');?>"></td>
        </tr>
		<tr>
            <th>QQ :</th>
            <td><input type="text" name="setting[qq]" class="input-text" size="50" value="<?php echo C('pin_qq');?>"></td>
        </tr>
        <tr>
            <th>QQ群 :</th>
            <td><input type="text" name="setting[qq_group]" class="input-text" size="50" value="<?php echo C('pin_qq_group');?>"></td>
        </tr>
        
          <tr>
            <th>备注消息 :</th>
            <td><input type="text" name="setting[beizhu]" class="input-text" size="50" value="<?php echo C(pin_beizhu);?>"></td>
        </tr>
        
        
        <tr>
            <th>店铺类型 :</th>
            <td><input type="text" name="setting[shoptype]" class="input-text" size="50" value="<?php echo C('pin_shoptype');?>"></td>
        </tr>
        <tr>
            <th>营业时间 :</th>
            <td><input type="text" name="setting[work_time]" class="input-text" size="10" value="<?php echo C('pin_work_time');?>"> 使用英文冒号隔开</td>
        </tr>
         <tr>
            <th>店长提成率 :</th>
            <td><input type="text" name="setting[d_ti]" class="input-text" size="5" value="<?php echo C('pin_d_ti');?>">       
            </td>
        </tr>
         <tr>
            <th>业务员提成率 :</th>
            <td><input type="text" name="setting[y_di]" class="input-text" size="5" value="<?php echo C('pin_y_di');?>">       
            </td>
        </tr>
		<tr>
            <th>地址 :</th>
            <td><input type="text" name="setting[address]" class="input-text" size="50" value="<?php echo C('pin_address');?>"></td>
        </tr>
        <tr>
            <th>签到积分下线值  :</th>
            <td><input type="text" name="setting[min_integral]" class="input-text" size="10" value="<?php echo C('pin_min_integral');?>"></td>
        </tr>
         <tr>
            <th>签到积分上线值  :</th>
            <td><input type="text" name="setting[max_integral]" class="input-text" size="10" value="<?php echo C('pin_max_integral');?>"</td>
        </tr>
         <tr>
            <th>消费送积分比例  :</th>
            <td><input type="text" name="setting[integralbili]" class="input-text" size="4" value="<?php echo C('pin_integralbili');?>">%</td>
        </tr>
         <tr>
            <th>商城价与标准价比例  :</th>
            <td><input type="text" name="setting[price_bili]" class="input-text" size="4" value="<?php echo C('pin_price_bili');?>">%</td>
        </tr>
        <tr>
            <th>免费配送距离 :</th>
            <td><input type="text" name="setting[distance]" class="input-text" size="10" value="<?php echo C('pin_distance');?>"> 公里</td>
        </tr>
        <tr>
            <th>一般收费配送距离 :</th>
            <td><input type="text" name="setting[commonly_distance]" class="input-text" size="10" value="<?php echo C('pin_commonly_distance');?>"> 公里</td>
        </tr>
         <tr>
            <th>一般配送距离满免配送费:</th>
            <td><input type="text" name="setting[is_qb]" class="input-text" size="4" value="<?php echo C('pin_is_qb');?>"> 元</td>
        </tr>
        <tr>
            <th>一般配送距离收费:</th>
            <td><input type="text" name="setting[freight]" class="input-text" size="4" value="<?php echo C('pin_freight');?>"> 元</td>
        </tr>
        <tr>
            <th>远距离配送每公里收费 :</th>
            <td><input type="text" name="setting[far_distance]" class="input-text" size="4" value="<?php echo C('pin_far_distance');?>"> 元</td>
        </tr>
        <tr>
            <th>免费配送距离配送时间 :</th>
            <td><input type="text" name="setting[delivery_time]" class="input-text" size="10" value="<?php echo C('pin_delivery_time');?>"> 秒</td>
        </tr>
        <tr>
            <th>一般距离每公里配送时间 :</th>
            <td><input type="text" name="setting[exceed_delivery_time]" class="input-text" size="10" value="<?php echo C('pin_exceed_delivery_time');?>"> 秒</td>
        </tr>
        <tr>
            <th>配送区域 :</th>
            <td><textarea rows="4" cols="50" name="setting[area]"><?php echo C('pin_area');?></textarea>
            </td>
        </tr>
        <tr>
            <th>客服热线  :</th>
            <td><input type="text" name="setting[customer]" class="input-text" size="50" value="<?php echo C('pin_customer');?>"></td>
        </tr>
       <!-- <tr>
            <th>热门搜索1 :</th>
            <td><input type="text" name="setting[hot_keyword1]" class="input-text" size="50" value="<?php echo C('pin_hot_keyword1');?>"></td>
        </tr>
        <tr>
            <th>热门搜索2 :</th>
            <td><input type="text" name="setting[hot_keyword2]" class="input-text" size="50" value="<?php echo C('pin_hot_keyword2');?>"></td>
        </tr>
        <tr>
            <th>热门搜索3 :</th>
            <td><input type="text" name="setting[hot_keyword3]" class="input-text" size="50" value="<?php echo C('pin_hot_keyword3');?>"></td>
        </tr>
        <tr>
            <th>热门搜索4 :</th>
            <td><input type="text" name="setting[hot_keyword4]" class="input-text" size="50" value="<?php echo C('pin_hot_keyword4');?>"></td>
        </tr>
        <tr>
            <th>热门搜索5 :</th>
            <td><input type="text" name="setting[hot_keyword5]" class="input-text" size="50" value="<?php echo C('pin_hot_keyword5');?>"></td>
        </tr>-->
        <!--<tr>
            <th>刷客咨询QQ :</th>
            <td><input type="text" name="setting[qq1]" class="input-text" size="50" value="<?php echo C('pin_qq1');?>"></td>
        </tr>
        <tr>
            <th>商家咨询QQ :</th>
            <td><input type="text" name="setting[qq2]" class="input-text" size="50" value="<?php echo C('pin_qq2');?>"></td>
        </tr>
        <tr>
            <th>审核客服QQ :</th>
            <td><input type="text" name="setting[qq3]" class="input-text" size="50" value="<?php echo C('pin_qq3');?>"></td>
        </tr>
        <tr>
            <th>投诉建议QQ :</th>
            <td><input type="text" name="setting[qq4]" class="input-text" size="50" value="<?php echo C('pin_qq4');?>"></td>
        </tr>-->
    	<tr>
        	<th><?php echo L('site_status');?> :</th>
        	<td>
            	<label><input type="radio" class="J_change_status" <?php if(C('pin_site_status') == '1'): ?>checked="checked"<?php endif; ?> value="1" name="setting[site_status]"> <?php echo L('open');?></label> &nbsp;&nbsp;
                <label><input type="radio" class="J_change_status" <?php if(C('pin_site_status') == '0'): ?>checked="checked"<?php endif; ?> value="0" name="setting[site_status]"> <?php echo L('close');?></label>
            </td>
    	</tr>
        <tr id="J_closed_reason" <?php if(C('pin_site_status') == 1): ?>class="hidden"<?php endif; ?>>
        	<th><?php echo L('closed_reason');?> :</th>
        	<td><textarea rows="4" cols="50" name="setting[closed_reason]" id="closed_reason"><?php echo C('pin_closed_reason');?></textarea></td>
    	</tr>
        <tr>
        	<th></th>
        	<td><input type="hidden" name="menuid"  value="<?php echo ($menuid); ?>"/><input type="submit" class="btn btn_submit" value="<?php echo L('submit');?>"/></td>
    	</tr>
	</table>
	</form>
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
<script>
$(function(){
    $('.J_change_status').live('click', function(){
        if($(this).val() == '0'){
            $('#J_closed_reason').fadeIn();
        }else{
            $('#J_closed_reason').fadeOut();
        }
    });
});
</script>
</body>
</html>