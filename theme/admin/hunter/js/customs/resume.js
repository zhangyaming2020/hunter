function searchFavorite(){
	$.post("/resume/favorite/list?rand=" + new Date(), {keyword:$("#resumeFavoriteKeyword").val(), resuId:$("#favoriteResuId").val()}, function(data){
		$("#resumeFavoriteResult").html(data);
	});
}
function showFavoriteDiv(){
	searchFavorite();
	var offset = $("#resumeFavorite").offset();
	$("#resumeFavoriteDiv").css({left:offset.left-221 + "px", top:20 + $("#resumeFavorite").outerHeight() + "px"}).slideDown("fast");
	$("body").bind("mousedown", onBodyDownFavoriteDiv);
}

function showFavoriteDivResume(){
	searchFavorite();
	var offset = $("#setFavoriteBtn").offset();
	$("#resumeFavoriteDiv").css({left:offset.left + "px", top:offset.top + $("#setFavoriteBtn").outerHeight() + "px"}).slideDown("fast");
	$("body").bind("mousedown", onBodyDownFavoriteDiv);
}

function hideFavoriteDiv() {
	$("#resumeFavoriteDiv").fadeOut("fast");
	$("body").unbind("mousedown", onBodyDownFavoriteDiv);
}

function editFavorite(id,rName){
	$.dialog.prompt('请输入收藏夹名称', function(data){
		if(data==""){
			$.dialog.alert("请输入收藏夹名称");
			return false;
		}
		$.post("/resume/favorite/save?rand=" + new Date(), {id:id, fName:data}, function(result){
			if(result=="success"){
				showFavoriteDiv();
			}else{
				$.dialog.alert(result);
			}
		});
	}, rName);
}

function deleteFavorite(id){
	if(window.confirm("您确定要删除此收藏夹吗？\r\r\n同时会删除此收藏夹下所有数据，且不可恢复。")){
		$.post("/resume/favorite/delete?rand=" + new Date(), {"id":id}, function(data){
			if(data=="success"){
				searchFavorite();
			}else{
				alert(data);
			}
		});
	}
	return false;
}

function setFavorite(refaId, resuId){
	$.post("/resume/favorite/addFavorite?rand=" + new Date(), {"refaId":refaId, "resuId":resuId}, function(result){
		if(result=="success"){
			$.dialog.alert("加入收藏夹成功！");
			hideFavoriteDiv();
		}else{
			$.dialog.alert(result);
		}
	});
}

function deleteFavoriteDate(refdId){
	if(window.confirm("您确定要删除此收藏夹吗？")){
		$.post("/resume/favorite/deleteFavoriteDate?rand=" + new Date(), {"refdId":refdId}, function(result){
			if(result=="success"){
				$.dialog.alert("删除成功！");
				hideFavoriteDiv();
				favoriteListPage();
			}else{
				$.dialog.alert(result);
			}
		});
	}
}

function selectFavorite(favoriteId){
	$("#favoriteId").val(favoriteId);
	$("#resumeType").val("favorite");
	$("#resumeSearchKeyword").val("姓名");
	$("#searchFromBtn").click();
	hideFavoriteDiv();
	$("#resumeTabs li").removeClass("current");
	$("#resumeFavorite").addClass("current");
}

function onBodyDownFavoriteDiv(event) {
	if (!(event.target.id == "resumeFavorite" || event.target.id == "setFavoriteBtn" || event.target.id == "resumeFavoriteDiv" || $(event.target).parents("#resumeFavoriteDiv").length>0)) {
		hideFavoriteDiv();
	}
}
function getContextPath() {
	var pathName = document.location.pathname;
	var index = pathName.substr(1).indexOf("/");
	var result = pathName.substr(0,index+1);
	if(result!=""){
		result = "../../";
	}
	return result;
}
function checkAll(obj, toObjName){
	if(obj.checked){
		$("input[name="+ toObjName +"]").attr("checked",true);
	}else{
		$("input[name="+ toObjName +"]").attr("checked",false);
	}
}


function myApplyListPage(){
	$('.maincontent').showLoading();
	var data = $("#searchFrom").formToArray();
	$.post("/resume/apply/myapplylistajax?rand=" + new Date(), data, function(result){
		$("#listpage").html(result);
		$('.maincontent').hideLoading();
	});
}

function favoriteListPage(){
	$("#apply_no_show").hide();
	$('.maincontent').showLoading();
	var data = $("#searchFrom").formToArray();
	$.post("/resume/favorite/resumeList?rand=" + new Date(), data, function(result){
		$("#listpage").html(result);
		$('.maincontent').hideLoading();
	});
}

function applyListPage(){
	$('.maincontent').showLoading();
	var data = $("#searchFrom").formToArray();
	$.post("/resume/apply/applylistajax?rand=" + new Date(), data, function(result){
		$("#listpage").html(result);
		$('.maincontent').hideLoading();
	});
}

$("#resumeSearchKeyword").keydown(function(event){
	if(event.keyCode==13){
		var searchType = $("#resumeType").val();
		if(searchType=="10000000000000"){
			myApplyListPage();
		}else if(searchType=="10000000000001"){
			applyListPage();
		}else if(searchType=="favorite"){
			favoriteListPage();
		}else{
			listpage();
		}
		return false;
	}
});
$("#searchFromBtn").click(function(){
	var searchType = $("#resumeType").val();
	if(searchType=="10000000000000"){
		myApplyListPage();
	}else if(searchType=="10000000000001"){
		applyListPage();
	}else if(searchType=="favorite"){
		favoriteListPage();
	}else{
		listpage();
	}
});
$("#resumeTabs li").click(function(){
	if($(this).attr("id")!="resumeFavorite"){
		$(this).addClass("current").siblings().removeClass("current");
		var dataId = $(this).attr("dataId");
		if(document.getElementById("projectResumeSearchFrom")!=null && typeof(document.getElementById("projectResumeSearchFrom"))=="object"){
			$("#projectResumeSearchFrom")[0].reset();
		}else{
			$("#searchFrom")[0].reset();
		}
		if(dataId>1000000000000){
			$("#resumeType").val(dataId);
			if(dataId==10000000000000){
				myApplyListPage();
				$("#apply_no_show").hide();
				$("#apply_show_name").text("姓名");
			}else{
				applyListPage();
				$("#apply_no_show").hide();
				$("#apply_show_name").text("姓名");
			}
		}else{
			$("#apply_show_name").text("关键字");
			$("#apply_no_show").show();
			$("#prtype").val(dataId);
			$("#resumeType").val(dataId);
			listpage();
			if($(this).attr("dataId")=="-1"){
				$(".recover").css("display","");
				$(".delete").css("display","none");
			}else{
				$(".recover").css("display","none");
				$(".delete").css("display","");
			}
		}
	}else{
		showFavoriteDiv();
	}
});
$("#createFavorite").click(function(){
	$.dialog.prompt('请输入收藏夹名称', function(data){
		if(data==""){
			$.dialog.alert("请输入您新增收藏夹名称");
			return false;
		}
		$.post("/resume/favorite/save?rand=" + new Date(), {id:0, fName:data}, function(result){
			if(result=="success"){
				showFavoriteDiv();
			}else{
				$.dialog.alert(result);
			}
		});
	});
});
$("#uploadButton").click(function(){
	var pjId=$(this).attr("dataId");
	var mydialog = $.dialog.open("/resume/upload?pjId="+pjId,{
		title :	"上传简历",
		width : '700px',
		height:	'550px',
		id	  : 'resumeupload',
		lock  :	false
	});
});

$("#exportResumes").click(function(){
	var mydialog = $.dialog.open("/resume/export/input?rand=" + new Date(),{
		title :	"导出简历",
		width : '380px',
		height:	'450px',
		id	  : 'resumeExport',
		lock  :	false
	});
});
$("#importResumes").click(function(){
	var mydialog = $.dialog.open("/resume/import/importResume",{
		title:	"导入简历",
		width:	'780px',
		height:	'450px',
		cancelVal: '关闭',
		cancel: function(){
			listpage();
		}
	});
});

