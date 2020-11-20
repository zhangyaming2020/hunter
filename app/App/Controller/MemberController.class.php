<?php
namespace App\Controller;
use Think\Rc4;
use \Mobile\Org\WeiXinAbout;
//use Think\HxCommon;
class MemberController extends ApiController {

    //公共方法
    public function _initialize(){
        parent::_initialize();
        $this->_mod =$this->member    = D('Member');//用户表

        $this->place    =D('Place'); //开放地区表
		$this->member=D('member');
    }

//账号密码登录 && 微信登录  10001
    public function login($datas){
        $data = $this->get_datas($datas);
		
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        
        $app_id='wx9de48f5d78c22507';
    	$secret_id='b5036d1e107f729ef5560861352e90cd';
    	$code=$data['code']; 
		
		//dump($data);exit;
    	//cookie('code',$code.'-trighhjhi');
    	//dump(cookie('code'));exit;
		$nickname=$data['nickname'];
		$avatar=$data['avatar'];
		$sex=$data['sex'];
    	$open_id=$this->oauth2($code,$app_id,$secret_id);
		 
		(!$open_id) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '数据获取失败'));//手机号码格式错误
		$mem=M('member')->where(array('open_id'=>$open_id))->find();
		if(!$mem){//用户添加操作
		   $da=$mem=array(
		   'nickname'=>$data['nickname'],
		   'avatar'=>$data['avatar'],
		   'sex'=>$data['sex'],
		   'reg_time'=>time(),
		   'open_id'=>$open_id,
		   );
		  // dump($da);exit;
		   $res=M('member')->add($da);
		   $mem['id']=$res;
		}
		file_put_contents('text.html',json_encode($da).'454545'.'code:'.$code.'小程序请求时间:'.date('Y-m-d H:i').':open_id:'.$open_id);
	   
		if($mem){
			$rc4 = new Rc4();
			$C_userid_encryption_key = '#fkj664@';//C('userid_encryption_key');
			$mem['id'] = str_replace('+','|jia|',$rc4->authcode($mem['id'],'ENCODE',$C_userid_encryption_key));
			$this->json_Response('success',$datas['pack_no'],$mem);
		}
		else{
			$this->json_Response('failed',$datas['pack_no']);
		}
    }

	public function oauth2($code,$app_id,$secret_id)
    {
        //$code = $code;//小程序传来的code值
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $app_id . '&secret=' . $secret_id . '&js_code=' . $code . '&grant_type=authorization_code';
        //dump($url);exit;
		//yourAppid为开发者appid.appSecret为开发者的appsecret,都可以从微信公众平台获取；
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
// 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        $json_obj = json_decode($res, true);
        $openid = $json_obj["openid"];
       // $data['openid'] = $openid;
        //dump($data);exit;
		return $openid;
		
	}
		
    //分数保存接口    10000
    public function score($datas){
		$this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas); //从请求中获取数据
        ($data == NULL || count($data) <= 0) && $this->print_error_status('params_error', $datas['pack_no']);
		//dump($data);exit;
		(!$data['score']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '分数不能为空'));//性别不能为空
        //(!$data['review_nums']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '复活次数不能为空'));//性别不能为空
		$where['user_id']=$data['user_id'];
		//echo strtotime(date('Y-m-d'));exit;
		$where['date']=array('egt',strtotime(date('Y-m-d')));
	   $find=M('rank')->where($where)->getfield('score');
	   //dump(M()->getLastSql());exit;
	   if($find){
		   $res=1;
		   if($find<$data['score']){
			   $res=M('rank')->where(array('user_id'=>$data['user_id']))->save(array('score'=>$data['score'],'review_nums'=>$data['review_nums'])); 
		   }
		   //dump(M()->getLastSql());exit;
	   }
	   else{
		   $res=M('rank')->where(array('user'=>$data['user_id']))->add(
				array(
				'score'=>$data['score'],
				'review_nums'=>$data['review_nums'],
				'user_id'=>$data['user_id'],
				'date'=>time(),
				'add_time'=>time()
				)
		   ); 
	   }
			$res&&$this->json_Response('success',$datas['pack_no'],array('return'=>$return));
       
           
            $this->json_Response('failed',$datas['pack_no'],array('return'=>$return));
    }

    //个人中心首页信息展示   9999
    public function member_info($datas)
    {
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        $member_info = $this->member->field('nickname,avatar,ewm,vips,realname,sex,address,mobile')
            ->where(array('id'=>$data['user_id'],'status'=>1))->find();
        $this->assign('member_info',$member_info);
        $this->json_Response('success',$datas['pack_no'],array('member_info'=>$member_info));
    }

    //排名 10002
    public function rank_list($datas)
    {
		$this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas); //从请求中获取数据
        ($data == NULL || count($data) <= 0) && $this->print_error_status('params_error', $datas['pack_no']);
        $where['user_id']=$data['user_id'];
		$type=$data['type']?$data['type']:1;//类型 1是今天排名  2是历史排名
		
		$time=strtotime(date('Y-m-d'));
		if($type==1){
			$whe['r.date']=array('egt',$time);
			$select=M('rank')->where($whe)
			->alias('r')->join("left join __MEMBER__ m on m.id=r.user_id")
			->order('r.score desc')->limit(10)
			->field("r.*,m.avatar,m.sex,m.nickname")->select();//当天前10
			
			$whe['r.user_id']=$data['user_id'];	
			$own=M('rank')->where($whe)
			->alias('r')->join("left join __MEMBER__ m on m.id=r.user_id")
			->order('score desc')
			->field("r.*,m.avatar,m.sex,m.nickname")->find();//当天我的排名
		}
		else{
			
		}
		$this->json_Response('success',$datas['pack_no'],array('list'=>$select,'own'=>$own));
    }

    //修改登录密码     10004
    public function updatepwd($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data) <= 0) && $this->print_error_status('params_error', $datas['pack_no']);

        $type = $data['type'];
        $member = D('Member');
        $res = $member->where(array('id' => $data['user_id']))->find(); //获取会员信息   用于验证原密码是否正确

//        if ($type == 1) {       //修改登录密码
            //登录密码验证
            (!$oldpwd = check_pwd($data['oldpwd'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '原密码由大小写字母跟数字组成并且长度在6-16字符之间'));
            (!$newpwd = check_pwd($data['password'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '新密码由大小写字母跟数字组成并且长度在6-16字符之间'));
            (!$cnewpwd = check_pwd($data['newpassword'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '确认密码由大小写字母跟数字组成并且长度在6-16字符之间'));

            //检查两次新密码是否一致
            ($newpwd != $cnewpwd) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '两次密码输入不一致'));

            //检查原登录密码是否正确
            $oldpwd = st_md5($oldpwd);
            $newpwd = st_md5($newpwd);
            if ($oldpwd !== $res['password']) {
                $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '原密码输入错误'));
            }
            //开始事务
            $this->_mod->startTrans();
            //修改登录密码
            $res2 =  $this->_mod->where(array('id' => $data['user_id']))->setField('password', $newpwd); //修改密码
//            if ($res2) {
//                //进行环信修改密码
//                $result = $this->hx_common->resetPassword($uid,$newpwd);
//                if ($result) {
//                    $this->_mod->commit();
//                    $this->json_Response('success', $datas['pack_no']);
//                } else {
//                    $this->_mod->rollback();
//                    $this->json_Response('failed', $datas['pack_no']);
//                }
//            }
            if($res2){
                $this->_mod->commit();
                $this->json_Response('success', $datas['pack_no']);
            }else{
                $this->_mod->rollBack();
                $this->json_Response('failed', $datas['pack_no']);
            }
