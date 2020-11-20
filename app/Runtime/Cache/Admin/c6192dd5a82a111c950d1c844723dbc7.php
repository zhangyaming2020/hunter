<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<link rel="stylesheet" href="http://ee.headhunterclub.com/js/plugins/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<link rel="stylesheet" media="screen" href="../../../css/style.css" />
<link href="http://ee.headhunterclub.com/js/plugins/art/skins/black.css" rel="stylesheet" type="text/css" />

<div class="dialog_content" data-control="custom" data-id="detail" data-width="1000" data-title="郭德纲"  data-uri="<?php echo U('custom/detail',array('id'=>$custom_id,'type'=>$type));?>" style="padding:10px; OVERFLOW-Y: auto;">
	<body style="background:#eee; padding:10px;">
		<div class="sTableOptions">
			<div class="pp">
				<a  href="javascript:;" data-uri="<?php echo ($url); ?>" data-title="新增"  data-id="add" data-width="<?php echo ($width); ?>" data-height="" class="no_editor_showdialog button add" id="addDict"><span>新增</span></a>
			</div>
			<div class="notification msginfo" style="margin-bottom: 0; margin-top:10px;">
			<?php echo ($remark); ?>
			</div>
		</div>
		<!--sTableOptions-->
		<div class="sTableWrapper" style="padding:0 10px 10px 10px; OVERFLOW-Y: auto;height:250px;">
			
			
			<ul id="dicts" class="ztree">
				<?php if(in_array($pid,array('4','102','103'))): ?><li>
					<?php echo ($list); ?>
					</li>
				<?php else: ?>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li id="dicts_1" class="top_level0" treenode="">
					<!--<button type="button" hidefocus="true" disabled="" id="dicts_1_switch" title="" class="level0 switch roots_docu" treenode_switch=""></button>
					-->
					<a id="dicts_1_a" class="level0" treenode_a="" onclick="" target="_blank" style="" title="重点客户">
						<button type="button" hidefocus="true" id="dicts_1_ico" title="" treenode_ico="" class="ico_docu" style="width:0px;height:0px;"></button>
						<span id="dicts_1_span" class="dic_name"><?php echo ($val["name"]); ?>
							</span>
						<span class="act" style="display:none;">
						<!--<button type="button" class="settingbtn" title="设为默认" onfocus="this.blur();"></button>
						-->
						<button type="button" data-width="80" class="edit" data-field="name" data-id="<?php echo ($val["id"]); ?>" data-content="<?php echo ($val["name"]); ?>" title="编辑" onfocus="this.blur();"></button>
						
						<button type="button" class="remove J_confirmurl" data-dialog="<?php echo u('dic/detail', array('remark'=>$remark,'id'=>$pid));?>" data-id="detail" data-uri="<?php echo u('dic/delete', array('id'=>$val['id']));?>" data-acttype="ajax" data-msg="确定选择该配置吗">
							
							
						</button>
						</span>
					</a>
				</li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
			</ul>
		</div>
	
		<div style="display: none; position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; cursor: move; opacity: 0; background: rgb(255, 255, 255);"></div>
	</body>
</div>

<script src="/theme/admin/js/jquery/plugins/jquery.treetable.js"></script>

<script>
var editor;
$(function(){
	$('.level0').hover(function(){
		$(this).addClass('curSelectedNode').parents('.top_level0').siblings('.top_level0').find('.level0').removeClass('curSelectedNode');
		$(this).find('.act').show().parents('.top_level0').siblings('.top_level0').find('.act').hide();
	},function(){
		$(this).removeClass('curSelectedNode');
		$(this).find('.act').hide()
	})
	$('tr').hover(function(){
		var nums=$(this).attr('node-nums');
		if(nums){
			$('#node-'+nums).find('.act').show().siblings().find('.act').hide();
		}
		
	},function(){
		$(this).find('.act').hide()
	})
	$('.level0').click(function(){
		$(this).addClass('curSelectedNode').parents('.top_level0').siblings('.top_level0').find('.level0').removeClass('curSelectedNode');
		$(this).find('.act').show().parents('.top_level0').siblings('.top_level0').find('.act').hide();
		$(this).off('hover');
	})
	//修改
		$(document).delegate('.edit', 'click', function() {
			var s_val   = $(this).attr('data-content'),
			s_name  = $(this).attr('data-field'),
			s_id    = $(this).attr('data-id'),
			width   = $(this).attr('data-width');
			$('<input type="text" class="lt_input_text"  value="'+s_val+'" />').width(width).focusout(function(){
				$(this).prev('span').show().text($(this).val());
				if($(this).val() != s_val) {
					$.getJSON("<?php echo U('dic/ajax_edit');?>", {id:s_id, field:s_name, val:$(this).val()}, function(result){
						if(result.status == 0) {
							$.pinphp.tip({content:result.msg, icon:'error'});
							$('span[data-field="'+s_name+'"][data-id="'+s_id+'"]').text(s_val);
							return;
						}
					});
				}
				$(this).remove();
			}).insertAfter($(this).parents('.level0').find('.dic_name')).focus().select();
			$(this).parents('.level0').find('.dic_name').hide();
			return false;
		});
		
		$("#J_cate_tree").treeTable({indent:20,treeColumn:2});
		
		//修改
		$(document).delegate('span[data-tdtype="edit"]', 'click', function() {
			var s_val   = $(this).text(),
			s_name  = $(this).attr('data-field'),
			s_id    = $(this).attr('data-id'),
			width   = $(this).width();
			$('<input type="text" class="lt_input_text" value="'+s_val+'" />').width(width).focusout(function(){
				$(this).prev('span').show().text($(this).val());
				if($(this).val() != s_val) {
					$.getJSON("<?php echo ($ajax_url); ?>", {id:s_id, field:s_name, val:$(this).val()}, function(result){
						if(result.status == 0) {
							$.pinphp.tip({content:result.msg, icon:'error'});
							$('span[data-field="'+s_name+'"][data-id="'+s_id+'"]').text(s_val);
							return;
						}
					});
				}
				$(this).remove();
			}).insertAfter($(this)).focus().select();
			$(this).hide();
			return false;
		});
})
</script>