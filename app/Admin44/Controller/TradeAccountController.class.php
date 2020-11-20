<?php
namespace Admin\Controller;
class TradeAccountController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('TradeAccount');
        $this->set_mod('TradeAccount');
    }

    public function _before_index(){
        $this->list_relation = true;
    }

    public function _before_add(){
        $member = D('Member')->field('id,nickname,mobile')->select();
        $this->assign('member',$member);
    }

    public function _before_edit(){
        $member = D('Member')->field('id,nickname,mobile')->select();
        $this->assign('member',$member);
    }

}