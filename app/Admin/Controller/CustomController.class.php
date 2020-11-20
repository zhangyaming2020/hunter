<?php
namespace Admin\Controller;
class CustomController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
    	$this->page_size=10;
        $this->_mod = D('Custom');
        $this->set_mod('Custom');
  }

    public function _before_index() {
    	$dic=array(
    		'0'=>array('name'=>'好','pid'=>11),
    		'1'=>array('name'=>'很好','pid'=>11),
    	);
    	$status=array('待审核','已审核','已过期','已作废');
    	$contract_type=array('contract_red','contract_green','contract_brown','contract_gray');
    	//dump(M('dic')->addAll($dic));
    	$funnel=M('dic')->where(array('pid'=>5))->field('id,name')->select();
    	$this->assign('funnel', $funnel);
    	//统计
    	$type = I('list_type',0, 'intval');
		switch($type){
			case 1:$map['owner_id'] = $_SESSION['admin']['id'];
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
    	$status_nums=array();
    	foreach($funnel as $k=>$v){
    		$map['cutaFunnel']=$v['id'];
    		$status_nums[$v['id']]=M('custom')->where($map)->group('cutaFunnel')->count();
    		//dump(M('custom')->getLastSql());
    		$status_nums[$v['id']]=$status_nums[$v['id']]?$status_nums[$v['id']]:0;
    	}
    	$map['cutaFunnel']=array('neq',0);
    	$status_nums[-1]=M('custom')->where($map)->count();//全部
    	$this->list_relation=true;
    	$this->assign('type', $type);
    	$this->assign('contract_type', $contract_type);
    	$this->assign('status', $status);
    	$this->assign('status_nums', $status_nums);
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
    	
        $dic= M('dic')->where(array('id'=>array('neq',4),'pid'=>1))->field('id,field,pid,name,is_must')->select();
       	foreach($dic as $k=>$v){
       		$dic[$k]['sub']=M('dic')->where(array('pid'=>$v['id']))->field('id,field,pid,name,is_must')->select();
       	}
        $id=I('id');
        $this->assign('id', $id);
        $this->assign('dic', $dic);
    }
    
    public function detail(){
        $id=I('id');
        $type=I('type');
        $contact=M('linkman')->where(array('customer_id'=>$id))->order('add_time desc')->select();
        
        $admin=M('admin')->where(array('status'=>1))->field('nickname as username,id')->select();
    	$info=D('Custom')->where(array('id'=>$id))->relation(true)->find();
    	$this->assign('contact',$contact);
    	$this->assign('admin',$admin);
        $this->assign('custom_id',$id);
        $this->assign('info',$info);//dump($contact);
        $this->assign('type',$type);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
        } else {
            $this->display();
        }
    }
    
    public function share(){//共享和取消共享
        $custome_id=I('custom_id');
        $mod=M('custom');
        $admin=M('admin')->where(array('status'=>1))->field('username,id')->select();
    	$this->assign('admin',$admin);
    	$share_ids=$mod->where(array('id'=>$custome_id))->getField('share_ids');
    	//dump($share_ids);exit;
    	$this->assign('share_ids',$share_ids);
        $this->assign('custom_id',$custome_id);
        if (IS_POST) {
        	$share_ids=I('share_ids');
        	if($share_ids){//共享
        		$res=$mod->where(array('id'=>I('id')))->save(array('type'=>2,'share_ids'=>$share_ids));
            }
        	else{//取消
        		$res=$mod->where(array('id'=>I('id')))->save(array('type'=>1,'share_ids'=>''));
        	}
            if (false !== $res) {
               
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'share');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } 
        else{
        	if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
	        } else {
	            $this->display();
	        }
        }
        
    }
    

    public function _before_insert($data='') {
    	$pid=I('pid');//省市区
    	$spid=M('place')->where(array('id'=>$pid))->getField('spid');
    	$spid=explode('|',$spid.$pid);
    	$fields=array('cutaProvince','cutaCity','cutaDistrict');
    	if($spid){
    		foreach($spid as $k=>$v){
    			$data[$fields[$k]]=$v;
    		}
    	}
//      if( ($data['password']=='')||(trim($data['password']=='')) ){
//          unset($data['password']);
//      }else{
//          $data['password'] = md5($data['password']);
//      }
		$data['creator_id']=$data['owner_id']=$_SESSION['admin']['id'];
		//dump($data);exit;
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
   
}