<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml" lang="zh">

	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=9">
		<meta http-equiv="X-UA-Compatible" content="chrome=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->
		<style>
			body {
				font-size: 12px;
			}
			
			p {
				line-height: 20px;
				padding: 0px;
				margin: 0px;
			}
			
			.messagelist h4 {
				font-size: 11px;
				color: #333;
				font-weight: normal;
				padding: 8px 10px;
				border-bottom: 1px solid #ccc;
				text-transform: uppercase;
			}
			
			.messagelist .link {
				padding: 8px 10px;
				background: #eee;
				font-size: 11px;
				border-top: 1px solid #ccc;
			}
			
			.messagelist ul {
				list-style: none;
				padding: 0px;
				margin: 0px;
			}
			
			.messagelist ul li {
				display: block;
				border-bottom: 1px dotted #ccc;
				padding: 5px 10px;
			}
			
			.messagelist ul li:last-child {
				border-bottom: 0;
			}
			
			.messagelist ul li.current {
				background: #fff;
				color: #333;
			}
			
			.messagelist ul li.current a {
				color: #6385ae;
				font-weight: bold;
			}
			
			.messagelist ul li a {
				display: block;
				color: #333;
			}
			
			.messagelist ul li a:hover {
				text-decoration: none;
			}
			
			.messagelist ul li span {
				color: #666;
				display: block;
				font-size: 11px;
			}
			
			.messagelist ul li small {
				font-size: 11px;
				color: #666;
			}
			
			.messagelist ul li:hover {
				background: #e8f3fe;
			}
		</style>
		<script>
			function showMenu(btnId, divId) {
				var btn = $(btnId);
				var offset = btn.offset();
				$(divId).css({
					left: offset.left + "px",
					top: offset.top + btn.outerHeight() + "px"
				}).slideDown("fast");
				$("body").bind("mousedown", onBodyDown);
			}

			function hideMenu() {
				$("#applyShowResumeDiv").fadeOut("fast");
				$("body").unbind("mousedown", onBodyDown);
			}

			function onBodyDown(event) {
				if($(event.target).parents("#applyShowResumeDiv").length == 0) {
					hideMenu();
				}
			}

			function applyShowResume() {
				showMenu("#applyShowResumeBtn", "#applyShowResumeDiv");
			}

			function applyShowResumeConfirm() {
				var applyResumeReason = $("#applyResumeReason");
				var applyProjectId = $("#applyProjectId");
				var applyProjectName = $("#applyProjectName");
				if(applyProjectId.val() == "") {
					alert("请选择相关项目.");
					return false;
				}
				var _confirm = $.dialog.confirm("你确认要申请查看此简历的联系方式吗？", function() {
					$.post("../applyContact/3270?rand=" + new Date(), {
						"applyResumeReason": applyResumeReason.val(),
						"applyProjectId": applyProjectId.val(),
						"applyProjectName": applyProjectName.val()
					}, function(data) {
						if(data == "success") {
							$.dialog.alert("申请发送成功，等待所有者确认！");
						} else if(data == "ok") {
							window.location.reload();
						} else {
							$.dialog.alert(data);
						}
					});
				});
			}

			function modifyOwnerUser() {
				var ownerUserId = $("#selectOwnerUserList").val();
				var _confirm = $.dialog.confirm("你确认此操作吗？", function() {
					$.get("../modifyOwnerUser/3270?rand=" + new Date(), {
						"ownerUserId": ownerUserId
					}, function(result) {
						if(result == 'success') {
							$.dialog.alert("恭喜您，操作成功！", function() {
								window.location.reload();
							});
						} else {
							$.dialog.alert(result);
							_confirm.close();
						}
					});
				});
			}
			$(function() {
				$('#userHelp').tipsy({
					html: true,
					fade: true,
					gravity: 'se'
				});
				$("#createFavorite").click(function() {
					$.dialog.prompt('请输入收藏夹名称', function(data) {
						if(data == "") {
							$.dialog.alert("请输入您新增收藏夹名称");
							return false;
						}
						$.post("/resume/favorite/save?rand=" + new Date(), {
							id: 0,
							fName: data
						}, function(result) {
							if(result == "success") {
								showFavoriteDiv();
							} else {
								$.dialog.alert(result);
							}
						});
					});
				});
				$("#applyProjectName").click(function() {
					var detailDialog = $.dialog.open("/projectResume/selectProject/3270?handle=apply", {
						title: "选择项目",
						id: "resume_select_project_3270",
						lock: false,
						width: '880px',
						height: '500px'
					});
					return false;
				});
			});
		</script>
	</head>

	<body style="background:#fff; padding:10px;">
		<div class="" style="display: none; position: absolute;">
			<div class="aui_outer">
				<table class="aui_border">
					<tbody>
						<tr>
							<td class="aui_nw"></td>
							<td class="aui_n"></td>
							<td class="aui_ne"></td>
						</tr>
						<tr>
							<td class="aui_w"></td>
							<td class="aui_c">
								<div class="aui_inner">
									<table class="aui_dialog">
										<tbody>
											<tr>
												<td colspan="2" class="aui_header">
													<div class="aui_titleBar">
														<div class="aui_title" style="cursor: move;"></div>
														<a class="aui_min" href="javascript:/*artMin*/;" style="display: none;">-</a>
														<a class="aui_max" href="javascript:/*artMax*/;" style="display: none;">+</a>
														<a class="aui_reset" href="javascript:/*artReset*/;" style="display:none">-</a>
														<a class="aui_close" href="javascript:/*artDialog*/;">×</a>
													</div>
												</td>
											</tr>
											<tr>
												<td class="aui_icon" style="display: none;">
													<div class="aui_iconBg" style="background: none;"></div>
												</td>
												<td class="aui_main" style="width: auto; height: auto;">
													<div class="aui_content" style="padding: 20px 25px;"></div>
												</td>
											</tr>
											<tr>
												<td colspan="2" class="aui_footer">
													<div class="aui_buttons" style="display: none;"></div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
							<td class="aui_e"></td>
						</tr>
						<tr>
							<td class="aui_sw"></td>
							<td class="aui_s"></td>
							<td class="aui_se" style="cursor: se-resize;"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<h1>智联招聘_赵凯_招聘经理_中文_20190107_1546844066044</h1>

		<p>
			编号：3270&nbsp;&nbsp;性别：男&nbsp;&nbsp;学历：硕士&nbsp;&nbsp;出生年：
		</p>

		<p>意向地区：深圳</p>

		<p>联系方式：13809860862 &nbsp;&nbsp;邮箱：zkai128@163.com&nbsp;&nbsp;
			<a href="./uploads/resume/<?php echo ($info["resuname"]); ?>" target="_blank" download>下载原始简历</a>

		</p>

		<p>创建者：管理员&nbsp;&nbsp;创建时间：2019-01-25 15:19&nbsp;&nbsp;&nbsp;&nbsp;所有者：

		</p>
		<p>当前所在公司：</p>
		<p style="margin:8px 0;">
			<input type="button" id="setFavoriteBtn" value="加入简历收藏夹" onclick="showFavoriteDivResume();">
			<!-- 
