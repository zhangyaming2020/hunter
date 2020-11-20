<?php
namespace Admin\Controller;
class OrderController extends AdminCoreController {
      public function _initialize() {
          parent::_initialize();
		      $this->_mod = D('Order');
          $this->set_mod('Order');
          $a['add_time']=array('elt',time()-24*60*60);
					$a['status']=1;
					M('order')->where($a)->delete();
      }
	 protected function _search() {

        $map = array();

        //'status'=>1

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

      	($mobile = I('mobile','','trim')) && $map['_string'] = 'member_id = (select id from jrkj_member where mobile = '.$mobile.' limit 1)';

        ($dingdan = I('dingdan','',  'trim')) && $map['dingdan'] = array('like', '%'.$dingdan.'%');
		
		($store_id = I('store_id','',  'trim')) && $map['store_id'] = $store_id;
		
		if( $_GET['status']==null ){

            $status = -1;

        }else{

            $map['status']=$status = intval($_GET['status']);

        }
        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,

            'dingdan' => $dingdan,
			
			'mobile' => $mobile,
			
			'status' => $status,

            'store_id' =>$store_id,
        ));

        return $map;

    }
	//订单管理
	public function test_lys(){
		$where =$this->_search();
		$order=M("order");
		$count = $order->where($where)->count();
		$Page = new \Think\Page($count,20);
		$list=$order->alias('o')->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)
		->field($field)->select();
		//dump($list);
		$show = $Page->show();
		$this->assign('page',$show);
		foreach ($list as $k => $v) {			 
			 $list[$k]['nickname']=M('member')->where(array('id'=>$v['member_id']))->getField("nickname");
		}
		$shtype=C('shtype');
		$zftype=C('zftype');
	//所有门店
	  $stores=M('store')->where(array('status'=>1))->field('id,title as name')->select();

	  //统计各状态订单
	  unset($where['status'],$where['mobile'],$where['dingdan']);
	  $order_list = $order->where($where)->field('count(id) as nums,status')->group('status')->select();
	  $order_count = array();
	  foreach($order_list as $k=>$v){
			$order_count[0] += $v['nums'];
			if($v['status'] == 5 || $v['status'] == 6 || $v['status'] == 7){
				$order_count[5] += $v['nums'];
			}else{
				$order_count[$v['status']] += $v['nums'];
			} 
	  }  
	  
	  $order_status=C('order_status');
	  $this->assign('order_count',$order_count);
	  $this->assign('shtype',$shtype);	  
	  $this->assign('zftype',$zftype);
	  $this->assign('stores',$stores);
	  $this->assign('order_status',$order_status);
		$this->assign('list',$list);unset($where['status']);
		$wh1=array_push($where,array('status'=>1));
		$count=$order->where($wh1)->count();
		$this->assign('count',$count);//待支付
		$counta = $order->count();
		$counta="(".$counta.")";
		$this->assign('counta',$counta);
		$countc=$order->where("1=1 and status=2 ")->count();
		$countc="(".$countc.")";
		$this->assign('countc',$countc);
		$countd=$order->where("1=1 and status=3 ")->count();
		$countd="(".$countd.")";
		$this->assign('countd',$countd);
		$counte=$order->where("1=1 and status=4 ")->count();
		$counte="(".$counte.")";
		$this->assign('counte',$counte);
		$countf=$order->where(array('status'=>array('in','5,6,7')))->count();
		$countf="(".$countf.")";
		$this->assign('countf',$countf);
		$countg=$order->where("1=1 and status=8 ")->count();
		$countg="(".$countg.")";
		$this->assign('countg',$countg);
		$this->display();
	}

   //订单详情
    public function detail(){
        $id = I('id',0,'intval');   
        $info=M('order')->where(array('id'=>$id))->find();
        $sku = M('order_list')->alias('ol')->where(array('ol.oid' => $id))
		->join("__ITEM__ o on o.id=ol.item_id")
		->field('ol.*,o.title')->select();
        $this->assign('zftype',C('zftype'));

        $this->assign('order_status',C('order_status'));
        $this->assign('sku',$sku);
		    $this->assign('id',$id);
        $this->assign('info',$info);
        if (IS_AJAX) {
            $response = $this->fetch();
            $this->ajax_return(1, '', $response);
        } else {
            $this->display();
        }
    }
  
	//退货管理
	public function out_order(){
		$order=M("order");
		$keyword=I("keyword");
		$status=I("status");
		$time_start=I("time_start");
		$time_end=I("time_end");
		//$price_min=I("post.price_min");
		//$price_max=I("post.price_max");
		$username=I("username");
		$idhao=I("idhao");
		$tel=I("tel");
		$this->assign("keyword",$keyword);
		$this->assign("status",$status);
		$this->assign("time_start",$time_start);
		$this->assign("time_end",$time_end);
		$this->assign("username",$username);
		$this->assign("idhao",$idhao);
		$this->assign("tel",$tel);
//		if(IS_GET){
		$where="1=1";
		//if($keyword==''&& $time_start=='' && $time_end=='' && $username=='' && $idhao=='' && $tel==''){
		if(empty($keyword)&& empty($time_start) && empty($time_end)&& empty($time_start1) && empty($time_end1) && empty($username) && empty($idhao) && empty($tel)){
			$where = array('out_huo'=>array('in','1,2'));
		}else{//搜索条件
			if($keyword){
				$where=$where." and dingdan like '%".$keyword."%' and out_huo in (1,2)";
			}
			if($username){
				$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') and out_huo in (1,2) ";
			}
			if($idhao){
				$where=$where." and uid like '%".$idhao."%' and out_huo in (1,2)";
			}
			if($tel){
				$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') and out_huo in (1,2) ";
			}
			if($time_start and $time_end){
				$where=$where." and addtime between '".strtotime($time_start)."' and '".strtotime($time_end)."' and out_huo in (1,2) ";
			}
			/*if($time_start1 and $time_end1){
				$where=$where." and addtime between '".strtotime($time_start1)."' and '".strtotime($time_end1)."'";
			}*/
		}

		$count = $order->where($where)->count();
		$Page = new \Think\Page($count,20);
		//所有退货单
		$list=$order->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		$show = $Page->show();
		$this->assign('page',$show);
		for($i=0;$i<count($list);$i++){
			//关联收货地址
			$name_adr= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->find();
			//订单详情
			$list[$i]['sub']=M('order_list')->where('oid="'.$list[$i]['id'].'" and out_nums is not null')->select();
			foreach($list[$i]['sub'] as $k=>$v){
				//产品名称
				$list[$i]['sub'][$k]['title'] = M('item')->where(array('id'=>$v['jid']))->getField('title');
				//退款金额
				$out_price = $v['out_nums']*$v['prices'];
				//如果退货成功则购买数量等于现购数量加已退数量
				if($list[$i]['out_huo'] == 2){
					$list[$i]['sub'][$k]['nums'] = $v['nums'] + $v['out_nums'];
				}
			}
			$list[$i]['name'] =$name_adr['shperson'];
			$list[$i]['tel'] =$name_adr['mobile'];
			$list[$i]['ads'] =$name_adr['province'].$name_adr['city'].$name_adr['county'].$name_adr['address'];
			$list[$i]['out_price'] = $out_price;
		}

		$this->assign('list',$list);
		$this->display();
	}

	//驳回退货
	public function bohui(){
		$id = I('id');
		//改变订单信息
		$ok = $this->_mod->where(array('id'=>$id))->save(array('delivery_time'=>time(),'out_huo'=>3));
		if($ok){
			$ok_l = M('order_list')->where(array('oid'=>$id))->where("out_nums is not null")->save(array('out_nums'=>null));
			if($ok_l){
				$this->success('驳回成功！');
			}else{
				//失败后改变已被改的退货状态
				$this->_mod->where(array('id'=>$id))->save(array('delivery_time'=>null,'out_huo'=>1));
				$this->success('驳回失败，请重试！');
			}
		}else{
			$this->success('驳回失败，请重试！');
		}
	}
	
	  //发货 
  public function fahuo(){
  	    $id = I('id',0,'intval');   
        $info=M('order')->where(array('id'=>$id))->find();   
        $wuliu=C('wuliu');
		    $a=0;
		    foreach($wuliu as $k=>$v){
		    	$wu[$a]=$k;
				  $a++;
		    }
		     
        $this->assign('zftype',C('zftype'));
        $this->assign('order_status',C('order_status'));     
		    $this->assign('id',$id);
        $this->assign('info',$info);
		    $this->assign('wu',$wu);
        if (IS_AJAX) {
            $response = $this->fetch();
            $this->ajax_return(1, '', $response);
        } else {
            $this->display();
        }
  } 
  
  public function ajax_fahuo(){
		$id=I('id');
		$tiaoxingma=I('tiaoxingma');
		$wuliu=I('wuliu');
		$fahuo=I('fahuo');
		$order=M('order')->where(array('id'=>$id))->find();
		$member=M('member')->where(array('id'=>$order['member_id']))->find();
		if($fahuo==1){
			 $a=M('order')->where(array('id'=>$id))->setField("tiaoxingma",$tiaoxingma);
			 $b=M('order')->where(array('id'=>$id))->setField("status",3);
			 $c=M('order')->where(array('id'=>$id))->setField("wuliu",$wuliu);
			 $d=M('order')->where(array('id'=>$id))->setField("update_time",time());
		}
    if($a&&$b&&$c){
    	$a=A('WeiXin')->connect_w($member['wx_openid'],$order['dingdan'],$wuliu,$tiaoxingma,$order['id']);
    	$this->ajax_return(1, L('operation_success'), '', 'edit');
    }else{
    	$this->ajax_return(0, L('operation_failure'));
    }

}
  
	//通过退货
	public function tongguo(){
		$id = I('id');
		$out_price = I('out_price');
		$totalprices = I('totalprices');
		//改变订单价格
		$totalprices_o = $totalprices - $out_price;
		//改变订单信息
		$ok = $this->_mod->where(array('id'=>$id))->save(array('totalprices'=>$totalprices_o,'delivery_time'=>time(),'out_huo'=>2));
		if($ok){
			$list = M('order_list')->where(array('oid'=>$id))->where("out_nums is not null")->select();
			foreach($list as $k=>$v){
				//改变订单详情商品数量
				$ok_l = M('order_list')->where(array('id'=>$v['id']))->setDec('nums',$v['out_nums']);
			}
			if($ok_l){
				//如果商品全被退了则订单状态改为退款单
				$nums = 0;
				$list = M('order_list')->field('nums')->where(array('oid'=>$id))->select();
				foreach($list as $v){
					$nums = $nums + $v['nums'];
				}
				if($nums == 0){
					$this->_mod->where(array('id'=>$id))->save(array('status'=>7));
				}
				$this->success('审核成功，记住退款给客户哦！');
			}else{
				//失败后改变已被改的订单信息
				$this->_mod->where(array('id'=>$id))->save(array('totalprices'=>$totalprices,'delivery_time'=>null,'out_huo'=>1));
				$this->success('审核失败，请重试！');
			}
		}else{
			$this->success('审核失败，请重试！');
		}
	}

	//快递订单
	public function kuai_di(){
		$order=M("order");
		$keyword=I("keyword");
		$status=I("status");
		$time_start=I("time_start");
		$time_end=I("time_end");
		//$price_min=I("post.price_min");
		//$price_max=I("post.price_max");
		$username=I("username");
		$idhao=I("idhao");
		$tel=I("tel");
		$this->assign("keyword",$keyword);
		$this->assign("status",$status);
		$this->assign("time_start",$time_start);
		$this->assign("time_end",$time_end);
		$this->assign("username",$username);
		$this->assign("idhao",$idhao);
		$this->assign("tel",$tel);
//		if(IS_GET){
		$where="1=1";
		//if($keyword==''&& $time_start=='' && $time_end=='' && $username=='' && $idhao=='' && $tel==''){
		if(empty($keyword)&& empty($time_start) && empty($time_end)&& empty($time_start1) && empty($time_end1) && empty($username) && empty($idhao) && empty($tel)){
			$where = array('status'=>array('in','1,2,4'));
		}else{//搜索条件
			if($keyword){
				$where=$where." and dingdan like '%".$keyword."%' and out_huo in (1,2,4)";
			}
			if($username){
				$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') and out_huo in (1,2,4) ";
			}
			if($idhao){
				$where=$where." and uid like '%".$idhao."%' and out_huo in (1,2,4)";
			}
			if($tel){
				$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') and out_huo in (1,2,4) ";
			}
			if($time_start and $time_end){
				$where=$where." and addtime between '".strtotime($time_start)."' and '".strtotime($time_end)."' and out_huo in (1,2,4) ";
			}
			/*if($time_start1 and $time_end1){
				$where=$where." and addtime between '".strtotime($time_start1)."' and '".strtotime($time_end1)."'";
			}*/
		}

		$count = $order->where($where)->count();
		$Page = new \Think\Page($count,20);
		//所有退货单
		$list=$order->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		$show = $Page->show();
		$this->assign('page',$show);
		for($i=0;$i<count($list);$i++){
			//关联收货地址
			$name_adr= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->find();
			$list[$i]['name'] =$name_adr['shperson'];
			$list[$i]['tel'] =$name_adr['mobile'];
			$list[$i]['ads'] =$name_adr['province'].$name_adr['city'].$name_adr['county'].$name_adr['address'];
		}

		$this->assign('list',$list);
		$this->display();
	}

