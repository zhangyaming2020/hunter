<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content">
	<div id="tabs">
		<form id="info_form" name="info_form" action="<?php echo U('report/add');?>" method="post">
			<input type="hidden" name="resume_id" id="resume_id" value="0">
			<input type="hidden" name="candidate_id" id="candidate_id" value="0">
			<input type="hidden" name="project_id" id="projectId" value="<?php echo ($project_id); ?>">
			<input type="hidden" name="custom_id" value="<?php echo ($custom_id); ?>">
			<input type="hidden" name="accessoryFile">
			
			<div id="tabs-1">
				<div class="form_default" style="padding:0; height:350px; BORDER: #ccc 1px solid;">
					<p>
						<label for="cutaName">候选人</label>
						<input type="button" id="selectResumeBtn" name="selectResumeBtn" class="button button_white" value="选择候选人...">
					</p>
					<div id="selectResume" class="dropbox" style="display:none; position: absolute; background: #f7f7f7; width:auto; min-width:220px;top:80px;left:128px; height:auto; max-height:230px; OVERFLOW-Y: auto; BORDER: #ccc 1px solid;">
			
						<div class="messagelist">
							<ul>
								<?php if(is_array($resume)): $i = 0; $__LIST__ = $resume;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="current" data-candidate="<?php echo ($val["id"]); ?>" data-id="<?php echo ($val["resume_id"]); ?>" data-name="<?php echo ($val["resuname"]); ?>">
									<a href="javascript:;">&nbsp;&nbsp;<?php echo ((isset($val["resuname"]) && ($val["resuname"] !== ""))?($val["resuname"]):'未知'); ?>&nbsp;&nbsp;
										<font color="#ff0000">(已推荐)</font>
									</a>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
								
			
							</ul>
							<div class="link">
								<form id="8631eb0af207fe4e15ef8eae13730966"><input type="hidden" name="page" id="page-8631eb0af207fe4e15ef8eae13730966" value="1"></form>
							</div>
						</div>
					</div>
					<p></p>
					<p>
						<label for="cutaTelephone">目前所在地</label>
						<input type="text" name="rereCurrentCity" id="rereCurrentCity" value="" class="sf" style="width:80px;"> 期望薪金：
						<input type="text" name="rereExpectMoney" id="rereExpectMoney" value="" class="sf" style="width:80px;">
					</p>
					<p>
						<label for="location">推荐说明</label>
						<textarea name="rereContent" id="rereContent" class="mf" style="width:333px; height:120px;"></textarea>
					</p>
					<p>
						<label for="cutaTelephone">报告附件</label>
						<button type="button" id="file" style="margin-left:0px !important;">上传附件</button>
						<span id="accessoryList" style="margin-left:10px;"></span>
					</p>
				</div>
			
			</div>
		</form>
	</div>
</div>
<form style="display:none">
        <input type="file" id="img" name="file">
</form>

<script>
	/*
var check_name_url = "<?php echo U('custom/ajax_check_name');?>";
$.formValidator.initConfig({formid:"info_form",autotip:true});
$('#selectResumeBtn').formValidator({onshow:lang.please_input+"请选择候选人",onfocus:lang.please_input+"请选择候选人"}).inputValidator({min:1,onerror:lang.please_input+"请选择候选人"});
$('#rereExpectMoney').formValidator({onshow:lang.please_input+"期望薪资",onfocus:lang.please_input+"期望薪资"}).inputValidator({min:1,onerror:lang.please_input+"期望薪资"});
$("#rereContent").formValidator({onshow:lang.please_input+"推荐说明",onfocus:lang.please_input+"推荐说明"}).inputValidator({min:1,onerror:lang.please_input+"推荐说明"});

$(function(){
	validate_form($('#sid').val());
});
function validate_form(store_id){
	
	$('#info_form').ajaxForm({success:complate,dataType:'json'});
	function complate(result){
		if(result.status == 1){
			$.dialog.get(result.dialog).close();
			$.pinphp.tip({content:result.msg});
			window.location.reload();
		} else {
			$.pinphp.tip({content:result.msg, icon:'alert'});
		}
	}
}*/
//选择候选人
$(".messagelist li").click(function(){
	var name=$(this).attr('data-name');
	var id=$(this).attr('data-id');
	var candidate=$(this).attr('data-candidate');
	$('#selectResumeBtn').val(name);
	$('#candidate_id').val(candidate);
	$('#resume_id').val(id);
	$("#selectResume").hide();
	$('#selectResumeBtn').parent().find('#selectResumeBtnTip').hide();
})



document.onclick = function(e) {　　　　　　　　
     $("#selectResume").hide();
}
$('#selectResumeBtn').on("click", function(e) {　
	if($("#selectResume").css("display") == "none") {　　　　　　　　　　
         $("#selectResume").slideDown();　　　　　　　　
     } else {　　　　　　　　　　
         $("#selectResume").hide();　　　　　　　　
     }　　　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });
 　　　　　　
$('#selectResume').on("click", function(e) {　　　　　　　　
     e = e || event;
     stopFunc(e);　　　　　　
 });　　　　
 function stopFunc(e) {　　　　　　
     e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;　　　　
 }
 //上传
$('#file').click(function(){
	$('input[name=file]').click();
})

 $("input[name=file]").change(function(){
    var fd = new FormData();
    fd.append("img", $("input[name=file]").get(0).files[0]);
		$.ajax({
			url: "<?php echo U('report/attachment');?>",
			type: "POST",
			processData: false,
			contentType: false,
			data: fd,
			success: function(result) { 
				alert(result['msg']);
				if(result['status']==1){
					$('#accessoryList').html(result['data']);
					$("input[name=accessoryFile]").val(result['data']);
					
				}
			}
		});
})
</script>