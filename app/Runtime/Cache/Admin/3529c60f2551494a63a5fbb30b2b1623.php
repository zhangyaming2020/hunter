<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content" style="padding:10px; OVERFLOW-Y: auto;">
	<div id="tabs" class="tabs2 ui-tabs ui-widget ui-widget-content ui-corner-all">
		<div id="tabs-1" style="padding:10px; OVERFLOW-Y: auto; min-height:450px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td valign="top" style="padding-right:10px;">
							<?php switch($info["oftastate"]): case "0": ?><div style="margin:8px 0;">
						
										<button style="color:#333;" class="J_showdialog button button_white" data-editor="1" data-uri="<?php echo U('offer/edit', array('id'=>$info['id'],'custom_id'=>$info['custom_id']));?>" data-title="编辑offer" data-id="edit" data-width="600" id="editProject">编辑</button>
						
										<button class="button button_blue" data-id="<?php echo ($info["id"]); ?>" data-status="1" data-uri="<?php echo U('offer/edit');?>" data-title="消息" data-msg="你确认此操作吗？" id="offerStateBtn">设置状态为已通知候选人</button>
						
									</div><?php break;?>
						
								<?php case "1": ?><div style="margin:8px 0;">
										<button style="color:#333;" class="J_showdialog button button_white" data-editor="1" data-uri="<?php echo U('offer/edit', array('id'=>$info['id'],'custom_id'=>$info['custom_id']));?>" data-title="编辑offer" data-id="edit" data-width="600">编辑</button>
						
										<button style="color:#fff;" class="button button_blue dialog_detail" dataid="2" id="offerFinishBtn" data-uri="<?php echo U('offer/finish', array('id'=>$info['id'],'custom_id'=>$info['custom_id']));?>" data-title="完成offer" data-id="finish" data-width="700">完成offer</button>
						
										<button class="button button_white" dataid="-1" id="commentFail">失败</button>
										<div id="commentFailDiv" class="dropbox" style="display:none; left:173px;top:100px;position: absolute; width:380px; background: #fff; BORDER: #ccc 1px solid; overflow: hidden;">
											<p><textarea id="commentFailReason" name="remark" style="width:370px; height:120px;"></textarea></p>
											<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
												请认真填写offer失败理由&nbsp;&nbsp;
												<input type="button" class="button button_white act_sure" data-uri="<?php echo U('offer/edit');?>" data-id="<?php echo ($info["id"]); ?>" data-title="消息" data-msg="你确认该offer已失败吗？" data-status="5" value=" 确定 ">
												<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
											</div>
										</div>
						
									</div><?php break;?>
								<?php case "2": ?><div style="margin:8px 0;">
						
										<button style="color:#333;" class="J_showdialog button button_white" data-editor="1" data-uri="<?php echo U('offer/edit', array('id'=>$info['id'],'custom_id'=>$info['custom_id']));?>" data-title="编辑offer" data-id="edit" data-width="600">编辑</button>
						
										<button  style="color:#fff;" class="button button_blue dialog_detail" dataid="2" id="offerFinishBtn" data-uri="<?php echo U('offer/check', array('id'=>$info['id'],'custom_id'=>$info['custom_id']));?>" data-title="完成offer" data-id="check" data-width="700">审核通过</button>
										<button class="button button_red" dataid="2" id="overOfferFail" data-id="<?php echo ($info["id"]); ?>" data-status="7" data-uri="<?php echo U('offer/edit');?>" data-title="消息" data-msg="你确认审核不通过该offer吗？">审核不通过</button>
						
									</div><?php break;?>
								<?php case "3": ?><div style="margin:8px 0;">
										<button class="button button_blue"  data-id="<?php echo ($info["id"]); ?>" data-status="4" data-uri="<?php echo U('offer/edit');?>" data-title="消息" data-msg="你确认此操作吗？" id="follow_btn" type="button"> 完成 </button>
										<button class="button button_white" dataid="-1" id="commentFail">失败</button>
										<div id="commentFailDiv" class="dropbox" style="display:none; position: absolute; width:380px; background: #fff; BORDER: #ccc 1px solid; overflow: hidden;">
											<p><textarea id="commentFailReason" name="remark" style="width:370px; height:120px;"></textarea></p>
											<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
												请认真填写offer失败理由&nbsp;&nbsp;
												<input type="button" class="button button_white" value=" 确定 " onclick="applyCommentFailConfirm();">
												<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
											</div>
										</div>
						
									</div><?php break;?>
								<?php case "4": ?><div style="margin:8px 0;">
						
									</div><?php break;?>
								<?php case "5": ?><div style="margin:8px 0;">
						
										<button style="color:#333;" class="J_showdialog button button_white" data-editor="1" data-uri="<?php echo U('offer/edit', array('id'=>$info['id'],'custom_id'=>$info['custom_id']));?>" data-title="编辑offer" data-id="edit" data-width="600">编辑</button>
						
										<button class="button button_green" id="overOfferSuccess2" data-id="<?php echo ($info["id"]); ?>" data-status="6" data-uri="<?php echo U('offer/edit');?>" data-title="消息" data-msg="你确认审核通过该offer失败吗？">审核通过</button>
										<button class="button button_red" id="overOfferFail2" data-id="<?php echo ($info["id"]); ?>" data-status="1" data-uri="<?php echo U('offer/edit');?>" data-title="消息" data-msg="你确认审核不通过该offer失败吗？">审核不通过</button>
						
									</div><?php break;?>
								<?php case "6": ?><div style="margin:8px 0;">
						
									</div><?php break;?>
								<?php case "7": ?><div style="margin:8px 0;">
										<button style="color:#fff;" class="button button_blue dialog_detail" dataid="2" id="offerFinishBtn" data-uri="<?php echo U('offer/finish', array('id'=>$info['id'],'custom_id'=>$info['custom_id']));?>" data-title="完成offer" data-id="finish" data-width="700">完成offer</button>
									
										<button class="button button_white" dataid="-1" id="commentFail">失败</button>
										<div id="commentFailDiv" class="dropbox" style="display:none; left:173px;top:100px;position: absolute; width:380px; background: #fff; BORDER: #ccc 1px solid; overflow: hidden;">
											<p><textarea id="commentFailReason" name="remark" style="width:370px; height:120px;"></textarea></p>
											<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
												请认真填写offer失败理由&nbsp;&nbsp;
												<input type="button" class="button button_white act_sure" data-uri="<?php echo U('offer/edit');?>" data-id="<?php echo ($info["id"]); ?>" data-title="消息" data-msg="你确认该offer已失败吗？" data-status="5" value=" 确定 ">
												<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
											</div>
										</div>
									
									</div><?php break; endswitch;?>
							<table width="100%" border="0" cellspacing="0" cellpadding="5" class="sTable2" style="margin-top:5px;">
								<tbody>
									<tr>
										<td align="right">客户名：</td>
										<td colspan="3"><?php if($info.cus_id): echo M('custom')->where(array('id'=>$info['cus_id']))->getField('cutaname'); endif; ?></td>
									</tr>
									<tr>
										<td align="right">项目名：</td>
										<td colspan="3"><?php echo ($info["prtaname"]); ?></td>
									</tr>
						
									<tr>
										<td align="right" width="80">候选人：</td>
										<td colspan="2" style="font-size:14px; font-weight: bold;">
											<a href="javascript:;"><?php echo ($info["resuname"]); ?></a>
										</td>
										<td>状态：
											<font color="#808080">待通知候选人</font>
						
										</td>
									</tr>
						
									<tr>
										<td align="right">offer年薪：</td>
										<td><?php echo ($info["money"]); ?></td>
										<td align="right">下达日期：</td>
										<td><?php echo (date('Y-m-d H:i:s',$info["senddate"])); ?></td>
									</tr>
									<tr>
										<td align="right">创建时间：</td>
										<td><?php echo (date('Y-m-d H:i:s',$info["add_time"])); ?></td>
										<td align="right">创建人：</td>
										<td><?php echo ($info["username"]); ?></td>
									</tr>
									<tr>
										<td align="right">保证期：</td>
										<td colspan="3"><?php echo (date('Y-m-d H:i:s',$info["guaranteeenddate"])); ?></td>
									</tr>
						
									<tr>
										<td align="right">备注：</td>
										<td colspan="3"><?php echo ($info["remark"]); ?></td>
									</tr>
								</tbody>
							</table>
							<h2 style="margin:10px 0;">公司收入</h2>
							<table width="100%" border="0" cellspacing="0" cellpadding="5" class="sTable2" style="margin-top:5px;">
						
								<tbody>
									<tr>
										<td align="right">提成点：</td>
										<td><?php echo ($info["cotacommissionpoint"]); ?>%</td>
									</tr>
									<tr>
										<td align="right" width="120">预计总收入：</td>
										<td><?php echo sprintf("%.2f",$info['money']*$info['cotacommissionpoint']/100);?></td>
									</tr>
									<tr>
										<td align="right">付款比例：</td>
										<td><?php echo ($info["cotaonepoint"]); ?>% , <?php echo ($info["cotatwopoint"]); ?>%</td>
									</tr>
									<tr>
										<td align="right">成功后付款：</td>
										<td>1000.00</td>
									</tr>
									<tr>
										<td align="right">尾款：</td>
										<td><?php echo sprintf("%.2f",$info['money']*$info['cotatwopoint']/100);?></td>
									</tr>
						
								</tbody>
							</table>
						</td>
						<td width="380" style="border-left: 1px solid #ddd; padding-left:10px; min-height:450px;" valign="top">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tbody>
									<tr>
										<td id="followInputView">
	
											<form id="followFrm" name="followFrm" method="post">
												<input type="hidden" name="cmfoId" id="cmfoId" value="0">
												<input type="hidden" name="cmfoType" id="cmfoType" value="7">
												<input type="hidden" name="cmfoDataId" id="cmfoDataId" value="232">
												<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
													<tbody>
														<tr>
															<td bgcolor="#eeeeee">新增跟进：</td>
														</tr>
														<tr>
															<td><textarea name="cmfoContent" rows="2" id="cmfoContent" style="width:380px;"></textarea>
															</td>
														</tr>
														<tr>
															<td><input type="checkbox" name="cmfoIsRemind" id="cmfoIsRemind" value="1" onclick="this.checked?document.getElementById('isOpen').style.display='':document.getElementById('isOpen').style.display='none'"><label for="cmfoIsRemind">需要系统自动提醒跟进</label></td>
														</tr>
														<tr style="display:none;" id="isOpen">
															<td>提醒时间：<input name="remindDate" type="text" id="remindDate" value="" size="20" maxlength="16">
	
															</td>
														</tr>
														<tr>
															<td><button class="button button_blue" id="followBtn" type="button"> 新增 </button></td>
														</tr>
													</tbody>
												</table>
											</form>
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tbody>
													<tr>
														<td height="6"></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td id="followPageView">
	
											<style>
												.sTable2 thead tr td,
												.sTable2 tbody tr td {
													padding: 3px;
												}
											</style>
											<table width="100%" class="sTable2" id="followlist" cellspacing="0" cellpadding="0">
												<thead>
													<tr>
														<td width="90">跟进日期</td>
														<td width="50">跟进人</td>
														<td>跟进内容</td>
													</tr>
												</thead>
												<tbody>
	
													<tr>
														<td colspan="3">暂无跟进记录</td>
													</tr>
	
												</tbody>
											</table>
											<table width="10%" border="0" align="center" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td height="10"></td>
													</tr>
												</tbody>
											</table>
											<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td>
															<form id="ebb270fe8858c0dc8fc48cfadfe2b954"><input type="hidden" name="type" value="7"><input type="hidden" name="dataId" value="232"><input type="hidden" name="page" id="page-ebb270fe8858c0dc8fc48cfadfe2b954" value="1"></form>
														</td>
													</tr>
												</tbody>
											</table>
											<table width="10%" border="0" align="center" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td height="10"></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<p style="margin:5px 0;">
								<div class="ke-inline-block "><iframe name="kindeditor_upload_iframe_1549866919235" style="display:none;"></iframe>
									<form class="ke-uploa           d-area ke-form" method="post" enctype="multipart/form-data" target="kindeditor_upload_iframe_1549866919235" action="/servlet/uploader?kindeditor=true"><span class="ke-button-common"><input type="button" class="ke-button-common ke-button" value="新增附件"></span><input type="file" class="ke-upload-file" name="accessoryFile" tabindex="-1" style="width: 72px;"></form>
								</div><input type="button" id="uploadAccessory" value="新增附件" style="display: none;"></p>
							<div id="accessoryList" style="margin:5px 0;">
	
								<style>
									.sTable2 thead tr td,
									.sTable2 tbody tr td {
										padding: 3px;
									}
								</style>
	
								<table width="100%" class="sTable2" id="followlist" cellspacing="0" cellpadding="0">
									<thead>
										<tr>
											<td width="50">创建人</td>
											<td width="95">创建日期</td>
											<td>附件名</td>
											<td width="60">下载次数</td>
										</tr>
									</thead>
									<tbody>
	
										<tr>
											<td>管理员</td>
											<td>2019-2-1 9:41</td>
											<td style="word-break:break-all;">
	
												<a href="javascript:accessoryPreview('79411f3f-31cc-495b-80b2-e7e6d5925ab5','customer_import_template (1).xls');">customer_import_template (1).xls</a>
	
												&nbsp;&nbsp;
												<a href="/accessory/download/79411f3f-31cc-495b-80b2-e7e6d5925ab5" title="下载" target="_blank">下载</a>
	
												&nbsp;&nbsp;
												<a href="javascript:;" title="删除" onclick="deleteAccessory(572);"><img src="/images/dock.colse.png" border="0"></a>
	
											</td>
											<td>0</td>
										</tr>
	
										<tr>
											<td>管理员</td>
											<td>2019-2-11 11:9</td>
											<td style="word-break:break-all;">
	
												<a href="javascript:accessoryPreview('1e955403-f738-439a-903d-707693872409','print4.png');">print4.png</a>
	
												&nbsp;&nbsp;
												<a href="/accessory/download/1e955403-f738-439a-903d-707693872409" title="下载" target="_blank">下载</a>
	
												&nbsp;&nbsp;
												<a href="javascript:;" title="删除" onclick="deleteAccessory(573);"><img src="/images/dock.colse.png" border="0"></a>
	
											</td>
											<td>0</td>
										</tr>
	
									</tbody>
								</table>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	//$.pinphp.tip({content:'加载成功', icon:'alert'});
	
	$("#offerStateBtn,#overOfferSuccess2,#overOfferFail2,#overOfferFail,#follow_btn").click(function(){
		dialog_open($(this));
	})
	
