<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<link rel="stylesheet" media="screen" href="/hunter/theme/admin/css/style.css" />
<div class="dialog_content">
	<form  id="importForm" action="<?php echo U('resume/upload');?>" method="post" enctype="multipart/form-data">
				
<body style="background:#fff;padding:10px;">
		<div class="notification msginfo" style="margin-bottom: 10px">请按照EXCEL模板格式进行导入。&nbsp;&nbsp;
			<a href="/uploads/resume_import_template.xls" target="_blank">下载简历EXCEL模板</a>
		</div>
		<div style="padding:15px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td width="80">导入到行业：</td>
						<td>
							<select class="J_cate_industries_select mr10" data-pid="0" data-uri="<?php echo U('resume/ajax_indus_getchilds');?>" data-selected="<?php echo ($spid); ?>"></select>
							<input type="hidden" name="trades" id="J_industries_pid" />
						
						</td>
					</tr>
					<tr>
						<td style="padding-top:10px;">导入到岗位：</td>
						<td style="padding-top:10px;">
							<select class="J_cate_place_select mr10" data-pid="0" data-uri="<?php echo U('resume/ajax_job_getchilds');?>" data-selected="<?php echo ($spid); ?>"></select>
							<input type="hidden" name="fuctions" id="J_pid" />
						</td>
					</tr>
					<tr>
						<td style="padding-top:10px;">&nbsp;</td>
						<td style="padding-top:10px;color:#f00;">提示：如果不选择行业与岗位，系统将自动识别行业与岗位信息，识别率取决于你EXCEL内容的完整性。</td>
					</tr>
					<tr>
						<td style="padding-top:10px;">导入EXCEL：</td>
						<td style="padding-top:10px;">
							<input type="file" name="excelData" id="files" size="15" class="ifile" value="上传"> &nbsp;&nbsp;
					      	<input type="hidden" name="owner_id" value="<?php echo ($admin_id); ?>">
					      	<input type="submit" class="button button_blue" id="execImport" value="导入">
						</td>
					</tr>
	
				</tbody>
				
			</table>
		</div>
		<div id="importState" style="height:188px; width:756px; BORDER-TOP: #ccc 1px solid; OVERFLOW-Y: auto; OVERFLOW-X:hidden;">准备就绪...</div>
<script>
$('.J_cate_place_select').cate_select({top_option:'请选择岗位',field:'J_pid',target_class:'J_cate_place_select'}); //分类联动
$('.J_cate_industries_select').cate_select({top_option:'请选择行业',field:'J_industries_pid',target_class:'J_cate_industries_select'}); //分类联动

</script>	
</body>
</form>
</div>