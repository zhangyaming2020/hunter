<?php
namespace Admin\Controller;
class SettlementController extends AdminCoreController {
    public function index(){
		$this->display(); 
    }
    public function cash_list(){
		$response = $this->fetch();
        $this->ajax_return(1,'',$response,'edit');
    }
    public function outstanding_order(){
		$response = $this->fetch();
        $this->ajax_return(1,'',$response,'edit');
    } 
} 