//失败 
$('#commentFail').on("click", function(e) {　
	$("#commentFailDiv").slideUp();　　
	$("#commentFailDiv").slideDown('fast');　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });
 
 
document.onclick = function(e) {　　　　　　　
     hideMenu();
}

 
 // 创建面试和推荐失败操作
 $(".act_sure").click(function(){
 	dialog_open($(this));
 })　　　　　
 $('#commentFailDiv').on("click", function(e) {　　　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });　
 
 function stopFunc(e) {　　　　　　
     e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;　　　　
 }
 function hideMenu(){
 	$("#commentFailDiv").hide();
 }
 
 //新增跟进
$('#followBtn').click(function(){
	var content=$("#cmfoContent").val();
	var id="<?php echo ($info['id']); ?>";
	var url="<?php echo U('Progress/add');?>";
	var data={content:content,type:4,offer_id:id};
	if(content){
		$.post(url,data,function(result){
			$.pinphp.tip({content:result.msg, icon:'alert'});
			progress_record(1);$("#cmfoContent").val('');
		})
	}
	else{
		$.pinphp.tip({content:'请输入跟进内容', icon:'alert'});
	}
	
})
//跟进记录
progress_record(1);
function progress_record(p){
	var id="<?php echo ($info['id']); ?>";
	$.get("<?php echo U('Progress/index',array('type'=>4,'offer_id'=>$info['id']));?>",{p:p},function(msg){
		$('#followPageView').html(msg['data']);
	});
}
//页码点击
$(document).delegate('.progress_pagination a','click',function(){
			var now_page=$('.progress_pagination .current').html();
			if($(this).hasClass('current')==false){
				var Request = new Object();
				Request = GetRequest($(this).attr('href'));
				var p= Request['p'];
				progress_record(p);return false;
			}
})
//获取地址参数
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
//会话框
function dialog_open(dom_self){
	var self = dom_self,
	uri = self.attr('data-uri'),
	remark=self.parents('.dropbox').find("textarea[name=remark]").val(),
	status=self.attr('data-status'),
	title = (self.attr('data-title') != undefined) ? self.attr('data-title') : lang.confirm_title,
	data='remark='+remark+'&oftaState='+status+'&id='+self.attr('data-id');
	msg = self.attr('data-msg');
	var acttype="ajax";
	$.dialog({
		title:title,
		content:msg,
		padding:'10px 20px',
		lock:true,
		ok:function(){
			if(acttype == 'ajax'){
				$.post(uri,data,function(result){
					if(result.status == 1){
						$.pinphp.tip({content:result.msg});
						$("#commentFailDiv").hide();
						if(callback != undefined){
							eval(callback+'(self)');
						}else{
							if(refresh==1){
								window.location.reload();
							}
						}
					}else{
						$.pinphp.tip({content:result.msg, icon:'error'});
					}
				});
			}else{
				location.href = uri;
			}
		},
		cancel:function(){
		}
	});
}
</script>