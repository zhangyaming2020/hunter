<?php
namespace Admin\Controller;
class ContractController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
    	$this->page_size=10;
    	$this->status=array('待审核','已审核','已过期','已作废');
        $this->_mod = D('Contract');
        $this->set_mod('Contract');
  }

    public function _before_index() {
    	$this->list_relation=true;
    	$this->assign('status', $this->status);
    }


	protected function _search() {

        $map = array();

        //'status'=>1

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        ($price_min = I('price_min','',  'trim')) && $map['net_price'][] = array('egt', $price_min);

        ($price_max = I('price_max','',  'trim')) && $map['net_price'][] = array('elt', $price_max);
		
        ($uname = I('keyword','',  'trim')) && $map['cotaName'] = array('like', '%'.$uname.'%');
		
		($brand_id = I('brand_id','',  'trim')) && $map['brand_id'] = $brand_id;
		($status = I('status','',  'trim')) && $map['status'] = $status-1;
		($custom_id = I('custom_id'));
		($resume_id = I('resume_id'));
		($is_tab = I('is_tab'));
        $type = I('type');
        $is_add = I('is_add');
        if(isset($_GET['type'])){//我的项目和其他人的项目
        	switch($type){
				case 1:$map['owner_id'] = $_SESSION['admin']['id'];break;
				case 2:$map['owner_id'] =array('neq', $_SESSION['admin']['id']);break;
				default:$map['owner_id'] = $_SESSION['admin']['id'];break;
			}
        }
		if(isset($_GET['resume_id'])){//简历模块
        	if(isset($_GET['is_tab'])){//简历下的项目
        		$project_ids=M('resume_project')->where(array('resume_id'=>$resume_id))
        		->order('add_time desc')->getField('project_id',true);
        		$map['id']=array('in',$project_ids);
        	}
        	else{//简历下选择项目
        		$map['owner_id'] = $_SESSION['admin']['id'];
        	}
        }
        
        if(isset($_GET['custom_id'])){//客户下的项目
				$map['custom_id'] = $custom_id;
        }
        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,

            'price_min' => $price_min,

            'price_max' => $price_max,

            'rates_max' => $rates_max,

            'status' =>$status,

            'selected_ids' => $spid,

            'cate_id' => $cate_id,

            'keyword' => $uname,

            'custom_id' =>$custom_id,
            
            'resume_id'=>$resume_id,
            
            'is_tab'=>$is_tab,
            
            'is_add'=>$is_add,
        ));
        return $map;

    }
    public function _before_add() {
    	$custom_id = I('custom_id','','intval');
        $admin=M('admin')->where(array('status'=>1))->select();
        $this->assign('admin',$admin);
        $this->assign('custom_id',$custom_id);
    }
    
    public function detail(){
        $id=I('id');
        $info=D('Contract')->where(array('id'=>$id))->relation(true)->find();
        $this->assign('custom_id',$id);
        $this->assign('info',$info);
        $this->assign('status', $this->status);
        $this->assign('admin_id',$_SESSION['admin']['id']);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'contract_detail');
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
    

    public function _before_insert($data) {
    	
		$data['creator_id']=$_SESSION['admin']['id'];
		$data['paperSaveDate']=strtotime($data['paperSaveDate']);
		$data['validity']=strtotime($data['validity']);
        return $data;
    }
    
    public function _after_insert($id) {
		$admin=$_SESSION['admin']['id'];
		$time=time();
		$data=array();
		if(I('electronicDocumentFile')){
			$data[0]=array(
				'creator_id'=>$admin,
				'add_time'=>$time,
				'contract_id'=>$id,
				'name'=>I('electronicDocumentFile')
			);
		}
		if(I('scanDocumentFile')){
			$data[1]=array(
				'creator_id'=>$admin,
				'add_time'=>time(),
				'contract_id'=>$id,
				'name'=>I('scanDocumentFile')
			);
		}
        if($data){
        	M('contract_attach')->addAll($data);
        }
    }

    public function _before_edit() {
        $this->_before_add();
        $pid=M('custom')->where(array('id'=>I('id')))->getField('pid');
        $pid&&$spid=M('place')->where(array('id'=>$pid))->getField('spid');
    	$pid&&$spid&&$spid=$spid.$pid;
    	$this->assign('spid',$spid);
    	$this->_relation=true;
    }

    public function _before_update($data=''){
    	$data['paperSaveDate']&&$data['paperSaveDate']=strtotime($data['paperSaveDate']);
        $data['validity']&&$data['validity']=strtotime($data['validity']);
        return $data;
    }
    public function ajax_check_name() {
        $name = I('cutaShortName','', 'trim');
        $id = I('id','', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajaxReturn(array('status'=>0));
        } else {
            $this->ajaxReturn(array('status'=>1));
        }
    }
    
    public function attachment(){
    	if(IS_AJAX){
    		if($_FILES){
    			$date_dir = date('ym/d/'); //上传目录
    			$result = $this->_upload($_FILES['img'], 'contract/'.$date_dir);
    			
    			$result['error']&&$this->ajax_return(0, $result['info']);
    			$result&&$this->ajax_return(1, '上传成功',$date_dir.$result['info'][0]['savename']);
    		}
    	}
    	
    }
   
}