$("#addNewButton").click(function(){
	var pjId=$(this).attr("dataId");
	editForm(0,"新增简历",pjId,0,"");
});
$("#addNewCopyButton").click(function(){
	var pjId=$(this).attr("dataId");
	var mydialog = $.dialog.open("/resume/parsercontent?rand=" + new Date(),{
		id		: 	'resumecopy',
		title	:	"复制解析简历",
		width	:	'900px',
		height	:	'450px',
		lock	:	false,
		button	: [{
			name:"提交简历",
			callback: function () {
				var iframe = this.iframe.contentWindow;
		    	if (!iframe.document.body) {
		        	alert("对不起，数据还在加载中.");
		        	return false;
		        };
		        var frm = iframe.document.getElementById("frm");
	        	if(!iframe.chkContent()){
	        		return false;
	        	}
	        	var data = $(frm).formToArray();
        		$.post("/resume/parsercontent?pjId="+pjId+"&rand="+new Date(), data, function(result){
        			if(result=='success'){
        				$.dialog.alert("恭喜您，操作成功！", function(){
        					mydialog.close();
        					$(".reloadButton").click();
        				});
        			}else if(result.indexOf('success')!=-1){
        				mydialog.close();
        				$(".reloadButton").click();
        				//edit(result.substr(7),0);
        				editForm(result.substr(7),'修改简历',pjId,0,'');
        			}else{
        				$.dialog.alert(result);
        			}
        		})
				return false;
			}
		}
		]
	})
});
$("#projectResumeSearchButton").click(function(){
	var pjId=$(this).attr("dataId");
	$.dialog.open("/projectResume/selectlist/"+pjId,{
		title	:	"选择候选人",
		width	:	'960px',
		height	:	'550px',
		lock	:	true,
		close: function(){
				$(".reloadButton").click();
			}
	})
});
function changestate(id,isRealDelete){
	var msg="你确定要执行本操作吗？";
	if(isRealDelete){
		msg="此操作不可恢复，你确定要继续吗？";
	}
	$.dialog.confirm(msg,function(){
		$.get("/resume/changestate/"+id+"?rand=" + new Date(),function(result){
			if(result=='success'){
				$.dialog.alert("恭喜您，操作成功！", function(){
					//listpage();
					//mydialog.close();
					var pageId = $(".pagination").find("form").attr("id");
					var currPage = $(".pagination").find(".current").text();
					var script = "ajaxPage" + pageId + "("+ currPage +")";
					eval(script);
				});
			}else{
				$.dialog.alert(result);
			}
		});
	});
}
function perfect(id,hasErr,swfId){
	editForm(id,"完善简历","0",hasErr,swfId);
}
function edit(id,hasErr,freshWindow){
	editForm(id,"修改简历","",hasErr,"",freshWindow);
}

function pjshow(id,resumeId,title,projectId){
	var winWidth = 850;
	if($(top.window).width()>1024){
		winWidth = 1200;
	}
	winWidth = winWidth + "px";
	var keywordencode = $("#keywordencode");
	var url = "/resume/show/"+resumeId;
	if(keywordencode && keywordencode.val()!=""){
		url += "?keywordencode=" + keywordencode.val();
	}
	var mydialog = $.dialog.open(url,{
		title :	title+'简历详情',
		width : winWidth,
		height:	'500px',
		id	:	"resume_show_" + resumeId,
		lock  :	false,
		cancelVal: '关闭',
		cancel: true,
		button:[{
			name: '生成报告',
			callback: function () {
                var mydialog = $.dialog.open("/project/report/input/0?projectId="+ projectId +"&prreId=" + id,{
					title:	"生成报告",
					width:	'600px',
					height:	'380px',
					ok: function () {
						var iframe = this.iframe.contentWindow;
				    	if (!iframe.document.body) {
				        	alert("对不起，数据还在加载中.");
				        	return false;
				        };
				        var frm = iframe.document.getElementById("frm");
				        
				        if(frm.rerePrreId.value=="0"){
				        	alert("请选择候选人...");
				        	frm.selectResumeBtn.click();
				        	return false;
				        }
				        
				        if(frm.rereCurrentCity.value==""){
				        	alert("请填写候选人目前所有地");
				        	frm.rereCurrentCity.focus();
				        	return false;
				        }
				        
			        	if(frm.rereAccessory.value==""){
			        		alert("请上传原始报告附件.");
			        		return false;
			        	}
			        	
			        	var _confirm = $.dialog.confirm('你确认资料正确吗？', function(){
				        	var data = $(frm).formToArray();
			        		$.post("../report/save?rand="+new Date(), data, function(result){
			        			if(result=='success'){
			        				$.dialog.alert("恭喜您，操作成功！", function(){
			        					mydialog.close();
			        					_confirm.close();
			        				});
			        			}else{
			        				$.dialog.alert(result);
			        				_confirm.close();
			        			}
			        		})
					    	return false;
						});
						return false;
					},
					cancelVal: '关闭',
					cancel: true
				});
				return false;
            }
		}
		]
	});
}

function pjshowEdit(id,resumeId,title,projectId){
	var winWidth = 850;
	if($(top.window).width()>1024){
		winWidth = 1200;
	}
	winWidth = winWidth + "px";
	var keywordencode = $("#keywordencode");
	var url = "/resume/show/"+resumeId;
	if(keywordencode && keywordencode.val()!=""){
		url += "?keywordencode=" + keywordencode.val();
	}
	var mydialog = $.dialog.open(url,{
		title :	title+'简历详情',
		width : winWidth,
		height:	'500px',
		id	:	"resume_show_" + resumeId,
		lock  :	false,
		fixed: true,
		cancelVal: '关闭',
		cancel: true,
		button:[{
			name: '生成报告',
			callback: function () {
                var mydialog = $.dialog.open("/project/report/input/0?projectId="+ projectId +"&prreId=" + id,{
					title:	"生成报告",
					width:	'600px',
					height:	'380px',
					ok: function () {
						var iframe = this.iframe.contentWindow;
				    	if (!iframe.document.body) {
				        	alert("对不起，数据还在加载中.");
				        	return false;
				        };
				        var frm = iframe.document.getElementById("frm");
				        
				        if(frm.rerePrreId.value=="0"){
				        	alert("请选择候选人...");
				        	frm.selectResumeBtn.click();
				        	return false;
				        }
				        
				        if(frm.rereCurrentCity.value==""){
				        	alert("请填写候选人目前所有地");
				        	frm.rereCurrentCity.focus();
				        	return false;
				        }
				        
			        	if(frm.rereAccessory.value==""){
			        		alert("请上传原始报告附件.");
			        		return false;
			        	}
			        	
			        	var _confirm = $.dialog.confirm('你确认资料正确吗？', function(){
				        	var data = $(frm).formToArray();
			        		$.post("../report/save?rand="+new Date(), data, function(result){
			        			if(result=='success'){
			        				$.dialog.alert("恭喜您，操作成功！", function(){
			        					mydialog.close();
			        					_confirm.close();
			        				});
			        			}else{
			        				$.dialog.alert(result);
			        				_confirm.close();
			        			}
			        		})
					    	return false;
						});
						return false;
					},
					cancelVal: '关闭',
					cancel: true
				});
				return false;
            }
		},
		{
			name:"编辑简历",
			callback: function () {
				var freshWindow = this.iframe.contentWindow;
				edit(resumeId,0,freshWindow);
				return false;
			}
		}
		]
	});
}

function show(id,title){
	var winWidth = 850;
	if($(top.window).width()>1024){
		winWidth = 1200;
	}
	winWidth = winWidth + "px";
	var keywordencode = $("#keywordencode");
	var url = "/resume/show/"+id;
	if(keywordencode && keywordencode.val()!=""){
		url += "?keywordencode=" + keywordencode.val();
	}
	var mydialog = $.dialog.open(url,{
		title :	title+'简历详情',
		width : winWidth,
		height:	'500px',
		id	:	"resume_show_" + id,
		lock  :	false,
		fixed: true,
		cancelVal: '关闭',
		cancel: true,
		button:[
			{
				name:"编辑简历",
				callback: function () {
					var freshWindow = this.iframe.contentWindow;
					edit(id,0,freshWindow);
					return false;
				},
				focus:true
			},
			{
				name:"上一份",
				callback: function () {
					perviewFor(id, -1);
				}
			},
			{
				name:"下一份",
				callback: function () {
					perviewFor(id, 1);
				}
			}
		]
	});
}

