<?php if (!defined('THINK_PATH')) exit();?><style>
	.link a{margin-left:6px;}
	.current{color:red;}
</style>
<div class="messagelist">
	<ul>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="current" data-id="<?php echo ($val["id"]); ?>" data-name="<?php echo ($val["cutaname"]); ?>">
			<a href="javascript:;" onclick="onSelectCustomer(163,'不能反躬');"><?php echo ($val["cutaname"]); ?></a>
		</li><?php endforeach; endif; else: echo "" ;endif; ?>
		


	</ul>
	<div class="link">
		<?php echo ($page); ?>
	</div>
</div>