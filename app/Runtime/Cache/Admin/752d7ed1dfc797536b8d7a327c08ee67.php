<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content" style="padding:10px; OVERFLOW-Y: auto;">
	<div id="tabs" class="tabs2 ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
				<a href="javascript:;">简历详情</a>
			</li>
			<li id="maxFollowTabLi" class="ui-state-default ui-corner-top" style="display: none;">
				<a href="javascript:;">跟进记录</a>
			</li>
	
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">附件</a>
			</li>
	
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;"  data-uri="<?php echo U('project/index',array('is_tab'=>1,'page_size'=>8,'resume_id'=>$resume_id));?>">参与项目</a>
			</li>
	
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">邮件列表</a>
			</li>
			<li class="ui-state-default ui-corner-top">
				<a href="javascript:;">修改记录</a>
			</li>
	
		</ul>
		<div id="tabs-1" style="padding:0px; overflow: hidden;" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td valign="top">
							<iframe src="<?php echo U('resume/detail_info',array('resume_id'=>$resume_id));?>" id="resumedetail" style="width:100%;height:435px;"></iframe>
						</td>
						<td width="350" valign="top">
							<div style="padding-top: 10px; padding-bottom: 10px; padding-left: 10px; height: 415px; overflow-y: auto; overflow-x: hidden; padding-right: 20px !important;" id="maxWindowFollow">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tbody>
										<tr>
											<td id="followInputView">
	
												<form id="followFrm" name="followFrm" method="post">
													<input type="hidden" name="cmfoId" id="cmfoId" value="0">
													<input type="hidden" name="cmfoType" id="cmfoType" value="4">
													<input type="hidden" name="cmfoDataId" id="cmfoDataId" value="3270">
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
											<td>
												<ul class="submenu" id="followPageTabs">
													<li datatype="-1" class="current">
														<a href="javascript:;">默认跟进记录<span></span></a>
													</li>
													<li datatype="0">
														<a href="javascript:;">推荐报告跟进记录<span></span></a>
													</li>
													<li datatype="1">
														<a href="javascript:;">offer跟进记录<span></span></a>
													</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td id="followPageView" style="padding-top:5px;">
	
												
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="tabs-2" style="padding:10px; OVERFLOW-Y: auto;" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td id="followInputView"></td>
					</tr>
					<tr>
						<td>
							<ul class="submenu" id="followPageTabs">
								<li datatype="-1" class="current">
									<a href="javascript:;">默认跟进记录<span></span></a>
								</li>
								<li datatype="0">
									<a href="javascript:;">推荐报告跟进记录<span></span></a>
								</li>
								<li datatype="1">
									<a href="javascript:;">offer跟进记录<span></span></a>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td id="followPageView" style="padding-top:5px;"></td>
					</tr>
				</tbody>
			</table>
		</div>
	
		<div id="tabs-3" style="padding:10px; OVERFLOW-Y: auto;" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td id="accessoryPage">
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
										<td colspan="11">没有附件.</td>
									</tr>
	
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="ui-tabs-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
	
			<div class="left">
				<a href="javascript:;" style="cursor:pointer;" class="reloadButton">刷新</a>
				<a href="javascript:;" class="dialog_open addNewButton"  data-uri="<?php echo U('project/index',array('is_add'=>1,'page_size'=>8,'resume_id'=>$resume_id));?>" data-title="<?php echo ($val["resuname"]); ?>" data-id="index" data-width="1200" data-height="600" id="addProjectButton" dataid="3270">加入项目</a>
	
				<ul class="submenu">
					<li class="current" dataid="0"><b>全部项目</b></li>
				</ul>
	
				<div id="resumeProjectPage" style="margin-top:20px;">
	
					
	
					
				</div>
			</div>
			<!--left-->
	
			<br clear="all">
		</div>
		<div id="ui-tabs-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
	
			<div class="left">
				<input type="hidden" value="" id="pageSC">
				<a href="javascript:;" class="reloadButton" onclick="pageReload();">刷新</a>
				<a href="javascript:;" class="addNewButton" id="addNewEmailButton" datatype="1" dataids="3270">撰写新邮件</a>
				<ul class="submenu">
					<li class="current" dataid="0">
						<a href="javascript:;">收件箱</a>
					</li>
					<li dataid="1">
						<a href="javascript:;">发件箱</a>
					</li>
					<li dataid="2">
						<a href="javascript:;">草稿箱</a>
					</li>
					<li dataid="3">
						<a href="javascript:;">垃圾邮件</a>
					</li>
					<li dataid="4">
						<a href="javascript:;">已删除</a>
					</li>
				</ul>
	
				<div class="sTableOptions">
					<div class="pp">
						<a class="button blue" id="getEmail"><span>收取邮件</span></a>
						<a class="button" id="rubbishEmail"><span>垃圾邮件</span></a>
						<a class="button" id="deleteEmail"><span>删除</span></a>
						<a class="button" id="thoroughgoingDeleteEmail"><span>彻底删除</span></a>
						<a class="button" id="signEmailNotNew"><span>标记为已读</span></a>
						<div style="float:right;">
							<form id="emailSearchFrom">
								<input type="hidden" value="0" name="type" id="type">
								<input type="hidden" value="1" name="dataType" id="dataType">
								<input type="hidden" value="3270" name="dataIds" id="dataIds"> 邮件标题/发件人关键字：
								<input type="text" name="keyword" id="keyword" class="sf" style="width:180px;">
								<input type="button" id="searchFromBtn" class="button button_red" value=" 查询 ">
							</form>
						</div>
					</div>
				</div>
				<!--sTableOptions-->
				<div id="emailListPage">
	
					<style>
						.sTable2 tbody tr td {
							padding: 3px 10px;
							cursor: pointer;
						}
						
						.sTable2 tbody tr.new td {
							font-weight: bold;
						}
					</style>
					<form id="emailListFrom">
						<table width="100%" class="sTable2" cellspacing="0" cellpadding="0">
							<thead>
								<tr>
									<td width="14" style="min-width:14px;"><input type="checkbox" id="selAllMail"></td>
									<td width="60">&nbsp;</td>
									<td width="200">发件人</td>
									<td width="*">邮件标题</td>
									<td width="150">发送时间</td>
								</tr>
							</thead>
							<tbody id="emailTbody">
	
								<tr>
									<td colspan="5">未查询到数据.</td>
								</tr>
	
							</tbody>
						</table>
					</form>
					<br clear="all">
					<div class="pagination">
						<form id="737a10590dab2d382739107a2d8e9f04" onkeydown="var e=window.event || arguments[0];if(e.keyCode==13){ajaxPage737a10590dab2d382739107a2d8e9f04(document.getElementById('pageInput-737a10590dab2d382739107a2d8e9f04').value);return false;}"><input type="hidden" name="dataType" value="1"><input type="hidden" name="dataIds" value="3270"><input type="hidden" name="page" id="page-737a10590dab2d382739107a2d8e9f04" value="1">
							<a class="first disabled">« 首页</a>
							<a class="prev disabled">‹ 上一页</a>
							<a href="javascript:ajaxPage737a10590dab2d382739107a2d8e9f04(1);" class="current">1</a>
							<a class="next disabled">下一页 ›</a>
							<a class="last disabled">尾页 »</a>
							<input type="text" id="pageInput-737a10590dab2d382739107a2d8e9f04" value="1" size="5" style="height:20px;"><input type="button" class="button button_blue" id="pageBtn-737a10590dab2d382739107a2d8e9f04" onclick="ajaxPage737a10590dab2d382739107a2d8e9f04(document.getElementById('pageInput-737a10590dab2d382739107a2d8e9f04').value);" style="height:30px;" value="跳转">共查询到
							<font color="#CC0000">0</font>条记录，每页显示
							<font color="#CC0000">30</font>条记录。</form>
					</div>
				</div>
			</div>
			<!--left-->
	
			<br clear="all">
	
		</div>
		<div id="ui-tabs-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
	
			<table width="100%" class="sTable2" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td width="40" style="min-width:24px;" align="center">版本号</td>
						<td width="120">修改日期</td>
						<td width="80">修改者</td>
						<td width="65">修改字段数</td>
						<td width="">修改字段</td>
						<td width="50">操作</td>
					</tr>
				</thead>
				<tbody>
	
					<tr>
						<td colspan="6">本简历未修改过.</td>
					</tr>
	
				</tbody>
			</table>
	
			<br clear="all">
		</div>
	
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
$('.ui-state-default').off('click').click(function(){
	var index=$(this).index();
	var url=$(this).find('a').attr('data-uri');
	if(index!=0){
		
	}
	if(url){
		$.get(url,'',function(msg){
			$('.ui-tabs-panel').eq(index).find('#resumeProjectPage').html(msg['data']);
			//reloadButton();
		});
	}
	$(this).addClass('ui-state-active').siblings('.ui-state-default').removeClass('ui-state-active');
	$('.ui-tabs-panel').eq(index).show().siblings('.ui-tabs-panel').hide();
})

