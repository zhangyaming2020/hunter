<?php
namespace Admin\Controller;
class WithdrawController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('Withdraw');
        $this->set_mod('Withdraw');
    }

    public function _before_index()
    {
        //$this->list_relation = true;
		$this->assign('draw_status',C('draw_status'));
    }

	public function pass($id){
		$db = D('Withdraw')->where(array('id'=>$id))->setField('status',1);
		$this->ajax_return(1, $db ? '审核成功，请尽快打钱对方' : '审核失败' , $response);
	}

}