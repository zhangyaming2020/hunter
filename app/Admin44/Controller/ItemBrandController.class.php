<?php
namespace Admin\Controller;
class ItemBrandController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('ItemBrand');
        $this->set_mod('ItemBrand');
    }

   
}