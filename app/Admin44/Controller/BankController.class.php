<?php
namespace Admin\Controller;
class BankController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('Bank');
        $this->set_mod('Bank');
    }

}