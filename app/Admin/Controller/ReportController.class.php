<?php
namespace Admin\Controller;
use Admin\Org\Image;
use Admin\Org\Tree;
header("Content-type:text/html;charset=utf-8");
class ReportController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Report');
        $this->set_mod('Report');
        $this->status=$status=array('待推荐','待面试','面试中','成功推荐','不予推荐','推荐失败');
        $this->status_color=array('#808080','#0080C0','#FF8000','#008000','#ff0000','#ff0000');
       
      
        $this->page_size =I('page_size')?I('page_size'):20;
    }

    public function _before_index() {
        $this->list_relation=true;
        $project_id=I('project_id');
          if(isset($_GET['project_id'])){//项目下的推荐报告
				$map['project_id'] = $project_id;
        }
        else{//默认为推荐人的报告列表
        	$_SESSION['admin']['id']!=1&&$map['recommend_id']=$_SESSION['admin']['id'];//
        } 
        $status_nums=array();
        $all=0;
        //dump($map);
		$status_nums=M('report')->where($map)->group('status')->field("count(*) as nums,status")->select();
		foreach($status_nums as $k=>$v){
			$all+=$v['nums'];
			$nums[$v['status']]=$v['nums'];
		}
		$this->assign('status',$this->status);
		$this->assign('nums',$nums);
		$this->assign('all',$all);
		//dump($status_nums);
        $this->assign('status_color',$this->status_color);
    }

	public function detail(){
        $id=I('id');
        $custom_id=I('custom_id');
        $project_id=I('project_id');
        //$custom_id&&$contact=M('linkman')->where(array('customer_id'=>$custom_id))->order('add_time desc')->select();
        //客户联系人  报告中为hr
        $contact_id=M('project')->where(array('id'=>$project_id))->getField('prtaContactIds');
        $contact_id&&$contact_info=M('linkman')->where(array('id'=>$contact_id))->field('cuconame,cucoemail')->find();
        
        //报告详情
        $info=D('Report')->where(array('id'=>$id))->relation(true)->find();
        //dump($info);
        //是否有合同
        $nums=M('contract')->where(array('custom_id'=>$info['custom_id'],'status'=>1))->count();
        
        //附件
    	$attach=M('report_attachment')->alias('r')->where(array('report_id'=>$id))
    	->join("left join __ADMIN__ a on r.creator_id=a.id")
    	->field('r.*,a.username')->order('r.add_time desc')->select();
    	
    	//面试安排
    	$interviw_result=array('待面试','面试成功','面试失败');
    	//$this->assign('contact',$contact);
    	$this->assign('contact_info',$contact_info);
    	$this->assign('attach',$attach);
        $this->assign('info',$info);
        $this->assign('interviw_result',$interviw_result);
        $this->assign('nums',$nums);//客户是否有有效的合同
        $this->assign('status',$this->status);//客户是否有有效的合同
        //dump($info);exit;
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'report_detail');
        } else {
            $this->display();
        }
    }
    
    protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($status = I('status','', 'trim')) && $map['status'] = $status-1;
        
        if($_GET['status']<0){
        	unset($map['status']);
        }
        $keyword = I('keyword','', 'trim');
         $cate_id = I('cate_id','', 'intval');
         if($keyword){
         	$map['_string']="project_id in(select id from jrkj_project where prtaName like '%".$keyword."%') or custom_id in(select id from jrkj_custom where cutaName like '%".$keyword."%') or resume_id in(select id from jrkj_resume where resuName like '%".$keyword."%')";
         }
        $selected_ids = '';
        if ($cate_id) {
            $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');
            $selected_ids = $spid ? $spid . $cate_id : $cate_id;
        }
        $project_id = I('project_id','','intval');
        if(isset($_GET['project_id'])){//项目下的推荐报告
				$map['project_id'] = $project_id;
        }
        else{//默认为推荐人的报告列表
        	$_SESSION['admin']['id']!=1&&$map['recommend_id']=$_SESSION['admin']['id'];//
        }
        
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'project_id' => $project_id,
            'selected_ids' => $selected_ids,
            'status'  => $status-1,
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function _before_add()
    {
        $project_id = I('project_id','','intval');
        $project_id&&$custom_id=M('project')->where(array('id'=>$project_id))->getField('custom_id');
        $resume=D('Candidate')->where(array('project_id'=>$project_id))
        ->relation(true)->select();
        $this->assign('resume',$resume);
        $this->assign('project_id',$project_id);
        $this->assign('custom_id',$custom_id);
    }
    
    public function attachment(){
    	if(IS_AJAX){
    		if($_FILES){
    			$date_dir = date('ym/d/'); //上传目录
    			$result = $this->_upload($_FILES['img'], 'report/'.$date_dir);
    			
    			$result['error']&&$this->ajax_return(0, $result['info']);
    			$result&&$this->ajax_return(1, '上传成功',$date_dir.$result['info'][0]['savename']);
    		}
    	}
    	
    }
    
    public function attachment_upload(){
    	if(IS_AJAX){
    		if($_FILES){
    			$filename =iconv('UTF-8', 'GB2312', $_FILES['img']['name']); 
    			$res=move_uploaded_file($_FILES["img"]["tmp_name"],"./uploads/attachment/report/" .iconv('UTF-8', 'GB2312', $_FILES['img']['name']));//如何保存成功，则进行下一步
    			
    			!$res&&$this->ajax_return(0, '失败');
    			if($res){
    				$data=array(
    				'accessoryFile'=>iconv("GB2312","UTF-8",$filename),
    				'report_id'=>$_POST['report_id'],
    				'creator_id'=>$_SESSION['admin']['id'],
    				'add_time'=>time()
    				);
    				$result=M('report_attachment')->add($data);
    				if(!$result){
    					@unlink('./uploads/attachment/report/'.$data['accessoryFile']);
    					$this->ajax_return(0, '上传失败');
    				}
    				else{
    					$this->ajax_return(1,'上传成功');
    				}
    			}
    			else{
    				$this->ajax_return(0, '上传失败');
    			}
    		}
    	}
    	
    }
	public function attach_record(){
    	$report_id=I('report_id');
    	$list=M('report_attachment')->alias('m')->where(array('report_id'=>$report_id))
    	->join("__ADMIN__ a on a.id=m.creator_id")
    	->field("m.*,a.nickname as username")
    	->order('add_time desc')->select();
        $this->assign('list',$list);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'report_detail');
        } else {
            $this->display();
        }
    	
    }
   public function _before_edit()
    {
    	$this->_relation=true;
       $this->_before_add();
    }

    public function _after_insert($id){
        $data=array(
        	'accessoryFile'=>I('accessoryFile'),
        	'add_time'=>time(),
        	'creator_id'=>$_SESSION['admin']['id'],
        	'report_id'=>$id
        );
        I('accessoryFile')&&$id&&M('report_attachment')->add($data);
        
    }
    
    public function _before_insert($data){
    	$data['recommend_id']=$_SESSION['admin']['id'];
       return $data;
    }

    protected function _before_update($data) {
        
        return $data;
    }

	protected function _after_update($id) {
        $data=array(
        	'accessoryFile'=>I('accessoryFile'),
        	'add_time'=>time(),
        	'creator_id'=>$_SESSION['admin']['id'],
        	'report_id'=>$id
        );
        
        //附件
        I('accessoryFile')&&$id&&I('accessoryFile')&&M('report_attachment')->add($data);
    	
    	//面试时间
    	if(I('status')==2&&I('interDate')){
    		$da=array(
    		'interState'=>I('interState'),
        	'add_time'=>time(),
        	'creator_id'=>$_SESSION['admin']['id'],
        	'report_id'=>$id,
        	'interDate'=>strtotime(I('interDate')),
        	'intaContent'=>I('intaContent'),
    		);
    		
    		M('interview')->add($da);
    	}
    	
    	//推荐发送邮件
    	if(I('status')==1&&I('intaSendMail')){
    		$email=I('intaSendMail','','trim');
    		if(check_email($email)){
    			$id=$_SESSION['admin']['id'];
        		$user=M('admin')->where(array('id'=>$id))->field('email,smtp_pwd')->find();
    			think_send_mail($user,$email,'','推荐报告',I('intaContent'));
    		}
    	}
    }

    /**
     * ajax获取标签
     */
    public function ajax_gettags() {
        $title = I('title','', 'trim');
        if ($title) {
            $tags = D('Tag')->get_tags_by_title($title);
            $tags = implode(' ', $tags);
            $this->ajax_return(1, L('operation_success'), $tags);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }

    public function get_attach_data($data){
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d/');
            //删除原图
            $old_img = $this->_mod->where(array('id'=>$data['id']))->getField('img');
            $old_img = attach('$old_img','artcile');
            is_file($old_img) && @unlink($old_img);
            //上传新图
            $result = $this->_upload($_FILES['img'], 'article/' . $art_add_time, array('width'=>'130', 'height'=>'100', 'remove_origin'=>true));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time .'/'. str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        } else {
            unset($data['img']);
        }

        return $data;
    }

}