//        }
//        elseif ($type == 2) {     //修改支付密码
//            (!$oldpwd = check_paypwd($data['oldpwd'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '原支付密码由6位纯数字组成'));
//            (!$newpwd = check_paypwd($data['password'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '新支付密码由6位纯数字组成'));
//            (!$cnewpwd = check_paypwd($data['newpassword'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '确认支付密码由6位纯数字组成'));
//           //检查两次新密码是否一致
//            ($newpwd != $cnewpwd) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '两次密码输入不一致'));
//
//            //检查原支付密码是否正确
//            $oldpwd = st_md5($oldpwd);
//            $newpwd = st_md5($newpwd);
//            if ($oldpwd !== $res['paypassword']) {
//                $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '原支付密码输入错误'));
//            }
//            //开始事务
//            $this->_mod->startTrans();
//            //修改登录密码
//            $res2 =  $this->_mod->where(array('id' => $data['user_id']))->setField('paypassword', $newpwd); //修改密码
////            if ($res2) {
////                //进行环信修改密码
////                $result = $this->hx_common->resetPassword($uid,$newpwd);
////                if ($result) {
////                    $this->_mod->commit();
////                    $this->json_Response('success', $datas['pack_no']);
////                } else {
////                    $this->_mod->rollback();
////                    $this->json_Response('failed', $datas['pack_no']);
////                }
////            }
//            if($res2){
//                $this->_mod->commit();
//                $this->json_Response('success', $datas['pack_no']);
//            }else{
//                $this->_mod->rollBack();
//                $this->json_Response('failed', $datas['pack_no']);
//            }
//        }
    }

    //设置支付密码    10007
//    public function set_pwd($datas){
//        $this->check_user_id($datas);
//        $data = $this->get_datas($datas);
//        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
//        //查找会员记录
//        $set = M('Member')->where(array("id"=>$data['user_id']))->find();
//        (!check_mobile($data['mobile'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '手机号码格式错误'));//手机号码格式错误
//        (!$code = $data['code']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '请填写验证码'));//请填写验证码
//        (!$code = check_code($data['code'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '验证码错误'));//验证码错误
//        (!$newpwd = check_paypwd($data['paypassword'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '支付密码由6位纯数字组成'));
//        (!$cnewpwd = check_paypwd($data['pypassword'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '确认支付密码由6位纯数字组成'));
//        //检查两次新密码是否一致
//        ($newpwd != $cnewpwd) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Password_Not_Consistent' => '两次密码输入不一致'));
//        if($set){   //设置支付密码
//            $paypassword = st_md5($newpwd);
//            $this->_mod->startTrans();
//            $res = $this->_mod->where(array("id"=>$data['user_id']))->setField("paypassword",$paypassword);
//            if($res){
//                $this->_mod->commit();
//                $this->json_Response('success',$datas['pack_no']);
//            }else{
//                $this->_mod->rollBack();
//                $this->json_Response('failed',$datas['pack_no']);
//            }
//        }else{
//            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '该会员账号不存在'));
//        }
//    }
    //忘记支付密码
//    public function forget_pay_pwd($datas){
//        $this->check_user_id($datas);
//        $data = $this->get_datas($datas);
//        $uid = $datas['user_id'];
//        (!$mobile = check_mobile($data['mobile']))&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile'));//手机号码格式错误
//        (!$code = $data['code']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'code'));//请填写验证码
//        (!$pay_pwd = check_paypwd($data['pay_pwd'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'pay_pwd'));
//        (!$repay_pwd = check_paypwd($data['repay_pwd'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'repay_pwd'));
//        $st_pay_pwd= st_md5($pay_pwd);
//        $yz_code=$this->sms->where(array('mobile'=>$mobile))->order('create_time desc')->field('code')->select();
//        $yz_code1=$yz_code[0]['code'];
//        if($code!==$yz_code1){
//            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'code_err'));//验证码错误
//        }
//        $old_pay_pwd=$this->_mod->where(array('id'=>$uid))->getField('pay_pwd');
//        if($st_pay_pwd==$old_pay_pwd){
//            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'old_pay_pwd'));//不能与原来的支付密码一致
//        }
//        //检查两次新密码是否一致
//        ($pay_pwd != $repay_pwd) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'error_repay_pwd'));
//        $res=$this->_mod->where(array('id'=>$uid))->setField('pay_pwd',$st_pay_pwd); //设置支付密码
//        if($res){
//            $this->json_Response('success',$datas['pack_no']);
//        }else{
//            $this->json_Response('failed',$datas['pack_no']);
//        }
//    }

    //展示收货地址
