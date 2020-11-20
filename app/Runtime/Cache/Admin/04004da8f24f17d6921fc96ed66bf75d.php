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

	var URL = '/hunter/jradmin.php/Dic';

	var SELF = '/hunter/jradmin.php?m=Admin&c=dic&a=index&menuid=551';

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
<style>
	body{
		background:#fff;
	}
	.dictset dl dd  {
	float: left;
    padding: 5px;
    width: 45%;
    line-height: 23px;
    display: inline;
    font-weight: bold;
    font-size: 14px;
    padding-bottom: 10px;
    cursor: pointer;
    }
    .dictset dl dd p{
    font-size: 12px;
    font-weight: normal;
    color: #999;
    word-spacing: break-word;
    word-break: normal;
    word-break: break-all;
	}
	dd{
	 -webkit-margin-start: 25px;
	}
	.dictset dl {
    float: left;
    list-style-type: decimal;
    width: 90%;
    }
    dl {
    display: block;
    -webkit-margin-before: 1em;
    -webkit-margin-after: 1em;
    -webkit-margin-start: 0px;
    -webkit-margin-end: 0px;
	}
	.ui-tabs-nav li  a{
		border-bottom: 0px !important;
	}
</style>
<br />
<div class="breadcrumbs" style="margin:0 !important;">
    	<a href="javascript:;" onclick="$('.tabmenu li').eq(0).click();">我的桌面</a>
        <span>字典设置</span>
</div>

<br />
<div class="left">

	<h1 class="pageTitle">字典设置</h1>
	<div id="tabs" class="tabs2 ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<?php if(is_array($dic)): $i = 0; $__LIST__ = $dic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="ui-state-default ui-corner-top <?php if($i == 1): ?>ui-state-active<?php endif; ?>">
				<a href="javascript:;"><?php echo ($val["name"]); ?></a>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
		<?php if(is_array($dic)): $i = 0; $__LIST__ = $dic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div  class="ui-tabs-hide dictset clearfix ui-tabs-panel ui-widget-content ui-corner-bottom">
			<dl>
				<?php if(is_array($val['child'])): $i = 0; $__LIST__ = $val['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><dd   class="dialog_detail" data-width="500" data-height="400" data-uri="<?php echo U('dic/detail', array('remark'=>$v['remark'],'id'=>$v['id']));?>" data-title="<?php echo ($v["name"]); ?>" data-id="detail" >
					<?php echo ($i); ?>. <?php echo ($v["name"]); ?>
					<p><?php echo ($v["remark"]); ?></p>
				</dd><?php endforeach; endif; else: echo "" ;endif; ?>
			</dl>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<!-- tabs -->
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
<script src="/hunter/theme/admin/js/kindeditor/kindeditor.js"></script>
<script src="/theme/admin/js/jquery/plugins/jquery.treetable.js"></script>

<script>
$(function(){
    $('.J_change_status').live('click', function(){
        if($(this).val() == '0'){
            $('#J_closed_reason').fadeIn();
        }else{
            $('#J_closed_reason').fadeOut();
        }
    });
    //tab键切换
    $('.dictset').eq(0).show();
$('.ui-state-default').click(function(){
	var index=$(this).index();
	$(this).addClass('ui-state-active').siblings('.ui-state-default').removeClass('ui-state-active');
	$('.dictset').eq(index).show().siblings('.dictset').hide();
})
});
</script>
</body>
</html>