<?php
namespace Admin\Controller;
class DataDictionaryController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
        $this->_mod = D('Resume');
        $this->set_mod('Resume');
 	}
	 public function index() {
//      Vendor('kdniaoapi');
//      $kdcx = new \kdcx('111111111','2222222222222');
//      echo $kdcx->test();
		$res =M()->query("select table_name from information_schema.tables where table_schema='mutong'");
		foreach($res as $k => $v){
			$res[$k]['table_name']=mb_substr($v['table_name'],5,strlen($v['table_name']));
			$res[$k]['sub'] =M()->query("select COLUMN_NAME,COLUMN_TYPE,COLUMN_COMMENT,IS_NULLABLE from information_schema.COLUMNS where table_name = '".$v['table_name']."'");
			}
			//dump($res);exit;
		$this->assign('res',$res);
//      $str=M('resume')->where(array('id'=>71))->getField('info');
//  	//$str='92年，学士，动物科学专业，负责河北保定徐水市场，饲料销售，目前月薪6k，期望月薪同，意向河北。离职原因：市';
//  	$place=M('place')->getField('name',true);
//  	$p_str='';
//  	foreach($place as $k=>$v){
//  		if(strpos($v,'省')){
//  		$place[$k]=str_replace('省','',trim($v));
//  		}
//  		else if(strpos($v,'市')){
//  			$place[$k]=str_replace('市','',trim($v));
//  		}
//  		else{
//  			
//  		}
//		}
//		$str=strip_tags($str);
//		$p_str=implode('|',$place);
//  	$pattern = '/[^;](.*)(?)(有限公司|集团)/'; 
//		preg_match($pattern, $str, $matches);
//  	dump($matches);	exit;
        $this->display();
        
    }
   
}