<?php if (!defined('THINK_PATH')) exit();?><table width="100%" class="sTable2" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<td><input type="checkbox" name="checkAll1" id="checkAll1" value="1" onclick="checkAll(this);"></td>
			<td width="40" style="min-width:40px;">编号</td>
			<td width="*">姓名</td>
			<td width="40">性别</td>
			<td width="40">学历</td>
			<td width="80" align="center">出生年份</td>
			<td width="80" align="center">当前公司</td>
			<td width="60">所有者</td>
			<td width="60">推荐人</td>
			<td width="140">推荐时间</td>
			<td width="140">操作</td>
		</tr>
	</thead>
	<tbody>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr class="even">
			<td align="center"><input type="checkbox" name="resuId" value="3269"></td>
			<td>3269</td>
			<td>
				<a href="javascript:;" style="color:green;"  class="J_showdialog"  data-uri="<?php echo U('resume/detail', array('id'=>$val['resume_id']));?>" data-title="<?php echo ($val["resuname"]); ?>" data-id="detail" data-width="1200" data-height="500"><?php echo ((isset($val["resuname"]) && ($val["resuname"] !== ""))?($val["resuname"]):'未知'); ?><span style="color:#ff0000">(已出报告)</span></a>
				<!--<p style="color:#777;">nih （管理员 2019-01-31 09:50）</p>-->
			</td>
			<td>女</td>
			<td><?php echo ($val["educations"]); ?></td>
			<td></td>
			<td></td>
			<td>总经理</td>
			<td>管理员</td>
			<td>2019-01-28 15:20:50</td>
			<td>
				<select onchange="changetype('493',this)" origin="78">

					<option value="18">意向强烈</option>

					<option value="19">一般1</option>

					<option value="78" selected="">待联系</option>

					<option value="79">暂无意向</option>

					<option value="2794">有问题</option>

					<option value="2862">意向不明</option>

					<option value="2896">abc</option>

				</select>
				&nbsp;
				<a href="javascript:;" onclick="del('493')">删除</a>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
</table>
<br clear="all">
<div class="ajax_pagination">
	<?php echo ($page); ?>
</div>