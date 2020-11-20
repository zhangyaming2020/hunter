<?php
namespace Admin\Controller;
class QunController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
    	$this->page_size=10;
        $this->_mod = D('Qun');
        $this->set_mod('Qun');
  }

    public function _before_index() {
    	$this->list_relation=true;
    }


	protected function _search() {

        $map = array();

        //'status'=>1

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        ($price_min = I('price_min','',  'trim')) && $map['net_price'][] = array('egt', $price_min);

        ($price_max = I('price_max','',  'trim')) && $map['net_price'][] = array('elt', $price_max);
		
        ($uname = I('cutaname','',  'trim')) && $map['cutaName'] = array('like', '%'.$uname.'%');
		
		($funnel = I('funnel','',  'trim')) && $map['cutaFunnel'] = $funnel;
		if(!$funnel){
			$funnel=-1;
		}
        $type = I('list_type',0, 'intval');
		switch($type){
			case 1:$map['type'] = array('in',array(1,2));$map['owner_id'] = $_SESSION['admin']['id'];
			break;
			case 2:$map['type'] = 0;break;
			case 3:
			$share_ids=$_SESSION['admin']['id'];
			$field= "type=2 and id in(select id from jrkj_custom where share_ids like '%".','.$share_ids."' or share_ids like '".$share_ids.",%' or share_ids like '%,".$share_ids.",%' or share_ids=".$share_ids.')';
		    $map['_string']=$field;
			break;
			case 4:$map['type'] = 1;$map['owner_id'] = array('neq',$_SESSION['admin']['id']);break;
			default:$map['type']=1;$map['owner_id'] = $_SESSION['admin']['id'];
		}

        
        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,

            'price_min' => $price_min,

            'funnel' => $funnel,

            'uname' => $uname,

            'status' =>$status,

            'selected_ids' => $spid,

            'share_ids' => $share_ids,

            'keyword' => $keyword,

            'type' =>$type,
        ));
        //                                                                                                                                                            dump($map);
        return $map;

    }
    
    public function _before_add() {
    	
        $tag= M('tag')->order('id desc')
        ->select();
        $this->assign('tag', $tag);
    }
    
    
   
    

    public function _before_insert($data='') {
    	$pid=I('pid');//省市区
    	$spid=M('place')->where(array('id'=>$pid))->getField('spid');
    	$spid=explode('|',$spid.$pid);
    	$fields=array('province_id','city_id','district');
    	if($spid){
    		foreach($spid as $k=>$v){
    			$data[$fields[$k]]=$v;
    		}
    	}
    	$data['logo']=I('img');
    	$data['add_time']=time();
		$data['creator_id']=0;
        return $data;
    }

    public function _before_edit() {
        $this->_before_add();
        $pid=M('custom')->where(array('id'=>I('id')))->getField('pid');
        $pid&&$spid=M('place')->where(array('id'=>$pid))->getField('spid');
    	$pid&&$spid&&$spid=$spid.$pid;
    	$type=I('type');
    	$this->assign('spid',$spid);
    	$this->assign('type',$type);
    }

    public function _before_update($data=''){
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
        //dump($data);exit;
        return $data;
    }
	public function _after_insert($id){
		$data=M('Linkman')->create($_POST);
		$data['customer_id']=$id;
		M('Linkman')->add($data);
	}
    public function ajax_check_name() {
        $name = I('cutaName','', 'trim');
        $id = I('id');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajaxReturn(array('status'=>0));
        } else {
            $this->ajaxReturn(array('status'=>1));
        }
    }
    
    /**
     * 放入公共库操作
     */
    public function add_public()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_AJAX) {
        	$act=I('act');
        	$data['is_share']=0;
        	if($act){//占有
        		$data['type']=1;
        		$data['owner_id']=$_SESSION['admin']['id'];
        	}
        	else{//放入公共库
        		$data['type']=0;
        		$data['owner_id']=0;
        		$type=$mod->where(array('id'=>I('id')))->getField('type');
        		IS_AJAX &&($type==2) && $this->ajax_return(0, '放入公共库前,请取消共享权限');
        	}
            $res=$mod->where(array('id'=>I('id')))->save($data);
            if (false !==$res ) {
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        }
    }
     //上传图片
    public function ajax_upload_img() {
        $type = I('type', 'img', 'trim');
//		print_r($_FILES[$type]['name']);  exit;
        if (!empty($_FILES[$type]['name'])) {
            $dir = date('ym/d/');
            $result = $this->_upload($_FILES[$type], 'logo/'. $dir );
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $savename = $dir . $result['info'][0]['savename'];
                $this->ajax_return(1, L('operation_success'), $savename);
            }
        } else {
            $this->ajax_return(0, L('illegal_parameters'));
        }
    }
   
}