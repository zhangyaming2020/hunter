<?php
namespace Admin\Controller;
class AdminController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Admin');
        $this->_cate_mod = D('AdminRole');
        $this->_depart_mod = D('Depart');
        $this->set_mod('Admin');
    }
	
/*	 public function index() {
	 	$admin = session('admin');
		if($admin['role_id'] != 1){
			$where = "role_id != 1";
		}
		
		$member =	$this->_mod->where($where)->relation(true)->select();
		$this->assign('list',$member);
		$this->assign('list_table', true);	  	
		$this ->display();
    }*/

    protected function _search() {
        $map = array();


        return $map;
    }

    public function _before_index() {
        $this->list_relation = true;
		/*
		//4. 设置图片保存路径
		$path = "./".date("Ymd",time());
		
		//5. 判断目录是否存在 不存在就创建 并赋予777权限
		if (!is_dir($path)){ //判断目录是否存在 不存在就创建
		 mkdir($path,0777,true);
		}
		
		//6. 拼接路径和图片名称
		$imageSrc= $path."/". $imageName;
		
		//7. 生成图片 返回的是字节数
		$r = file_put_contents($imageSrc, base64_decode($image));
		
		//8. 判断图片是否生成成功
		if (!$r) {
		 $tmparr1=array('data'=>null,"code"=>1,"msg"=>"图片生成失败");
		 echo json_encode($tmparr);
		}else{
		 $tmparr2=array('data'=>1,"code"=>0,"msg"=>"图片生成成功");
		 echo json_encode($tmparr2);
		}
        
        $md5_name='13e75a7cf71f1b3ce9ec66501f60e0c7';
       
        $res=$content=file_get_contents('./uploads/htm/'.$md5_name.'.html');
       preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU", $res,$matches);
       	//dump($matches[2]);
    	foreach($matches[2] as $k=>$v){
    		$v=preg_replace('/\+/','\+',$v);
    		$v=preg_replace('/\=/','\=',$v);
    		$v=preg_replace('/\:/','\:',$v);
    		$pattern[$k]='/'.preg_replace('/\//','\/',$v).'/';
    		$replace[$k]='./uploads/resume_img/token/C__13e75a7cf71f1b3ce9ec66501f60e0c7.docx_image'.($k+1).'.png';
    	}
    	*/
		//echo "preg_replace returns\n<pre/>";
		//print_r(preg_replace($pattern, $replace, $res)); 
    }
	public function  DeleteHtml($str) 
	{ 
	    $str = trim($str); //清除字符串两边的空格
	    $str = preg_replace("/\t/","",$str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
	    $str = preg_replace("/\r\n/","",$str); 
	    $str = preg_replace("/\r/","",$str); 
	    $str = preg_replace("/\n/","",$str); 
	    $str = preg_replace("/ /","",$str);
	    $str = preg_replace("/  /","",$str);  //匹配html中的空格
	    return trim($str); //返回字符串
	}
    public function _before_add() {
        $pid = I('pid',$_SESSION['admin']['role_id'] == 1 ? 0 : $_SESSION['admin']['role_id'],'intval');
        if ($pid) {
            $spid = $this->_cate_mod->where(array('id'=>$pid))->getField('spid');
            $spid = $spid ? $spid.$pid : $pid;
            $this->assign('spid', $spid);
        }
        $this->assign('store_list', $store_list);
        $this->assign('id_all', json_encode($id_all));
    }
	
    public function _before_insert($data) {
        $data['password'] = md5($data['password']);
        ($data['role_id'] == $_SESSION['admin']['role_id'] && $_SESSION['admin']['role_id'] != 1) && $this->ajax_return(0, '请选择角色');

        return $data;
    }

    public function _before_edit() {
        $spid_list = $this->_cate_mod->getField('id,spid');
        if($dep_id=I('depart_id')){
        	$dpid_list = $this->_depart_mod->where(array('id'=>$dep_id))->getField('spid');
        	$dpid_list.=$dep_id;
        }
		//dump($dpid_list);
        $this->assign('spid_list', $spid_list);
        $this->assign('dpid_list', $dpid_list);
        $this->assign('id_all', json_encode($id_all));
      
    }

    public function _before_update($data=''){
        if( ($data['password']=='')||(trim($data['password']=='')) ){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
        return $data;
    }

    public function ajax_check_name() {
        $name = I('username','', 'trim');
        $id = I('id','', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            echo 0;
        } else {
            echo 1;
        }
    }
   
}