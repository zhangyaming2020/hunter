//$.noConflict();

$(document).ready(function(){
	
	/**
	 * Message Notify Drop Down
	**/
	$('.messagenotify .wrap, .friendnotify .wrap, .tasknotify .wraptask, .servicenotify .wraptask').click(function(){
		var t = $(this).parent();
		var url = t.attr('href');
		if(t.hasClass('showmsg')) {
			t.removeClass('showmsg');
			t.find('.thicon').removeClass('thiconhover');
			t.parent().find('.dropbox').remove();
		} else {
			
			$('.topheader li').each(function(){
				$(this).find('.showmsg').removeClass('showmsg');
				$(this).find('.thicon').removeClass('thiconhover');
				$(this).find('.dropbox').remove();
			});
			
			t.addClass('showmsg');
			t.find('.thicon').addClass('thiconhover');
			t.parent().append('<div class="dropbox"></div>');
			var dropboxIndex=1800;
			var list = artDialog.list;
			for (var i in list) {
				if (list[i]) {
					if(list[i].config.zIndex > dropboxIndex){
						dropboxIndex = list[i].config.zIndex + 1;
					}
				};
			};
			$('.dropbox').css("z-index",dropboxIndex);
			if(url=="dataTop"){
				$('.dropbox').css("width","360px");
			}
			if(url=="plugin-service"){
				$('.dropbox').css("width","435px");
			}
			if(url=="onlineUser"){
				$('.dropbox').css("width","200px");
				$('.dropbox').css("height","320px");
				$('.dropbox').css("z-index",8000);
			}
			
			$.get(url+"?rand="+new Date(),function(data){
				if(url=="onlineUser"){
					$('.dropbox').append("<div style='OVERFLOW-Y:auto;'>"+ data +"</div>");
				}else{
					$('.dropbox').append(data);
				}
				if(url=="follow/remind"){
					$('.dropbox').find("li").bind("click",function(){
						var title = $(this).find("a").text();
						var id = $(this).attr("dataId");
						if(title.length>8){
				        	title = title.substring(0,8) + "...";
				        }
						var detailDialog = $.dialog.open("follow/show/" + id,{
							title:	title,
							id:		"follow_right_bottom" + id,
							lock:	false,
							width:	'320px',
							height:	'200px',
							cancelVal: '关闭',
							cancel: true,
							button:[{
								name: '删除',
								callback: function () {
						        	var _confirm = $.dialog.confirm('你确认要删除此日历吗？', function(){
						        		$.post("follow/delete/"+ id +"?rand="+new Date(), function(result){
						        			if(result=='success'){
						        				clearMessageNum();
						        				_confirm.close();
				        						detailDialog.close();
						        			}else{
						        				$.dialog.alert(result);
						        				_confirm.close();
						        			}
						        		})
								    	return false;
									});
									return false;
					            }
					         	},
					            {
								name: '已处理',
								callback: function () {
						        	$.post("follow/state/"+ id +"?rand="+new Date(), function(result){
					        			if(result=='success'){
					        				clearMessageNum();
			        						detailDialog.close();
					        			}else{
					        				$.dialog.alert(result);
					        			}
					        		})
							    	return false;
					            }
							}]
						});
						return false;
					});
					$('.dropbox .link').find("a").bind("click",function(){
						$("#calendarLink").click();
					});
				}
			});
		}
		return false;
		
	});
	
	$(".notebutton a").click(function(){
		return false;
	});
	
	
	/**
	 * Login form validation
	**/
	$('#loginform').submit(function(){
		var username = $('.username').val();
		var password = $('.password').val();
		if(username == '' && password == '') {
			$('.loginNotify').slideDown('fast');	
			return false;
		} else {
			return true;
		}
	});
	
	
	/**
	 * Widget Box Toggle
	**/
	$('.widgetbox h3, .widgetbox2 h3').hover(function(){
		$(this).addClass('arrow');
		return false;
	},function(){
		$(this).removeClass('arrow');
		return false;
	});
	
	$('.widgetbox h3, .widgetbox2 h3').toggle(function(){
		$(this).next().slideUp('fast');
		$(this).css({MozBorderRadius: '3px', 
						  WebkitBorderRadius: '3px',
						  borderRadius: '3px'});
		return false;
	},function(){
		$(this).next().slideDown('fast');
		$(this).css({MozBorderRadius: '3px 3px 0 0', 
						  WebkitBorderRadius: '3px 3px 0 0',
						  borderRadius: '3px 3px 0 0'});
		return false;
	});
	
	
	/**
	 * Notification
	**/
	$('.notification .close').click(function(){
		$(this).parent().fadeOut();
	});	
	
	
	/** Make footer always at the bottom**/
	if($('body').height() > $(window).height()) {
		$('.footer').removeClass('footer_float');
	}
	
	
	
	/**DROP DOWN MENU**/
	$(".subnav").css({display: "none"}); // Opera Fix
	$(".tabmenu li").hover(function(){
		$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
	},function(){
		$(this).find('ul:first').css({visibility: "hidden"});
	});
	
	
	/**
	���ܵ�������
	**/
	
	$(".tabmenu li").click(function(){
		var t = $(this).find("a").attr("href");
		var m;
		var selectClick = true;
		//隐藏已经打开的窗口
		var list = artDialog.list;
		for (var i in list) {
			if (list[i]) {
				if(list[i].config.lock === false){
					list[i].hide();
				}
			};
		};
		if($(this).parent().attr("class")=='subnav'){
			$(this).parent().parent().addClass("current").siblings().removeClass("current");
			$(this).parent().css({visibility: "hidden"});
			m = $(this).parent().parent().attr("menu");
			//selectClick = true;
		}else{
			$(this).addClass("current").siblings().removeClass("current");
			$(this).find("ul").css({visibility: "hidden"});
			m = $(this).attr("menu");
		}
		$('.maincontent').hideLoading();
		$('#loading').showLoading();
		$('#loading').hideLoading();
		var url;
		if(m.indexOf("?")==-1){
			url = m + "?rand=" + new Date();
		}else{
			url = m + "&rand=" + new Date();
		}
		/*
		$.get(url,function(data){
			$("#accordion").html(data);
			$('#accordion h3').click(function() {
				if($(this).hasClass('open')) {
					$(this).removeClass('open');
					$(this).next().slideUp('fast');
				} else {
					$(this).addClass('open');
					$(this).next().slideDown('fast');
				} return false;
			});
			$('#accordion li').click(function() {
				//隐藏已经打开的窗口
				var list = artDialog.list;
				for (var i in list) {
					if (list[i]) {
						if(list[i].config.lock === false){
							list[i].hide();
						}
					};
				};
				var lt = $(this).find("a").attr("href");
				$('#accordion div h3').each(function(){
					$(this).removeClass('open');
					$(this).parent().find(".leftmenu").css("display","none");
				});
				$('#accordion li').siblings().removeClass("current");
				$(this).addClass("current");
				$(this).parent().parent().find("h3").addClass('open').next();
				$(this).parent().css("display","block");
				//$('#accordion h3').siblings().removeClass("open");
				//$(this).parent().parent().find("h3").addClass('open');
				//$('#accordion .leftmenu').siblings().css("display","none");
				//$(this).parent().css("display","block");
				
				$('.maincontent').hideLoading();
				$('.maincontent').showLoading();
				
				if(lt.indexOf("?")==-1){
					url = lt + "?rand=" + new Date();
				}else{
					url = lt + "&rand=" + new Date();
				}
				$.get(url,function(data){
					$(".maincontent").html(data);
					$('.maincontent').hideLoading();
				});
				hideTopPopup();
				return false;
			});	
			
			if(selectClick){
				$('#accordion li').each(function(i){
					var lt = $(this).find("a").attr("href");
					if(t==lt){
						$(this).addClass("current").siblings().removeClass("current");
						$(this).parent().css("display","block");
					}
				});
			}
		});
		*/
		if(t.indexOf("?")==-1){
			url = t + "?rand=" + new Date();
		}else{
			url = t + "&rand=" + new Date();
		}
		if(typeof($(this).attr("keyword"))!="undefined"){
			$.post(url, {keyword:$(this).attr("keyword")}, function(data){
				$(".maincontent").html(data);
				$('#loading').hideLoading();
			});
		}else{
			$.get(url,function(data){
				$(".maincontent").html(data);
				$('#loading').hideLoading();
			});
		}
		hideTopPopup();
		return false;
	});
	
	$(".searchbutton").click(function(){
		if($("#headSearchKeyword").val()==""){
			$("#headSearchKeyword").focus();
			return false;
		}
		$("#resumeLink").attr("keyword",$("#headSearchKeyword").val());
		$("#resumeLink").click();
		return false;
	});
	
	gotoDisktop();
	
	$.get("update/check?rand=" + new Date(),function(result){
		if(result!="0"){
			$("#updatePatchPop").html(result);
			$("#updatePatchPop a").click(function(){
				var mydialog = $.dialog.open("update/detail?rand=" + new Date(),{
				title :	'查看更新',
				width : '550px',
				height:	'350px',
				lock  :	true,
				cancelVal: '关闭',
				cancel: true,
				button:[{
					name: '安装更新',
					callback: function () {
		                openInstall("update");
		                return false;
		            }
				}]
			});
			});
		}
	});
	
	$("#suggestLink").click(function(){
		var mydialog = $.dialog.open("club/suggest/list",{
			title :	"意见建议",
			fixed: true,
			width : '850px',
			height:	'450px',
			id	  : 'userSuggestDiv',
			lock  :	false
		});
	});
	
	$('.notebutton li a').tipsy({fade: true, gravity: 'nw'});
	var emailNum = parseInt($('#emailNum').text());
	if(emailNum>0){
		emailPopup(emailNum);
	}
});

