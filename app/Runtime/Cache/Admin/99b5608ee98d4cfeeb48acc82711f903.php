<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content" style="padding:10px; OVERFLOW-Y: auto;">
	<div id="tabs" class="tabs2 ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
				<a href="javascript:;">详细资料</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;" data-uri="<?php echo U('project/index',array('page_size'=>8,'custom_id'=>$custom_id));?>">项目</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;" data-uri="<?php echo U('Contract/index',array('page_size'=>8,'custom_id'=>$custom_id));?>">合同</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">财务</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">邮件列表</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">修改记录</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">联系人修改记录</a>
			</li>
		</ul>
		<div id="tabs-1" style="padding:10px; OVERFLOW-Y: auto;height:500px;" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td valign="top" width="800" style="padding-right:10px;">
							<div id="contactsPage">
	
								<table width="100%" class="sTable2" id="followlist" cellspacing="0" cellpadding="0">
									<thead>
										<tr>
											<td width="24"></td>
											<td width="60">联系人</td>
											<td width="30">性别</td>
											<td width="100">称谓</td>
											<td width="270">办公电话</td>
											<td width="180">操作</td>
										</tr>
									</thead>
									<tbody>
										<?php if(is_array($contact)): $i = 0; $__LIST__ = $contact;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
											<td><img class="wrap" alt="" src="/hunter/theme/admin/images/nolines_plus.gif" border="0" style="cursor: pointer;"></td>
											<td><?php echo ($val["cuconame"]); ?></td>
											<td>
											<?php if($val["cucosex"] == 2): ?>男
											<?php else: ?>
											女<?php endif; ?>
											</td>
											<td><?php echo ($val["cucofunction"]); ?></td>
											<td><?php echo ($val["cucoofficetel"]); ?></td>
											<td>
												<?php if($info['owner_id'] == $_SESSION['admin']['id'] or $_SESSION['admin']['id'] == 1 ): ?><a class="iconlink2 J_confirmurl" id="selectUserMgrBtn" style="cursor:pointer;" data-refresh="0" data-uri="<?php echo u('linkman/delete', array('id'=>$val['id']));?>" data-acttype="ajax" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['cuconame']);?>" title="删除"><img alt="删除" src="/hunter/theme/admin/images/close.png"></a>&nbsp;&nbsp;
												<a  href="javascript:;" class="iconlink2 J_showdialog"    data-uri="<?php echo U('linkman/edit', array('id'=>$val['id'],'custom_id'=>$custom_id));?>" data-title="编辑" data-id="edit" data-width="600" title="编辑" data-height="100" ><img alt="编辑" src="/hunter/theme/admin/images/editor.png"></a>&nbsp;&nbsp;<?php endif; ?>
												<a href="javascript:;" class="iconlink2 dialog_detail" title="发送邮件"     data-uri="<?php echo U('custom/email', array('id'=>$val['id']));?>" data-title="发送邮件" data-id="email" data-width="900"  data-height="517"><img alt="发送邮件" src="/hunter/theme/admin/images/mail_send.png"></a>
											
											</td>
										</tr>
										<tr id="contact_detail_193" class="contact_detail" style="display:none;">
											<td colspan="6">
												<table width="100%" border="0" cellspacing="0" cellpadding="0" class="sTable2">
													<tbody>
														<tr>
															<td align="right" width="80">年龄：</td>
															<td></td>
															<td align="right" width="80">重要程度：</td>
															<td></td>
														</tr>
														<tr>
															<td align="right">认可程度：</td>
															<td></td>
															<td align="right">传真：</td>
															<td></td>
														</tr>
														<tr>
															<td align="right">手机：</td>
															<td></td>
															<td align="right">邮箱：</td>
															<td></td>
														</tr>
														<tr>
															<td align="right">QQ：</td>
															<td></td>
															<td align="right">MSN：</td>
															<td></td>
														</tr>
														<tr>
															<td align="right">家庭电话：</td>
															<td colspan="3"></td>
														</tr>
														<tr>
															<td align="right">关心重点：</td>
															<td colspan="3"></td>
														</tr>
														<tr>
															<td align="right">利益诉求：</td>
															<td colspan="3"></td>
														</tr>
														<tr>
															<td align="right">备注：</td>
															<td colspan="3"></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr><?php endforeach; endif; else: echo "" ;endif; ?>
										
	
									</tbody>
								</table>
							</div>
							<?php if($info['owner_id'] == $_SESSION['admin']['id'] or $_SESSION['admin']['id'] == 1 ): ?><div style="margin:8px 0;">
	
								<button style="color:#333;" class="J_showdialog button button_white" id="editCustomer"  data-uri="<?php echo U('custom/edit', array('id'=>$custom_id));?>" data-title="编辑客户资料" data-id="edit" data-width="600" data-height="500"  id="newCustomer">
									编辑资料
								</button> &nbsp;
								<?php if($type == 2): ?><button class="button button_white J_confirmurl" id="setShare" data-refresh="1"  data-uri="<?php echo u('custom/add_public', array('id'=>$custom_id,'act'=>1));?>" data-acttype="ajax" data-msg="<?php echo sprintf('你确认要占有此客户吗',$val['title']);?>">占有</button> &nbsp;
								<?php else: ?>
								<button style="color:#fff;" class="J_showdialog button button_blue" id="newContact"    data-uri="<?php echo U('linkman/add', array('custom_id'=>$custom_id));?>" data-title="新增联系人" data-id="add" data-width="600" data-height="100" >新增联系人</button> &nbsp;
								<button class="button button_white J_confirmurl" id="setPublic" data-refresh="1"  data-uri="<?php echo u('custom/add_public', array('id'=>$custom_id));?>" data-acttype="ajax" data-msg="<?php echo sprintf('你确认放入公共库吗',$val['title']);?>">放入公共库</button> &nbsp;
								<button  style="color:#666;" class="J_showdialog button button_white" id="setShare"    data-uri="<?php echo U('custom/share', array('share_ids'=>$info['share_ids'],'custom_id'=>$custom_id));?>" data-title="客户共享" data-id="share" data-width="420" data-height="300" >共享</button> &nbsp;
								<button class="button button_white" id="pushMoney">分成</button> &nbsp;<?php endif; ?>
								
	
							</div><?php endif; ?>
							<div id="detailPage">
	
								<style>
									.sTable2 td {
										word-break: break-all;
										word-wrap: break-word;
									}
								</style>
								<table width="100%" border="0" cellspacing="0" cellpadding="5" class="sTable2">
									<tbody>
										<tr>
											<td align="right" width="80">客户名称：</td>
											<td colspan="3" style="font-size:14px; font-weight: bold;"><?php echo ($info["cutaname"]); ?></td>
										</tr>
										<tr>
											<td align="right">当前状态：</td>
											<td colspan="3">
												<?php if($info['owner_id'] == $_SESSION['admin']['id'] or $_SESSION['admin']['id'] == 1 ): ?><p>所有者：
	
													<select id="selectOwnerUserList" style="padding:0;">
													<option>请选择</option>
													<?php if(is_array($admin)): $i = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="1" <?php if($info['owner_id'] == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["username"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
														
	
													</select>
													&nbsp;&nbsp;<input type="button" value="更改所有者" id="selectOwnerUserBtn" class="iconlink2">
	
												</p><?php endif; ?>
												<!--<p><b>分成：</b></p>
	
												<p>总公司&nbsp;»&nbsp;总经理&nbsp;&nbsp;
													<font color="#006699">(20%)</font>
												</p>
	-->
											</td>
										</tr>
										<tr>
											<td align="right">所在省/市：</td>
											<td><?php echo ($info["proname"]); ?>&nbsp;&nbsp;<?php echo M('place')->where(array('id'=>$info['cutacity']))->getField('name');?></td>
											<?php if($info['owner_id'] == $_SESSION['admin']['id'] or $_SESSION['admin']['id'] == 1 ): ?><td align="right" width="80">客户漏斗：</td>
											<td>
	
												<select name="funnel" id="funnel">
													<option value="0">开发漏斗</option>
	
													<option value="58" selected="">已合作</option>
	
													<option value="59">待确定合同</option>
	
													<option value="60">有意向，跟进中</option>
	
													<option value="61">暂缓</option>
	
													<option value="62">不考虑</option>
	
												</select>
	
											</td><?php endif; ?>
										</tr>
										<tr>
											<td align="right">客户类型：</td>
											<td>重点客户</td>
											<td align="right">来源：</td>
											<td></td>
										</tr>
										<tr>
											<td align="right">所属行业：</td>
											<td>消费品</td>
											<td align="right">认可程度：</td>
											<td></td>
										</tr>
										<tr>
											<td align="right">机构类型：</td>
											<td></td>
											<td align="right">忠诚度：</td>
											<td></td>
										</tr>
										<tr>
											<td align="right">重要程度：</td>
											<td>很重要</td>
											<td align="right">信誉度：</td>
											<td></td>
										</tr>
										<tr>
											<td align="right">公司地址：</td>
											<td colspan="3"><?php echo ($info["cutaaddress"]); ?></td>
										</tr>
										<tr>
											<td align="right">总机电话：</td>
											<td><?php echo ($info["cutatelephone"]); ?></td>
											<td align="right">公司网址：</td>
											<td><?php echo ($info["cutaweb"]); ?></td>
										</tr>
										<tr>
											<td align="right">公司简介：</td>
											<td colspan="3"><?php echo ($info["cutacontent"]); ?></td>
										</tr>
										<tr>
											<td align="right">创建时间：</td>
											<td><?php echo (date('Y-m-d H:i:s',$info["add_time"])); ?></td>
											<td align="right">创建者：</td>
											<td><?php echo ($info["username"]); ?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</td>
						<td width="420" style="border-left: 1px solid #ddd; padding-left:10px; min-height:420px;" valign="top">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tbody>
									<tr>
										<td id="followInputView">
	
											<form id="followFrm" name="followFrm" method="post">
												<input type="hidden" name="cmfoId" id="cmfoId" value="0">
												<input type="hidden" name="cmfoType" id="cmfoType" value="1">
												<input type="hidden" name="cmfoDataId" id="cmfoDataId" value="161">
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
	
											
										</td>
									</tr>
								</tbody>
							</table>
							<p style="margin-bottom:5px;">
								<div class="ke-inline-block "><iframe name="kindeditor_upload_iframe_1547533163407" style="display:none;"></iframe>
									<form class="ke-upload-area ke-form" method="post" enctype="multipart/form-data" target="kindeditor_upload_iframe_1547533163407" action="../../servlet/uploader?kindeditor=true"><span class="ke-button-common"><input type="button" class="ke-button-common ke-button" value="新增附件"></span><input type="file" class="ke-upload-file" name="accessoryFile" tabindex="-1" style="width: 72px;"></form>
								</div><input type="button" id="uploadAccessory" value="新增附件" style="display: none;"></p>
							<div id="accessoryList">
	
								<style>
									.sTable2 thead tr td,
									.sTable2 tbody tr td {
										padding: 3px;
									}
								</style>
	
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="ui-tabs-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
			
		</div>
		<div id="ui-tabs-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">2</div>
		<div id="ui-tabs-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">3</div>
		<div id="ui-tabs-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">4</div>
		<div id="ui-tabs-5" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">5</div>
		<div id="ui-tabs-6" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">6</div>
	</div>
</div>
<script>
$('.wrap').toggle(function(){
	$(this).attr('src',"/hunter/theme/admin/images/nolines_minus.gif");
	$(this).parents('tr').next().show();
},function(){
    $(this).parents('tr').next().hide();
    $(this).attr('src',"/hunter/theme/admin/images/nolines_plus.gif");
})
//tab键切换
$('.ui-state-default').click(function(){
	var index=$(this).index();
	var url=$(this).find('a').attr('data-uri');
	if(url){
		$.get(url,'',function(msg){
			$('.ui-tabs-panel').eq(index).html(msg['data']);
			reloadButton();page_click();
		});
	}
	$(this).addClass('ui-state-active').siblings('.ui-state-default').removeClass('ui-state-active');
	$('.ui-tabs-panel').eq(index).show().siblings('.ui-tabs-panel').hide();
})
//页码点击
function page_click(){
	$('.ajax_pagination a').click(function(){
		if($(this).hasClass('current')==false){
			var Request = new Object();
			var url=$(this).attr('href');
			Request = GetRequest(url);
			var p= Request['p'];
			ajax_index(p,url);return false;
		}
		else{
			return false;
		}
		
	})
}
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
//异步更新数据
function reloadButton(p){
	$('.reloadButton').click(function(){
		var p=p?p:1;
		var index=$('.ui-state-active').index();
		var url=$('.ui-state-active').find('a').attr('data-uri');
		var _body=$('.ui-tabs-panel').eq(index);
		if(url){
			$.get(url,{p:p},function(msg){
				_body.html(msg['data']);reloadButton();page_click();
			});
		}
	})
	
}
//异步搜索或点击链接
function ajax_index(p,url){
	var p=p?p:1;
	var index=$('.ui-state-active').index();
		var _body=$('.ui-tabs-panel').eq(index);
		if(url){
			$.get(url,{p:p},function(msg){
				_body.html(msg['data']);reloadButton();page_click();
			});
		}
}

//新增跟进
$('#followBtn').click(function(){
	var content=$("#cmfoContent").val();
	var url="<?php echo U('Progress/add');?>";
	var data={content:content,type:0,custom_id:"<?php echo ($custom_id); ?>"};
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
	$.get("<?php echo U('Progress/index',array('type'=>0,'custom_id'=>$custom_id));?>",{p:p},function(msg){
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
</script>