public function index(){
	$this->getLatLong('江西省南昌市');exit;
		//echo "aa";
		//echo strtotime(date('Y-m-d'));
		    $order=M("order");
            $keyword=I("post.keyword");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keyword",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
			if(IS_POST){
			
			//echo $keyword;exit;
		
			$where="1=1";
			
			//if($keyword==''&& $time_start=='' && $time_end=='' && $username=='' && $idhao=='' && $tel==''){
			
			if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($username) && empty($idhao) && empty($tel)){
			   // $where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			   $where="1=1";
			
			}else{
				if($keyword){
					$where=$where." and dingdan like '%".$keyword."%'";
				}
				if($username){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') ";
				}
				
				if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and delivery_time between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}
			}
			
		
			/*if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}*/
			//$where=$where."and status=".$status."";
			//echo $where;exit;
	       // $count = $order->count();
			//$Page = new \Think\Page($count,20);
			$list=$order->where($where)->order('id desc')->select();
			//$list=$order->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select(); 
			//$show = $Page->show();
			//$this->assign('page',$show);
			 //echo M()->_sql();exit;
			for($i=0;$i<count($list);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$list[$i]['uid'].' )')->getField('name');
		    $list[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$list[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$list[$i]['name'] =$name;
			$list[$i]['tel'] =$tel;
			$list[$i]['ads'] =$ads;
			$list[$i]['star']=$star;
			$list[$i]['end']=$end;
			$list[$i]['city_name']=$city_name;
			$res = M('item')->field('id,title,unit')->select();
//			$gwc_title = array();
//			foreach ($res as $val) {
//				$gwc_title[$val['id']] = $val['title'];
//			}
//	        $this->assign('gwc_title', $gwc_title);
			
			
					
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
				$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
				$gwc_value[$val['id']] =$val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
            }//exit;
	        }else{
			$count = $order->count();
			$Page = new \Think\Page($count,20);
			$list=$order->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select(); 
			$show = $Page->show();
			$this->assign('page',$show);
			 //  $list=$order->order('id desc')->select();
			  // echo M()->_sql();
			for($i=0;$i<count($list);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$list[$i]['uid'].' )')->getField('name');
		    $list[$i]['sub']=M('order_list')->field('*')->where('oid='.$list[$i]['id'])->select();
			//echo M()->_sql();
	        $this->assign('title',$title);
		   // dump($list);
			// $lista[$i]['shang']=$title;
			//dump($city_name);
			$list[$i]['name'] =$name;
			$list[$i]['tel'] =$tel;
			$list[$i]['ads'] =$ads;
			$list[$i]['star']=$star;
			$list[$i]['end']=$end;
			$list[$i]['city_name']=$city_name;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			//$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] = $val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
			
            }//exit;
			
			 }
		
		   $this->assign('list',$list);
		
		   $count=$order->where("Is_ok=1 and status=1")->count();
	      $count="(".$count.")";
		  $this->assign('count',$count);
		 
		 
		    $counta = $order->count();
		    $counta="(".$counta.")";
		    $this->assign('counta',$counta);
		  
		   
		     $countc=$order->where("Is_ok=1 and status=0 ")->count();
			 $countc="(".$countc.")";
		     $this->assign('countc',$countc);
			 
			 
			 $countd=$order->where("1=1 and Is_ok=2 ")->count();
			 $countd="(".$countd.")";
		     $this->assign('countd',$countd);
			 
			 
			 $counte=$order->where("1=1 and status=2 ")->count();
			 $counte="(".$counte.")";
		     $this->assign('counte',$counte);
			 
			 
			  $countf=$order->where("1=1 and status=3 ")->count(); $countf="(".$countf.")";
		     $this->assign('countf',$countf);
		   		
			
		 
		
	     $this->display();
	}
	
	
	public function getLatLong($address){dump(3);exit;
    if (!is_string($address))die("All Addresses must be passed as a string");
    $_url = sprintf('http://maps.google.com/maps?output=js&q=%s',rawurlencode($address));
    $_result = false;
    if($_result = file_get_contents($_url)) {
        if(strpos($_result,'errortips') > 1 || strpos($_result,'Did you mean:') !== false) return false;
        preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
        $_coords['lat'] = $_match[1];
        $_coords['long'] = $_match[2];
    }
    echo $_coords;exit;
}





	public function indexyi(){
	
		
		//echo strtotime(date('Y-m-d'));
		$order=M("order");

			$keyword=I("post.keyword");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keyword",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
			if(IS_POST){
				
			//echo $keyword;exit;
		
			//$where="1=1";
			
			//if($keyword==''&& $time_start=='' && $time_end=='' && $username=='' && $idhao=='' && $tel==''){
			//dump($_POST);
			if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($username) && empty($idhao) && empty($tel)){
				
			    //$where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			       $where="1=1";
			}else{
				
				if($keyword){
					$where=$where." and dingdan like '%".$keyword."%'";
				}
				
				if($username){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') ";
				}
				
				if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
			
	
				if($time_start and $time_end){
					$where=$where." and delivery_time between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}else{
					
					$where=$where." "." ";
					}
				
				
				
			}
				
		
			/*if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}*/
			$where=$where."and status=".$status."";
			//echo $where;exit;
	   
		// echo $where;exit;
			$lista=$order->where("Is_ok=1".$where)->order('id desc')->select();
			 
			// echo M()->_sql();exit;
			for($i=0;$i<count($lista);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid']))->getField('address');
		
			$star=M('sundry')->where(array('id'=>$lista[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$lista[$i]['deli_id']))->getField('over_time');
				
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$lista[$i]['uid'].' )')->getField('name');
		
		    $lista[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$lista[$i]['id'])->select();
				// echo "aadb";exit;
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$lista[$i]['city_name']=$city_name;
			$lista[$i]['name'] =$name;
			$lista[$i]['tel'] =$tel;
			$lista[$i]['ads'] =$ads;
			$lista[$i]['star']=$star;
			$lista[$i]['end']=$end;
			$res = M('item')->field('id,title,unit')->select();
			$gwc_title = array();
			foreach ($res as $val) {
				$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			
			
					
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
				$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
				$gwc_value[$val['id']] =$val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
            }//exit;
	        }else{
			
			   $lista=$order->where("Is_ok=1 and status=1 ")->order('id desc')->select();
			  // echo M()->_sql();
			for($i=0;$i<count($lista);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$lista[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$lista[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$lista[$i]['uid'].' )')->getField('name');
		    $lista[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$lista[$i]['id'])->select();
			//echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$lista[$i]['city_name']=$city_name;
			$lista[$i]['name'] =$name;
			$lista[$i]['tel'] =$tel;
			$lista[$i]['ads'] =$ads;
			$lista[$i]['star']=$star;
			$lista[$i]['end']=$end;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			//$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] =$val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
			
            }//exit;
			
			 }
		   $this->assign('lista',$lista);
		   
		    $count=$order->where("Is_ok=1 and status=1 ")->count();
	      $count="(".$count.")";
		  $this->assign('count',$count);
		 
		 
		    $counta= $order->count();
		    $counta="(".$counta.")";
		    $this->assign('counta',$counta);
		  
		   
		     $countc=$order->where("Is_ok=1 and status=0 ")->count();
			 $countc="(".$countc.")";
		     $this->assign('countc',$countc);
			 
			 
			 $countd=$order->where("1=1 and Is_ok=2 ")->count();
			 $countd="(".$countd.")";
		     $this->assign('countd',$countd);
			 
			 
			 $counte=$order->where("1=1 and status=2 ")->count();
			 $counte="(".$counte.")";
		     $this->assign('counte',$counte);
			 
			 
			  $countf=$order->where("1=1 and status=3 ")->count(); $countf="(".$countf.")";
		     $this->assign('countf',$countf);
		  
		   $this->display();
	}
	
	
	
	
	
		public function indexwei(){
		
		$order=M("order");
		if(IS_POST){
			
		
			
		$keyword=I("post.keyword");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keyword",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
		
				
			//$where="1=1";
			
			//if($keyword==''&& $time_start=='' && $time_end=='' && $username=='' && $idhao=='' && $tel==''){
			
			if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($username) && empty($idhao) && empty($tel)){
			    $where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			
			}else{
				if($keyword){
					$where=$where." and dingdan like '%".$keyword."%'";
				}
				if($username){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') ";
				}
				
				if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and delivery_time between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}else{
					
					$where=$where." "." ";
					}
			}
			/*if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}*/
			$where=$where."and status=".$status."";
			//echo $where;exit;
	     
			$listb=$order->where("Is_ok=1".$where)->order('id desc')->select();
			  //echo M()->_sql();exit;
			for($i=0;$i<count($listb);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$listb[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listb[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$listb[$i]['uid'].' )')->getField('name');
			$listb[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$listb[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$listb[$i]['city_name']=$city_name;
			$listb[$i]['name'] =$name;
			$listb[$i]['tel'] =$tel;
			$listb[$i]['ads'] =$ads;
			$listb[$i]['star']=$star;
			$listb[$i]['end']=$end;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			
					$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] ="(" .$val['unit'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] = "(".$val['attr_value'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
            }//exit;
	        }else{			
			   $listb=$order->where("Is_ok=1 and status=0 ")->order('id desc')->select();
			 //  var_dump($listb);exit;
			   for($i=0;$i<count($listb);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid'],'status'=>1))->getField('shperson');
			//echo $name;exit;
			$tel= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$listb[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listb[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$listb[$i]['uid'].' )')->getField('name');
			
		    $listb[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$listb[$i]['id'])->select();
			
		   // echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$listb[$i]['city_name']=$city_name;
			$listb[$i]['name'] =$name;
			$listb[$i]['tel'] =$tel;
			$listb[$i]['ads'] =$ads;
			$listb[$i]['star']=$star;
			$listb[$i]['end']=$end;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
				$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			
			
					$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] =$val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
			
            }//exit;	
		    }
		   $this->assign('listb',$listb);
		   		   
	 	   $count=$order->where("Is_ok=1 and status=1 ")->count();
	      $count="(".$count.")";
		  $this->assign('count',$count);
		 
		 
		    $counta= $order->count();
		    $counta="(".$counta.")";
		    $this->assign('counta',$counta);
		  
		   
		     $countc=$order->where("Is_ok=1 and status=0 ")->count();
			 $countc="(".$countc.")";
		     $this->assign('countc',$countc);
			 
			 
			 $countd=$order->where("1=1 and Is_ok=2 ")->count();
			 $countd="(".$countd.")";
		     $this->assign('countd',$countd);
			 
			 
			 $counte=$order->where("1=1 and status=2 ")->count();
			 $counte="(".$counte.")";
		     $this->assign('counte',$counte);
			 
			 
			  $countf=$order->where("1=1 and status=3 ")->count(); $countf="(".$countf.")";
		     $this->assign('countf',$countf);
		  
		  
		
		   $this->display();
	}
		
	
	
		
		
		
		public function indexque(){
		
		$order=M("order");
		if(IS_POST){
			$keyword=I("post.keyword");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			$Is_ok=I('post.Is_ok');
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keyword",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
			
		
			$where="1=1";
			
			//if($keyword==''&& $time_start=='' && $time_end=='' && $username=='' && $idhao=='' && $tel==''){
			
			if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($username) && empty($idhao) && empty($tel)){
			    $where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			
			}else{
				if($keyword){
					$where=$where." and dingdan like '%".$keyword."%'";
				}
				if($username){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') ";
				}
				
				if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and delivery_time between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}else{
					
					$where=$where." "." ";
					}
				
				
				
				
			}
			/*if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}*/
			$where=$where."and Is_ok=".$Is_ok."";
			//echo $where;exit;
	     
			$listc=$order->where($where)->order('id desc')->select();
			  //echo M()->_sql();
			for($i=0;$i<count($listc);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$listc[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listc[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$listc[$i]['uid'].' )')->getField('name');

		    $listc[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$listc[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$listc[$i]['city_name']=$city_name;
			$listc[$i]['name'] =$name;
			$listc[$i]['tel'] =$tel;
			$listc[$i]['ads'] =$ads;
			$listc[$i]['star']=$star;
			$listc[$i]['end']=$end;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			
					$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] =$val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
            }//exit;
	        }else{
			
			   $listc=$order->where("1=1 and Is_ok=2 ")->order('id desc')->select();
			   for($i=0;$i<count($listc);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid']))->getField('mobile');
			//echo M()->_sql();
			$ads= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$listc[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listc[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$listc[$i]['uid'].' )')->getField('name');

		    $listc[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$listc[$i]['id'])->select();
			//echo $ads;
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$listc[$i]['city_name']=$city_name;
			$listc[$i]['name'] =$name;
			$listc[$i]['tel'] =$tel;
			$listc[$i]['ads'] =$ads;
			$listc[$i]['star']=$star;
			$listc[$i]['end']=$end;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			
			
					$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] =$val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
			
            }//exit;	
		    }
			
			//dump($listc);
			
		   $this->assign('listc',$listc);
		   
		   
		   		   
	   	    $count=$order->where("Is_ok=1 and status=1 ")->count();
	      $count="(".$count.")";
		  $this->assign('count',$count);
		 
		 
		    $counta= $order->count();
		    $counta="(".$counta.")";
		    $this->assign('counta',$counta);
		  
		   
		     $countc=$order->where("Is_ok=1 and status=0 ")->count();
			 $countc="(".$countc.")";
		     $this->assign('countc',$countc);
			 
			 
			 $countd=$order->where("1=1 and Is_ok=2 ")->count();
			 $countd="(".$countd.")";
		     $this->assign('countd',$countd);
			 
			 
			 $counte=$order->where("1=1 and status=2 ")->count();
			 $counte="(".$counte.")";
		     $this->assign('counte',$counte);
			 
			 
			  $countf=$order->where("1=1 and status=3 ")->count(); $countf="(".$countf.")";
		     $this->assign('countf',$countf);
			 
			 
			 
			 
		   $this->display();
	}
		
		
		
		
		
			public function indexcheng(){
		
		$order=M("order");
		if(IS_POST){
	        $keyword=I("post.keyword");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keyword",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
			
			
			
			$where="1=1";
			
			//if($keyword==''&& $time_start=='' && $time_end=='' && $username=='' && $idhao=='' && $tel==''){
			
			if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($username) && empty($idhao) && empty($tel)){
			    $where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			
			}else{
				if($keyword){
					$where=$where." and dingdan like '%".$keyword."%'";
				}
				if($username){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') ";
				}
				
				if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and delivery_time between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}else{
					
					$where=$where." "." ";
					}
				
				
				
				
			}
			/*if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}*/
			$where=$where."and status=".$status."";
			//echo $where;exit;
	     
			$listd=$order->where($where)->order('id desc')->select();
			  //echo M()->_sql();exit;
			for($i=0;$i<count($listd);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$listd[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listd[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$listd[$i]['uid'].' )')->getField('name');
            $listd[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$listd[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$listd[$i]['city_name']=$city_name;
			$listd[$i]['name'] =$name;
			$listd[$i]['tel'] =$tel;
			$listd[$i]['ads'] =$ads;
			$listd[$i]['star']=$star;
			$listd[$i]['end']=$end;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			
			
					$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] =$val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
            }//exit;
	        }else{
			
			   $listd=$order->where("1=1 and status=2 ")->order('id desc')->select();
			   for($i=0;$i<count($listd);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$listd[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listd[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$listd[$i]['uid'].' )')->getField('name');
            $listd[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$listd[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$listd[$i]['city_name']=$city_name;
			$listd[$i]['name'] =$name;
			$listd[$i]['tel'] =$tel;
			$listd[$i]['ads'] =$ads;
			$listd[$i]['star']=$star;
			$listd[$i]['end']=$end;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			
					$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] = "(".$val['attr_value'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
			
			
            }//exit;	
		    }
		   $this->assign('listd',$listd);
		   
   	    $count=$order->where("Is_ok=1 and status=1 ")->count();
	      $count="(".$count.")";
		  $this->assign('count',$count);
		 
		 
		    $counta= $order->count();
		    $counta="(".$counta.")";
		    $this->assign('counta',$counta);
		  
		   
		     $countc=$order->where("Is_ok=1 and status=0 ")->count();
			 $countc="(".$countc.")";
		     $this->assign('countc',$countc);
			 
			 
			 $countd=$order->where("1=1 and Is_ok=2 ")->count();
			 $countd="(".$countd.")";
		     $this->assign('countd',$countd);
			 
			 
			 $counte=$order->where("1=1 and status=2 ")->count();
			 $counte="(".$counte.")";
		     $this->assign('counte',$counte);
			 
			 
			  $countf=$order->where("1=1 and status=3 ")->count(); $countf="(".$countf.")";
		     $this->assign('countf',$countf);
			 
			 
			 
		
		   $this->display();
	}
		
		
		
		
			public function indexshi(){
		
		$order=M("order");
		if(IS_POST){
			$keyword=I("post.keyword");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keyword",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
			
		
			$where="1=1";
			
			//if($keyword==''&& $time_start=='' && $time_end=='' && $username=='' && $idhao=='' && $tel==''){
			
			if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($username) && empty($idhao) && empty($tel)){
			    $where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			
			}else{
				if($keyword){
					$where=$where." and dingdan like '%".$keyword."%'";
				}
				if($username){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') ";
				}
				
				if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and delivery_time between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}else{
					
					$where=$where." "." ";
					}
				
				
				
				
			}
			/*if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}*/
			$where=$where."and status=".$status."";
			//echo $where;exit;
	     
			$liste=$order->where($where)->order('id desc')->select();
			  //echo M()->_sql();exit;
			for($i=0;$i<count($liste);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$liste[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$liste[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$liste[$i]['uid'].' )')->getField('name');
			$liste[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$liste[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$liste[$i]['city_name']=$city_name;
			$liste[$i]['name'] =$name;
			$liste[$i]['tel'] =$tel;
			$liste[$i]['ads'] =$ads;
			$liste[$i]['star']=$star;
			$liste[$i]['end']=$end;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			
					$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] =$val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
			
			
            }//exit;
	        }else{
			
			   $liste=$order->where("1=1 and status=3 ")->order('id desc')->select();
			   for($i=0;$i<count($liste);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid']))->getField('address');
			$star=M('sundry')->where(array('id'=>$liste[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$liste[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$liste[$i]['uid'].' )')->getField('name');
			$liste[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$liste[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$liste[$i]['city_name']=$city_name;
			$liste[$i]['name'] =$name;
			$liste[$i]['tel'] =$tel;
			$liste[$i]['ads'] =$ads;
			$liste[$i]['star']=$star;
			$liste[$i]['end']=$end;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] =$val['unit'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] =$val['attr_value'];
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			
			
			
			
            }//exit;	
		    }
		   $this->assign('liste',$liste);
		   
		   		   
          $count=$order->where("Is_ok=1 and status=1 ")->count();
	      $count="(".$count.")";
		  $this->assign('count',$count);
		 
		 
		    $counta= $order->count();
		    $counta="(".$counta.")";
		    $this->assign('counta',$counta);
		  
		   
		     $countc=$order->where("Is_ok=1 and status=0 ")->count();
			 $countc="(".$countc.")";
		     $this->assign('countc',$countc);
			 
			 
			 $countd=$order->where("1=1 and Is_ok=2 ")->count();
			 $countd="(".$countd.")";
		     $this->assign('countd',$countd);
			 
			 
			 $counte=$order->where("1=1 and status=2 ")->count();
			 $counte="(".$counte.")";
		     $this->assign('counte',$counte);
			 
			 
			  $countf=$order->where("1=1 and status=3 ")->count(); $countf="(".$countf.")";
		     $this->assign('countf',$countf);
		
		   $this->display();
	}
		
		
		
		
		
      
    /**
     *确认订单
     */
    public function que(){		
        $ids = trim(I('id'), ',');		
        if ($ids) {           
		   $data['Is_ok']="2";
		   $false =M('order')->where(array('id'=>array('in', $ids)))->save($data) ;
           $this->success();   
    	}
	}
	
	
	 /**
     *作废订单
     */
    public function zuofei(){
		
        $ids = trim(I('id'), ',');
		
        if ($ids) {
           $data['status']="3";
		   $false =M('order')->where(array('id'=>array('in', $ids)))->save($data) ;
           $this->success();
   
    }
	}
	
		 /**
     *成功订单
     */
    public function cheng(){
		
        $ids = trim(I('id'), ',');
		
        if ($ids) {
			$data['Is_ok']='1';
           $data['status']="2";
		   $false =M('order')->where(array('id'=>array('in', $ids)))->save($data) ;
           $this->success();
   
    }
	}
	
			 /**
     *失败订单
     */
    public function shi(){
		
        $ids = trim(I('id'), ',');
		
        if ($ids) {
			$data['Is_ok']='1';
           $data['status']="3";
		   $false =M('order')->where(array('id'=>array('in', $ids)))->save($data) ;
           $this->success();
   
    }
	}
	
	
	
	
		
	//合并订单
	public function hebing(){
	  $ids = trim(I('id'), ',');
	   $pchu=M('order')->field('id,zftype,aid')->where('id in('.$ids.')')->select(); 
	    foreach($pchu as $k=>$v){
	    $pchua=M('order')->field('id,zftype,aid')->where('id in('.$ids.')')->select(); 
		foreach($pchua as $ke=>$va){
	    if($v['zftype']!=$va['zftype']){
                   $this->success();exit;
	        }
       if($v['aid']!=$va['aid']){
                $this->success();exit;
				 }		
			 }
		  
		  }
	  
	  // echo $ids;exit;
	  $str=explode(',',$ids);
	  for($i=0;$i<count($str);$i++){
		  $false =M('order_list')->where('oid in('.$str[$i].') and oid in(select id from jrkj_order where Is_ok=2)')->order('oid desc')->select() ;//查询单个订单所有商品
		  //var_dump($false);exit;
		  //echo M()->_sql();exit;
		  foreach($false as $key=>$val){
		  	  $nums=$val['nums'];$id='';
			  // var_dump($val);
			  //$totalprices+=$val['prices']*$val['nums'];
			$res=M('order_list')->where('oid in('.$ids.') and oid not in('.$val['oid'].') and oid in(select id from jrkj_order where Is_ok=2)')->select() ;//勾选后的全部订单中的所 有商品
			  // var_dump($res); exit;
			 // echo M()->_sql();exit;
			  foreach($res as $key=>$vals){
			  	  //比较单个订单中的商品与所有订单中的商品是否有重复的商品
				  if($val['jid']==$vals['jid'] && $val['did']==$vals['did']){
				  	 // echo $vals['nums']."<br>";
				  	  $nums+=$vals['nums'];
				  	  if($id==''){
						$id=$vals['id'];
					  }else{
						$id=$id.','.$vals['id'];
					  }	
					  
					    $data['nums']=$nums;
			 	  M('order_list')->where('id='.$val['id'])->save($data);				  		  
				  }
				
				  //echo M()->_sql();exit;

				  if($val['jid']!=$vals['jid'] || $val['did']!=$vals['did']){
				  	  if($idd==''){
						$idd=$vals['id'];
					  }else{
						$idd=$idd.','.$vals['id'];
					  }	
					  
					   $datas['oid']=$val['oid'];
		           M('order_list')->where('id in('.$idd.')')->save($datas);					  		  
				  }
				  
				  
							  
			  }			 
		  }
		    //echo $totalprices;exit;
		 
		 //$datas['totalprices']= 100;
		 //M('order')->where('id='.$val['oid'].')')->save($datas); 
		 
		 $pric =M('order_list')->where('oid in('.$str[$i].')')->order('oid desc')->select();
		 //echo M()->_sql();
		 foreach($pric as $val){
			 $aa+=$val['nums']*$val['prices'];
			}
		//echo $aa;exit;
				
	     $datac['totalprices']=$aa;
	
		 M('order')->where('id='.$val['oid'])->save($datac);
	
	
		  $datasa['status']=3;
		  $datasa['Is_ok']=1;
		  M('order')->where('id in('.$ids.') and id not in('.$val['oid'].')')->save($datasa);
		  //echo M()->_sql();exit;
		  
		 $this->success();
		
	  } 
	  $this->success();
	}
	


	
	
	/*
	//合并订单
	public function hebing(){
	  $ids = trim(I('id'), ',');
	

	  $str=explode(',',$ids);
	  for($i=0;$i<count($str);$i++){
		  $false =M('order_list')->where('oid in('.$str[$i].')')->order('oid desc')->select() ;//查询单个订单所有商品
		  //var_dump($false);exit;
		  //echo M()->_sql();exit;
		  foreach($false as $key=>$val){
		  	  $nums=$val['nums'];$id='';
			  // var_dump($val);
			  //$totalprices+=$val['prices']*$val['nums'];
			  $res=M('order_list')->where('oid in('.$ids.') and oid not in('.$val['oid'].')')->select() ;//勾选后的全部订单中的所 有商品
			   
			
			   //var_dump($res); exit;
			  //echo M()->_sql();exit;
			  foreach($res as $key=>$vals){
				    echo "aa";exit;  
			  	  //比较单个订单中的商品与所有订单中的商品是否有重复的商品
				  if($val['jid']==$vals['jid'] && $val['did']==$vals['did']){
				  	 // echo $vals['prices']."<br>";exit;
				  	  $nums+=$vals['nums'];
			          
				  	  if($id==''){
						$id=$vals['id'];
					  }else{
						$id=$id.','.$vals['id'];
					  }					  		  
				  }
				
				     
				  $data['nums']=$nums;
					 
			 	  M('order_list')->where('id='.$val['id'])->save($data);
				  echo M()->_sql();exit;
				
				  if($val['jid']!=$vals['jid'] || $val['did']!=$vals['did']){
				  	  if($idd==''){
						$idd=$vals['id'];
					  }else{
						$idd=$idd.','.$vals['id'];
					  }					  		  
				  }
				  
				    $this->success();
				  
				  $datas['oid']=$val['oid'];
			 	  M('order_list')->where('id in('.$idd.')')->save($datas);				  
			  }			 
		  }
		  $count=M('order_list')->field("sum(prices)")->find();
		  $data['prices']=$count;
		 // 
		  M('order')->where('id='.$str[$i])->save($data);
		  $datas['status']=3;
		  M('order')->where('id !='.$str[$i].'and id in('.$ids.')')->save($datas);
	
		  
	  } 
	}
	
	
	*/
	
	
	
	//订单条件导出
	
    public function tjdaochu()
    {
	    ob_end_clean();
		 
		if(IS_POST){
			$keyword=I('post.keyword');
			$time_start=I('post.time_start');
			$time_end=I('post.time_end');
			$username=I('post.username');
			$idhao=I('post.idhao');
			$tel=I('post.tel');
			$status=I('post.status');
			$Is_ok=I('post.Is_ok');
		
			$where="1=1  ";
			//$where=$where." and status=".$status." ";
				
			if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($username) && empty($idhao) && empty($tel)){
					$where="1=1";
				if($status!=''){
					//echo "aa";exit;
				
					$where=$where." and status=".$status." ";
				}
				if($Is_ok!=''){
					//echo "aa";exit;
				
					$where=$where." and Is_ok=".$Is_ok." ";
				}
				
			    //$where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			
			//echo $where;exit;
			}else{
				if($keyword){
					$where=$where." and dingdan like '%".$keyword."%'";
				}
				
				if($status){
					$where=$where."and status=".$status."";
					
					}
				
				if($username){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') ";
				}
				
				if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and delivery_time between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}
			
			}
			
		//echo $where;exit;
			
	   $goods_list=M('order')->where($where)->order('id desc')->select();	
	  // echo M()->_sql();exit;
	 
		//$goods_list = M('user')->select();
		
		//***********************************
		
		for($i=0;$i<count($goods_list);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'] ,'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'] ,'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$goods_list[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$goods_list[$i]['deli_id']))->getField('over_time');
		    $goods_list[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$goods_list[$i]['id'])->select();
	
	
			$goods_list[$i]['name'] =$name;
			$goods_list[$i]['tel'] =$tel;
			$goods_list[$i]['ads'] =$ads;
			$goods_list[$i]['star']=$star;
			$goods_list[$i]['end']=$end;
			
			$res = M('item')->field('id,title,unit')->select();
			$gwc_title = array();
			foreach ($res as $val) {
				$gwc_title[$val['id']] = $val['title'];
			}
	     		
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
				$gwc_unit[$val['id']] ="(" .$val['unit'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			//$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
				$gwc_value[$val['id']] = "(".$val['attr_value'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			//$this->assign('gwc_value', $gwc_value);	
			
		
		}
		
		
			//dump($goods_list[0]['sub']);exit;
			//foreach($goods_list[])
			
		//**************************************
     
		$data = array();
        foreach ($goods_list as $k=>$goods_info){
			
			$reg=array();
			foreach($goods_list[$k]['sub'] as $i =>$val){
			//dump($val);	exit;
			if($val['did']==0){
			$reg[$i]=$gwc_title[$val['jid']].''.$gwc_unit[$val['jid']].'/'.$val['nums'].'/';
				
				}else{
			$reg[$i]=$gwc_title[$val['jid']].''.$gwc_value[$val['did']].'/'.$val['nums'].'/';
				}
			
		}
		//exit;
			
			
			//var_dump($reg[0]);
			$aa=implode(';',$reg);
			 
		$dates=date('Y-m-d',$goods_info['delivery_time']);	
		$sten=$dates.' '.$goods_info['star'].'  '.$goods_info['end'];
		
		$datex=date('Y-m-d H-m-s',$goods_info['addtime']);
			
			$data[$k][dingdan] =' '.$goods_info['dingdan'];     //订单号
            $data[$k][totalprices] = $goods_info['totalprices']; //金额
			//exit;
           $data[$k][zftype] = zffsa($goods_info['zftype']); //支付方式
		  // $data[$k][zftype] =$goods_info['zftype']; //支付方式
		  $data[$k][status]=xgstatusa($goods_info['status']);  //状态
		  //$data[$k][status]=$goods_info['status'];  //状态
			
			$data[$k][uid]=' '.$goods_info['uid'];     //顾客ID
			$data[$k][name]=$goods_info['name'];  //姓名
				
			$data[$k][tel]=' '.$goods_info['tel'];   //电话
			$data[$k][ads]=$goods_info['ads']; //配送地址
			$data[$k][jid]= $aa; //详情
		   
			$data[$k][memos]=$goods_info['memos']; //备注
			$data[$k][delivery_time]=$sten;//收货时间
			$data[$k][addtime]=$datex;//下单时间
        }
		
        //print_r($goods_list);
        //print_r($data);exit;

        foreach ($data as $field=>$v){
            if($field == 'dingdan'){
                $headArr[]='订单号';
            }

            if($field == 'totalprices'){
                $headArr[]='金额';
            }
			
			if($field == 'zftype'){
                $headArr[]='支付方式';
            }
			if($field == 'status'){
                $headArr[]='状态';
            }
			if($field == 'uid'){
                $headArr[]='顾客ID';
            }
			if($field == 'name'){
                $headArr[]='姓名';
            }
			if($field == 'tel'){
                $headArr[]='电话';
            }
			if($field == 'ads'){
                $headArr[]='配送地址';
            }
			if($field == 'jid'){
                $headArr[]='详情';
            }
			if($field == 'memos'){
                $headArr[]='备注';
            }
			if($field == 'delivery_time'){
                $headArr[]='收货时间';
            }
			if($field == 'addtime'){
                $headArr[]='下单时间';
            }
			
			
        }

        $filename="goods_list";

        $this->getExceltj($filename,$headArr,$data);
    }
				}
				

   private  function getExceltj($fileName,$headArr,$data){
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
	
	
	
	
	
	
	//订单条件导出二
	
    public function tjdaochub()
    {
		ob_end_clean();
		 
		if(IS_POST){
			$keyword=I('post.keyword');
			$time_start=I('post.time_start');
			$time_end=I('post.time_end');
			$username=I('post.username');
			$idhao=I('post.idhao');
			$tel=I('post.tel');
			$status=I('post.status');
			$Is_ok=I('post.Is_ok');
		
		
			$where="1=1  ";
			//$where=$where." and status=".$status." ";
				
			if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($username) && empty($idhao) && empty($tel)){
					$where="1=1";
				if($status!=''){
					//echo "aa";exit;
				
					$where=$where." and status=".$status." ";
				}
				if($Is_ok!=''){
					//echo "aa";exit;
				
					$where=$where." and Is_ok=".$Is_ok." ";
				}
				
				if($time_start and $time_end){
					$where=$where." and delivery_time between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}
			    //$where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			
		//	echo $where;exit;
			}else{
				if($keyword){
					$where=$where." and dingdan like '%".$keyword."%'";
				}
				
				if($status){
					$where=$where."and status=".$status."";
					
					}
					
				if($Is_ok){
				   $where=$where." and Is_ok=".$Is_ok." ";
				}
				
				if($username){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$username."%') ";
				}
				
				if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and delivery_time between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}else{
					
					$where=$where." and delivery_time=".strtotime(date('Y-m-d'))." ";
					}
				
			
			}
			
		//echo $where;
			
	   $goods_list=M('order')->where($where)->order('id desc')->select();	
	  // echo M()->_sql();exit;
	 
		//$goods_list = M('user')->select();
		
		//***********************************
		
		for($i=0;$i<count($goods_list);$i++){
			$name_adr= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid']))->find();
//			$name= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'],'status'=>1))->getField('shperson');
//			$tel= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'] ,'status'=>1))->getField('mobile');
//			$ads= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'] ,'status'=>1))->getField('address');
//			$star=M('sundry')->where(array('id'=>$goods_list[$i]['deli_id']))->getField('start_time');
//			$end=M('sundry')->where(array('id'=>$goods_list[$i]['deli_id']))->getField('over_time');
		    $goods_list[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$goods_list[$i]['id'])->select();
	
	
			$goods_list[$i]['name'] =$name_adr['shperson'];
			$goods_list[$i]['tel'] =$name_adr['mobile'];
			$goods_list[$i]['ads'] =$name_adr['province'].$name_adr['city'].$name_adr['county'].$name_adr['address'];
//			$goods_list[$i]['star']=$star;
//			$goods_list[$i]['end']=$end;
			
			$res = M('item')->field('id,title,unit')->select();
			$gwc_title = array();
			foreach ($res as $val) {
				$gwc_title[$val['id']] = $val['title'];
			}
	     		
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
				$gwc_unit[$val['id']] ="(" .$val['unit'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			//$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
				$gwc_value[$val['id']] = "(".$val['attr_value'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			//$this->assign('gwc_value', $gwc_value);	
			
		
		}
		
			
			//dump($goods_list[0]['sub']);exit;
			//foreach($goods_list[])
			
		//**************************************
     
		$data = array();
        foreach ($goods_list as $k=>$goods_info){
			
			$reg=array();
			foreach($goods_list[$k]['sub'] as $i =>$val){
			//dump($val);	
			if($val['did']==0){
//				$reg[$i]=$gwc_title[$val['jid']].''.$gwc_unit[$val['jid']].'/'.$val['nums'].'/';
				$reg[$i]=$gwc_title[$val['jid']].'。数量 ：'.$val['nums'].'/';
			}else{
				$reg[$i]=$gwc_title[$val['jid']].''.$gwc_value[$val['did']].'/'.$val['nums'].'/';
			}
			
		}
			
			//var_dump($reg[0]);
			$aa=implode(';',$reg);
//		$dates=date('Y-m-d',$goods_info['delivery_time']);
//		$sten=$dates.' '.$goods_info['star'].'  '.$goods_info['end'];
		
		$datex=date('Y-m-d H-m-s',$goods_info['addtime']);
			
			$data[$k][dingdan] =' '.$goods_info['dingdan'];     //订单号
            $data[$k][totalprices] = $goods_info['totalprices']; //金额
            $data[$k][zftype] = zffsa($goods_info['zftype']); //支付方式
			$data[$k][status]=xgstatusa($goods_info['status']);  //状态
			$data[$k][uid]=' '.$goods_info['uid'];     //顾客ID
			$data[$k][name]=$goods_info['name'];  //姓名
			$data[$k][tel]=' '.$goods_info['tel'];   //电话
			$data[$k][ads]=$goods_info['ads']; //配送地址
			$data[$k][jid]= $aa; //详情
			$data[$k][memos]=$goods_info['memos']; //备注
//			$data[$k][delivery_time]=$sten;//收货时间
			$data[$k][addtime]=$datex;//下单时间
        }
		//exit;
        //print_r($goods_list);
        //print_r($data);exit;

        foreach ($data as $field=>$v){
            if($field == 'dingdan'){
                $headArr[]='订单号';
            }

            if($field == 'totalprices'){
                $headArr[]='金额';
            }
			
			if($field == 'zftype'){
                $headArr[]='支付方式';
            }
			if($field == 'status'){
                $headArr[]='状态';
            }
			if($field == 'uid'){
                $headArr[]='顾客ID';
            }
			if($field == 'name'){
                $headArr[]='姓名';
            }
			if($field == 'tel'){
                $headArr[]='电话';
            }
			if($field == 'ads'){
                $headArr[]='配送地址';
            }
			if($field == 'jid'){
                $headArr[]='详情';
            }
			if($field == 'memos'){
                $headArr[]='备注';
            }
//			if($field == 'delivery_time'){
//                $headArr[]='收货时间';
//            }
			if($field == 'addtime'){
                $headArr[]='下单时间';
            }
			
			
        }

        $filename="goods_list";

        $this->getExceltja($filename,$headArr,$data);
    }
				}
				

   private  function getExceltja($fileName,$headArr,$data){
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
	
	
	
		
	//订单单个导出
	
	public function dgdaochu(){
		
      ob_end_clean();
	  $id=I('get.id');
		 
	
			
		//echo $where;
			
	   $goods_list=M('order')->where("id=".$id."")->order('id desc')->select();	
	  // echo M()->_sql();exit;
	 
		//$goods_list = M('user')->select();
		
		//***********************************
		
		for($i=0;$i<count($goods_list);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'] ,'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'] ,'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$goods_list[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$goods_list[$i]['deli_id']))->getField('over_time');
		    $goods_list[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$goods_list[$i]['id'])->select();
	
	
			$goods_list[$i]['name'] =$name;
			$goods_list[$i]['tel'] =$tel;
			$goods_list[$i]['ads'] =$ads;
			$goods_list[$i]['star']=$star;
			$goods_list[$i]['end']=$end;
			
			$res = M('item')->field('id,title,unit')->select();
			$gwc_title = array();
			foreach ($res as $val) {
				$gwc_title[$val['id']] = $val['title'];
			}
	     		
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
				$gwc_unit[$val['id']] ="(" .$val['unit'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			//$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
				$gwc_value[$val['id']] = "(".$val['attr_value'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			//$this->assign('gwc_value', $gwc_value);	
			
		
		}
		
			
			//dump($goods_list[0]['sub']);exit;
			//foreach($goods_list[])
			
		//**************************************
     
		$data = array();
        foreach ($goods_list as $k=>$goods_info){
			
			$reg=array();
			foreach($goods_list[$k]['sub'] as $i =>$val){
			//dump($val);	
			if($val['did']==0){
			$reg[$i]=$gwc_title[$val['jid']].''.$gwc_unit[$val['jid']].'/'.$val['nums'].'/';
				
				}else{
			$reg[$i]=$gwc_title[$val['jid']].''.$gwc_value[$val['did']].'/'.$val['nums'].'/';
				}
			
		}
			
			//var_dump($reg[0]);
			$aa=implode(';',$reg);
		$dates=date('Y-m-d',$goods_info['delivery_time']);	
		$sten=$dates.' '.$goods_info['star'].'  '.$goods_info['end'];
		
		$datex=date('Y-m-d H-m-s',$goods_info['addtime']);
			
			$data[$k][dingdan] =' '.$goods_info['dingdan'];     //订单号
            $data[$k][totalprices] = $goods_info['totalprices']; //金额
            $data[$k][zftype] = zffsa($goods_info['zftype']); //支付方式
			$data[$k][status]=xgstatusa($goods_info['status']);  //状态
			$data[$k][uid]=' '.$goods_info['uid'];     //顾客ID
			$data[$k][name]=$goods_info['name'];  //姓名
			$data[$k][tel]=' '.$goods_info['tel'];   //电话
			$data[$k][ads]=$goods_info['ads']; //配送地址
			$data[$k][jid]= $aa; //详情
			$data[$k][memos]=$goods_info['memos']; //备注
			$data[$k][delivery_time]=$sten;//收货时间
			$data[$k][addtime]=$datex;//下单时间
        }
		//exit;
        //print_r($goods_list);
        //print_r($data);exit;

        foreach ($data as $field=>$v){
            if($field == 'dingdan'){
                $headArr[]='订单号';
            }

            if($field == 'totalprices'){
                $headArr[]='金额';
            }
			
			if($field == 'zftype'){
                $headArr[]='支付方式';
            }
			if($field == 'status'){
                $headArr[]='状态';
            }
			if($field == 'uid'){
                $headArr[]='顾客ID';
            }
			if($field == 'name'){
                $headArr[]='姓名';
            }
			if($field == 'tel'){
                $headArr[]='电话';
            }
			if($field == 'ads'){
                $headArr[]='配送地址';
            }
			if($field == 'jid'){
                $headArr[]='详情';
            }
			if($field == 'memos'){
                $headArr[]='备注';
            }
			if($field == 'delivery_time'){
                $headArr[]='收货时间';
            }
			if($field == 'addtime'){
                $headArr[]='下单时间';
            }
			
			
        }

        $filename="goods_list";

        $this->getExceltjab($filename,$headArr,$data);
    }
				
				

   private  function getExceltjab($fileName,$headArr,$data){
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
	
	
	
		
	
	
	
	
	
	
	public function edit(){		
		$order=M("order");		
		if(IS_POST){
			$id=I("post.id");
			$status=I("post.status");
			$data['status']=$status;
			$order->where("id=".$id."")->save($data);
			$this->success("状态修改成功");
		}else{
			$id=I("get.id");
			$info=$order->where("id=".$id."")->find();
			$name= M('member') ->where(array('id'=>$info['uid']))->getField('nickname');
			$info['name'] =$name;
			$this->assign("info",$info);
			$this->display();
		}		
	}
	
	public function lists(){
		$id=I("get.id");
		$item=M("item");
		$order=M("order");
		$order_list=M("order_list");
		$rest=$order_list->where("oid=".$id."")->select();
		$this->assign("rest",$rest);
		
        /*$res = $item->field('id,title')->select();
        $array_title = array();
        foreach ($res as $val) {
            $array_title[$val['id']] = $val['title'];
        }
		$this->assign('array_title', $array_title);
		
		
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] ="(" .$val['unit'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] = "(".$val['attr_value'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			*/
			 
		
	
		
		//调用订单
		$res1 = $order->field('id,memos,dingdan,tiaoxingma')->select();
        $array_dingdan = array();
		$array_tiaoxingma = array();
        foreach ($res1 as $val1) {
            $array_dingdan[$val1['id']] = $val1['dingdan'];
			 $array_memos[$val1['id']] =	 $val1['memos'];
			 $array_tiaoxingma[$val1['id']] =	 $val1['tiaoxingma'];
	    }
		
		$this->assign('array_dingdan', $array_dingdan);
		$this->assign('array_memos', $array_memos);
		$this->assign('array_tiaoxingma', $array_tiaoxingma);
		$aid = M('order') ->where(array('id'=>$id))->getField('aid');
		$addr = M('memberGoodsaddress') ->where(array('id'=>$aid))->find();
		$addr['new_dz'] = $addr['province'].$addr['city'].$addr['county'].$addr['address'];
		$this->assign('addr',$addr);
	    
		/*$or=M('order')->where(array('id'=>$id))->find();
		$yhq = M('yhq') -> where(array('id'=>$or['cid'])) -> find();
		//var_dump($or);
		$this->assign('or',$or);
		$this->assign('yhq',$yhq);*/
	    
		
		$this->display();
	}
	
	
	
	
	//配送路线
	public function pei(){
		//echo "aa";exit;
		$order=M('order');
		$count = $order->where("1=1 and Is_ok=2 ")->count();
		//echo $count;
			$Page = new \Think\Page($count,10);
			
			$list=$order->limit($Page->firstRow.','.$Page->listRows)->where("1=1 and Is_ok=2 ")->order('id desc')->select(); 
			$show = $Page->show();
			$this->assign('page',$show);
			 //  $list=$order->order('id desc')->select();
			  // echo M()->_sql();
			for($i=0;$i<count($list);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('address');
			$rim= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid']))->getField('rim');
			$star=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('over_time');
			$city_name=M('place')->where('type=2 and bd_city_code = (select city_id from jrkj_member where id='.$list[$i]['uid'].' )')->getField('name');
		    $list[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$list[$i]['id'])->select();
			//echo M()->_sql();
	        $this->assign('title',$title);
		   // dump($list);
			// $lista[$i]['shang']=$title;
			//dump($city_name);
			$list[$i]['name'] =$name;
			$list[$i]['tel'] =$tel;
			$list[$i]['ads'] =$ads;
			$list[$i]['star']=$star;
			$list[$i]['end']=$end;
			$list[$i]['rim']=$rim;
			$list[$i]['city_name']=$city_name;
			$res = M('item')->field('id,title')->select();
			$gwc_title = array();
			foreach ($res as $val) {
			$gwc_title[$val['id']] = $val['title'];
			//$gwc_title[$val['id']] = $val['title'];
			}
	        $this->assign('gwc_title', $gwc_title);
			$resa = M('item')->field('id,unit')->select();
			$gwc_unit = array();
			foreach ($resa as $val) {
			$gwc_unit[$val['id']] ="(" .$val['unit'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_unit', $gwc_unit);	
			
			$resb = M('item_attr')->field('id,attr_value')->select();
			$gwc_value = array();
			foreach ($resb as $val) {
			$gwc_value[$val['id']] = "(".$val['attr_value'].")";
			//$gwc_title[$val['id']] = $val['title'];
			}
			$this->assign('gwc_value', $gwc_value);	
			//var_dump($gwc_value);
			 }//exit;
		$this->assign('list',$list);
		
		//dump($list);
	
	   $this->display();
	}
	
	
	
	
	
	
	public function xiu(){

		if(IS_POST){
			
			$textarea=I('post.textarea');
			$psr=I('post.psr');
			$ssr=I('post.ssr');
			$qssj=I('post.qssj');
			
			$peikuang=I('post.peikuang');
			$qiangkuang=I('post.qiangkuang');
			$id=I('post.id');
			$jigan=strtotime($qssj);
			$datas['id']=$id;
	        $datas['comment']=$textarea;
			$datas['picking']=$psr;
			$datas['deliveryman']=$ssr;
			$datas['sign_time']=$jigan;
			$datas['peikuang']=$peikuang;
			$datas['qiangkuang']=$qiangkuang;
		 
			
		
			$rest=M('order')->save($datas);
			//echo M()->_sql();exit;
			
		
			if($rest>0){
				$this->success('修改成功！');
				}else{
					$this->error('修改无效！');
					}
			
			
			}
		
		
		}
	
	
	
	//库存订单
	public function kclist(){
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		//echo strtotime(date('Y-m-d'));
		$res=$item_cate->where("pid=0")->select();
		foreach($res as $ks=>$vs){
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where  cate_id in(select id from jrkj_item_cate where id=".$vs['id']." or pid=".$vs['id']."))")->order("jid asc")->select();
			//echo M()->_sql()."<br>";exit;
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where("jid=".$v['jid']." and did=".$v['did']." and oid in(select id from jrkj_order where Is_ok=2 ) ")->field("sum(nums) as nums,jid,did")->select();
				//echo M()->_sql()."<br><br>";
				//echo $res[$ks]['all'][$k]['total'][0]['jid']."<br>";
			}
			
		}
		//dump($res);
		$this->assign("res",$res);
		
		//调用商品的标题
		$rest = $item->field('id,title,unit')->select();
		$gwc_title = array();
		foreach ($rest as $val){
			$gwc_title[$val['id']] = $val['title'];
			$shop_value[$val['id']] = $val['unit'];
		}
		$this->assign('gwc_title', $gwc_title);
		$this->assign('shop_value', $shop_value);
		
		//调用商品的规格
		$res1 = $item_attr->field('id,attr_value')->select();
		$gwc_value = array();
		foreach ($res1 as $val){
			$gwc_value[$val['id']] = $val['attr_value'];
		}
		$this->assign('gwc_value', $gwc_value);
		
		//调用订单的备注
		$res2 = $order->field('id,memos')->select();
		$shop_memos = array();
		foreach ($res2 as $val){
			$shop_memos[$val['id']] = $val['memos'];
		}
		$this->assign('shop_memos', $shop_memos);
		
		//调用商品小类别
		$res3 = $item->field('id,cate_id')->select();
		$shop_cid = array();
		foreach ($res3 as $val){
			$shop_cid[$val['id']] = $val['cate_id'];
		}
		$this->assign('shop_cid', $shop_cid);
		
		//调用商品小类别
		$res4 = $item_cate->field('id,name')->select();
		$shop_types = array();
		foreach ($res4 as $val){
			$shop_types[$val['id']] = $val['name'];
		}
		$this->assign('shop_types', $shop_types);
		
		$this->display();
	}
	
	
	
	//库存订单打印页面
	public function kclistda(){
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		//echo strtotime(date('Y-m-d'));
		$res=$item_cate->where("pid=0")->select();
		foreach($res as $ks=>$vs){
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where  cate_id in(select id from jrkj_item_cate where id=".$vs['id']." or pid=".$vs['id']."))")->order("jid asc")->select();
			//echo M()->_sql()."<br>";
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where("jid=".$v['jid']." and did=".$v['did']." and oid in(select id from jrkj_order where Is_ok=2) ")->field("sum(nums) as nums")->select();
				//echo M()->_sql()."<br><br>";
			}
		}
		
		//dump($res);
		$this->assign("res",$res);
		
		//调用商品的标题
		$rest = $item->field('id,title,unit')->select();
		$gwc_title = array();
		foreach ($rest as $val){
			$gwc_title[$val['id']] = $val['title'];
			$shop_value[$val['id']] = $val['unit'];
		}
		$this->assign('gwc_title', $gwc_title);
		$this->assign('shop_value', $shop_value);
		
		//调用商品的规格
		$res1 = $item_attr->field('id,attr_value')->select();
		$gwc_value = array();
		foreach ($res1 as $val){
			$gwc_value[$val['id']] = $val['attr_value'];
		}
		$this->assign('gwc_value', $gwc_value);
		
		//调用订单的备注
		$res2 = $order->field('id,memos')->select();
		$shop_memos = array();
		foreach ($res2 as $val){
			$shop_memos[$val['id']] = $val['memos'];
		}
		$this->assign('shop_memos', $shop_memos);
		
		//调用商品小类别
		$res3 = $item->field('id,cate_id')->select();
		$shop_cid = array();
		foreach ($res3 as $val){
			$shop_cid[$val['id']] = $val['cate_id'];
		}
		$this->assign('shop_cid', $shop_cid);
		
		//调用商品小类别
		$res4 = $item_cate->field('id,name')->select();
		$shop_types = array();
		foreach ($res4 as $val){
			$shop_types[$val['id']] = $val['name'];
		}
		$this->assign('shop_types', $shop_types);
		
		$this->display();
	}
	
	
	
	
	
	
	//非库存订单
	public function fkclist(){
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		
		$res=$item_cate->where("pid=0")->select();
		foreach($res as $ks=>$vs){
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where inv_status=2 and cate_id in(select id from jrkj_item_cate where id=".$vs['id']." or pid=".$vs['id']."))")->order("jid asc")->select();
			//echo M()->_sql()."<br>";
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where("jid=".$v['jid']." and did=".$v['did']." and oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where inv_status=2)")->field("sum(nums) as nums")->select();
				//echo M()->_sql()."<br><br>";
			}
		}
		$this->assign("res",$res);
		
		//调用商品的标题
		$rest = $item->field('id,title,unit')->select();
		$gwc_title = array();
		foreach ($rest as $val){
			$gwc_title[$val['id']] = $val['title'];
			$shop_value[$val['id']] = $val['unit'];
		}
		$this->assign('gwc_title', $gwc_title);
		$this->assign('shop_value', $shop_value);
		
		//调用商品的规格
		$res1 = $item_attr->field('id,attr_value')->select();
		$gwc_value = array();
		foreach ($res1 as $val){
			$gwc_value[$val['id']] = $val['attr_value'];
		}
		$this->assign('gwc_value', $gwc_value);
		
		//调用订单的备注
		$res2 = $order->field('id,memos')->select();
		$shop_memos = array();
		foreach ($res2 as $val){
			$shop_memos[$val['id']] = $val['memos'];
		}
		$this->assign('shop_memos', $shop_memos);
		
		//调用商品小类别
		$res3 = $item->field('id,cate_id')->select();
		$shop_cid = array();
		foreach ($res3 as $val){
			$shop_cid[$val['id']] = $val['cate_id'];
		}
		$this->assign('shop_cid', $shop_cid);
		
		//调用商品小类别
		$res4 = $item_cate->field('id,name')->select();
		$shop_types = array();
		foreach ($res4 as $val){
			$shop_types[$val['id']] = $val['name'];
		}
		$this->assign('shop_types', $shop_types);
		
		$this->display();
	}
	
	
	//非库存订单打印
	public function fkclistda(){
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		
		$res=$item_cate->where("pid=0")->select();
		foreach($res as $ks=>$vs){
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=2 ) and jid in(select id from jrkj_item where inv_status=2 and cate_id in(select id from jrkj_item_cate where id=".$vs['id']." or pid=".$vs['id']."))")->order("jid asc")->select();
			//echo M()->_sql()."<br>";
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where("jid=".$v['jid']." and did=".$v['did']." and oid in(select id from jrkj_order where Is_ok=2 ) and jid in(select id from jrkj_item where inv_status=2)")->field("sum(nums) as nums")->select();
				//echo M()->_sql()."<br><br>";
			}
		}
		$this->assign("res",$res);
		
		//调用商品的标题
		$rest = $item->field('id,title,unit')->select();
		$gwc_title = array();
		foreach ($rest as $val){
			$gwc_title[$val['id']] = $val['title'];
			$shop_value[$val['id']] = $val['unit'];
		}
		$this->assign('gwc_title', $gwc_title);
		$this->assign('shop_value', $shop_value);
		
		//调用商品的规格
		$res1 = $item_attr->field('id,attr_value')->select();
		$gwc_value = array();
		foreach ($res1 as $val){
			$gwc_value[$val['id']] = $val['attr_value'];
		}
		$this->assign('gwc_value', $gwc_value);
		
		//调用订单的备注
		$res2 = $order->field('id,memos')->select();
		$shop_memos = array();
		foreach ($res2 as $val){
			$shop_memos[$val['id']] = $val['memos'];
		}
		$this->assign('shop_memos', $shop_memos);
		
		//调用商品小类别
		$res3 = $item->field('id,cate_id')->select();
		$shop_cid = array();
		foreach ($res3 as $val){
			$shop_cid[$val['id']] = $val['cate_id'];
		}
		$this->assign('shop_cid', $shop_cid);
		
		//调用商品小类别
		$res4 = $item_cate->field('id,name')->select();
		$shop_types = array();
		foreach ($res4 as $val){
			$shop_types[$val['id']] = $val['name'];
		}
		$this->assign('shop_types', $shop_types);
		
		$this->display();
	}
	
	
	
	
	
	
    
	public function personlist(){
		$item=M("item");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		$member=M("member");
		$member_goodsaddress=M("member_goodsaddress");
		if(IS_POST){
			$keyword=I("post.keyword");
			$this->assign("keyword",$keyword);
			
			$order=M("order");
            $keywording=I("post.keywording");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keywording",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
			$where="1=1";
		
		
		
		if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($keywording) && empty($idhao) && empty($tel)){
			   // $where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			   $where="1=1";
			
			}else{
				if($keywording){
					
					//  dump($keywording);
					
					$where=$where." and dingdan like '%".$keywording."%'";
				}
			if($keyword){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$keyword."%') ";
				}
			 if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and addtime between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}
				
				
				
			}
			
			//$where=$where." and Is_ok=2 and delivery_time between ".strtotime(date("Y-m-d"))." and ".strtotime(date("Y-m-d",strtotime("+1 day")))."";
			$where=$where." and Is_ok=2";
			$list=$order->where($where)->order('id desc')-> select();
			//echo M()->_sql();
			//echo time();
			foreach($list as $k=>$v){
				 $list[$k]['total']=$order_list->where('oid='.$v["id"].'')->select();
				 //echo M()->_sql()."<br>";
			}	
			$this->assign("list",$list);
			
			//调用会员的名称，电话，地址
			//$res = $member->field('id,realname,mobile,address')->select();
			$res = $member_goodsaddress->field('id,shperson,mobile,address')->select();
			$member_name = array();
			$member_mobile = array();
			$member_address = array();
			foreach ($res as $val){
				$member_name[$val['id']] = $val['shperson'];
				$member_mobile[$val['id']] = $val['mobile'];
				$member_address[$val['id']] = $val['address'];
			}
			$this->assign('member_name', $member_name);
			$this->assign('member_mobile', $member_mobile);
			$this->assign('member_address', $member_address);
			
			
			//调用商品名称
			$res1 = $item->field('id,title,unit')->select();
			$item_title = array();
			$item_unit = array();
			foreach ($res1 as $val){
				$item_title[$val['id']] = $val['title'];
				$item_unit[$val['id']] = $val['unit'];
			}
			$this->assign('item_title', $item_title);
			$this->assign('item_unit', $item_unit);		
			
			
			//调用商品名称
			$res2 = $item_attr->field('id,attr_value')->select();
			$item_guige = array();
			foreach ($res2 as $val){
				$item_guige[$val['id']] = $val['attr_value'];
			}
			$this->assign('item_guige', $item_guige);	
					
		}
		$this->display();	
	}
	
	
	//对账打印
	
	public function personlistda(){
		$item=M("item");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		$member=M("member");
		$member_goodsaddress=M("member_goodsaddress");
		if(IS_POST){
			$keyword=I("post.keyword");
			$this->assign("keyword",$keyword);
			
			$order=M("order");
            $keywording=I("post.keywording");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keywording",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
			$where="1=1";
		
		
		
		if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($keywording) && empty($idhao) && empty($tel)){
			   // $where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			   $where="1=1";
			
			}else{
				if($keywording){
					
					//  dump($keywording);
					
					$where=$where." and dingdan like '%".$keywording."%'";
				}
			if($keyword){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$keyword."%') ";
				}
			 if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and addtime between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}
				
				
				
			}
			
			//$where=$where." and Is_ok=2 and delivery_time between ".strtotime(date("Y-m-d"))." and ".strtotime(date("Y-m-d",strtotime("+1 day")))."";
			$where=$where." and Is_ok=2";
			$list=$order->where($where)->order('id desc')-> select();
			//echo M()->_sql();
			//echo time();
			foreach($list as $k=>$v){
				 $list[$k]['total']=$order_list->where('oid='.$v["id"].'')->select();
				 //echo M()->_sql()."<br>";
			}	
			$this->assign("list",$list);
			
			//调用会员的名称，电话，地址
			//$res = $member->field('id,realname,mobile,address')->select();
			$res = $member_goodsaddress->field('id,shperson,mobile,address')->select();
			$member_name = array();
			$member_mobile = array();
			$member_address = array();
			foreach ($res as $val){
				$member_name[$val['id']] = $val['shperson'];
				$member_mobile[$val['id']] = $val['mobile'];
				$member_address[$val['id']] = $val['address'];
			}
			$this->assign('member_name', $member_name);
			$this->assign('member_mobile', $member_mobile);
			$this->assign('member_address', $member_address);
			
			
			//调用商品名称
			$res1 = $item->field('id,title,unit')->select();
			$item_title = array();
			$item_unit = array();
			foreach ($res1 as $val){
				$item_title[$val['id']] = $val['title'];
				$item_unit[$val['id']] = $val['unit'];
			}
			$this->assign('item_title', $item_title);
			$this->assign('item_unit', $item_unit);		
			
			
			//调用商品名称
			$res2 = $item_attr->field('id,attr_value')->select();
			$item_guige = array();
			foreach ($res2 as $val){
				$item_guige[$val['id']] = $val['attr_value'];
			}
			$this->assign('item_guige', $item_guige);	
					
		}
		$this->display();	
	}
		
		
	
	
	
	
	
	
	public function fenjian1(){
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		$res=$order_list->field('jid,did,nums')->where("oid in(select id from jrkj_order where Is_ok=2)")->order("jid desc,did desc,id desc")->select();
		//echo M()->_sql();
		//dump($res);
	
			
	//dump($res);
			
		
		
		$this->assign("res",$res);
		
		//调用商品的标题
		$rest = $item->field('id,title,unit')->select();
		$gwc_title = array();
		foreach ($rest as $val){
			$gwc_title[$val['id']] = $val['title'];
			$shop_value[$val['id']] = $val['unit'];
		}
		$this->assign('gwc_title', $gwc_title);
		$this->assign('shop_value', $shop_value);
		
		//调用商品的规格
		$res1 = $item_attr->field('id,attr_value')->select();
		$gwc_value = array();
		foreach ($res1 as $val){
			$gwc_value[$val['id']] = $val['attr_value'];
		}
		$this->assign('gwc_value', $gwc_value);
		
		//调用订单的备注
		$res2 = $order->field('id,memos')->select();
		$shop_memos = array();
		foreach ($res2 as $val){
			$shop_memos[$val['id']] = $val['memos'];
		}
		$this->assign('shop_memos', $shop_memos);
		
		//调用商品小类别
		$res3 = $item->field('id,cate_id')->select();
		$shop_cid = array();
		foreach ($res3 as $val){
			$shop_cid[$val['id']] = $val['cate_id'];
		}
		$this->assign('shop_cid', $shop_cid);
		
		//调用商品小类别
		$res4 = $item_cate->field('id,name')->select();
		$shop_types = array();
		foreach ($res4 as $val){
			$shop_types[$val['id']] = $val['name'];
		}
		$this->assign('shop_types', $shop_types);
		
		$this->display();
	}
	
	
	
	//分拣一打印页面
	
	public function fenjian1da(){
		
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		$res=$order_list->DISTINCT('ture')->field('jid,did')->where("oid in(select id from jrkj_order where Is_ok=2)")->order("jid desc,did desc,id desc")->select();
		//echo M()->_sql();
		//dump($res);
		foreach($res as $k=>$va){
			$resa=$order_list->where("oid in(select id from jrkj_order where Is_ok=2)")->order("jid desc,did desc,id desc")->select();			
			foreach($resa as $key=>$val){ 
			if($va['jid']==$val['jid'] && $va['id']!=$val['id'] && $va['did']==$val['did'] ){
				$va['nums']+=$val['nums'];
				$res[$k]['numsa']=$va['nums'];		
			}
		}
	}
			
	//dump($res);
			
		
		
		$this->assign("res",$res);
		
		//调用商品的标题
		$rest = $item->field('id,title,unit')->select();
		$gwc_title = array();
		foreach ($rest as $val){
			$gwc_title[$val['id']] = $val['title'];
			$shop_value[$val['id']] = $val['unit'];
		}
		$this->assign('gwc_title', $gwc_title);
		$this->assign('shop_value', $shop_value);
		
		//调用商品的规格
		$res1 = $item_attr->field('id,attr_value')->select();
		$gwc_value = array();
		foreach ($res1 as $val){
			$gwc_value[$val['id']] = $val['attr_value'];
		}
		$this->assign('gwc_value', $gwc_value);
		
		//调用订单的备注
		$res2 = $order->field('id,memos')->select();
		$shop_memos = array();
		foreach ($res2 as $val){
			$shop_memos[$val['id']] = $val['memos'];
		}
		$this->assign('shop_memos', $shop_memos);
		
		//调用商品小类别
		$res3 = $item->field('id,cate_id')->select();
		$shop_cid = array();
		foreach ($res3 as $val){
			$shop_cid[$val['id']] = $val['cate_id'];
		}
		$this->assign('shop_cid', $shop_cid);
		
		//调用商品小类别
		$res4 = $item_cate->field('id,name')->select();
		$shop_types = array();
		foreach ($res4 as $val){
			$shop_types[$val['id']] = $val['name'];
		}
		$this->assign('shop_types', $shop_types);
		
		
		$this->display();
	}
	
	
	
	
	
	public function fenjian2(){
		$item=M("item");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		$member=M("member");
		$member_goodsaddress=M("member_goodsaddress");
		if(IS_POST){
			$keyword=I("post.keyword");
			$this->assign("keyword",$keyword);
			
			$order=M("order");
            $keywording=I("post.keywording");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keywording",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
			$where="1=1";
		
		
		
		if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($keywording) && empty($idhao) && empty($tel)){
			   // $where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			   $where="1=1";
			
			}else{
				if($keywording){
					
					//  dump($keywording);
					
					$where=$where." and dingdan like '%".$keywording."%'";
				}
			if($keyword){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$keyword."%') ";
				}
			 if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and addtime between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}
				
				
				
			}
			
			
			
			//echo $where;exit;
			
			
			
			
			
			
			//$where=$where." and Is_ok=2 and delivery_time between ".strtotime(date("Y-m-d"))." and ".strtotime(date("Y-m-d",strtotime("+1 day")))."";
			$where=$where." and Is_ok=2";
			$list=$order->where($where)->order('id desc')-> select();
			//echo M()->_sql();
			//echo time();
			foreach($list as $k=>$v){
				 $list[$k]['total']=$order_list->where('oid='.$v["id"].'')->select();
				 //echo M()->_sql()."<br>";
			}	
			$this->assign("list",$list);
			
			//调用会员的名称，电话，地址
			//$res = $member->field('id,realname,mobile,address')->select();
			$res = $member_goodsaddress->field('id,shperson,mobile,address')->select();
			$member_name = array();
			$member_mobile = array();
			$member_address = array();
			foreach ($res as $val){
				$member_name[$val['id']] = $val['shperson'];
				$member_mobile[$val['id']] = $val['mobile'];
				$member_address[$val['id']] = $val['address'];
			}
			$this->assign('member_name', $member_name);
			$this->assign('member_mobile', $member_mobile);
			$this->assign('member_address', $member_address);
			
			
			//调用商品名称
			$res1 = $item->field('id,title,unit')->select();
			$item_title = array();
			$item_unit = array();
			foreach ($res1 as $val){
				$item_title[$val['id']] = $val['title'];
				$item_unit[$val['id']] = $val['unit'];
			}
			$this->assign('item_title', $item_title);
			$this->assign('item_unit', $item_unit);		
			
			
			//调用商品名称
			$res2 = $item_attr->field('id,attr_value')->select();
			$item_guige = array();
			foreach ($res2 as $val){
				$item_guige[$val['id']] = $val['attr_value'];
			}
			$this->assign('item_guige', $item_guige);	
					
		}
		$this->display();
	}
	
	
	
	
	public function fenjian2da(){
		$item=M("item");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		$member=M("member");
		$member_goodsaddress=M("member_goodsaddress");
		if(IS_POST){
			$keyword=I("post.keyword");
			$this->assign("keyword",$keyword);
			
			$order=M("order");
            $keywording=I("post.keywording");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keywording",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("username",$username);
			$this->assign("idhao",$idhao);
			$this->assign("tel",$tel);
			$where="1=1";
		
		
		
		if(empty($keyword)&& empty($time_start) && empty($time_end) && empty($keywording) && empty($idhao) && empty($tel)){
			   // $where=$where." and delivery_time='".strtotime(date('Y-m-d'))."'";
			   $where="1=1";
			
			}else{
				if($keywording){
					
					//  dump($keywording);
					
					$where=$where." and dingdan like '%".$keywording."%'";
				}
			if($keyword){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where shperson like '%".$keyword."%') ";
				}
			 if($idhao){
					$where=$where." and uid like '%".$idhao."%'";
				}
				
				if($tel){
					$where=$where." and aid in(select id from jrkj_member_goodsaddress where mobile like '%".$tel."%') ";
				}
	
				if($time_start and $time_end){
					$where=$where." and addtime between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
				}
				
				
				
			}
			
			
			
			//echo $where;exit;
			
			
			
			
			
			
			//$where=$where." and Is_ok=2 and delivery_time between ".strtotime(date("Y-m-d"))." and ".strtotime(date("Y-m-d",strtotime("+1 day")))."";
			$where=$where." and Is_ok=2";
			$list=$order->where($where)->order('id desc')-> select();
			//echo M()->_sql();
			//echo time();
			foreach($list as $k=>$v){
				 $list[$k]['total']=$order_list->where('oid='.$v["id"].'')->select();
				 //echo M()->_sql()."<br>";
			}	
			$this->assign("list",$list);
			
			//调用会员的名称，电话，地址
			//$res = $member->field('id,realname,mobile,address')->select();
			$res = $member_goodsaddress->field('id,shperson,mobile,address')->select();
			$member_name = array();
			$member_mobile = array();
			$member_address = array();
			foreach ($res as $val){
				$member_name[$val['id']] = $val['shperson'];
				$member_mobile[$val['id']] = $val['mobile'];
				$member_address[$val['id']] = $val['address'];
			}
			$this->assign('member_name', $member_name);
			$this->assign('member_mobile', $member_mobile);
			$this->assign('member_address', $member_address);
			
			
			//调用商品名称
			$res1 = $item->field('id,title,unit')->select();
			$item_title = array();
			$item_unit = array();
			foreach ($res1 as $val){
				$item_title[$val['id']] = $val['title'];
				$item_unit[$val['id']] = $val['unit'];
			}
			$this->assign('item_title', $item_title);
			$this->assign('item_unit', $item_unit);		
			
			
			//调用商品名称
			$res2 = $item_attr->field('id,attr_value')->select();
			$item_guige = array();
			foreach ($res2 as $val){
				$item_guige[$val['id']] = $val['attr_value'];
			}
			$this->assign('item_guige', $item_guige);	
					
		}
		$this->display();
	}
	
	
	
	
	
	
	 //导出单表数据方法
    public function goods_export_duo()
    {
		
		
		$goods_list = M('order_list')->where('jid in(select id from jrkj_item where inv_status=1)  and oid in(select id from jrkj_order where Is_ok=1)')->select();
        //print_r($goods_list);exit;
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
			$data[$k][jid] = $goods_info['jid'];
            $data[$k][did] = $goods_info['did'];
            $data[$k][nums] = $goods_info['nums'];
			//$data[$k][unit] = $goods_info['unit'];
        }
        //print_r($goods_list);
       // print_r($data);exit;

        foreach ($data as $field=>$v){
            if($field == 'jid'){
                $headArr[]='商品名称';
            }

            if($field == 'did'){
                $headArr[]='规格';
            }
			
			if($field == 'nums'){
                $headArr[]='订单数量';
            }
			If($field==''){
				$headArr[]='备注';
				}
        }

    // print_r($data);exit;
        $filename="goods_list";

        $this->getExcel($filename,$headArr,$data);
    }


    public  function getExcel($fileName,$headArr,$data){
        //导入PHPExcel类库
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");

        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls";

        //创建PHPExcel对象\ 
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

        
        foreach($data as $key => $rows){ //行写入
		
		$item=M("item");
		$rest = $item->field('id,title,unit')->where($rows['jid']."=id")->select();
		//var_dump($rest);
		$gwc_title = array();
		foreach ($rest as $val){
			$rows['jid'] = $val['title'];
			$rows['did'] = $val['unit'];
		}
		//var_dump($gwc_title);exit;
		
		//var_dump( $rows);
		
            $span = ord("A");
            foreach($rows as $keyName=>$value){//列写入
			
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }
		//exit;
        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        //$objPHPExcel->getActiveSheet()->setTitle('test');
   
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        //exit;
    }
	
	
	//库存订单多表导出
	public function goods_export(){
		ob_end_clean();
		set_include_path('.'.get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/PHPExecl/');
		import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
		//**********************
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		
		
		$obpe = new \PHPExcel();
	   /* @func 设置文档基本属性 */
		$obpe_pro = $obpe->getProperties();
		$obpe_pro->setCreator('midoks')//设置创建者
         ->setLastModifiedBy('2013/2/16 15:00')//设置时间
         ->setTitle('data')//设置标题
         ->setSubject('beizhu')//设置备注
         ->setDescription('miaoshu')//设置描述
         ->setKeywords('keyword')//设置关键字 | 标记
         ->setCategory('catagory');//设置类别
		 
		$str='';
		$str1='';
		$res=$item_cate->where("pid=0")->select();
		foreach($res as $ks=>$vs){
			if($ks>0){ 
				$obpe->createSheet();
			}
			$obpe->setactivesheetindex($ks);
			$obpe->getActiveSheet()->setTitle($vs['name']);		//名称不能包含特殊字符	
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where inv_status=1 and cate_id in(select id from jrkj_item_cate where id=".$vs['id']." or pid=".$vs['id']."))")->order("jid asc")->select();	
			//echo M()->_sql()."<br>";
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where inv_status=1)')->field("sum(nums) as nums,jid,did")->select();
				//echo M()->_sql()."<br><br>";
				$str1[$k]=array(
					'0'=>$this->order_type($res[$ks]['all'][$k]['total'][0]['jid']),
					'1'=>$this->order_name($res[$ks]['all'][$k]['total'][0]['jid']),
					'2'=>$this->order_guige($res[$ks]['all'][$k]['total'][0]['did'],$res[$ks]['all'][$k]['total'][0]['jid']),
					'3'=>$res[$ks]['all'][$k]['total'][0]['nums'],
					'4'=>''
				);
				$cards[0]=array('小类', '商品名称', '单位','订单数量','备注');				
				$cards[$k+1]=$str1[$k];
				
				//var_dump($str1[$k]);echo "<br>";
				//写入多行数据
				foreach($cards as $k=>$v){
					$k = $k+1;
					/* @func 设置列 */
					$obpe->getactivesheet()->setcellvalue('A'.$k, $v[0]);
					$obpe->getactivesheet()->setcellvalue('B'.$k, $v[1]);
					$obpe->getactivesheet()->setcellvalue('C'.$k, $v[3]);
					$obpe->getactivesheet()->setcellvalue('D'.$k, $v[2]);
					$obpe->getactivesheet()->setcellvalue('E'.$k, $v[4]);
				}	
				unset($cards);//释放数组中的数据
				$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');	//写入类容					
			}						
		}//exit;
		//保存文件
		//$obwrite->save('云农飞菜.xls');
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Content-Type:application/force-download');
		header('Content-Type:application/vnd.ms-execl');
		header('Content-Type:application/octet-stream');
		header('Content-Type:application/download');
		header("Content-Disposition:attachment;filename='".date('Y-m-d')."库存订单.xls'");
		header('Content-Transfer-Encoding:binary');
		$obwrite->save('php://output');
	}	
	
	
	
	//非库存订单多表导出
	public function goods_export_fkc(){
		set_include_path('.'.get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/PHPExecl/');
		import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
		//**********************
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		
		
		$obpe = new \PHPExcel();
	   /* @func 设置文档基本属性 */
		$obpe_pro = $obpe->getProperties();
		$obpe_pro->setCreator('midoks')//设置创建者
         ->setLastModifiedBy('2013/2/16 15:00')//设置时间
         ->setTitle('data')//设置标题
         ->setSubject('beizhu')//设置备注
         ->setDescription('miaoshu')//设置描述
         ->setKeywords('keyword')//设置关键字 | 标记
         ->setCategory('catagory');//设置类别
		 
		$str='';
		$str1='';
		$res=$item_cate->where("pid=0")->select();
		foreach($res as $ks=>$vs){
			if($ks>0){ 
				$obpe->createSheet();
			}
			$obpe->setactivesheetindex($ks);
			$obpe->getActiveSheet()->setTitle($vs['name']);		//名称不能包含特殊字符
			
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where inv_status=2 and cate_id in(select id from jrkj_item_cate where id=".$vs['id']." or pid=".$vs['id']."))")->order("jid asc")->select();		
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where inv_status=2)')->field("sum(nums) as nums,jid,did")->select();				
							
				$str1[$k]=array(
					'0'=>$this->order_type($res[$ks]['all'][$k]['total'][0]['jid']),
					'1'=>$this->order_name($res[$ks]['all'][$k]['total'][0]['jid']),
					'2'=>$this->order_guige($res[$ks]['all'][$k]['total'][0]['did'],$res[$ks]['all'][$k]['total'][0]['jid']),
					'3'=>$res[$ks]['all'][$k]['total'][0]['nums'],
					'4'=>''
				);				
				$cards[0]=array('小类', '商品名称', '单位','订单数量','备注');				
				$cards[$k+1]=$str1[$k];
				
				//写入多行数据
				foreach($cards as $k=>$v){
					$k = $k+1;
					/* @func 设置列 */
					$obpe->getactivesheet()->setcellvalue('A'.$k, $v[0]);
					$obpe->getactivesheet()->setcellvalue('B'.$k, $v[1]);
					$obpe->getactivesheet()->setcellvalue('C'.$k, $v[2]);
					$obpe->getactivesheet()->setcellvalue('D'.$k, $v[3]);
					$obpe->getactivesheet()->setcellvalue('E'.$k, $v[4]);
				}	
			}
			unset($cards);//释放数组中的数据					
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');		//写入类容		
		}		
		//保存文件
		//$obwrite->save('云农飞菜.xls');
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Content-Type:application/force-download');
		header('Content-Type:application/vnd.ms-execl');
		header('Content-Type:application/octet-stream');
		header('Content-Type:application/download');
		header("Content-Disposition:attachment;filename='mulit_sheet.xls'");
		header('Content-Transfer-Encoding:binary');
		$obwrite->save('php://output');
	}	
	
	//导出对账订单表
	public function goods_export_person(){
		set_include_path('.'.get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/PHPExecl/');
		import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
		//**********************
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		
		
		$obpe = new \PHPExcel();
	   /* @func 设置文档基本属性 */
		$obpe_pro = $obpe->getProperties();
		$obpe_pro->setCreator('midoks')//设置创建者
         ->setLastModifiedBy('2013/2/16 15:00')//设置时间
         ->setTitle('data')//设置标题
         ->setSubject('beizhu')//设置备注
         ->setDescription('miaoshu')//设置描述
         ->setKeywords('keyword')//设置关键字 | 标记
         ->setCategory('catagory');//设置类别
		 
		$str='';
		$str1='';
		
		//勾选后导出的对账订单
		$ids = trim(I('id'), ',');		
		if ($ids) {
		
		}

		$res=$item_cate->where("pid=0")->select();
		foreach($res as $ks=>$vs){
			if($ks>0){
				$obpe->createSheet();
			}
			$obpe->setactivesheetindex($ks);
			$obpe->getActiveSheet()->setTitle($vs['name']);		//名称不能包含特殊字符

			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=1) and jid in(select id from jrkj_item where inv_status=1 and cate_id in(select id from jrkj_item_cate where pid=".$vs['id']."))")->order("jid asc")->select();
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where Is_ok=1) and jid in(select id from jrkj_item where inv_status=1)')->field("sum(nums) as nums,uid,oid,jid,did,prices")->select();

				$str1[$k]=array(
					'0'=>$this->order_name($res[$ks]['all'][$k]['total'][0]['jid']),
					'1'=>$this->order_guige($res[$ks]['all'][$k]['total'][0]['did'],$res[$ks]['all'][$k]['total'][0]['jid']),
					'2'=>$res[$ks]['all'][$k]['total'][0]['nums'],
					'3'=>$res[$ks]['all'][$k]['total'][0]['prices'],
					'4'=>$res[$ks]['all'][$k]['total'][0]['nums']*$res[$ks]['all'][$k]['total'][0]['prices']
				);
				//var_dump($cards);
				$cards[0]=array('订单编号',$this->order_dingdan($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[1]=array('姓名',$this->order_xdperson($res[$ks]['all'][$k]['total'][0]['uid']));
				$cards[2]=array('电话',$this->order_xdtel($res[$ks]['all'][$k]['total'][0]['uid']));
				$cards[3]=array('地址',$this->order_xdaddr($res[$ks]['all'][$k]['total'][0]['uid']));
				$cards[4]=array('下单时间',$this->order_addtime($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[5]=array('合计',$this->order_total($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[6]=array('商品','单位','数量','价格','金额');
				$cards[$k+7]=$str1[$k];
				
				//写入多行数据
				foreach($cards as $k=>$v){
					$k = $k+1;
					/* @func 设置列 */
					$obpe->getactivesheet()->setcellvalue('A'.$k, $v[0]);
					$obpe->getactivesheet()->setcellvalue('B'.$k, $v[1]);
					$obpe->getactivesheet()->setcellvalue('C'.$k, $v[2]);
					$obpe->getactivesheet()->setcellvalue('D'.$k, $v[3]);
					$obpe->getactivesheet()->setcellvalue('E'.$k, $v[4]);
				}	
			}
			unset($cards);//释放数组中的数据					
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');		//写入类容				
		}//exit;
		//保存文件
		//$obwrite->save('云农飞菜.xls');
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Content-Type:application/force-download');
		header('Content-Type:application/vnd.ms-execl');
		header('Content-Type:application/octet-stream');
		header('Content-Type:application/download');
		header("Content-Disposition:attachment;filename='mulit_sheet.xls'");
		header('Content-Transfer-Encoding:binary');
		$obwrite->save('php://output');
	}
	
		//导出对账订单表
	public function goods_export_person1(){
		set_include_path('.'.get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/PHPExecl/');
		import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
		//**********************
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
	
		
		$obpe = new \PHPExcel();
	   /* @func 设置文档基本属性 */
		$obpe_pro = $obpe->getProperties();
		$obpe_pro->setCreator('midoks')//设置创建者
         ->setLastModifiedBy('2013/2/16 15:00')//设置时间
         ->setTitle('data')//设置标题
         ->setSubject('beizhu')//设置备注
         ->setDescription('miaoshu')//设置描述
         ->setKeywords('keyword')//设置关键字 | 标记
         ->setCategory('catagory');//设置类别
		 
		$str='';
		$str1='';
		
		//勾选后导出的对账订单
		$ids = I("post.id");
		//var_dump($ids[0]);exit;
		foreach($ids as $ks=>$vs){
			$res=$order->where("id=".$ids[$ks]."")->select();
			//echo M()->_sql();exit;
			if($ks>0){
				$obpe->createSheet();
			}
			$obpe->setactivesheetindex($ks);
			$obpe->getActiveSheet()->setTitle($this->order_xdperson($res[0]['uid']));		//名称不能包含特殊字符

			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid=".$ids[$ks]."")->order("jid asc")->select();
			//echo M()->_sql();
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where Is_ok=2) and oid='.$ids[$ks].'')->field("sum(nums) as nums,uid,oid,jid,did,prices")->select();
				//echo M()->_sql()
				$str1[$k]=array(
					'0'=>$this->order_name($res[$ks]['all'][$k]['total'][0]['jid']),
					'1'=>$this->order_guige($res[$ks]['all'][$k]['total'][0]['did'],$res[$ks]['all'][$k]['total'][0]['jid']),
					'2'=>$res[$ks]['all'][$k]['total'][0]['nums'],
					'3'=>" ".number_format($res[$ks]['all'][$k]['total'][0]['prices'],2),
					'4'=>"￥".number_format($res[$ks]['all'][$k]['total'][0]['nums']*$res[$ks]['all'][$k]['total'][0]['prices'],2)
				);
				$rsn=$order->where("id=".$ids[$ks]."")->select();
				//var_dump($cards);
				$cards[0]=array('云农飞菜:'.$this->order_xdaddra($rsn[0]['uid']));
				$cards[1]=array('订单编号:'.$this->order_dingdan($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[2]=array('姓名:'.$this->order_xdperson($rsn[0]['uid']));
				$cards[3]=array('电话:'.$this->order_xdtel($rsn[0]['uid']));
				$cards[4]=array('地址:'.$this->order_xdaddr($rsn[0]['uid']));
				$cards[5]=array('下单时间:'.$this->order_addtime($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[6]=array('备注:'.$this->order_memos($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[7]=array('合计:'.$this->order_total($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[8]=array('商品','单位','数量','价格','金额');
				$cards[$k+9]=$str1[$k];
				
				
					
		
				//写入多行数据
				//$dump($cards);exit;
				foreach($cards as $k=>$v){
					
				
					$k = $k+1;
					/* @func 设置列 */
					$obpe->getactivesheet()->setcellvalue('A'.$k, $v[0]);
					$obpe->getactivesheet()->setcellvalue('B'.$k, $v[2]);
					$obpe->getactivesheet()->setcellvalue('C'.$k, $v[1]);
					$obpe->getactivesheet()->setcellvalue('D'.$k, $v[3]);
					$obpe->getactivesheet()->setcellvalue('E'.$k, $v[4]);
				}	
			}
			unset($cards);//释放数组中的数据					
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');		//写入类容				
		}//exit;
		//保存文件
		//$obwrite->save('云农飞菜.xls');
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Content-Type:application/force-download');
		header('Content-Type:application/vnd.ms-execl');
		header('Content-Type:application/octet-stream');
		header('Content-Type:application/download');
		header("Content-Disposition:attachment;filename='mulit_sheet.xls'");
		header('Content-Transfer-Encoding:binary');
		$obwrite->save('php://output');
	}
	
			//导出对账订单表
	public function goods_export_person2(){
	    set_include_path('.'.get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/PHPExecl/');
		import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
		//**********************
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		
		
		$obpe = new \PHPExcel();
	   /* @func 设置文档基本属性 */
		$obpe_pro = $obpe->getProperties();
		$obpe_pro->setCreator('midoks')//设置创建者
         ->setLastModifiedBy('2013/2/16 15:00')//设置时间
         ->setTitle('data')//设置标题
         ->setSubject('beizhu')//设置备注
         ->setDescription('miaoshu')//设置描述
         ->setKeywords('keyword')//设置关键字 | 标记
         ->setCategory('catagory');//设置类别
		 
		$str='';
		$str1='';
		
		//勾选后导出的对账订单
		$ids = I("post.id");
		//var_dump($ids[0]);exit;
		foreach($ids as $ks=>$vs){
			$res=$order->where("id=".$ids[$ks]."")->select();
			//echo M()->_sql();exit;
			if($ks>0){
				$obpe->createSheet();
			}
			$obpe->setactivesheetindex($ks);
			$obpe->getActiveSheet()->setTitle($this->order_xdperson($res[0]['uid']));		//名称不能包含特殊字符

			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid=".$ids[$ks]."")->order("jid asc")->select();
			//echo M()->_sql();
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where Is_ok=2) and oid='.$ids[$ks].'')->field("sum(nums) as nums,uid,oid,jid,did,prices")->select();
				//echo M()->_sql()
				$str1[$k]=array(
					'0'=>$this->order_name($res[$ks]['all'][$k]['total'][0]['jid']),
					'1'=>$this->order_guige($res[$ks]['all'][$k]['total'][0]['did'],$res[$ks]['all'][$k]['total'][0]['jid']),
					'2'=>$res[$ks]['all'][$k]['total'][0]['nums'],
					'3'=>$res[$ks]['all'][$k]['total'][0]['prices'],
					'4'=>$res[$ks]['all'][$k]['total'][0]['nums']*$res[$ks]['all'][$k]['total'][0]['prices']
				);
				$rsn=$order->where("id=".$ids[$ks]."")->select();
				//var_dump($cards);
				$cards[0]=array('云农飞菜:'.$this->order_xdaddra($rsn[0]['uid']));
				$cards[1]=array('订单编号:'.$this->order_dingdan($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[2]=array('姓名:'.$this->order_xdperson($rsn[0]['uid']));
				$cards[3]=array('电话:'.$this->order_xdtel($rsn[0]['uid']));
				$cards[4]=array('地址:'.$this->order_xdaddr($rsn[0]['uid']));
				$cards[5]=array('下单时间:'.$this->order_addtime($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[6]=array('备注:'.$this->order_memos($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[7]=array('商品','单位','数量');
				$cards[$k+8]=$str1[$k];
				//dump($cards);exit;
				
				//写入多行数据
				foreach($cards as $k=>$v){
					$k = $k+1;
					/* @func 设置列 */
					$obpe->getactivesheet()->setcellvalue('A'.$k, $v[0]);
					$obpe->getactivesheet()->setcellvalue('B'.$k, $v[2]);
					$obpe->getactivesheet()->setcellvalue('C'.$k, $v[1]);
				}	
			}
			unset($cards);//释放数组中的数据					
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');		//写入类容				
		}//exit;
		//保存文件
		//$obwrite->save('云农飞菜.xls');
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Content-Type:application/force-download');
		header('Content-Type:application/vnd.ms-execl');
		header('Content-Type:application/octet-stream');
		header('Content-Type:application/download');
		header("Content-Disposition:attachment;filename='mulit_sheet.xls'");
		header('Content-Transfer-Encoding:binary');
		$obwrite->save('php://output');
	}
	
	public function goods_export_fj1(){
		ob_end_clean();
		set_include_path('.'.get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/PHPExecl/');
		import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
		//**********************
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		
		
		$obpe = new \PHPExcel();
	   /* @func 设置文档基本属性 */
		$obpe_pro = $obpe->getProperties();
		$obpe_pro->setCreator('midoks')//设置创建者
         ->setLastModifiedBy('2013/2/16 15:00')//设置时间
         ->setTitle('data')//设置标题
         ->setSubject('beizhu')//设置备注
         ->setDescription('miaoshu')//设置描述
         ->setKeywords('keyword')//设置关键字 | 标记
         ->setCategory('catagory');//设置类别
	 
		$obpe->setactivesheetindex(0);
		$obpe->getActiveSheet()->setTitle('分拣一');		//名称不能包含特殊字符

		//$res=$order_list->where("oid in(select id from jrkj_order where Is_ok=2)")->order("jid desc,did desc,id desc")->select();
		$res=$order_list->DISTINCT('ture')->field('jid,did')->where("oid in(select id from jrkj_order where Is_ok=2)")->order("jid desc,did desc,id desc")->select();
		//echo M()->_sql();
		foreach($res as $key=>$val){
			$rsn=$order_list->field('sum(nums) as nums')->where("jid=".$val['jid']." and did=".$val['did']." and oid in(select id from jrkj_order where Is_ok=2)")->order("jid desc,did desc,id desc")->select();
			//echo M()->_sql()."<br>";
			//echo $val['jid']."<br>";
			$str[$key]=array(
				'0'=>$this->order_name($val['jid']),
				'1'=>$this->order_guige($val['did'],$val['jid']),
				'2'=>$rsn[0]['nums'],
				'3'=>''
			);				
			$cards[0]=array('商品名称', '单位','订单数量','备注');				
			$cards[$key+1]=$str[$key];
			
			//写入多行数据
			foreach($cards as $k=>$v){
				$k = $k+1;
				/* @func 设置列 */
				$obpe->getactivesheet()->setcellvalue('A'.$k, $v[0]);
				$obpe->getactivesheet()->setcellvalue('B'.$k, $v[1]);
				$obpe->getactivesheet()->setcellvalue('C'.$k, $v[2]);
				$obpe->getactivesheet()->setcellvalue('D'.$k, $v[3]);
			}	
	
			unset($cards);//释放数组中的数据					
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');		//写入类容	
		}//exit;
		//保存文件
		//$obwrite->save('云农飞菜.xls');
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Content-Type:application/force-download');
		header('Content-Type:application/vnd.ms-execl');
		header('Content-Type:application/octet-stream');
		header('Content-Type:application/download');
		header("Content-Disposition:attachment;filename='mulit_sheet.xls'");
		header('Content-Transfer-Encoding:binary');
		$obwrite->save('php://output');
	}
	
	//商品小类调用
	function order_type($id){
		$item_cate=M("item_cate");
		$item=M("item");
		$res=$item_cate->where('id in(select cate_id from jrkj_item where id='.$id.')')->select();
		return $res[0]['name'];
	}
	
	//商品名称调用
	function order_name($id){
		$item=M("item");
		$res=$item->where('id='.$id.'')->select();
		return $res[0]['title'];
	}
	
	//订单编号调用
	function order_dingdan($id){
		$order=M("order");
		$res=$order->where('id='.$id.'')->select();
		return $res[0]['dingdan'];
	}
	//下单人
	function order_xdperson($id){
		$member=M("member");
		$goodsaddress=M("member_goodsaddress");
		$rs=$goodsaddress->where('uid='.$id.' and status=1')->select();
		if($rs){
			$ss=$rs[0]['shperson'];
		}else{
			$res=$member->where('id='.$id.'')->select();
			$ss=$res[0]['realname'];
		}
		return $ss;
	}
	//下单地址
	function order_xdaddr($id){
		$member=M("member");
		$goodsaddress=M("member_goodsaddress");
		$rs=$goodsaddress->where('uid='.$id.' and status=1')->select();
		if($rs){
			$ss=$rs[0]['address'];
		}else{
			$res=$member->where('id='.$id.'')->select();
			$ss=$res[0]['address'];
		}
		return $ss;
	}
	
	
		function order_xdaddra($id){
		$member=M("member");
		$goodsaddress=M("member_goodsaddress");
		$rs=$goodsaddress->where('uid='.$id.' and status=1')->select();
		if($rs){
			$ss=$rs[0]['region'];
		}else{
			$res=$member->where('id='.$id.'')->select();
			$ss=$res[0]['region'];
		}
		return $ss;
	}
	
	
	
	//下单电话
	function order_xdtel($id){
	
		$member=M("member");
		$goodsaddress=M("member_goodsaddress");
		$rs=$goodsaddress->where('uid='.$id.' and status=1')->select();
		if($rs){
			$ss=$rs[0]['mobile'];
		}else{
			$res=$member->where('id='.$id.'')->select();
			$ss=$res[0]['mobile'];
		}
		return $ss;
	}
	//下单时间
	function order_addtime($id){
		$order=M("order");
		$res=$order->where('id='.$id.'')->select();
		$time=date('Y-m-d H:i:s',$res[0]['addtime']);
		return $time;
	}
	
	//订单合计
	function order_total($id){
		$order=M("order");
		$res=$order->where('id='.$id.'')->select();
		return $res[0]['totalprices'];
	}
	
	//备注
	 function order_memos($id){
		 $order=M("order");
		 $res=$order->where('id='.$id)->select();
		 $memos=$res[0]['memos'];
		 return $memos;
		 }
	
	//商品规格调用
	function order_guige($id,$ids){
		$item=M("item");
		$item_attr=M("item_attr");
		if($id==0){
			$res=$item->where('id='.$ids.'')->select();
			$v=$res[0]['unit'];
		}else{
			$res=$item_attr->where('id in(select did from jrkj_order_list where did='.$id.')')->select();
			$v=$res[0]['attr_value'];
		}
		return $v;
	}
	
	
	
	
	
	
	//配送路线导出
	
	
   
   
    public function dao_pei()
    {
		
		
		ob_end_clean();
		 
		
			
	   $goods_list=M('order')->where("1=1 and Is_ok=2 ")->order('id desc')->select();	
	  // echo M()->_sql();exit;
	 
		//$goods_list = M('user')->select();
		
		//***********************************
		
		for($i=0;$i<count($goods_list);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'] ,'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$goods_list[$i]['aid'] ,'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$goods_list[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$goods_list[$i]['deli_id']))->getField('over_time');
		    $goods_list[$i]['sub']=M('order_list')->field('id,jid,did,nums')->where('oid='.$goods_list[$i]['id'])->select();
	
	
			$goods_list[$i]['name'] =$name;
			$goods_list[$i]['tel'] =$tel;
			$goods_list[$i]['ads'] =$ads;
			$goods_list[$i]['star']=$star;
			$goods_list[$i]['end']=$end;
		      
			
		
		}
		
			
			//dump($goods_list[0]['sub']);exit;
			//foreach($goods_list[])
			
		//**************************************
     
		$data = array();
        foreach ($goods_list as $k=>$goods_info){
		
		$sten=$dates.' '.$goods_info['star'].'  '.$goods_info['end'];
		$data[$k][siji]=$goods_info['siji']; //司机
		   $data[$k][name]=$goods_info['name'];  //姓名
			$data[$k][tel]=' '.$goods_info['tel'];   //电话
			$data[$k][ads]=$goods_info['ads']; //配送地址
	
			$data[$k][delivery_time]=$sten;//收货时间
		
        }
		

		//exit;
        //print_r($goods_list);
        //print_r($data);exit;

        foreach ($data as $field=>$v){
             
			 	
			if($field == 'siji'){
                $headArr[]='司机';
            }
			if($field == 'name'){
                $headArr[]='姓名';
            }
			if($field == 'tel'){
                $headArr[]='电话';
            }
			if($field == 'ads'){
                $headArr[]='配送地址';
            }
		
			if($field == 'delivery_time'){
                $headArr[]='收货时间';
            }
		}


        $filename="goods_list";

        $this->getExcelpei($filename,$headArr,$data);
			
   
				}
				

   public  function getExcelpei($fileName,$headArr,$data){
	   
	   
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
?>