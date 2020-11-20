<?php
namespace Admin\Controller;
use Think\Page;
class EmailController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
        $this->_mod = D('Project');
        $this->set_mod('Project');
        $this->assign('admin_id',$_SESSION['admin']['id']);
        $this->page_size =I('page_size')?I('page_size'):15;
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
		
        ($uname = I('uname','',  'trim')) && $map['uname'] = array('like', '%'.$uname.'%');
		
		($brand_id = I('brand_id','',  'trim')) && $map['brand_id'] = $brand_id;

		
        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,

            'price_min' => $price_min,

            'price_max' => $price_max,

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
   	
    public function _before_add() {
    }
    
    public function detail(){
        $id=I('id');
        $custom_id=I('custom_id');
        $custom_id&&$contact=M('linkman')->where(array('customer_id'=>$custom_id))->order('add_time desc')->select();
        $admin=M('admin')->where(array('status'=>1))->field('username,id')->select();
    	$info=D('Project')->where(array('id'=>$id))->relation(true)->find();
    	$info['linkman']=M('linkman')->where(array('customer_id'=>$info['custom_id']))->select();
    	$this->assign('contact',$contact);
    	$this->assign('admin',$admin);
        $this->assign('custom_id',$custom_id);
        $this->assign('project_id',$id);
        $this->assign('info',$info);	
        //dump($info);exit;
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'project_detail');
        } else {
            $this->display();
        }
    }
	
    public function _before_insert($data='') {
    	$data['owner_id']=$_SESSION['admin']['id'];
        return $data;
    }

   public function ajax_mail_send() {
        $email = I('email','', 'trim');
        !$email && $this->ajax_return(0);
        $title = I('title','', 'trim');
        !$title && $this->ajax_return(0);
        $info= I('info','', 'trim');
        $attach= I('attach','', 'trim');
        //发送
        $id=$_SESSION['admin']['id'];
        $user=M('admin')->where(array('id'=>$id))->field('email,smtp_pwd')->find();
       // dump($user);exit;
        $res=think_send_mail($user,$email,'章亚明',$title,$info,array($attach));
        if ($res) {
            $this->ajax_return(1);
        } else {
            $this->ajax_return(0);
        }
    }
    public function attachment(){
    	if(IS_AJAX){
    		if($_FILES){
    			$date_dir = date('ym/d/'); //上传目录
    			$result = $this->_upload($_FILES['img'], 'email/'.$date_dir);
    			
    			$result['error']&&$this->ajax_return(0, $result['info']);
    			$result&&$this->ajax_return(1, '上传成功','uploads/attachment/email/'.$date_dir.$result['info'][0]['savename']);
    		}
    	}
    	
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