function showEdit(id,title){
	var winWidth = 850;
	if($(top.window).width()>1024){
		winWidth = 1200;
	}
	winWidth = winWidth + "px";
	var keywordencode = $("#keywordencode");
	var url = "/resume/show/"+id;
	if(keywordencode && keywordencode.val()!=""){
		url += "?keywordencode=" + keywordencode.val();
	}
	var mydialog = $.dialog.open(url,{
		title :	title+'简历详情',
		width : winWidth,
		height:	'500px',
		fixed: true,
		id	:	"resume_show_" + id,
		lock  :	false,
		button:[
			{
				name:"编辑简历",
				callback: function () {
					var freshWindow = this.iframe.contentWindow;
					edit(id,0,freshWindow);
					return false;
				},
				focus:true
			},
			{
				name:"上一份",
				callback: function () {
					perviewFor(id, -1);
				}
			},
			{
				name:"下一份",
				callback: function () {
					perviewFor(id, 1);
				}
			}
		],
		cancelVal: '关闭',
		cancel: true
	});
}

function perviewFor(id, action){
	if(resumeIds.toString().length>1){
		var resumeId = resumeIds.toString().substring(0, resumeIds.toString().length-1);
		var resumeTitle = resumeTitles.toString().substring(0, resumeTitles.toString().length-1);
		var rIds = resumeId.split(",");
		var titles = resumeTitle.split(",");
		var rId = 0;
		var rTitle = "";
		for(var i=0;i<rIds.length;i++){
			if(id==rIds[i]){
				if(action==-1){
					if(i==0){
						alert("本份简历已经是本页最前面一份简历。");
						return false;
					}else{
						rId = rIds[i-1];
						rTitle = titles[i-1];
						break;
					}
				}
				if(action==1){
					if(i==rIds.length-1){
						alert("本份简历已经是本页最后一份简历。");
						return false;
					}else{
						rId = rIds[i+1];
						rTitle = titles[i+1];
						break;
					}
				}
			}
		}
		showEdit(rId, rTitle);
	}else{
		alert("简历不存在。");
		return false;
	}
}

function StringBuffer() {
    this._strings_ = new Array;
}
StringBuffer.prototype.append = function(str) {
    this._strings_.push(str);
}
StringBuffer.prototype.toString = function() {
    return this._strings_.join("");
}

function emailtoresume(emailid){
	var btnName="保存到我的简历库";
	var mydialog = $.dialog.open("/resume/emailtoresume/"+emailid,{
		id: 'emailtoresume_'+emailid,
		title :	'保存到简历库',
		width : '800px',
		height:	'500px',
		fixed: true,
		lock  :	true,
		button:[
		{
			name:btnName,
			callback: function () {
				var iframe = this.iframe.contentWindow;
		    	if (!iframe.document.body) {
		        	alert("对不起，数据还在加载中.");
		        	return false;
		        };
		        var frm = iframe.document.getElementById("frm");
	        	if(!iframe.chkContent()){
	        		return false;
	        	}
	        	var data = $(frm).formToArray();
        		$.post("/resume/save?rand="+new Date(), data, function(result){
        			if(result=='success'){
        				$.dialog.alert("恭喜您，操作成功！", function(){
        					mydialog.close();
        				});
        			}else{
        				$.dialog.alert(result);
        			}
        		})
				return false;
			},
			focus:true
		}
		],
		cancelVal: '关闭',
		cancel: true
	});
}

function emailattachmentoresume(emailid, filePath, fileName){
	var btnName="保存到我的简历库";
	var tmp = $('input[name="allowUpdateExist"]:checked').serialize();
	var v = 0;
	if(tmp.length>0){
		v = tmp.split("=")[1];
	}
	var mydialog = $.dialog.open("/resume/emailattachmenttoresume/" + emailid + "?filePath="+filePath+"&fileName="+fileName+"&d="+new Date()+"&allowUpdateExist="+v,{
		id: 'emailattachmenttoresume_'+emailid,
		title :	'保存到简历库',
		width : '800px',
		height:	'500px',
		fixed: true,
		lock  :	true,
		button:[
		{
			name:btnName,
			callback: function () {
				var iframe = this.iframe.contentWindow;
		    	if (!iframe.document.body) {
		        	alert("对不起，数据还在加载中.");
		        	return false;
		        };
		        var frm = iframe.document.getElementById("frm");
	        	if(!iframe.chkContent()){
	        		return false;
	        	}
	        	var data = $(frm).formToArray();
        		$.post("/resume/save?rand="+new Date(), data, function(result){
        			if(result=='success'){
        				$.dialog.alert("恭喜您，操作成功！", function(){
        					mydialog.close();
        				});
        			}else{
        				$.dialog.alert(result);
        			}
        		})
				return false;
			},
			focus:true
		}
		],
		cancelVal: '关闭',
		cancel: true
	});
}

function editForm(id,title,pjId,hasErr,swfId,freshWindow){
	var btnName="保存";
	if(hasErr==1){
		btnName="保存到我的简历库";
	}
	var mydialog = $.dialog.open("/resume/input/"+id+"?pjId="+pjId,{
		title :	title,
		width : '800px',
		height:	'500px',
		fixed: true,
		lock  :	true,
		button:[
		{
			name:btnName,
			callback: function () {
				var iframe = this.iframe.contentWindow;
		    	if (!iframe.document.body) {
		        	alert("对不起，数据还在加载中.");
		        	return false;
		        };
		        var frm = iframe.document.getElementById("frm");
	        	if(!iframe.chkContent()){
	        		return false;
	        	}
	        	var data = $(frm).formToArray();
	        	var lockscreen = $.dialog({content:'正在保存简历……',lock:true});
        		$.post(getContextPath() + "resume/save?rand="+new Date(), data, function(result){
        			lockscreen.close();
        			if(result=='success'){
        				$.dialog.alert("恭喜您，操作成功！", function(){
        					if(pjId=="0"){
        						if(typeof(swfId)!="undefined" && swfId!=""){
        							$('#file_upload_1').uploadify('cancel', swfId);
        						}
        						var win = $.dialog.open.origin;
        						if(typeof(win.listpage)!="undefined")
	        						win.listpage();
	        				}else if(pjId>0){
	        					listpage();
	        				}else if(typeof(freshWindow)!='undefined'){
	        					freshWindow.location.reload();
        					}else{
        						var funstr = $("#listpage a[class='current']").attr("href").replace("javascript:","");
	        					eval(funstr);
        					}
        					mydialog.close();
        				});
        			}else{
        				$.dialog.alert(result);
        			}
        			$('.maincontent').hideLoading();
        		})
				return false;
			},
			focus:true
		}
		],
		cancelVal: '关闭',
		cancel: true
	});
}
function select(obj,pjId,reId,name){
	$.dialog.confirm("你确定要选择候选人【 "+name+" 】吗？",function(){
		$.get("../select/"+pjId+"/"+reId+"?rand=" + new Date(),function(result){
			if(result=='success'){
				$.dialog.alert("恭喜您，添加成功！");
				$(obj).closest('tr').remove(); 
			}else{
				$.dialog.alert(result);
			}
		});
	});
}
function changetype(prId,objSel){
	$.dialog.confirm("你确定改变该候选人的状态吗？",function(){
		$.get("../../projectResume/changetype/"+prId+"/"+$(objSel).val()+"?rand=" + new Date(),function(result){
			if(result=='success'){
				$.dialog.alert("恭喜您，修改成功！", function(){
					var funstr = $("#projectResumePage a[class='current']").attr("href").replace("javascript:","");
		        	eval(funstr);
				});
			}else{
				$.dialog.alert(result);
			}
		});
	},function(){
		$(objSel).find("option[value='"+$(objSel).attr("origin")+"']").attr("selected",true);
	});
}
function del(prId){
	$.dialog.confirm("你确定要删除此候选人吗？",function(){
		$.get("../../projectResume/del/"+prId+"?rand=" + new Date(),function(result){
			if(result=='success'){
				$.dialog.alert("恭喜您，删除成功！", function(){
					listpage();
				});
			}else{
				$.dialog.alert(result);
			}
		});
	});
}
function handleFunction(obj, handle){
	var fuctions = $("#fuctionHidden").val();
	var fucs;
	if(fuctions==""){
		fucs = new Array();
	}else{
		fucs = fuctions.split(";");
	}
	var fuctionTxts = $("#jobfuctiontype_dropdivselected_group .selected_box");
	var v = $(obj).attr("v");
	var p = $(obj).attr("p");
	var t = $(obj).attr("title");
	var str =v;
	if(handle){
		var div = "<div class=\"selected_box inline_block f_l\" p=\""+ p +"\" v=\""+ v +"\"><span>"+ t +"</span></div>";
		$("#jobfuctiontype_dropdivselected_div").show();
		if($("#jobfuctiontype_dropdivselected_group .selected_box").length<5){
			$("#jobfuctiontype_dropdivselected_group").append(div);
			fucs.push(str);
			var newfuctions = "";
			for(var i=0;i<fucs.length;i++){
				newfuctions = newfuctions + fucs[i] + ";";
			}
			$("#fuctionHidden").val(newfuctions.substring(0, newfuctions.length-1));
		}else{
			$("#jobfuctiontype_dropdivselected_group .max_selected").show();
			alert("最多只能选择5项.");
			return false;
		}
	}else{
		fuctionTxts.each(function(){
			if($(this).attr("v")==v)$(this).remove();
		});
		var newfuctions = "";
		for(var i=0;i<fucs.length;i++){
			if(fucs[i]!=str){
				newfuctions = newfuctions + fucs[i] + ";";
			}
		}
		$("#fuctionHidden").val(newfuctions.substring(0, newfuctions.length-1));
	}
	var fucount = $("#jobfuctiontype_dropdivselected_group .selected_box").length; 
	if(fucount>0 && fucount<=5){
		$("#jobfuctiontype_dropdivselected_group .max_selected").hide();
		var fucTxt = "";
		$("#jobfuctiontype_dropdivselected_group .selected_box").each(function(){
			fucTxt = fucTxt + $(this).find("span").text() + ",";
		}).unbind("mouseover").bind("mouseover",function(){
			$(this).addClass("selected_box_hover");
		}).unbind("mouseout").bind("mouseout",function(){
			$(this).removeClass("selected_box_hover");
		}).unbind("click").bind("click",function(){
			$("#jobfuctiontype_classId_" + $(this).attr("v")).click();
		});
		$("#jobfuctiontype_txt").text(fucTxt.substring(0, fucTxt.length-1));
		$("#jobfuctiontype_txt").parent().find(".countnumber").show().text("共"+ fucount +"岗位");
		$("#jobfuctiontype_dropdivleft_scroll p span").removeClass("checkbox_txt_checked").removeClass("bold");
	}else if(fucount==0){
		$("#jobfuctiontype_dropdivselected_div").hide();
		$("#jobfuctiontype_txt").text("全部岗位");
		$("#jobfuctiontype_txt").parent().find(".countnumber").hide().text("");
		$("#jobfuctiontype_dropdivleft_scroll p span").addClass("checkbox_txt_checked").addClass("bold");
	}else{
		$("#jobfuctiontype_dropdivselected_group .max_selected").show();
	}
	return true;
}

