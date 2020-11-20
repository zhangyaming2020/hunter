<?php
namespace Admin\Controller;
class OrderController extends AdminCoreController {
      public function _initialize() {
          parent::_initialize();
		$this->_mod = D('Order');
          $this->set_mod('Order');
         
      }
	
//	public function _before_index() {
//        $this->list_relation = true;
//    }


public function index(){
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
			$this->assign("price_min",$price_min);
			if(IS_POST){
			
			//echo $keyword;exit;
		
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
			$name= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid'] ,'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid'] ,'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('over_time');
		    $list[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$list[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$list[$i]['name'] =$name;
			$list[$i]['tel'] =$tel;
			$list[$i]['ads'] =$ads;
			$list[$i]['star']=$star;
			$list[$i]['end']=$end;
			$res = M('item')->field('id,title,unit')->select();
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
			$count = $order->count();
			$Page = new \Think\Page($count,20);
			$list=$order->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select(); 
			$show = $Page->show();
			$this->assign('page',$show);
			 //  $list=$order->order('id desc')->select();
			  // echo M()->_sql();
			for($i=0;$i<count($list);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid'] ,'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid'] ,'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$list[$i]['aid'] ,'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$list[$i]['deli_id']))->getField('over_time');
			$list[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$list[$i]['id'])->select();
			//echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
			$list[$i]['name'] =$name;
			$list[$i]['tel'] =$tel;
			$list[$i]['ads'] =$ads;
			$list[$i]['star']=$star;
			$list[$i]['end']=$end;
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
			
			 }
		
		   $this->assign('list',$list);
		
		   $this->display();
	}
	
	
	





	public function indexyi(){
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
			$this->assign("price_min",$price_min);
			if(IS_POST){
			
			//echo $keyword;exit;
		
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
				}
				
				
				
			}
			
		
			/*if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}*/
			$where=$where."and status=".$status."";
			//echo $where;exit;
	     
			$lista=$order->where("Is_ok=1".$where)->order('id desc')->select();
			 //echo M()->_sql();exit;
			for($i=0;$i<count($lista);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid'] ,'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid'] ,'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$lista[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$lista[$i]['deli_id']))->getField('over_time');
		    $lista[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$lista[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
			
			   $lista=$order->where("Is_ok=1 and status=1 and delivery_time=".strtotime(date('Y-m-d')))->order('id desc')->select();
			  // echo M()->_sql();
			for($i=0;$i<count($lista);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid'] ,'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid'] ,'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$lista[$i]['aid'] ,'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$lista[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$lista[$i]['deli_id']))->getField('over_time');
			$lista[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$lista[$i]['id'])->select();
			//echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
			
			 }
		   $this->assign('lista',$lista);
		
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
			$this->assign("price_min",$price_min);
		
				
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
			$name= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid'] ,'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid'],'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid'],'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$listb[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listb[$i]['deli_id']))->getField('over_time');
		    $listb[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$listb[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
			
			   $listb=$order->where("Is_ok=1 and status=0 and delivery_time=".strtotime(date('Y-m-d')))->order('id desc')->select();
			 //  var_dump($listb);exit;
			   for($i=0;$i<count($listb);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid'],'status'=>1))->getField('shperson');
			//echo $name;exit;
			$tel= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid'],'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listb[$i]['aid'],'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$listb[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listb[$i]['deli_id']))->getField('over_time');
		    $listb[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$listb[$i]['id'])->select();
			
		   // echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
		    }
		   $this->assign('listb',$listb);
		
		   $this->display();
	}
		
	
	
		
		
		
		public function indexque(){
		
		$order=M("order");
		if(IS_POST){
			$keyword=I("post.keyword");
			$Is_ok=I("post.Is_ok");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			//$price_min=I("post.price_min");
			//$price_max=I("post.price_max");
			
			$username=I("post.username");
			$idhao=I("post.idhao");
			$tel=I("post.tel");
			$this->assign("keyword",$keyword);
			$this->assign("Is_ok",$Is_ok);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("price_min",$price_min);
			
		
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
				}
				
				
				
			}
			/*if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}*/
			$where=$where."and Is_ok=".$Is_ok."";
			//echo $where;exit;
	     
			$listc=$order->where($where)->order('id desc')->select();
			  //echo M()->_sql();exit;
			for($i=0;$i<count($listc);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid'],'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid'],'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$listc[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listc[$i]['deli_id']))->getField('over_time');
		    $listc[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$listc[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
			
			   $listc=$order->where("1=1 and Is_ok=2 and delivery_time=".strtotime(date('Y-m-d')))->order('id desc')->select();
			   for($i=0;$i<count($listc);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid'],'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listc[$i]['aid'],'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$listc[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listc[$i]['deli_id']))->getField('over_time');
		    $listc[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$listc[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
		    }
		   $this->assign('listc',$listc);
		
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
			$this->assign("price_min",$price_min);
			
			
			
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
			$name= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid'],'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid'],'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$listd[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listd[$i]['deli_id']))->getField('over_time');
		    $listd[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$listd[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
			
			   $listd=$order->where("1=1 and status=2 and delivery_time=".strtotime(date('Y-m-d')))->order('id desc')->select();
			   for($i=0;$i<count($listd);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid'],'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$listd[$i]['aid'],'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$listd[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$listd[$i]['deli_id']))->getField('over_time');
		    $listd[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$listd[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
		    }
		   $this->assign('listd',$listd);
		
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
			$this->assign("price_min",$price_min);
			
		
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
				}
				
				
				
			}
			/*if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}*/
			$where=$where."and status=".$status."";
			//echo $where;exit;
	     
			$liste=$order->where($where)->order('id desc')->select();
			  //echo M()->_sql();exit;
			for($i=0;$i<count($listb);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid'],'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid'],'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$liste[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$liste[$i]['deli_id']))->getField('over_time');
		    $liste[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$liste[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
			
			   $liste=$order->where("1=1 and status=3 and delivery_time=".strtotime(date('Y-m-d')))->order('id desc')->select();
			   for($i=0;$i<count($liste);$i++){
			$name= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid'],'status'=>1))->getField('shperson');
			$tel= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid'],'status'=>1))->getField('mobile');
			$ads= M('member_goodsaddress') ->where(array('id'=>$liste[$i]['aid'],'status'=>1))->getField('address');
			$star=M('sundry')->where(array('id'=>$liste[$i]['deli_id']))->getField('start_time');
			$end=M('sundry')->where(array('id'=>$liste[$i]['deli_id']))->getField('over_time');
		    $liste[$i]['sub']=M('order_list')->field('id,jid,did')->where('oid='.$liste[$i]['id'])->select();
			
		    //echo M()->_sql();
	        $this->assign('title',$title);
		    //var_dump($title);
			// $lista[$i]['shang']=$title;
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
		    }
		   $this->assign('liste',$liste);
		
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
	  // echo $ids;exit;
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
			  // var_dump($res); exit;
			  //echo M()->_sql();exit;
			  foreach($res as $key=>$vals){
			  	  //比较单个订单中的商品与所有订单中的商品是否有重复的商品
				  if($val['jid']==$vals['jid'] && $val['did']==$vals['did']){
				  	  //echo $vals['nums']."<br>";
				  	  $nums+=$vals['nums'];
				  	  if($id==''){
						$id=$vals['id'];
					  }else{
						$id=$id.','.$vals['id'];
					  }					  		  
				  }
				  $data['nums']=$nums;
			 	  M('order_list')->where('id='.$val['id'])->save($data);
				  //echo M()->_sql();exit;
				  if($id!=0){
				  	  M('order_list')->where('id in('.$id.') and oid not in('.$val['oid'].')')->delete();
				  }
				  if($val['jid']!=$vals['jid'] || $val['did']!=$vals['did']){
				  	  if($idd==''){
						$idd=$vals['id'];
					  }else{
						$idd=$idd.','.$vals['id'];
					  }					  		  
				  }
				  $datas['oid']=$val['oid'];
			 	  M('order_list')->where('id in('.$idd.')')->save($datas);				  
			  }			 
		  }
		  M('order')->where('id in('.$ids.') and id not in('.$val['oid'].')')->delete();
	  } 
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
		
        $res = $item->field('id,title')->select();
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
			
			 
		
	
		
		//调用订单
		$res1 = $order->field('id,memos,dingdan')->select();
        $array_dingdan = array();
        foreach ($res1 as $val1) {
            $array_dingdan[$val1['id']] = $val1['dingdan'];
					 $array_memos[$val1['id']] =	 $val1['memos'];
	    }
		
		$this->assign('array_dingdan', $array_dingdan);
		$this->assign('array_memos', $array_memos);
		$aid = M('order') ->where(array('id'=>$id))->getField('aid');
		$addr = M('memberGoodsaddress') ->where(array('id'=>$aid))->find();
		//var_dump($addr);
		$this->assign('addr',$addr);
	    
		$or=M('order')->where(array('id'=>$id))->find();
		//var_dump($or);
		$this->assign('or',$or);
	    
		
		$this->display();
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
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=2 and delivery_time='".strtotime(date('Y-m-d'))."') and jid in(select id from jrkj_item where inv_status=1 and cate_id in(select id from jrkj_item_cate where id=".$vs['id']." or pid=".$vs['id']."))")->order("jid asc")->select();
			//echo M()->_sql()."<br>";
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where("jid=".$v['jid']." and did=".$v['did']." and oid in(select id from jrkj_order where Is_ok=2 and delivery_time='".strtotime(date('Y-m-d'))."') and jid in(select id from jrkj_item where inv_status=1)")->field("sum(nums) as nums")->select();
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
	
	//非库存订单
	public function fkclist(){
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		
		$res=$item_cate->where("pid=0")->select();
		foreach($res as $ks=>$vs){
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=2 and delivery_time='".strtotime(date('Y-m-d'))."') and jid in(select id from jrkj_item where inv_status=2 and cate_id in(select id from jrkj_item_cate where id=".$vs['id']." or pid=".$vs['id']."))")->order("jid asc")->select();
			//echo M()->_sql()."<br>";
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where("jid=".$v['jid']." and did=".$v['did']." and oid in(select id from jrkj_order where Is_ok=2 and delivery_time='".strtotime(date('Y-m-d'))."') and jid in(select id from jrkj_item where inv_status=2)")->field("sum(nums) as nums")->select();
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
		if(IS_POST){
			$keyword=I("post.keyword");
			$this->assign("keyword",$keyword);
			$where="1=1";
			if($keyword){
				$where=$where." and uid in(select id from jrkj_member where realname like '%".$keyword."%')";
			}
			$where=$where." and Is_ok=2 and delivery_time between ".strtotime(date("Y-m-d"))." and ".strtotime(date("Y-m-d",strtotime("+1 day")))."";
			$list=$order->where($where)->order('id desc')-> select();
			//echo M()->_sql();
			//echo time();
			foreach($list as $k=>$v){
				 $list[$k]['total']=$order_list->where('oid='.$v["id"].'')->select();
				 //echo M()->_sql()."<br>";
			}	
			$this->assign("list",$list);
			
			//调用会员的名称，电话，地址
			$res = $member->field('id,realname,mobile,address')->select();
			$member_name = array();
			$member_mobile = array();
			$member_address = array();
			foreach ($res as $val){
				$member_name[$val['id']] = $val['realname'];
				$member_mobile[$val['id']] = $val['mobile'];
				$member_address[$val['id']] = $val['address'];
			}
			$this->assign('member_name', $member_name);
			$this->assign('member_mobile', $member_mobile);
			$this->assign('member_address', $member_address);
			
			
			//调用商品名称
			$res = $item->field('id,title,unit')->select();
			$item_title = array();
			$item_unit = array();
			foreach ($res as $val){
				$item_title[$val['id']] = $val['title'];
				$item_unit[$val['id']] = $val['unit'];
			}
			$this->assign('item_title', $item_title);
			$this->assign('item_unit', $item_unit);		
			
			
			//调用商品名称
			$res = $item_attr->field('id,attr_value')->select();
			$item_guige = array();
			foreach ($res as $val){
				$item_guige[$val['id']] = $val['attr_value'];
			}
			$this->assign('item_guige', $item_guige);	
					
		}
		$this->display();	
	}
	
	public function fenjian1(){
		$item=M("item");	
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		$member=M("member");
		if(IS_POST){
			$keyword=I("post.keyword");
			$this->assign("keyword",$keyword);
			$where="1=1";
			if($keyword){
				$where=$where." and uid in(select id from jrkj_member where realname like '%".$keyword."%')";
			}
			$where=$where." and Is_ok=2 and delivery_time between ".strtotime(date("Y-m-d"))." and ".strtotime(date("Y-m-d",strtotime("+1 day")))."";
			$list=$order->where($where)->order('id desc')-> select();	
			//echo M()->_sql()."<br>";
			foreach($list as $k=>$v){
				 $list[$k]['total']=$order_list->where('oid='.$v["id"].'')->select();	
				 //echo M()->_sql()."<br>";			 
			}	
			$this->assign("list",$list);
			
			//调用会员的名称，电话，地址
			$res = $member->field('id,realname,mobile,address')->select();
			$member_name = array();
			$member_mobile = array();
			$member_address = array();
			foreach ($res as $val){
				$member_name[$val['id']] = $val['realname'];
				$member_mobile[$val['id']] = $val['mobile'];
				$member_address[$val['id']] = $val['address'];
			}
			$this->assign('member_name', $member_name);
			$this->assign('member_mobile', $member_mobile);
			$this->assign('member_address', $member_address);
			
			
			//调用商品名称
			$res = $item->field('id,title')->select();
			$item_title = array();
			foreach ($res as $val){
				$item_title[$val['id']] = $val['title'];
			}
			$this->assign('item_title', $item_title);
			
			
			//调用商品名称
			$res = $item_attr->field('id,attr_value')->select();
			$item_guige = array();
			foreach ($res as $val){
				$item_guige[$val['id']] = $val['attr_value'];
			}
			$this->assign('item_guige', $item_guige);			
		}
		$this->display();
	}
	
	public function fenjian2(){
		$item=M("item");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		$member=M("member");
		if(IS_POST){
			$keyword=I("post.keyword");
			$this->assign("keyword",$keyword);
			$where="1=1";
			if($keyword){
				$where=$where." and uid in(select id from jrkj_member where realname like '%".$keyword."%')";
			}
			$where=$where." and Is_ok=2 and delivery_time between ".strtotime(date("Y-m-d"))." and ".strtotime(date("Y-m-d",strtotime("+1 day")))."";
			$list=$order->where($where)->order('id desc')-> select();
			//echo M()->_sql();
			//echo time();
			foreach($list as $k=>$v){
				 $list[$k]['total']=$order_list->where('oid='.$v["id"].'')->select();
				 //echo M()->_sql()."<br>";
			}	
			$this->assign("list",$list);
			
			//调用会员的名称，电话，地址
			$res = $member->field('id,realname,mobile,address')->select();
			$member_name = array();
			$member_mobile = array();
			$member_address = array();
			foreach ($res as $val){
				$member_name[$val['id']] = $val['realname'];
				$member_mobile[$val['id']] = $val['mobile'];
				$member_address[$val['id']] = $val['address'];
			}
			$this->assign('member_name', $member_name);
			$this->assign('member_mobile', $member_mobile);
			$this->assign('member_address', $member_address);
			
			
			//调用商品名称
			$res = $item->field('id,title,unit')->select();
			$item_title = array();
			$item_unit = array();
			foreach ($res as $val){
				$item_title[$val['id']] = $val['title'];
				$item_unit[$val['id']] = $val['unit'];
			}
			$this->assign('item_title', $item_title);
			$this->assign('item_unit', $item_unit);		
			
			
			//调用商品名称
			$res = $item_attr->field('id,attr_value')->select();
			$item_guige = array();
			foreach ($res as $val){
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


    private  function getExcel($fileName,$headArr,$data){
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
			
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=1) and jid in(select id from jrkj_item where inv_status=1 and cate_id in(select id from jrkj_item_cate where pid=".$vs['id']."))")->order("jid asc")->select();			
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where Is_ok=1) and jid in(select id from jrkj_item where inv_status=1)')->field("sum(nums) as nums,jid,did")->select();
							
				$str1[$k]=array(
					'0'=>$this->order_type($res[$ks]['all'][$k]['total'][0]['jid']),
					'1'=>$this->order_name($res[$ks]['all'][$k]['total'][0]['jid']),
					'2'=>$this->order_guige($res[$ks]['all'][$k]['total'][0]['did'],$res[$ks]['all'][$k]['total'][0]['jid']),
					'3'=>$res[$ks]['all'][$k]['total'][0]['nums'],
					'4'=>''
				);				
				$cards[0]=array('小类', '商品名称', '规格','订单数量','备注');				
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
			//写入类容		
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');				
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
			
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where Is_ok=1) and jid in(select id from jrkj_item where inv_status=2 and cate_id in(select id from jrkj_item_cate where pid=".$vs['id']."))")->order("jid asc")->select();			
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where Is_ok=1) and jid in(select id from jrkj_item where inv_status=2)')->field("sum(nums) as nums,jid,did")->select();
							
				$str1[$k]=array(
					'0'=>$this->order_type($res[$ks]['all'][$k]['total'][0]['jid']),
					'1'=>$this->order_name($res[$ks]['all'][$k]['total'][0]['jid']),
					'2'=>$this->order_guige($res[$ks]['all'][$k]['total'][0]['did'],$res[$ks]['all'][$k]['total'][0]['jid']),
					'3'=>$res[$ks]['all'][$k]['total'][0]['nums'],
					'4'=>''
				);				
				$cards[0]=array('小类', '商品名称', '规格','订单数量','备注');				
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
			//写入类容		
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');				
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
				$cards[6]=array('商品','规格','数量','价格','金额');
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
			//写入类容		
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');				
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
			//echo M()->_sql();exit;
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where inv_status=1)')->field("sum(nums) as nums,uid,oid,jid,did,prices")->select();
				//echo M()->_sql();exit;
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
				$cards[6]=array('商品','规格','数量','价格','金额');
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
			//写入类容		
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');				
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
			//echo M()->_sql();exit;
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where Is_ok=2) and jid in(select id from jrkj_item where inv_status=1)')->field("sum(nums) as nums,uid,oid,jid,did,prices")->select();
				//echo M()->_sql();exit;
				$str1[$k]=array(
					'0'=>$this->order_name($res[$ks]['all'][$k]['total'][0]['jid']),
					'1'=>$this->order_guige($res[$ks]['all'][$k]['total'][0]['did'],$res[$ks]['all'][$k]['total'][0]['jid']),
					'2'=>$res[$ks]['all'][$k]['total'][0]['nums']
				);
				//var_dump($cards);
				$cards[0]=array('订单编号',$this->order_dingdan($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[1]=array('姓名',$this->order_xdperson($res[$ks]['all'][$k]['total'][0]['uid']));
				$cards[2]=array('电话',$this->order_xdtel($res[$ks]['all'][$k]['total'][0]['uid']));
				$cards[3]=array('地址',$this->order_xdaddr($res[$ks]['all'][$k]['total'][0]['uid']));
				$cards[4]=array('下单时间',$this->order_addtime($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[5]=array('合计',$this->order_total($res[$ks]['all'][$k]['total'][0]['oid']));
				$cards[6]=array('商品','规格','数量');
				$cards[$k+7]=$str1[$k];
				
				//写入多行数据
				foreach($cards as $k=>$v){
					$k = $k+1;
					/* @func 设置列 */
					$obpe->getactivesheet()->setcellvalue('A'.$k, $v[0]);
					$obpe->getactivesheet()->setcellvalue('B'.$k, $v[1]);
					$obpe->getactivesheet()->setcellvalue('C'.$k, $v[2]);
				}	
			}
			//写入类容		
			$obwrite= \PHPExcel_IOFactory::createWriter($obpe, 'Excel5');				
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
		$res=$member->where('id='.$id.'')->select();
		return $res[0]['realname'];
	}
	//下单地址
	function order_xdaddr($id){
		$member_goodsaddress=M("member_goodsaddress");
		$res=$member_goodsaddress->where('uid='.$id.' and status=1')->select();
		return $res[0]['address'];
	}
	//下单电话
	function order_xdtel($id){
		$member_goodsaddress=M("member_goodsaddress");
		$res=$member_goodsaddress->where('uid='.$id.' and status=1')->select();
		return $res[0]['mobile'];
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
}
?>