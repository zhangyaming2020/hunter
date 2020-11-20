<?php
namespace Admin\Controller;
class AdminController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Admin');
        $this->set_mod('Admin');
    }
	
	 public function index() {
	 	$admin = session('admin');
		$count      = $this->_mod ->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$member = $this->_mod ->where($where)->relation(true)->order('id asc')
		               ->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$member);	
		$this->assign('page',$show);// 赋值分页输出	
		$this ->display();
    }
	
	
    public function _before_index() {
        $this->list_relation = true;
    }

    public function _before_add() {
    	/*if($_SESSION['admin']['role_id'] == 1){
    		$city_id = M('place')->where('type=2 and status=1')->select();
    	}else{
    		$city_id = $_SESSION['admin']['city_id'];
    	}*/
 
    	if($_SESSION['admin']['role_id'] != 1){
    		$where = array('id'=>arrar('neq',1),'status'=>1);
    	}else{
    		$where = array('status'=>1);
    	}
		
		$city_id = region_division();
        $role_list = M('admin_role')->where($where)->select();
        $this->assign('role_list', $role_list);
        $this->assign('city_id', $city_id);
    }
	
    public function _before_insert($data='') {
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
		/*$admin = session('admin');
		$data['city_id'] = $admin['city_id'];*/
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
        $name = I('username','', 'trim');
        $id = I('id','', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            echo 0;
        } else {
            echo 1;
        }
    }
   
}