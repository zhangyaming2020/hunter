<?php
namespace Admin\Controller;
class LinkmanController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
        $this->_mod = D('Linkman');
        $this->set_mod('Linkman');
  }

    public function _before_index() {
    	
    }

    public function _before_add() {
    	
        $dic= M('dic')->where(array('id'=>array('in',array('7','10'))))->field('id,field,pid,name,is_must')->select();
       	foreach($dic as $k=>$v){
       		$dic[$k]['sub']=M('dic')->where(array('pid'=>$v['id']))->field('id,field,pid,name,is_must')->select();
       	}
       	$custom_id=I('custom_id');
       	$this->assign('custom_id', $custom_id);
       	$this->assign('type', I('type'));
        $this->assign('dic', $dic);
    }
    
    public function detail(){
    	if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
        } else {
            $this->display();
        }
    }

    public function _before_insert($data='') {
    	
        return $data;
    }

    public function _before_edit() {
        $this->_before_add();
    }

    public function _before_update($data=''){
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
        return $data;
    }

    public function ajax_check_name() {
        $name = I('cutaShortName','', 'trim');
        $id = I('id','', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajaxReturn(array('status'=>0));
        } else {
            $this->ajaxReturn(array('status'=>1));
        }
    }
   
}