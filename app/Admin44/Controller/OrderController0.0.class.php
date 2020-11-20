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
		$order=M("order");
		if(IS_POST){
			$keyword=I("post.keyword");
			$status=I("post.status");
			$time_start=I("post.time_start");
			$time_end=I("post.time_end");
			$price_min=I("post.price_min");
			$price_max=I("post.price_max");
			$this->assign("keyword",$keyword);
			$this->assign("status",$status);
			$this->assign("time_start",$time_start);
			$this->assign("time_end",$time_end);
			$this->assign("price_min",$price_min);
			$this->assign("price_max",$price_max);
			$where="1=1";
			if($keyword){
				$where=$where." and dingdan like '%".$keyword."%'";
			}
//			if($status){
				$where=$where." and status=".$status."";
//			}
			if($time_start and $time_end){
				$where=$where." and addtime between '".strtotime($time_start)."' and '".strtotime($time_end)."'";
			}
			if($price_max){
				$where=$where." and totalprices between '".$price_min."' and '".$price_max."'";
			}
//		print_r($where);
			$list=$order->where($where)->order('id desc')-> select();
		}else{
			$list=$order->where("1=1")->order('id desc')->select();		
		}
		for($i=0;$i<count($list);$i++){
			$name= M('member') ->where(array('id'=>$list[$i]['uid']))->getField('nickname');
			$list[$i]['name'] =$name;
		}
		$this->assign("list",$list);
		$this->display();
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
		$this->assign('addr',$addr);
		$this->display();
	}
	
	//库存订单
	public function kclist(){
		$item=M("item");
		$item_cate=M("item_cate");
		$item_attr=M("item_attr");
		$order=M("order");
		$order_list=M("order_list");
		
		$res=$item_cate->where("pid=0")->select();
		foreach($res as $ks=>$vs){
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where status=2) and jid in(select id from jrkj_item where inv_status=1 and cate_id in(select id from jrkj_item_cate where pid=".$vs['id']."))")->order("jid asc")->select();
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where status=2) and jid in(select id from jrkj_item where inv_status=1)')->field("sum(nums) as nums")->select();
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
			$res[$ks]['all']=$order_list->DISTINCT(TRUE)->field("jid,did")->where("oid in(select id from jrkj_order where status=2) and jid in(select id from jrkj_item where inv_status=2 and cate_id in(select id from jrkj_item_cate where pid=".$vs['id']."))")->order("jid asc")->select();
			foreach($res[$ks]['all'] as $k=>$v){
				$res[$ks]['all'][$k]['total']=$order_list->where('jid='.$v['jid'].' and did='.$v['did'].' and oid in(select id from jrkj_order where status=2) and jid in(select id from jrkj_item where inv_status=2)')->field("sum(nums) as nums")->select();
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
			$where=$where." and status=2 and addtime between ".strtotime(date("Y-m-d"))." and ".strtotime(date("Y-m-d",strtotime("+1 day")))."";
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
		}else{
			$where=" and status=2 and addtime between ".strtotime(date("Y-m-d"))." and ".strtotime(date("Y-m-d",strtotime("+1 day")))."";
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
	
	public function fenjian1(){
		$this->display();
	}
	
	public function fenjian2(){
		$this->display();
	}
}
?>