//    public function location($datas)
//    {
//        $this->check_user_id($datas);//验证用户ID是否存在
//        $data = $this->get_datas($datas);
//        $address_info=$this->address->where(["member_id"=>$data['user_id']])->field('id,shperson,mobile,province,city,county,address')->select();
//        if($data['status']){
//            $id = $data['id'];
//            $status = $data["status"];
//            $member_address=$this->address;
//            //开始事务
//            $member_address->startTrans();
//            if($status){
//                $this->address->where(["member_id"=>$data['user_id']])->setField("status",2);
//                $address =$member_address->where(['id'=>$id])->setField('status',1);
//            }
////        else{      //返回收货地址信息
////            $this->json_Response('success',$datas['pack_no'],array("address_info"=>$address_info));
////        }
//            if($address){
//                //提交事务
//                $member_address->commit();
//                $this->json_Response('success',$datas['pack_no'],array("address_info"=>$address_info));
//            }else{
//                //事务回滚
//                $member_address->rollback();
//                $this->json_Response('failed',$datas['pack_no']);
//            }
//        }
//
//    }

    //展示收货地址    10008
    public function location($datas)
    {
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        $address_info=$this->address->where(["uid"=>$data['user_id']])->field('id,shperson,mobile,province,city,district,address')->select();
        if($address_info){
            //提交事务
            $this->json_Response('success',$datas['pack_no'],array("address_info"=>$address_info));
        }else{
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //设为默认地址    10014
    public function  moren($datas){
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        $uid = $data['user_id'];
        $id = $data['id'];
        if(empty($uid)){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请先登录'));
        }

        $address = $this->address->where(array('id'=>$id,'uid'=>$uid))->find();
        (!$address['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '数据异常'));
        if($address['status']==1){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '该地址已经是默认收货地址'));
        }
        $this->address->startTrans();
        $a = $this->address->where(array('status'=>array('eq','1'),'uid'=>$uid))->setField('status','0');
        $b = $this->address->where(array('id'=>$id,'uid'=>$uid))->setField('status','1');
        if($b){
            $this->address->commit();
            $this->json_Response('success',$datas['pack_no']);
        }else{
            $this->address->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }


    //添加&&修改收货地址    10009
    public function add_location($datas)
    {
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);

        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        (!$arr['shperson'] = $data['shperson']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '收货人不能为空')); //收货人
        (!$arr['mobile'] = check_mobile($data['mobile'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '手机号码格式不正确')); //手机号码
        (!$arr['province'] = $data['province']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '收货地址所在省不能为空')); //收货地址所在省
        (!$arr['city'] = $data['city']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '收货地址所在市不能为空')); //收货地址所在市
        (!$arr['address'] = $data['address']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '详细地址不能为空')); //详细地址

        $id = $data['id'];
        if($id){    //id存在说明是修改操作
            $arr['addtime'] = time();
            if($data['districty']) $arr['county'] = $data['county'];
            $arr['status'] = $data['status'] ? 1 : 2;
            $member_address=$this->address;
            //开始事务
            $member_address->startTrans();

            $address =$member_address->where("id='$id'")->save($arr);

            if($address){
                //提交事务
                $member_address->commit();
                $this->json_Response('success',$datas['pack_no']);
            }else{
                //事务回滚
                $member_address->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
        }else{      //id不存在说明是添加操作
            $arr['member_id'] = $data['user_id'];
            $arr['addtime'] = time();
            if($data['county']){
                $arr['county'] = $data['county'];
            }
            if(false !== $data['status']){
                $arr['status'] = 1;
            }else{
                $arr['status'] = 2;
            }
            $member_address=$this->address;
            //开始事务
            $member_address->startTrans();

            $address =$member_address->add($arr);

            if($address){
                //提交事务
                $member_address->commit();
                $this->json_Response('success',$datas['pack_no']);
            }else{
                //事务回滚
                $member_address->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
        }
    }

    //删除收货地址    10010
    public function delete_address($datas)
    {
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);

        $this->address->startTrans();
        $address = $this->address->where('member_id='.$data['user_id'].' and '.'id='.$data['id'])->delete();
        if($address){
            $this->address->commit();
            $this->json_Response('success',$datas['pack_no']);
        }
        else{
            $this->address->rollBack();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //实名认证      10011
    public function attestation($datas)
    {
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        (!$arr['realname'] = check_realname($data['realname'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '真实姓名必须是中文且符合命名常识')); //真实姓名
        (!$arr['id_nums'] = check_idcode($data['id_nums'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '身份证号格式不正确')); //身份证号
        (!$arr['province_id'] = $data['province_id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '所在省不能为空'));
        (!$arr['city_id'] = $data['city_id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '所在市不能为空'));
//        (!$data['id_nums_zheng']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '身份证正面照不能为空')); //身份证正面照
//        (!$data['id_nums_fan']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '身份证反面照不能为空')); //身份证反面照

////        //上传正面图片
//        $date_dir = date('ym/d/'); //上传目录
//        $result = $this->_upload($_FILES['id_nums_zheng'], 'member/'.$date_dir, array(
//            'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'),
//            'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
//            'suffix' => '_b,_m,_s',
//        ));
////            dump($result);die;
//        if ($result['error']) {
//            $this->error($result['info']);
//        } else {
//            //获取正面照
//            $arr['id_nums_zheng'] = $date_dir . $result['info'][0]['savename'];
//        }
//        //上传反面图片
//        $date_dir = date('ym/d/'); //上传目录
//        $result = $this->_upload($_FILES['id_nums_fan'], 'member/'.$date_dir, array(
//            'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'),
//            'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
//            'suffix' => '_b,_m,_s',
//        ));
////            dump($result);die;
//        if ($result['error']) {
//            $this->error($result['info']);
//        } else {
//            //获取反面照
//            $arr['id_nums_fan'] = $date_dir . $result['info'][0]['savename'];
//        }

        $arr['id_nums_zheng'] = $this->upload_file('img','id_card');
        $arr['id_nums_fan'] = $this->upload_file('img1','id_card');
        //开始事务
        $member=$this->member;
        $member->startTrans();
        //所在地区
        if($data['district_id'])     $arr['district_id'] = $data['district_id'];
        $res =$member->where(["id"=>$data['user_id']])->save($arr);

        if($res){
            //提交事务
            $member->commit();
            $this->json_Response('success',$datas['pack_no']);
        }else{
            //事务回滚
            $member->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //意见反馈    10012
    public function opinion($datas)
    {
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<= 0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid = $data['user_id'];
        (!$arr['realname'] = ($data['realname'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '真实姓名必须是中文且符合命名常识')); //真实姓名
    }

    //关于我们  10013
    public function about($datas){
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        $about = M('Article')->where(array('id'=>167))->field('id,title,intro')->find();//title分行名
        $this->assign('about',$about);
        $this->json_Response('success',$datas['pack_no'],array('about'=>$about));

    }

    //搜索商品    10015
    public function search($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<= 0) && $this->print_error_status('params_error',$datas['pack_no']);
        (!$arr['title'] = $data['title']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '商品名称不能为空'));
        if(is_numeric($arr['title'])){
            $ar['cate_id'] = $arr['title'];
            $ar['status'] = 1;
            $data['pagesize'] = 8;
            $data['page'] = 3;
            $count =$this->Item->where($ar)->count();
            $Page = new \Think\Page($count,$data['page'],$data['pagesize']);
            $list= $this->Item->where($ar)->order('id desc,add_time desc')->field('id,price,title,img,sales')->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{
            $arr['title'] = array('like','%'.$arr['title'].'%');
            $arr['status'] = 1;
            $data['pagesize'] = 8;
            $data['page'] = 3;
            $count =$this->Item->where($arr)->count();
            $Page = new \Think\Page($count,$data['page'],$data['pagesize']);
            $list= $this->Item->where($arr)->order('id desc,add_time desc')->field('id,price,title,img,sales')->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        if($list){
            $this->json_Response('success',$datas['pack_no'],array('list'=>$list));
        }
    }



    //选择设置还是修改
    public function changepwd($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid = $data['user_id'];
        $member=D('Member');
        $res =  $member->check($uid);
        if($res){
            $this->json_Response('success',$datas['pack_no'],array('type' => 1));//已有密码
        }else{
            $this->json_Response('success',$datas['pack_no'],array('type' => 0));//没有密码
        }

    }

    //忘记登录密码 && 设置支付密码 && 忘记支付密码    10005
  public function forgetpwd($datas){
		$data = $this->get_datas($datas);
        $type = $data['type'];
	    ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
		(!$mobile = check_mobile($data['mobile'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '手机号码格式不正确'));
		(!$code = $data['code']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '验证码不能为空'));

      (!$code = check_code($data['code'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '验证码错误'));//验证码错误
      if($type == 1){   //忘记登录密码
          //检查登录密码是否符合规格
          (!$password = check_pwd($data['password'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '新密码由大小写字母跟数字组成并且长度在6-16字符之间'));
          (!$cnewpwd = check_pwd($data['newpassword'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '确认密码由大小写字母跟数字组成并且长度在6-16字符之间'));
          //检查两次新密码是否一致
          ($password != $cnewpwd) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Password_Not_Consistent' => '两次密码输入不一致'));
          $this->member->startTrans();
          $res =  D('Member')->where(array('mobile'=>$mobile))->save(array('password'=>st_md5($password)));
          if($res){
              $this->member->commit();
              $this->json_Response('success',$datas['pack_no']);
          }else{
              $this->member->rollBack();
              $this->json_Response('faild',$datas['pack_no']);
          }
      }elseif ($type == 2){
          //检查支付密码是否符合规格
          (!$password = check_paypwd($data['password'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '支付密码由6位纯数字组成'));
          (!$cnewpwd = check_paypwd($data['newpassword'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '确认支付密码由6位纯数字组成'));
          //检查两次新密码是否一致
          ($password != $cnewpwd) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Password_Not_Consistent' => '两次密码输入不一致'));
          $this->member->startTrans();
          $res =  D('Member')->where(array('mobile'=>$mobile))->save(array('paypassword'=>st_md5($password)));
          if($res){
              $this->member->commit();
              $this->json_Response('success',$datas['pack_no']);
          }else{
              $this->member->rollBack();
              $this->json_Response('faild',$datas['pack_no']);
          }
      }

	}

    //添加收藏
    public function add_collection($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        dump($data);die;
        $uid = $datas['user_id']; //用户id
//		 $list =  D('Member')->get_shouye($uid);
//		 if($list['type'] == 1){
//		     $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'type')); //商家不可以收藏
//		 }
        $arr = array();
        $arr['uid'] = $uid;//收藏人
        $arr['type'] = $data['type'];
        $type=$data['type'];
        //验证是否已经收藏
        $collection = D('Collection');
//        if($type==1){
//            $arr['pid'] = $data['id']; //店铺id
//            $check_collection = $collection->check_collection($uid,$arr['pid']);
//        }elseif($type==2){
//            $arr['item_id'] = $data['id']; //店铺id
//            $check_collection = $collection->check_itemcollection($uid,$arr['item_id']);
//        }
        if(!$check_collection){
            $res = $collection->add_collection($arr);
            if($res){
                $this->json_Response('success',$datas['pack_no']);
            }else{
                $this->json_Response('failed',$datas['pack_no']);
            }
        }else{
            $this->json_Response('failed',$datas['pack_no'],array('ERROR_Param_Format' => '请勿重复收藏'));//已经收藏
        }
    }

    //获取商品评价信息



    public function get_evaluate($datas){







        //$this->check_user_id($datas);



        $data = $this->get_datas($datas);//dump($data);exit;



        $uid = $datas['user_id'];



        $page = $data['page'];



        $pagesize = $data['pagesize'];



        $itemComment = D('ItemComment');



        $type=$data['type'];



        if($type==1){

            (!$pid = $data['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'item_id'));  //商品id



//			    $count = D('Evaluate')->where(array('pid'=>$pid))->count();



//              $Page = new \Think\Page_App($count,$pagesize,$page);



            $list =$itemComment->evaluate($pid,$page,$pagesize,$type);//dump($list);exit;



        }elseif($type==2){



            (!$item_id = $data['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'item_id'));  //商品id



//			     $count = $itemComment->where(array('item_id'=>$item_id))->count();



//              $Page = new \Think\Page_App($count,$pagesize,$page);



            $list = $itemComment->get_evaluate($item_id,$page,$pagesize,$type);



        }



        $this->json_Response('success',$datas['pack_no'],array('list' => $list));



    }



    //我的订单



    public function myorder($datas){



        $this->check_user_id($datas);



        $data = $this->get_datas($datas);



        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);

        //$data['user_id']=9844;

        $uid =$data['user_id'];





        $page = $data['page'];



        $pagesize = $data['pagesize'];



        //dump($data);exit;



        $listorder=D('Address');



//      $count = $listorder->where(array('uid'=>$uid))->count();



//      $Page = new \Think\Page_App($count,$pagesize,$page);



        $type=$data['type'];

        //状态  0=待支付, 1=已支付(待收货)(支付既配送中) ,2=已确认收货(待评价),3= 作废,4=已发货(待收货),6=已评价,7=退款单



        if($type==4){



            //待评价



            $list = $listorder->orders($uid,$page,$pagesize,$type);



        }elseif($type==1){



            //待收货



            $list = $listorder->orders($uid,$page,$pagesize,$type);



        }elseif($type==5){



            //退换货



            $list = $listorder->orders($uid,$page,$pagesize,$type);



        }elseif($type==2){



            //待发货



            $list = $listorder->orders($uid,$page,$pagesize,$type);



        }elseif($type==3){



            //待付款



            $list = $listorder->orders($uid,$page,$pagesize,$type);



        }elseif($type==0){



            //全部订单



            $list = $listorder->orderss($uid,$page,$pagesize);



        }//dump($list);exit;



        $this->json_Response('success',$datas['pack_no'],array('list'=>$list));



    }



    //改变订单状态

    public function change_status($datas){

        $this->check_user_id($datas);

        $data = $this->get_datas($datas);

        $uid =$datas['user_id'];



        (!$oid = $data['oid']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'oid'));  //订单id不能为空

        (!$type = $data['type']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'type'));

        $order_info=$this->_ord ->where(array('id'=>$oid))->field('dingdan,status,addtime,tuikuan_status')->find();

        $dingdan =$order_info['dingdan'];

        $status=(int)$order_info['status'];

        $tuikuan_status=(int)$order_info['tuikuan_status'];



        if($type==1){//确认收货



            if($status==4){

                $result= $this->_ord ->where(array('dingdan'=>$dingdan,'uid'=>$uid))->setField('status',2);

            }else{

                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'err_status4'));//不是已发货(待收货)订单

            }



            if($result){

                $this->json_Response('success',$datas['pack_no']);

            }else{

                $this->json_Response('failed',$datas['pack_no']);

            }

        }elseif ($type==2){//商品评论

            if($status!==2){

                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'err_status2'));//不是已发货(待收货)订单

            }



            (!$commot['degrees']= $data['degrees']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'degrees'));  //请输入星

            (!$commot['memos'] = $data['memos']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'memos'));  //请写评论语

            (!$item_id = $data['item_id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'item_id'));  //商品id不能为空



            $commot['type']=2;//商品评论

            $commot['oid']=$dingdan;

            $commot['uid']=$uid;

            $commot['add_time']=time();

            $commot['item_id']=$item_id;



            $this->_comment->startTrans();

            $result1=$this->_comment->add($commot);//增加评论

            if($result1){



                $item= $this->item->where(array('id'=>$item_id))->field('xingji,comments')->find();

                $new_xing=((double)$data['degrees']+(double)$item['xingji'])/2;

                $arr['xingji']=round($new_xing);

                $arr['comments']+=1;



                $this->item->startTrans();

                $result_item=$this->item->where(array('id'=>$item_id))->save($arr);

                if($result_item){

                    $result=$this->_ord ->where(array('dingdan'=>$dingdan,'uid'=>$uid))->setField('status',6);

                    $set_commit_id=$this->order_list->where(array('oid'=>$oid,'uid'=>$uid,'jid'=>$item_id))->setField('commit_id',$result1);



                    if($result&&$set_commit_id){

                        $this->item->commit();

                        $this->_comment->commit();

                        $this->json_Response('success',$datas['pack_no']);

                    }else{

                        $this->item->rollback();

                        $this->_comment->rollback();

                        $this->json_Response('failed',$datas['pack_no']);

                    }

                }else{

                    $this->item->rollback();

                    $this->_comment->rollback();

                    $this->json_Response('failed',$datas['pack_no']);

                }

            }else{

                $this->json_Response('failed',$datas['pack_no']);

            }





        }else if($type==3){//删除订单

            if($status==0||$status==6||$status==2||($tuikuan_status==2&&$status==7)){

                $result= $this->_ord ->where(array('dingdan'=>$dingdan,'uid'=>$uid))->setField('status',3);

            }else{

                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'err_status'));//此订单状态不能删除

            }



            if($result){

                $this->json_Response('success',$datas['pack_no']);

            }else{

                $this->json_Response('failed',$datas['pack_no']);

            }



        }else if($type==3){//删除订单

            if($status==0||$status==6){

                $result= $this->_ord ->where(array('dingdan'=>$dingdan,'uid'=>$uid))->setField('status',3);

            }else{

                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'err_status'));//此订单状态不能删除

            }



            if($result){

                $this->json_Response('success',$datas['pack_no']);

            }else{

                $this->json_Response('failed',$datas['pack_no']);

            }



        }else if($type==4){//去评论

            if($status==6||$status==2){

                $list=$this->order_list->where(array('oid'=>$oid,'uid'=>$uid))->field('jid,title,img,commit_id')->select();

                $this->json_Response('success',$datas['pack_no'],array('list'=>$list,'dingdan'=>$dingdan,'time'=>$order_info['addtime']));

            }else{

                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'err_status6'));//此订单状态不是已评价

            }

        }











    }



    //去支付

    public function go_pay($datas){

        $this->check_user_id($datas);

        $data = $this->get_datas($datas);

        $uid =$datas['user_id'];

        (!$oid = $data['oid']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'oid'));//订单id

        $balance=$this->_mod->where(array('id'=>$uid))->field('prices,give_redpacket')->find();

        $order= $this->_ord ->where(array('id'=>$oid,'uid'=>$uid))->field('totalprices,dingdan')->find();

        /*if($totalprices==null){

            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'not_status1'));

        }*/



        $this->json_Response('success',$datas['pack_no'],array('totalprices'=>$order['totalprices'],'dingdan'=>$order['dingdan'],'prices'=>$balance['prices'],'give_redpacket'=>$balance['give_redpacket']));

    }







    //我的收藏



    public function mycollection($datas){



        $this->check_user_id($datas);



        $data = $this->get_datas($datas);



        $uid = $datas['user_id'];



        $coll = D('Collection');



        $page = $data['page'];



        $pagesize = $data['pagesize'];



        $type=$data['type'];



        if($type==1){



            $list = $coll->get_all_collection($uid,$page,$pagesize);







        }elseif($type==2){



            $list = $coll->get_collection($uid,$page,$pagesize);







        }







        $this->json_Response('success',$datas['pack_no'],$list);



    }







    //删除收藏



    public function del_collection($datas){







        $this->check_user_id($datas);



        $data = $this->get_datas($datas);



        $uid = $datas['user_id'];



        (!$id = $data['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'id'));//未选择操作对象 //收藏记录的id  删除单条 传一个id  多条删除 传数组







        $coll = D('Collection');







        $type=$data['type'];







        if($type==1){







            $list = $coll->delete_contition($id,$uid);



            file_put_contents("text.txt", var_export($notify,true)."\r\n",FILE_APPEND);







        }elseif($type==2){







            $list = $coll->del_contition($id,$uid);







        }







        $this->json_Response('success',$datas['pack_no']);



    }







    //取消收藏



    public function delete_collection($datas){







        $this->check_user_id($datas);



        $data = $this->get_datas($datas);



        $uid =$datas['user_id']; //用户id



//		 $list = $this->mod_user->get_shouye($uid);



//		 if($list['type'] == 1){



//		     $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'type')); //商家不可以收藏



//		 }



        $arr['type'] = $data['type'];



        $type=$data['type'];







        //验证是否已经收藏



        $collection = D('Collection');



        if($type==1){//店铺收藏



            $arr = $data['id']; //店铺id



            $check_collection = $collection->check_collection($uid,$arr);



        }elseif($type==2){//商品收藏



            $arr = $data['id']; //店铺id



            $check_collection = $collection->check_itemcollection($uid,$arr);



        }







        if($check_collection){



            $delect_c = $collection->del_c($uid,$check_collection);//取消收藏



            if($delect_c)



                $this->json_Response('success',$datas['pack_no']);



            else



                $this->json_Response('failed',$datas['pack_no']);







        }else{



            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'collection')); //车辆没有被收藏



        }



    }







    //订单详情



    public function order_details($datas){

        $this->check_user_id($datas);

        $data = $this->get_datas($datas);

        $uid = $datas['user_id'];



        (!$oid = $data['oid']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'oid'));//订单id



        $order=D('Address');



        $list = $order->where(array('uid'=>$uid,'id'=>$oid))->field('merchant_id,aid,dingdan,addtime,status,totalprices,memos,tuikuan_status')->find();



        $list['item_info'] = M('OrderList')->where(array('uid'=>$uid,'oid'=>$oid))->field('prices,nums,title')->select();

        $list['count']=count($list['item_info']);

        $list['member'] = M('MemberGoodsaddress')->where(array('uid'=>$uid,'id'=>$list['aid']))->field('shperson,mobile,province,city,area,address')->find();

        $list['merchant_name'] = M('merchant')->where(array('id'=>$list['merchant_id']))->getField('title');



        $this->json_Response('success',$datas['pack_no'],array('list'=>$list));

    }



    //商品详情



    public function item_details($datas){







        $this->check_user_id($datas);



        $data = $this->get_datas($datas);



        $uid = $datas['user_id'];







        (!$id = $data['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'id'));//商品id



        $itemComment=D('ItemComment');



        $type=$data['type'];



        if($type==1){



            $item=D('Merchant');



            $item_img=D('Merchant_img');



            $list = $item->where(array('id'=>$id))->find();



            $list['imgs'] = $item_img->where(array('pid'=>$id))->select();



            $list['collection'] = D('Collection')->where(array('pid'=>$id,'uid'=>$uid))->getField('status');



            $count =$itemComment->where(array('merchant_id'=>$id))->count();



        }elseif($type==2){// 商品详情



            $item=D('Item');



            $item_img=D('Item_img');



            $list = $item->where(array('id'=>$id))->find();



            $list['imgs'] = $item_img->where(array('item_id'=>$id))->select();



            $list['collection'] = D('Collection')->where(array('item_id'=>$id))->getField('status');



            $count = $itemComment->where(array('item_id'=>$id))->count();



            $check_gwc=$this->gwc->where(array('member_id'=>$uid,'merchant_id'=>0,'item_id'=>$id))->find();

            if($check_gwc){

                $list['exist_gwc']=1;//已加入购物车

            }else{

                $list['exist_gwc']=0;//未加入购物车

            }



        }

        // dump($list);exit;





        $this->json_Response('success',$datas['pack_no'],array('totalcounts' => $count,'list'=>$list));



    }







    //个人页面



    public function member($datas){

        $this->check_user_id($datas);



        $data = $this->get_datas($datas);

        $bonus=C('pin_cardinal');

        // $datas['user_id']=9844;

        $uid = $datas['user_id'];



        $list =$this->_mod->where(array('id'=>$uid))->field('all_give_redpacket,give_redpacket,consume_redpacket,mobile,types,is_zhsh,avatar,nickname,gender,bare,goods_prices,achievement,prices')->find();



        $list['head']  = D('merchant')->where(array('member_id'=>$uid))->field('id,title,logo,office_starthours,office_endhours,office_startday,office_endday,tel,member_id,withdraw_charge')->find();



        $red_right = floor($list['consume_redpacket']/C('pin_cardinal')) - floor($list['all_give_redpacket']/C('pin_cardinal')) ;

        $list['bonus']=$red_right<0 ?0:$red_right;



        // $list2=($list['consume_redpacket']-$list['all_give_redpacket'])/$bonus;

        //$aa=sprintf("%.2f", $list2);

        //$list['bonus']=round($aa, 0);



        //$list['card']  = D('Card')->where(array('user_id'=>$uid))->count();

        $count_num=$this->_mod->where(array('id'=>$uid))->getField('accountno');

        if($count_num==0||$count_num==null||$count_num=="") {

            $list['card']=0;

        }else{

            $list['card']=1;

        }

        //dump($list);exit;



        $this->json_Response('success',$datas['pack_no'],array('list' => $list));



    }



    //自营账单记录



    public function record($datas){







        $this->check_user_id($datas);



        $data = $this->get_datas($datas);



        $uid = $datas['user_id'];



        $page = $data['page'];



        $pagesize = $data['pagesize'];



        $list = $this->_ord ->record($uid,$page,$pagesize);







        $this->json_Response('success',$datas['pack_no'],array('list' => $list));



    }







    //客服



    public function get_mobile($datas){

        $list = C('pin_tel');

        $this->json_Response('success', $datas['pack_no'],array('list'=>$list));

    }





    //版本号和版本说明



    public function version($datas){



        $list = array();



        $list['version_number'] = C('pin_site_name');//版本号



        $list['release_notes'] = C('pin_area');//版本说明



        $this->json_Response('success', $datas['pack_no'],array('list'=>$list));



    }



    //金豆支付

    public function pay_packet($datas){

        $this->check_user_id($datas);

        $data = $this->get_datas($datas);

        $uid  = $datas['user_id'];



        (!$mobile=$data['mobile']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile'));//商品id

        (!$money=strval($data['give_redpacket'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'redpacket'));//商品id



        $a= $this->_mod->where(array('id'=>$uid))->field('mobile,give_redpacket,pay_pwd')->find();

        $c= $this->_mod->where(array('mobile'=>$mobile))->field('id,mobile,types,goods_prices,give_redpacket')->find();



        if($c['id'] == $uid){

            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'is_self'));//不能给自己转金豆

        }



        if($c == ""){

            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile2'));//此账号不存在

        }



        if($money > $a['give_redpacket']){

            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'give_redpacket'));//金豆余额不足

        }



        (!$oldpwd = check_paypwd($data['pay_pwd'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'pay'));



        $newpwd = st_md5($oldpwd);

        if($newpwd != $a['pay_pwd']){

            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'pay_pwd'));//支付密码错误

        }



        $where['give_redpacket'] = $a['give_redpacket']-$money;

        if($c['types'] == 2){//商家为货款



            $date['goods_prices'] = $c['goods_prices']+$money;

        }else{//用户为金豆

            $date['give_redpacket'] = $c['give_redpacket']+$money;

        }

        M()->startTrans();



        $b = $this->_mod->where(array('id'=>$uid))->save($where);

        $f = $this->_mod->where(array('mobile'=>$mobile))->save($date);





        $self_account['uid']=$uid;

        $self_account['iiuv']=$c['mobile'];

        $self_account['red_packet']=-$money;

        $self_account['create_time']=time();

        $self_account['change_desc']="金豆转账：转到".$c['mobile'];

        $self_account['change_type']=31;



        $other_account['uid']=$c['id'];

        $other_account['iiuv']=$a['mobile'];

        if($c['types'] == 2){//商家为货款

            /*$other_account['goods_prices']=$money;

            $other_account['all_give_redpacket']=$money;*/

            $other_account['red_packet'] = $money;

        }else{//用户为金豆

            $other_account['red_packet'] = $money;

//            $other_account['give_redpacket']=$money;

        }

        $other_account['create_time']=time();

        $other_account['change_desc']="金豆转账：".$a['mobile']." 转进";

        $other_account['change_type']=31;



        if($f&&$b){

            $self=$this->Account->add($self_account);

            $other=$this->Account->add($other_account);

            if(false !== $other && false !== $self){

                M()->commit();

                $this->json_Response('success',$datas['pack_no'],array('list' => $a));

            }else{

                M()->rollback();

                $this->json_Response('failed',$datas['pack_no']);

            }

        }else{

            M()->rollback();

            $this->json_Response('failed',$datas['pack_no']);

        }

    }



    //转账明细

    public function pay_list($datas){

        $this->check_user_id($datas);

        $data = $this->get_datas($datas);

        //$datas['user_id']=9837;

        $uid  = $datas['user_id'];



        $page = $data['page'];

        $pagesize = $data['pagesize'];

        $count = M('Merchant')->where(array('uid'=>$uid,'change_type'=>31))->count();

        $Page = new \Think\Page_App($count,$pagesize,$page);



        $list= $this->Account->where(array('uid'=>$uid,'change_type'=>31))->limit($Page->firstRow.','.$Page->listRows)->field('iiuv,red_packet,create_time')->order('create_time desc')->select();



        foreach ($list as $k=>$v){

            $v['red_packet']=(double)$v['red_packet'];

            if($v['red_packet']>0){

                $list[$k]['red_packet']=(String)"+".$v['red_packet'];

            }

        }



        if($list){

            $this->json_Response('success',$datas['pack_no'],array('list' => $list));

        }else{

            $this->json_Response('success',$datas['pack_no'],array('list' => null));

        }

    }



    //可用金豆



    public function packet($datas){

        $this->check_user_id($datas);

        $data = $this->get_datas($datas);



        $uid  = $datas['user_id'];



        //$list=C('pin_share_cardinal');

        // $b=$this->_mod->where(array('id'=>$uid))->field('bare,give_redpacket')->find();

        // if($b['bare']>=$list){

//            $a['bare']=$b['bare']-$list;

//            $a['give_redpacket']=$b['give_redpacket']+$list;

//            $c=$this->_mod->where(array('id'=>$uid))->save($a);

        // }

        $list1=$this->_mod->where(array('id'=>$uid))->field('give_redpacket,bare,all_give_redpacket,goods_prices,prices')->find();

        $list1['pricesto_redpacket'] = C('pin_pricesto_redpacket');

        $list1['share_red'] = C('pin_share_red');

        $this->json_Response('success',$datas['pack_no'],array('list' =>$list1));

    }



    //金豆转余额，



    public function give_packet($datas){



        $this->check_user_id($datas);



        $data = $this->get_datas($datas);



        $uid  =$datas['user_id'];



        $member=D('Member');



        (!$packet=$data['packet']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'packet'));//金豆数量不能为空



        $a=$member->where(array('id'=>$uid))->field('give_redpacket,prices,pay_pwd,consume_redpacket')->find();



        if($packet>$a['give_redpacket']){



            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'give_redpacket'));//金豆余额不足



        }



        $list=strval($packet-($packet*0.16));    //实际兑换余额







        (!$pay_pwd = check_paypwd($data['pay_pwd'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'pwd'));



        $newpwd = st_md5($pay_pwd);



        if($newpwd!=$a['pay_pwd']){



            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'pay_pwd'));//支付密码错误



        }



        $where['give_redpacket']=$a['give_redpacket']-strval($packet);    //金豆余额



        $where['prices']=$a['prices']+$list;    //商家余额



        $where['consume_redpacket']=$a['consume_redpacket']+strval($packet);    //累计消费金豆







        $b=$member->where(array('id'=>$uid))->save($where);



        if($b){



            $this->json_Response('success',$datas['pack_no'],array('list' =>$a['give_redpacket'],'list2'=>$list));



        }



    }



    //分类

    public function class_ify($datas){

        $data = $this->get_datas($datas);//dump($data);exit

        //先获取定位信息

        (!$city=$data['city']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'city'));//金豆数量不能为空

        //在获取分类信息

        $class=$data['class'];

        $place=D('Place');

        $merchant=D('Merchant');

        $merch=D('Merchant_cate');

        $page = $data['page'];

        $pagesize = $data['pagesize'];

        //市

        $date['name']=array('like','%'.$city.'%');

        $list=$place->where($date)->getfield('id');

        //区

        $list3=$place->where(array('pid'=>$list))->field('name')->select();

        $list3['fujin']='附近';



        if($class!==null&&$class!==""&&$class!==NULL){

            $where['name']  = array('like','%'.$class.'%');

            $where['pid']   = 0;

            $a = $merch->where($where)->getfield('id');

            $b = $merch->field('id')->where('spid LIKE \''.$a.'|%\'')->select();

            $list1 = implode(',',array_column($b,'id'));

            $list1 .= empty($list1) ? $a : ','.$a;

        }

        $area=$data['area'];

        $lat=(String)$data['latitude'];

        $lng=(String)$data['longitude'];

        $distance=$data['distance']*1000;

        if(($area=="附近"&&$distance=="全城")||$area==""){



            //不选地区，距离最近

            $condition['status']=4;//营业中

            $condition['city_id']=(int)$list;

            if($list1!==null){

                $condition['cate_id'] = array('in',$list1);

            }

            $count = $merchant->where($condition)->count();

            $Page = new \Think\Page_App($count,$pagesize,$page);

            $aa=$merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)

                ->field('id,title,logo,xing,address,tel,longitude,latitude,cate_id')->select();

            foreach ($aa as $k=>$v){

                $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);

                $aa[$k]['distance']=sprintf("%.2f", intval($distancess)/1000);

            }

            $type=$data['type'];

            if($type==1){

                //销量排序

                $aa=$merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)

                    ->field('id,title,logo,xing,address,tel,longitude,latitude')

                    ->order("sales desc")->select();

                foreach ($aa as $k=>$v){

                    $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);

                    $aa[$k]['distance']= sprintf("%.2f", intval($distancess)/1000);

                }

            }elseif($type==2){

                //好评排序

                $aa=$merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)

                    ->field('id,title,logo,xing,address,tel,longitude,latitude')->order("xing desc")->select();

                foreach ($aa as $k=>$v){

                    $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);

                    $aa[$k]['distance']= sprintf("%.2f", intval($distancess)/1000);

                }

            }elseif($type==3){

                //距离最近

                $aa= M('Merchant')->where($condition)->limit($Page->firstRow.','.$Page->listRows)

                    ->field('id,title,logo,xing,address,tel,longitude,latitude')->select();

                foreach ($aa as $k=>$v){

                    $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);

                    $aa[$k]['distance']= sprintf("%.2f", intval($distancess)/1000);

                }

                $aa=$this->array_sort($aa, distance, $type = 'asc');

            }

        }else{

            if($distance=="" ){



                //各区的店铺



                $list4=$place->where(array('name'=>$area))->getfield('id');



                $condition['status']=4;//营业中



                $condition['city_id']=$list;



                $condition['cate_id']=$list1;



                $condition['district_id']=$list4;



                $count = $merchant->where($condition)->count();



                $Page = new \Think\Page_App($count,$pagesize,$page);



                $aa=$merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)



                    ->field('id,title,logo,xing,address,tel,longitude,latitude')->order("id desc")->select();



                foreach ($aa as $k=>$v){



                    $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);



                    $aa[$k]['distance']= sprintf("%.2f", intval($distancess)/1000);



                }

                file_put_contents("text.txt", var_export($aa,true)."\r\n",FILE_APPEND);

                $type=$data['type'];



                if($type==1){



                    $aa=$merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)



                        ->field('id,title,logo,xing,address,tel,longitude,latitude')->order("sales desc")->select();



                    foreach ($aa as $k=>$v){



                        $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);



                        $aa[$k]['distance']=sprintf("%.2f", intval($distancess)/1000);



                    }



                }elseif($type==2){



                    $aa=$merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)



                        ->field('id,title,logo,xing,address,tel,longitude,latitude')->order("xing desc")->select();



                    foreach ($aa as $k=>$v){



                        $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);



                        $aa[$k]['distance']=sprintf("%.2f", intval($distancess)/1000);



                    }



                }elseif($type==3){



                    //距离最近



                    $aa= $merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)



                        ->field('id,title,logo,xing,address,tel,longitude,latitude')->select();



                    foreach ($aa as $k=>$v){



                        $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);



                        $aa[$k]['distance']=sprintf("%.2f", intval($distancess)/1000);



                    }



                    $aa=$this->array_sort($aa, distance, $type = 'asc');



                }



            }else{



                //距离最近，根据附近距离查找所有店铺



                $array = getAround($lat, $lng, $distance);



                $condition['status']=4;   //营业中



                $condition['latitude']  = array(array('EGT',$array['minLat']),array('ELT',$array['maxLat']),'and');//(`latitude` >= minLat) AND (`latitude` <=maxLat)



                $condition['longitude'] = array(array('EGT',$array['minLng']),array('ELT',$array['maxLng']),'and');//(`longitude` >= minLng) AND (`longitude` <= maxLng)



                $condition['city_id']=$list;



                $condition['cate_id']=$list1;



//		        $count = $merchant->where($condition)->count();

//

//	            $Page = new \Think\Page_App($count,$pagesize,$page);



                $like=$merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)



                    ->field('id,title,logo,xing,address,tel,longitude,latitude')->select();





                foreach ($like as $k=>$v){



                    $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);



                    $like[$k]['distance']= sprintf("%.2f", intval($distancess)/1000);







                    if($distancess>$distance){

                        unset($like[$k]);

                    }



                    $aa=array();

                    foreach($like  as $k=>$v){

                        $bb=array();

                        $bb=$v;

                        $aa[]=$bb;

                    }



                }









                $type=$data['type'];

                if($type==1){



                    $like=$merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)



                        ->field('id,title,logo,xing,address,tel,longitude,latitude')->order("sales desc")->select();



                    foreach ($like as $k=>$v){



                        $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);



                        $like[$k]['distance']= sprintf("%.2f", intval($distancess)/1000);

                        if($distancess>$distance){

                            unset($like[$k]);

                        }

                        $aa=array();

                        foreach($like  as $k=>$v){

                            $bb=array();

                            $bb=$v;

                            $aa[]=$bb;

                        }

                    }



                }elseif($type==2){



                    $like=$merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)



                        ->field('id,title,logo,xing,address,tel,longitude,latitude')->order("xing desc")->select();



                    foreach ($like as $k=>$v){



                        $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);



                        $like[$k]['distance']=sprintf("%.2f", intval($distancess)/1000);

                        if($distancess>$distance){

                            unset($like[$k]);

                        }

                        $aa=array();

                        foreach($like  as $k=>$v){

                            $bb=array();

                            $bb=$v;

                            $aa[]=$bb;

                        }

                    }



                }elseif($type==3){



                    //距离最近



                    $array = getAround($lat, $lng, $distance);



                    $like= $merchant->where($condition)->limit($Page->firstRow.','.$Page->listRows)



                        ->field('id,title,logo,xing,address,tel,longitude,latitude')->select();



                    foreach ($like as $k=>$v){



                        $distancess=getdistance($lng,$lat,$v['longitude'],$v['latitude']);



                        $like[$k]['distance']= sprintf("%.2f", intval($distancess)/1000);



                        if($distancess>$distance){

                            unset($like[$k]);

                        }

                        $aa=array();

                        foreach($like  as $k=>$v){

                            $bb=array();

                            $bb=$v;

                            $aa[]=$bb;

                        }

                    }



                    $aa=$this->array_sort($aa, distance, $type = 'asc');



                }



            }



        }



        //dump($aa);exit;



        $this->json_Response('success',$datas['pack_no'],array('list'=>$aa,'count'=>$count));















    }







    public function distance($datas){







        $data = $this->get_datas($datas);



        //先获取定位信息



        (!$city=$data['city']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'city'));//金豆数量不能为空



        //在获取分类信息



        //市



        $date['name']=array('like','%'.$city.'%');



        $list= $this->place ->where($date)->find();







        //区



        $list3= $this->place ->where(array('pid'=>$list['id']))->field('name')->select();







        $list4=array();



        $list4[]=array('name'=>'附近');



        foreach($list3 as $key=>$val){



            $bb=array();



            $bb['name']=$val['name'];







            $list4[]=$bb;



        }











        $this->json_Response('success',$datas['pack_no'],array('list4'=>$list4));



    }







    function array_sort($arr, $keys, $type = 'desc') {







        $keysvalue = $new_array = array();



        foreach ($arr as $k => $v) {



            $keysvalue[$k] = $v[$keys];



        }



        if ($type == 'asc') {



            asort($keysvalue);



        } else {



            arsort($keysvalue);



        }



        reset($keysvalue);



        foreach ($keysvalue as $k => $v) {



            $new_array[] = $arr[$k];



        }







        return $new_array;



    }







    //根据两点求距离



    function getdistance($lng,$lat,$lng2,$lat2){



        //将角度转为狐度



        $radLat1=deg2rad($lat);//deg2rad()函数将角度转换为弧度



        $radLat2=deg2rad($lat2);



        $radLng1=deg2rad($lng);



        $radLng2=deg2rad($lng2);



        $a=$radLat1-$radLat2;



        $b=$radLng1-$radLng2;



        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;



        return $s;



    }



    /**



     * @param  $latitude    纬度



     * @param  $longitude    经度



     * @param  $raidus        半径范围(单位：米)



     * @return multitype:number



     */



    public function getAround($latitude,$longitude,$raidus){







        $PI = 3.14159265;



        $degree = (24901*1609)/360.0;



        $dpmLat = 1/$degree;



        $radiusLat = $dpmLat*$raidus;



        $minLat = (float)$latitude - $radiusLat;



        $maxLat = (float)$latitude + $radiusLat;



        $mpdLng = $degree*cos($latitude * ($PI/180));



        $dpmLng = 1 / $mpdLng;



        $radiusLng = $dpmLng*$raidus;



        $minLng = (float)$longitude - $radiusLng;



        $maxLng = (float)$longitude + $radiusLng;



        return array (minLat=>$minLat, maxLat=>$maxLat, minLng=>$minLng, maxLng=>$maxLng);



    }







    //提现

    public function withdraw($datas){

        $this->check_user_id($datas);//验证是否登录

        $data = $this->get_datas($datas);//获取数据

        ($data == null || count($data) <=0 ) && $this->print_error_status('params_error',$datas['pack_no']);

        //$data['user_id']=9837;

        $uid  =$data['user_id'];//获取用户id

        (!$money = $data['money']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'money'));//提现金额不能小于30或不能为空



        $pwd = $data['pay_pwd'];

        $type=$data['type'];

        $remark=$data['remark'];

        $pay_pwd = st_md5($pwd);

        // $arr =$this->card->validation_catd($id,$uid);

        $results =$this->_mod->get_info($uid);

