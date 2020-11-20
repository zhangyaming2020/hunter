<?php
namespace Admin\Controller;
use Think\Page;
class ProjectController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
        $this->_mod = D('Project');
        $this->set_mod('Project');
        $this->assign('admin_id',$_SESSION['admin']['id']);
        $this->page_size =I('page_size')?I('page_size'):15;
 }
    public function _before_index() {
    	$this->list_relation=true;
    	$share_ids=$_SESSION['admin']['id'];
    	$type=$_GET['type'];
    	if(!$share_ids){
    		$this->redirect(U('index/login'));
    	}
    	if(isset($_GET['type'])){//我的项目和其他人的项目
        	switch($type){
				case 1:
				$field= "owner_id='".$share_ids."' or id in(select id from jrkj_project where is_share=1 and share_ids like '%".','.$share_ids."' or share_ids like '".$share_ids.",%' or share_ids like '%,".$share_ids.",%' or share_ids=".$share_ids.')';
		    	$map['_string']=$field;
				break;
				case 2:$map['owner_id'] =array('neq', $_SESSION['admin']['id']);break;
				default:$map['owner_id'] = $_SESSION['admin']['id'];break;
			}
     	}
    	$status=M('dic')->where(array('pid'=>60))->field('name,id')->select();
    	$status_nums=array();
    	foreach($status as $k=>$v){
    		$map['prtaFunnel']=$v['id'];
    		$status_nums[$v['id']]=M('project')->where($map)->group('prtaFunnel')->count();
    		$status_nums[$v['id']]=$status_nums[$v['id']]?$status_nums[$v['id']]:0;
    	}
    	unset($map['prtaFunnel']);
    	$status_nums[-2]=M('project')->where($map)->count();//全部
    	$map['prtaFunnel']=-1;
    	$status_nums[-1]=M('project')->where($map)->count();//已完成
    	unset($map['prtaFunnel']);
    	$map['prtaState']=1;
    	$status_nums[-4]=M('project')->where($map)->count();//启用中
    	$map['prtaState']=0;
    	$status_nums[-3]=M('project')->where($map)->count();//未启用
    	
    	//职位级别
    	$job_level=M('dic')->where(array('pid'=>62))->field('id,name')->select();
    	//职位类别
    	$job_cate=M('dic')->where(array('pid'=>61))->field('id,name')->select();
    	$this->assign('status',$status);
    	$this->assign('status_nums',$status_nums);
    	
    	$this->assign('job_level',$job_level);
    	$this->assign('job_cate',$job_cate);
    	$this->assign('search', array('type'=>$type));
    }


	protected function _search() {

        $map = array();

        //'status'=>1

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        ($prtatype = I('prtatype','',  'trim')) && $map['prtaType'] = $prtatype;

        ($prtalevel = I('prtalevel','',  'trim')) && $map['prtaLevel'] = $prtalevel;

        $keyword = I('keyword','',  'trim');
        
		
		($brand_id = I('brand_id','',  'trim')) && $map['brand_id'] = $brand_id;

		($custom_id = I('custom_id'));
		($resume_id = I('resume_id'));
		($is_tab = I('is_tab'));//是否经由简历点击而来
        $type = I('type');
        $is_add = I('is_add');
        $share_ids=$_SESSION['admin']['id'];
        if(isset($_GET['type'])){//我的项目和其他人的项目
        	switch($type){
				case 1:
				$field= "owner_id='".$share_ids."' or id in(select id from jrkj_project where is_share=1 and share_ids like '%".','.$share_ids."' or share_ids like '".$share_ids.",%' or share_ids like '%,".$share_ids.",%' or share_ids=".$share_ids.')';
		    	$map['_string']=$field;
				break;
				case 2:$map['owner_id'] =array('neq', $_SESSION['admin']['id']);break;
				default:$map['owner_id'] = $_SESSION['admin']['id'];break;
			}
        }
		if(isset($_GET['resume_id'])){//简历模块
        	if(isset($_GET['is_tab'])){//简历下的项目
        		$project_ids=M('candidate')->where(array('resume_id'=>$resume_id))
        		->order('add_time desc')->getField('project_id',true);
        		$project_ids&&$map['id']=array('in',$project_ids);
        		!$project_ids&&$map['id']=-999;
        	}
        	else{//简历下选择项目
        		$map['owner_id'] = $_SESSION['admin']['id'];
        	}
       }
        if($keyword){
        	//$map['_string'].=" and ( prtaName like '%职业顾问%' or prtaCode like '%职业顾问%' )";
        	$map['prtaName|prtaCode']=array('like','%'.$keyword.'%');
        }
        if(isset($_GET['custom_id'])){//客户下的项目
				$map['custom_id'] = $custom_id;
        }
        $status = I('get.prtaFunnel') ;
        
        if(!isset($_GET['prtaFunnel'])){//客户下的项目
				$map['prtaState']=1;
				$status =-4;
        }
        else if($status==-3){
        	$map['prtaState']=0;
        }
        else if($status==-1){
        	$map['prtaFunnel']=-1;
        }
        else if($status==-2){
        }
        else if($status==-4){
        	$map['prtaState']=1;
        }
        else{
        	$map['prtaFunnel']=$status;
        }
       // dump($status);
        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,

            'prtalevel' => $prtalevel,

            'prtatype' => $prtatype,

            'prtaState' => $map['prtaState'],

            'status' =>$status,

            'selected_ids' => $spid,

            'type' => $type,
            

            'keyword' => $keyword,

            'custom_id' =>$custom_id,
            
            'resume_id'=>$resume_id,
            
            'is_tab'=>$is_tab,
            
            'is_add'=>$is_add,
        ));
        //dump($map);
        return $map;

    }
    //
    public function ajax_list(){ 
    	$model=M('custom');
    	$map=array();
    	$share_ids=$_SESSION['admin']['id'];
		
    	($name = I('name','',  'trim')) && $map['cutaName'] = array('like', '%'.$name.'%');
    	$map['owner_id']=$_SESSION['admin']['id'];
    	$field= "type=2 and id in(select id from jrkj_custom where share_ids like '%".','.$share_ids."' or share_ids like '".$share_ids.",%' or share_ids like '%,".$share_ids.",%' or share_ids=".$share_ids.')';
    	$where['_string']=$field;
    	$where['_logic'] = 'or';
		$where['_complex'] = $map;
    	$count = $model->where($where)->count();
        $pager = new Page($count, 10);
        $select = $model->field('id,cutaName')->order('add_time desc')->where($where); 
		//dump($select->getLastSql());exit;
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
    	
        $dic= M('dic')->where(array('id'=>array('in',array('60','62','61'))))->field('id,field,pid,name,is_must')->select();
       	foreach($dic as $k=>$v){
       		$dic[$k]['sub']=M('dic')->where(array('pid'=>$v['id']))->field('id,field,pid,name,is_must')->select();
       	}
        $id=I('id');
        $custom_info=array(
        			'custom_id'=>I('custom_id'),
        			'cutaname'=>M('custom')->where(array('id'=>I('custom_id')))->getField('cutaname')
        			);
        $this->assign('id', $id);
        $this->assign('custom_info', $custom_info);
        
        $this->assign('dic', $dic);
    }
    
    public function detail(){
        $id=I('id');
        $custom_id=I('custom_id');
        $custom_id&&$contact=M('linkman')->where(array('customer_id'=>$custom_id))->order('add_time desc')->select();
        $admin=M('admin')->where(array('status'=>1))->field('username,id')->select();
    	$info=D('Project')->where(array('id'=>$id))->relation(true)->find();
    	//项目漏斗
    	$funnel=M('dic')->where(array('pid'=>60))->field('id,name')->select();
    
    	
    	$info['linkman']=M('linkman')->where(array('customer_id'=>$info['custom_id']))->select();
    	$this->assign('contact',$contact);
    	$this->assign('admin',$admin);
        $this->assign('custom_id',$custom_id);
        $this->assign('project_id',$id);
        $this->assign('info',$info);
        $this->assign('funnel',$funnel);
        $this->assign('tab_index',I('tab_index','0','intval'));	
        //dump($info);exit;
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'project_detail');
        } else {
            $this->display();
        }
    }
	//候选人
	public function candidate(){
		$mod=D('Candidate');
    	$map=array();
    	($name = I('name','',  'trim')) && $map['cutaName'] = array('like', '%'.$name.'%');
    	($project_id =I('project_id')) && $map['project_id'] =$project_id;
    	$this->list_relation=true;
    	$this->_list($mod, $map,'','','',8);
    	$educate=array('','初中','高中','专科','大专','本科','硕士','博士');
    	$this->assign('educate',$educate);
		if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'candidate');
        } else {
            $this->display();
        }
        
        
	}
    public function _before_insert($data='') {
    	$data['owner_id']=$_SESSION['admin']['id'];
        return $data;
    }

    public function _before_edit() {
        $this->_before_add();
        $this->_relation=true;
    }
	public function _after_edit($info){
	    $ids=explode(',',$info['prtacontactids']);
	    $info['cont_name']=implode(',',M('linkman')->where(array('id'=>array('in',$ids)))->getField('cuconame',true));
		return $info;
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
   	public function test(){
   		
       	$this->display();
   	}
   	
   	
}