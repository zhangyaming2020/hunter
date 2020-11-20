<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content">
    <form id="info_form" name="info_form" action="<?php echo U('custom/share');?>" method="post">
		<div class="notification msginfo" style="margin-bottom: 0">
			说明：共享后对方可查看本客户资料，在本客户基础上创建项目，查看本客户所有项目，发送推荐报告等。
		</div>
		<div class="sTableWrapper" style="padding:10px 0 0 10px;height:350px; OVERFLOW-Y: auto;">
			<ul id="users" class="ztree">
				<input type="hidden"  name="share_ids" value="<?php echo ($share_ids); ?>">	
				<input type="hidden"  name="id" value="<?php echo ($custom_id); ?>">	
				<li id="users_17" class="level0" treenode="">
					<?php if(is_array($admin)): $i = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><input type="checkbox" <?php if(in_array($val['id'],explode(',',$share_ids))): ?>checked<?php endif; ?> <?php if($val['id'] == $_SESSION['admin']['id']): ?>disabled<?php endif; ?>  name="ids" value="<?php echo ($val["id"]); ?>">	
				&nbsp;&nbsp;<?php echo ($val["username"]); ?><br /><?php endforeach; endif; else: echo "" ;endif; ?>
				</li>
			</ul>
		</div>
    </form>
</div>

<script>
$(function(){
	$("input[type=checkbox]").click(function(){
		var ids='';
		$("input[type=checkbox]:checked").each(function(){
			ids +=$(this).val()+',';
		})
		$("input[name=share_ids]").val(ids.substr(0,ids.length-1));
	})
    $.formValidator.initConfig({formid:"info_form",autotip:true});

    $('#info_form').ajaxForm({success:complate,dataType:'json'});
    function complate(result){
        if(result.status == 1){
            $.dialog.get(result.dialog).close();
            $.pinphp.tip({content:result.msg});
          //  window.location.reload();
        } else {
            $.pinphp.tip({content:result.msg, icon:'alert'});
        }
    }
});
</script>
</div>