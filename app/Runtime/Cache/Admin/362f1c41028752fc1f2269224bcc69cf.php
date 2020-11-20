<?php if (!defined('THINK_PATH')) exit();?><div class="content" style="display: block;">
	
	<?php if(is_array($left_menu)): $i = 0; $__LIST__ = $left_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><h3 class="f14"><span class="J_switchs cu on" title="<?php echo L('expand_or_contract');?>"></span><?php echo ($val["name"]); ?></h3>
    <ul class="leftmenu" style="display: block;">
        <?php if(is_array($val['sub'])): $i = 0; $__LIST__ = $val['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sval): $mod = ($i % 2 );++$i; if($sval['id'] == 455): ?><li class="sub_menu  current"><a class="diao" href="<?php echo U('Index/index');?>" hidefocus="true"><?php echo ($sval["name"]); ?></a></li>
        <?php else: ?>
    	<li class="sub_menu"><a class="diao" href="javascript:;" data-uri="<?php echo U($sval['controller_name'].'/'.$sval['action_name'], array('menuid'=>$sval['id'])); echo ($sval["data"]); ?>" data-id="<?php echo ($sval["id"]); ?>" hidefocus="true"><?php echo ($sval["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
       
    </ul><?php endforeach; endif; else: echo "" ;endif; ?>
</div>