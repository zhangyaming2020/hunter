<?php
namespace Admin\Controller;
use Admin\Org\Image;
use Admin\Org\Tree;
class InventoryController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Inventory');
        $this->set_mod('Inventory');
        $this->_map = "";
    }
    
	//进销存明细
    public function _before_index() {
    	$rest = M('item')->select();
		$title = array();          //关联的商品名称
		$unit = array();           //关联的商品单位
		$classification = array(); //关联的商品分类
		$product_id = array();          //关联的商品编号 
		foreach($rest as $val){
			$title[$val['id']] = $val['title'];
			$unit[$val['id']] = $val['unit'];
			$classification[$val['id']] = M('item_cate')->where(array('id'=>$val['cate_id']))->getfield('name');
			$product_id[$val['id']] = $val['product_id'];
		}
        $p = I('p',1,'intval');   //序号
        $this->assign('p',$p);
        $this->assign('title',$title);
        $this->assign('unit',$unit);
        $this->assign('classification',$classification);
        $this->assign('product_id',$product_id);  
        //默认排序
        //$this->sort = 'ordid';
        //$this->order = 'ASC';
    }
    
	//进销存管理 or 导出，调用方法
	private function _g($list){
		//统计每个商品最小计量重量
		foreach($list as $k=>$v){
			$list[$k]['purchase'] = $v['purchase']*$v['nums'];
			$list[$k]['ruku'] = $v['ruku']*$v['nums'];
			$list[$k]['chuku'] = $v['chuku']*$v['nums'];
//			$list[$k]['kucun'] = $v['kucun']*$v['nums'];
			$list[$k]['f_sunhao'] = $v['f_sunhao']*$v['nums'];
			$list[$k]['sunhao'] = $v['sunhao']*$v['nums'];
		}
		//根据商品编号分组
        $array = array();
		foreach($list as $val){
			$array[$val['product_id']][]=$val;
		}
		unset($list);
		//统计每个编号商品最小计量重量和金额
		$list = array();
		foreach($array as $val){
			if(count($val)>1){
				//一个编号含多个商品的
				$purchase=0;$g_price=0;$ruku=0;$chuku=0;$c_price=0;$kucun=0;$f_sunhao=0;$sunhao=0;$sunhao_j=0;
				foreach($val as $k=>$v){
					$purchase = $purchase+$v['purchase'];
					$g_price = $g_price+$v['g_price'];
					$ruku = $ruku+$v['ruku'];
					$chuku = $chuku+$v['chuku'];
					$c_price = $c_price+$v['c_price'];
//					$kucun = $kucun+$v['kucun'];
					$f_sunhao = $f_sunhao+$v['f_sunhao'];
					$sunhao = $sunhao+$v['sunhao'];
					$sunhao_j = $sunhao_j+$v['sunhao_j'];
					$product_id = $v['product_id'];
					$unit = $v['unit'];
					$item_id = $v['item_id'];
				}
				//计算损耗率
				$sunhao_l = (($sunhao+$f_sunhao)/($sunhao+$f_sunhao+$chuku))*100;
				$list[] = array(
					'item_id'=>$item_id,
					'product_id'=>$product_id,
					'purchase'=>$purchase,
					'g_price'=>$g_price,
					'ruku'=>$ruku,
					'chuku'=>$chuku,
					'c_price'=>$c_price,
//					'kucun'=>$kucun,
					'f_sunhao'=>$f_sunhao,
					'sunhao'=>$sunhao,
					'sunhao_j'=>$sunhao_j,
					'unit'=>$unit,
					'sunhao_l'=>round($sunhao_l,2),
				);
				unset($sunhao_l);
			}else{
				//一个编号就一个商品的
				foreach($val as $va){
					//计算损耗率
					$sunhao_l = (($va['sunhao']+$va['f_sunhao'])/($va['sunhao']+$va['f_sunhao']+$va['chuku']))*100;
					$list[] = array(
						'item_id'=>$va['item_id'],
						'product_id'=>$va['product_id'],
						'purchase'=>$va['purchase'],
						'g_price'=>$va['g_price'],
						'ruku'=>$va['ruku'],
						'chuku'=>$va['chuku'],
						'c_price'=>$va['c_price'],
//						'kucun'=>$va['kucun'],
						'f_sunhao'=>$va['f_sunhao'],
						'sunhao'=>$va['sunhao'],
						'sunhao_j'=>$va['sunhao_j'],
						'unit'=>$va['unit'],
						'sunhao_l'=>round($sunhao_l,2),
					);
				}
				unset($sunhao_l);
			}
		}
		unset($array);
//		dump($list);  
		return $list;      
	}

	//统计采、出、损全部金额
	private function t_price($map){
		$list = $this->_mod
		->field("item_id,SUM(purchase*j_price) g_price,SUM(chuku*s_price) c_price,SUM((f_sunhao+sunhao)*j_price) sunhao_j")->where($map)->relation('Item')->group('item_id')->select();
		$list = $this->_g($list);
		$data = array();
		$g_price = 0;$c_price = 0;$s_price = 0;
		foreach($list as $val){
			$g_price = $g_price + $val['g_price'];  
			$c_price = $c_price + $val['c_price'];
			$s_price = $s_price + $val['sunhao_j'];
		}
		$data = array($g_price,$c_price,$s_price);
		unset($list);
        $this->assign('data',$data);
	}
	
	//进销存管理
	public function g_index(){
		$this->_before_index();
		//查询条件   
		$map = $this->_search();
		$count      = count($this->_mod->where($map)->field('item_id')->group('item_id')->select());
		$Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出  
//		dump($count);
		$list = $this->_mod
		->field("item_id,j_price,SUM(purchase) purchase,SUM(purchase*j_price) g_price,SUM(ruku) ruku,SUM(chuku) chuku,SUM(chuku*s_price) c_price,SUM(sunhao) sunhao,SUM(f_sunhao) f_sunhao,SUM((f_sunhao+sunhao)*j_price) sunhao_j")->where($map)->relation('Item')->group('item_id')
		->limit($Page->firstRow.','.$Page->listRows)->select();
		
		//调用统计采、出、损全部金额
		$this->t_price($map);
		  
		$this->assign('list',$this->_g($list));
		$this->assign('page',$show);
		$this->display();
	}
	
	//进销存管理导出
	public function g_index_d(){
		ob_end_clean();
		$rest = M('item')->select();
		$title = array();          //关联的商品名称
		$unit = array();           //关联的商品单位
		$classification = array(); //关联的商品分类
		foreach($rest as $val){
			$title[$val['id']] = $val['title'];
			$unit[$val['id']] = $val['unit'];
			$classification[$val['id']] = M('item_cate')->where(array('id'=>$val['cate_id']))->getfield('name');
		}
		//查询条件   
		$map = $this->_search();
		$list = $this->_mod
		->field("item_id,SUM(purchase) purchase,SUM(purchase*j_price) g_price,SUM(ruku) ruku,SUM(chuku) chuku,SUM(chuku*s_price) c_price,SUM(sunhao) sunhao,SUM(f_sunhao) f_sunhao,SUM((f_sunhao+sunhao)*j_price) sunhao_j")->where($map)->relation('Item')->group('item_id')->select();
		$goods_list = $this->_g($list);
		$data = array();
        foreach ($goods_list as $k=>$goods_info){
//			$data[$k]['id'] = $goods_ info['id'];//进销存表单ID
			$data[$k]['product_id'] = $goods_info['product_id'];//商品编号
			$data[$k]['title'] = jie_string($title[$goods_info['item_id']]);//商品名
			$data[$k]['unit'] = unit_type($unit[$goods_info['item_id']]);//商品单位
			$data[$k]['cate'] = $classification[$goods_info['item_id']];//分类
            $data[$k]['purchase'] = $goods_info['purchase']; //实际采购数量
			$data[$k]['g_price'] = $goods_info['g_price']; //采购金额
            $data[$k]['ruku'] = $goods_info['ruku']; //实际入库数量
			$data[$k]['chuku'] = $goods_info['chuku'];//实际出库数量
			$data[$k]['c_price'] = $goods_info['c_price'];//出库金额
//			$data[$k]['kucun'] = $goods_info['kucun'];//库存数量
			$data[$k]['f_sunhao'] = $goods_info['f_sunhao'];//分拣损耗
			$data[$k]['sunhao'] = $goods_info['sunhao'];//损耗
			$data[$k]['sunhao_j'] = $goods_info['sunhao_j'];//损耗金额
			$data[$k]['sunhao_l'] = $goods_info['sunhao_l']."%";//损耗率
        }
        foreach ($data as $field=>$v){
/*            if($field == 'id'){
                $headArr[]='ID';
            }*/
            if($field == 'product_id'){
                $headArr[]='商品编号';
            }
            if($field == 'title'){
                $headArr[]='商品名';
            }
            if($field == 'unit'){
                $headArr[]='商品单位';
            }
            if($field == 'cate'){
                $headArr[]='分类';
            }
			 if($field == 'purchase'){
                $headArr[]='采购数量';
            }
			 if($field == 'g_price'){
                $headArr[]='采购金额';
            }
			 if($field == 'ruku'){
                $headArr[]='入库数量';
            }
			 if($field == 'chuku'){
                $headArr[]='出库数量';
            }
			 if($field == 'c_price'){
                $headArr[]='出库金额';
            }
			/* if($field == 'kucun'){
                $headArr[]='库存数量';
            }*/	
			 if($field == 'f_sunhao'){
                $headArr[]='分拣损耗';
            }
			 if($field == 'sunhao'){
                $headArr[]='损耗';
            }
			 if($field == 'sunhao_j'){
                $headArr[]='损耗金额';
            }
			 if($field == 'sunhao_l'){  
                $headArr[]='损耗率';
            }
        }
         
		 
        $filename="Inventory_list";
		
        $this->getExcel($filename,$headArr,$data);
	}	
	
	//单商品统计表(16/7/13因客户改需求，废弃)
	public function sale(){
		//日期查询
		$map = array();
		($time_start = I('time_start','', 'trim')) && $map['addtime'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['addtime'][] = array('elt', strtotime($time_end)+(24*60*60-1));
		//区域划分
		if($_SESSION['admin']['role_id'] != 1){
    		$map['city_id'] = $_SESSION['admin']['city_id'];
    	}
		if($time_start || $time_end){
			$this->assign('time_start',$time_start);
			$this->assign('time_end',$time_end);
		}
		//分类查询
		$cate_id = I('cate_id','', 'intval');
		$where = "";
		if($cate_id){
			//是否查询的是一级栏目
			$pid = M("item_cate")->where(array('id'=>$cate_id))->getfield('pid');
			if($pid > 0){
				//查询二级栏目
				$where = " and jid in(select id from jrkj_item where cate_id = ".$cate_id.")";
			}else{
				//查询一级栏目
				$where = " and jid in(select id from jrkj_item where cate_id in(select id from jrkj_item_cate where id=".$cate_id." or pid=".$cate_id."))";
			}
			$this->assign('cate_id',$cate_id);
		}
		$map['status'] = 2;
		//分页, 查询满足要求的总记录数
		$count = count(M('order')->field('addtime')->where($map)->group("FROM_UNIXTIME(addtime, '%Y%m%d' )")->select());
		$Page  = new \Think\Page($count,1);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show  = $Page->show();// 分页显示输出
		//分组查询每一天的订单支付时间
		$pay_time = M('order')->field('addtime')->where($map)->group("FROM_UNIXTIME(addtime, '%Y%m%d' )")->order('addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		//获取每一天的订单
		$list = array();
		$date = "";
		foreach($pay_time as $val){
            $list[] = M('order')->where("status = 2 and FROM_UNIXTIME(addtime, '%Y%m%d' ) = FROM_UNIXTIME(".$val['addtime'].", '%Y%m%d' )")->order('addtime desc')->select();
			$date = $val['addtime'];
		}
		//把每天的支付成功的订单ID统计
		$list_id = array();
		foreach($list as $val){
			foreach($val as $v){
				$id .= $v['id'].",";
			}
			$list_id[] = $id;
            unset($id);
		}
//		dump($list_id);exit;
		//获取每天的销售数据
		$duo = array();
		foreach($list_id as $val){
			$val = substr($val,0,-1);
			if(!$val){$val = 10000000000000000000000;}
            $duo_l = M('order_list')->query("SELECT jid, SUM(nums) as 'sales' FROM jrkj_order_list where oid in(".$val.") ".$where." GROUP BY jid order by jid desc");
			foreach($duo_l as $k=>$v){
				//单个产品详情
				$ban = M('item')->where(array('id'=>$v['jid']))->find();
				//单个产品销售总重量
				$duo_nums =  $ban['nums'] * $v['sales'];
				//接收单个产品销售信息
				$duo[] = array($ban['id'],$ban['product_id'],$duo_nums,$ban['unit']);
			}
		}
		//根据产品编号重组
		$resa = array(); 
		foreach ($duo as $k => $v) {
		  $resa[$v[1]][] = $v;
		}
		//把相同编号的单个商品总重量相加，然后重组成一个二维数组
		$resac = array();
        foreach($resa as $kk=>$vk){
			$item_duo = 0;
    		foreach($vk as $vv){   
    			$item_duo = $item_duo + $vv[2];
				$item_id = $vv[0]; 
				$product_id = $vv[1];
				$unit = $vv[3]; 
    		}
    		$resac[$kk] = array('product_id'=>$product_id,'jid'=>$item_id,'sales'=>$item_duo,'unit'=>$unit);       	
        }
        //所需的数据
		$this->assign('list',$resac);
		$this->assign('date',$date);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}
	
	//单门店统计调用私有
	private function _sales(){
		$where = "";
		($time_start = I('time_start','', 'trim')) && $where.=" and a.addtime>=".strtotime($time_start);
        ($time_end = I('time_end','', 'trim')) && $where.=" and a.addtime<=".(strtotime($time_end)+(24*60*60-1));
		$mobile = I('get.mobile');$men = I('get.men');$uid = I('get.uid');
        if($mobile || $men || $uid){
        	if($uid){
        		$where.=" and a.uid=".$uid;
        	}else{
        		($mobile) && $map['mobile'] = $mobile;
        		($men) && $map['realname'] = array('like','%'.$men.'%');
				if($uid = M('member')->where($map)->getfield('id')){
					$where.=" and a.uid=".$uid;
				}
        	}
        }
		if($where && !$uid) $this->error('请输入有效的会员信息');  
		if($where){
//			echo $where;exit;
			//区域划分
			if($_SESSION['admin']['role_id'] != 1){
				$where.=" and a.city_id=".$_SESSION['admin']['city_id'];
	    	}
			$where.= " and a.status=2";
			//多表联合查询
			$list = M()->query("select a.id,a.uid,b.jid,b.prices,b.nums,(select j_price from jrkj_inventory where item_id=b.jid 
	 and addtime<=a.addtime order by addtime desc limit 1) 
	j_price,c.product_id,c.nums ji_l,c.unit,c.cate_id from jrkj_order a,jrkj_order_list b,jrkj_item c  where a.id=b.oid and b.jid=c.id ".$where."");
        	//分类查询
			$cate_id = I('cate_id','', 'intval');
			//商品名查询
			$title = I('title');
			if($title){
				$product_id = M('item')->where(array('title'=>array('like','%'.$title.'%')))
				->field('product_id')->select();
				if($product_id){
					foreach($product_id as $v){
						$id_all[] = $v['product_id'];
					}
					foreach($list as $k=>$v){
						if(in_array($v['product_id'],$id_all) == FALSE){
							unset($list[$k]);
						}
					}
				}
			}elseif($cate_id){
				//是否查询的是一级栏目
				$item_cate = M("item_cate");
				$pid = $item_cate->field('pid')->where(array('id'=>$cate_id))->getfield('pid');
				$count = $item_cate->where(array('pid'=>$cate_id))->count();
				if($pid > 0 || $count == 0){
					//查询二级栏目
					$id_all[]= $cate_id;
				}else{
					//查询一级栏目
					$cate = $item_cate->field('id')->where(array('pid'=>$cate_id))->select();
					foreach($cate as $v){
						$id_all[] = $v['id'];
					}
				}

				foreach($list as $k=>$v){
					if(in_array($v['cate_id'],$id_all) == FALSE){
						unset($list[$k]);
					}
				}
			}
			//统计总的销售金额和入库金额 
			$j_price = 0;
			$z_price = 0;
			$resa = array();
			foreach($list as $v){
				$j_price = $j_price+($v['nums']*$v['j_price']);
				$z_price = $z_price+($v['nums']*$v['prices']);
				$resa[$v['product_id']][] = $v;
			}
			unset($list);
			//把相同编号的单个商品总重量相加，然后重组成一个二维数组
			$resac = array();
	        foreach($resa as $kk=>$vk){
				$item_duo = 0;$price = 0;$r_price = 0;
	    		foreach($vk as $vv){   
	    			$item_duo = $item_duo + ($vv['nums']*$vv['ji_l']);
					$item_id = $vv['jid']; 
					$price = $price + ($vv['nums']*$vv['prices']);
					$r_price = $r_price + ($vv['nums']*$vv['j_price']);
					$unit = $vv['unit']; 
					$uid = $vv['uid']; 
					$cate_id_c = $vv['cate_id']; 
	    		}
	    		$resac[$kk] = array(
	    			'product_id'=>$kk,
	    			'jid'=>$item_id,
	    			'sales'=>$item_duo,
	    			'price'=>$price,
	    			'r_price'=>$r_price,
	    			'uid'=>$uid,  
	    			'unit'=>$unit,
	    			'cate_id'=>$cate_id_c
				);       	
	        }
	        unset($resa);
			$this->assign('hz',array(
				'z_price'=>$z_price,
				'j_price'=>$j_price,
			));
			$this->assign('search',array(
				'time_start'=>$time_start,
				'time_end'=>$time_end,
				'cate_id'=>$cate_id,
				'title'=>$title,
				'uid'=>$uid,  
				'men'=>$men,  
				'mobile'=>$mobile,  
			));
			return 	$resac;		
		}
	}
	
    //单门店统计表
    public function sales(){
        $this->assign('list',$this->_sales());
    	$this->display();
    }
	//单门店统计表导出  
	public function goods_sales(){
		ob_end_clean();
//		dump($_GET);exit;
		$goods_list = $this->_sales();
		if(!$goods_list) $this->error('没有数据');
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
			$data[$k]['product_id'] = $goods_info['product_id'];//商品编号
			$data[$k]['cate'] = item_info($goods_info['jid'],'cate');//分类
			$data[$k]['title'] = jie_string(item_info($goods_info['jid'],'title'));//商品名
            $data[$k]['miao'] = round(($goods_info['price']-$goods_info['r_price'])/$goods_info['price']*100,2).'%'; //毛利率
			$data[$k]['sales'] = $goods_info['sales']; //销量
			$data[$k]['r_price'] = $goods_info['r_price']; //入库金额
            $data[$k]['price'] = $goods_info['price']; //销售金额
			$data[$k]['unit'] = unit_type(item_info($goods_info['jid'],'unit'));//商品单位
			$data[$k]['uid'] = $goods_info['uid'];//顾客ID
			$data[$k]['men'] = user_info($goods_info['uid'],'realname');//门店
			$data[$k]['mobile'] = user_info($goods_info['uid'],'mobile');//手机
        }
		//设置表头		       
        $headArr[0]='商品编号';
        $headArr[1]='分类';
        $headArr[2]='商品名';
        $headArr[3]='毛利率';
        $headArr[4]='销量';
        $headArr[5]='入库金额';
        $headArr[6]='销售金额';
        $headArr[7]='商品单位';
        $headArr[8]='顾客ID';       
        $headArr[9]='门店';       
        $headArr[10]='手机';       
        //设置表名	
        $filename="sales_list";  
		//调用导出方法
        $this->getExcel($filename,$headArr,$data);
	}

    //多门店统计分类统计
    public function cate_sales(){
    	//产品栏目
    	$item_cate = D('ItemCate');
    	$cate = $item_cate->where(array('pid'=>0))->field('id,name')->select();
		
		//获取分类名称
		$cate_name = array();
		foreach($cate as $v){
			$cate_name[$v['id']] = $v['name'];
		}
		
		//分类查询
		$pid = I('pid','','intval');  
		if($pid){
			$cate_list = $item_cate->get_child_ids($pid);
			if($cate_list){
				$cate_sub = $item_cate->where(array('pid'=>$pid))->field('id,name')->select();
				unset($cate_list);  
			}else{
				$cate_sub = array(array('id'=>$pid,'name'=>$cate_name[$pid]));
			}
		}else{
			$cate_sub = $cate;    
		}
		
		//分类下面所有商品ID
		$cate_id_all = array();
		$jid_all = array();
		foreach($cate_sub as $v){
			$cate_id = $item_cate->get_child_ids($v['id'],TRUE);
			$id_all = M('item')->where(array('cate_id'=>array('in',$cate_id)))->field('id')->select();
			foreach($id_all as $vv){
				$id[] = $vv['id'];
				$jid_all[] = $vv['id'];    
			}
			$cate_id_all[$v['id']] = $id;
			unset($id);  
		}
		
		//分类下没有商品则不继续查询
		if($jid_all){
			//会员编号分前三，中三，后二查询
			$ywy = I('ywy');$md = I('md');$kh = I('kh');
			if($ywy || $md || $kh){
				$uid_all = array($ywy,$md,$kh);
				$member = M('member')->where("product_id > 0")->field('id,product_id')->select();
				$ywy_l = array();$md_l = array();$kh_l = array();
				foreach($member as $v){
					$ywy_l[$v['id']] = substr($v['product_id'],0,3);	
					$md_l[$v['id']] = substr($v['product_id'],3,3);	
					$kh_l[$v['id']] = substr($v['product_id'],6,2);	
				}
				
				//比对数据，相同则把uid保存下来
				$uid_l = array();
				foreach($uid_all as $k=>$v){
					if($v){
						if($k == 0){
							foreach($ywy_l as $kk=>$vv){
								if($vv == $v) $uid_l[] = $kk;
							}
						}else if($k == 1){
							foreach($md_l as $kk=>$vv){
								if($vv == $v) $uid_l[] = $kk;
							}
						}else{
							foreach($kh_l as $kk=>$vv){
								if($vv == $v) $uid_l[] = $kk;
							}
						}
					}
				}
			}
			unset($uid_all);unset($member);unset($ywy_l);unset($md_l);unset($kh_l);  
			//去重
			$uid_l = array_unique($uid_l);
			
			//查询条件
			(!empty($uid_l)) && $map['uid'] = array('in',$uid_l);  	  
			($time_start = I('time_start','', 'trim')) && $map['addtime'][] = array('egt', strtotime($time_start));
        	($time_end = I('time_end','', 'trim')) && $map['addtime'][] = array('elt', strtotime($time_end)+(24*60*60-1));
			$map['status'] = 2;
			 
			//已完成订单ID
			$order = M('order')->where($map)->field('id')->select();
			(empty($order)) && $order = array(array('id'=>10000000000));
			$oid = array();
			foreach($order as $v){
				$oid[] = $v['id'];
			} 
			unset($order);
			//订单详情
			$list = M('order_list')->where(array('oid'=>array('in',$oid),'jid'=>array('in',$jid_all)))
							       ->field('jid,(prices*nums) price')->select();					   
			//分类获取商品销售额					   
			$cate_data = array();					   
			foreach($cate_id_all as $k=>$v){
				foreach($list as $kk=>$vv){
					if(in_array($vv['jid'],$v)){
						$cate_data[$k][] = $vv['price'];
						unset($list[$kk]);  
					}
				}
			}					 
			unset($list);unset($cate_id_all);unset($jid_all);unset($oid);
			
			//统计各分类销售额
			$data = array();
			foreach($cate_data as $k=>$v){
				$data[$k] = round(array_sum($v),2);
			}
			unset($cate_data);    
//			dump($data);  
			$this->assign('data',$data);
			$this->assign('z_data',round(array_sum($data),2));
		}
		$this->assign('cate',$cate);
		$this->assign('pid',$pid);
		$this->assign('time_start',$time_start);
		$this->assign('time_end',$time_end);
		$this->assign('cate_sub',$cate_sub);	      
		$this->assign('ywy',$ywy);	      
		$this->assign('md',$md);	      
		$this->assign('kh',$kh);	      
    	$this->display();
    }
	//销售统计图
	public function sale_tu(){
		//查询条件
	    $date = strtotime(I('time_start'));
		if(!$date){$date = time();}
 		//按日分组查询
		$month_price = M('order')
						->field("SUM(totalprices) as price,count(addtime) as count,AVG(totalprices) as ping")
						->where("status=2 and FROM_UNIXTIME(addtime,'%Y%m%d' ) = FROM_UNIXTIME($date,'%Y%m%d' )")
						->order('addtime asc')->group("FROM_UNIXTIME(addtime,'%Y%m%d')")->select();
		//所需的数据 				
        foreach($month_price as $val){
        	$price = $val['price'];
        	$count = $val['count'];
        	$ping = $val['ping'];
        }
//		dump($month_price);
		$this->assign('date',date('Y-m-d',$date));
		$this->assign('price',round($price,2));
		$this->assign('count',$count);
		$this->assign('ping',round($ping,2));
		$this->display();
	}
	
	//操作日志
	public function journal(){
		$where = $this->_search();
		$count      = M('inventory_list')->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$list = M('inventory_list')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		//查询操作人
		foreach($list as $k=>$val){
			$list[$k]['uid'] = M('admin')->where(array('id'=>$val['uid']))->getfield('username');
		}
//		echo M()->_sql();
		$p = I('p',1,'intval');   //序号
        $this->assign('p',$p);
		$this->assign('list',$list);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}
	
    protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($status = I('status','', 'trim')) && $map['status'] = $status;
//      ($keyword = I('keyword','', 'trim')) && $map['title|unit|cate'] = array('like', '%'.$keyword.'%');
//      ($item_id = I('item_id','', 'trim')) && $map['item_id'] = array('like', '%'.$item_id.'%');
        ($id = I('id','', 'trim')) && $map['item_id'] = $id;
        ($type = I('type','', 'trim')) && $map['type'] = $type;
		if(($keyword = I('keyword','', 'trim')) or ($cate_id = I('cate_id'))){
			$keyword = I('keyword','', 'trim');
			$cate_id = I('cate_id');
			$map['item_id'] = array('in',search_id($keyword,$cate_id));  
		}
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'id' => $id,
            'type' => $type,
            'status'  => $status,
            'keyword' => $keyword,
            'item_id' => $item_id,
            'cate_id' => $cate_id,
        ));
		
		//区域划分
		if($_SESSION['admin']['role_id'] != 1){
    		$map['city_id'] = $_SESSION['admin']['city_id'];
    	}
        return $map;
    }
	//添加添加日志
    public function _before_add()
    {
    	//设置添加数据权限
		$role_id = M('admin')->where(array('id'=>$_SESSION['admin']['id']))->getfield('role_id');
		//区域划分
		$city_id = region_division();
		$this->assign('city_id',$city_id);
		$this->assign('role_id',$role_id);
    }
	
	//添加添加日志
    public function _after_add()
    {
		if (IS_POST) {
			$inventory = M('inventory_list');
			$inventory->create();
			$inventory->inventory_id = $_COOKIE['add_id'];
			$inventory->addtime = time();
			$inventory->type = 1;
			$inventory->uid = $_SESSION['admin']['id'];  
			$inventory->add();	
		}
    }


    public function _after_insert($id){
        //获取属性
        $attrs = I('attr');
        $tags = I('tags','','trim');
        //添加
        $article_attr = array();
        foreach($attrs as $val){
            $article_attr[] = array(
                'article_id' => $id,
                'attr_id' => $val,
            );
        }
        $this->update_tags($tags,$id);
        //扩展内容
        $ext = I('ext','', ',');
        if( $ext ){
            foreach( $ext['name'] as $key=>$val ){
                if( $val&&$ext['value'][$key] ){
                    $atr['article_id'] = $id;
                    $atr['ext_name'] = $val;
                    $atr['ext_value'] = $ext['value'][$key];
                    M('ArticleExt')->add($atr);
                }
            }
        }
        //上传相册
        $item_imgs = array(); //相册
        $file_imgs = array();
        $date_dir = date('ym/');
        foreach( $_FILES['imgs']['name'] as $key=>$val ){
            if( $val ){
                $file_imgs['name'][] = $val;
                $file_imgs['type'][] = $_FILES['imgs']['type'][$key];
                $file_imgs['tmp_name'][] = $_FILES['imgs']['tmp_name'][$key];
                $file_imgs['error'][] = $_FILES['imgs']['error'][$key];
                $file_imgs['size'][] = $_FILES['imgs']['size'][$key];
            }
        }
        if( $file_imgs ){
            $result = $this->_upload($file_imgs, 'article/'.$date_dir,array(
                'width'=>C('pin_item_simg.width'),
                'height'=>C('pin_item_simg.height'),
                'suffix' => '_s',
            ));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                foreach( $result['info'] as $key=>$val ){
                    $item_imgs[] = array(
                        'article_id' => $id,
                        'url'    => $date_dir . $val['savename'],
                        'order'   => $key + 1,
                    );
                }
            }
        }

        //更新图片和相册
        $item_imgs && M('ArticleImg')->addAll($item_imgs);
    }

    public function update_tags($tags='',$article_id = 0,$edit = false){
        if(!is_array($tags)){
            $tags = str_replace(' ',',',$tags);
            $tags = str_replace('，',',',$tags);
            $tags = explode(',',$tags);
        }

        if($edit){
            D('ArticleTag')->where(array('article_id'=>$article_id))->delete();
        }
        if(!empty($tags)){
            foreach($tags as $val){
                $val = trim($val);
                if(!empty($val)){
                    $tag_id = D('Tag')->getFieldByName($val,'id');
                    (!$tag_id) && $tag_id = D('Tag')->add(array('name'=>$val));
                    D('ArticleTag')->add(array('article_id'=>$article_id,'tag_id'=>$tag_id));
                }
            }
        }
    }
	//保存被修改前的数据
	public function _before_edit(){
		//保存修改前的数据
		$id = I('id');
		$info1 = $this->_mod->find($id);
		session('info1', array(
            'item_id' => $info1['item_id'],
            'j_price' => $info1['j_price'],
            'purchase' => $info1['purchase'],
            'ruku' => $info1['ruku'],
            'chuku' => $info1['chuku'],
            'kucun' => $info1['kucun'],
            'sunhao' => $info1['sunhao'],
            'f_sunhao' => $info1['f_sunhao'],
        ));
		//设置修改数据权限
		$role_id = M('admin')->where(array('id'=>$_SESSION['admin']['id']))->getfield('role_id');
		//区域划分
		$city_id = region_division();
		$this->assign('city_id',$city_id);
		$this->assign('role_id',$role_id);	
    }
	
    //添加修改日志
    public function _after_edit(){
    	if (IS_POST) {
			$id = I('id');
			$sess = $_SESSION['info1'];
			$info = $this->_mod->find($id);
			
			$map['j_price'] = inventory_edit('j_price',$info,$sess);
			$map['purchase'] = inventory_edit('purchase',$info,$sess);
			$map['ruku'] = inventory_edit('ruku',$info,$sess);
			$map['chuku'] = inventory_edit('chuku',$info,$sess);
			$map['kucun'] = inventory_edit('kucun',$info,$sess);   
			$map['sunhao'] = inventory_edit('sunhao',$info,$sess);
			$map['f_sunhao'] = inventory_edit('f_sunhao',$info,$sess);
			
			/*if($info['j_price'] == $sess['j_price']){
				$map['j_price'] = $sess['j_price'];
			}else if($info['j_price'] > $sess['j_price']){
				$aa = $info['j_price'] - $sess['j_price'];
				$map['j_price'] = $sess['j_price'].'+'.$aa.'='.$info['j_price'];
			}else{
				$aa = $sess['j_price'] - $info['j_price'];
				$map['j_price'] = $sess['j_price'].'-'.$aa.'='.$info['j_price'];
			}*/
			
			$map['inventory_id'] = $id;
			$map['item_id'] = $sess['item_id'];   
			$map['add_time'] = time();
			$map['type'] = 2;
			$map['uid'] = $_SESSION['admin']['id'];
			$map['city_id'] = $info['city_id'];
			$map['beizu'] = I('beizu');
			M('inventory_list')->add($map);	
		}
    }

    //添加删除日志
	public function _before_delete()
    {
    	$id = I('id');
		$list = $this->_mod->select($id);
		$data_list = "";
		foreach($list as $val){
			$inventory_id = $val['id'];
			$item_id = $val['item_id'];
			$j_price = $val['j_price'];
			$purchase = $val['purchase'];
			$ruku = $val['ruku'];
			$chuku = $val['chuku'];
			$add_time = time();
			$type = 3;
			$kucun = $val['kucun'];
			$beizu = $val['beizu'];
			$sunhao = $val['sunhao'];
			$f_sunhao = $val['f_sunhao'];
			$uid = $_SESSION['admin']['id'];
			$city_id = $val['city_id'];
			$data_list .= "('$inventory_id','$item_id','$j_price','$purchase','$ruku','$chuku','$add_time','$type','$kucun','$beizu','$sunhao','$f_sunhao','$uid','$city_id'),";
		}
		$data_list = substr($data_list,0,-1);
		M('inventory_list')->execute("insert into jrkj_inventory_list (inventory_id,item_id,j_price,purchase,ruku,chuku,add_time,type,kucun,beizu,sunhao,f_sunhao,uid,city_id) values $data_list");
    }   

    //商品导入
	