&nbsp;<input type="button" id="exportPDF" dataId="3270" exportType="PDF" value="导出PDF" />
&nbsp;<input type="button" id="exportWord" dataId="3270" exportType="Word" value="导出Word" />
 -->

			&nbsp;
			<select id="selectOwnerUserList" style="padding:0;">

				<?php if(is_array($admin)): $i = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" data-id="<?php echo ($val["id"]); ?>" <?php if($info['owner_id'] == $val['id']): ?>selected="selected"<?php endif; ?>><?php echo ($val["username"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
			&nbsp;<input type="button" value="更改所有者" class="iconlink2" onclick="modifyOwnerUser();">

		</p>
		<div id="resumeFavoriteDiv" style="display:none; width:auto; min-width:280px; position: absolute; background: #f7f7f7; BORDER: #ccc 1px solid; z-index: 300;">
			<div style="padding:10px;">
				<input type="hidden" id="favoriteResuId" value="3270">
				<input type="text" name="keyword" id="resumeFavoriteKeyword" value="" class="sf" style="width:120px;">&nbsp;<input type="button" id="searchFavorite" class="button button_white" value=" 查询 " onclick="searchFavorite()">&nbsp;<input type="button" id="createFavorite" class="button button_white" value="创建收藏夹">
			</div>
			<div id="resumeFavoriteResult"></div>
		</div>
		<p></p>
		<p style="margin-top:10px; padding-top:10px; border-top: 1px solid #ccc; word-spacing: break-word; word-break: normal; word-break:break-all; font-size:14px; line-height: 23px;">

		</p>
		<div class="resume-preview clearfix">
			<?php echo code2html($info['info']);?>
		</div>

		<p></p>

		<div style="display: none; position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; cursor: move; opacity: 0; background: rgb(255, 255, 255);"></div>
	</body>

</html>