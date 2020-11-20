<?php if (!defined('THINK_PATH')) exit();?><style>
	.sTable2 thead tr td,
	.sTable2 tbody tr td {
		padding: 3px;
	}
	.progress_pagination .current{color: #069;}
	.progress_pagination a{margin-right:5px;}
</style>
<table width="100%" class="sTable2" id="followlist" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<td width="150">跟进日期</td>
			<td width="50">跟进人</td>
			<td>跟进内容</td>
		</tr>
	</thead>
	<tbody class="progress_content">
		<?php if($list): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
			<td><?php echo (date('Y-m-d H:i:s',$val["add_time"])); ?></td>
			<td><?php echo M('admin')->where(array('id'=>$val['creator_id']))->getField('username');?></td>
			<td style="word-break:break-all;"><?php echo ($val["content"]); ?></td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<?php else: ?>
		<tr>
			<td colspan="3">暂无跟进记录</td>
		</tr><?php endif; ?>
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
				<div class="progress_pagination">
					<?php echo ($page); ?>
				</div>
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