function handleTrade(obj, handle){
	var fuctions = $("#tradeHidden").val();
	var fucs;
	if(fuctions==""){
		fucs = new Array();
	}else{
		fucs = fuctions.split(";");
	}
	var fuctionTxts = $("#tradetype_dropdivselected_group .selected_box");
	var v = $(obj).attr("v");
	var p = $(obj).attr("p");
	var t = $(obj).attr("title");
	//var str = p + "a" + v;
	var str = v;
	if(handle){
		var div = "<div class=\"selected_box inline_block f_l\" p=\""+ p +"\" v=\""+ v +"\"><span>"+ t +"</span></div>";
		$("#tradetype_dropdivselected_div").show();
		if($("#tradetype_dropdivselected_group .selected_box").length<5){
			$("#tradetype_dropdivselected_group").append(div);
			fucs.push(str);
			var newfuctions = "";
			for(var i=0;i<fucs.length;i++){
				newfuctions = newfuctions + fucs[i] + ";";
			}
			$("#tradeHidden").val(newfuctions.substring(0, newfuctions.length-1));
		}else{
			$("#tradetype_dropdivselected_group .max_selected").show();
			alert("最多只能选择5项.");
			return false;
		}
	}else{
		fuctionTxts.each(function(){
			if($(this).attr("v")==v)$(this).remove();
		});
		var newfuctions = "";
		for(var i=0;i<fucs.length;i++){
			if(fucs[i]!=str){
				newfuctions = newfuctions + fucs[i] + ";";
			}
		}
		$("#tradeHidden").val(newfuctions.substring(0, newfuctions.length-1));
	}
	var fucount = $("#tradetype_dropdivselected_group .selected_box").length; 
	if(fucount>0 && fucount<=5){
		$("#tradetype_dropdivselected_group .max_selected").hide();
		var fucTxt = "";
		$("#tradetype_dropdivselected_group .selected_box").each(function(){
			fucTxt = fucTxt + $(this).find("span").text() + ",";
		}).unbind("mouseover").bind("mouseover",function(){
			$(this).addClass("selected_box_hover");
		}).unbind("mouseout").bind("mouseout",function(){
			$(this).removeClass("selected_box_hover");
		}).unbind("click").bind("click",function(){
			$("#tradetype_classId_" + $(this).attr("v")).click();
		});
		$("#tradetype_txt").text(fucTxt.substring(0, fucTxt.length-1));
		$("#tradetype_txt").parent().find(".countnumber").show().text("共"+ fucount +"岗位");
		$("#tradetype_dropdivleft_scroll p span").removeClass("checkbox_txt_checked").removeClass("bold");
	}else if(fucount==0){
		$("#tradetype_dropdivselected_div").hide();
		$("#tradetype_txt").text("全部岗位");
		$("#tradetype_txt").parent().find(".countnumber").hide().text("");
		$("#tradetype_dropdivleft_scroll p span").addClass("checkbox_txt_checked").addClass("bold");
	}else{
		$("#tradetype_dropdivselected_group .max_selected").show();
	}
	return true;
}

function handleFla(obj, handle){
	var allowFlaMulti=0;
	if(allowFlaMulti){
		var fuctions = $("#flaHidden").val();
		var fucs;
		if(fuctions==""){
			fucs = new Array();
		}else{
			fucs = fuctions.split(";");
		}
		var fuctionTxts = $("#flatype_dropdivselected_group .selected_box");
		var v = $(obj).attr("v");
		var p = $(obj).attr("p");
		var t = $(obj).attr("title");
		var str = p + "a" + v;
		if(handle){
			var div = "<div class=\"selected_box inline_block f_l\" p=\""+ p +"\" v=\""+ v +"\"><span>"+ t +"</span></div>";
			$("#flatype_dropdivselected_div").show();
			if($("#flatype_dropdivselected_group .selected_box").length<5){
				$("#flatype_dropdivselected_group").append(div);
				fucs.push(str);
				var newfuctions = "";
				for(var i=0;i<fucs.length;i++){
					newfuctions = newfuctions + fucs[i] + ";";
				}
				$("#flaHidden").val(newfuctions.substring(0, newfuctions.length-1));
			}else{
				$("#flatype_dropdivselected_group .max_selected").show();
				alert("最多只能选择5项.");
				return false;
			}
		}else{
			fuctionTxts.each(function(){
				if($(this).attr("v")==v)$(this).remove();
			});
			var newfuctions = "";
			for(var i=0;i<fucs.length;i++){
				if(fucs[i]!=str){
					newfuctions = newfuctions + fucs[i] + ";";
				}
			}
			$("#flaHidden").val(newfuctions.substring(0, newfuctions.length-1));
		}
		var fucount = $("#flatype_dropdivselected_group .selected_box").length; 
		if(fucount>0 && fucount<=5){
			$("#flatype_dropdivselected_group .max_selected").hide();
			var fucTxt = "";
			$("#flatype_dropdivselected_group .selected_box").each(function(){
				fucTxt = fucTxt + $(this).find("span").text() + ",";
			}).unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("selected_box_hover");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("selected_box_hover");
			}).unbind("click").bind("click",function(){
				$("#flatype_classId_" + $(this).attr("v")).click();
			});
			$("#flatype_txt").text(fucTxt.substring(0, fucTxt.length-1));
			$("#flatype_txt").parent().find(".countnumber").show().text("共"+ fucount +"分类");
			$("#flatype_dropdivleft_scroll p span").removeClass("checkbox_txt_checked").removeClass("bold");
		}else if(fucount==0){
			$("#flatype_dropdivselected_div").hide();
			$("#flatype_txt").text("全部类型");
			$("#flatype_txt").parent().find(".countnumber").hide().text("");
			$("#flatype_dropdivleft_scroll p span").addClass("checkbox_txt_checked").addClass("bold");
		}else{
			$("#flatype_dropdivselected_group .max_selected").show();
		}
		return true;
	}else{
		var fuctions = $("#flaHidden").val();
		var fucs;
		if(fuctions==""){
			fucs = new Array();
		}else{
			fucs = fuctions.split(";");
		}
		var fuctionTxts = $("#flatype_dropdivselected_group .selected_box");
		var v = $(obj).attr("v");
		var p = $(obj).attr("p");
		var t = $(obj).attr("title");
		var str = p + "a" + v;
		if(handle){
			var div = "<div class=\"selected_box inline_block f_l\" p=\""+ p +"\" v=\""+ v +"\"><span>"+ t +"</span></div>";
			$("#flatype_dropdivselected_div").show();
			
			$(obj).parent().parent().find("li").each(function(){
				if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
					if(handleFla(this, false)){
						$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
					}
				}
			});
			
			if($("#flatype_dropdivselected_group .selected_box").length<5){
				
				var newfuctions = "";
				for(var i=0;i<fucs.length;i++){
					if(fucs[i].indexOf(p+"a")==-1)
						newfuctions = newfuctions + fucs[i] + ";";
				}
	//			alert(11);
				$("#flatype_dropdivselected_group").append(div);
	//			fucs.push(str);
				newfuctions = newfuctions + str + ";";
				$("#flaHidden").val(newfuctions.substring(0, newfuctions.length-1));
			}else{
				$("#flatype_dropdivselected_group .max_selected").show();
				alert("最多只能选择5项.");
				return false;
			}
		}else{
			fuctionTxts.each(function(){
				if($(this).attr("v")==v)$(this).remove();
			});
			var newfuctions = "";
			for(var i=0;i<fucs.length;i++){
				if(fucs[i]!=str){
					newfuctions = newfuctions + fucs[i] + ";";
				}
			}
			$("#flaHidden").val(newfuctions.substring(0, newfuctions.length-1));
		}
		var fucount = $("#flatype_dropdivselected_group .selected_box").length; 
		if(fucount>0 && fucount<=5){
			$("#flatype_dropdivselected_group .max_selected").hide();
			var fucTxt = "";
			$("#flatype_dropdivselected_group .selected_box").each(function(){
				fucTxt = fucTxt + $(this).find("span").text() + ",";
			}).unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("selected_box_hover");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("selected_box_hover");
			}).unbind("click").bind("click",function(){
				$("#flatype_classId_" + $(this).attr("v")).click();
			});
			$("#flatype_txt").text(fucTxt.substring(0, fucTxt.length-1));
			$("#flatype_txt").parent().find(".countnumber").show().text("共"+ fucount +"类型");
			$("#flatype_dropdivleft_scroll p span").removeClass("checkbox_txt_checked").removeClass("bold");
			$("#flatype_dropdivselected_div").show();
		}else if(fucount==0){
			$("#flatype_dropdivselected_div").hide();
			$("#flatype_txt").text("自定义分类");
			$("#flatype_txt").parent().find(".countnumber").hide().text("");
			$("#flatype_dropdivleft_scroll p span").addClass("checkbox_txt_checked").addClass("bold");
		}else{
			$("#flatype_dropdivselected_group .max_selected").show();
		}
		return true;
	}
}