$(document).delegate(".ajax_pagination a","click",function(){
	if($(this).hasClass('current')==false){
			var Request = new Object();
			var url=$(this).attr('href');
			Request = GetRequest(url);
			var p= Request['p'];
			var is_tab=Request['is_tab'];
			var _this;
			if(is_tab!=1){
				_this=$(this).parents('.d-content');
			}
			else{
				_this=$(this).parents('#resumeProjectPage');
			}
			ajax_index(p,url,_this);return false;
	}
});
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
$(document).delegate(".reloadButton","click",function(){
		var p=p?p:1;
		var index=$('.ui-state-active').index();
		var url=$('.ui-state-active').find('a').attr('data-uri');
		var _body=$('#resumeProjectPage');
		if(url){
			$.get(url,{p:p},function(msg){
				_body.html(msg['data']);//reloadButton();page_click();
			});
		}
})
//异步搜索或点击链接
function ajax_index(p,url,_this){
	var p=p?p:1;
		var _body=_this;
		if(url){
			$.get(url,{p:p},function(msg){
				_body.html(msg['data']);
			});
		}
}
$(".d-footer .d-buttons input").eq(1).val('关闭');
$(".d-footer .d-buttons input").eq(0).val('编辑简历');
$(".d-footer .d-buttons input").eq(0).addClass('resume_edit');
$(".d-footer .d-buttons input").eq(0).click(function(){
	$('.resume_edit').click();
	return false;
})
//新增跟进
$('#followBtn').click(function(){
	var content=$("#cmfoContent").val();
	var url="<?php echo U('Progress/add');?>";
	var data={content:content,type:2,resume_id:"<?php echo ($resume_id); ?>"};
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
	$.get("<?php echo U('Progress/index',array('type'=>2,'resume_id'=>$resume_id));?>",{p:p},function(msg){
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
//弹窗表单
$('.resume_edit').live('click', function(){
	    var resume_id="<?php echo ($resume_id); ?>";
		var self = $(this),
			dtitle ='辅导费',
			did = 'resume',
			duri = '/jradmin.php?m=Admin&c=Resume&a=edit&id='+resume_id,
			dwidth = 783,
			dheight = 500,
			dpadding = 10,
			dcallback = self.attr('data-callback');
		$.dialog({id:did}).close();
		$.dialog({
			id:did,
			title:dtitle,
			width:dwidth ? dwidth : 'auto',
			height:dheight ? dheight : 'auto',
			padding:dpadding,
			lock:true,
			ok:function(){
				//异步实例化编辑器后实现表单赋值操作
				$('.info').val(editor.html());
				var info_form = this.dom.content.find('#info_form');
				if(info_form[0] != undefined){
					info_form.submit();
					if(dcallback != undefined){
						eval(dcallback+'()');
					}
					return false;
				}
				if(dcallback != undefined){
					eval(dcallback+'()');
				}
			},
			cancel:function(){
			}
		});
		$.getJSON(duri, function(result){
			if(result.status == 1){
				$.dialog.get(did).content(result.data);
			}
		});
	});
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
</script>