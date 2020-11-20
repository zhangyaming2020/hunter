<?php
namespace Admin\Controller;
class MemberController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Member');
		$this->set_mod('Member');
		 $this->_cate_mod = D('MemberCate');
    }
    public function _before_index(){
        $this->list_relation = true;
		
        //显示模式
        $sm = I('sm', '','trim');
        $this->assign('sm', $sm);

        //分类信息
        $res = $this->_cate_mod->field('id,name,pid')->select();
        $cate_list = array();
        foreach ($res as $val) {
            $cate_list[$val['id']] = $val['name'];
			$big_cate2[$val['id']] = $this->_cate_mod -> where(array('id'=>$val['pid'])) ->getField('name');
        }
//		print_r($big_cate2);
        $this->assign('cate_list', $cate_list);
		$this->assign('big_cate2', $big_cate2);
//	print_r($res);
        
    
    }
//  protected function _search() {
//      $map = array();
//      if( $keyword = I('keyword','', 'trim') ){
//          $map['_string'] = "nickname like '%".$keyword."%' OR mobile like '%".$keyword."%'";
//      }
//		
//		($member_type = I('member_type',0, 'intval')) && $map['member_type'] = array('eq',$member_type);
//      $this->assign('search', array(
//          'keyword' => $keyword,
//			'member_type' => $member_type,
//      ));
//      return $map;
//  }
	
	 protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['reg_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['reg_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($status = I('status','', 'trim')) && $map['status'] = $status;
        ($keyword = I('keyword','', 'trim')) && $map['mobile'] = array('like', '%'.$keyword.'%');
        $cate_id = I('cate_id','', 'intval');
        $selected_ids = '';
        if ($cate_id) {
            $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);
            $map['cate_id'] = array('IN', $id_arr);
            $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');
            $selected_ids = $spid ? $spid . $cate_id : $cate_id;
        }
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'cate_id' => $cate_id,
            'selected_ids' => $selected_ids,
            'status'  => $status,
            'keyword' => $keyword,
        ));
        return $map;
    }
	

    public function _before_insert($data) {
        if( ($data['password']!='')&&(trim($data['password'])!='') ){
            $data['password'] = $data['password'];
        }else{
            unset($data['password']);
        }
        $birthday = I('birthday','', 'trim');
        if ($birthday) {
            $birthday = explode('-', $birthday);
            $data['byear'] = $birthday[0];
            $data['bmonth'] = $birthday[1];
            $data['bday'] = $birthday[2];
        }
        return $data;
    }

    public function _after_insert($id) {
        $img = I('img','','trim');
        $this->user_thumb($id,$img);
    }

    public function edit()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST) {
            $data = I('post.');

            if (false === $data = $mod->create($data)) {
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (false !== $mod->save($data)) {
                if( method_exists($this, '_after_update')){
                    $id = $data['id'];
                    $this->_after_update($id);
                }
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
        	//分类
            $spid = $this->_cate_mod->where(array('id'=>$item['cate_id']))->getField('spid');
            if( $spid==0 ){
                $spid = $item['cate_id'];
            }else{
                $spid .= $item['cate_id'];
            }
            $this->assign('selected_ids',$spid); //分类选中
//          $tag_cache = unserialize($item['tag_cache']);
//          $item['tags'] = implode(' ', $tag_cache);
//          $this->assign('info', $item);
			
            $id = I($pk, 'intval');
            $this->_relation && $mod->relation(true);
            $info = $mod->find($id);
//			print_r($info);
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


    public function _after_update($id){
        $img = I('img','','trim');
        if($img){
            $this->user_thumb($id,$img);
        }
    }

    public function identity(){
        if(IS_POST){
            $identity_id = I('identity_id',0,'intval');
            $status = I('status',0,'intval');
            $id = I('id',0,'intval');
            D('Identity')->where(array('id'=>$identity_id))->setField(array('status'=>$status));
            if (IS_AJAX) {
                $this->ajax_return(1,'操作成功','','identity');
            } else {
                $this->success('操作成功');
            }
        }else{
            $id = I('id',0,'intval');
            $member = $this->_mod->relation('identity')->find($id);
            $this->assign('info',$member);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'add');
            } else {
                $this->display();
            }
        }
    }

    public function identity_list(){
        $mod = D('Identity');
        //$list = D('Identity')->where(array('status'=>0))->relation('member')->select();
        $this->list_relation = true;
        $this->_list($mod, array('status'=>2));
        $this->display('index');
    }

    public function user_thumb($id,$img){
        $img_path= avatar_dir($id);
        //会员头像规格
        $avatar_size = explode(',', C('pin_avatar_size'));
        $paths =C('pin_attach_path');

        foreach ($avatar_size as $size) {
            if($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg'){
                @unlink($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg');
            }
            !is_dir($paths.'avatar/'.$img_path) && mkdir($paths.'avatar/'.$img_path, 0777, true);
            Image::thumb($paths.'avatar/temp/'.$img, $paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg', '', $size, $size, true);
        }

        @unlink($paths.'avatar/temp/'.$img);
    }

    public function add_users(){
        if (IS_POST) {
            $users = I('username','', 'trim');
            $users = explode(',', $users);
            $password = I('password','', 'trim');
            $gender = I('gender',0, 'intavl');
            $reg_time= time();
            $data=array();
            foreach($users as $val){
                $data['password']=$password;
                $data['gender']=$gender;
                $data['reg_time']=$reg_time;
                if($gender==3){
                    $data['gender']=rand(0,1);
                }
                $data['username']=$val;
                $this->_mod->create($data);
                $this->_mod->add();
            }
            $this->success(L('operation_success'));
        } else {
            $this->display();
        }
    }

	public function add_user(){
			$city = D('place') -> where(array('type'=>2)) -> order('ordid ,letter') -> select();
			$this -> assign('city',$city);
        if (IS_POST) {
        	 $data['realname']=  $realname = I('realname','', 'trim');
             $data['mobile']= $mobile = I('mobile',0, 'intval');
             $data['password']= $password = I('password','', 'trim');
			 $data['city_id']= $city_id = I('city_id','', 'trim');
			 $data['reg_ip']= 888888;
             $data['reg_time']=$reg_time = time();
			 if($mobile&&$password&&$city_id){
			 	 $this->_mod->add($data);
            $this->success(L('operation_success'));
			 }else{
			$this->error('请把资料填写完整'); 	
			 }
			 
//              $this->_mod->create($data);
//              $this->_mod->add();
        } else {
            $this->display();
        }
    }

    public function ajax_upload_imgs() {
        $file_name = 'avatar';
        $member_id = I('get.member_id',0,'intval');
        if (!empty($_FILES[$file_name]['name'])) {
            $result = $this->_upload($_FILES[$file_name], 'avatar/',  array('width'=>100, 'height'=>100,'remove_origin'=>true, 'remove_origin'=>true),$member_id);
            if ($result['error']) {
                $this->ajax_return(0, L('illegal_parameters'));
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $result['info'][0]['savename'].'?t='.time();
                $data['thumb_img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                $this->ajax_return(1, L('operation_success'), $data['thumb_img'].'?t='.time());
            }
        }

    }

    /**
     * ajax检测身份证是否存在
     */
    public function ajax_check_id_card() {

        $id_card = I('J_id_card','', 'trim');
        $id = I('id',0, 'intval');
        $mod = D('Identity');
		$result = $mod->where(array('id_card'=>$id_card,'id'=>array('neq',$id)))->find();
        if ($result) {
            $this->ajax_return(0, '该身份证号已经存在');
        } else {
            $this->ajax_return();
        }
    }

    /**
     * ajax检测会员是否存在
     */
    public function ajax_check_name() {
        $name = $this->_get('username', 'trim');
        $id = $this->_get('id', 'intval');
        if ($this->_mod->name_exists($name,  $id)) {
            $this->ajax_return(0, '该会员已经存在');
        } else {
            $this->ajax_return();
        }
    }

    /**
     * ajax检测邮箱是否存在
     */
    public function ajax_check_val() {
        $key = I('clientid','','trim');
        $val = I($key,'','trim');
        $id = I('id',0, 'intval');
        $result = $this->_mod->where(array($key=>$val,'id'=>array('neq',$id)))->getField('id');
        if ($result) {
            $this->ajax_return(0, '该邮箱已经存在');
        } else {
            $this->ajax_return();
        }
    }

}