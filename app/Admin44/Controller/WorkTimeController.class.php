<?php
namespace Admin\Controller;
use Admin\Org\Image;
use Admin\Org\Tree;
class WorkTimeController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Sundry');
        $this->_cate_mod = D('ArticleCate');
        $this->set_mod('Sundry');
    }

    public function _before_index() {
        $res = $this->_cate_mod->field('id,name')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
        }
        $this->assign('cate_list', $cate_list);

        $p = I('p',1,'intval');
        $this->assign('p',$p);
		$rest = $this -> _mod -> where('cate_id=1') -> limit(7) -> order('id') ->select();
		
		$this->assign('rest',$rest);
        //默认排序
        //$this->sort = 'ordid';
        //$this->order = 'ASC';
    }
		
	public function delivery(){
		$rest = $this -> _mod -> where('cate_id=2') -> limit(7) -> order('id') ->select();
		$this->assign('rest',$rest);
//		print_r($rest);
		$this -> display();
	}	
   


	
	public function add(){
		($start_time1 = I('start_time1','', 'trim')) && $map1['start_time'] = strtotime($start_time1);
		($over_time1 = I('over_time1','', 'trim')) && $map1['over_time'] = strtotime($over_time1);
		if($map1){
			$map1['cate_id']= 1 ;
		$this -> _mod ->where(array('id'=>1)) -> data($map1) -> save();
		}
		($start_time2 = I('start_time2','', 'trim')) && $map2['start_time'] = strtotime($start_time2);
		($over_time2 = I('over_time2','', 'trim')) && $map2['over_time'] = strtotime($over_time2);
		if($map2){
			$map2['cate_id']= 1 ;
		$this -> _mod ->where(array('id'=>2)) -> data($map2) -> save();
		}
		($start_time3 = I('start_time3','', 'trim')) && $map3['start_time'] = strtotime($start_time3);
		($over_time3 = I('over_time3','', 'trim')) && $map3['over_time'] = strtotime($over_time3);
		if($map3){
			$map3['cate_id']= 1 ;
		$this -> _mod ->where(array('id'=>3)) -> data($map3) -> save();
		}
		($start_time4 = I('start_time4','', 'trim')) && $map4['start_time'] = strtotime($start_time4);
		($over_time4 = I('over_time4','', 'trim')) && $map4['over_time'] = strtotime($over_time4);
		if($map4){
			$map4['cate_id']= 1 ;
		$this -> _mod ->where(array('id'=>4)) -> data($map4) -> save();
		}
		($start_time5 = I('start_time5','', 'trim')) && $map5['start_time'] = strtotime($start_time5);
		($over_time5 = I('over_time5','', 'trim')) && $map5['over_time'] = strtotime($over_time5);
		if($map5){
			$map5['cate_id']= 1 ;
		$this -> _mod ->where(array('id'=>5)) -> data($map5) -> save();
		}
		($start_time6 = I('start_time6','', 'trim')) && $map6['start_time'] = strtotime($start_time6);
		($over_time6 = I('over_time6','', 'trim')) && $map6['over_time'] = strtotime($over_time6);
		if($map6){
			$map6['cate_id']= 1 ;
		$this -> _mod ->where(array('id'=>6)) -> data($map6) -> save();
		}
		($start_time7 = I('start_time7','', 'trim')) && $map7['start_time'] = strtotime($start_time7);
		($over_time7 = I('over_time7','', 'trim')) && $map7['over_time'] = strtotime($over_time7);
		if($map7){
			$map7['cate_id']= 1 ;
		$this -> _mod ->where(array('id'=>7)) -> data($map7) -> save();
		}
		$this -> redirect(U('workTime/index'));
	}

	public function add1(){
			
		($start_time1 = I('start_time1','', 'trim')) ;
		$map1['start_time'] = $start_time1;
		($over_time1 = I('over_time1','', 'trim')) ;
		 $map1['over_time'] = $over_time1;
			$map1['cate_id']= 2 ;
//			var_dump($map1); 
		$this -> _mod ->where(array('id'=>11)) -> data($map1) -> save();
//		echo $this -> _mod->_sql(); 
//			exit;
		($start_time2 = I('start_time2','', 'trim')) ;
		 $map2['start_time'] = $start_time2;
		($over_time2 = I('over_time2','', 'trim')) ;
		 $map2['over_time'] = $over_time2;
//	print_r($map2); exit;
			$map2['cate_id']= 2 ;
		$this -> _mod ->where(array('id'=>12)) -> data($map2) -> save();
		($start_time3 = I('start_time3','', 'trim')) ;
		 $map3['start_time'] = $start_time3;
		($over_time3 = I('over_time3','', 'trim')) ;
		 $map3['over_time'] = $over_time3;
			$map3['cate_id']= 2 ;
		$this -> _mod ->where(array('id'=>13)) -> data($map3) -> save();
		($start_time4 = I('start_time4','', 'trim')) ;
		 $map4['start_time'] = $start_time4;
		($over_time4 = I('over_time4','', 'trim')) ;
		 $map4['over_time'] = $over_time4;
			$map4['cate_id']= 2 ;
		$this -> _mod ->where(array('id'=>14)) -> data($map4) -> save();
		($start_time5 = I('start_time5','', 'trim')) ;
		 $map5['start_time'] = $start_time5;
		($over_time5 = I('over_time5','', 'trim')) ;
		 $map5['over_time'] = $over_time5;
			$map5['cate_id']= 2 ;
		$this -> _mod ->where(array('id'=>15)) -> data($map5) -> save();
		($start_time6 = I('start_time6','', 'trim')) ;
		 $map6['start_time'] = $start_time6;
		($over_time6 = I('over_time6','', 'trim')) ;
		 $map6['over_time'] = $over_time6;
			$map6['cate_id']= 2 ;
		$this -> _mod ->where(array('id'=>16)) -> data($map6) -> save();
		($start_time7 = I('start_time7','', 'trim')) ;
		 $map7['start_time'] = $start_time7;
		($over_time7 = I('over_time7','', 'trim')) ;
		 $map7['over_time'] = $over_time7;
			$map7['cate_id']= 2 ;
		$this -> _mod ->where(array('id'=>17)) -> data($map7) -> save();
		$this -> redirect(U('workTime/delivery'));
	}
	

}