function handleLocation(obj, handle){
	var fuctions = $("#locationHidden").val();
	var fucs;
	if(fuctions==""){
		fucs = new Array();
	}else{
		fucs = fuctions.split(";");
	}
	var fuctionTxts = $("#locationtype_dropdivselected_group .selected_box");
	var v = $(obj).attr("v");
	var p = $(obj).attr("p");
	var t = $(obj).attr("title");
	var str = v;
	if(handle){
		var div = "<div class=\"selected_box inline_block f_l\" p=\""+ p +"\" v=\""+ v +"\"><span>"+ t +"</span></div>";
		$("#locationtype_dropdivselected_div").show();
		if($("#locationtype_dropdivselected_group .selected_box").length<5){
			$("#locationtype_dropdivselected_group").append(div);
			fucs.push(str);
			var newfuctions = "";
			for(var i=0;i<fucs.length;i++){
				newfuctions = newfuctions + fucs[i] + ";";
			}
			$("#locationHidden").val(newfuctions.substring(0, newfuctions.length-1));
		}else{
			$("#locationtype_dropdivselected_group .max_selected").show();
			alert("最多只能选择5项.");
			return false;
		}
	}else{
		fuctionTxts.each(function(){
			if($(this).attr("v")==v)$(this).remove();
		});
		var newfuctions = "";
		for(var i=0;i<fucs.length;i++){
			if(fucs[i]!=str){
				newfuctions = newfuctions + fucs[i] + ";";
			}
		}
		$("#locationHidden").val(newfuctions.substring(0, newfuctions.length-1));
	}
	var fucount = $("#locationtype_dropdivselected_group .selected_box").length; 
	if(fucount>0 && fucount<=5){
		$("#locationtype_dropdivselected_group .max_selected").hide();
		var fucTxt = "";
		$("#locationtype_dropdivselected_group .selected_box").each(function(){
			fucTxt = fucTxt + $(this).find("span").text() + ",";
		}).unbind("mouseover").bind("mouseover",function(){
			$(this).addClass("selected_box_hover");
		}).unbind("mouseout").bind("mouseout",function(){
			$(this).removeClass("selected_box_hover");
		}).unbind("click").bind("click",function(){
			$("#locationtype_classId_" + $(this).attr("v")).click();
		});
		$("#locationtype_txt").text(fucTxt.substring(0, fucTxt.length-1));
		$("#locationtype_txt").parent().find(".countnumber").show().text("共"+ fucount +"地区");
		$("#locationtype_dropdivleft_scroll p span").removeClass("checkbox_txt_checked").removeClass("bold");
	}else if(fucount==0){
		$("#locationtype_dropdivselected_div").hide();
		$("#locationtype_txt").text("意向地区");
		$("#locationtype_txt").parent().find(".countnumber").hide().text("");
		$("#locationtype_dropdivleft_scroll p span").addClass("checkbox_txt_checked").addClass("bold");
	}else{
		$("#locationtype_dropdivselected_group .max_selected").show();
	}
	return true;
}

