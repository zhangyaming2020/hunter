<?php
namespace Admin\Controller;
class ProgressController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
    	$this->page_size=2;
        $this->_mod = D('Progress');
        $this->set_mod('Progress');
  }

    public function _before_index() {
    	$dic=array(
    		'0'=>array('name'=>'好','pid'=>11),
    		'1'=>array('name'=>'很好','pid'=>11),
    	);
    	//dump(M('dic')->addAll($dic));
    	$type=I('list_type');
    	$this->assign('type', $type);
    }


	protected function _search() {

        $map = array();

        //'status'=>1

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        ($price_min = I('price_min','',  'trim')) && $map['net_price'][] = array('egt', $price_min);

        ($price_max = I('price_max','',  'trim')) && $map['net_price'][] = array('elt', $price_max);
		
        ($uname = I('uname','',  'trim')) && $map['uname'] = array('like', '%'.$uname.'%');
		
		($type = I('type')) && $map['type'] = $type;
		
		($resume_id = I('resume_id')) && $map['resume_id'] = $resume_id;
		
		($custom_id = I('custom_id')) && $map['custom_id'] = $custom_id;
		
		($project_id = I('project_id')) && $map['project_id'] = $project_id;
		
		($report_id = I('report_id')) && $map['report_id'] = $report_id;
		
		($offer_id = I('offer_id')) && $map['offer_id'] = $offer_id;
        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,

            'price_min' => $price_min,

            'price_max' => $price_max,

            'rates_max' => $rates_max,

            'status' =>$status,

            'selected_ids' => $spid,

            'share_ids' => $share_ids,

            'keyword' => $keyword,

            'type' =>$type,
        ));
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
        
        $admin=M('admin')->where(array('status'=>1))->field('username,id')->select();
    	$info=M('custom')->where(array('id'=>$id))->select();
    	$this->assign('contact',$contact);
    	$this->assign('admin',$admin);
        $this->assign('custom_id',$id);
        $this->assign('info',$info);
        $this->assign('type',$type);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
        } else {
            $this->display();
        }
    }
    
    public function share(){
        $custome_id=I('custom_id');
        $mod=M('custom');
        $admin=M('admin')->where(array('status'=>1))->field('username,id')->select();
    	$this->assign('admin',$admin);
        $this->assign('custom_id',$custome_id);
        if (IS_POST) {
            $res=$mod->where(array('id'=>I('id')))->save(array('is_share'=>1,'share_ids'=>I('share_ids')));
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
    	$this->assign('spid',$spid);
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
        $name = I('cutaShortName','', 'trim');
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