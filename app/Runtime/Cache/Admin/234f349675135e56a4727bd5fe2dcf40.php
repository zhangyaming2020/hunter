<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content" style="padding:10px; OVERFLOW-Y: auto;">
	<div id="tabs" class="tabs2 ui-tabs ui-widget ui-widget-content ui-corner-all">
		<div id="tabs-1" style="padding:10px; OVERFLOW-Y: auto; min-height:450px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td valign="top" style="padding-right:10px;">
						<?php switch($info["status"]): case "0": ?><div style="margin:8px 0;">
								
										<button class="button button_white J_showdialog" id="editReport"    style="color:#333;" data-uri="<?php echo U('report/edit',array('id'=>$info['id'],'project_id'=>$info['project_id']));?>" data-title="编辑报告" data-id="edit" data-width="610" data-height="300">编辑</button>
								
										<button class="button button_blue" dataid="1" id="commentReport">推荐</button>
										<button class="button button_red" dataid="-3" id="nocommentReport" style="float:right;">不予推荐</button>
								
									</div>
									<div id="handleState" class="dropbox" style="top:101px;left:88px;display:none; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 1000000;">
										<div style="padding:5px;">
											<form id="intaForm">
												<input type="hidden" name="operator_id" value="<?php echo $_SESSION['admin']['id'];?>">
												<input type="hidden" name="operator_time" value="<?php echo time();?>">
												<p>处理说明：<input type="hidden" value="" name="stateVal" id="stateVal"></p>
												<p><textarea name="intaContent" id="intaContent" class="mf" style="width:300px; height:60px;"></textarea></p>
												<div id="mailDiv" style="">
													<p><label><input type="checkbox" name="intaSendMail" id="intaSendMail" value="1">直接发送到HR邮箱</label></p>
													<div id="mailContent" style="display:none;">
								
														<p><input type="text" name="intaMailTitle" id="intaMailTitle" value="智联招聘_赵凯_招聘经理_中文_20190107_1546844066045-忙不忙-推荐报告" placeholder="邮件标题" style="width:300px;"></p>
														<p style="margin-top:5px;"><textarea name="intaMailContent" id="intaMailContent" placeholder="邮件内容" class="mf" style="width:300px; height:80px;">您好，智联招聘_赵凯_招聘经理_中文_20190107_1546844066045-忙不忙-推荐报告，请审阅。</textarea></p>
								
														<p><label><input type="checkbox" name="accessIds" value="/uploads/File/2019/02/01/03201767-39f4-4a2b-b54c-04933424369e.xls|customer_import_template (1).xls" checked="">附件： customer_import_template (1).xls</label></p>
								
														<p><label><input type="checkbox" name="accessIds" value="/uploads/File/2019/02/11/f01fa2e4-e2cc-4959-a9c1-29f1c276b9da.png|print4.png" checked="">附件： print4.png</label></p>
								
													</div>
												</div>
								
											</form>
										</div>
										<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
											<input type="button" class="button button_white J_confirmurl" id="act_sure" data-status="1" data-id="<?php echo ($info['id']); ?>" data-uri="<?php echo U('report/edit', array('id'=>$info['id']));?>" data-title="消息" data-msg="确定此操作吗" value=" 确定 ">
											<input type="button" class="button button_white" value=" 取消 " id="act_cancel">
										</div>
									</div>
									<div id="interviewDiv" class="dropbox" style="display:none; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 1000000;">
										<form id="interForm" method="post">
											<div style="padding:5px;">
												<p>面试时间：
													<input type="hidden" value="0" name="intaId" id="intaId">
													
													<input type="hidden" value="232" name="intaRereId" id="intaRereId">
													<input type="text" name="interDate" id="time_start1" class="date sf" size="20"  style="width:120px;" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="">
												</p>
												<p>面试说明：</p>
												<p><textarea name="interContent" id="interContent" class="mf" style="width:300px; height:50px;"></textarea></p>
												<p style="color:red;">面试说明请认真填写，如：初试、复试、视频面试等.</p>
												<p>面试结果：<label><input type="radio" value="-1" name="interState">失败</label>&nbsp;&nbsp;<label><input type="radio" value="0" name="interState" checked="">待面试</label>&nbsp;&nbsp;<label><input type="radio" value="1" name="interState">成功</label></p>
											</div>
											<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
												<input type="button" id="interConfirm" class="button button_white" value=" 确定 ">
												<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
											</div>
										</form>
									</div><?php break;?>
							<?php case "1": ?><div style="margin:8px 0;">
								
										<button class="button button_blue" dataid="2" id="createInterview">设置面试</button>
								
										<button class="button button_white" dataid="-1" style="float:right;" id="commentFail">推荐失败</button>
										<div id="handleFailState" class="dropbox" style="display:none;left:28px;top:101px; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 10000000000;">
											<div style="padding:5px;">
												<ul>
													<li class="interveiw_fail" style="display:none;">
														<form>
															<input type="hidden" name="operator_id" value="<?php echo $_SESSION['admin']['id'];?>">
															<input type="hidden" name="operator_time" value="<?php echo time();?>">
														<p>推荐失败原因：</p>
														<p>
															<textarea name="failContent" id="intaContent" class="mf" style="width:300px; height:120px;"></textarea>
														</p>
														</form>
													</li>
													<li class="interveiw">
														<form>
														<p>面试时间：
															<input type="hidden" value="0" name="intaId" id="intaId">
				
															<input type="hidden" value="219" name="intaRereId" id="intaRereId">
															<input type="text" name="interDate" id="time_start1" class="date sf" size="20"  style="width:120px;" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="">
														</p>
														<p>面试说明：</p>
														<p><textarea name="intaContent" id="interContent" class="mf" style="width:300px; height:50px;"></textarea></p>
														<p style="color:red;">面试说明请认真填写，如：初试、复试、视频面试等.</p>
														<p>面试结果：<label><input type="radio" value="-1" name="interState">失败</label>&nbsp;&nbsp;<label><input type="radio" value="0" name="interState" checked="">待面试</label>&nbsp;&nbsp;<label><input type="radio" value="1" name="interState">成功</label></p>
														</form>
													</li>
												</ul>
												</div>
											<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
												<input type="button" id="handleFailStateConfirm" class="button button_white" value=" 确定 " data-id="<?php echo ($info["id"]); ?>" data-uri="<?php echo U('report/edit');?>" data-title="消息" data-msg="你确认推荐失败吗" data-status="2">
												<input type="button" class="button button_white" value=" 取消 " id="act_cancel">
											</div>
										</div>
								
									</div><?php break;?>
							<?php case "2": ?><div style="margin:8px 0;">
								
										<button class="button button_blue" dataid="2" id="createInterview">新增面试</button>
										<button class="button button_blue" dataid="3" id="createOffer" class="addNewButton"  data-uri="<?php echo U('offer/add',array('report_id'=>$info['id'],'resume_id'=>$info['resume_id'],'project_id'=>$info['project_id'],'custom_id'=>$info['custom_id']));?>" data-title="生成offer" data-id="add" data-width="610" data-height="300" data-nums="<?php echo ($nums); ?>">创建offer</button>
										<button class="button button_white" dataid="-1" style="float:right;" id="commentFail">推荐失败</button>
										<div id="handleFailState" class="dropbox" style="display:none;left:28px;top:101px; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 10000000000;">
											<div style="padding:5px;">
												<ul>
													<li class="interveiw_fail" style="display:none;">
														<form>
															<input type="hidden" name="operator_id" value="<?php echo $_SESSION['admin']['id'];?>">
															<input type="hidden" name="operator_time" value="<?php echo time();?>">
															<p>推荐失败原因：</p>
															<p>
																<textarea name="intaContent" id="intaContent" class="mf" style="width:300px; height:120px;"></textarea>
															</p>
														</form>
													</li>
													<li class="interveiw">
														<form>
															<p>面试时间：
																<input type="hidden" value="0" name="intaId" id="intaId">
																<input type="hidden" value="219" name="intaRereId" id="intaRereId">
																<input type="text" name="interDate" id="time_start1" class="date sf" size="20"  style="width:120px;" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="">
															</p>
															<p>面试说明：</p>
															<p><textarea name="intaContent" id="interContent" class="mf" style="width:300px; height:50px;"></textarea></p>
															<p style="color:red;">面试说明请认真填写，如：初试、复试、视频面试等.</p>
															<p>面试结果：<label><input type="radio" value="-1" name="interState">失败</label>&nbsp;&nbsp;<label><input type="radio" value="0" name="interState" checked="">待面试</label>&nbsp;&nbsp;<label><input type="radio" value="1" name="interState">成功</label></p>
														</form>
													</li>
												</ul>
											</div>
											<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
												<input type="button" id="handleFailStateConfirm" class="button button_white" value=" 确定 " data-id="<?php echo ($info["id"]); ?>" data-uri="<?php echo U('report/edit');?>" data-title="消息" data-msg="你确认推荐失败吗" data-status="2">
												<input type="button" class="button button_white" value=" 取消 " id="act_cancel">
											</div>
										</div>
								
									</div>
									<table width="100%" class="sTable2" cellspacing="0" cellpadding="0">
										<thead>
											<tr>
												<td>面试说明</td>
												<td width="95">面试时间</td>
												<td width="50">状态</td>
												<td width="95">创建时间</td>
												<td width="40" align="center">操作</td>
											</tr>
										</thead>
										<tbody>
									
											<tr>
												<td>各个</td>
												<td>2019-2-13 9:43</td>
												<td>
									
													<font color="green">面试成功</font>
												</td>
												<td>
													管理员<br> 2019-2-12 9:42
												</td>
												<td align="center"></td>
											</tr>
									
										</tbody>
									</table><?php break;?>
							<?php case "3": ?><div style="margin:8px 0;">
								
									</div>
									<div id="handleState" class="dropbox" style="display:none; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 1000000;">
										<div style="padding:5px;">
											<form id="intaForm">
												<input type="hidden" name="operator_id" value="<?php echo $_SESSION['admin']['id'];?>">
												<input type="hidden" name="operator_time" value="<?php echo time();?>">
												<p>处理说明：<input type="hidden" value="" name="stateVal" id="stateVal"></p>
												<p><textarea name="intaContent" id="intaContent" class="mf" style="width:300px; height:60px;"></textarea></p>
												<div id="mailDiv">
													<p><label><input type="checkbox" name="intaSendMail" id="intaSendMail" value="1">直接发送到HR邮箱</label></p>
													<div id="mailContent" style="display:none;">
								
														<p><input type="text" name="intaMailTitle" id="intaMailTitle" value="15000-25000元/月-test2-推荐报告" placeholder="邮件标题" style="width:300px;"></p>
														<p style="margin-top:5px;"><textarea name="intaMailContent" id="intaMailContent" placeholder="邮件内容" class="mf" style="width:300px; height:80px;">您好，15000-25000元/月-test2-推荐报告，请审阅。</textarea></p>
								
														<p><label><input type="checkbox" name="accessIds" value="/uploads/File/2019/01/09/67449977-be54-4ba9-ba78-665d1fb4350f.txt|简历下载6.5.txt" checked="">附件： 简历下载6.5.txt</label></p>
								
													</div>
												</div>
								
											</form>
										</div>
										<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
											<input type="button" id="handleStateConfirm" class="button button_white" value=" 确定 ">
											<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
										</div>
									</div>
									<div id="interviewDiv" class="dropbox" style="display:none; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 1000000;">
										<form id="interForm" method="post">
											<div style="padding:5px;">
												<p>面试时间：
													<input type="hidden" value="0" name="intaId" id="intaId">
													<input type="hidden" value="227" name="intaRereId" id="intaRereId">
													<input type="text" name="interDate" id="interDate" value="" class="sf" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})">
												</p>
												<p>面试说明：</p>
												<p><textarea name="interContent" id="interContent" class="mf" style="width:300px; height:50px;"></textarea></p>
												<p style="color:red;">面试说明请认真填写，如：初试、复试、视频面试等.</p>
												<p>面试结果：<label><input type="radio" value="-1" name="interState">失败</label>&nbsp;&nbsp;<label><input type="radio" value="0" name="interState" checked="">待面试</label>&nbsp;&nbsp;<label><input type="radio" value="1" name="interState">成功</label></p>
											</div>
											<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
												<input type="button" id="interConfirm" class="button button_white" value=" 确定 ">
												<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
											</div>
										</form>
									</div><?php break;?>
							<?php case "4": ?><div style="margin:8px 0;">
								
									</div>
									<div id="handleState" class="dropbox" style="display:none; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 1000000;">
										<div style="padding:5px;">
											<form id="intaForm">
												<input type="hidden" name="operator_id" value="<?php echo $_SESSION['admin']['id'];?>">
												<input type="hidden" name="operator_time" value="<?php echo time();?>">
												<p>处理说明：<input type="hidden" value="" name="stateVal" id="stateVal"></p>
												<p><textarea name="intaContent" id="intaContent" class="mf" style="width:300px; height:60px;"></textarea></p>
												<div id="mailDiv">
													<p><label><input type="checkbox" name="intaSendMail" id="intaSendMail" value="1">直接发送到HR邮箱</label></p>
													<div id="mailContent" style="display:none;">
								
														<p><label><input type="checkbox" name="intaSendMailAddr" value="3124633839@qq.com">吴勇&lt;3124633839@qq.com&gt;</label></p>
								
														<p><input type="text" name="intaMailTitle" id="intaMailTitle" value="智联招聘_赵凯_招聘经理_中文_20190107_1546844066045-忙不忙-推荐报告" placeholder="邮件标题" style="width:300px;"></p>
														<p style="margin-top:5px;"><textarea name="intaMailContent" id="intaMailContent" placeholder="邮件内容" class="mf" style="width:300px; height:80px;">您好，智联招聘_赵凯_招聘经理_中文_20190107_1546844066045-忙不忙-推荐报告，请审阅。</textarea></p>
								
														<p><label><input type="checkbox" name="accessIds" value="/uploads/File/2019/02/01/03201767-39f4-4a2b-b54c-04933424369e.xls|customer_import_template (1).xls" checked="">附件： customer_import_template (1).xls</label></p>
								
														<p><label><input type="checkbox" name="accessIds" value="/uploads/File/2019/02/11/f01fa2e4-e2cc-4959-a9c1-29f1c276b9da.png|print4.png" checked="">附件： print4.png</label></p>
								
													</div>
												</div>
								
											</form>
										</div>
										<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
											<input type="button" id="handleStateConfirm" class="button button_white" value=" 确定 ">
											<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
										</div>
									</div>
									<div id="interviewDiv" class="dropbox" style="display:none; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 1000000;">
										<form id="interForm" method="post">
											<div style="padding:5px;">
												<p>面试时间：
													<input type="hidden" value="0" name="intaId" id="intaId">
													<input type="hidden" value="232" name="intaRereId" id="intaRereId">
													<input type="text" name="interDate" id="time_start1" class="date sf" size="20"  style="width:120px;" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="">
												</p>
												<p>面试说明：</p>
												<p><textarea name="interContent" id="interContent" class="mf" style="width:300px; height:50px;"></textarea></p>
												<p style="color:red;">面试说明请认真填写，如：初试、复试、视频面试等.</p>
												<p>面试结果：<label><input type="radio" value="-1" name="interState">失败</label>&nbsp;&nbsp;<label><input type="radio" value="0" name="interState" checked="">待面试</label>&nbsp;&nbsp;<label><input type="radio" value="1" name="interState">成功</label></p>
											</div>
											<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
												<input type="button" id="interConfirm" class="button button_white" value=" 确定 ">
												<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
											</div>
										</form>
									</div><?php break;?>
							<?php case "5": ?><div style="margin:8px 0;">
								
									</div>
									<div id="handleState" class="dropbox" style="display:none; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 1000000;">
										<div style="padding:5px;">
											<form id="intaForm">
												<input type="hidden" name="operator_id" value="<?php echo $_SESSION['admin']['id'];?>">
												<input type="hidden" name="operator_time" value="<?php echo time();?>">
												<p>处理说明：<input type="hidden" value="" name="stateVal" id="stateVal"></p>
												<p><textarea name="intaContent" id="intaContent" class="mf" style="width:300px; height:60px;"></textarea></p>
												<div id="mailDiv">
													<p><label><input type="checkbox" name="intaSendMail" id="intaSendMail" value="1">直接发送到HR邮箱</label></p>
													<div id="mailContent" style="display:none;">
								
														<p><label><input type="checkbox" name="intaSendMailAddr" value="3124633839@qq.com">吴勇&lt;3124633839@qq.com&gt;</label></p>
								
														<p><input type="text" name="intaMailTitle" id="intaMailTitle" value="15000-25000元/月-忙不忙-推荐报告" placeholder="邮件标题" style="width:300px;"></p>
														<p style="margin-top:5px;"><textarea name="intaMailContent" id="intaMailContent" placeholder="邮件内容" class="mf" style="width:300px; height:80px;">您好，15000-25000元/月-忙不忙-推荐报告，请审阅。</textarea></p>
								
														<p><label><input type="checkbox" name="accessIds" value="/uploads/File/2019/01/31/95e38680-4d0b-4b4b-ae49-9a79463bec71.html|智联招聘_赵凯2_招聘经理_中文_20190107_1546844066044.html" checked="">附件： 智联招聘_赵凯2_招聘经理_中文_20190107_1546844066044.html</label></p>
								
													</div>
												</div>
								
											</form>
										</div>
										<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
											<input type="button" id="handleStateConfirm" class="button button_white" value=" 确定 ">
											<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
										</div>
									</div>
									<div id="interviewDiv" class="dropbox" style="display:none; position: absolute; width:320px; background: #fff; BORDER: #ccc 1px solid; z-index: 1000000;">
										<form id="interForm" method="post">
											<div style="padding:5px;">
												<p>面试时间：
													<input type="hidden" value="0" name="intaId" id="intaId">
													<input type="hidden" value="230" name="intaRereId" id="intaRereId">
													<input type="text" name="interDate" id="time_start1" class="date sf" size="20"  style="width:120px;" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="">
               
												</p>
												<p>面试说明：</p>
												<p><textarea name="interContent" id="interContent" class="mf" style="width:300px; height:50px;"></textarea></p>
												<p style="color:red;">面试说明请认真填写，如：初试、复试、视频面试等.</p>
												<p>面试结果：<label><input type="radio" value="-1" name="interState">失败</label>&nbsp;&nbsp;<label><input type="radio" value="0" name="interState" checked="">待面试</label>&nbsp;&nbsp;<label><input type="radio" value="1" name="interState">成功</label></p>
											</div>
											<div style="padding:10px; background: #eee; text-align: right; BORDER-TOP: #ccc 1px solid;">
												<input type="button" id="interConfirm" class="button button_white" value=" 确定 ">
												<input type="button" class="button button_white" value=" 取消 " onclick="hideMenu();">
											</div>
										</form>
									</div>
								
									<table width="100%" class="sTable2" cellspacing="0" cellpadding="0">
										<thead>
											<tr>
												<td>面试说明</td>
												<td width="95">面试时间</td>
												<td width="50">状态</td>
												<td width="95">创建时间</td>
												<td width="40" align="center">操作</td>
											</tr>
										</thead>
										<tbody>
								
										</tbody>
									</table><?php break;?>
							<?php default: ?>订单异常<?php echo ($ad_ord['state']); ?>;<?php endswitch;?>
						
							<table width="100%" border="0" cellspacing="0" cellpadding="5" class="sTable2" style="margin-top:5px;">
									<tbody>
										<tr>
											<td align="right" width="80">姓名：</td>
											<td colspan="2" style="font-size:14px; font-weight: bold;">
												<a href="javascript:;" onclick="showResume(3270,'智联招聘_赵凯_招聘经理_中文_20190107_1546844066045');">智联招聘_赵凯_招聘经理_中文_20190107_1546844066045</a>
											</td>
											<td>状态：
												<font color="#808080"><?php echo ($status[$info['status']]); ?>..</font>
							
											</td>
										</tr>
										<?php if($info["status"] == 4 or $info["status"] == 5): ?><tr>
										    <td align="right">失败原因：</td>
										    <td colspan="3" style="color:red;"><?php echo ((isset($info["failcontent"]) && ($info["failcontent"] !== ""))?($info["failcontent"]):''); ?></td>
									  	</tr><?php endif; ?>
										<tr>
											<td colspan="4" style="padding:10px;">
												<h4>推荐说明：</h4> <?php echo ($info["rerecontent"]); ?>
											</td>
										</tr>
										<tr>
											<td align="right">目前所在地：</td>
											<td colspan="3"><?php echo ($info["rerecurrentcity"]); ?></td>
										</tr>
										<tr>
											<td align="right">期望薪金：</td>
											<td><?php echo ($info["rereexpectmoney"]); ?></td>
											<td align="right">推荐人：</td>
											<td><?php echo ($info["recommend_name"]); ?></td>
										</tr>
										<tr>
											<td align="right">推荐时间：</td>
											<td><?php echo (date('Y-m-d H:i:s',$info["add_time"])); ?></td>
											<td align="right">负责人：</td>
											<td>管理员</td>
										</tr>
										<?php if($info["status"] != 0): ?><tr>
										    <td align="right">处理人：</td>
										    <td><?php if($info.operator_id): echo M('admin')->where(array('id'=>$info['operator_id']))->getField('username'); endif; ?></td>
										    <td align="right">处理时间：</td>
										    <td><?php echo (date('Y-m-d H:i:s',$info["operator_time"])); ?></td>
									  	</tr>	
									  	<tr>
										    <td align="right">处理备注：</td>
										    <td colspan="3"><?php echo ((isset($info["intacontent"]) && ($info["intacontent"] !== ""))?($info["intacontent"]):''); ?></td>
									  	</tr><?php endif; ?>
							
								</tbody>
							</td>
							</table>
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
	
	$("#act_sure").click(function(){
		var data=$(this).parents("#handleState").find('form').serialize();
		dialog_open($(this),data);
	})
	
