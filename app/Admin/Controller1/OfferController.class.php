<?php
namespace Admin\Controller;
use Think\Page;
class OfferController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
        $this->_mod = D('Offer');
        $this->set_mod('Offer');
        $this->assign('admin_id',$_SESSION['admin']['id']);
        $this->page_size =I('page_size');
        $this->status=array('等待通知候选人','候选人、客户协商中','成功-待审核','成功-待付款','已完成','失败','审核不通过');
        $this->status_color=array('#808080','#ff6600','#FF8000','#008000','#008000','#ff0000','#ff0000');
       
 }
    public function _before_index() {
    	$this->list_relation=true;
    	$_SESSION['admin']['id']!=1&&$map['creator_id'] = $_SESSION['admin']['id'];
    	$status_nums=array();
        $all=0;
		$status_nums=M('offer')->where($map)->group('oftaState')->field("count(*) as nums,oftaState")->select();
		
		foreach($status_nums as $k=>$v){
			$all+=$v['nums'];
			$nums[$v['oftastate']]=$v['nums'];
		}
    	$this->assign('status',$this->status);
    	$this->assign('status_color',$this->status_color);
		$this->assign('nums',$nums);
		$this->assign('all',$all);
    	//dump(C('THINK_EMAIL'));exit;
    	//dump($_GET);exit;
    }


	protected function _search() {

        $map = array();

        //'status'=>1

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        ($price_min = I('price_min','',  'trim')) && $map['net_price'][] = array('egt', $price_min);

        ($oftaState = I('oftaState','',  'trim')) && $map['oftaState']= $oftaState-1;
		
        ($uname = I('uname','',  'trim')) && $map['uname'] = array('like', '%'.$uname.'%');
		
		$keyword = I('keyword','', 'trim');
         if($keyword){
         	$map['_string']="project_id in(select id from jrkj_project where prtaName like '%".$keyword."%') or custom_id in(select id from jrkj_custom where cutaName like '%".$keyword."%') or resume_id in(select id from jrkj_resume where resuName like '%".$keyword."%')";
         }
		($custom_id = I('custom_id'));
		
		($resume_id = I('resume_id'));
		
		($project_id = I('project_id'));
		
		($is_tab = I('is_tab'));
		
        $type = I('type');
        $is_add = I('is_add');
        $map['creator_id']!=1&&$map['creator_id'] = $_SESSION['admin']['id'];
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
        if($_GET['oftaState']<0){
        	unset($map['oftaState']);
        }
        if(isset($_GET['custom_id'])){//客户下的项目
				$map['custom_id'] = $custom_id;
        }
        
        if(isset($_GET['project_id'])){//客户下的项目
				$map['project_id'] = $project_id;
        }
        
        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,

            'price_min' => $price_min,

            'price_max' => $price_max,

            'rates_max' => $rates_max,

            'oftaState' =>$oftaState-1,

            'selected_ids' => $spid,

            'cate_id' => $cate_id,

            'keyword' => $keyword,

            'custom_id' =>$custom_id,
            
            'resume_id'=>$resume_id,
            
            'is_tab'=>$is_tab,
            
            'is_add'=>$is_add,
        ));
        return $map;

    }
    //
    public function ajax_list(){ 
    	$model=M('custom');
    	$map=array();
    	($name = I('name','',  'trim')) && $map['cutaName'] = array('like', '%'.$name.'%');
    	$count = $model->where($map)->count();
        $pager = new Page($count, 10);
        $select = $model->field('id,cutaName')->where($map); 
		//$total=$model->count();
		$list=$select->limit($pager->firstRow.','.$pager->listRows)->select();
		$pager->setConfig("last","末页");
		$pager->setConfig("first","首页");
        $page = $pager->show();
        $this->assign("page", $page);
        $this->assign("list", $list);
    	if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'ajax_list');
        } else {
            $this->display();
        }
    }
    public function ajax_contact_list(){
    	$id=I('custom_id');
    	$id&&$list=M('linkman')->where(array('customer_id'=>$id))->field('id,cucoName,cucoOfficeTel')->order('add_time desc')->select();
        if($list){
        	$str='';
        	foreach($list as $k=>$v){
        		$str.='<li class="current" style="height:30px;line-height:30px;">';
				$str.='<label style="width:100%;margin-left:15px; float: none; text-align: left;"><a style="text-decoration:none;" href="javascript:;"><input type="checkbox" name="cucoId" class="name" cuconame="'.$v['cuconame'].'" value="'.$v['id'].'">&nbsp;&nbsp;'.$v['cuconame'].'&nbsp;&nbsp;'.$v['cucoofficetel'].'</a></label>';
				$str.='</li>';
						
        	}
        	$this->ajaxReturn($str);
        }
    }
    public function _before_add() {
    	
       
        $id=I('id');
        $report_id=I('report_id');
        $resume_id=I('resume_id');
        $custom_id=I('custom_id');
        $project_id=I('project_id');
        $custom_id=I('custom_id');
        $custom_info=array(
        			'custom_id'=>I('custom_id'),
        			'cutaname'=>M('custom')->where(array('id'=>I('custom_id')))->getField('cutaname')
        			);
        $contract=M('contract')->where(array('custom_id'=>$custom_id,'status'=>1))->field('cotaname,id')->select();
        $arr['position_num']=M('project')->where(array('id'=>$project_id))->getField('prtaPositionNum');//项目招聘人数
        $arr['offer_nums']=M('offer')->where(array('project_id'=>$project_id))->count();//产生的offer数量
        //dump($arr);
        $this->assign('id', $id);
        $this->assign('arr', $arr);
        $this->assign('report_id', $report_id);
        $this->assign('resume_id', $resume_id);
        $this->assign('project_id', $project_id);
        $this->assign('custom_id', $custom_id);
        $this->assign('contract', $contract);
        $this->assign('custom_info', $custom_info);
        $this->assign('dic', $dic);
    }
    
    public function detail(){
        $id=I('id');
        $custom_id=I('custom_id');
        $custom_id&&$contact=M('linkman')->where(array('customer_id'=>$custom_id))->order('add_time desc')->select();
        $info=D('Offer')->where(array('id'=>$id))->relation(true)->find();
        $nums=M('contract')->where(array('custom_id'=>$info['custom_id'],'status'=>1))->count();
    	//dump($info);
    	$this->assign('contact',$contact);
        $this->assign('info',$info);
        $this->assign('nums',$nums);//客户是否有有效的合同
        //dump($info);exit;
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'offer_detail');
        } else {
            $this->display();
        }
    }
	//候选人
	public function candidate(){
		$mod=D('ResumeProject');
    	$map=array();
    	($name = I('name','',  'trim')) && $map['cutaName'] = array('like', '%'.$name.'%');
    	($project_id =I('project_id')) && $map['project_id'] =$project_id;
    	$this->list_relation=true;
    	$this->_list($mod, $map,'','','',8);
		if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'candidate');
        } else {
            $this->display();
        }
	}
	//候选人
	public function finish(){
		$mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST) {
        } else {
            $id = I($pk, 'intval');
            $this->_relation && $mod->relation(true);
            $info = $mod->find($id);//dump($info);exit;
         	if(method_exists($this, '_after_edit')){
                $info=$this->_after_edit($info);
            }
            //dump($info);
            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
	}
	
	//候选人
	public function check(){
		$mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST) {
            
        } else {
            $id = I($pk, 'intval');
            $this->_relation=true;
            $this->_relation && $mod->relation(true);
            $info = $mod->find($id);//dump($info);exit;
         	if(method_exists($this, '_after_edit')){
                $info=$this->_after_edit($info);
            }
            //dump($info);
            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
	}
    public function _before_insert($data='') {
    	$data['creator_id']=$_SESSION['admin']['id'];
    	$data['sendDate']=strtotime($data['sendDate']);
    	$data['guaranteeEndDate']=strtotime($data['guaranteeEndDate']);
    	//dump($data);exit;
        return $data;
    }

    public function _before_edit() {
        $this->_before_add();
        $this->_relation=true;
    }
    
    public function _before_update($data) {
    	$data['sendDate']&&$data['sendDate']=strtotime($data['sendDate']);
    	$data['guaranteeEndDate']&&$data['guaranteeEndDate']=strtotime($data['guaranteeEndDate']);
    	//dump($data);exit;
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
    
    public function _after_insert($id,$data){
    	$repor_id=$data['report_id'];
    	M('report')->where(array('id'=>$repor_id))->setField('status',3);//更新报告为成功推荐
    	$project_id=I('project_id');
    	//更新项目是否为已完成
    	$position_nums=I('position_num');
    	$offer_nums=I('offer_nums');
    	if(($offer_nums+1)==$position_nums&&$project_id){
    		M('project')->where(array('id'=>$project_id))->setField('prtaFunnel',-1);
    	}
    }
   public function attachment(){
    	if(IS_AJAX){
    		if($_FILES){
    			$date_dir = date('ym/d/'); //上传目录
    			$result = $this->_upload($_FILES['img'], 'offer/'.$date_dir);
    			
    			$result['error']&&$this->ajax_return(0, $result['info']);
    			$result&&$this->ajax_return(1, '上传成功',$date_dir.$result['info'][0]['savename']);
    		}
    	}
    	
    }
   	
   	
}