<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content">
    <div id="tabs" class="tabs2 ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
        <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="javascrip:;">编辑客户资料</a></li>
    </ul>
    <form id="info_form" name="info_form" action="<?php echo u('custom/edit');?>" method="post">
    <input type="hidden" name="id" value="<?php echo ($id); ?>">
    <div id="tabs-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
        <div class="form_default" style="padding:0; height:435px; OVERFLOW-Y: auto;">
                <p>
                    <label for="cutaShortName">简称/总机电话</label>
                    <input type="text" name="cutaShortName" id="cutaShortName" value="<?php echo ($info['cutashortname']); ?>" class="sf" style="width:180px;">&nbsp;&nbsp;<img id="searchSuccess" style="display:none;" src="../../images/success.png">&nbsp;&nbsp;
                    <!--<input type="button" value=" 查重 " id="searchCompany" class="button button_blue" style="height:30px;"> 
               -->
                </p>
                <div id="resultContent" style="display:none; position: absolute; background: #f7f7f7; height:230px; width:380px; OVERFLOW-Y: scroll; BORDER: #ccc 1px solid;">
					<ul id="resultList" class="list" style="margin-top:0;"></ul>
				</div>
                <p>
                    <label for="cutaName">公司全称</label>
                    <input type="text" name="cutaName" id="cutaName" value="<?php echo ($info['cutaname']); ?>" class="sf">
                </p>
                <p>
                <label for="cutaProvince">所在地区</label>
				<select class="J_cate_place_select mr10" data-pid="0" data-uri="<?php echo U('place/ajax_getchilds');?>" data-selected="<?php echo ($spid); ?>"></select>
				<input type="hidden" name="pid" id="J_pid" value="<?php echo ($info["pid"]); ?>" />
				</p>
				<p>
                    <label for="cutaFunnel">&nbsp;</label>
					<?php if(is_array($dic)): $i = 0; $__LIST__ = $dic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; if($val['is_must']): if($val['id'] != 6): ?><select name="<?php echo ($val["field"]); ?>" id="<?php echo ($val["field"]); ?>">
							<option value=""><?php echo ($val["name"]); ?></option>
							<?php if(is_array($val['sub'])): $i = 0; $__LIST__ = $val['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"  <?php if(in_array($v['id'],array($info['cutafunnel'],$info['cutaimporta'],$info['cutaindustr'],$info['cutatype'],$info['cutaloyalty']))): ?>selected="selected"<?php endif; ?> ><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    		</select><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                </p>
				<?php if(is_array($dic)): $i = 0; $__LIST__ = $dic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; if($val['is_must']): if($val["id"] == 6): ?><p>
			                    <label for="<?php echo ($val["field"]); ?>"><?php echo ($val["name"]); ?></label>
			                    <select name="<?php echo ($val["field"]); ?>" id="<?php echo ($val["field"]); ?>">
			                     <option value=""><?php echo ($val["name"]); ?></option>
			                      <?php if(is_array($val['sub'])): $i = 0; $__LIST__ = $val['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"  <?php if(in_array($v['id'],array($info['cutafunnel'],$info['cutaimporta'],$info['cutaindustr'],$info['cutatype'],$info['cutaloyalty']))): ?>selected="selected"<?php endif; ?>  ><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			                    </select>
			                </p><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
				
                
               
                <p>
                    <label for="type" class="nopadding">私有化</label>
                    <input type="radio" name="type" value="1"  <?php if($info["type"] == 1): ?>checked<?php endif; ?> > 私有  &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="type" value="0"  <?php if($info["type"] == 0): ?>checked<?php endif; ?> > 公共
                </p>
                <p>
                    <label for="cutaTelephone">总机电话</label>
                    <input type="text" name="cutaTelephone" id="cutaTelephone" value="<?php echo ($info["cutatelephone"]); ?>" class="sf">
                </p>
                 <p>
                    <label for="cutaWeb">公司地址</label>
                    <input type="text" name="cutaAddress" id="cutaAddress" class="sf" value="<?php echo ($info["cutaaddress"]); ?>">
                </p>
                <p>
                    <label for="cutaAddress">公司网址</label>
                    <input type="text" name="cutaWeb" id="cutaWeb" class="sf" value="<?php echo ($info["cutaweb"]); ?>">
                </p>
                
                
                
                <p>
				<label for="cutaFunnel">&nbsp;</label>
                <?php if(is_array($dic)): $i = 0; $__LIST__ = array_slice($dic,4,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; if($val['is_must'] == 0): ?><select name="<?php echo ($val["field"]); ?>" id="<?php echo ($val["field"]); ?>">
							<option value=""><?php echo ($k); echo ($val["name"]); ?></option>
							<?php if(is_array($val['sub'])): $i = 0; $__LIST__ = $val['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"  data="<?php dump(array($info['cutaloyalty'],$info['cutaapprove'],$info['cutacredit'],$info['cutasource'],$info['cutaorganiz']));?>"   <?php if(in_array($v['id'],array($info['cutaloyalty'],$info['cutaapprove'],$info['cutacredit'],$info['cutasource'],$info['cutaorganiz']))): ?>selected="selected"<?php endif; ?>  ><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    	</select><?php endif; endforeach; endif; else: echo "" ;endif; ?>
				</p>
				
				<p>
				<label for="cutaFunnel">&nbsp;</label>
                <?php if(is_array($dic)): $i = 0; $__LIST__ = array_slice($dic,7,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; if($val['is_must'] == 0): ?><select name="<?php echo ($val["field"]); ?>" id="<?php echo ($val["field"]); ?>">
							<option value=""><?php echo ($k); echo ($val["name"]); ?></option>
							<?php if(is_array($val['sub'])): $i = 0; $__LIST__ = $val['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>"  data="<?php dump(array($info['cutaloyalty'],$info['cutaapprove'],$info['cutacredit'],$info['cutasource'],$info['cutaorganiz']));?>"   <?php if(in_array($v['id'],array($info['cutaloyalty'],$info['cutaapprove'],$info['cutacredit'],$info['cutasource'],$info['cutaorganiz']))): ?>selected="selected"<?php endif; ?>  ><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    	</select><?php endif; endforeach; endif; else: echo "" ;endif; ?>
				</p>
				
                
                <p>
                    <label for="cutaContent">公司简介</label>
                    <textarea name="cutaContent" class="mf" style="height:70px;"><?php echo ($info["cutacontent"]); ?></textarea>
                </p>
        	</div>
        
        </div>
        </form>
    </div>
</div>

<script src="/hunter/theme/admin/js/kindeditor/kindeditor.js"></script>	
</div>
<script>
var check_name_url = "<?php echo U('custom/ajax_check_name',array('id'=>$id));?>";
$.formValidator.initConfig({formid:"info_form",autotip:true});
$('#cutaName').formValidator({onshow:lang.please_input+"公司名称",onfocus:lang.please_input+"公司名称"}).inputValidator({min:1,onerror:lang.please_input+"公司名称"});
$('#cutaTelephone').formValidator({onshow:lang.please_input+"总机电话",onfocus:lang.please_input+"总机电话"}).inputValidator({min:1,onerror:lang.please_input+"总机电话"});
$('#cucoName').formValidator({onshow:lang.please_input+"联系人",onfocus:lang.please_input+"联系人"}).inputValidator({min:1,onerror:lang.please_input+"联系人"});
$('#cucoFunction').formValidator({onshow:lang.please_input+"称谓",onfocus:lang.please_input+"称谓"}).inputValidator({min:1,onerror:lang.please_input+"称谓"});

$("#cutaShortName").formValidator({onshow:lang.please_input+'简称/总机电话',onfocus:lang.please_input+'公司名称'}).inputValidator({min:1,onerror:lang.please_input+'简称/总机电话'}).defaultPassed().ajaxValidator({
	    type : "get",
		url : check_name_url,
		datatype : "json",
		async:'false',
		success : function(result){	
              if(result.status == 0){
                  return false;
			}else{
                  return true;
			}
		},
		onerror : '简称/总机电话',
		onwait : lang.connecting_please_wait
	});
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
$('.J_cate_place_select').cate_select({top_option:lang.all,field:'J_pid',target_class:'J_cate_place_select'}); //分类联动
</script>