public function upload()
    {
        header("Content-Type:text/html;charset=utf-8");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类
        $upload->savePath  =      '/'; // 设置附件上传目录
// 		$upload->savePath =  '/data/attachment/editer/image/';
        // 上传文件
        $info   =   $upload->uploadOne($_FILES['excelData']);
        $filename = './Uploads'.$info['savepath'].$info['savename'];
        $exts = $info['ext'];
        if(!$info) {// 上传错误提示错误信息
              $this->error($upload->getError());
          }else{// 上传成功
                  $this->goods_import($filename, $exts);
        }
    }

    //导入数据页面
    public function import()
    {
        $this->display('goods_import');
    }

    //导入数据方法
    protected function goods_import($filename, $exts='xls')
    {
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        if($exts == 'xls'){
            import("Org.Util.PHPExcel.Reader.Excel5");
            $PHPReader=new \PHPExcel_Reader_Excel5();
        }else if($exts == 'xlsx'){
            import("Org.Util.PHPExcel.Reader.Excel2007");
            $PHPReader=new \PHPExcel_Reader_Excel2007();
        }


        //载入文件
        $PHPExcel=$PHPReader->load($filename);
		//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet=$PHPExcel->getSheet(0);
        //获取总列数
        $allColumn=$currentSheet->getHighestColumn();
        //获取总行数
        $allRow=$currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for($currentRow=2;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                //读取到的数据，保存到数组$arr中
                $data[$currentRow][$currentColumn]=(string)$currentSheet->getCell($address)->getValue();
            }

        }
        $this->save_import($data);
    }

    //保存导入数据
    public function save_import($data)
    {
//  	dump($data);exit;
        $Goods = D('Inventory');
        $data_list = "";
        foreach ($data as $k=>$v){
			$item_id = $v['A'];
			$j_price = $v['C'];
			$purchase = $v['D'];
			$ruku = $v['E'];
			$chuku = $v['F'];  
			$kucun = $v['G'];
			$fenjian = $v['H'];
			$sunhao = $v['I'];
			$beizu = $v['J'];
			$city_id = $v['K'];  
	        $add_time = $v['L'];
			$s_price = $v['M']; 
	        $addtime = time();
            $data_list .= "('$item_id','$j_price','$purchase','$ruku','$chuku','$s_price',$kucun,'$fenjian','$sunhao','$beizu','$city_id','$add_time','$addtime'),";	  		
		}  
		$data_list = substr($data_list,0,-1);
	   	$result = $Goods->execute("insert into jrkj_inventory (item_id,j_price,purchase,ruku,chuku,s_price,kucun,f_sunhao,sunhao,beizu,city_id,add_time,addtime) values $data_list");  
		if($result){
            $this->success('导入成功');  
        }else{
            $this->error('导入失败');
        }
    }

    
	 //导出数据方法
    public function goods_export()
    {
		ob_end_clean();
		$rest = M('item')->select();
		$title = array();          //关联的商品名称
		$unit = array();           //关联的商品单位
		$classification = array(); //关联的商品分类
		$product_id = array();          //关联的商品编号 
		foreach($rest as $val){
			$title[$val['id']] = $val['title'];
			$unit[$val['id']] = $val['unit'];
			$classification[$val['id']] = M('item_cate')->where(array('id'=>$val['cate_id']))->getfield('name');
			$product_id[$val['id']] = $val['product_id'];
		}
		//搜索条件
		$map = $this->_search();
		$goods_list = D('Inventory')->where($map)->select();
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
        	
//			$data[$k]['id'] = $goods_ info['id'];//进销存表单ID
			$data[$k]['item_id'] = $goods_info['item_id']; //	商品ID
			$data[$k]['product_id'] = $product_id[$goods_info['item_id']];//商品编号
			$data[$k]['title'] = $title[$goods_info['item_id']];//商品名
			$data[$k]['unit'] = unit_type($unit[$goods_info['item_id']],1);//商品单位
			$data[$k]['cate'] = $classification[$goods_info['item_id']];//分类
            $data[$k]['purchase'] = $goods_info['purchase']; //实际采购数量
			$data[$k]['j_price'] = $goods_info['j_price']; //进价
			$data[$k]['g_price'] = $goods_info['j_price']*$goods_info['purchase']; //采购金额
            $data[$k]['ruku'] = $goods_info['ruku']; //实际入库数量
			$data[$k]['chuku'] = $goods_info['chuku'];//实际出库数量
			$data[$k]['s_price'] = $goods_info['s_price'];//售价
			$data[$k]['c_price'] = $goods_info['s_price']*$goods_info['chuku'];//出库金额
			$data[$k]['kucun'] = $goods_info['kucun'];//库存数量
			$data[$k]['f_sunhao'] = $goods_info['f_sunhao'];//分拣损耗
			$data[$k]['sunhao'] = $goods_info['sunhao'];//损耗数量
			$data[$k]['h_price'] = ($goods_info['sunhao']+$goods_info['f_sunhao'])*$goods_info['j_price'];//损耗金额
			$data[$k]['beizu'] = $goods_info['beizu'];//损耗数量
			$data[$k]['city_id'] = $goods_info['city_id'];//城市ID
			$data[$k]['add_time'] = $goods_info['add_time'];//城市ID  
        }
        foreach ($data as $field=>$v){
/*            if($field == 'id'){
                $headArr[]='ID';
            }*/
            if($field == 'item_id'){
                $headArr[]='商品ID';
            }
            if($field == 'product_id'){
                $headArr[]='商品编号';
            }
            if($field == 'title'){
                $headArr[]='商品名';
            }
            if($field == 'unit'){
                $headArr[]='商品单位';
            }
            if($field == 'cate'){
                $headArr[]='分类';
            }
			 if($field == 'purchase'){
                $headArr[]='采购数量';
            }
			 if($field == 'j_price'){
                $headArr[]='进价';
            }
			 if($field == 'g_price'){
                $headArr[]='采购金额';
            }
			 if($field == 'ruku'){
                $headArr[]='入库数量';
            }
			 if($field == 'chuku'){
                $headArr[]='出库数量';
            }
			 if($field == 's_price'){
                $headArr[]='售价';
            }
			 if($field == 'c_price'){
                $headArr[]='出库金额';
            }
			 if($field == 'kucun'){
                $headArr[]='库存数量';
            }	
			 if($field == 'sunhao'){
                $headArr[]='分拣损耗';
            }
			 if($field == 'sunhao'){
                $headArr[]='损耗';
            }
			 if($field == 'h_price'){
                $headArr[]='损耗金额';
            }
			 if($field == 'beizu'){
                $headArr[]='备注';
            }	
			 if($field == 'city_id'){
                $headArr[]='城市ID';
            }
			 if($field == 'add_time'){
                $headArr[]='日期';
            }				  
        }
         
		 
        $filename="Inventory_list";
		
        $this->getExcel($filename,$headArr,$data);
    }
    
	//单商品统计表导出(16/7/13因客户改需求，废弃)  
	public function goods_sale(){    
		ob_end_clean();
		//日期查询
		$map = array();
		($time_start = I('time_start','', 'trim')) && $map['addtime'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['addtime'][] = array('elt', strtotime($time_end)+(24*60*60-1));
		if($time_start || $time_end){
			$this->assign('time_start',$time_start);
			$this->assign('time_end',$time_end);
		}
		//分类查询
		$cate_id = I('cate_id','', 'intval');
		$where = "";
		if($cate_id){
			//是否查询的是一级栏目
			$pid = M("item_cate")->where(array('id'=>$cate_id))->getfield('pid');
			if($pid > 0){
				//查询二级栏目
				$where = " and jid in(select id from jrkj_item where cate_id = ".$cate_id.")";
			}else{
				//查询一级栏目
				$where = " and jid in(select id from jrkj_item where cate_id in(select id from jrkj_item_cate where id=".$cate_id." or pid=".$cate_id."))";
			}
			$this->assign('cate_id',$cate_id);
		}
		$map['status'] = 2;
		//分组查询每一天的订单支付时间
		$pay_time = M('order')->field('addtime')->where($map)->group("FROM_UNIXTIME(addtime, '%Y%m%d' )")->order('addtime desc')->select();
		//获取每一天的订单
		$list = array();
		$time = array();
		foreach($pay_time as $val){
            $list[] = M('order')->where("status=2 and FROM_UNIXTIME(addtime, '%Y%m%d' ) = FROM_UNIXTIME(".$val['addtime'].", '%Y%m%d' )")->order('addtime desc')->select();
			$time[] = $val['addtime'];
		}
		//把每天的支付成功的订单ID统计
		$list_id = array();
		foreach($list as $val){
			foreach($val as $v){
				$id .= $v['id'].",";
			}
			$list_id[] = $id;
            unset($id);
		}
		$order_list = array();
		//获取每天的销售数据
		foreach($list_id as $val){
			$duo = array();
			$val = substr($val,0,-1);
			if(!$val){$val = 10000000000000000000000;}
            $duo_l = M('order_list')->query("SELECT jid, SUM(nums) as 'sales' FROM jrkj_order_list where oid in(".$val.") ".$where." GROUP BY jid order by jid desc");
			foreach($duo_l as $k=>$v){
				//单个产品详情
				$ban = M('item')->where(array('id'=>$v['jid']))->find();
				//单个产品销售总重量
				$duo_nums =  $ban['nums'] * $v['sales'];
				//接收单个产品销售信息
				$duo[] = array($ban['id'],$ban['product_id'],$duo_nums);
			}
            //根据产品编号重组
			$resa = array(); 
			foreach ($duo as $k => $v) {
			  $resa[$v[1]][] = $v;
			}
			//把相同编号的单个商品总重量相加，然后重组成一个二维数组
			$resac = array();
	        foreach($resa as $kk=>$vk){
				$item_duo = 0;
	    		foreach($vk as $vv){   
	    			$item_duo = $item_duo + $vv[2];
					$item_id = $vv[0]; 
					$product_id = $vv[1]; 
	    		}
	    		$resac[$kk] = array('product_id'=>$product_id,'jid'=>$item_id,'sales'=>$item_duo);       	
	        }
			$order_list[] = $resac;
		}
		//要导出的数据
		$data = array();
		$i = "";
		$kkk = 0;
        foreach ($order_list as $kk=>$val){
    		$k = 1;
        	foreach($val as $goods_info){
        		$kkkk = $kkk + $k;
				$data[$kkkk]['item_id'] = $goods_info['product_id']; //	商品编号
				$data[$kkkk]['cate'] = item_info($goods_info['jid'],'cate');//分类
				$data[$kkkk]['title'] = jie_string(item_info($goods_info['jid'],'title'));//商品名
				$data[$kkkk]['sales'] = $goods_info['sales'];//销量
				$data[$kkkk]['unit'] = unit_type(item_info($goods_info['jid'],'unit'));//商品单位
	            $data[$kkkk]['time'] = date("Y-m-d",$time[$kk]); //日期
	            $i = $k++;
			}	  
			$kkk = $kkk + $i;  
        }
		//dump($data);exit;
		//设置表头		       
        $headArr[0]='商品编号';
        $headArr[1]='分类';
        $headArr[2]='商品名';
        $headArr[3]='销量';
        $headArr[4]='商品单位';
        $headArr[5]='日期';       
        //设置表名	
        $filename="sale_list";
		//调用导出方法
        $this->getExcel($filename,$headArr,$data);
	}

    private  function getExcel($fileName,$headArr,$data){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");

        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls";

        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();

        //设置表头
        $key = ord("A");
        //print_r($headArr);exit;
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }

        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        //print_r($data);exit;
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value){// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        //$objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }

}