<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content">
<body style="background:#fff;padding:10px;">
	<form id="info_form" name="info_form" action="<?php echo U('linkman/add');?>" method="post">
		<!--<input type="hidden" name="cucoId" value="0">-->
		<input type="hidden" name="customer_id" value="<?php echo ($custom_id); ?>">
		<div class="form_default"  style="padding:0; height:435px; OVERFLOW-Y: auto;">
			<p>
				<label for="cucoName">姓名</label>
				<input type="text" name="cucoName" id="cucoName" class="sf" value="">
			</p>
			<p>
				<label for="cucoSex" class="nopadding">性别</label>
				<input type="radio" name="cucoSex" value="1" checked=""> 女 &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="cucoSex" value="2"> 男
			</p>
			<p>
				<label for="cucoAge">年龄</label>
				<input type="text" name="cucoAge" id="cucoAge" class="sf" style="width:120px;" value="">
			</p>
			<p>
				<label for="cucoFunction">职能/称谓</label>
				<input type="text" name="cucoFunction" id="cucoFunction" class="sf" style="width:150px;" value=""> 如：人事部经理
			</p>
			<?php if(is_array($dic)): $i = 0; $__LIST__ = $dic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><p>
				<label for="<?php echo ($val["field"]); ?>"><?php echo ($val["name"]); ?></label>
				<select name="<?php echo ($val["field"]); ?>" id="<?php echo ($val["field"]); ?>">
				<option value=""><?php echo ($val["name"]); ?></option>
				<?php if(is_array($val['sub'])): $i = 0; $__LIST__ = $val['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
			</p><?php endforeach; endif; else: echo "" ;endif; ?>
			<p>
				<label for="cucoOfficeTel">办公电话</label>
				<input type="text" name="cucoOfficeTel" id="cucoOfficeTel" class="sf" value="">
			</p>
			<p>
				<label for="cucoFax">传真</label>
				<input type="text" name="cucoFax" id="cucoFax" class="sf" value="">
			</p>
			<p>
				<label for="cucoMobile">手机</label>
				<input type="text" name="cucoMobile" id="cucoMobile" class="sf" value="">
			</p>
			<p>
				<label for="cucoEmail">邮箱</label>
				<input type="text" name="cucoEmail" id="cucoEmail" class="sf" value="">
			</p>
			<p>
				<label for="cucoQq">QQ</label>
				<input type="text" name="cucoQq" id="cucoQq" class="sf" value="">
			</p>
			<p>
				<label for="cucoMsn">MSN</label>
				<input type="text" name="cucoMsn" id="cucoMsn" class="sf" value="">
			</p>
			<p>
				<label for="cucoHomeTel">家庭电话</label>
				<input type="text" name="cucoHomeTel" id="cucoHomeTel" class="sf" value="">
			</p>
			<p>
				<label for="cucoAboutKey">关心重点</label>
				<textarea name="cucoAboutKey" class="mf" rows="" cols="" style="height:60px; width:250px;"></textarea>
			</p>
			<p>
				<label for="cucoInterest">利益诉求</label>
				<textarea name="cucoInterest" class="mf" rows="" cols="" style="height:60px; width:250px;"></textarea>
			</p>
			<p>
				<label for="cucoRemarker">备注</label>
				<textarea name="cucoRemarker" class="mf" rows="" cols="" style="height:60px; width:250px;"></textarea>
			</p>
		</div>
	</form>

</body>
</div>

<script>
$(function(){
    $.formValidator.initConfig({formid:"info_form",autotip:true});

    $("#name").formValidator({ onshow:lang.please_input+lang.menu_name, onfocus:lang.please_input+lang.menu_name, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.menu_name}).defaultPassed();
    $("#module_name").formValidator({ onshow:lang.please_input+lang.module_name, onfocus:lang.please_input+lang.module_name, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.module_name}).defaultPassed();
    $("#action_name").formValidator({ onshow:lang.please_input+lang.action_name, onfocus:lang.please_input+lang.action_name, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.action_name}).defaultPassed();

    $('#info_form').ajaxForm({success:complate,dataType:'json'});
    function complate(result){
        if(result.status == 1){
            $.dialog.get(result.dialog).close();
            $.pinphp.tip({content:result.msg});
            window.location.reload();
        } else {
            $.pinphp.tip({content:result.msg, icon:'alert'});
        }
    }
});
</script>
</div>
<script>
var check_name_url = "<?php echo U('custom/ajax_check_name');?>";
$.formValidator.initConfig({formid:"info_form",autotip:true});
$('#cucoMobile').formValidator({onshow:lang.please_input+"手机",onfocus:lang.please_input+"手机"}).inputValidator({min:1,onerror:lang.please_input+"手机"});
$('#cucoOfficeTel').formValidator({onshow:lang.please_input+"办公电话",onfocus:lang.please_input+"办公电话"}).inputValidator({min:1,onerror:lang.please_input+"办公电话"});
$('#cucoName').formValidator({onshow:lang.please_input+"联系人",onfocus:lang.please_input+"联系人"}).inputValidator({min:1,onerror:lang.please_input+"联系人"});
$('#cucoFunction').formValidator({onshow:lang.please_input+"称谓",onfocus:lang.please_input+"称谓"}).inputValidator({min:1,onerror:lang.please_input+"称谓"});
$('#cucoEmail').formValidator({onshow:lang.please_input+"邮箱",onfocus:lang.please_input+"邮箱"}).inputValidator({min:1,onerror:lang.please_input+"邮箱"});

$(function(){
	validate_form($('#sid').val());
});
function validate_form(store_id){
	
	$('#info_form').ajaxForm({success:complate,dataType:'json'});
	function complate(result){
		if(result.status == 1){
			$.dialog.get(result.dialog).close();
			$.pinphp.tip({content:result.msg});
			//window.location.reload();
		} else {
			$.pinphp.tip({content:result.msg, icon:'alert'});
		}
	}
}
//tab切换
$('.ui-state-default').click(function(){
	var index=$(this).index();
	$(this).addClass('ui-state-active').siblings().removeClass('ui-state-active');
	$('.ui-tabs-panel').siblings().hide();
	$('.ui-tabs-panel').eq(index).show();
})
$('.J_cate_place_select').cate_select({top_option:lang.all,field:'J_pid',target_class:'J_cate_place_select'}); //分类联动
</script>