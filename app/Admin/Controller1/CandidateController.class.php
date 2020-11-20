<?php
namespace Admin\Controller;
use Think\Page;
class CandidateController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
        $this->_mod = D('Candidate');
        $this->set_mod('Candidate');
 }



    public function _before_index() {
		$this->list_relation=true;
    	$arr=array();
    	foreach($str as $k=>$v){
    		$arr[$k]=array('name'=>$v,'pid'=>4,'spid'=>'4|');
    	}
    	$this->list_relation=true;
    	//dump(M('job')->addAll($arr));
    }

	protected function _search() {
		
		$map = array();

        //'status'=>1

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        ($price_min = I('price_min','',  'trim')) && $map['net_price'][] = array('egt', $price_min);

        ($project = I('project_id','',  'trim')) && $map['project_id'] = $project;
        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,

            'price_min' => $price_min,

            'price_max' => $price_max,

            'rates_max' => $rates_max,

            'status' =>$status,

            'selected_ids' => $spid,

            'cate_id' => $cate_id,

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
    	$this->display();
    }
    /**
     * 加入项目中
     */
    public function resume_project()
    {
    	
        $mod = D('Candidate');
        $resume_id = I('resume_id');
        $project_id = I('id');
        if ($resume_id&&$project_id) {
        	$username=$mod->alias('m')->where(array('m.resume_id'=>$resume_id,'m.project_id'=>$project_id))
        	->join('left join __ADMIN__ a on a.id=m.operate_id')
        	->getField('username');
        	if($username){
        		IS_AJAX && $this->ajax_return(0, '对不起，'.$username.'已经在本项目中推荐过此简历！');
        	}
        	$data=array(
        		'resume_id'=>$resume_id,
        		'project_id'=>$project_id,
        		'operate_id'=>$_SESSION['admin']['id'],
        		'add_time'=>time()
        		);
            if (false !== $mod->add($data)) {
                IS_AJAX && $this->ajax_return(1, '恭喜您,加入成功');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            IS_AJAX && $this->ajax_return(0, L('illegal_parameters'));
            $this->error(L('illegal_parameters'));
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
    	
        $place['province']= M('place')->where(array('pid'=>0))->field('id,pid,name,spid')->select();
       	
        $id=I('id');
        $this->assign('project_id', I('project_id'));
        $this->assign('id', $id);
        $this->assign('place', $place);
    }
    
    public function detail(){
        $id=I('id');
        $this->assign('resume_id',$id);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
        } else {
            $this->display();
        }
    }
    
    public function detail_info(){
        $id=I('resume_id');
        $admin=M('admin')->where(array('status'=>1))->field('username,id')->select();
    	$info=M('resume')->where(array('id'=>$id))->find();
        $this->assign('resume_id',$id);
        $this->assign('admin',$admin);
        $this->assign('info',$info);//dump($id);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
        } else {
            $this->display();
        }
    }
	/**
     * 获取紧接着的下一级分类ID
     */
    public function ajax_job_getchilds() {
        $id = I('id',0, 'intval');
        $type = I('type', 0, 'intval');
        $map = array('pid'=>$id);
        if (!empty($type)) {
            $map['type'] = $type;
        }
        $return = M('job')->field('id,name')->where($map)->select();
        if ($return) {
            $this->ajax_return(1, L('operation_success'), $return);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }
	/**
     * 获取紧接着的下一级分类ID
     */
    public function ajax_indus_getchilds() {
        $id = I('id',0, 'intval');
        $type = I('type', 0, 'intval');
        $map = array('pid'=>$id);
        if (!empty($type)) {
            $map['type'] = $type;
        }
        $return = M('industries')->field('id,name')->where($map)->select();
        if ($return) {
            $this->ajax_return(1, L('operation_success'), $return);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }
    public function _before_insert($data='') {
    	$data['owner_id']=$data['creator_id']=$_SESSION['admin']['id'];
        return $data;
    }
    
    public function _after_insert($id) {
    	$data['operate_id']=$_SESSION['admin']['id'];
        $data['resume_id']=$id;
        $data['project_id']=$project_id=I('project_id');
        $data['add_time']=time();
        if($project_id&&$id){
        	M('Candidate')->add($data);
        }
    }
    
    public function doc_import() {
    	if(IS_POST){
        	include "./doc.php";
        	$res=word_upload();
        	if ($res['type']==0) {
                IS_AJAX && $this->ajax_return(0, '暂不支持此类简历的导入');
            } 
        	if (!$res['mobile']) {
                IS_AJAX && $this->ajax_return(0, '解析失败,该简历没有可用的联系方式');
                $this->success(L('operation_success'));
            } 
            else{
            	$find=M('resume')->where(array('mobile'=>$res['mobile']))->count();
            	$find&&$this->ajax_return(0, '该简历已导入');
            }
//          else if(!$res['email']) {
//              IS_AJAX && $this->ajax_return(0, '解析失败,该简历没有可用的邮箱');
//              $this->error(L('operation_failure'));
//          }
            if($res['sex']=='女'){
            	$data['sex']=0;
            }
            if($res['sex']=='男'){
            	$data['sex']=1;
            }
    		$res['email']&&$data['resuEmail']=$res['email'];
    		$res['educations']&&$data['educations']=$res['educations'];
    		$data['resuContactInfo']=$res['mobile'];
    		$data['info']=$res['html'];
    		$data['resuName']='测试'.rand(0000,9999);
    		//dump($data);exit;
    		if(M('resume')->add($data)){
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'doc_import');
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
	            echo "非法访问";exit;
	        }
    	}
    }

    public function _before_edit() {
        $this->_before_add();
        $this->_relation=true;
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
   	
   	public function file_upload(){
   		$filename =$resuname= iconv('UTF-8', 'GB2312', $_FILES['file']['name']); 
		$key = $_POST['key'];
		$key2 = $_POST['key2'];
		if ($filename) {
        	$find=M('resume')->where(array('resuName'=>iconv("GB2312","UTF-8",$filename)))->getField('id');
        	if($find)
        	{
        	IS_AJAX && $this->ajax_return(2, '简历已存在',U('Resume/edit',array('id'=>$find)));
        	}
        	
        	IS_AJAX && $this->ajax_return(0, '操作失败,功能正在开发中……');
		   
		    $res=move_uploaded_file($_FILES["file"]["tmp_name"],"./uploads/resume/" . $filename);//如何保存成功，则进行下一步
			if($res){
				
				include "./doc.php";
	        	$res=word_upload("/uploads/resume/" . $filename);
	        	if (!$res['mobile']) {
	                IS_AJAX && $this->ajax_return(0, '解析失败,该简历没有可用的联系方式');
	            } 
	            else{
	            	//$find&&$this->ajax_return(0, '该简历已导入');
	            }
	//          else if(!$res['email']) {
	//              IS_AJAX && $this->ajax_return(0, '解析失败,该简历没有可用的邮箱');
	//              $this->error(L('operation_failure'));
	//          }
	            if($res['sex']=='女'){
	            	$data['sex']=0;
	            }
	            if($res['sex']=='男'){
	            	$data['sex']=1;
	            }
	    		$res['email']&&$data['resuEmail']=$res['email'];
	    		$res['educations']&&$data['educations']=$res['educations'];
	    		$data['resuContactInfo']=$res['mobile'];
	    		$data['info']=$res['html'];
	    		$data['add_time']=time();
	    		$data['owner_id']=$data['creator_id']=$_SESSION['admin']['id'];
	    		$data['resuName']=iconv("GB2312","UTF-8",$filename);
	    		if($res=M('resume')->add($data)){
	                IS_AJAX && $this->ajax_return(1, L('operation_success'), U('Resume/edit',array('id'=>$res)), 'doc_import');
	           } else {
	                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
					
	            }
			}
			
		}
		else{
				IS_AJAX && $this->ajax_return(0, '文件未识别');
		}
	}
}