function initResumeInput(){
	$("#resuTags").focus(function(){
		$("#resuTags_div").addClass("common_group_focus");
		if($(this).val()=="当前职位、简历标题、关键字、自定义标签等")$(this).val("");
	}).blur(function(){
		$("#resuTags_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("");
	});
	$("#resuName").focus(function(){
		$("#resuName_div").addClass("common_group_focus");
		if($(this).val()=="姓名")$(this).val("");
	}).blur(function(){
		$("#resuName_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("");
	});
	$("#resuBirthdayStr").focus(function(){
		$("#resuBirthdayStr_div").addClass("common_group_focus");
		if($(this).val()=="出生年份")$(this).val("");
	}).blur(function(){
		$("#resuBirthdayStr_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("");
	});
	$("#resuEmail").focus(function(){
		$("#resuEmail_div").addClass("common_group_focus");
		if($(this).val()=="联系邮箱")$(this).val("");
	}).blur(function(){
		$("#resuEmail_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("");
	});
	$("#resuContactInfo").focus(function(){
		$("#resuContactInfo_div").addClass("common_group_focus");
		if($(this).val()=="联系方式")$(this).val("");
	}).blur(function(){
		$("#resuContactInfo_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("联系方式");
	});
	$("#resuCurCompany").focus(function(){
		$("#resuCurCompany_div").addClass("common_group_focus");
		if($(this).val()=="当前公司")$(this).val("");
	}).blur(function(){
		$("#resuCurCompany_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("");
	});
	$("#locationtype_dropdivselected_group .selected_box").unbind("mouseover").bind("mouseover",function(){
		$(this).addClass("selected_box_hover");
	}).unbind("mouseout").bind("mouseout",function(){
		$(this).removeClass("selected_box_hover");
	}).unbind("click").bind("click",function(){
		$("#locationtype_classId_" + $(this).attr("v")).click();
	});
	$("#tradetype_dropdivselected_group .selected_box").unbind("mouseover").bind("mouseover",function(){
		$(this).addClass("selected_box_hover");
	}).unbind("mouseout").bind("mouseout",function(){
		$(this).removeClass("selected_box_hover");
	}).unbind("click").bind("click",function(){
		$("#tradetype_classId_" + $(this).attr("v")).click();
	});
	$("#jobfuctiontype_dropdivselected_group .selected_box").unbind("mouseover").bind("mouseover",function(){
		$(this).addClass("selected_box_hover");
	}).unbind("mouseout").bind("mouseout",function(){
		$(this).removeClass("selected_box_hover");
	}).unbind("click").bind("click",function(){
		$("#jobfuctiontype_classId_" + $(this).attr("v")).click();
	});
	$("#flatype_dropdivselected_group .selected_box").unbind("mouseover").bind("mouseover",function(){
		$(this).addClass("selected_box_hover");
	}).unbind("mouseout").bind("mouseout",function(){
		$(this).removeClass("selected_box_hover");
	}).unbind("click").bind("click",function(){
		$("#flatype_classId_" + $(this).attr("v")).click();
	});
}

function initResumeSearchText(){
	$("#resumeSearchKeyword").focus(function(){
		$("#txtKeyWords_div").addClass("common_group_focus");
		if($(this).val()=="输入简历关键字")$(this).val("");
		if($(this).val()=="姓名")$(this).val("");
	}).blur(function(){
		$("#txtKeyWords_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("输入简历关键字");
	});
	$("#txtResumeNo").focus(function(){
		$("#txtResumeNo_div").addClass("common_group_focus");
		if($(this).val()=="简历编号")$(this).val("");
	}).blur(function(){
		$("#txtResumeNo_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("简历编号");
	});
	$("#agemin").focus(function(){
		$("#txtAgemin_div").addClass("common_group_focus");
		if($(this).val()=="最小年龄")$(this).val("");
	}).blur(function(){
		$("#txtAgemin_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("最小年龄");
	});
	$("#agemax").focus(function(){
		$("#txtAgemax_div").addClass("common_group_focus");
		if($(this).val()=="最大年龄")$(this).val("");
	}).blur(function(){
		$("#txtAgemax_div").removeClass("common_group_focus");
		if($(this).val()=="")$(this).val("最大年龄");
	});
}

function initResumeInputEducationAndSex(){
	var layers = "#education,#sexlayer";
	$(layers).unbind("mouseover").bind("mouseover",function(){
		$(this).find(".icon_deaultarrow").addClass("icon_deaultarrow_selected");
	}).unbind("mouseout").bind("mouseout",function(){
		if(!($(this).hasClass("common_group_focus"))){
			$(this).find(".icon_deaultarrow").removeClass("icon_deaultarrow_selected");
		}
	}).unbind("click").bind("click",function(){
		$(this).find(".icon_deaultarrow").addClass("icon_deaultarrow_selected");
		$(this).addClass("common_group_focus");
		$(this).find(".selected_con").removeClass("c_8c8c8c").addClass("c_79c9ee");
		var objId = $(this);
		if($(this).attr("id")=="education"){
			$(this).find(".icon_jobstype").removeClass("icon_xl").addClass("icon_xl_ac");
			$(this).find("li").unbind("click").bind("click",function(){
				$("#xl_children li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold").siblings();
				$("#educationHidden").val($(this).find("span").attr("val"));
				$("#education_txt").text($(this).find("span").text());
			}).unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			});
		}else if($(this).attr("id")=="sexlayer"){
			$(this).find("li").unbind("click").bind("click",function(){
				$("#sex_children li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$("#sexHidden").val($(this).find("span").attr("val"));
				$("#sex_txt").text($(this).find("span").text());
				$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold");
			}).unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			});
		}
		$(this).find(".common_choose_layer_wrapper").slideDown("fast");
		$("body").unbind("mousedown").bind("mousedown", clearResumeSearchDiv);
	});
}

function initResumeSearchEducationAndSex(){
	var layers = "#education,#sexlayer,#sortlayer,#ownerlayer";
	$(layers).unbind("mouseover").bind("mouseover",function(){
		$(this).find(".icon_deaultarrow").addClass("icon_deaultarrow_selected");
	}).unbind("mouseout").bind("mouseout",function(){
		if(!($(this).hasClass("common_group_focus"))){
			$(this).find(".icon_deaultarrow").removeClass("icon_deaultarrow_selected");
		}
	}).unbind("click").bind("click",function(){
		$(this).find(".icon_deaultarrow").addClass("icon_deaultarrow_selected");
		$(this).addClass("common_group_focus");
		$(this).find(".selected_con").removeClass("c_8c8c8c").addClass("c_79c9ee");
		if($(this).attr("id")=="education"){
			$(this).find(".icon_jobstype").removeClass("icon_xl").addClass("icon_xl_ac");
			$(this).find(".all_level_box").unbind("click").bind("click",function(){
				$("#educationHidden").val("");
				$("#education_txt").text("全部学历");
				$("#education_txt").removeClass("w74").addClass("w141");
				$("#education_txt").parent().find(".countnumber").text("");
				$("#education_txt").parent().find(".countnumber").hide();
				$("#xl_children li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$(objId).find(".all_level_box span").addClass("checkbox_txt_checked").addClass("bold");
			});
			$(this).find("li").unbind("click").bind("click",function(){
				var educations = "," + $("#educationHidden").val() + ",";
				var educationTxts = "," + $("#education_txt").text() + ",";
				if(educationTxts==',全部学历,' || educationTxts==',学历,')educationTxts = ",";
				if(educations==",,")educations = ",";
				if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
					$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
					var val = "," + $(this).find("span").attr("val") + ",";
					var txt = "," + $(this).find("span").text() + ",";
					educations = educations.replace(val, ",");
					educationTxts = educationTxts.replace(txt, ",");
				}else{
					$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold");
					educations += $(this).find("span").attr("val") + ",";
					educationTxts += $(this).find("span").text() + ",";
				}
				if(educations.length==1){
					$("#educationHidden").val("");
					$("#education_txt").text("全部学历");
					$("#education_txt").removeClass("w74").addClass("w141");
					$("#education_txt").parent().find(".countnumber").text("");
					$("#education_txt").parent().find(".countnumber").hide();
					$(objId).find(".all_level_box span").addClass("checkbox_txt_checked").addClass("bold");
				}else{
					var edu = educations.substring(1, educations.length-1);
					var edulen = edu.split(",").length;
					var educount = "共"+ edulen +"个学历";
					$("#educationHidden").val(edu);
					$("#education_txt").text(educationTxts.substring(1, educationTxts.length-1));
					$("#education_txt").removeClass("w141").addClass("w74");
					$("#education_txt").parent().find(".countnumber").text(educount);
					$("#education_txt").parent().find(".countnumber").show();
					$(objId).find(".all_level_box span").removeClass("checkbox_txt_checked").removeClass("bold");
				}
			}).unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			});
		}else if($(this).attr("id")=="sexlayer"){
			$(this).find(".all_level_box").unbind("click").bind("click",function(){
				$("#sexHidden").val("0");
				$("#sex_txt").text("全部性别");
				$("#sex_children li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$(objId).find(".all_level_box span").addClass("checkbox_txt_checked").addClass("bold");
			});
			$(this).find("li").unbind("click").bind("click",function(){
				$("#sex_children li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$("#sexHidden").val($(this).find("span").attr("val"));
				$("#sex_txt").text($(this).find("span").text());
				$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold");
				$(objId).find(".all_level_box span").removeClass("checkbox_txt_checked").removeClass("bold");
			}).unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			});
		}else if($(this).attr("id")=="sortlayer"){
			$(this).find(".all_level_box").unbind("click").bind("click",function(){
				$("#sortHidden").val("0");
				$("#sort_txt").text("默认排序");
				$("#sort_children li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$(objId).find(".all_level_box span").addClass("checkbox_txt_checked").addClass("bold");
			});
			$(this).find("li").unbind("click").bind("click",function(){
				$("#sort_children li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$("#sortHidden").val($(this).find("span").attr("val"));
				$("#sort_txt").text($(this).find("span").text());
				$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold");
				$(objId).find(".all_level_box span").removeClass("checkbox_txt_checked").removeClass("bold");
			}).unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			});
		}else if($(this).attr("id")=="ownerlayer"){
			$(this).find(".all_level_box").unbind("click").bind("click",function(){
				$("#ownerHidden").val("0");
				$("#owner_txt").text("全部员工");
				$("#owner_children li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$(objId).find(".all_level_box span").addClass("checkbox_txt_checked").addClass("bold");
			});
			$(this).find("li").unbind("click").bind("click",function(){
				$("#owner_children li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$("#ownerHidden").val($(this).find("span").attr("val"));
				$("#owner_txt").text($(this).find("span").text());
				$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold");
				$(objId).find(".all_level_box span").removeClass("checkbox_txt_checked").removeClass("bold");
			}).unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			});
		}
		$(this).find(".common_choose_layer_wrapper").slideDown("fast");
		$("body").unbind("mousedown").bind("mousedown", clearResumeSearchDiv);
	});
}

function initResumeSelectDivs(){
	var layers = "#jobfuctiontype,#tradetype,#locationtype,#flatype";
	$(layers).unbind("mouseover").bind("mouseover",function(){
		$(this).find(".icon_deaultarrow").addClass("icon_deaultarrow_selected");
	}).unbind("mouseout").bind("mouseout",function(){
		if(!($(this).hasClass("common_group_focus"))){
			$(this).find(".icon_deaultarrow").removeClass("icon_deaultarrow_selected");
		}
	}).unbind("click").bind("click",function(){
		$(this).find(".icon_deaultarrow").addClass("icon_deaultarrow_selected");
		$(this).addClass("common_group_focus");
		$(this).find(".selected_con").removeClass("c_8c8c8c").addClass("c_79c9ee");
		var objId = $(this);
		if($(this).attr("id")=="jobfuctiontype"){
			$(this).find(".icon_jobstype").removeClass("icon_gw").addClass("icon_gw_ac");
			$("#jobfuctiontype_dropdivselected_ok").unbind("click").bind("click",function(){
				$(objId).removeClass("common_group_focus");
				$(objId).find(".icon_deaultarrow").removeClass("icon_deaultarrow_selected");
				$(objId).find(".selected_con").removeClass("c_79c9ee").addClass("c_8c8c8c");
				$(objId).find(".icon_jobstype").removeClass("icon_gw_ac").addClass("icon_gw");
				$(objId).find(".common_choose_layer_wrapper").fadeOut("fast");
				return false;
			});
			$("#jobfuctiontype_dropdivleft_scroll p").unbind("click").bind("click",function(){
				$("#jobfuctiontype_dropdivright_tip").show();
				$("#jobfuctiontype_dropdivcenter_wrap").hide();
				$("#jobfuctiontype_dropdivright_wrap").hide();
				$("#jobfuctiontype_dropdivleft_scroll li").each(function(){
					$(this).removeClass("selected");
				});
				$("#jobfuctiontype_dropdivcenter_wrap li").each(function(){
					$(this).removeClass("selected");
				});
				$("#jobfuctiontype_dropdivright_wrap li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$("#jobfuctiontype_dropdivright_wrap p span").each(function(){
					$(this).removeClass("checkbox_txt_checked").removeClass("bold");
				});
				$("#jobfuctiontype_dropdivselected_group .selected_box").each(function(){
					$(this).remove();
				});
				$(this).find("span").addClass("checkbox_txt_checked").addClass("bold");
				$("#jobfuctiontype_dropdivselected_div").hide();
				$("#jobfuctiontype_txt").text("全部岗位");
				$("#jobfuctiontype_txt").parent().find(".countnumber").hide().text("");
				$("#fuctionHidden").val("");
			});
			$("#jobfuctiontype_dropdivleft_scroll li").unbind("click").bind("click",function(){
				$("#jobfuctiontype_dropdivright_tip").hide();
				$(this).addClass("selected").siblings().removeClass("selected");
				$("#jobfuctiontype_dropdivcenter_wrap").show();
				$("#jobfuctiontype_dropdivmdiv_" + $(this).attr("v")).show().siblings().hide();
				$("#jobfuctiontype_dropdivright_wrap").hide();
				$("#jobfuctiontype_dropdivcenter_wrap li").each(function(){
					$(this).removeClass("selected");
				});
				$("#jobfuctiontype_dropdivright_wrap li").each(function(){
					$(this).removeClass("selected");
				});
				$("#jobfuctiontype_dropdivmdiv_" + $(this).attr("v") + " li").unbind("click").bind("click",function(){
					$(this).addClass("selected").siblings().removeClass("selected");
					$("#jobfuctiontype_dropdivright_wrap").show();
					$("#jobfuctiontype_dropdivrdiv_" + $(this).attr("v")).show().siblings().hide();
				});
			});
			$("#jobfuctiontype_dropdivright_wrap p").unbind("mouseover").bind("mouseover",function(){
				$(this).find("span").addClass("checkbox_txt_hover");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).find("span").removeClass("checkbox_txt_hover");
			}).unbind("click").bind("click",function(){
				//选择此大类所有子类
				if($(this).find("span").hasClass("checkbox_txt_checked")){
					if(handleFunction(this, false)){
						$(this).find("span").removeClass("checkbox_txt_checked").removeClass("bold");
					}
				}else{
					$(this).parent().find("li").each(function(){
						if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
							if(handleFunction(this, false)){
								$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
							}
						}
					});
					if(handleFunction(this, true)){
						$(this).find("span").addClass("checkbox_txt_checked").addClass("bold");
					}
				}
			});
			$("#jobfuctiontype_dropdivright_wrap li").unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			}).unbind("click").bind("click",function(){
				$(this).parent().parent().find("p span").removeClass("checkbox_txt_checked").removeClass("bold");
				//选择子类
				if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
					if(handleFunction(this, false)){
						$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
					}
				}else{
					if(handleFunction($(this).parent().parent().find("p"), false) && handleFunction(this, true)){
						$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold");
					}
				}
			});
		}else if($(this).attr("id")=="tradetype"){
			$(this).find(".icon_jobstype").removeClass("icon_hy").addClass("icon_hy_ac");
			$("#tradetype_dropdivselected_ok").unbind("click").bind("click",function(){
				$(objId).removeClass("common_group_focus");
				$(objId).find(".icon_deaultarrow").removeClass("icon_deaultarrow_selected");
				$(objId).find(".selected_con").removeClass("c_79c9ee").addClass("c_8c8c8c");
				$(objId).find(".icon_jobstype").removeClass("icon_hy_ac").addClass("icon_hy");
				$(objId).find(".common_choose_layer_wrapper").fadeOut("fast");
				return false;
			});
			$("#tradetype_dropdivleft_scroll p").unbind("click").bind("click",function(){
				$("#tradetype_dropdivright_tip").show();
				$("#tradetype_dropdivright_wrap").hide();
				$("#tradetype_dropdivleft_scroll li").each(function(){
					$(this).removeClass("selected");
				});
				$("#tradetype_dropdivright_wrap li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$("#tradetype_dropdivright_wrap p span").each(function(){
					$(this).removeClass("checkbox_txt_checked").removeClass("bold");
				});
				$("#tradetype_dropdivselected_group .selected_box").each(function(){
					$(this).remove();
				});
				$(this).find("span").addClass("checkbox_txt_checked").addClass("bold");
				$("#tradetype_dropdivselected_div").hide();
				$("#tradetype_txt").text("全部岗位");
				$("#tradetype_txt").parent().find(".countnumber").hide().text("");
				$("#tradeHidden").val("");
			});
			$("#tradetype_dropdivleft_scroll li").unbind("click").bind("click",function(){
				$("#tradetype_dropdivright_tip").hide();
				$(this).addClass("selected").siblings().removeClass("selected");
				$("#tradetype_dropdivright_wrap li").each(function(){
					$(this).removeClass("selected");
				});
				$("#tradetype_dropdivright_wrap").show();
				$("#tradetype_dropdivrdiv_" + $(this).attr("v")).show().siblings().hide();
			});
			$("#tradetype_dropdivright_wrap p").unbind("mouseover").bind("mouseover",function(){
				$(this).find("span").addClass("checkbox_txt_hover");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).find("span").removeClass("checkbox_txt_hover");
			}).unbind("click").bind("click",function(){
				//选择此大类所有子类
				if($(this).find("span").hasClass("checkbox_txt_checked")){
					if(handleTrade(this, false)){
						$(this).find("span").removeClass("checkbox_txt_checked").removeClass("bold");
					}
				}else{
					$(this).parent().find("li").each(function(){
						if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
							if(handleTrade(this, false)){
								$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
							}
						}
					});
					if(handleTrade(this, true)){
						$(this).find("span").addClass("checkbox_txt_checked").addClass("bold");
					}
				}
			});
			$("#tradetype_dropdivright_wrap li").unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			}).unbind("click").bind("click",function(){
				$(this).parent().parent().find("p span").removeClass("checkbox_txt_checked").removeClass("bold");
				//选择子类
				if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
					if(handleTrade(this, false)){
						$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
					}
				}else{
					if(handleTrade($(this).parent().parent().find("p"), false) && handleTrade(this, true)){
						$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold");
					}
				}
			});
		}else if($(this).attr("id")=="locationtype"){
			$(this).find(".icon_jobstype").removeClass("icon_dq").addClass("icon_dq_ac");
			$("#locationtype_dropdivselected_ok").unbind("click").bind("click",function(){
				$(objId).removeClass("common_group_focus");
				$(objId).find(".icon_deaultarrow").removeClass("icon_deaultarrow_selected");
				$(objId).find(".selected_con").removeClass("c_79c9ee").addClass("c_8c8c8c");
				$(objId).find(".icon_jobstype").removeClass("icon_dq_ac").addClass("icon_dq");
				$(objId).find(".common_choose_layer_wrapper").fadeOut("fast");
				return false;
			});
			$("#locationtype_dropdivleft_scroll p").unbind("click").bind("click",function(){
				$("#locationtype_dropdivright_tip").show();
				$("#locationtype_dropdivright_wrap").hide();
				$("#locationtype_dropdivleft_scroll li").each(function(){
					$(this).removeClass("selected");
				});
				$("#locationtype_dropdivright_wrap li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$("#locationtype_dropdivright_wrap p span").each(function(){
					$(this).removeClass("checkbox_txt_checked").removeClass("bold");
				});
				$("#locationtype_dropdivselected_group .selected_box").each(function(){
					$(this).remove();
				});
				$(this).find("span").addClass("checkbox_txt_checked").addClass("bold");
				$("#locationtype_dropdivselected_div").hide();
				$("#locationtype_txt").text("意向地区");
				$("#locationtype_txt").parent().find(".countnumber").hide().text("");
				$("#locationHidden").val("");
			});
			$("#locationtype_dropdivleft_scroll li").unbind("click").bind("click",function(){
				$("#locationtype_dropdivright_tip").hide();
				$(this).addClass("selectedlocation").siblings().removeClass("selectedlocation");
				$("#locationtype_dropdivright_wrap").show();
				$("#locationtype_dropdivrdiv_" + $(this).attr("v")).show().siblings().hide();
			});
			$("#locationtype_dropdivright_wrap p").unbind("mouseover").bind("mouseover",function(){
				$(this).find("span").addClass("checkbox_txt_hover");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).find("span").removeClass("checkbox_txt_hover");
			}).unbind("click").bind("click",function(){
				//选择此大类所有子类
				if($(this).find("span").hasClass("checkbox_txt_checked")){
					if(handleLocation(this, false)){
						$(this).find("span").removeClass("checkbox_txt_checked").removeClass("bold");
					}
				}else{
					$(this).parent().find("li").each(function(){
						if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
							if(handleLocation(this, false)){
								$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
							}
						}
					});
					if(handleLocation(this, true)){
						$(this).find("span").addClass("checkbox_txt_checked").addClass("bold");
					}
				}
			});
			$("#locationtype_dropdivright_wrap li").unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			}).unbind("click").bind("click",function(){
				$(this).parent().parent().find("p span").removeClass("checkbox_txt_checked").removeClass("bold");
				//选择子类
				if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
					if(handleLocation(this, false)){
						$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
					}
				}else{
					if(handleLocation($(this).parent().parent().find("p"), false) && handleLocation(this, true)){
						$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold");
					}
				}
			});
		}else if($(this).attr("id")=="flatype"){
			$(this).find(".icon_jobstype").removeClass("icon_hy").addClass("icon_hy_ac");
			$("#flatype_dropdivselected_ok").unbind("click").bind("click",function(){
				$(objId).removeClass("common_group_focus");
				$(objId).find(".icon_deaultarrow").removeClass("icon_deaultarrow_selected");
				$(objId).find(".selected_con").removeClass("c_79c9ee").addClass("c_8c8c8c");
				$(objId).find(".icon_jobstype").removeClass("icon_hy_ac").addClass("icon_hy");
				$(objId).find(".common_choose_layer_wrapper").fadeOut("fast");
				return false;
			});
			$("#flatype_dropdivleft_scroll p").unbind("click").bind("click",function(){
				$("#flatype_dropdivright_tip").show();
				$("#flatype_dropdivright_wrap").hide();
				$("#flatype_dropdivleft_scroll li").each(function(){
					$(this).removeClass("selected");
				});
				$("#flatype_dropdivright_wrap li span").each(function(){
					$(this).removeClass("checkbox_txt_checked_h28").removeClass("bold");
				});
				$("#flatype_dropdivright_wrap p span").each(function(){
					$(this).removeClass("checkbox_txt_checked").removeClass("bold");
				});
				$("#flatype_dropdivselected_group .selected_box").each(function(){
					$(this).remove();
				});
				$(this).find("span").addClass("checkbox_txt_checked").addClass("bold");
				$("#flatype_dropdivselected_div").hide();
				$("#flatype_txt").text("自定义分类");
				$("#flatype_txt").parent().find(".countnumber").hide().text("");
				$("#flaHidden").val("");
			});
			$("#flatype_dropdivleft_scroll li").unbind("click").bind("click",function(){
				$("#flatype_dropdivright_tip").hide();
				$(this).addClass("selected").siblings().removeClass("selected");
				$("#flatype_dropdivright_wrap li").each(function(){
					$(this).removeClass("selected");
				});
				$("#flatype_dropdivright_wrap").show();
				$("#flatype_dropdivrdiv_" + $(this).attr("v")).show().siblings().hide();
			});
			$("#flatype_dropdivright_wrap p").unbind("mouseover").bind("mouseover",function(){
				$(this).find("span").addClass("checkbox_txt_hover");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).find("span").removeClass("checkbox_txt_hover");
			}).unbind("click").bind("click",function(){
				//选择此大类所有子类
				if($(this).find("span").hasClass("checkbox_txt_checked")){
					if(handleFla(this, false)){
						$(this).find("span").removeClass("checkbox_txt_checked").removeClass("bold");
					}
				}else{
					$(this).parent().find("li").each(function(){
						if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
							if(handleFla(this, false)){
								$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
							}
						}
					});
					if(handleFla(this, true)){
						$(this).find("span").addClass("checkbox_txt_checked").addClass("bold");
					}
				}
			});
			$("#flatype_dropdivright_wrap li").unbind("mouseover").bind("mouseover",function(){
				$(this).addClass("hover").siblings().removeClass("hover");
				$(this).find("span").addClass("checkbox_txt_hover_h28");
			}).unbind("mouseout").bind("mouseout",function(){
				$(this).removeClass("hover");
				$(this).find("span").removeClass("checkbox_txt_hover_h28");
			}).unbind("click").bind("click",function(){
				$(this).parent().parent().find("p span").removeClass("checkbox_txt_checked").removeClass("bold");
				//选择子类
				if($(this).find("span").hasClass("checkbox_txt_checked_h28")){
					if(handleFla(this, false)){
						$(this).find("span").removeClass("checkbox_txt_checked_h28").removeClass("bold");
					}
				}else{
					if(handleFla($(this).parent().parent().find("p"), false) && handleFla(this, true)){
						$(this).find("span").addClass("checkbox_txt_checked_h28").addClass("bold");
					}
				}
			});
		}
		$(this).find(".common_choose_layer_wrapper").slideDown("fast");
		$("body").unbind("mousedown").bind("mousedown", clearResumeSearchDiv);
	});
}

