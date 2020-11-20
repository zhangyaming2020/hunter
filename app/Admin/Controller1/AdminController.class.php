<?php
namespace Admin\Controller;
class AdminController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Admin');
        $this->_cate_mod = D('AdminRole');
        $this->set_mod('Admin');
    }
	
/*	 public function index() {
	 	$admin = session('admin');
		if($admin['role_id'] != 1){
			$where = "role_id != 1";
		}
		
		$member =	$this->_mod->where($where)->relation(true)->select();
		$this->assign('list',$member);
		$this->assign('list_table', true);	  	
		$this ->display();
    }*/

    protected function _search() {
        $map = array();


        return $map;
    }

    public function _before_index() {
        $this->list_relation = true;
    }

    public function _before_add() {
        $pid = I('pid',$_SESSION['admin']['role_id'] == 1 ? 0 : $_SESSION['admin']['role_id'],'intval');
        if ($pid) {
            $spid = $this->_cate_mod->where(array('id'=>$pid))->getField('spid');
            $spid = $spid ? $spid.$pid : $pid;
            $this->assign('spid', $spid);
        }
        $this->assign('store_list', $store_list);
        $this->assign('id_all', json_encode($id_all));
    }
	
    public function _before_insert($data) {
        $data['password'] = md5($data['password']);
        ($data['role_id'] == $_SESSION['admin']['role_id'] && $_SESSION['admin']['role_id'] != 1) && $this->ajax_return(0, '请选择角色');

        return $data;
    }

    public function _before_edit() {
        $spid_list = $this->_cate_mod->getField('id,spid');

        $this->assign('spid_list', $spid_list);
        $this->assign('store_list', $store_list);
        $this->assign('id_all', json_encode($id_all));
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
        $name = I('username','', 'trim');
        $id = I('id','', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            echo 0;
        } else {
            echo 1;
        }
    }
   
}