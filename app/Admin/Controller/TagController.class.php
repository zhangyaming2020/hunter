<?php
namespace Admin\Controller;
class TagController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Tag');
        $this->set_mod('Tag');
    }

	protected function _search() {
        $map = array();
        ($keyword = I('keyword','', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
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
    }
}