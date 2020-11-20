<?php
namespace Admin\Controller;
use Admin\Org\Image;
use Admin\Org\Tree;
class CouponsController extends AdminCoreController {
	
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Coupons');
        $this->set_mod('Coupons');
    }
	public function _before_index(){
		$p = I('p',1,'intval');
        $this->assign('p',$p);
	}
	//
	public function _before_insert($data){
		$data['add_time'] = $_SERVER['REQUEST_TIME'];
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = strtotime($data['end_time']);
		$data['residue_number'] = $data['number'];
		return $data;
	}
	
	//优惠券发放列表
	public function coupon(){
		$map = array();
		($start_time = I('start_time','',''))&&$map['cs.start_time'] = array('egt',strtotime($start_time));
		($end_time = I('end_time','',''))&&$map['cs.end_time'] = array('elt',strtotime($end_time +' 1 days'));
		($title = I('title','','trim'))&&$map['cs.title'] = array('like','%'.$title.'%');
		($amount = I('amount','','trim'))&&$map['cs.amount'] = $amount;
		($sn_no = I('sn_no','','trim'))&&$map['mc.sn_no'] = $sn_no;
		$status = I('status','','trim');
		if($status!=-1 && $status!='') $map['mc.status'] = $status;
		$this->assign('search',array(
			'title'=>$title,
			'sn_no'=>$sn_no,
			'amount'=>$amount,
			'start_time'=>$start_time,
			'end_time'=>$end_time,
			'status'=>$status,
		));
		$card = $this->_mod->where(array('residue_number'=>array('gt',0)))->select();
		$count = D()->table('__MEMBER_COUPONS__ mc')
				   ->join('__COUPONS__ cs on cs.id = mc.coupons_id')
				   ->join('__MEMBER__ m on m.id = mc.member_id')
				   ->where($map)
				   ->count();
		$Page = new \Think\Page($count,50);
		$Page->setConfig('prev', "上一页");//上一页
		$Page->setConfig('next', '下一页');//下一页
		$Page->setConfig('first', '首页');//第一页
		$Page->setConfig('last', '末页');//最后一页
		$Page->rollPage =6;
		$Page ->setConfig ( 'theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%' );
		$show = $Page->show(); 
		$this->assign('page',$show);
		$list = D()->table('__MEMBER_COUPONS__ mc')
				   ->join('__COUPONS__ cs on cs.id = mc.coupons_id')
				   ->join('__MEMBER__ m on m.id = mc.member_id')
				   ->field('mc.*,cs.title,cs.amount as amoun,cs.full,cs.end_time,m.id as member_id,m.nickname,m.avatar')
				   ->order('id desc')
				   ->limit($Page->firstRow.','.$Page->listRows)
				   ->where($map)
				   ->select();
		$this->assign('list',$list);
		$p = I('p',1,'intval');
        $this->assign('p',$p);
		$this->assign('card',$card);
		$this->display();
	}
	//优惠券删除
	public function del($id){
		$db = M('Coupons')->delete($id);
		$this->ajax_return($db, $db ? L('operation_success') : L('operation_failure'));
	}
	//优惠券删除
	public function delete($id){
		$db = M('MemberCoupons')->delete($id);
		$this->ajax_return($db, $db ? L('operation_success') : L('operation_failure'));
	}
	//优惠券发放
	public function use_coupons(){
		if(IS_POST){
			$number = I('number','','intval'); 
			$member_id = I('member_id','','intval'); 
			$coupons_id = I('coupons_id','','intval'); 
			$all = I('all',0,'intval');
			$info = $this->_mod->field('residue_number,amount,start_time,end_time')->find($coupons_id);
			($info['residue_number'] < 0) &&$this->error('优惠券已发放完','','1');
			($info['residue_number'] < $number) &&$this->error('优惠券数量不足，剩余'.$info['residue_number'].'张','','1');
			if($all == 1){
				$db = D('Member')->where(array('status'=>1))->field('id')->select();
				foreach($db as $k => $v){
					$data[$k] = array(
						'member_id' =>$v['id'],
						'coupons_id' =>$coupons_id,
						'sn_no' => make_sn_no('MemberCoupons',11,$arr),
						'add_time' => time(),
						'status' => 1,
						'amount' =>$info['amount'],
					);
				}
			}else{
				if($member_id==0 || $member_id=='')
					$this->error('单个会员发放必须填写会员id','','1');
				for($i=0;$i<$number;$i++){
					$data[$i] = array(
						'member_id' =>$member_id,
						'coupons_id' =>$coupons_id,
						'sn_no' => make_sn_no('MemberCoupons',11,$arr),
						'add_time' => time(),
						'status' => 1,
						'amount' =>$info['amount'],
					);
				}
			}
			$MemberCoupons = D('MemberCoupons');
			$MemberCoupons->startTrans();
			$db1 = $MemberCoupons->addAll($data);
			$db2 = D('Coupons')->where(array('id'=>$coupons_id))->setDec('residue_number',$number);
			($db1 && $db2) ? $MemberCoupons->commit() : $MemberCoupons->rollback();
			($db1 && $db2) ? $this->success('发放成功','',1) : $this->error('发放失败,请检查是否数量不足','',1);
		}	
	}
	
	/**
     * ajax修改单个字段值
     */
    public function ajax_edit()
    {
        //AJAX修改数据
        $mod = D('Coupons');
        $pk = $mod->getPk();
        $id = I($pk, 'intval');
        $field = I('field', 'trim');
        $val = I('val', 'trim');
		if($field =='end_time') $val = strtotime($val);
        //允许异步修改的字段列表  放模型里面去 TODO
        $mod->where(array($pk=>$id))->setField($field, $val);
        $this->ajax_return(1);
    }

    protected function _search() {
        $map = array();
		($start_time = I('start_time','',''))&&$map['start_time'] = array('egt',strtotime($start_time));
		($end_time = I('end_time','',''))&&$map['end_time'] = array('elt',strtotime($end_time +' 1 days'));
		($title = I('title','','trim'))&&$map['title'] = $title;
		($amount = I('amount','','trim'))&&$map['amount'] = $amount;
		$this->assign('search',array(
			'title'=>$title,
			'amount'=>$amount,
			'start_time'=>$start_time,
			'end_time'=>$end_time,
		));
        return $map;
    }

  

}