function evalScript(str){
	window.eval(str);
}

function showInstallMsg(msg){
	if(msg!="updatePatch"){
		if(msg.indexOf("error")!=-1){
			$("#installContent").contents().find("#installLogs").append("<p class='error'>"+ msg +"</p>");
			$("#installContent").contents().find("#installLogs").append("<p><input type='button' value=' 关闭 ' onclick='return parent.DialogHide();' /></p>");
		}else if(msg.indexOf("success")!=-1){
			$("#installContent").contents().find("#installLogs").append("<p class='success'>"+ msg +"</p>");
			$("#installContent").contents().find("#installLogs").append("<p><input type='button' value=' 关闭 ' onclick='return parent.DialogHide();' /></p>");
		}else if(msg.indexOf("重启")!=-1){
			$("#installContent").contents().find("#installLogs").append("<p class='success'>"+ msg +"</p>");
			window.setInterval(function(){
				$.get("/serverRestartState?rand" + new Date(),function(result){
					if(result.indexOf("登录")>-1){
						top.location="/login?flag=ot&r="+new Date();
					}
				});
			},1000);
		}else{
			$("#installContent").contents().find("#installLogs").append("<p>"+ msg +"</p>");
		}
		$("#installContent").contents().find("#installLogs").scrollTop(99999999);
		//$("#installContent").contents().find('html,body').animate({scrollTop: $("#installContent").contents().find("#installLogs").height()}, 1000);
	}
}

