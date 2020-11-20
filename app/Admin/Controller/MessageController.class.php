<?php
namespace Admin\Controller;
class MessageController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Message');
        $this->set_mod('Message');
		$this->_cate_mod=D('Apply');
    }

    public function _before_index() {
    	
        $this->assign('apply_city_list',C('apply_city_list'));
        $this->assign('apply_age_list',C('apply_age_list'));
		$message_cate =M('apply')->where(array('status'=>1))->select();
		$this->assign('message_cate',$message_cate);//留言分类
	    //$this->_
    }

    public function _before_add() {
        $role_list = M('admin_role')->where('status=1')->select();
        $this->assign('role_list', $role_list);
    }

    public function _before_insert($data='') {
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
        return $data;
    }
	
	   protected function _search() {

        $map = array();

        //'status'=>1

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

    	$cate_id = I('cate_id',0, 'intval');

        if ($cate_id) {

            $map['type_id'] = $cate_id;

        }

        if( $_GET['status']==null ){

            $status = -1;

        }else{

            $status = intval($_GET['status']);

        }

        $status>=0 && $map['status'] = array('eq',$status);

        ($keyword = I('keyword','',  'trim')) && $map['content'] = array('like', '%'.$keyword.'%');

        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,
			
            'cate_id' => $cate_id,

            'keyword' => $keyword,
        ));
        return $map;

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
	
	 /**

     * 获取紧接着的下一级分类ID

     */

    public function ajax_getchilds() {
        $id = I('id',0, 'intval');

        $type = I('type', null, 'intval');

        $map = array('pid'=>$id);

        if (!is_null($type)) {

            $map['type'] = $type;

        }
        $map['status'] = 1;
        $return = $this->_cate_mod->field('id,name')->where($map)->order('ordid,id')->select();
        if ($return) {

            $this->ajax_return(1, L('operation_success'), $return);

        } else {
            $this->ajax_return(0, L('operation_failure')); 

        }

    }

   
}