<?php
namespace Admin\Controller;
class RechargeController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('MemberRecharge');
        $this->set_mod('MemberRecharge');
    }

    public function _before_index(){
			
    }
	
	public function _search(){
		($order_sn = I('order_sn','','trim'))&&$map['order_sn']=array('like','%'.$order_sn.'%');
		($id = I('id','','trim'))&&$map['member_id']=$id;
		($amount = I('amount','','trim'))&&$map['amount']=$amount;
		$status = I('status','','trim');
		if($status!=''&&$status!=-1) $map['status']=$status;
		($time_start = I('time_start','','trim'))&&$map['add_time'][]=array('egt',strtotime($time_start));
		($time_end = I('time_end','','trim'))&&$map['add_time'][]=array('elt',strtotime($time_end.' +1 days'));
		
		$this->assign('ser',array(
			'order_sn' => $order_sn,
			'id' => $id,
			'amount' => $amount,
			'status' => $status,
			'time_start' => $time_start,
			'time_end'	=> $time_end,
		));
		return $map;
    }

    public function _before_add(){

    }
}