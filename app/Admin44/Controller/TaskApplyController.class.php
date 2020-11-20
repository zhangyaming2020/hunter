<?php
namespace Admin\Controller;
class TaskApplyController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('TaskApply');
        $this->set_mod('TaskApply');
    }

    public function _before_index(){
        $this->list_relation = true;
    }

    protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['create_time'][] = array('egt', $time_start);
        ($time_end = I('time_end','', 'trim')) && $map['create_time'][] = array('elt', $time_end.'23:59:59');
        ($task_type = I('task_type',0,'intval')) && $map['task_type_id'] = $task_type;
        if($status = I('status','', 'trim')){
            if($status == '2'){
                $map['status'] = array('eq', 0);
            }else{
                $map['status'] = array('neq', 0);
            }

        }
        ($keyword = I('keyword','', 'trim')) && $map['title'] = array('like', '%'.$keyword.'%');

        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'status'  => $status,
            'keyword' => $keyword,
            'task_type' => $task_type,
        ));
        return $map;
    }

    public function _before_add(){
        $member_list = M('Member')->where(array('member_type'=>1))->limit(100)->select();
        $this->assign('member_list',$member_list);
        $this->assign('bind_status',C('bind_status'));
    }

    public function _before_edit(){
        $id = I('id');
        $member_id = $this->_mod->where(array('id'=>$id))->getField('member_id');
        $bind = M('MerchantBind')->field('id,account')->where(array('member_id'=>$member_id))->select();
        $this->assign('bind',$bind);
        $this->assign('task_type',C('task_type'));
        $this->assign('buyer_bind_rank',C('buyer_bind_rank'));
        $this->assign('task_terminal',C('task_terminal'));
    }

    public function port(){
        $map = $this->_search();

        $data = $this->_mod->where($map)->relation(true)->select();
        $new_data = array();
        foreach($data as $val){
            unset($val['content']);
            $val['title'] = str_replace(',','，',$val['title']);
            $new_data[] = $val;
        }
        export_csv($new_data);
    }

}