function clearResumeSearchDiv(event){
	var layers = ["jobfuctiontype","tradetype","locationtype","education","sexlayer","sortlayer","flatype","ownerlayer"];
	for(var i=0;i<layers.length;i++){
		if(!(event.target.id == layers[i] || $(event.target).parents("#"+layers[i]).length>0)){
			var objId = "#" + layers[i];
			$(objId).removeClass("common_group_focus");
			$(objId).find(".icon_deaultarrow").removeClass("icon_deaultarrow_selected");
			$(objId).find(".selected_con").removeClass("c_79c9ee").addClass("c_8c8c8c");
			switch (layers[i]) {
				case "jobfuctiontype":
					$(objId).find(".icon_jobstype").removeClass("icon_gw_ac").addClass("icon_gw");
					break;
				case "tradetype":
					$(objId).find(".icon_jobstype").removeClass("icon_hy_ac").addClass("icon_hy");
					break;
				case "locationtype":
					$(objId).find(".icon_jobstype").removeClass("icon_dq_ac").addClass("icon_dq");
					break;
				case "education":
					$(objId).find(".icon_jobstype").removeClass("icon_xl_ac").addClass("icon_xl");
					break;
				case "flatype":
					$(objId).find(".icon_jobstype").removeClass("icon_hy_ac").addClass("icon_hy");
					break;
			}
			$(objId).find(".common_choose_layer_wrapper").fadeOut("fast");
		}
	}
}