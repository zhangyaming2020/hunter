<?php
namespace App\Controller;

use Think\HxCommon;

class OrderRechargeController extends ApiController {
    
	public function _initialize(){
        parent::_initialize(); 
	    $this->Order = D('OrderRecharge');
	
	}
	
	//充值记录
	public function recharge($datas)
	{
	    $this->check_user_id($datas);
		$data = $this->get_datas($datas);
		$uid = $datas['user_id'];
		$pagesize = $data['pagesize'];
		$currentpage = $data['page'];
		$get_recharges = $this->Order->rechargeModel($uid,$pagesize,$currentpage);
		$this->json_Response('success',$datas['pack_no'],$get_recharges);
	}

}