<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content">
	<div id="tabs">
		<form id="info_form" name="info_form" action="<?php echo U('project/edit');?>" method="post">
			<input type="hidden" name="custom_id" id="prtaCustId" value="<?php echo ($info["custom_id"]); ?>">
			<input type="hidden" name="prtaContactIds" id="prtaContactIds" value="<?php echo ($info["prtacontactids"]); ?>">
			<input type="hidden" name="id"  value="<?php echo ($info["id"]); ?>">
			<div id="tabs-1">
				<div class="form_default" style="padding:0; height:458px; OVERFLOW-Y: auto; BORDER: #ccc 1px solid;">
					<p>
						<label for="cutaName">项目名称</label>
						<input type="text" name="prtaName" id="prtaName" value="<?php echo ($info["prtaname"]); ?>" class="sf" style="width:280px;">
						<select name="<?php echo ($dic[0]['field']); ?>" id="field">
							<option value="0"><?php echo ($dic[0]['name']); ?></option>
							<?php if(is_array($dic[0]['sub'])): $i = 0; $__LIST__ = $dic[0]['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" <?php if(in_array($val['id'],array($info['prtafunnel'],$info['prtalevel'],$info['prtatype']))): ?>selected="selected"<?php endif; ?> ><?php echo ($val["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
						<select name="prtaState" id="prtaState">
							<option value="">状态</option>
							<option value="1" selected="">启用</option>
							<option value="0">不启用</option>
						</select>
					</p>
					<p>
						<label for="cutaTelephone">所属客户</label>
						<input type="button" id="selectCustomerBtn" class="button button_white" value="<?php echo ($info["cutaname"]); ?>">&nbsp;&nbsp;&nbsp;&nbsp;客户联系人：<input type="button" id="selectCustomerContactBtn" class="button button_white" value="<?php echo ($info["cont_name"]); ?>" style="max-width:300px;">
					</p>
					<div id="selectCustomer" class="dropbox" style="top:135px;left:128px; width:auto; min-width:280px; position: absolute; background: #f7f7f7;display:none;">
						<div style="padding:10px;">
							<input type="text" name="keyword" id="keyword" value="" class="sf" style="width:180px;"><input type="button" id="searchCustomerBtn" class="button button_white" value=" 查询 " style="height:28px;">
						</div>
						<div id="customerResult">
	
							
						</div>
					</div>
					<div id="selectCustomerContact" class="dropbox" style="top:135px;left:270px;display:none; position: absolute; background: #f7f7f7; width:220px; height:auto; max-height:230px; OVERFLOW-Y: auto; BORDER: #ccc 1px solid;">
						<div class="messagelist">
						    <ul>
						        
						        
						        
						        
						    </ul>
						</div>
					</div>
	
					<p></p>
					<p>
						<label for="cutaTelephone">工作地区</label>
						<input type="text" name="prtaWorkCity" id="prtaWorkCity" class="sf" style="width:80px;" value="<?php echo ($info["prtaworkcity"]); ?>">
						
						<?php if(is_array($dic)): $i = 0; $__LIST__ = array_slice($dic,1,null,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><select name="<?php echo ($val["field"]); ?>" id="<?php echo ($val["field"]); ?>">
								<option value="0"><?php echo ($val["name"]); ?></option>	
								<?php if(is_array($val['sub'])): $i = 0; $__LIST__ = $val['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["id"]); ?>" <?php if(in_array($v['id'],array($info['prtafunnel'],$info['prtalevel'],$info['prtatype']))): ?>selected="selected"<?php endif; ?> ><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select><?php endforeach; endif; else: echo "" ;endif; ?>
						招聘人数&nbsp;&nbsp;<input type="text" name="prtaPositionNum" id="prtaPositionNum" class="sf" style="width:25px;" value="<?php echo ($info["prtapositionnum"]); ?>">名
					</p>
					<p>
						<label for="location">职位描述</label>
						<div class="ke-container ke-container-default" style="width: 490px;">
						<textarea name="info" class="info" style="width:492px;height:200px;visibility:hidden;resize:none;"><?php echo code2html($info['info']);?></textarea>
                
						</div>
						
					</p>
					<p>
						<label>项目编号</label>
						<input type="text" name="prtaCode" id="prtaCode" value="<?php echo ($info["prtacode"]); ?>" class="sf" style="width:180px;" maxlength="40">&nbsp;&nbsp;&nbsp;&nbsp;
						<span>
	                      <span>薪资福利&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<input type="text" name="prtaRemuneration" id="prtaRemuneration" value="<?php echo ($info["prtaremuneration"]); ?>" class="sf" style="width:180px;">
						</span>
					</p>
				</div>
	
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
var editor;  //全局变量
$(function() {
	editor =KindEditor.create('.info', {
		uploadJson : '<?php echo U("attachment/editer_upload");?>',
		fileManagerJson : '<?php echo U("attachment/editer_manager");?>',
		allowFileManager : true,
		 items : [
                    'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link','fullscreen']

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
	
	$('#selectCustomerBtn').click(function(){
		$('#selectCustomer').slideDown('fast');
		$('#selectCustomerContact').hide();
		ajax_customer_list(1);
	})
	function test(){
		alert(1)
	}
	$('#selectCustomerContactBtn').click(function(){
		var id=$('#prtaCustId').val();
		if(id!=0){
			$('#selectCustomerContact').slideDown('fast');
			$('#selectCustomer').hide()
		}
		else{
			$.pinphp.tip({content:'请选择客户', icon:'alert'});
		}
		
	})
	
	
	var cid=$('#prtaCustId').val();//客户ID
	if(cid){
		contact_list(cid);//初始化联系人列表
	}
	ajax_customer_list(1);
	$('#searchCustomerBtn').click(function(){
		ajax_customer_list(cid);
	});
	function page_click(){
		$('.link a').click(function(){
			var now_page=$('.link .current').html();
			if($(this).hasClass('current')==false){
				var Request = new Object();
				Request = GetRequest($(this).attr('href'));
				var p= Request['p'];
				ajax_customer_list(p);return false;
			}
			
		})
	}
	//选择客户
	function select_cus(){
		$('.messagelist ul li').click(function(){
			$('#prtaCustId').val($(this).attr('data-id'));
			$('#selectCustomerBtn').val($(this).attr('data-name'));
			$('#selectCustomer').hide();
			$('#selectCustomerContactBtn').val('请选择联系人');
			$('#prtaContactIds').val(0);
			contact_list($(this).attr('data-id'));//联系人列表
		})
	}
	//选择联系人
	function select_contact(){
		$(".name").click(function(){
			var str='';
			var id='';
			$(".name").each(function(){
				if($(this).prop('checked')==true){
					str+=$(this).attr('cuconame')+',';
					id +=$(this).val()+',';
				}
			})
			$('#selectCustomerContactBtn').val(str.substr(0,str.length-1));
			$('#prtaContactIds').val(id.substr(0,id.length-1));
			$('#selectCustomerContact').hide();
		})
	}
	//客户列表
	function ajax_customer_list(p){
		var name=$('#keyword').val();
		var p=p?p:1;
		$.get("<?php echo U('Project/ajax_list');?>",{name:name,p:p},function(msg){
			$('#customerResult').html(msg['data']);page_click();select_cus();
		});
	}
	//联系人列表
	function contact_list(cus_id){
		$.get("<?php echo U('Project/ajax_contact_list');?>",{custom_id:cus_id},function(msg){
			$('#selectCustomerContact').html(msg);select_contact();
		});
	}
	function GetRequest(url) {
	   var theRequest = new Object();
	   if (url.indexOf("?") != -1) {
	      var str = url.substr(1);
	      strs = str.split("&");
	      for(var i = 0; i < strs.length; i ++) {
	         theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
	      }
	   }
	   return theRequest;
	}
});
</script>