<?php
namespace Admin\Controller;
class DataDictionaryController extends AdminCoreController {
	public function _initialize() {
		
    }
	 public function index() {
//      Vendor('kdniaoapi');
//      $kdcx = new \kdcx('111111111','2222222222222');
//      echo $kdcx->test(); 
		$res =M()->query("select table_name from information_schema.tables where table_schema='bao'");
		foreach($res as $k => $v){
			$res[$k]['table_name']=mb_substr($v['table_name'],4,strlen($v['table_name']));
			$res[$k]['sub'] =M()->query("select COLUMN_NAME,COLUMN_TYPE,COLUMN_COMMENT,IS_NULLABLE from information_schema.COLUMNS where table_name = '".$v['table_name']."'");
			}
			//dump($res);exit;
		$this->assign('res',$res);
        $this->display();
    }
   
}