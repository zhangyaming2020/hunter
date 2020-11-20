<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<style>
	.share_button li{
		float:right;
	}
	#users li{
		float:left;
	}
</style>
<div class="dialog_content" style="padding:10px; OVERFLOW-Y: auto;">
	<div id="tabs" class="tabs2 ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
				<a href="javascript:;">项目详情</a>
				
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;"  data-dom-id="projectResumePage"   data-uri="<?php echo U('project/candidate',array('page_size'=>8,'project_id'=>$project_id));?>">候选人</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;" data-dom-id="body"  data-uri="<?php echo U('report/index',array('page_size'=>8,'project_id'=>$project_id));?>">推荐报告</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">offer</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">定向寻猎</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">财务</a>
			</li>
	
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">邮件列表</a>
			</li>
	
			<div style="float:right; margin:8px 80px 0 0;">
				<a class="iconlink2" href="javascript:;" id="projectUserTreeBtn"><img class="mgright5" alt="" src="../../images/people.png"><span>当前项目成员结构</span></a>
			</div>
		</ul>
		<div id="projectUserTree" class="dropbox" style="position: absolute; width: 190px; background: rgb(255, 255, 255); border: 1px solid rgb(204, 204, 204); left: 835px; top: 44px; display: none; overflow-x: hidden; overflow-y: auto;">
		</div>
		<div id="tabs-1" style="padding:10px; OVERFLOW-Y: auto;height:500px;" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td valign="top" style="padding-right:10px;">
							<div style="margin:8px 0;">
								<?php if($info['owner_id'] == $_SESSION['admin']['id'] or $_SESSION['admin']['id'] == 1 ): ?><button  style="color:#333;" class="J_showdialog button button_white" data-editor="1"  data-uri="<?php echo U('project/edit', array('id'=>$project_id));?>" data-title="编辑客户资料" data-id="edit" data-width="700"  id="editProject">编辑资料</button> &nbsp;
								<button class="button button_white" id="selectUserMgrBtn">设置共享</button> &nbsp;
								<div id="selectUserMgr" class="dropbox" style="top:143px;left:118px; display:none; position: absolute; width:240px; background: #fff; BORDER: #ccc 1px solid;">
									<div style="padding:10px;background: #eee;OVERFLOW-Y: auto; text-align: right; BORDER-TOP: #ccc 1px solid;">
										
										<div class="sTableWrapper" style="padding:10px 0 0 10px;height:100px; OVERFLOW-Y: auto;">
											<ul id="users" class="ztree" style="float:left;clear:both;">
												<input type="hidden" name="share_ids" value="<?php echo ($share_ids); ?>">
												<input type="hidden" name="id" value="<?php echo ($info['id']); ?>">
												<?php if(is_array($admin)): $i = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li>
													<input type="checkbox" <?php if(in_array($val['id'],explode(',',$info['share_ids']))): ?>checked<?php endif; ?> <?php if($val['id'] == $_SESSION['admin']['id']): ?>disabled<?php endif; ?> name="ids" value="<?php echo ($val["id"]); ?>">
													 &nbsp;&nbsp;<?php echo ($val["username"]); ?>
												 </li>
												 <br /><?php endforeach; endif; else: echo "" ;endif; ?>
											</ul>
											<div>
											</div>
										</div>
									</div>
									<div class="share_button" style="float:right;">
									<li>
									<input type="button" class="button button_white share_cancel" style="margin-right:10px;" value=" 取消 " onclick="hideMenu();">
									</li>
									<li>
									<input type="button" id="userMrgBtnConfirm" style="margin-right:10px;" class="button button_white share_sure" value=" 确定 ">
									</li>
									</div>
								</div>
								<div id="selectMgrUsersList" style="display:none;">
									<label>
	                    	转移至：
	                    	<select id="selectMgrUsersListId">
	                    		<option value="0">请选择</option>
	                    		
	                    		<option value="1">管理员</option>
	                    		
	                    		
	                    		
	                    	</select>
	                    	</label>
								</div>
								<!--<button class="button button_white" id="selectUserBtn">设置助理顾问</button> &nbsp;
								<div id="selectUser" class="dropbox" style="display:none; position: absolute; width:240px; background: #fff; BORDER: #ccc 1px solid;">
									<iframe id="userIfram" src="../../asyncUserBox?selectIds=4&amp;disabledIds=1,1,1" frameborder="0" width="240" height="280" style="OVERFLOW-Y: auto;"></iframe>
									<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
										<a href="javascript:;" id="userHelp" original-title="可指定多个助理顾问与您合作，指定的用户只能提供推荐报告。"><img src="../../images/help.gif">什么是助理顾问?</a>&nbsp;&nbsp;
										<input type="button" id="userBtnConfirm" class="button button_white" value=" 确定 ">
										<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
									</div>
								</div>-->
	
								<button class="button button_white" id="projectState">暂停</button> &nbsp;
	
								<button class="button button_white J_confirmurl" id="selectUserMgrBtn" style="cursor:pointer;" data-refresh="1" data-uri="<?php echo u('project/delete', array('id'=>$info['id']));?>" data-acttype="ajax" data-msg="<?php echo sprintf(L('confirm_delete_one'),$info['prtaname']);?>">删除</button> &nbsp;
								
								<!--<button class="button button_blue" id="setRatio">设置提成点</button> &nbsp;--><?php endif; ?>
	
							</div>
							<div id="detailPage">
	
								<h4>客户：<a href="javascript:;" id="detailCustomerBtn">广州特猎商务服务有限公司</a></h4>
								<div id="detailCustomerDiv" class="dropbox" style="display:none; position: absolute; background: #f7f7f7; width:520px; height:auto; max-height:350px; OVERFLOW-Y: auto; BORDER: #ccc 1px solid;"></div>
	
								<table width="100%" class="sTable2" cellspacing="0" cellpadding="0">
									<thead>
										<tr>
											<td width="24"></td>
											<td width="60">联系人</td>
											<td width="30">性别</td>
											<td width="60">称谓</td>
											<td>办公电话</td>
											<td width="30">操作</td>
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
												<a class="iconlink2" href="javascript:;" onclick="sendMail(193);" title="发送邮件"><img alt="发送邮件" src="/hunter/theme/admin/images/mail_send.png"></a>
											</td>
										</tr>
										<tr id="contact_detail_193" class="contact_detail" style="display:none;">
											<td colspan="6">
												<table width="100%" border="0" cellspacing="0" cellpadding="0" class="sTable2">
													<tbody>
														<tr>
															<td align="right" width="80">年龄：</td>
															<td><?php echo ($val["cucoage"]); ?></td>
															<td align="right" width="80">重要程度：</td>
															<td></td>
														</tr>
														<tr>
															<td align="right">认可程度：</td>
															<td></td>
															<td align="right">传真：<?php echo ($val["cucofax"]); ?></td>
															<td></td>
														</tr>
														<tr>
															<td align="right">手机：<?php echo ($val["cucomobile"]); ?></td>
															<td></td>
															<td align="right">邮箱：<?php echo ($val["cucoemail"]); ?></td>
															<td></td>
														</tr>
														<tr>
															<td align="right">QQ：<?php echo ($val["cucoqq"]); ?></td>
															<td></td>
															<td align="right">MSN：<?php echo ($val["cucomsn"]); ?></td>
															<td></td>
														</tr>
														<tr>
															<td align="right">家庭电话：<?php echo ($val["cucohometel"]); ?></td>
															<td colspan="3"></td>
														</tr>
														<tr>
															<td align="right">关心重点：</td>
															<td colspan="3"><?php echo ($val["cucoaboutkey"]); ?></td>
														</tr>
														<tr>
															<td align="right">利益诉求：</td>
															<td colspan="3"><?php echo ($val["cucointerest"]); ?></td>
														</tr>
														<tr>
															<td align="right">备注：<?php echo ($val["cucoremarker"]); ?></td>
															<td colspan="3"></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr><?php endforeach; endif; else: echo "" ;endif; ?>
										
	
									</tbody>
								</table>
	
								<table width="100%" border="0" cellspacing="0" cellpadding="5" class="sTable2" style="margin-top:5px;">
									<tbody>
										<tr>
											<td align="right" width="80">项目名称：</td>
											<td colspan="2" style="font-size:14px; font-weight: bold;"><?php echo ($info["prtaname"]); ?></td>
											<td>
	
												<select name="funnel" id="funnel" style="padding:0;">
													<option value="0">开发漏斗</option>
	
													<option value="63">特别紧急</option>
	
													<option value="64">一般</option>
	
													<option value="65" selected="">滞后</option>
	
													<option value="2790">prospect</option>
	
												</select>
	
											</td>
										</tr>
										<tr>
											<td align="right">项目状态：</td>
											<td>
	
												<font color="green">正常</font>
	
											</td>
											<td align="right">项目编号：</td>
											<td><?php echo ($info["prtacode"]); ?></td>
										</tr>
	
										<tr>
											<td align="right">薪资福利：</td>
											<td colspan="3"><?php echo ($info["prtaremuneration"]); ?></td>
										</tr>
	
										<tr>
											<td colspan="4" style="padding:10px;">
												<h4>职位描述(JD)：</h4>
												<span style="white-space:nowrap;"><?php echo code2html($info['info']);?></span>
											</td>
										</tr>
										<tr>
											<td align="right">招聘人数：</td>
											<td><?php echo ($info["prtapositionnum"]); ?></td>
											<td align="right" width="80">工作地点：</td>
											<td><?php echo ($info["prtaworkcity"]); ?></td>
										</tr>
										<tr>
											<td align="right">职位类别：</td>
											<td>计算机/互联网/通信/电子</td>
											<td align="right">职位级别：</td>
											<td>主管</td>
										</tr>
										<tr>
											<td align="right">创建时间：</td>
											<td><?php echo (date('Y-m-d H:i:s',$info["add_time"])); ?></td>
											<td align="right">创建者：</td>
											<td><?php echo ($info["username"]); ?></td>
										</tr>
	
										<tr>
											<td align="right">所有者：</td>
											<td colspan="3">
												<select id="selectOwnerUserList" style="padding:0;">
	
													<?php if(is_array($admin)): $i = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" data-id="<?php echo ($val["id"]); ?>" <?php if($info['owner_id'] == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["username"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	
												</select>
												&nbsp;&nbsp;<input type="button" value="更改所有者" id="selectOwnerUserBtn" class="iconlink2"></td>
										</tr>
	
									</tbody>
								</table>
							</div>
						</td>
						<td width="400" style="border-left: 1px solid #ddd; padding-left:10px; min-height:420px;" valign="top">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tbody>
									<tr>
										<td id="followInputView">
	
											<form id="followFrm" name="followFrm" method="post">
												<input type="hidden" name="cmfoId" id="cmfoId" value="0">
												<input type="hidden" name="cmfoType" id="cmfoType" value="3">
												<input type="hidden" name="cmfoDataId" id="cmfoDataId" value="186">
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
								<div class="ke-inline-block "><iframe name="kindeditor_upload_iframe_1547779903436" style="display:none;"></iframe>
									<form class="ke-upload-area ke-form" method="post" enctype="multipart/form-data" target="kindeditor_upload_iframe_1547779903436" action="../../servlet/uploader?kindeditor=true"><span class="ke-button-common"><input type="button" class="ke-button-common ke-button" value="新增附件"></span><input type="file" class="ke-upload-file" name="accessoryFile" tabindex="-1" style="width: 72px;"></form>
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
			<div class="left">
				<a href="javascript:;" class="reloadButton">刷新</a>
				<a href="javascript:;"   style="color:#fff;"    class="J_showdialog  addNewButton" data-uri="<?php echo U('Resume/add',array('project_id'=>$project_id));?>" data-title="新增简历" data-id="resume_add" data-editor="1" data-width="783" data-height="500">新增简历</a>
				
		<a href="javascript:;"  class="dialog_open  addNewButton" style="color:#fff;"   data-uri="<?php echo U('Resume/doc_import');?>" data-title="导入简历" data-id="doc_import" data-editor="1" data-width="783" data-height="550">导入简历</a>
		<a href="javascript:;" class="addNewButton" id="projectResumeSearchButton" dataid="191">查询简历库</a>
					<a href="javascript:;" class="addNewButton" id="addNewCopyButton" dataid="191">解析简历</a>
				
				<ul class="submenu" id="resumeTabs">
					<li class="current" dataid="0">
						<a href="javascript:;">全部 (2)</a>
					</li>
			
					<li dataid="18">
						<a href="javascript:;">意向强烈 (0)</a>
					</li>
			
					<li dataid="19">
						<a href="javascript:;">一般1 (0)</a>
					</li>
			
					<li dataid="78">
						<a href="javascript:;">待联系 (2)</a>
					</li>
			
					<li dataid="79">
						<a href="javascript:;">暂无意向 (0)</a>
					</li>
			
					<li dataid="2794">
						<a href="javascript:;">有问题 (0)</a>
					</li>
			
					<li dataid="2862">
						<a href="javascript:;">意向不明 (0)</a>
					</li>
			
					<li dataid="2896">
						<a href="javascript:;">abc (0)</a>
					</li>
			
				</ul>
				<div class="sTableOptions">
					<div class="form_default">
						<form id="projectResumeSearchFrom">
							<input type="hidden" name="prtype" id="prtype" value="0">
							<input type="hidden" name="pjId" id="pjId" value="191"> 姓名：
							<input type="text" name="keyword" id="projectResumeSearchKeyword" class="sf" style="width:180px;"> 简历编号：
							<input type="text" name="no" id="no" style="width:60px;"> 当前公司：
							<input type="text" name="curCompany" id="curCompany" style="width:60px;">
							<input type="button" id="searchFromBtn" class="button button_red" value=" 查询 ">
							<div style="float:right;">
								<input type="button" id="copyResumes" class="button button_red" value="复制到其他项目">
								<input type="button" id="moveResumes" class="button button_red" value="移动到其他项目">
							</div>
						</form>
			
					</div>
				</div>
				<!--sTableOptions-->
				<div id="projectResumePage">
				</div>
			</div>	
			
			
		</div>
		<div id="ui-tabs-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide"></div>
		<div id="ui-tabs-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide"></div>
		<div id="ui-tabs-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide"></div>
		<div id="ui-tabs-5" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide"></div>
		<div id="ui-tabs-6" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide"></div>
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
$('.ui-state-default').off('clik').click(function(){
	var index=$(this).index();
	var url=$(this).find('a').attr('data-uri');
	var dom=$(this).find('a').attr('data-dom-id');
	if(dom){
		if(dom=='body'){
			dom=$('.ui-tabs-panel').eq(index);
		}
		else{
			dom=$('#'+'projectResumePage');
			
		}
	}
	if(url){
		$.get(url,'',function(msg){
			dom.html(msg['data']);
			setTimeout(function(){
				$('#J_ajax_loading').hide();
			},500);
			
			reloadButton(dom);page_click();
		});
	}
	$(this).addClass('ui-state-active').siblings('.ui-state-default').removeClass('ui-state-active');
	$('.ui-tabs-panel').eq(index).show().siblings('.ui-tabs-panel').hide();
})
//页码点击
$(document).delegate('.ajax_pagination a','click',function(){
		if($(this).hasClass('current')==false){
			var Request = new Object();
			var url=$(this).attr('href');
			Request = GetRequest(url);
			var p= Request['p'];
			var dom='#projectResumePage';//初始化dom
			ajax_index(p,url,dom);return false;
		}
		
})
//异步搜索或点击链接
function ajax_index(p,url,dom){
	var p=p?p:1;
	var index=$('.ui-state-active').index();
		var _body=$('.ui-tabs-panel').eq(index);
		if(url){
			$.get(url,{p:p},function(msg){
				$(''+dom+'').html(msg['data']);reloadButton();
			});
		}
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
		var _body=$('#projectResumePage');
		if(url){
			$.get(url,{p:p},function(msg){
				_body.html(msg['data']);reloadButton();
			});
		}
	})
	
}
//新增跟进
$('#followBtn').click(function(){
	var content=$("#cmfoContent").val();
	var url="<?php echo U('Progress/add');?>";
	var data={content:content,type:1,project_id:"<?php echo ($project_id); ?>"};
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
	$.get("<?php echo U('Progress/index',array('type'=>1,'project_id'=>$project_id));?>",{p:p},function(msg){
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

$("input[type=checkbox]").click(function(){
		var ids='';
		$("input[type=checkbox]:checked").each(function(){
			ids +=$(this).val()+',';
		})
		$("input[name=share_ids]").val(ids.substr(0,ids.length-1));
	})
//分享
$('.share_sure').click(function(){
	var ids=$("input[name=share_ids]").val(),
		id=$("input[name=id]").val(),
		data={is_share:(ids?1:0),share_ids:ids,id:id};
		$.post("<?php echo U('project/edit');?>",data,function(result){
			$.pinphp.tip({content:result.msg});
			$('#selectUserMgr').hide();
		});
})

$('.share_cancel').click(function(){
	$('#selectUserMgr').hide();
})
$('#selectUserMgrBtn').on("click", function(e) {
	$("#selectUserMgr").slideUp();　　
	$("#selectUserMgr").slideDown('fast');　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });
$('#selectUserMgr').on("click", function(e) {　　　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });　
document.onclick = function(e) {　　　　　　　　
     $("#selectUserMgr").hide();　　　
}
function stopFunc(e) {　　　　　　
     e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;　　　　
 }
</script>