function showApplyResume(id){
	
	$.dialog.open("resume/apply/show/"+id,{
		title :	'申请详情',
		width : '550px',
		height:	'350px',
		id	:	"resume_apply_show_" + id,
		lock  :	false,
		cancelVal: '关闭',
		cancel: true
	});
}

function portalResumeShow(id){
	var detailDialog = $.dialog.open("project/portal/resumeShow/" + id,{
		title:	"外投简历详细",
		id:		"portal_resume_" + id,
		lock:	false,
		fixed: true,
		width:	'820px',
		height:	'500px'
	});
	return false;
}

function projectReportDetailPop(id){
	var detailDialog = $.dialog.open("project/report/show/" + id,{
		title:	"推荐报告",
		id:		"project_report_" + id,
		lock:	false,
		fixed: true,
		width:	'860px',
		height:	'500px'
	});
	return false;
}

function hideTopPopup(){
	
}

function showPluginService(alias, id){
	var mydialog = $.dialog.open("showPluginServices/"+ id +"?rand=" + new Date(),{
		id:'pluginservice_' + alias,
		fixed: true,
		title :	'插件与服务详情',
		width : '650px',
		height:	'550px',
		lock  :	true
	});
}

function showLogs(){
	$.dialog.open("userlogs/main?rand=" + new Date(),{
		id:'user_login_logs',
		fixed: true,
		title :	'登录日志',
		width : '720px',
		height:	'450px',
		lock  :	false
	});
}

function gotoEmailCenter(){
	$('#emailLink').click();
	var list = artDialog.list;
	for (var i in list) {
		if (list[i]) {
			if(list[i].config.id=='email_popup'){
				list[i].close();
			}
		};
	};
}

function emailPopup(num){
	$.dialog({
		content: '<a href="javascript:;" onClick="gotoEmailCenter();">您有 '+ num +' 封未读邮件，点击查看</a>',
		id: 'email_popup',
		title: '您有新的未读邮件',
		width: '250px',
		height: '100px',
		left: '100%',
		top: '100%',
		fixed: true,
		lock :false,
		resize: false
	});
}

function moreBraph(){
	$('.topheader li').each(function(){
		$(this).find('.showmsg').removeClass('showmsg');
		$(this).find('.thicon').removeClass('thiconhover');
		$(this).find('.dropbox').remove();
	});
	$("#reports").click();
}

function gotoDisktop(){
	$(".tabmenu li").eq(0).click();
}

function pageReload(){
	$('#accordion li.current').click();
}