//      $money = (int)$money * 10;



        if ($results['accountno']==0||$results['accountno']==null||$results['accountno']==""){

            $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'accountno_error'));//没有添加银行卡

        }



        if ($results['prices'] < $money){

            $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'price_error'));//您余额不足请不能进行提现

        }



        if ($results['pay_pwd']!= $pay_pwd){

            $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'pay_pwd'));//支付密码错误

        }

        $timess=time();

        $team=date('ymd',$timess);



        $code =$team.rand(1000000000,9999999999);//订单号



        if(!$this->withdraw->validation_user($uid)){//判断用户是否有其他的在提现

            $this->withdraw->startTrans();

            $information = array(

                'create_time'=> time(),

                'bank_account'   => $results['accountno'],

                'member_id'       => $uid,

                'type'     => $type,

                'amount'     => $money,

                'remark'=>$remark,

                'branch'=>$results['bankname'],

                'uname'=>$results['realname'],

                'order_no'=>$code,

            );



            $map['prices'] = array('exp','prices-'.$money);

            $save = $this->_mod->set_user($uid,$map);

            $add = $this->withdraw->add_withdraw($information);



            if($add && $save){//修改用户表的余额和向提现表中添加数据

                $this->withdraw->commit();

                $this->json_Response('success', $datas['pack_no']);

            } else {

                $this->withdraw->rollback();

                $this->json_Response('params_error', $datas['pack_no']);

            }

        }else{

            $this->json_Response('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'status_error'));//在审核中不能进行提现

        }

    }



    //去评价



    public function evaluate($datas){



        $this->check_user_id($datas);



        $data = $this->get_datas($datas);



        $uid  = $data['user_id'];



        //赠送金豆记录的id和订单ID



        $id=$data['id'];



        $type=$data['type'];



        if($type==1){//店铺评论



            $list=$this->Account->where(array('id'=>$id))->find();



            $list4= $this->merchant->where(array('id'=>$list['merchant_id']))->find();



            if($list['comment_id']){



                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'list')); //已经评论



            }else{



                $arr['memos']=$data['memos'];



                $arr['degrees']=$data['degrees'];



                $arr['add_time']=time();



                $arr['uid']=$list['uid'];



                $arr['merchant_id']=$list['merchant_id'];



                $arr['type']=$type;



                $list2=$this->_comment->add($arr);//添加店铺评论



                $list3=$this->Account->where(array('id'=>$id))->setfield('comment_id',$list2);



                $merchant_xing=$this->merchant->where(array('id'=>$list['merchant_id']))->getField('xing');

                $new_xing=((double)$data['degrees']+(double)$merchant_xing)/2;

                $xing=round($new_xing);

                $aa=$this->merchant->where(array('id'=>$list['merchant_id']))->setField('xing',$xing);



            }



        }elseif($type==2){//商品评论



            $item_id=$data['item_id'];



            $list1=$this->_comment->where(array('oid'=>$id,'item_id'=>$item_id))->find();







            if($list1){



                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'list')); //已经评论



            }else{



                $arr['memos']=$data['memos'];



                $arr['degrees']=$data['degrees'];



                $arr['oid']=$id;



                $arr['add_time']=time();



                $arr['item_id']=$item_id;



                $arr['type']=$type;



                $list2=$this->_comment->add($arr);



            }



        }



        $this->json_Response('success',$datas['pack_no']);



    }

    //提现记录

    public function incarnate($datas){

        $this->check_user_id($datas);

        $data = $this->get_datas($datas);

        $uid  = $datas['user_id'];



        $page = $data['page'];

        $pagesize = $data['pagesize'];

        $type=$data['type'];

        if($type==1){

            $count =$this->withdraw->where(array('member_id'=>$uid))->count();

            $Page = new \Think\Page_App($count,$pagesize,$page);

            $list=$this->withdraw->where(array('member_id'=>$uid))->limit($Page->firstRow.','.$Page->listRows)->field('amount,create_time')->order('create_time desc')->select();

            foreach($list as $item){

                $temp = $item;

                $list2 = $this->_mod-> where(array('id'=>$uid))->find();

                $temp['mobile'] = $list2['mobile'];

                $arr[] = $temp;

            }

        }elseif($type==2){

            $count =$this->recharge ->where(array('uid'=>$uid,'status'=>1))->count();

            $Page = new \Think\Page_App($count,$pagesize,$page);

            $arr=$this->recharge ->where(array('uid'=>$uid,'status'=>1))->limit($Page->firstRow.','.$Page->listRows)->field('totalprices,addtime')->order('addtime desc')->select();



        }

        $this->json_Response('success',$datas['pack_no'],array('arr' => $arr));



    }



    //金豆使用限制

    public function red_envelope_to_limit($datas)

    {

        $uid  = $datas['user_id'];

        $price_type = 0;

        if ($uid){

            $price = $this->_mod->where('id='.$uid)->getField('consume_redpacket');

            (int)C('pin_use_red') <= $price && $price_type = 1;

        }

        $this->json_Response('success',$datas['pack_no'],array('type'=>$price_type));

    }



    //android版本号

    public function android($datas)

    {

        $this->json_Response('success',$datas['pack_no'],C('pin_android'));

    }



    //商家添加订单

    public function front_add_order($datas)

    {

        $this->json_Response('success',$datas['pack_no'],C('pin_service_charge'));

    }



    //商家添加订单验证用户

    public function user_add_order($datas)

    {

        $this->check_user_id($datas);

        $data = $this->get_datas($datas);

        $uid  = $datas['user_id'];



        $user = M('member')-> where(array('mobile'=> $data['mobile']))->field('id,prices,give_redpacket,realname')->find() ;  //买家



        empty($user) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'mobile'));//没有改用户

        ($uid == $user['id']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'oneself'));//不能我自己添加订单



        $this->json_Response('success',$datas['pack_no'],$user);

    }



    //商家添加订单

    public function add_order($datas)

    {

        $this->check_user_id($datas);

        $data = $this->get_datas($datas);

        $uid  = $datas['user_id'];



        (!$mobile = $data['mobile']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'mobile'));//手机号不能为空

        (!$service_charge = $data['service_charge']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'service_charge'));//金额不能为空



        $member = M('Member');

        $user = $member->where(array('mobile'=>$mobile))->field('id,prices')->find() ;  //买家

        $prices = $member->where('id = '.$uid)->getField('prices');



        ($prices < $service_charge) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'not_sufficient_funds'));//商家余额不足



        //生成订单

        $arr['merchant_id']         = $data['merchant_id'];//店铺id

        $arr['dingdan']             = make_order_id();

        $arr['uid']                 = $user['id'] ;

        $arr['type']                = 2 ;

        $arr['status']              = 2 ;

        $arr['pay_time']            = time() ;

        $arr['addtime']             = time() ;

        $arr['totalprices']         = $data['totalprices'];

        $arr['cash_prices']         = $data['totalprices'];

        $arr['service_charge']      = $service_charge;



        $rs = M('Address')->add($arr);

        if ($rs){

            $data1['oid']       = $rs ;

            $data1['uid']       = $uid ;

//            $data1['jid']       = $data['jid'] ;

//            $data1['nums']      = $data['nums'] ;

//            $data1['title']     = $data['title'] ;



            $rs1 = D('OrderList')->add($data1);

            if ($rs1){

                //商家扣除服务费

                ($service_charge) &&$data3['prices']=array('exp','prices-'.$service_charge);



                if($data3){

                    M('Member')->where(array('id'=>$uid))->data($data3)->save();  //商家

                }



                $data4['uid']               = $uid;  //商家ID

                $data4['oid']               = $rs ;

                $data4['prices']            = $data['totalprices'];

                $data4['goods_prices']      = $data['totalprices'];

                $data4['service_charge']    = '-'. $service_charge;

                $data4['change_desc']       = '商家报单：'.$arr['dingdan'];

                $data4['change_type']       = 23 ;

                $data4['create_time']       = time() ;



                $s_res = $this->merchant->where('member_id = '.$uid)->setInc('sales');

                if (false !== $s_res && M('account_log')->add($data4)){

                    $this->json_Response('success',$datas['pack_no']);

                }else{

                    $this->json_Response('params_error', $datas['pack_no']);

                }

            }

        }else{

            $this->json_Response('params_error', $datas['pack_no']);

        }

    }



    //获取微信支付配置

    public function wx_configuration($datas)

    {

        $wx = array();

        $wx['app_id'] = C('pin_wx_appid');

        $wx['app_secret'] = C('pin_wx_app_secret');

        $this->json_Response('success',$datas['pack_no'],$wx);

    }

}



