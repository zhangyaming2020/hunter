<?php
namespace Admin\Controller;
class EnvelopeController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('Envelope');
        $this->set_mod('Envelope');
    }

}