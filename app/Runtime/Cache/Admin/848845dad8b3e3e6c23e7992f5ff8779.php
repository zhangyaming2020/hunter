<?php if (!defined('THINK_PATH')) exit();?><!--添加管理员-->
<div class="dialog_content form_default"  style="padding:0; height:458px; OVERFLOW-Y: auto; BORDER: #ccc 1px solid;">
	<form id="info_form" name="info_form" action="<?php echo U('resume/add');?>" method="post">
	<body style="background:#fff;padding:10px;">
			<table border="0" cellpaddin海g="0" style="margin-top:20px;" cellspacing="0" width="100%" class="rTable" align="left">
				<input type="hidden" name="resuId" value="0">
				<input type="hidden" name="pjId" value="undefined">
	
				<input type="hidden" name="sex" id="sexHidden" value="0">
				<input type="hidden" name="fuctions" id="fuctionHidden" value="">
				<input type="hidden" name="trades" id="tradeHidden" value="">
				<input type="hidden" name="locations" id="locationHidden" value="">
				<input type="hidden" name="educations" id="educationHidden" value="0">
				<input type="hidden" name="fla" id="flaHidden" value="">
				<input type="hidden" name="project_id" id="prtaContactIds" value="<?php echo ($project_id); ?>">
			
				<tbody>
					<tr>
						<td align="center" class="ri">基本资料</td>
						<td style="width:720px;">
							<div class="common_group relative clearfixq" id="resuTags_div" style="width:628px;">
								<input class="common_keywordsinput c_8c8c8c" name="resuTags" id="resuTags" style="width:98%;" type="text" value="当前职位、简历标题、关键字、自定义标签等">
							</div>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<div class="common_group w194 f_l mr_10 relative clearfixq" id="resuName_div">
								<input class="common_keywordsinput c_8c8c8c w183 f_l" name="resuName" id="resuName" type="text" value="姓名">
							</div>
							<div class="common_group w201 f_l mr_10 clearfixq pointer" id="locationtype">
								<em class="icon_jobstype icon_dq f_l"></em>
								<div class="centercon w155 clearfixq f_l">
									<span class="selected_con c_8c8c8c ellipsis pointer w70" id="locationtype_txt">意向地区</span>
									<span class="countnumber countnumber_hide t_r f_r"></span>
								</div>
								<em class="icon_deaultarrow f_r"></em>
								<div class="common_choose_layer_wrapper w314" style="display: none;">
									<div class="common_choose_layer clearfix">
										<div class="common_choose_layer_arrow"></div>
										<div class="w310 clearfix">
											<div class="selected_group_box clearfix" id="locationtype_dropdivselected_div" style="display: none;">
												<div class="new_okbtn_box f_r">
													<a class="new_commonbtn_02" id="locationtype_dropdivselected_ok" href="javascript:;">确定</a>
												</div>
												<div class="selected_group" id="locationtype_dropdivselected_group">
	
													<p class="max_selected hide inline_block f_r">(最多可选5项)</p>
												</div>
											</div>
											<div class="clearfix">
												<div class="tips_masklayer tips_masklayer_cities" id="locationtype_dropdivright_tip" style="left:160px; top:30px; width:120px;">
													<div class="bg"></div><span>选择地区</span></div>
												<div class="chose_citiestype_left_wrap f_l clearfix" id="locationtype_dropdivleft_wrap">
													<div class="chose_posttype_left" style="position:relative; padding: 0px; height:360px;  overflow-x: hidden; overflow-y: auto;">
														<div tabindex="0" class="clearfix" id="locationtype_dropdivleft_scroll" hidefocus="">
															<p class="posttype_level_one_all posttype_level_one_allcities clearfix">
																<span class="checkbox_txt checkbox_txt_checked bold">全部地区</span>
															</p>
															<ul class="posttype_level_one type_level_one">
																<?php if(is_array($place['province'])): $i = 0; $__LIST__ = $place['province'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li id="locationtype_dropdivlli_<?php echo ($val["id"]); ?>" p="0" v="<?php echo ($val["id"]); ?>">
																		<a class="common_alist" href="javascript:;"><?php echo ($val["name"]); ?></a>
																	</li><?php endforeach; endif; else: echo "" ;endif; ?>
															</ul>
														</div>
													</div>
												</div>
												<!-- 2 -->
												<div class="chose_posttype_right chose_citiestype_right posttype_level_two_box f_l chose_bgc_change" id="locationtype_dropdivright_wrap" style="display: none; height:360px; overflow-x: hidden; overflow-y: auto;">
													<div id="locationtype_dropdivright_list">
													
														<?php if(is_array($place['province'])): $i = 0; $__LIST__ = $place['province'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="hide clearfix" class="<?php echo ($val["id"]); ?>" id="locationtype_dropdivrdiv_<?php echo ($val["id"]); ?>" v="<?php echo ($val["id"]); ?>" style="display: none;">
																<div class="clearfix">
																	<p class="chose_right_all clearfix" id="locationtype_classId_<?php echo ($val["id"]); ?>" v="<?php echo ($val["id"]); ?>" p="<?php echo ($val["id"]); ?>" title="<?php echo ($val["name"]); ?>">
																		<span class="checkbox_txt"><?php echo ($val["name"]); ?></span>
																	</p>
																	<ul class="chose_right_list">
																		<?php if(is_array($city[$val['id']])): $i = 0; $__LIST__ = $city[$val['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="clear" id="locationtype_classId_<?php echo ($v["id"]); ?>" title="<?php echo ($v["name"]); ?>" v="<?php echo ($v["id"]); ?>" p="<?php echo ($v["pid"]); ?>">
																				<span class="checkbox_txt checkbox_txt_h28"><?php echo ($v["name"]); ?></span>
																			</li><?php endforeach; endif; else: echo "" ;endif; ?>
													
																	</ul>
																</div>
															</div><?php endforeach; endif; else: echo "" ;endif; ?>
													
													</div>
												</div>
												<!-- 2 end -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="common_group w202 f_l mr_10 clearfixq pointer" id="education">
								<em class="icon_jobstype icon_xl f_l"></em>
								<div class="centercon w155 clearfixq f_l">
									<span class="selected_con c_8c8c8c w141 ellipsis" id="education_txt">学历</span>
									<span class="countnumber countnumber_hide t_r f_r"></span>
								</div>
								<em class="icon_deaultarrow f_r"></em>
								<!-- common_choose_layer start -->
								<div class="common_choose_layer_wrapper w206 hide" style="display: none;">
									<div class="common_choose_layer clearfixq">
										<div class="common_choose_layer_arrow common_choose_layer_arrow02"></div>
										<div class="w202 clearfixq">
											<div class="all_level_children">
												<ul class="clearfixq" id="xl_children">
													<?php if(is_array($educate)): $i = 0; $__LIST__ = $educate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v): ?><li class="clear">
														<span val="<?php echo ($i); ?>" class="checkbox_txt checkbox_txt_h28 "><?php echo ($v); ?></span>
													</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	
												</ul>
											</div>
										</div>
									</div>
								</div>
								<!-- common_choose_layer end -->
							</div>
							<div class="common_group w86 f_l mr_10 clearfixq pointer" id="sexlayer">
								<div class="centercon clearfixq f_l">
									<span class="selected_con c_8c8c8c ellipsis" id="sex_txt">性别</span>
								</div>
								<em class="icon_deaultarrow f_r"></em>
								<!-- common_choose_layer start -->
								<div class="common_choose_layer_wrapper w92" style="display: none;">
									<div class="common_choose_layer clearfixq">
										<div class="common_choose_layer_arrow common_choose_layer_arrow02"></div>
										<div class="w86 clearfixq">
											<div class="all_level_children" id="sex_children">
												<ul class="clearfixq">
													<li class="clear"><span val="0" class="checkbox_txt checkbox_txt_h28 ">女</span></li>
													<li class="clear"><span val="1" class="checkbox_txt checkbox_txt_h28 ">男</span></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<!-- common_choose_layer end -->
							</div>
							<div class="common_group w92 f_l mr_10 relative clearfixq" id="resuBirthdayStr_div">
								<input class="common_keywordsinput c_8c8c8c f_l" name="resuBirthdayStr" id="resuBirthdayStr" type="text" value="出生年份" style="width:78px;">
							</div>
							<div class="common_group w201 f_l mr_10 clearfixq pointer" id="tradetype">
								<em class="icon_jobstype icon_hy f_l"></em>
								<div class="centercon w155 clearfixq f_l">
									<span class="selected_con c_8c8c8c w74 ellipsis" id="tradetype_txt">所属行业</span>
									<span class="countnumber countnumber_hide t_r f_r"></span>
								</div>
								<em class="icon_deaultarrow f_r"></em>
								<div class="common_choose_layer_wrapper w424" style="display: none;">
									<div class="common_choose_layer clearfix">
										<div class="common_choose_layer_arrow"></div>
										<div class="w420 clearfix">
											<div class="selected_group_box clearfix" id="tradetype_dropdivselected_div" style="display: none;">
												<div class="new_okbtn_box f_r">
													<a class="new_commonbtn_02" id="tradetype_dropdivselected_ok" href="javascript:;">确定</a>
												</div>
												<div class="selected_group" id="tradetype_dropdivselected_group">
	
													<p class="max_selected hide inline_block f_r">(最多可选5项)</p>
												</div>
											</div>
											<div class="columns_chosebox relative clearfix">
												<div class="tips_masklayer" id="tradetype_dropdivright_tip">
													<div class="bg"></div><span>选择行业</span></div>
												<div class="chose_left_wrap clearfix" id="tradetype_dropdivleft_wrap">
													<div class="chose_posttype_left" style="position:relative;">
														<div tabindex="0" class="scroll-pane clearfix jspScrollable" id="tradetype_dropdivleft_scroll" style="padding: 0px; width: 211px; overflow: hidden;" hidefocus="">
															<p class="posttype_level_one_all posttype_level_one_allcities clearfix">
																<span class="checkbox_txt checkbox_txt_checked bold">全部行业</span>
															</p>
															<ul class="posttype_level_one type_level_one">
	
																
																<?php if(is_array($job)): $i = 0; $__LIST__ = $job;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li id="tradetype_dropdivlli_<?php echo ($val["id"]); ?>" p="0" v="<?php echo ($val["id"]); ?>">
																	<a class="common_alist" href="javascript:;"><?php echo ($val["name"]); ?></a>
																	</li><?php endforeach; endif; else: echo "" ;endif; ?>
	
															</ul>
														</div>
													</div>
												</div>
												<!-- 2 -->
												<div class="chose_right_wrap chose_right_items clearfix" id="tradetype_dropdivright_wrap" style="display: none; overflow-x: hidden; overflow-y: auto;">
													<div id="tradetype_dropdivright_list">
														<?php if(is_array($job)): $i = 0; $__LIST__ = $job;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><div class="hide clearfix" style="clear:none !important;" id="tradetype_dropdivrdiv_<?php echo ($val["id"]); ?>" v="<?php echo ($val["id"]); ?>" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="tradetype_classId_<?php echo ($val["id"]); ?>" v="<?php echo ($val["id"]); ?>" p="<?php echo ($val["id"]); ?>" title="<?php echo ($val["name"]); ?>">
																	<span class="checkbox_txt"><?php echo ($val["name"]); ?></span>
																</p>
																<ul class="chose_right_list">
																	<?php if(is_array($job_sel[$val['id']])): $i = 0; $__LIST__ = $job_sel[$val['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="clear" id="tradetype_classId_<?php echo ($v["id"]); ?>" title="<?php echo ($v["name"]); ?>" v="<?php echo ($v["id"]); ?>" p="<?php echo ($v["pid"]); ?>">
																		<span class="checkbox_txt checkbox_txt_h28"><?php echo ($v["name"]); ?></span>
																	</li><?php endforeach; endif; else: echo "" ;endif; ?>
																</ul>
															</div>
														</div><?php endforeach; endif; else: echo "" ;endif; ?>
														
													</div>
												</div>
												<!-- 2 end -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="common_group w201 f_l mr_10 relative clearfixq" id="resuCurCompany_div">
								<input class="common_keywordsinput c_8c8c8c w183 f_l" name="resuCurCompany" id="resuCurCompany" type="text" value="当前公司">
							</div>
						</td>
					</tr>
					<tr>
						<td width="100" align="right">&nbsp;</td>
						<td>
							<div class="common_group w310 f_l mr_10 clearfixq pointer" id="jobfuctiontype">
								<em class="icon_jobstype icon_gw f_l"></em>
								<div class="centercon w264 clearfixq f_l">
									<span class="selected_con c_8c8c8c ellipsis pointer w183" id="jobfuctiontype_txt">所属岗位</span>
									<span class="countnumber countnumber_hide t_r f_r"></span>
								</div>
								<em class="icon_deaultarrow f_r"></em>
								<div class="common_choose_layer_wrapper w640" id="jobfuctiontype_dropdiv" style="display: none;">
									<div class="common_choose_layer clearfix">
										<div class="common_choose_layer_arrow"></div>
										<div class="w636 clearfix">
											<div class="selected_group_box clearfix" id="jobfuctiontype_dropdivselected_div" style="display: none;">
												<div class="new_okbtn_box f_r">
													<a class="new_commonbtn_02" id="jobfuctiontype_dropdivselected_ok" href="javascript:;">确定</a>
												</div>
												<div class="selected_group" id="jobfuctiontype_dropdivselected_group">
	
													<p class="max_selected hide inline_block f_r">(最多可选5项)</p>
												</div>
											</div>
											<div class="columns_chosebox relative clearfix" style="display:block;">
												<div class="tips_masklayer" id="jobfuctiontype_dropdivright_tip">
													<div class="bg"></div><span>选择岗位</span></div>
												<div class="chose_left_wrap clearfix" id="jobfuctiontype_dropdivleft_wrap">
													<div class="chose_posttype_left" style="position:relative;">
														<div tabindex="0" class="scroll-pane clearfix jspScrollable" id="jobfuctiontype_dropdivleft_scroll" style="padding: 0px; width: 211px; overflow: hidden;" hidefocus="">
															<p class="posttype_level_one_all posttype_level_one_allcities clearfix">
																<span class="checkbox_txt checkbox_txt_checked bold">全部岗位</span>
															</p>
															<ul class="posttype_level_one type_level_one">
	
																<li id="jobfuctiontype_dropdivlli_2161" p="0" v="2161">
																	<a class="common_alist" href="javascript:;">计算机/互联网/通信/电子</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2232" p="0" v="2232">
																	<a class="common_alist" href="javascript:;">广告/市场/媒体/艺术</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2284" p="0" v="2284">
																	<a class="common_alist" href="javascript:;">服务业</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2329" p="0" v="2329">
																	<a class="common_alist" href="javascript:;">销售/客服/技术支持</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2367" p="0" v="2367">
																	<a class="common_alist" href="javascript:;">生产/营运/采购/物流</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2530" p="0" v="2530">
																	<a class="common_alist" href="javascript:;">公务员/翻译/其他</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2549" p="0" v="2549">
																	<a class="common_alist" href="javascript:;">会计/金融/银行/保险</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2595" p="0" v="2595">
																	<a class="common_alist" href="javascript:;">生物/制药/医疗/护理</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2620" p="0" v="2620">
																	<a class="common_alist" href="javascript:;">建筑/房地产</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2659" p="0" v="2659">
																	<a class="common_alist" href="javascript:;">人事/行政/高级管理</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2697" p="0" v="2697">
																	<a class="common_alist" href="javascript:;">咨询/法律/教育/科研</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2786" p="0" v="2786">
																	<a class="common_alist" href="javascript:;">橡胶工艺工程师</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2818" p="0" v="2818">
																	<a class="common_alist" href="javascript:;">dddddd</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2820" p="0" v="2820">
																	<a class="common_alist" href="javascript:;">森</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2829" p="0" v="2829">
																	<a class="common_alist" href="javascript:;">Test</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2834" p="0" v="2834">
																	<a class="common_alist" href="javascript:;">建筑工程</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2836" p="0" v="2836">
																	<a class="common_alist" href="javascript:;">任溶溶</a>
																</li>
	
																<li id="jobfuctiontype_dropdivlli_2882" p="0" v="2882">
																	<a class="common_alist" href="javascript:;">电力总工</a>
																</li>
	
															</ul>
														</div>
													</div>
												</div>
												<!-- 2 -->
												<div class="chose_posttype_right chose_center_wrap posttype_level_two_group clearfix" id="jobfuctiontype_dropdivcenter_wrap">
													<div id="jobfuctiontype_dropdivcenter_list">
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2161" v="2161" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2167" p="2161" v="2167">
																	<a class="common_alist" href="javascript:;"><span title="计算机软件类" class="fixedwidth_item ellipsis">计算机软件类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2176" p="2161" v="2176">
																	<a class="common_alist" href="javascript:;"><span title="信息技术管理/集成/运行维护类" class="fixedwidth_item ellipsis">信息技术管理/集成/运行维护类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2191" p="2161" v="2191">
																	<a class="common_alist" href="javascript:;"><span title="互联网/网络应用类" class="fixedwidth_item ellipsis">互联网/网络应用类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2203" p="2161" v="2203">
																	<a class="common_alist" href="javascript:;"><span title="电子/电器类" class="fixedwidth_item ellipsis">电子/电器类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2845" p="2161" v="2845">
																	<a class="common_alist" href="javascript:;"><span title="一级建造师" class="fixedwidth_item ellipsis">一级建造师</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2870" p="2161" v="2870">
																	<a class="common_alist" href="javascript:;"><span title="测试" class="fixedwidth_item ellipsis">测试</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2889" p="2161" v="2889">
																	<a class="common_alist" href="javascript:;"><span title="电商" class="fixedwidth_item ellipsis">电商</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2232" v="2232" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2233" p="2232" v="2233">
																	<a class="common_alist" href="javascript:;"><span title="设计类" class="fixedwidth_item ellipsis">设计类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2250" p="2232" v="2250">
																	<a class="common_alist" href="javascript:;"><span title="广告类" class="fixedwidth_item ellipsis">广告类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2259" p="2232" v="2259">
																	<a class="common_alist" href="javascript:;"><span title="公关/会展类" class="fixedwidth_item ellipsis">公关/会展类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2265" p="2232" v="2265">
																	<a class="common_alist" href="javascript:;"><span title="影视类" class="fixedwidth_item ellipsis">影视类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2275" p="2232" v="2275">
																	<a class="common_alist" href="javascript:;"><span title="文字媒体/写作类" class="fixedwidth_item ellipsis">文字媒体/写作类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2284" v="2284" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2285" p="2284" v="2285">
																	<a class="common_alist" href="javascript:;"><span title="百货/连锁/零售类" class="fixedwidth_item ellipsis">百货/连锁/零售类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2293" p="2284" v="2293">
																	<a class="common_alist" href="javascript:;"><span title="餐饮/娱乐类" class="fixedwidth_item ellipsis">餐饮/娱乐类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2303" p="2284" v="2303">
																	<a class="common_alist" href="javascript:;"><span title="酒店/旅游类" class="fixedwidth_item ellipsis">酒店/旅游类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2312" p="2284" v="2312">
																	<a class="common_alist" href="javascript:;"><span title="美容/保健类" class="fixedwidth_item ellipsis">美容/保健类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2323" p="2284" v="2323">
																	<a class="common_alist" href="javascript:;"><span title="保安/家政服务类" class="fixedwidth_item ellipsis">保安/家政服务类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2329" v="2329" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2330" p="2329" v="2330">
																	<a class="common_alist" href="javascript:;"><span title="销售管理类" class="fixedwidth_item ellipsis">销售管理类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2339" p="2329" v="2339">
																	<a class="common_alist" href="javascript:;"><span title="销售业务类" class="fixedwidth_item ellipsis">销售业务类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2348" p="2329" v="2348">
																	<a class="common_alist" href="javascript:;"><span title="销售行政/商务类" class="fixedwidth_item ellipsis">销售行政/商务类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2355" p="2329" v="2355">
																	<a class="common_alist" href="javascript:;"><span title="客服/技术支持类" class="fixedwidth_item ellipsis">客服/技术支持类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2367" v="2367" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2368" p="2367" v="2368">
																	<a class="common_alist" href="javascript:;"><span title="市场/营销/推广类" class="fixedwidth_item ellipsis">市场/营销/推广类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2382" p="2367" v="2382">
																	<a class="common_alist" href="javascript:;"><span title="采购类" class="fixedwidth_item ellipsis">采购类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2390" p="2367" v="2390">
																	<a class="common_alist" href="javascript:;"><span title="机械/机器设备/仪器仪表类" class="fixedwidth_item ellipsis">机械/机器设备/仪器仪表类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2414" p="2367" v="2414">
																	<a class="common_alist" href="javascript:;"><span title="质量保证/品质管理类" class="fixedwidth_item ellipsis">质量保证/品质管理类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2427" p="2367" v="2427">
																	<a class="common_alist" href="javascript:;"><span title="生产/制造类" class="fixedwidth_item ellipsis">生产/制造类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2447" p="2367" v="2447">
																	<a class="common_alist" href="javascript:;"><span title="汽车制造/销售/维修/驾培类" class="fixedwidth_item ellipsis">汽车制造/销售/维修/驾培类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2467" p="2367" v="2467">
																	<a class="common_alist" href="javascript:;"><span title="贸易/进出口类" class="fixedwidth_item ellipsis">贸易/进出口类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2477" p="2367" v="2477">
																	<a class="common_alist" href="javascript:;"><span title="物流/仓储/运输类" class="fixedwidth_item ellipsis">物流/仓储/运输类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2496" p="2367" v="2496">
																	<a class="common_alist" href="javascript:;"><span title="服装/纺织/皮革类" class="fixedwidth_item ellipsis">服装/纺织/皮革类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2507" p="2367" v="2507">
																	<a class="common_alist" href="javascript:;"><span title="包装/印刷类" class="fixedwidth_item ellipsis">包装/印刷类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2514" p="2367" v="2514">
																	<a class="common_alist" href="javascript:;"><span title="技工类" class="fixedwidth_item ellipsis">技工类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2530" v="2530" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2531" p="2530" v="2531">
																	<a class="common_alist" href="javascript:;"><span title="翻译类" class="fixedwidth_item ellipsis">翻译类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2543" p="2530" v="2543">
																	<a class="common_alist" href="javascript:;"><span title="其他类" class="fixedwidth_item ellipsis">其他类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2549" v="2549" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2550" p="2549" v="2550">
																	<a class="common_alist" href="javascript:;"><span title="财务/审计/税务类" class="fixedwidth_item ellipsis">财务/审计/税务类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2570" p="2549" v="2570">
																	<a class="common_alist" href="javascript:;"><span title="证券/金融/投资类" class="fixedwidth_item ellipsis">证券/金融/投资类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2582" p="2549" v="2582">
																	<a class="common_alist" href="javascript:;"><span title="银行/保险类" class="fixedwidth_item ellipsis">银行/保险类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2595" v="2595" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2596" p="2595" v="2596">
																	<a class="common_alist" href="javascript:;"><span title="生物/化工/医药类" class="fixedwidth_item ellipsis">生物/化工/医药类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2603" p="2595" v="2603">
																	<a class="common_alist" href="javascript:;"><span title="医院/医疗/护理类" class="fixedwidth_item ellipsis">医院/医疗/护理类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2620" v="2620" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2621" p="2620" v="2621">
																	<a class="common_alist" href="javascript:;"><span title="建筑/工程类" class="fixedwidth_item ellipsis">建筑/工程类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2643" p="2620" v="2643">
																	<a class="common_alist" href="javascript:;"><span title="房地产开发/中介类" class="fixedwidth_item ellipsis">房地产开发/中介类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2651" p="2620" v="2651">
																	<a class="common_alist" href="javascript:;"><span title="物业管理类" class="fixedwidth_item ellipsis">物业管理类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2659" v="2659" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2660" p="2659" v="2660">
																	<a class="common_alist" href="javascript:;"><span title="经营管理类" class="fixedwidth_item ellipsis">经营管理类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2671" p="2659" v="2671">
																	<a class="common_alist" href="javascript:;"><span title="人力资源类" class="fixedwidth_item ellipsis">人力资源类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2685" p="2659" v="2685">
																	<a class="common_alist" href="javascript:;"><span title="行政/文职/后勤类" class="fixedwidth_item ellipsis">行政/文职/后勤类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2697" v="2697" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2698" p="2697" v="2698">
																	<a class="common_alist" href="javascript:;"><span title="咨询/顾问类" class="fixedwidth_item ellipsis">咨询/顾问类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2707" p="2697" v="2707">
																	<a class="common_alist" href="javascript:;"><span title="律师/法务类" class="fixedwidth_item ellipsis">律师/法务类</span></a>
																</li>
	
																<li id="jobfuctiontype_dropdivmli_2714" p="2697" v="2714">
																	<a class="common_alist" href="javascript:;"><span title="文教类" class="fixedwidth_item ellipsis">文教类</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2786" v="2786" style="display: none;">
															<ul class="posttype_level_group">
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2818" v="2818" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2819" p="2818" v="2819">
																	<a class="common_alist" href="javascript:;"><span title="5555" class="fixedwidth_item ellipsis">5555</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2820" v="2820" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2821" p="2820" v="2821">
																	<a class="common_alist" href="javascript:;"><span title="" class="fixedwidth_item ellipsis"></span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2829" v="2829" style="display: none;">
															<ul class="posttype_level_group">
	
																<li id="jobfuctiontype_dropdivmli_2830" p="2829" v="2830">
																	<a class="common_alist" href="javascript:;"><span title="T1" class="fixedwidth_item ellipsis">T1</span></a>
																</li>
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2834" v="2834" style="display: none;">
															<ul class="posttype_level_group">
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2836" v="2836" style="display: none;">
															<ul class="posttype_level_group">
	
															</ul>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivmdiv_2882" v="2882" style="display: none;">
															<ul class="posttype_level_group">
	
															</ul>
														</div>
	
													</div>
												</div>
												<!-- 2 end -->
												<!-- 3 -->
												<div class="chose_right_wrap chose_right_items clearfix" id="jobfuctiontype_dropdivright_wrap" style="display: none; overflow-x: hidden; overflow-y: auto;">
													<div id="jobfuctiontype_dropdivright_list">
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2167" v="2167" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2167" v="2167" p="2161a2167" title="全部计算机软件类">
																	<span class="checkbox_txt">全部计算机软件类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2168" title="软件架构设计11师" v="2168" p="2161a2167">
																		<span class="checkbox_txt checkbox_txt_h28">软件架构设计11师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2169" title="系统分析师" v="2169" p="2161a2167">
																		<span class="checkbox_txt checkbox_txt_h28">系统分析师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2170" title="交互设计师/可用性分析" v="2170" p="2161a2167">
																		<span class="checkbox_txt checkbox_txt_h28">交互设计师/可用性分析</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2171" title="高级软件工程师/高级程序员/资深程序员" v="2171" p="2161a2167">
																		<span class="checkbox_txt checkbox_txt_h28">高级软件工程师/高级程序员/资深程序员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2172" title="软件工程师/程序员" v="2172" p="2161a2167">
																		<span class="checkbox_txt checkbox_txt_h28">软件工程师/程序员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2173" title="软件测试工程师" v="2173" p="2161a2167">
																		<span class="checkbox_txt checkbox_txt_h28">软件测试工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2174" title="数据库开发工程师" v="2174" p="2161a2167">
																		<span class="checkbox_txt checkbox_txt_h28">数据库开发工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2175" title="其他计算机软件类" v="2175" p="2161a2167">
																		<span class="checkbox_txt checkbox_txt_h28">其他计算机软件类</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2890" title="电商" v="2890" p="2161a2167">
																		<span class="checkbox_txt checkbox_txt_h28">电商</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2176" v="2176" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2176" v="2176" p="2161a2176" title="全部信息技术管理/集成/运行维护类">
																	<span class="checkbox_txt">全部信息技术管理/集成/运行维护类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2177" title="首席技术官CTO/技术总监" v="2177" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">首席技术官CTO/技术总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2178" title="首席信息官CIO/信息总监" v="2178" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">首席信息官CIO/信息总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2179" title="信息技术经理/主管" v="2179" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">信息技术经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2180" title="信息技术专员/助理" v="2180" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">信息技术专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2181" title="系统集成/支持/测试" v="2181" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">系统集成/支持/测试</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2182" title="MRP/ERP实施顾问" v="2182" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">MRP/ERP实施顾问</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2183" title="IT项目经理/主管" v="2183" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">IT项目经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2184" title="IT项目执行/项目组长/协调人员" v="2184" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">IT项目执行/项目组长/协调人员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2185" title="网络工程师" v="2185" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">网络工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2186" title="系统管理员" v="2186" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">系统管理员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2187" title="数据库管理员" v="2187" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">数据库管理员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2188" title="信息安全工程师" v="2188" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">信息安全工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2189" title="智能大厦/综合布线/弱电" v="2189" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">智能大厦/综合布线/弱电</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2190" title="其他IT管理/集成类" v="2190" p="2161a2176">
																		<span class="checkbox_txt checkbox_txt_h28">其他IT管理/集成类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2191" v="2191" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2191" v="2191" p="2161a2191" title="全部互联网/网络应用类">
																	<span class="checkbox_txt">全部互联网/网络应用类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2192" title="网站运营总监" v="2192" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">网站运营总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2193" title="网站运营经理/主管" v="2193" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">网站运营经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2194" title="互联网软件开发工程师" v="2194" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">互联网软件开发工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2195" title="多媒体/游戏开发工程师" v="2195" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">多媒体/游戏开发工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2196" title="网站架构设计师" v="2196" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">网站架构设计师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2197" title="网站维护工程师" v="2197" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">网站维护工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2198" title="网站策划/管理" v="2198" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">网站策划/管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2199" title="网站编辑/论坛维护/内容监管" v="2199" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">网站编辑/论坛维护/内容监管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2200" title="网站推广/优化/SEO" v="2200" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">网站推广/优化/SEO</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2201" title="网页设计/制作/美工" v="2201" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">网页设计/制作/美工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2202" title="其他互联网/网络应用类" v="2202" p="2161a2191">
																		<span class="checkbox_txt checkbox_txt_h28">其他互联网/网络应用类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2203" v="2203" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2203" v="2203" p="2161a2203" title="全部电子/电器类">
																	<span class="checkbox_txt">全部电子/电器类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2204" title="电路/版图/布线设计" v="2204" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">电路/版图/布线设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2205" title="IC工程师" v="2205" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">IC工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2206" title="电子工程师/技术员" v="2206" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">电子工程师/技术员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2207" title="电气工程师/技术员" v="2207" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">电气工程师/技术员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2208" title="电路工程师/技术员" v="2208" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">电路工程师/技术员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2209" title="电器工程师/技术员" v="2209" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">电器工程师/技术员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2210" title="激光/光电子技术" v="2210" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">激光/光电子技术</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2211" title="家电/数码产品开发工程师" v="2211" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">家电/数码产品开发工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2212" title="电声/音响工程师/技术员" v="2212" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">电声/音响工程师/技术员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2213" title="无线电技术工程师/技术员" v="2213" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">无线电技术工程师/技术员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2214" title="电子元器件工程师" v="2214" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">电子元器件工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2215" title="光源/照明工程师" v="2215" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">光源/照明工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2216" title="自动控制工程师/技术员" v="2216" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">自动控制工程师/技术员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2217" title="单片机/ARM/CNC/DLC/DSP/FAE工程师" v="2217" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">单片机/ARM/CNC/DLC/DSP/FAE工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2218" title="嵌入式开发工程师" v="2218" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">嵌入式开发工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2219" title="电池/电源开发工程师" v="2219" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">电池/电源开发工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2220" title="电子测试工程师" v="2220" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">电子测试工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2221" title="其他电子/电器类" v="2221" p="2161a2203">
																		<span class="checkbox_txt checkbox_txt_h28">其他电子/电器类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2845" v="2845" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2845" v="2845" p="2161a2845" title="全部一级建造师">
																	<span class="checkbox_txt">全部一级建造师</span>
																</p>
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2870" v="2870" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2870" v="2870" p="2161a2870" title="全部测试">
																	<span class="checkbox_txt">全部测试</span>
																</p>
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2889" v="2889" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2889" v="2889" p="2161a2889" title="全部电商">
																	<span class="checkbox_txt">全部电商</span>
																</p>
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2233" v="2233" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2233" v="2233" p="2232a2233" title="全部设计类">
																	<span class="checkbox_txt">全部设计类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2234" title="平面设计" v="2234" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">平面设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2235" title="动漫设计" v="2235" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">动漫设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2236" title="游戏设计" v="2236" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">游戏设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2237" title="动画/3D设计" v="2237" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">动画/3D设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2238" title="展台/店面设计" v="2238" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">展台/店面设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2239" title="多媒体设计与开发" v="2239" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">多媒体设计与开发</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2240" title="包装设计" v="2240" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">包装设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2241" title="室内设计" v="2241" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">室内设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2242" title="园艺/园林/景观设计" v="2242" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">园艺/园林/景观设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2243" title="造型/雕塑设计" v="2243" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">造型/雕塑设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2244" title="工业设计/产品外观设计" v="2244" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">工业设计/产品外观设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2245" title="形象设计" v="2245" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">形象设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2246" title="珠宝/饰品设计" v="2246" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">珠宝/饰品设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2247" title="家具/家居用品设计" v="2247" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">家具/家居用品设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2248" title="玩具/工艺品设计" v="2248" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">玩具/工艺品设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2249" title="其他设计类" v="2249" p="2232a2233">
																		<span class="checkbox_txt checkbox_txt_h28">其他设计类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2250" v="2250" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2250" v="2250" p="2232a2250" title="全部广告类">
																	<span class="checkbox_txt">全部广告类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2251" title="广告创意总监" v="2251" p="2232a2250">
																		<span class="checkbox_txt checkbox_txt_h28">广告创意总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2252" title="广告创意经理/主管/专员" v="2252" p="2232a2250">
																		<span class="checkbox_txt checkbox_txt_h28">广告创意经理/主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2253" title="广告客户经理" v="2253" p="2232a2250">
																		<span class="checkbox_txt checkbox_txt_h28">广告客户经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2254" title="广告客户主管/专员" v="2254" p="2232a2250">
																		<span class="checkbox_txt checkbox_txt_h28">广告客户主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2255" title="广告制作" v="2255" p="2232a2250">
																		<span class="checkbox_txt checkbox_txt_h28">广告制作</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2256" title="广告设计师/美术指导" v="2256" p="2232a2250">
																		<span class="checkbox_txt checkbox_txt_h28">广告设计师/美术指导</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2257" title="广告文案策划" v="2257" p="2232a2250">
																		<span class="checkbox_txt checkbox_txt_h28">广告文案策划</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2258" title="其他广告类" v="2258" p="2232a2250">
																		<span class="checkbox_txt checkbox_txt_h28">其他广告类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2259" v="2259" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2259" v="2259" p="2232a2259" title="全部公关/会展类">
																	<span class="checkbox_txt">全部公关/会展类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2260" title="公关/活动经理" v="2260" p="2232a2259">
																		<span class="checkbox_txt checkbox_txt_h28">公关/活动经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2261" title="公关/活动主管/专员" v="2261" p="2232a2259">
																		<span class="checkbox_txt checkbox_txt_h28">公关/活动主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2262" title="会展/会务经理" v="2262" p="2232a2259">
																		<span class="checkbox_txt checkbox_txt_h28">会展/会务经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2263" title="会展/会务主管/专员" v="2263" p="2232a2259">
																		<span class="checkbox_txt checkbox_txt_h28">会展/会务主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2264" title="其他公关/会展类" v="2264" p="2232a2259">
																		<span class="checkbox_txt checkbox_txt_h28">其他公关/会展类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2265" v="2265" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2265" v="2265" p="2232a2265" title="全部影视类">
																	<span class="checkbox_txt">全部影视类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2266" title="影视策划/艺术总监" v="2266" p="2232a2265">
																		<span class="checkbox_txt checkbox_txt_h28">影视策划/艺术总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2267" title="导演/编导" v="2267" p="2232a2265">
																		<span class="checkbox_txt checkbox_txt_h28">导演/编导</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2268" title="经纪人/星探" v="2268" p="2232a2265">
																		<span class="checkbox_txt checkbox_txt_h28">经纪人/星探</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2269" title="演员/模特/礼仪/主持人" v="2269" p="2232a2265">
																		<span class="checkbox_txt checkbox_txt_h28">演员/模特/礼仪/主持人</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2270" title="摄影师/灯光师" v="2270" p="2232a2265">
																		<span class="checkbox_txt checkbox_txt_h28">摄影师/灯光师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2271" title="音效/配音" v="2271" p="2232a2265">
																		<span class="checkbox_txt checkbox_txt_h28">音效/配音</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2272" title="制作/剪辑/合成/冲印" v="2272" p="2232a2265">
																		<span class="checkbox_txt checkbox_txt_h28">制作/剪辑/合成/冲印</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2273" title="造型/化妆/剧务" v="2273" p="2232a2265">
																		<span class="checkbox_txt checkbox_txt_h28">造型/化妆/剧务</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2274" title="其他影视类" v="2274" p="2232a2265">
																		<span class="checkbox_txt checkbox_txt_h28">其他影视类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2275" v="2275" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2275" v="2275" p="2232a2275" title="全部文字媒体/写作类">
																	<span class="checkbox_txt">全部文字媒体/写作类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2276" title="总编/副总编" v="2276" p="2232a2275">
																		<span class="checkbox_txt checkbox_txt_h28">总编/副总编</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2277" title="编辑/作家/独立撰稿人" v="2277" p="2232a2275">
																		<span class="checkbox_txt checkbox_txt_h28">编辑/作家/独立撰稿人</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2278" title="记者/新闻采编" v="2278" p="2232a2275">
																		<span class="checkbox_txt checkbox_txt_h28">记者/新闻采编</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2279" title="美术编辑" v="2279" p="2232a2275">
																		<span class="checkbox_txt checkbox_txt_h28">美术编辑</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2280" title="排版设计" v="2280" p="2232a2275">
																		<span class="checkbox_txt checkbox_txt_h28">排版设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2281" title="校对/录入" v="2281" p="2232a2275">
																		<span class="checkbox_txt checkbox_txt_h28">校对/录入</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2282" title="出版/发行" v="2282" p="2232a2275">
																		<span class="checkbox_txt checkbox_txt_h28">出版/发行</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2283" title="其他文字媒体/写作类" v="2283" p="2232a2275">
																		<span class="checkbox_txt checkbox_txt_h28">其他文字媒体/写作类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2285" v="2285" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2285" v="2285" p="2284a2285" title="全部百货/连锁/零售类">
																	<span class="checkbox_txt">全部百货/连锁/零售类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2286" title="店长/卖场经理" v="2286" p="2284a2285">
																		<span class="checkbox_txt checkbox_txt_h28">店长/卖场经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2287" title="店员/营业员" v="2287" p="2284a2285">
																		<span class="checkbox_txt checkbox_txt_h28">店员/营业员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2288" title="收银员" v="2288" p="2284a2285">
																		<span class="checkbox_txt checkbox_txt_h28">收银员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2289" title="理货员/陈列员/防损员" v="2289" p="2284a2285">
																		<span class="checkbox_txt checkbox_txt_h28">理货员/陈列员/防损员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2290" title="营运/督导/企划" v="2290" p="2284a2285">
																		<span class="checkbox_txt checkbox_txt_h28">营运/督导/企划</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2291" title="促销员/导购员" v="2291" p="2284a2285">
																		<span class="checkbox_txt checkbox_txt_h28">促销员/导购员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2292" title="其他百货/连锁/零售类" v="2292" p="2284a2285">
																		<span class="checkbox_txt checkbox_txt_h28">其他百货/连锁/零售类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2293" v="2293" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2293" v="2293" p="2284a2293" title="全部餐饮/娱乐类">
																	<span class="checkbox_txt">全部餐饮/娱乐类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2294" title="餐饮/娱乐/宴会管理" v="2294" p="2284a2293">
																		<span class="checkbox_txt checkbox_txt_h28">餐饮/娱乐/宴会管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2295" title="餐饮/娱乐领班" v="2295" p="2284a2293">
																		<span class="checkbox_txt checkbox_txt_h28">餐饮/娱乐领班</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2296" title="餐饮/娱乐服务生" v="2296" p="2284a2293">
																		<span class="checkbox_txt checkbox_txt_h28">餐饮/娱乐服务生</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2297" title="礼仪/迎宾/司仪" v="2297" p="2284a2293">
																		<span class="checkbox_txt checkbox_txt_h28">礼仪/迎宾/司仪</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2298" title="行政主厨/厨师长" v="2298" p="2284a2293">
																		<span class="checkbox_txt checkbox_txt_h28">行政主厨/厨师长</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2299" title="厨师" v="2299" p="2284a2293">
																		<span class="checkbox_txt checkbox_txt_h28">厨师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2300" title="面点师" v="2300" p="2284a2293">
																		<span class="checkbox_txt checkbox_txt_h28">面点师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2301" title="调酒师/茶艺师" v="2301" p="2284a2293">
																		<span class="checkbox_txt checkbox_txt_h28">调酒师/茶艺师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2302" title="其他餐饮/娱乐类" v="2302" p="2284a2293">
																		<span class="checkbox_txt checkbox_txt_h28">其他餐饮/娱乐类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2303" v="2303" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2303" v="2303" p="2284a2303" title="全部酒店/旅游类">
																	<span class="checkbox_txt">全部酒店/旅游类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2304" title="酒店/宾馆经理" v="2304" p="2284a2303">
																		<span class="checkbox_txt checkbox_txt_h28">酒店/宾馆经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2305" title="酒店/宾馆营销" v="2305" p="2284a2303">
																		<span class="checkbox_txt checkbox_txt_h28">酒店/宾馆营销</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2306" title="大堂经理/楼面经理" v="2306" p="2284a2303">
																		<span class="checkbox_txt checkbox_txt_h28">大堂经理/楼面经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2307" title="客房服务/楼面服务/行李员" v="2307" p="2284a2303">
																		<span class="checkbox_txt checkbox_txt_h28">客房服务/楼面服务/行李员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2308" title="前厅接待" v="2308" p="2284a2303">
																		<span class="checkbox_txt checkbox_txt_h28">前厅接待</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2309" title="商务中心/订票/订房" v="2309" p="2284a2303">
																		<span class="checkbox_txt checkbox_txt_h28">商务中心/订票/订房</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2310" title="导游/旅行顾问" v="2310" p="2284a2303">
																		<span class="checkbox_txt checkbox_txt_h28">导游/旅行顾问</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2311" title="其他酒店/旅游类" v="2311" p="2284a2303">
																		<span class="checkbox_txt checkbox_txt_h28">其他酒店/旅游类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2312" v="2312" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2312" v="2312" p="2284a2312" title="全部美容/保健类">
																	<span class="checkbox_txt">全部美容/保健类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2313" title="美容顾问" v="2313" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">美容顾问</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2314" title="美容师/化妆师" v="2314" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">美容师/化妆师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2315" title="发型师" v="2315" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">发型师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2316" title="发型助理/学徒" v="2316" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">发型助理/学徒</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2317" title="美甲师" v="2317" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">美甲师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2318" title="按摩/足疗" v="2318" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">按摩/足疗</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2319" title="健身顾问/教练" v="2319" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">健身顾问/教练</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2320" title="瑜伽/舞蹈老师" v="2320" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">瑜伽/舞蹈老师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2321" title="宠物护理/美容" v="2321" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">宠物护理/美容</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2322" title="其他美容/保健类" v="2322" p="2284a2312">
																		<span class="checkbox_txt checkbox_txt_h28">其他美容/保健类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2323" v="2323" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2323" v="2323" p="2284a2323" title="全部保安/家政服务类">
																	<span class="checkbox_txt">全部保安/家政服务类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2324" title="保安人员/保镖" v="2324" p="2284a2323">
																		<span class="checkbox_txt checkbox_txt_h28">保安人员/保镖</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2325" title="电器维修/搬家" v="2325" p="2284a2323">
																		<span class="checkbox_txt checkbox_txt_h28">电器维修/搬家</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2326" title="司机" v="2326" p="2284a2323">
																		<span class="checkbox_txt checkbox_txt_h28">司机</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2327" title="保姆/月嫂/护理" v="2327" p="2284a2323">
																		<span class="checkbox_txt checkbox_txt_h28">保姆/月嫂/护理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2328" title="其他保安/家政服务类" v="2328" p="2284a2323">
																		<span class="checkbox_txt checkbox_txt_h28">其他保安/家政服务类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2330" v="2330" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2330" v="2330" p="2329a2330" title="全部销售管理类">
																	<span class="checkbox_txt">全部销售管理类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2331" title="销售总监" v="2331" p="2329a2330">
																		<span class="checkbox_txt checkbox_txt_h28">销售总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2332" title="销售经理" v="2332" p="2329a2330">
																		<span class="checkbox_txt checkbox_txt_h28">销售经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2333" title="销售主管/主任" v="2333" p="2329a2330">
																		<span class="checkbox_txt checkbox_txt_h28">销售主管/主任</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2334" title="招商/渠道/分销/拓展经理" v="2334" p="2329a2330">
																		<span class="checkbox_txt checkbox_txt_h28">招商/渠道/分销/拓展经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2335" title="招商/渠道/分销/拓展主管" v="2335" p="2329a2330">
																		<span class="checkbox_txt checkbox_txt_h28">招商/渠道/分销/拓展主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2336" title="区域销售经理" v="2336" p="2329a2330">
																		<span class="checkbox_txt checkbox_txt_h28">区域销售经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2337" title="大客户经理/主管" v="2337" p="2329a2330">
																		<span class="checkbox_txt checkbox_txt_h28">大客户经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2338" title="其他销售管理类" v="2338" p="2329a2330">
																		<span class="checkbox_txt checkbox_txt_h28">其他销售管理类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2339" v="2339" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2339" v="2339" p="2329a2339" title="全部销售业务类">
																	<span class="checkbox_txt">全部销售业务类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2340" title="客户经理" v="2340" p="2329a2339">
																		<span class="checkbox_txt checkbox_txt_h28">客户经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2341" title="销售代表/客户代表" v="2341" p="2329a2339">
																		<span class="checkbox_txt checkbox_txt_h28">销售代表/客户代表</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2342" title="推销员/业务员" v="2342" p="2329a2339">
																		<span class="checkbox_txt checkbox_txt_h28">推销员/业务员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2343" title="渠道/分销/拓展专员" v="2343" p="2329a2339">
																		<span class="checkbox_txt checkbox_txt_h28">渠道/分销/拓展专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2344" title="销售工程师" v="2344" p="2329a2339">
																		<span class="checkbox_txt checkbox_txt_h28">销售工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2345" title="电话销售专员" v="2345" p="2329a2339">
																		<span class="checkbox_txt checkbox_txt_h28">电话销售专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2346" title="特许加盟/经销商" v="2346" p="2329a2339">
																		<span class="checkbox_txt checkbox_txt_h28">特许加盟/经销商</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2347" title="其他销售业务类" v="2347" p="2329a2339">
																		<span class="checkbox_txt checkbox_txt_h28">其他销售业务类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2348" v="2348" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2348" v="2348" p="2329a2348" title="全部销售行政/商务类">
																	<span class="checkbox_txt">全部销售行政/商务类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2349" title="销售行政经理/主管" v="2349" p="2329a2348">
																		<span class="checkbox_txt checkbox_txt_h28">销售行政经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2350" title="销售行政专员/助理" v="2350" p="2329a2348">
																		<span class="checkbox_txt checkbox_txt_h28">销售行政专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2351" title="商务经理/主管" v="2351" p="2329a2348">
																		<span class="checkbox_txt checkbox_txt_h28">商务经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2352" title="商务代表/专员/助理" v="2352" p="2329a2348">
																		<span class="checkbox_txt checkbox_txt_h28">商务代表/专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2353" title="销售助理" v="2353" p="2329a2348">
																		<span class="checkbox_txt checkbox_txt_h28">销售助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2354" title="其他销售行政/商务类" v="2354" p="2329a2348">
																		<span class="checkbox_txt checkbox_txt_h28">其他销售行政/商务类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2355" v="2355" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2355" v="2355" p="2329a2355" title="全部客服/技术支持类">
																	<span class="checkbox_txt">全部客服/技术支持类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2356" title="客服总监" v="2356" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">客服总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2357" title="客服经理/主管（非技术）" v="2357" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">客服经理/主管（非技术）</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2358" title="客服专员/助理（非技术）" v="2358" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">客服专员/助理（非技术）</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2359" title="客户关系/客户培训" v="2359" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">客户关系/客户培训</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2360" title="客户投诉受理/监控" v="2360" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">客户投诉受理/监控</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2361" title="售前支持经理/主管" v="2361" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">售前支持经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2362" title="售后服务专员" v="2362" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">售后服务专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2363" title="技术支持经理/主管" v="2363" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">技术支持经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2364" title="技术服务/技术支持" v="2364" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">技术服务/技术支持</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2365" title="客户咨询/服务/热线人员/呼叫中心" v="2365" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">客户咨询/服务/热线人员/呼叫中心</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2366" title="其他客服/技术支持类" v="2366" p="2329a2355">
																		<span class="checkbox_txt checkbox_txt_h28">其他客服/技术支持类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2368" v="2368" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2368" v="2368" p="2367a2368" title="全部市场/营销/推广类">
																	<span class="checkbox_txt">全部市场/营销/推广类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2369" title="市场/营销总监" v="2369" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">市场/营销总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2370" title="市场/营销经理/主管" v="2370" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">市场/营销经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2371" title="市场/营销专员/助理" v="2371" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">市场/营销专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2372" title="市场分析/调研/情报收集/分析" v="2372" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">市场分析/调研/情报收集/分析</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2373" title="产品/品牌经理/主管" v="2373" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">产品/品牌经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2374" title="产品/品牌专员" v="2374" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">产品/品牌专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2375" title="市场推广经理/主管" v="2375" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">市场推广经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2376" title="市场推广专员" v="2376" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">市场推广专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2377" title="促销经理" v="2377" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">促销经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2378" title="促销主管/督导" v="2378" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">促销主管/督导</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2379" title="媒介企划经理/主管" v="2379" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">媒介企划经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2380" title="媒介企划专员" v="2380" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">媒介企划专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2381" title="其他市场/营销/推广类" v="2381" p="2367a2368">
																		<span class="checkbox_txt checkbox_txt_h28">其他市场/营销/推广类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2382" v="2382" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2382" v="2382" p="2367a2382" title="全部采购类">
																	<span class="checkbox_txt">全部采购类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2383" title="采购总监" v="2383" p="2367a2382">
																		<span class="checkbox_txt checkbox_txt_h28">采购总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2384" title="采购经理/主管" v="2384" p="2367a2382">
																		<span class="checkbox_txt checkbox_txt_h28">采购经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2385" title="采购专员/助理" v="2385" p="2367a2382">
																		<span class="checkbox_txt checkbox_txt_h28">采购专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2386" title="供应商管理" v="2386" p="2367a2382">
																		<span class="checkbox_txt checkbox_txt_h28">供应商管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2387" title="外贸采购" v="2387" p="2367a2382">
																		<span class="checkbox_txt checkbox_txt_h28">外贸采购</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2388" title="材料/设备采购管理" v="2388" p="2367a2382">
																		<span class="checkbox_txt checkbox_txt_h28">材料/设备采购管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2389" title="其他采购类" v="2389" p="2367a2382">
																		<span class="checkbox_txt checkbox_txt_h28">其他采购类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2390" v="2390" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2390" v="2390" p="2367a2390" title="全部机械/机器设备/仪器仪表类">
																	<span class="checkbox_txt">全部机械/机器设备/仪器仪表类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2391" title="机械设计/制造工程师" v="2391" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">机械设计/制造工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2392" title="设备经理/主管" v="2392" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">设备经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2393" title="设备工程师" v="2393" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">设备工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2394" title="机器/设备维修保养" v="2394" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">机器/设备维修保养</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2395" title="机器加工" v="2395" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">机器加工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2396" title="机械绘图员" v="2396" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">机械绘图员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2397" title="精密机械/仪器工程师" v="2397" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">精密机械/仪器工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2398" title="仪表/计量工程师" v="2398" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">仪表/计量工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2399" title="变压/变频工程师" v="2399" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">变压/变频工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2400" title="机械工程师" v="2400" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">机械工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2401" title="结构工程师" v="2401" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">结构工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2402" title="模具工程师" v="2402" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">模具工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2403" title="机电工程师" v="2403" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">机电工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2404" title="维修工程师" v="2404" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">维修工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2405" title="铸造/锻造工程师" v="2405" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">铸造/锻造工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2406" title="注塑工程师" v="2406" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">注塑工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2407" title="焊接工程师" v="2407" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">焊接工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2408" title="夹具/刀具工程师" v="2408" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">夹具/刀具工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2409" title="CNC/数控工程师" v="2409" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">CNC/数控工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2410" title="冲压工程师" v="2410" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">冲压工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2411" title="航空/航天/船舶技术" v="2411" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">航空/航天/船舶技术</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2412" title="电力工程师/技术员" v="2412" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">电力工程师/技术员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2413" title="其他机械/仪器仪表类" v="2413" p="2367a2390">
																		<span class="checkbox_txt checkbox_txt_h28">其他机械/仪器仪表类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2414" v="2414" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2414" v="2414" p="2367a2414" title="全部质量保证/品质管理类">
																	<span class="checkbox_txt">全部质量保证/品质管理类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2415" title="品质经理" v="2415" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">品质经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2416" title="品质主管" v="2416" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">品质主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2417" title="品质工程师" v="2417" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">品质工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2418" title="化验员/检验员" v="2418" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">化验员/检验员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2419" title="质检员/测试员" v="2419" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">质检员/测试员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2420" title="标准化工程师" v="2420" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">标准化工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2421" title="体系/认证工程师/审核员" v="2421" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">体系/认证工程师/审核员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2422" title="安全/健康/环境工程师" v="2422" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">安全/健康/环境工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2423" title="物控/资材/物料/PMC" v="2423" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">物控/资材/物料/PMC</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2424" title="文控专员/主管" v="2424" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">文控专员/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2425" title="流程管理" v="2425" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">流程管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2426" title="其他质量保证/品质管理类" v="2426" p="2367a2414">
																		<span class="checkbox_txt checkbox_txt_h28">其他质量保证/品质管理类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2427" v="2427" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2427" v="2427" p="2367a2427" title="全部生产/制造类">
																	<span class="checkbox_txt">全部生产/制造类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2428" title="工厂经理/厂长" v="2428" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">工厂经理/厂长</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2429" title="生产经理/车间主任" v="2429" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">生产经理/车间主任</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2430" title="生产计划/调度" v="2430" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">生产计划/调度</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2431" title="生产主管/督导/领班/组长" v="2431" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">生产主管/督导/领班/组长</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2432" title="生产文员" v="2432" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">生产文员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2433" title="生产跟单/单证" v="2433" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">生产跟单/单证</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2434" title="生产设备管理" v="2434" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">生产设备管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2435" title="物流/仓库管理" v="2435" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">物流/仓库管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2436" title="总工程师/副总工程师" v="2436" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">总工程师/副总工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2437" title="工程经理/主管" v="2437" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">工程经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2438" title="生产项目工程师" v="2438" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">生产项目工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2439" title="结构工程/工艺师" v="2439" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">结构工程/工艺师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2440" title="产品工程师/生产工程师" v="2440" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">产品工程师/生产工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2441" title="工业工程师" v="2441" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">工业工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2442" title="制程工程师/制造工程师" v="2442" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">制程工程师/制造工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2443" title="工业产品开发/新产品导入" v="2443" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">工业产品开发/新产品导入</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2444" title="实验室负责人/工程师" v="2444" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">实验室负责人/工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2445" title="表面焊接/DIP/SMT" v="2445" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">表面焊接/DIP/SMT</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2446" title="其他生产/制造类" v="2446" p="2367a2427">
																		<span class="checkbox_txt checkbox_txt_h28">其他生产/制造类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2447" v="2447" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2447" v="2447" p="2367a2447" title="全部汽车制造/销售/维修/驾培类">
																	<span class="checkbox_txt">全部汽车制造/销售/维修/驾培类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2448" title="汽车设计工程师" v="2448" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车设计工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2449" title="车身/造型设计" v="2449" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">车身/造型设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2450" title="发动机及附件设计" v="2450" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">发动机及附件设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2451" title="底盘及传动系统工程师" v="2451" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">底盘及传动系统工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2452" title="汽车内外饰设计工程师" v="2452" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车内外饰设计工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2453" title="汽车零部件工程师" v="2453" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车零部件工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2454" title="汽车焊接/涂装工程师" v="2454" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车焊接/涂装工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2455" title="汽车总装工程师" v="2455" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车总装工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2456" title="汽车试验/测试/验车员" v="2456" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车试验/测试/验车员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2457" title="4S店展厅经理" v="2457" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">4S店展厅经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2458" title="整车销售/顾问/业务" v="2458" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">整车销售/顾问/业务</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2459" title="汽车经纪/二手车估价师" v="2459" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车经纪/二手车估价师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2460" title="汽车维修/售后经理/主任" v="2460" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车维修/售后经理/主任</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2461" title="汽车维修高级技工" v="2461" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车维修高级技工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2462" title="汽车维修顾问/接车员" v="2462" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车维修顾问/接车员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2463" title="汽车美容" v="2463" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车美容</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2464" title="汽车零部件销售" v="2464" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">汽车零部件销售</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2465" title="驾驶培训" v="2465" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">驾驶培训</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2466" title="其他汽车制造/销售/维修/驾培类" v="2466" p="2367a2447">
																		<span class="checkbox_txt checkbox_txt_h28">其他汽车制造/销售/维修/驾培类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2467" v="2467" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2467" v="2467" p="2367a2467" title="全部贸易/进出口类">
																	<span class="checkbox_txt">全部贸易/进出口类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2468" title="外贸经理/主管" v="2468" p="2367a2467">
																		<span class="checkbox_txt checkbox_txt_h28">外贸经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2469" title="外贸专员/助理" v="2469" p="2367a2467">
																		<span class="checkbox_txt checkbox_txt_h28">外贸专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2470" title="外贸跟单" v="2470" p="2367a2467">
																		<span class="checkbox_txt checkbox_txt_h28">外贸跟单</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2471" title="外贸单证" v="2471" p="2367a2467">
																		<span class="checkbox_txt checkbox_txt_h28">外贸单证</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2472" title="国内贸易" v="2472" p="2367a2467">
																		<span class="checkbox_txt checkbox_txt_h28">国内贸易</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2473" title="内贸跟单" v="2473" p="2367a2467">
																		<span class="checkbox_txt checkbox_txt_h28">内贸跟单</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2474" title="报关/报检" v="2474" p="2367a2467">
																		<span class="checkbox_txt checkbox_txt_h28">报关/报检</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2475" title="电子商务" v="2475" p="2367a2467">
																		<span class="checkbox_txt checkbox_txt_h28">电子商务</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2476" title="其他贸易/进出口类" v="2476" p="2367a2467">
																		<span class="checkbox_txt checkbox_txt_h28">其他贸易/进出口类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2477" v="2477" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2477" v="2477" p="2367a2477" title="全部物流/仓储/运输类">
																	<span class="checkbox_txt">全部物流/仓储/运输类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2478" title="物流总监" v="2478" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">物流总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2479" title="物流经理/主管" v="2479" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">物流经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2480" title="物流专员/助理" v="2480" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">物流专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2481" title="供应链总监" v="2481" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">供应链总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2482" title="供应链经理/主管" v="2482" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">供应链经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2483" title="供应链主管/专员" v="2483" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">供应链主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2484" title="货运代理" v="2484" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">货运代理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2485" title="集装箱业务操作" v="2485" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">集装箱业务操作</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2486" title="船务/空运/陆运操作" v="2486" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">船务/空运/陆运操作</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2487" title="仓库经理/主管/专员" v="2487" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">仓库经理/主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2488" title="调度员/理货员" v="2488" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">调度员/理货员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2489" title="运输经理/主管/专员" v="2489" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">运输经理/主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2490" title="货车司机" v="2490" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">货车司机</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2491" title="快递员/配送员" v="2491" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">快递员/配送员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2492" title="乘务员" v="2492" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">乘务员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2493" title="船长/副船长" v="2493" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">船长/副船长</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2494" title="船员/水手" v="2494" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">船员/水手</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2495" title="其他物流/仓储/运输类" v="2495" p="2367a2477">
																		<span class="checkbox_txt checkbox_txt_h28">其他物流/仓储/运输类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2496" v="2496" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2496" v="2496" p="2367a2496" title="全部服装/纺织/皮革类">
																	<span class="checkbox_txt">全部服装/纺织/皮革类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2497" title="服装/纺织设计" v="2497" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">服装/纺织设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2498" title="面料/辅料开发/采购" v="2498" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">面料/辅料开发/采购</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2499" title="服装/纺织/皮革跟单" v="2499" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">服装/纺织/皮革跟单</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2500" title="板房/楦头/底格出格师" v="2500" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">板房/楦头/底格出格师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2501" title="织造/染色/定型/印花" v="2501" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">织造/染色/定型/印花</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2502" title="裁床/放码" v="2502" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">裁床/放码</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2503" title="服装打样/制版" v="2503" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">服装打样/制版</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2504" title="纸样师/车板工" v="2504" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">纸样师/车板工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2505" title="裁剪车缝熨烫" v="2505" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">裁剪车缝熨烫</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2506" title="其他服装/纺织/皮革类" v="2506" p="2367a2496">
																		<span class="checkbox_txt checkbox_txt_h28">其他服装/纺织/皮革类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2507" v="2507" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2507" v="2507" p="2367a2507" title="全部包装/印刷类">
																	<span class="checkbox_txt">全部包装/印刷类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2508" title="电分/排版/菲林输出" v="2508" p="2367a2507">
																		<span class="checkbox_txt checkbox_txt_h28">电分/排版/菲林输出</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2509" title="打版/调墨/装订/晒版" v="2509" p="2367a2507">
																		<span class="checkbox_txt checkbox_txt_h28">打版/调墨/装订/晒版</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2510" title="丝印/烫金" v="2510" p="2367a2507">
																		<span class="checkbox_txt checkbox_txt_h28">丝印/烫金</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2511" title="机长" v="2511" p="2367a2507">
																		<span class="checkbox_txt checkbox_txt_h28">机长</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2512" title="包装工程师" v="2512" p="2367a2507">
																		<span class="checkbox_txt checkbox_txt_h28">包装工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2513" title="其他包装/印刷类" v="2513" p="2367a2507">
																		<span class="checkbox_txt checkbox_txt_h28">其他包装/印刷类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2514" v="2514" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2514" v="2514" p="2367a2514" title="全部技工类">
																	<span class="checkbox_txt">全部技工类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2515" title="技工" v="2515" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">技工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2516" title="电工" v="2516" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">电工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2517" title="钳工/机修工/钣金工" v="2517" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">钳工/机修工/钣金工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2518" title="电焊工/铆焊工" v="2518" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">电焊工/铆焊工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2519" title="车工/磨工/铣工/冲压工/锣工" v="2519" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">车工/磨工/铣工/冲压工/锣工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2520" title="模具工" v="2520" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">模具工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2521" title="水工/木工/油漆工" v="2521" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">水工/木工/油漆工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2522" title="叉车司机" v="2522" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">叉车司机</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2523" title="空调工/电梯工/锅炉工" v="2523" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">空调工/电梯工/锅炉工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2524" title="普工" v="2524" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">普工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2525" title="搬运/清洁/厨工" v="2525" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">搬运/清洁/厨工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2526" title="切割技工" v="2526" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">切割技工</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2527" title="CNC技工/数控车床操作员" v="2527" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">CNC技工/数控车床操作员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2528" title="烤漆技师" v="2528" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">烤漆技师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2529" title="其他技工类" v="2529" p="2367a2514">
																		<span class="checkbox_txt checkbox_txt_h28">其他技工类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2531" v="2531" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2531" v="2531" p="2530a2531" title="全部翻译类">
																	<span class="checkbox_txt">全部翻译类</span>
																</p>
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2543" v="2543" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2543" v="2543" p="2530a2543" title="全部其他类">
																	<span class="checkbox_txt">全部其他类</span>
																</p>
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2550" v="2550" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2550" v="2550" p="2549a2550" title="全部财务/审计/税务类">
																	<span class="checkbox_txt">全部财务/审计/税务类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2551" title="首席财务官CFO/财务总监" v="2551" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">首席财务官CFO/财务总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2552" title="注册会计师" v="2552" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">注册会计师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2553" title="财务经理" v="2553" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">财务经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2554" title="财务主管/会计主管" v="2554" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">财务主管/会计主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2555" title="财务分析" v="2555" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">财务分析</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2556" title="财务顾问/助理" v="2556" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">财务顾问/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2557" title="会计" v="2557" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">会计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2558" title="出纳" v="2558" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">出纳</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2559" title="账务" v="2559" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">账务</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2560" title="资产评估师" v="2560" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">资产评估师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2561" title="成本经理/主管" v="2561" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">成本经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2562" title="成本会计/专员" v="2562" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">成本会计/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2563" title="注册审计师" v="2563" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">注册审计师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2564" title="审计专员" v="2564" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">审计专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2565" title="统计专员" v="2565" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">统计专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2566" title="注册税务师" v="2566" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">注册税务师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2567" title="税务经理/主管" v="2567" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">税务经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2568" title="税务会计/专员" v="2568" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">税务会计/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2569" title="其他财务/审计/税务类" v="2569" p="2549a2550">
																		<span class="checkbox_txt checkbox_txt_h28">其他财务/审计/税务类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2570" v="2570" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2570" v="2570" p="2549a2570" title="全部证券/金融/投资类">
																	<span class="checkbox_txt">全部证券/金融/投资类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2571" title="证券/期货/黄金/外汇经纪人" v="2571" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">证券/期货/黄金/外汇经纪人</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2572" title="证券分析师" v="2572" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">证券分析师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2573" title="股票/期货操盘手" v="2573" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">股票/期货操盘手</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2574" title="金融/经济研究员" v="2574" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">金融/经济研究员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2575" title="注册投资/金融分析师" v="2575" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">注册投资/金融分析师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2576" title="投资顾问/基金经理" v="2576" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">投资顾问/基金经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2577" title="投资银行业务" v="2577" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">投资银行业务</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2578" title="融资经理/融资主管" v="2578" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">融资经理/融资主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2579" title="融资专员" v="2579" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">融资专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2580" title="拍卖师/典当师" v="2580" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">拍卖师/典当师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2581" title="其他证券/金融/投资类" v="2581" p="2549a2570">
																		<span class="checkbox_txt checkbox_txt_h28">其他证券/金融/投资类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2582" v="2582" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2582" v="2582" p="2549a2582" title="全部银行/保险类">
																	<span class="checkbox_txt">全部银行/保险类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2583" title="银行行长/营业部主任" v="2583" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">银行行长/营业部主任</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2584" title="银行柜员" v="2584" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">银行柜员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2585" title="银行客户经理/主管" v="2585" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">银行客户经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2586" title="银行客户专员" v="2586" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">银行客户专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2587" title="理财顾问/财务规划师" v="2587" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">理财顾问/财务规划师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2588" title="金融资产评估/风险控制" v="2588" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">金融资产评估/风险控制</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2589" title="银保精算师/产品开发" v="2589" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">银保精算师/产品开发</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2590" title="信贷管理/信用调查/稽核分析" v="2590" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">信贷管理/信用调查/稽核分析</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2591" title="预结算/清算" v="2591" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">预结算/清算</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2592" title="保险代理/经纪人/客户经理" v="2592" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">保险代理/经纪人/客户经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2593" title="保险核保/理赔/续期" v="2593" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">保险核保/理赔/续期</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2594" title="其他银行/保险类" v="2594" p="2549a2582">
																		<span class="checkbox_txt checkbox_txt_h28">其他银行/保险类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2596" v="2596" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2596" v="2596" p="2595a2596" title="全部生物/化工/医药类">
																	<span class="checkbox_txt">全部生物/化工/医药类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2597" title="生物工程/生物制药" v="2597" p="2595a2596">
																		<span class="checkbox_txt checkbox_txt_h28">生物工程/生物制药</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2598" title="医药代表" v="2598" p="2595a2596">
																		<span class="checkbox_txt checkbox_txt_h28">医药代表</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2599" title="化学分析/测试" v="2599" p="2595a2596">
																		<span class="checkbox_txt checkbox_txt_h28">化学分析/测试</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2600" title="化工技术" v="2600" p="2595a2596">
																		<span class="checkbox_txt checkbox_txt_h28">化工技术</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2601" title="环保技术" v="2601" p="2595a2596">
																		<span class="checkbox_txt checkbox_txt_h28">环保技术</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2602" title="其他生物/化工/医药类" v="2602" p="2595a2596">
																		<span class="checkbox_txt checkbox_txt_h28">其他生物/化工/医药类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2603" v="2603" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2603" v="2603" p="2595a2603" title="全部医院/医疗/护理类">
																	<span class="checkbox_txt">全部医院/医疗/护理类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2604" title="医院管理人员" v="2604" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">医院管理人员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2605" title="内科医生" v="2605" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">内科医生</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2606" title="外科医生" v="2606" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">外科医生</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2607" title="专科医生" v="2607" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">专科医生</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2608" title="中医科医生" v="2608" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">中医科医生</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2609" title="妇幼保健" v="2609" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">妇幼保健</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2610" title="医药技术/医疗仪器" v="2610" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">医药技术/医疗仪器</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2611" title="麻醉医生" v="2611" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">麻醉医生</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2612" title="理疗/针灸/推拿" v="2612" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">理疗/针灸/推拿</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2613" title="心理医生" v="2613" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">心理医生</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2614" title="营养师" v="2614" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">营养师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2615" title="药剂师" v="2615" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">药剂师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2616" title="医药学检验" v="2616" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">医药学检验</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2617" title="兽医/宠物医生" v="2617" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">兽医/宠物医生</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2618" title="护士/护理人员" v="2618" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">护士/护理人员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2619" title="其他医院/医疗/护理类" v="2619" p="2595a2603">
																		<span class="checkbox_txt checkbox_txt_h28">其他医院/医疗/护理类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2621" v="2621" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2621" v="2621" p="2620a2621" title="全部建筑/工程类">
																	<span class="checkbox_txt">全部建筑/工程类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2622" title="建筑工程师/注册建筑师" v="2622" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">建筑工程师/注册建筑师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2623" title="建筑工程设计" v="2623" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">建筑工程设计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2624" title="建筑结构设计/制图" v="2624" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">建筑结构设计/制图</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2625" title="室内外装修" v="2625" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">室内外装修</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2626" title="幕墙工程师" v="2626" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">幕墙工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2627" title="制冷/暖通/管道" v="2627" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">制冷/暖通/管道</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2628" title="电气/照明工程师" v="2628" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">电气/照明工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2629" title="给排水/水电工程师" v="2629" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">给排水/水电工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2630" title="施工员" v="2630" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">施工员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2631" title="环境与城市规划" v="2631" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">环境与城市规划</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2632" title="报批/报建" v="2632" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">报批/报建</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2633" title="工程项目经理" v="2633" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">工程项目经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2634" title="工程投标主管" v="2634" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">工程投标主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2635" title="工程管理" v="2635" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">工程管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2636" title="工程造价/预结算/审计" v="2636" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">工程造价/预结算/审计</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2637" title="工程督导/验收/监理" v="2637" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">工程督导/验收/监理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2638" title="工程测绘/测量" v="2638" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">工程测绘/测量</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2639" title="土木/结构工程师" v="2639" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">土木/结构工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2640" title="路桥/港口/航道/隧道工程师" v="2640" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">路桥/港口/航道/隧道工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2641" title="基础地下/岩土工程师" v="2641" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">基础地下/岩土工程师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2642" title="其他建筑/工程类" v="2642" p="2620a2621">
																		<span class="checkbox_txt checkbox_txt_h28">其他建筑/工程类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2643" v="2643" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2643" v="2643" p="2620a2643" title="全部房地产开发/中介类">
																	<span class="checkbox_txt">全部房地产开发/中介类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2644" title="房地产开发/策划经理" v="2644" p="2620a2643">
																		<span class="checkbox_txt checkbox_txt_h28">房地产开发/策划经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2645" title="房地产开发/策划主管/专员" v="2645" p="2620a2643">
																		<span class="checkbox_txt checkbox_txt_h28">房地产开发/策划主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2646" title="商业地产策划/招商/营运" v="2646" p="2620a2643">
																		<span class="checkbox_txt checkbox_txt_h28">商业地产策划/招商/营运</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2647" title="房地产评估" v="2647" p="2620a2643">
																		<span class="checkbox_txt checkbox_txt_h28">房地产评估</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2648" title="房地产中介/置业顾问" v="2648" p="2620a2643">
																		<span class="checkbox_txt checkbox_txt_h28">房地产中介/置业顾问</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2649" title="房地产销售人员" v="2649" p="2620a2643">
																		<span class="checkbox_txt checkbox_txt_h28">房地产销售人员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2650" title="其他房地产类" v="2650" p="2620a2643">
																		<span class="checkbox_txt checkbox_txt_h28">其他房地产类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2651" v="2651" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2651" v="2651" p="2620a2651" title="全部物业管理类">
																	<span class="checkbox_txt">全部物业管理类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2652" title="高级物业顾问/物业顾问" v="2652" p="2620a2651">
																		<span class="checkbox_txt checkbox_txt_h28">高级物业顾问/物业顾问</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2653" title="物业管理经理/主管" v="2653" p="2620a2651">
																		<span class="checkbox_txt checkbox_txt_h28">物业管理经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2654" title="物业管理专员/助理" v="2654" p="2620a2651">
																		<span class="checkbox_txt checkbox_txt_h28">物业管理专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2655" title="物业招商/租赁/租售" v="2655" p="2620a2651">
																		<span class="checkbox_txt checkbox_txt_h28">物业招商/租赁/租售</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2656" title="物业维修" v="2656" p="2620a2651">
																		<span class="checkbox_txt checkbox_txt_h28">物业维修</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2657" title="会所管理" v="2657" p="2620a2651">
																		<span class="checkbox_txt checkbox_txt_h28">会所管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2658" title="其他物业管理类" v="2658" p="2620a2651">
																		<span class="checkbox_txt checkbox_txt_h28">其他物业管理类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2660" v="2660" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2660" v="2660" p="2659a2660" title="全部经营管理类">
																	<span class="checkbox_txt">全部经营管理类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2661" title="首席执行官CEO/总裁/总经理" v="2661" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">首席执行官CEO/总裁/总经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2662" title="首席运营官COO/副总经理/副总裁" v="2662" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">首席运营官COO/副总经理/副总裁</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2663" title="董事会秘书/总裁助理/总经理助理" v="2663" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">董事会秘书/总裁助理/总经理助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2664" title="总监" v="2664" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2665" title="项目总监/产品经理" v="2665" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">项目总监/产品经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2666" title="企业发展规划经理/主管" v="2666" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">企业发展规划经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2667" title="分公司/办事处/分支机构经理" v="2667" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">分公司/办事处/分支机构经理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2668" title="合伙人" v="2668" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">合伙人</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2669" title="监察/稽查" v="2669" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">监察/稽查</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2670" title="其他经营管理类" v="2670" p="2659a2660">
																		<span class="checkbox_txt checkbox_txt_h28">其他经营管理类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2671" v="2671" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2671" v="2671" p="2659a2671" title="全部人力资源类">
																	<span class="checkbox_txt">全部人力资源类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2672" title="人力资源总监" v="2672" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">人力资源总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2673" title="人力资源经理/主管" v="2673" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">人力资源经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2674" title="人力资源专员/助理" v="2674" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">人力资源专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2675" title="招聘经理/主管" v="2675" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">招聘经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2676" title="招聘专员/助理" v="2676" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">招聘专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2677" title="薪资福利经理/主管" v="2677" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">薪资福利经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2678" title="薪资福利专员/助理" v="2678" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">薪资福利专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2679" title="绩效考核经理/主管" v="2679" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">绩效考核经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2680" title="绩效考核专员/助理" v="2680" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">绩效考核专员/助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2681" title="培训经理/主管" v="2681" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">培训经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2682" title="培训专员/助理/培训师" v="2682" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">培训专员/助理/培训师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2683" title="企业文化经理/主管/专员" v="2683" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">企业文化经理/主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2684" title="其他人力资源类" v="2684" p="2659a2671">
																		<span class="checkbox_txt checkbox_txt_h28">其他人力资源类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2685" v="2685" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2685" v="2685" p="2659a2685" title="全部行政/文职/后勤类">
																	<span class="checkbox_txt">全部行政/文职/后勤类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2686" title="行政总监" v="2686" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">行政总监</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2687" title="行政经理/主管/办公室主任" v="2687" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">行政经理/主管/办公室主任</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2688" title="行政专员/经理助理" v="2688" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">行政专员/经理助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2689" title="秘书/高级文员" v="2689" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">秘书/高级文员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2690" title="资料/文档管理" v="2690" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">资料/文档管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2691" title="技术文档写作" v="2691" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">技术文档写作</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2692" title="前台/接待/礼仪" v="2692" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">前台/接待/礼仪</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2693" title="后勤/总务/话务" v="2693" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">后勤/总务/话务</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2694" title="图书/情报/合同管理" v="2694" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">图书/情报/合同管理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2695" title="文员/电脑录入/校对" v="2695" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">文员/电脑录入/校对</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2696" title="其他行政/文职/后勤类" v="2696" p="2659a2685">
																		<span class="checkbox_txt checkbox_txt_h28">其他行政/文职/后勤类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2698" v="2698" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2698" v="2698" p="2697a2698" title="全部咨询/顾问类">
																	<span class="checkbox_txt">全部咨询/顾问类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2699" title="咨询总监/首席咨询师" v="2699" p="2697a2698">
																		<span class="checkbox_txt checkbox_txt_h28">咨询总监/首席咨询师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2700" title="咨询经理/主管" v="2700" p="2697a2698">
																		<span class="checkbox_txt checkbox_txt_h28">咨询经理/主管</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2701" title="咨询师" v="2701" p="2697a2698">
																		<span class="checkbox_txt checkbox_txt_h28">咨询师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2702" title="专业顾问/信息中介" v="2702" p="2697a2698">
																		<span class="checkbox_txt checkbox_txt_h28">专业顾问/信息中介</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2703" title="营销管理顾问" v="2703" p="2697a2698">
																		<span class="checkbox_txt checkbox_txt_h28">营销管理顾问</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2704" title="人力资源/猎头顾问" v="2704" p="2697a2698">
																		<span class="checkbox_txt checkbox_txt_h28">人力资源/猎头顾问</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2705" title="专业培训师" v="2705" p="2697a2698">
																		<span class="checkbox_txt checkbox_txt_h28">专业培训师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2706" title="其他咨询/顾问类" v="2706" p="2697a2698">
																		<span class="checkbox_txt checkbox_txt_h28">其他咨询/顾问类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2707" v="2707" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2707" v="2707" p="2697a2707" title="全部律师/法务类">
																	<span class="checkbox_txt">全部律师/法务类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2708" title="律师" v="2708" p="2697a2707">
																		<span class="checkbox_txt checkbox_txt_h28">律师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2709" title="法律顾问" v="2709" p="2697a2707">
																		<span class="checkbox_txt checkbox_txt_h28">法律顾问</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2710" title="律师/法务助理" v="2710" p="2697a2707">
																		<span class="checkbox_txt checkbox_txt_h28">律师/法务助理</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2711" title="法务主管/专员" v="2711" p="2697a2707">
																		<span class="checkbox_txt checkbox_txt_h28">法务主管/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2712" title="知识产权/专利顾问/专员" v="2712" p="2697a2707">
																		<span class="checkbox_txt checkbox_txt_h28">知识产权/专利顾问/专员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2713" title="其他法律/法务类" v="2713" p="2697a2707">
																		<span class="checkbox_txt checkbox_txt_h28">其他法律/法务类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2714" v="2714" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2714" v="2714" p="2697a2714" title="全部文教类">
																	<span class="checkbox_txt">全部文教类</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2715" title="大学教授" v="2715" p="2697a2714">
																		<span class="checkbox_txt checkbox_txt_h28">大学教授</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2716" title="讲师/助教" v="2716" p="2697a2714">
																		<span class="checkbox_txt checkbox_txt_h28">讲师/助教</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2717" title="中学教师" v="2717" p="2697a2714">
																		<span class="checkbox_txt checkbox_txt_h28">中学教师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2718" title="小学教师/幼教" v="2718" p="2697a2714">
																		<span class="checkbox_txt checkbox_txt_h28">小学教师/幼教</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2719" title="体育/音乐/美术教师" v="2719" p="2697a2714">
																		<span class="checkbox_txt checkbox_txt_h28">体育/音乐/美术教师</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2720" title="院校教务管理人员" v="2720" p="2697a2714">
																		<span class="checkbox_txt checkbox_txt_h28">院校教务管理人员</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2721" title="职业教育/家教" v="2721" p="2697a2714">
																		<span class="checkbox_txt checkbox_txt_h28">职业教育/家教</span>
																	</li>
	
																	<li class="clear" id="jobfuctiontype_classId_2722" title="其他文教类" v="2722" p="2697a2714">
																		<span class="checkbox_txt checkbox_txt_h28">其他文教类</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2819" v="2819" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2819" v="2819" p="2818a2819" title="全部5555">
																	<span class="checkbox_txt">全部5555</span>
																</p>
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2821" v="2821" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2821" v="2821" p="2820a2821" title="全部">
																	<span class="checkbox_txt">全部</span>
																</p>
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="jobfuctiontype_dropdivrdiv_2830" v="2830" style="display: none;">
															<div class="clearfix">
	
																<p class="chose_right_all clearfix" id="jobfuctiontype_classId_2830" v="2830" p="2829a2830" title="全部T1">
																	<span class="checkbox_txt">全部T1</span>
																</p>
																<ul class="chose_right_list">
	
																	<li class="clear" id="jobfuctiontype_classId_2837" title="2" v="2837" p="2829a2830">
																		<span class="checkbox_txt checkbox_txt_h28">2</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
													</div>
												</div>
												<!-- 3 end -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="common_group f_l mr_10 relative clearfixq" id="resuEmail_div" style="width:300px">
								<input class="common_keywordsinput c_8c8c8c f_l" name="resuEmail" id="resuEmail" type="text" value="联系邮箱" style="width:255px"> &nbsp;
								<a href="javascript:;" style="float:right;margin:10px 10px 0 0;" id="emailHelp" original-title="如果有多个邮箱，请用空格或逗号分隔开"><img src="../../images/help.gif"></a>
							</div>
						</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td>
							<div class="common_group w201 f_l mr_10 clearfixq pointer" id="flatype">
								<em class="icon_jobstype icon_hy f_l"></em>
								<div class="centercon w155 clearfixq f_l">
									<span class="selected_con c_8c8c8c w74 ellipsis" id="flatype_txt">自定义分类（可不填）</span>
									<span class="countnumber countnumber_hide t_r f_r"></span>
								</div>
								<em class="icon_deaultarrow f_r"></em>
								<div class="common_choose_layer_wrapper w424" style="display: none;">
									<div class="common_choose_layer clearfix">
										<div class="common_choose_layer_arrow"></div>
										<div class="w420 clearfix">
											<div class="selected_group_box clearfix" id="flatype_dropdivselected_div" style="display: none;">
												<div class="new_okbtn_box f_r">
													<a class="new_commonbtn_02" id="flatype_dropdivselected_ok" href="javascript:;">确定</a>
												</div>
												<div class="selected_group" id="flatype_dropdivselected_group">
	
													<p class="max_selected hide inline_block f_r">(最多可选5项)</p>
												</div>
											</div>
											<div class="columns_chosebox relative clearfix">
												<div class="tips_masklayer" id="flatype_dropdivright_tip">
													<div class="bg"></div><span>选择分类</span></div>
												<div class="chose_left_wrap clearfix" id="flatype_dropdivleft_wrap">
													<div class="chose_posttype_left" style="position:relative;">
														<div tabindex="0" class="scroll-pane clearfix jspScrollable" id="flatype_dropdivleft_scroll" style="padding: 0px; width: 211px;overflow-x:hidden; overflow-y: auto; height:360px;" hidefocus="">
															<p class="posttype_level_one_all posttype_level_one_allcities clearfix">
																<span class="checkbox_txt checkbox_txt_checked bold">自定义分类（可不填）</span>
															</p>
															<ul class="posttype_level_one type_level_one">
	
																<li id="flatype_dropdivlli_2852" p="0" v="2852">
																	<a class="common_alist" href="javascript:;">状态</a>
																</li>
	
																<li id="flatype_dropdivlli_2854" p="0" v="2854">
																	<a class="common_alist" href="javascript:;">已审核-待岗</a>
																</li>
	
																<li id="flatype_dropdivlli_2855" p="0" v="2855">
																	<a class="common_alist" href="javascript:;">已预约</a>
																</li>
	
																<li id="flatype_dropdivlli_2856" p="0" v="2856">
																	<a class="common_alist" href="javascript:;">佳才已面试--待定</a>
																</li>
	
																<li id="flatype_dropdivlli_2857" p="0" v="2857">
																	<a class="common_alist" href="javascript:;">佳才已面试--推荐企业</a>
																</li>
	
																<li id="flatype_dropdivlli_2858" p="0" v="2858">
																	<a class="common_alist" href="javascript:;">佳才已面试--推荐后待定</a>
																</li>
	
																<li id="flatype_dropdivlli_2861" p="0" v="2861">
																	<a class="common_alist" href="javascript:;">88</a>
																</li>
	
																<li id="flatype_dropdivlli_2863" p="0" v="2863">
																	<a class="common_alist" href="javascript:;">上班状态</a>
																</li>
	
																<li id="flatype_dropdivlli_2872" p="0" v="2872">
																	<a class="common_alist" href="javascript:;">1111111</a>
																</li>
	
																<li id="flatype_dropdivlli_2873" p="0" v="2873">
																	<a class="common_alist" href="javascript:;">qq</a>
																</li>
	
																<li id="flatype_dropdivlli_2874" p="0" v="2874">
																	<a class="common_alist" href="javascript:;">证书</a>
																</li>
	
																<li id="flatype_dropdivlli_2875" p="0" v="2875">
																	<a class="common_alist" href="javascript:;">到期时间</a>
																</li>
	
																<li id="flatype_dropdivlli_2892" p="0" v="2892">
																	<a class="common_alist" href="javascript:;">xxx</a>
																</li>
	
																<li id="flatype_dropdivlli_2893" p="0" v="2893">
																	<a class="common_alist" href="javascript:;">xxxxx</a>
																</li>
	
															</ul>
														</div>
													</div>
												</div>
												<!-- 2 -->
												<div class="chose_right_wrap chose_right_items clearfix" id="flatype_dropdivright_wrap" style="display: none; overflow-x: hidden; overflow-y: auto;">
													<div id="flatype_dropdivright_list">
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2852" v="2852" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																	<li class="clear" id="flatype_classId_2891" title="状态xx" v="2891" p="2852">
																		<span class="checkbox_txt checkbox_txt_h28">xx</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2854" v="2854" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																	<li class="clear" id="flatype_classId_2859" title="已审核-待岗1" v="2859" p="2854">
																		<span class="checkbox_txt checkbox_txt_h28">1</span>
																	</li>
	
																	<li class="clear" id="flatype_classId_2860" title="已审核-待岗2" v="2860" p="2854">
																		<span class="checkbox_txt checkbox_txt_h28">2</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2855" v="2855" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																	<li class="clear" id="flatype_classId_2871" title="已预约方式" v="2871" p="2855">
																		<span class="checkbox_txt checkbox_txt_h28">方式</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2856" v="2856" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2857" v="2857" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2858" v="2858" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2861" v="2861" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2863" v="2863" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																	<li class="clear" id="flatype_classId_2864" title="上班状态上班了" v="2864" p="2863">
																		<span class="checkbox_txt checkbox_txt_h28">上班了</span>
																	</li>
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2872" v="2872" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2873" v="2873" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2874" v="2874" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2875" v="2875" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2892" v="2892" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
														<div class="hide clearfix" id="flatype_dropdivrdiv_2893" v="2893" style="display: none;">
															<div class="clearfix">
	
																<ul class="chose_right_list">
	
																</ul>
															</div>
														</div>
	
													</div>
												</div>
												<!-- 2 end -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="common_group w194 f_l mr_10 relative clearfixq" id="resuContactInfo_div" style="width:405px;">
								<input class="common_keywordsinput c_8c8c8c f_l w183" name="resuContactInfo" id="resuContactInfo" style="width:90%;" type="text" value="联系方式"> &nbsp;
								<a href="javascript:;" style="float:right;margin:10px 10px 0 0;" id="telHelp" original-title="如果有多个电话，请用空格分隔开"><img src="../../images/help.gif"></a>
							</div>
						</td>
					</tr>
					<tr>
						<td align="center" valign="top">简历详情</td>
						<td>
							<p style="color:#666;">为了保障您简历的所有权，请勿在简历正文中留下联系方式。</p>
							
							<textarea name="info" style="width:628px;height:511px;" class="info" ></textarea>
						</td>
					</tr>
	
				</tbody>
			</table>
	</body>
	</form>
</div>

<script type="text/javascript">
	
var editor;  //全局变量
$(function() {
	editor =KindEditor.create('.info', {
		uploadJson : '<?php echo U("attachment/editer_upload");?>',
		fileManagerJson : '<?php echo U("attachment/editer_manager");?>',
		allowFileManager : true,
		 items : [
                    'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link','fullscreen']

	});
});
</script>
<script>
$(function(){
    $.formValidator.initConfig({formid:"info_form",autotip:true});

    $('#info_form').ajaxForm({success:complate,dataType:'json'});
    function complate(result){
        if(result.status == 1){
        	$.pinphp.tip({content:result.msg});
            $.dialog.get(result.dialog).close();
            
            window.location.reload();
        } else {
            $.pinphp.tip({content:result.msg, icon:'alert'});
        }
    }
});
</script>
<script type="text/javascript" src="/hunter/theme/admin/hunter/js/customs/resume.js"></script>
<script>
initResumeInput();
initResumeInputEducationAndSex();
initResumeSelectDivs();
initResumeSearchEducationAndSex();

</script>