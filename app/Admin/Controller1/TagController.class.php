<?php
namespace Admin\Controller;
class TagController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Tag');
        $this->set_mod('Tag');
        $this->rel_mod=D('Merchant');
        
		$this->admin=$admin=cookie('admin');
		$this->role_id=$admin['role_id'];
		$this->admin_id=$admin['id'];
		$this->sid=$admin['store_id'];
		$this->assign('admin',$this->admin);
    }

	protected function _search() {
        $map = array();
        ($keyword = I('keyword','', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        if( $this->role_id==2||$this->role_id==8 ){

            $map['store_id']=$this->sid;

        }
        else{
	        if($_GET['store_id']==null){
	        	$map['store_id']=-1;
	        }
	        else{
	        	$map['store_id']=$store_id=$_GET['store_id'];
	        }
        }
        $this->assign('search', array(

            'status' =>$status,
            'keyword' => $keyword,
			'store_id'=>$store_id
        ));
        return $map;
    }

    public function ajax_check_name() {
        $name = I('name','', 'trim');
        $id = I('id','', 'intval');
        $sid = I('store_id','', 'intval');
        if (D('tag')->name_exists($name, $id,$sid)) {
            $this->ajax_return(0, L('标签已存在'));
        } else {
            $this->ajax_return(1);
        }
    }
    public function _before_index(){
    	$this->list_relation=true;
    	$this->assign('store_list',$this->store_info());
    }
    public function _before_add(){
    	$this->assign('store_list',$this->store_info());
    }
     public function _before_edit(){
    	$this->assign('store_list',$this->store_info());
    }
    public function store_info(){
    	$info=$this->rel_mod->where(array('status'=>1))->field('id,title')->select();
    	return $info;
    }
}