//推荐
$('#commentReport').on("click", function(e) {　
	$("#act_sure").attr('data-status',1);
	$("#handleState").css({'left':'88px'});
	$('#mailDiv').show();
	$("#handleState").slideUp();　　
	$("#handleState").slideDown('fast');　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });
 
 
document.onclick = function(e) {　　　　　　　　
     $("#handleFailState").hide();　　　　　　　
     $("#handleState").hide();
}


$("#act_cancel").click(function(){　　　　　　　　
     $("#handleState,#handleFailState").hide();
})

//不推荐 
$('#nocommentReport').on("click", function(e) {
	$("#act_sure").attr('data-status',4);
	$("#handleState").css({'left':'353px'});
	$('#mailDiv').hide();
	$("#handleState").slideUp();　　
	$("#handleState").slideDown('fast');　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });
 // commentFail  推荐失败
 $('#commentFail').on("click", function(e) {
 	$(".interveiw_fail").show();
 	$(".interveiw").hide();
	$("#handleFailStateConfirm").attr('data-status',5);
	$("#handleFailStateConfirm").attr('data-msg','你确认推荐失败吗');
	$("#handleFailState").css({'left':'353px'});
	$('#mailDiv').hide();
	$("#handleFailState").slideUp();　　
	$("#handleFailState").slideDown('fast');　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });  
 
 // createInterview  设置面试
 $('#createInterview').on("click", function(e) {
 	$(".interveiw_fail").hide();
 	$(".interveiw").show();
	$("#handleFailStateConfirm").attr('data-status',2);
	$("#handleFailStateConfirm").attr('data-msg','你确认资料正确吗');
	$("#handleFailState").css({'left':'28px'});
	$('#mailDiv').hide();
	$("#handleFailState").slideUp();　　
	$("#handleFailState").slideDown('fast');　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });
 // 创建面试和推荐失败操作
 $("#handleFailStateConfirm").click(function(){
 	var is_show=$(".interveiw").css('display');
 	var data;
 	if(is_show=='none'){
 		data=$(".interveiw_fail form").serialize();
 	}
 	else{
 		data=$(".interveiw form").serialize();
 		
 	}
 	dialog_open($(this),data);
 })　　　　　
 $('#handleState,#handleFailState').on("click", function(e) {　　　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });　
 //createOffer  创建offer
 $("#createOffer").click(function(){
 	var nums=$(this).attr('data-nums');
 	if(nums>0){
 		$(this).addClass('J_showdialog');
 	}
 	else{
 		$.pinphp.tip({content:"系统未检测到该客户的有效合同，不能创建offer，请联系客户所有者补全合同。", icon:'error'});
 	}
})　　　
 function stopFunc(e) {　　　　　　
     e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;　　　　
 }
 
 //新增跟进
$('#followBtn').click(function(){
	var content=$("#cmfoContent").val();
	var id="<?php echo ($info['id']); ?>";
	var url="<?php echo U('Progress/add');?>";
	var data={content:content,type:3,report_id:id};
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
	$.get("<?php echo U('Progress/index',array('type'=>3,'report_id'=>$info['id']));?>",{p:p},function(msg){
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
function dialog_open(dom_self,con){
	var self = dom_self,
	uri = self.attr('data-uri'),
	status=self.attr('data-status'),
	title = (self.attr('data-title') != undefined) ? self.attr('data-title') : lang.confirm_title,
	data=con+'&status='+status+'&id='+self.attr('data-id');
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