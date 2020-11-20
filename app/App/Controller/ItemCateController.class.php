<?php
namespace App\Controller;
use Think\Rc4;
use Think\HxCommon;
class ItemCateController extends ApiController{
    public function _initialize()
    {
        parent::_initialize();
        $this->member = D('Member');    //用户表
        $this->item = D('Item');          //商品表
        $this->item_cate = D('ItemCate'); //商品栏目表
        $this->place=D('Place');//地区表
        $this->order=D('Address');//订单表
        $this->OrderList=D('OrderList');//订单详细
        $this->card=D('Card');//银行卡
        $this->withdraw=D('Withdraw');//银行卡消费记录
        $this->merchant_cate=D('MerchantCate');//店铺分类
        $this->account_log=D('AccountLog');//账单详情
        $this->sms=D('Sms');//验证码
        $this->merchant=D('Merchant');//店铺

        $this->hx_common =new HxCommon(C('hx_params'));
    }

    //城市
    public function get_by_id_item($datas){
        $data = $this->get_datas($datas);

        $list = $this->itemcate->get_all_one_item();//获取所有一级分类
        $this->json_Response('success',$datas['pack_no'],$list);
    }
    
    //根据城市获取商品
    public function get_two_item($datas){
        $data = $this->get_datas($datas);

        (!$item_id = $data['item_id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'item_id'));//城市id

        //获取所有子分类
        $list = $this->itemcate->get_two_item($item_id);

        $list2 = $this->item->get_id_item(empty($data['id']) ? $list[0]['id'] : $data['id']);

        $list3 = M('Ad')->field('id,content')->where('id=24')->find();//广告图

        $this->json_Response('success',$datas['pack_no'],array('list'=>$list,'list2'=>$list2,'list3'=>$list3));
    }

    /**
     * 中亿商城接口
     */

    //注册用户
    public function reg($datas){
        $data = $this->get_datas($datas); //从请求中获取数据
        ($data == NULL || count($data) <= 0) && $this->print_error_status('params_error', $datas['pack_no']);
        //dump($data);exit;
        $arr = array();
        (!$arr['nickname'] = check_nickname($data['nickname'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'nickname'));//由大小写字母跟数字组成并且长度在6-20字符直接
        (!$arr['mobile'] = check_mobile($data['mobile'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'mobile'));//手机号码格式错误
        (!$code = $data['code']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'code'));//请填写验证码
        (!$password = check_pwd($data['password'])) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'password'));//密码不能为空
        (!$checkbox = $data['checkbox']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'checkbox'));//用户协议
        (!$invitation_code = $data['invitation_code']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'invitation_code'));//邀请码不能为空

        $yz_code=$this->sms->where(array('mobile'=>$arr['mobile']))->order('create_time desc')->field('code')->select();
        $yz_code1=$yz_code[0]['code'];
        if($code!==$yz_code1){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'code_err'));//验证码错误
        }

        if ($this->member->where(array('nickname' => $arr['nickname']))->getField('id'))//判断用户名是否已存在
            $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'exist_nickname'));

        if ($this->member->where(array('mobile' => $arr['mobile']))->getField('id'))//验证手机号码是否已注册
            $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'mobile_error'));

        //($code != $_COOKIE['code']) &&$this->print_error_status('params_error', $datas['pack_no'],array('ERROR_Param_Format' => 'code_error'));
        $this->member->startTrans();
        $now = time();

        $invitation_code = $data['invitation_code'];
        //推荐码

        $sj_member_id = $this->member->where(array('mobile' => $invitation_code))->getField('id');
        if (!$sj_member_id) {
            $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'no_thisman'));//无此推荐人
        }

        $arr['iiuv'] = $invitation_code;
        $arr['password'] = st_md5($password);
        $arr['reg_time'] = $now;
        $str = $now . rand(100, 999);
        $arr['reg_ip'] = get_client_ip();//dump($arr);exit;
        $arr['consume_redpacket'] = 500;

        $this->member->startTrans();
        //持久化
        $new_id = $this->member->add($arr);

        if ($new_id) {

            $arr_log['uid']=$new_id;
            $arr_log['red_packet'] = 500;
            $arr_log['change_desc'] = '增加累计消费金豆：'.time();
            $arr_log['change_type'] = '27';
            $arr_log['create_time'] = time();
            $a_res = $this->account_log->add($arr_log);
            $res = $this->member->where('id='.$sj_member_id)->setInc('bare',round((C('pin_iiuv_member') / 1000) * 500,2));
            //进行环信同步注册
            $result = $this->hx_common->createUser($new_id, $arr['password']);

            if(!empty($result['entities']) && false !== $a_res && false !== $res){
                $boo = $this->hx_common->getUser($new_id);

                if(!empty($boo['entities'])){
                    $this->member->commit();//提交事务
                    $this->json_Response('success',$datas['pack_no'],array('reg' => 'reg_success'));
                }
            }else{
                $this->member->rollback();//事务回滚
                $this->json_Response('failed',$datas['pack_no'],array('reg' => 'reg_failed'));
            }
        } else {
            $this->member->rollback();
            $this->json_Response('failed', $datas['pack_no'],array('reg' => 'reg_failed'));
        }
    }

/*
    //注册
    public function reg($datas){
        $data = $this->get_datas($datas); //从请求中获取数据
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);

        $arr = array();
        (!$arr['nickname'] = check_nickname($data['nickname']))&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'nickname'));//由大小写字母跟数字组成并且长度在6-20字符直接
        (!$arr['mobile'] = check_mobile($data['mobile']))&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile'));//手机号码格式错误
        (!$code = $data['code']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'code'));//请填写验证码
        (!$password = check_nickname($data['password'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'password'));//密码不能为空
        (!$checkbox = $data['checkbox'])&& $this->print_error_status('params_error', $datas['pack_no'],array('ERROR_Param_Format' => 'checkbox'));//用户协议
        (!$invitation_code= $data['invitation_code']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'invitation_code'));//邀请码不能为空

        $yz_code=$this->sms->where(array('mobile'=>$arr['mobile']))->order('create_time desc')->field('code')->select();
        $yz_code1=$yz_code[0]['code'];
        if($code!==$yz_code1){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'code_err'));//验证码错误
        }

        if($this->member->where(array('nickname'=>$arr['nickname']))->getField('id'))//判断用户名是否已存在
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'exist_nickname'));

        if($this->member->where(array('mobile'=>$arr['mobile']))->getField('id'))//验证手机号码是否已注册
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile_error'));

        //($code != $_COOKIE['code']) &&$this->print_error_status('params_error', $datas['pack_no'],array('ERROR_Param_Format' => 'code_error'));
        $this->member->startTrans();
        $now=time();

        $invitation_code=$data['invitation_code'];
        //推荐码

        $sj_member_id=$this->member->where(array('mobile'=>$invitation_code))->getField('id');
        if(!$sj_member_id){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'no_thisman'));//无此推荐人
        }

        $arr['iiuv'] =$invitation_code;
        $arr['password'] = st_md5($password);
        $arr['reg_time'] = $now;
        $str=$now.rand(100,999);
        $arr['reg_ip'] = get_client_ip();//dump($arr);exit;
        $new_id = $this->member->add($arr);
        //  dump($new_id);exit;
        if($new_id){
            $this->json_Response('success',$datas['pack_no'], array('reg' =>'reg_success'));
        }else{
            $this->member->rollback();
            $this->json_Response('failed',$datas['pack_no'], array('reg' =>'reg_failed'));
        }

    }*/

    //忘记密码
    public function forgetpwd($datas){

        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);

        (!$mobile = check_mobile($data['mobile'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile'));

        (!$code = $data['code']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'code'));

        (!$password = check_pwd($data['password'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'password'));
        (!$repassword = check_pwd($data['repassword'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'password'));
        ($password != $repassword) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Password_Not_Consistent' => 'pwd'));

        $yz_code=$this->sms->where(array('mobile'=>$mobile))->order('create_time desc')->field('code')->select();
        $yz_code1=$yz_code[0]['code'];

        if((String)$code!==$yz_code1){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'code_error'));//验证码错误
        }

        $yz_password=$this->member->where(array('mobile'=>$mobile))->field('id,password')->find();
        if(st_md5($yz_password['password'])==$password){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'password_original'));//原密码
        }

        $pwd=st_md5($password);

        //开户事务
        $this->member->startTrans();
        $require_pwd=$this->member->where(array('mobile'=>$mobile))->save(array('password'=>$pwd));

        if ($require_pwd) {
            //进行环信修改密码
            $result = $this->hx_common->resetPassword($yz_password['id'],$pwd);

            if ($result) {
                $this->member->commit();
                $this->json_Response('success', $datas['pack_no']);
            } else {
                $this->member->rollback();
                $this->json_Response('failed', $datas['pack_no']);
            }
        }
    }

    //登录
    public function login($datas){
        $data = $this->get_datas($datas);

        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        (!$mobile =$data['mobile']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile'));
        (!$password = check_pwd($data['password'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'password'));

        //验证用户名是否存在
        $condition['nickname|mobile']=$mobile;
        $condition['status']=1;
        $pwd=st_md5($password);
        $check_account = $this->member->check_acc($condition,$pwd);//dump($pwd);dump($check_account);//exit;

        if($check_account == 2){
            $this->json_Response('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'password_error'));
        }else if($check_account == 3){
            $this->json_Response('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile_error'));
        }else{
          //  dump(1111);exit;
            $this->member->where('id='.$check_account['id'])->setField(array('last_login_time' => time(),'last_login_ip'=>get_client_ip()));
            //更新最后登录时间 ip 次数
            $set_status = $this->member->set_ip($mobile);

            $result = $this->hx_common->getUser($check_account['id']);//dump($result['entities']);exit;
            if (empty($result['entities'])){
                //进行环信同步注册
                $this->hx_common->createUser($check_account['id'], $check_account['password']);
                $result = $this->hx_common->getUser($check_account['id']);
            }

            //用户昵称
            $nickname=$this->member->where('id='.$check_account['id'])->getField('nickname');

            if(!empty($result['entities'])){
                //构建登录凭证,返回给客户端，客户端每个请求都会携带，服务器端进行解密等到user_id
                $rc4 = new Rc4();
                $C_userid_encryption_key = C('userid_encryption_key');
                $login_credentials = str_replace('+','|jia|',$rc4->authcode($check_account['id'],'ENCODE',$C_userid_encryption_key));

                $this->json_Response('success',$datas['pack_no'],array('local_login' => 1 , 'hx_login' => 1 , 'login_credentials' => $login_credentials ,"u_id"=>$check_account['id'], "mobile" => $mobile,'id'=>$check_account['id'],'password'=>$pwd,'nickname'=>$nickname,'avatar'=>$check_account['avatar']));
            }else{
                $this->json_Response('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'hx_login'));
            }

            /*  //构建登录凭证,返回给客户端，客户端每个请求都会携带，服务器端进行解密等到user_id
              $rc4 = new Rc4();
              $C_userid_encryption_key = C('userid_encryption_key');
              $login_credentials = str_replace('+','|jia|',$rc4->authcode($check_account['id'],'ENCODE',$C_userid_encryption_key));
              $this->json_Response('success',$datas['pack_no'],array('local_login' => 1, 'login_credentials' => $login_credentials , "mobile" => $mobile,"u_id"=>$check_account['id'],"password"=>$password));*/

        }
    }


//*************************************个人中心*************************************************//
//*********************************************************************************************//

    //修改头像
    public function update_avatar($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid=$data['user_id'];

        $aa  = $this->img('useravatar');
        $arr['avatar']=$aa[0];
        $res = $this->member->where(array('id' => $uid))->save($arr);

        if($res!==false)
            $this->json_Response('success',$datas['pack_no'],array('avatar'=> $arr['avatar']));
        else
            $this->json_Response('failed',$datas['pack_no']);
    }
    //设置个人资料
    public function set_member($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        //$data['user_id']=262;
        $uid=$data['user_id'];

        $nickname = $data['value'];//修改参数
        //dump($data);//exit;
        switch($data['ziduan']){
            case 'gender':
                $res = $this->member->set_userinfo($data['ziduan'],$nickname,$uid);
                break;
          /*  case 'nickname':
                $res = $this->member->set_userinfo($data['ziduan'],$nickname,$uid);
                break;*/
            default:
                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Empty' => 'ziduan'));//无此字段参数
        }//dump($res);exit;

        if($res!==false)
            $this->json_Response('success',$datas['pack_no']);
        else
            $this->json_Response('failed',$datas['pack_no']);
    }

    /*   //获取用户的二维码
       public function get_user_qrcode($datas){
           $this->check_user_id($datas);
           $data = $this->get_datas($datas);
           $text="jadsfoifnoskfjm";

           $qrcode_filename = $this->build_user_qrcode($text);

           $this->json_Response('success',$datas['pack_no'],array('qr_code' => $qrcode_filename));
       }

       //生成二维码
       public function build_user_qrcode($text){
           $base_path = "./data/attachment/qrcode/"; //获取存储二维码目录
           $qrcode_filename = $base_path.$text."_qrcode.png";

           if(file($qrcode_filename)){
               return $qrcode_filename;
           }else{
               //生成二维码
               include("./phpqrcode/phpqrcode.php");
               $errorCorrectionLevel = "L";//容错级别
               $matrixPointSize = "5x5";//生成图片大小
               //Thinkphp在引用没有命名空间的类时要在前面加斜杆
               //\QRcode::png($uid, $qrcode_filename, $errorCorrectionLevel, $matrixPointSize,3);
               \QRcode::raw($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4);

              // dump($qrcode_filename);exit;
               return $qrcode_filename;
           }
       }*/

    //修改密码
    public function update_pwd($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);

        $uid =5;
        (!$oldpwd = check_pwd($data['oldpwd'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'oldpwd'));//原密码
        (!$newpwd = check_pwd($data['newpwd'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'newpwd'));//新密码

        //验证有没有此账户
        $check_pwd = $this->member->check_pwd($uid,st_md5($oldpwd));

        if($check_pwd){  //修改密码
            if(false !== $this->member->where(array('id'=>$uid))->setField(array('password'=>st_md5($newpwd)))){
                $this->json_Response('success',$datas['pack_no']);
            }else{
                $this->json_Response('failed',$datas['pack_no']);
            }
        }else{
            $this->json_Response('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'oldpwd_error'));//原密码错误
        }
    }

    //获取验证码
    public function get_yzm($datas){
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);

        $act = $data['act'];
        $dingdan = $data['dingdan'];
        if(!$dingdan){
            (!$mobile = check_mobile($data['mobile']))  && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile'));

            $start_time = strtotime('-1 hour');
            $map = array(
                'mobile' => $mobile,
                'create_time' => array('between',array($start_time,time())),
            );

            $code_record = M('Sms')->where($map)->count();
            ($code_record > 4) && $this->error('您发送的短信太频繁了，休息一下吧！'); //一小时最多发五条
            $code = rand(1000,9999); //四位验证码
            $data = array(
                'code' => $code,
                'mobile' => $mobile,
                'create_time' => time(),
                'member_id' => 0
            );

            M('Sms')->add($data);//记录到表
            session(array('code'=>$code,'expire'=>1800));
            cookie('code',$code,'1800');
        }
        if($act == 'pwdfind'){
            $mess = '您正在找回密码，验证码  '.$code.'，请在15分钟内按页 面提示提交验证码，切勿将验证码泄露于他人。如非本人操作请忽略！'.C('sms.sign') ;
        }else if($act == 'ok'){
            $mess ='尊敬的用户,您的订单编号为：'.$dingdan.'于'.date("Y-m-d H:i:s",time()).'已成功支付，您的订单将于第二天指定时间内为您配送，请保持电话畅通，谢谢您的配合！【合合商城】';
        }else{
            $mess ='感谢您使用合合商城系统认证服务，您的验证码  '.$code.'，请在15分钟内按页面提示提交验证码，切勿将验证码泄露于他人。'.C('sms.sign') ;
        }
        $ch = curl_init();
        $post_data = array(
            "account" => C('sms.account'),
            "password" => C('sms.password'),
            "destmobile" => $mobile,
            "msgText" => $mess,
            //  "sendDateTime" => time()
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        $post_data = http_build_query($post_data);
        //echo $post_data;
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_URL, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/http/sendBatchMessage');
        //$info=
        curl_exec($ch);
        curl_close($ch);
        // }
    }

    //立即购买
    public function zhifu($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $datas['user_id']=5;
        $uid = $datas['user_id'];
        (!$zftype = $data['zftype']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'zftype')); //支付方式
        (!$total = $data['total']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'total')); //支付总金额
        (!$dingdan = $data['dingdan']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'dingdan')); //订单号
        //dump($datas); echo $dingdan; echo $total; echo $zftype; exit;
        switch($zftype){
            case 2: //支付宝
                $this->process_ZFB_pay($datas,$uid,$zftype,$total,$dingdan);
                break;
            case 1: //微信
                $this->process_Wx_pay($datas,$uid,$zftype,$total,$dingdan);
                break;
        }
    }

    //处理支付宝充值
    private function process_ZFB_pay($datas,$user_id,$pay_type,$pay_money,$dingdan){
        if(!is_numeric($user_id) || !is_numeric($pay_type) || !is_numeric($pay_money))
            $this->print_error_status('params_error',$datas['pack_no'],array('Error_Param_Format' => 'pay_type,pay_money'));

        //获取用户实体
        $entity_user = D('Member')->where(array('id' => $user_id))->find();
        if($entity_user==NULL) $this->print_error_status('params_error',$datas['pack_no'],array('Error' => 'User Not Esixts'));


        $body = '合合商城用户支付';
        $name = '支付';
        $C_pay_type = C('pay_type');
        //装配实体信息
        $order_id = empty($dingdan) ? substr_replace(time().rand(10000,99999), 88,1,5) : $dingdan;

        $order_recharge = array(
            'order_id' => $order_id,
            'uid' => $user_id,
            'pay_type' => $pay_type,
            'order_money' => $pay_money,
            'body' => $body,
            'detail' => $entity_user['user_id']." 申请".$name."$pay_money 元，".$name."类型为 ".$C_pay_type[$pay_type]['type_name'],
            'zfb_sign' => '',
            'type' =>1,
            'add_time' => time()
        );
        // dump($order_recharge);exit;

        $C_base_pay_info = C('base_pay_info');
        $C_zfb_pay_config = C('zfb_pay_config');
        if($C_base_pay_info == NULL || $C_zfb_pay_config == NULL)
            $this->json_Response('failed',$datas['pack_no']);

        //构建签名参数
        $arr_sign = array();
        $arr_sign['service'] = $C_zfb_pay_config['service'];
        $arr_sign['partner'] = $C_zfb_pay_config['partner'];
        $arr_sign['_input_charset'] = $C_zfb_pay_config['_input_charset'];
        $arr_sign['notify_url'] = $C_base_pay_info['zfb_notify_url'];
        //$arr_sign['app_id'] = $C_zfb_pay_config['app_id'];
        $arr_sign['out_trade_no'] = $order_id;
        $arr_sign['subject'] = $order_recharge['body'];
        $arr_sign['payment_type'] = $C_zfb_pay_config['payment_type'];
        $arr_sign['seller_id'] = $C_zfb_pay_config['seller_id'];
        $arr_sign['total_fee'] = $order_recharge['order_money'];
        $arr_sign['body'] = $order_recharge['detail'];
        //dump($arr_sign);//exit;

        //生成签名 PayCommon /支付支持处理类
        $PayCommon = new \Think\PayCommon();
        $sign_str = $PayCommon->build_zfb_sign_str($arr_sign);
        $sign = $PayCommon->rsaSign($sign_str,'./pay_zfb/key/rsa_private_key.pem');
        //dump($sign); exit;
        //设置支付宝签名
        $order_recharge['zfb_sign'] = $sign;
        $arr_sign['sign'] = $sign;
        $arr_sign['sign_type'] = $C_zfb_pay_config['sign_type'];

        //### 生成待返回APP端的支付字符串 #
        $alipay_info = $PayCommon->build_zfb_sign_str($arr_sign);
        //dump($alipay_info); exit;
        //开户事务
        $this->_mod->startTrans();

        //持久化订单
        $OrderRecharge = D('OrderRecharge');
        $new_id = $OrderRecharge->add_order($order_recharge);

        if($new_id){
            if($this->order->where(array('uid'=>$user_id,'dingdan'=>$dingdan))->setField('Is_ok',2)){
                $this->_mod->commit();
                $this->json_Response('success',$datas['pack_no'],array('pay_info' => $alipay_info));
            }else{
                $this->_mod->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
        }else{
            $this->_mod->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //微信支付处理
    private function process_Wx_pay($datas,$user_id,$pay_type,$pay_money,$dingdan){
        if(!is_numeric($user_id) || !is_numeric($pay_type) || !is_numeric($pay_money))
            $this->print_error_status('params_error',$datas['pack_no'],array('Error_Param_Format' => 'pay_type,pay_money'));
        $this->_users = D('Member');
        //获取用户实体
        $entity_user = $this->_users->where(array('id' => $user_id))->find();
        if($entity_user==NULL) $this->print_error_status('params_error',$datas['pack_no'],array('Error' => 'User Not Esixts'));

        //存入数据库的充值金额转换
        //微信充值的单位是：分，所以这里要进行元转换 分除以100即为元;
        $order_money = $pay_money/100;

        $C_pay_type = C('pay_type');
        //装配实体信息
        $order_id = make_order_id('OrderRecharge');
        $order_recharge = array(
            'order_id' => $order_id,
            'uid' => $user_id,
            'pay_type' => $pay_type,
            'order_money' => $order_money,
            'fee_type' => 'CNY',
            'trade_type' => 'APP',
            'body' => '合合商城用户支付.',
            'detail' => $entity_user['user_id']." 申请支付 $order_money 元，充值类型为 ".$C_pay_type[$pay_type]['type_name'],
            'wx_sign' => '',
            'add_time' => time()
        );

        $C_base_pay_info = C('base_pay_info');
        $C_wx_pay_config = C('wx_pay_config');
        if($C_wx_pay_config == NULL || $C_base_pay_info == NULL)
            $this->json_Response('failed',$datas['pack_no']);

        //构造签名的参数
        $appid = $C_wx_pay_config['AppID'];
        $AppSecret = $C_wx_pay_config['AppSecret'];
        $mch_id = $C_wx_pay_config['mch_id'];
        $wx_key = $C_wx_pay_config['key'];

        $nonce_str = make_rand_str();//生成随机码
        $body = $order_recharge['body'];
        $out_trade_no = $order_recharge['order_id'];
        $total_fee = $pay_money;
        $spbill_create_ip = GetHostByName($_SERVER['SERVER_NAME']);//获取当前服务器IP地址 GetHostByName 此方法为PHP自带的获取当前服务器IP的方法
        $notify_url = $C_base_pay_info['wx_notify_url'];
        $trade_type = $order_recharge['trade_type'];

        //微信请求参数列表
        $arr_params['appid'] = $appid;
        $arr_params['mch_id'] = $mch_id;
        $arr_params['nonce_str'] = $nonce_str;
        $arr_params['body'] = $body;
        $arr_params['out_trade_no'] = $out_trade_no;
        $arr_params['total_fee'] = $total_fee;
        $arr_params['spbill_create_ip'] = $spbill_create_ip;
        $arr_params['notify_url'] = $notify_url;
        $arr_params['trade_type'] = $trade_type;

        //生成签名 PayCommon /支付支持处理类
        $PayCommon = new \Think\PayCommon();
        $sign = $PayCommon->build_sign($arr_params,$wx_key);
        //设置微信签名
        $order_recharge['wx_sign'] = $sign;
        $arr_params['sign'] = $sign;

        //开户事务
        $this->_users->startTrans();

        //持久化订单
        $OrderRecharge = D('OrderRecharge');
        $new_id = $OrderRecharge->add_order($order_recharge);

        if($new_id){

            //构造支付请求参数 XML字符串
            $wx_xml = $PayCommon->build_xml($arr_params);

            if($wx_xml!=''){

                //向微信支付网关发送支付请求
                $response_wx_xml_data = $PayCommon->send($C_wx_pay_config['pay_gateway'],$wx_xml);
                echo $response_wx_xml_data;

                if($response_wx_xml_data['return_code'] == 'SUCCESS' && $response_wx_xml_data['result_code'] == 'SUCCESS' && $response_wx_xml_data['appid'] == $arr_params['appid'] && $response_wx_xml_data['mch_id'] == $arr_params['mch_id']){

                    //获取微信返回的签名
                    $back_sign = $response_wx_xml_data['sign'];

                    //微信返回的预支付编号
                    $prepay_id = $response_wx_xml_data['prepay_id'];

                    //更新微信预支付编号
                    $OrderRecharge->change_field('wx_prepay_id',$prepay_id,$new_id);

                    //微信返回的签名不参与验证签名生成
                    unset($response_wx_xml_data['sign']);

                    //根据微信返回的信息生成验证签名
                    $back_params_sign = $PayCommon->build_sign($response_wx_xml_data,$wx_key);

                    //验证签名是否正确
                    if($back_sign == $back_params_sign){

                        //生成返回给APP端的信息
                        $response_App_datas = array();
                        $response_App_datas['appid'] = $appid;//APP ID
                        $response_App_datas['partnerid'] = $mch_id;//商户编号
                        $response_App_datas['prepayid'] = $prepay_id; //微信返回的预支付编号
                        $response_App_datas['package'] = 'Sign=WXPay';//package 微信规定用固定值
                        $response_App_datas['noncestr'] = make_rand_str();//生成随机字符串 必须全是字母
                        $response_App_datas['timestamp'] = time();//时间戳

                        //APP端支付签名
                        $response_APP_sign = $PayCommon->build_sign($response_App_datas,$wx_key);
                        $response_App_datas['sign'] = $response_APP_sign;

                        //用户支付订单编号  ###注意这个值APP端不需要传给支付宝接口###
                        $response_App_datas['order_id'] = $order_id;
                        if($this->order->where(array('uid'=>$user_id,'dingdan'=>$dingdan))->setField('Is_ok',2)){
                            $this->_users->commit();
                            $this->json_Response('success',$datas['pack_no'],array('response_App_datas'=>$response_App_datas));
                        }else{
                            $this->_users->rollback();
                            $this->json_Response('failed',$datas['pack_no']);
                        }
                    }else{
                        $this->_users->rollback();
                        $this->json_Response('failed',$datas['pack_no']);
                    }
                }else{
                    $this->_users->rollback();
                    $this->json_Response('failed',$datas['pack_no']);
                }
            }else{
                $this->_users->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
        }
    }

    /**
     *中亿首页
     */

    //首页
    public function index_info($datas){
        //显示城市
        $data = $this->get_datas($datas);
        //$datas['user_id']="9837";
        $city_id = $data['city_id'];
        $city_name = $data['city_name'];
        $page = $data['page'];
        $pagesize = $data['pagesize'];
        // $datas['user_id']=9834;
        $uid = $datas['user_id'];// dump($uid);exit;

        if($city_name){
            $city = $city_name;
            $where['name'] = array('like','%'.$city.'%');
            $where['type']=2;
           $dingwei= $this->place->where($where)->field('id')->find();//dump($dingwei);exit;
        } else{
            $city = '南昌';
            $data['latitude']='28.654852';
            $data['longitude']="115.928194";
        }//dump($city);exit;

        //栏目
        $article_cate=  $this->merchant_cate->where(array('status'=>1,'pid'=>0))->order('ordid,id')->field('id,name,img,url')->limit(8)->select();
        //dump($article_cate);  exit;
        //猜你喜欢
        //$likes= M('Merchant')->field('id,longitude,latitude')->order('ordid desc,id')->limit(3)->select();

        if($dingwei!==null){
            $lat=(String)$data['latitude'];
            $lng=(String)$data['longitude'];
            if(!$lat||$lat==null){
                $lat='28.654852';
                $lng="115.928194";
            }

            $array =getAround($lat, $lng, 10000000);

            if($dingwei['id']){
                $condition['city_id|province_id']=(int)$dingwei['id'];
            }
            $condition['status']=4;//营业中
            $condition['latitude']  = array(array('EGT',$array['minLat']),array('ELT',$array['maxLat']),'and');//(`latitude` >= minLat) AND (`latitude` <=maxLat)
            $condition['longitude'] = array(array('EGT',$array['minLng']),array('ELT',$array['maxLng']),'and');//(`longitude` >= minLng) AND (`longitude` <= maxLng)
           // dump($condition);exit;

           // $like=M('Merchant')->where($condition)->limit($Page->firstRow.','.$Page->listRows)->field('id,title,logo,xing,address,tel,longitude,latitude')->select();
            $like=M('Merchant')->where($condition)->field('id,title,logo,xing,address,tel,longitude,latitude')->select();

            foreach ($like as $k=>$v){
                $distance=getdistance($lng,$lat,$v['longitude'],$v['latitude']);
                $like[$k]['distance']= sprintf("%.2f", $distance/1000);

                if($distance>10000000){
                    unset($like[$k]);
                }
            }
            $new_like=array_sort($like, distance, $type = 'asc');//  dump($new_like);//exit;

            $pagesize=(int)$pagesize;$page=(int)$page;
            $start = ($page - 1) * $pagesize;
            $count = count($new_like);//dump($count);exit;

            $list = array_slice($new_like, $start, $pagesize);
        }else{
            //$new_like=null;
            $list=null;
        }
        $price_type = 0;
        if ($uid){
            $price = $this->member->where('id='.$uid)->getField('consume_redpacket');
            (int)C('pin_use_red') <= $price && $price_type = 1;
        }

        $this->json_Response('success',$datas['pack_no'],array('article_cate'=>$article_cate,'city'=>$city,'like_shop'=>$list,'type'=>$price_type));
    }




    /*   //选择城市
       public function select_city($datas){
           $data = $this->get_datas($datas);

           $province=M('Place')->where(array('type'=>1,'status'=>1))->field('id,name,bd_city_code')->order('ordid desc,id')->select();
           $province_id=$data['province_id'];
           if($province_id){
               $city=M('Place')->where(array('type'=>2,'status'=>1,'spid'=>$province_id))->field('id,name,bd_city_code')->order('ordid desc,id')->select();
           }

           $this->json_Response('success',$datas['pack_no'],array('province'=>$province,'city'=>$city));
       }*/

    //搜索店铺
    public function select_dp($datas){
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        (!$dp = $data['dp'])&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'dp'));
        (!$lat = (String)$data['latitude'])&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'dw'));
        (!$lng=(String)$data['longitude'])&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'dw'));

        $array =getAround($lat, $lng, 14000);

        $condition['title'] = array('like','%'.$dp.'%');
        $condition['status']=4;//营业中
        $condition['latitude']  = array(array('EGT',$array['minLat']),array('ELT',$array['maxLat']),'and');//(`latitude` >= minLat) AND (`latitude` <=maxLat)
        $condition['longitude'] = array(array('EGT',$array['minLng']),array('ELT',$array['maxLng']),'and');//(`longitude` >= minLng) AND (`longitude` <= maxLng)

        $like=M('Merchant')->where($condition)->field('id,title,logo,xing,address,tel,longitude,latitude')->select();

        foreach ($like as $k=>$v){
            $distance=getdistance($lng,$lat,$v['longitude'],$v['latitude']);
            $like[$k]['distance']= sprintf("%.2f", $distance/1000);

            if($distance>14000){
                unset($like[$k]);
            }
        }

        $list=array_sort($like, distance, $type = 'asc');
        if(!$list){
            $this->json_Response('success',$datas['pack_no'],array('list'=>null));
        }
       // dump($list);exit;

        $this->json_Response('success',$datas['pack_no'],array('list'=>$list));
    }

    /*
     * 自营
     */

    //查找商品
    public function select_good($datas){
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        (!$good_name = $data['good_name'])&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'good_name'));
        $where['title'] = array('like','%'.$good_name.'%');
        $where['status'] = 1;
        $list= M('Item')->where($where)->order('ordid desc,add_time desc,id')->field('id,title,price,img,sales')->select();

        $this->json_Response('success',$datas['pack_no'],array('list'=>$list));
    }

    //自营
    public function self_support($datas){
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);

        $classify_cate=$this->item_cate->where(array('status'=>1,"pid"=>0,'self_support'=>1))->order('ordid desc')->field('id,seo_title')->select();// dump($classify_cate);exit;
       // $classify=M("ItemCate")->where(array('status'=>1,"pid"=>$classify_cate['id']))->field('id,name')->order('ordid desc,id')->select();

        $item_cate_id = empty($data['item_cate_id']) ? $classify_cate[0]['id'] : $data['item_cate_id'];

        $str = 'status = 1 AND self_support = 1 AND spid LIKE \''.$item_cate_id.'|%\'';
        $classify_cate_ids=$this->item_cate->where($str)->field('id')->select();
//        echo M()->_sql();exit;
/*        if($item_cate_id){

        }else{
            $classify_cate_ids=$this->item_cate->where(array('status'=>1,'self_support'=>1,"spid"=>array('like',$classify_cate[0]['id'].'|%')))->field('id')->select();
        }*/

        $arr_ids = array_column($classify_cate_ids, 'id');
        $arr_ids[] = (string)$item_cate_id;

//        dump($arr_ids);exit;
//        dump($arr_ids);exit;
//        $arr_classify_cate_ids=$this->item_cate->where(array('pid'=>array('IN',$arr_ids)))->field('id')->select();
//        $arr_cate_ids = array_column($arr_classify_cate_ids, 'id');
        //dump($arr_cate_ids);exit;
       // $demo_goods=M('Item')->where(array('cate_id'=>array('IN',$arr_cate_ids),'status'=>1))->order('sales desc,id')->field('id,title,img,price,sales')->select();
        //dump($demo_goods);exit;
        $type=$data['type'];
        if(!empty($arr_ids)){
            if($item_cate_id){
                if($type==1){//销量降序排
                    $demo_goods=M('Item')->where(array('cate_id'=>array('IN',$arr_ids),'status'=>1))->order('sales desc,id')->field('id,title,img,price,sales,inventory')->select();
                }else if($type==2){//价格降序排
                    $demo_goods=M('Item')->where(array('cate_id'=>array('IN',$arr_ids),'status'=>1))->order('price desc,id')->field('id,title,img,price,sales,inventory')->select();
                } else if($type==3){//价格升序排
                    $demo_goods=M('Item')->where(array('cate_id'=>array('IN',$arr_ids),'status'=>1))->order('price asc,id')->field('id,title,img,price,sales,inventory')->select();
                } else{//默认后台排序
                    $demo_goods=M('Item')->where(array('cate_id'=>array('IN',$arr_ids),'status'=>1))->order('ordid desc,id')->field('id,title,img,price,sales,inventory')->select();
                }
            }else{
                $demo_goods=M('Item')->where(array('cate_id'=>array('IN',$arr_ids),'status'=>1))->order('ordid desc,id')->field('id,title,img,price,sales,inventory')->select();
            }
        }else{
            $demo_goods=null;
        }
       // dump($demo_goods);exit;

        $start_price=C('Pin_start_price');
        $freight=C('Pin_freight');

        //dump($classify_cate); dump($classify);dump($demo_goods);exit;
        if(!$demo_goods){
            $this->json_Response('success',$datas['pack_no'],array('list'=>null));
        }else{
            $this->json_Response('success',$datas['pack_no'],array('list'=>$demo_goods,'classify'=>$classify_cate,'start_price'=>$start_price,'freight'=>$freight));
        }
    }

    /*
     * 地址问题
     */

    //添加 修改地址
    public function upornew_address($datas){
        // dump(123);exit;
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        // $data['user_id']=9832;
        $uid = $data['user_id'];
        $check_user = $this->member->get_info($uid);
        //dump($check_user);exit;

        $arr = array();
        (!$arr['shperson'] = $data['shperson']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'shperson')); //收货人
        (!$arr['mobile'] = check_mobile($data['mobile'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile'));
        (!$arr['province'] = $data['province']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'province')); //省
        (!$arr['city'] = $data['city']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'city')); //市
        (!$arr['area'] = $data['area']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'area')); //区
        (!$arr['address'] = $data['address']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'address')); //详细地址

        $arr['uid'] = $uid;
        $arr['addtime'] = time();
        //(!$arr['city_id'] = $data['city_id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'city_id'));
        // dump($arr); exit;
        $id = $data['id']; //修改地址id
        $Member = M('MemberGoodsaddress');
        $arr['status'] = $data['status'];  //地址状态1:默认地址   2：普通地址
        $Member->startTrans();
        if(!$id){  //添加地址
            $count_city = $Member->where(array('uid' => $uid, 'status' => array('neq', 0)))->count();//验证用户地址数量不能大于10

            if($count_city > 10){
                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'address_count'));
            }

            $rs = $Member->data($arr)->add();
            if($arr['status'] ==1){
                if($rs){
                    if($Member->where(array('id' => array('neq', $rs), 'status' => 1, 'uid' => $uid))->find()){

                        $rs1 = $Member->where(array('id' => array('neq', $rs), 'status' => 1, 'uid' => $uid))->setField('status',2); //更改其他城市状态

                        if($rs1){
                            $Member->commit();
                            $this->json_Response('success',$datas['pack_no'],array('id'=>$rs));
                        }else{
                            $Member->rollback();
                            $this->json_Response('failed',$datas['pack_no']);
                        }
                    }else{
                        $Member->commit();
                        $this->json_Response('success',$datas['pack_no'],array('id'=>$rs));
                    }
                }else {
                    $Member->rollback();
                    $this->json_Response('failed', $datas['pack_no']);
                }
            }else if($arr['status'] ==2){
                if($rs){
                    $Member->commit();
                    $this->json_Response('success',$datas['pack_no'],array('id'=>$rs));
                }else{
                    $Member->rollback();
                    $this->json_Response('failed',$datas['pack_no']);
                }
            }

        }else{ //修改

            if (false !== $Member->where(array('id'=>$id,'uid'=>$uid))->data($arr)->save()){
                if($arr['status'] ==1){
                    if($Member->where(array('id' => array('neq', $id), 'status' => 1, 'uid' => $uid))->find()){
                        $rs1 = $Member->where(array('id' => array('neq', $id), 'status' => 1, 'uid' => $uid))->setField('status',2); //更改其他城市状态
                        if($rs1){
                            $Member->commit();
                            $this->json_Response('success',$datas['pack_no']);
                        }else{
                            $Member->rollback();
                            $this->json_Response('failed',$datas['pack_no']);
                        }
                    }else{
                        $Member->commit();
                        $this->json_Response('success',$datas['pack_no']);
                    }
                }

            }else{
                $Member->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
        }
    }

    //省市区
    public function pro_city_area($datas){
        $data = $this->get_datas($datas);
        $province_id=$data['province_id'];
        $city_id=$data['city_id'];

        $province=M('Area')->where(array('pid'=>0))->field('id,name')->select();
        if($province_id){
            $city=M('Area')->where(array('pid'=>$province_id))->field('id,name')->select();
        }
        if($city_id){
            $area=M('Area')->where(array('pid'=>$city_id))->field('id,name')->select();
        }

        $this->json_Response('success',$datas['pack_no'],array('province'=>$province,'city'=>$city,'area'=>$area));
    }

    //获取地址
    public function get_address($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);

        // $data['user_id']=9840;
        $uid = $data['user_id'];
        $list = M('MemberGoodsaddress')->where(array('uid' => $uid, 'status' => array('neq', 0)))->order('status,id desc')->field('id,shperson,mobile,province,city,area,address,status')->select();
        //dump($list);exit;
        $this->json_Response('success',$datas['pack_no'],array('list'=>$list));
    }

    //删除地址
    public function del_address($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        $uid = $datas['user_id'];
        (!$id = $data['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'id'));
        $Member = M('MemberGoodsaddress');
        $del = $Member->where(array('id'=>$id,'uid'=>$uid))->save(array('status'=>0));
        if($del)
            $this->json_Response('success',$datas['pack_no']);
        else
            $this->json_Response('failed',$datas['pack_no']);
    }

    //设置默认地址
    public function set_def_address($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        (!$id = $data['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'id'));

        $uid =$data['user_id'];
        $id = $data['id']; //修改地址id
        $Member = M('MemberGoodsaddress');
        $Member->startTrans();
        $res = $Member->where(array('uid'=>$uid,'id'=>$id))->setField('status',1);
        if($res){
            if($Member->where(array('id' => array('neq', $id), 'status' => 1, 'uid' => $uid))->find()){
                $rs1 = $Member->where(array('id' => array('neq', $id), 'status' => 1, 'uid' => $uid))->setField('status',2); //更改其他城市状态
                if($rs1){
                    $Member->commit();
                    $this->json_Response('success',$datas['pack_no']);
                }else{
                    $Member->rollback();
                    $this->json_Response('failed',$datas['pack_no']);
                }
            }else{
                $Member->commit();
                $this->json_Response('success',$datas['pack_no']);
            }
        }else{
            $Member->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    /*
     * 订单
     */

  /*  //订单地址选择
    public function order_address($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        (!$id = $data['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'id'));

        $uid =$data['user_id'];
        $id=$data['id'];
        $address = M('MemberGoodsaddress')->where(array('uid' => $uid,'id'=>$id, 'status' => array('neq', 0)))->field('id,shperson,mobile,province,city,area,address')->find();

        $this->json_Response('success',$datas['pack_no'],array('address'=>$address));
    }*/
    //选好了
    public function goods_ok($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        // $data['user_id']=9837;

        $uid =$data['user_id'];
        $shopname=C('Pin_site_title');
        $freight=C('Pin_freight');

        $uid =$data['user_id'];
        $id=$data['id'];

        if($id){//设为默认地址
            $res = M('MemberGoodsaddress')->where(array('uid'=>$uid,'id'=>$id))->setField('status',1);
            $rs1 = M('MemberGoodsaddress')->where(array('id' => array('neq', $id), 'status' => 1, 'uid' => $uid))->setField('status',2);
        }else{
            $address = M('MemberGoodsaddress')->where(array('uid' => $uid, 'status' =>1))->field('id,shperson,mobile,province,city,area,address')->find();
            if(!$address){
                $address = M('MemberGoodsaddress')->where(array('uid' => $uid, 'status' =>2))->order('id')->field('id,shperson,mobile,province,city,area,address')->find();
                if(!$address){
                    $this->json_Response('success',$datas['pack_no'],array('address'=>null,'shopname'=>$shopname,'freight'=>$freight));
                }
            }
        }

        $this->json_Response('success',$datas['pack_no'],array('address'=>$address,'shopname'=>$shopname,'freight'=>$freight));
    }


    //提交订单
    public function set_order($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
       // $data['user_id']=9837;

        $uid= $data['user_id'];
        $arr = array();
        $now = time();
        (!$aid = $data['aid']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'aid'));	//地址关联id
        (!$gwc_item = $data['gwc_item']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'gwc_item'));	//购物车
        (!$totalprices = $data['totalprices']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'totalprices'));	//支付总金额

        //金豆余额与用户余额
        $balance=$this->member->where(array('id'=>$uid))->field('prices,give_redpacket')->find();

        $arr['memos'] = $data['memos'];	//买家备注
        $arr['dingdan'] = substr_replace(time().rand(10000,99999), 88,1,5);//订单号
        $arr['status'] = 0;
        $arr['type'] = 1;
        $arr['aid'] = $aid;
        $arr['totalprices'] = $totalprices;
        $arr['uid'] = $uid;
        $arr['merchant_id'] =0;
        $arr['addtime'] = time();
        $gwc_item = $data['gwc_item']; //获得用户购物车商品

        $this->order->startTrans();
        $add = $this->order->data($arr)->add();//添加到订单表

        if($add){//订单详情

            foreach($gwc_item as $item =>$v){
                $list = $this->item->where(array('id'=>$v['jid']))->field('price,img,title')->find(); //获取具体商品信息
                $map[] = array(
                    'oid' => $add,
                    'uid' => $uid,
                    'prices' => $list['price'],
                    'img' => $list['img'],
                    'title' => $list['title'],
                    'nums' => $v['nums'],
                    'jid' => $v['jid'],
                );
            }

            if(false !== $this->OrderList->addAll($map)){
                $list2 = array('dingdan'=>$arr['dingdan'],'totalprices'=>$totalprices,'prices'=>$balance['prices'],'give_redpacket'=>$balance['give_redpacket']);
                if($list2){
                    $this->order->commit();
                    $this->json_Response('success',$datas['pack_no'],array('list'=>$list2));
                }else{
                    $this->order->rollback();
                    $this->json_Response('failed',$datas['pack_no']);
                }
            }else{
                $this->order->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
        }else{
            $this->order->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //二维码(获取店铺金豆)
    public function qrcode_get_redpacket($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        //$data['user_id']=9834;
        $uid=$data['user_id'];

        // $host=$_SERVER["HTTP_HOST"];
        $order=make_order_id('Address');
        vendor("phpqrcode.phpqrcode");

        $device_tokens=$datas['token'];
        $data ='{"client_id":' . '"' .$uid .'"' . ',"out_trade_no":'. '"' . $order  .'"' . ',"price":'. '"'. $data['price']  .'"'. ',"device_tokens":'. '"' . $device_tokens .'"'. ',"type":'. '"3"'.'}';
        // $data ='{"client_id":' . '"' .$uid .'"' . ',"out_trade_no":'. '"' . $order  .'"' . ',"price":'. '"'. $data['price']  .'"'.'}';
        //dump($data);exit;
        $level = 'L';
        $size = 4;
        $path = "data/attachment/qrcode/";
        // 生成的文件名
        $fileName = $path.$uid.time().'.png';
        \QRcode::png($data, $fileName, $level, $size);

        $this->json_Response('success',$datas['pack_no'],array('qr_code' => $fileName));
    }

    //我是商家
    public function is_shangjia($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        $uid=$data['user_id'];

        $is_shj=$this->member->where(array('id'=>$uid))->getField('types');//dump($is_shj);exit;
        if($is_shj==2){
            $this->json_Response('success',$datas['pack_no']);
        }else{
            $this->json_Response('failed',$datas['pack_no'],array('is_shj' =>'is_shj'));
        }
    }

    //生成二维码(邀请好友)
    public function invite_friend($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        //$data['user_id']=9837;
        $uid=$data['user_id'];
        $mobile=$this->member->where(array('id'=>$uid))->getField('mobile');//dump($mobile);exit;
        $android=C('pin_site_url')."/Download/mifengwang.apk";
        $ios=C('pin_site_url')."/Download/app-ios.encrypted_signed_Aligned.apk";

        vendor("phpqrcode.phpqrcode");
        //$data ='{"iiuv_mobile":'. '"' . $mobile  .'"' . ',"android":'. '"' . $android  .'"' . ',"ios":'. '"' . $ios  .'"' . ',"type":'. '"2"'.'}';
        $data ='http://'.C('pin_site_url').'/index.php?m=Home&c=News&a=share&mobile='.$mobile;

        // dump($data);exit;
        $level = 'L';
        $size = 4;
        $path = "data/attachment/inviteqrcode/";
        // 生成的文件名
        $fileName = $path.$uid.'.png';
        if (!is_file($fileName)){
            \QRcode::png($data, $fileName, $level, $size);
        }

        $this->json_Response('success',$datas['pack_no'],array('iiuv_code' => $fileName));
    }


    //我的会员
    public function my_member($datas){
        $this->check_user_id($datas);//判断用户是否登录
        $data = $this->get_datas($datas);//获取数据
        //$data['user_id']=10182;
        $uid=$data['user_id'];
        $page = $data['page'];
        $pagesize = $data['pagesize'];

        $mobile=$this->member->where(array('id'=>$uid))->getField('mobile');


        $count =$this->member->where(array('iiuv'=>$mobile))->count();
        $Page = new \Think\Page_App($count,$pagesize,$page);
        $my_member=$this->member->where(array('iiuv'=>$mobile))->limit($Page->firstRow.','.$Page->listRows)->field('id,nickname,mobile,avatar')->select();
      // dump($my_member);exit;
        if($my_member){
            $this->json_Response('success',$datas['pack_no'],array('my_member' => $my_member));
        }else{
            $this->json_Response('success',$datas['pack_no'],array('my_member' =>null));//无会员
        }
    }

    //二维码（我的-直接进入店铺）
    public function qrcode_merchant($datas){
        $this->check_user_id($datas);//判断用户是否登录
        $data = $this->get_datas($datas);//获取数据

        $merchant_id=$data['merchant_id'];

        vendor("phpqrcode.phpqrcode");
        $data ='{"merchant_id":'. '"' . $merchant_id  .'"' . ',"type":'. '"1"' .'}';
        // dump($data);exit;
        $level = 'L';
        $size = 4;
        $path = "data/attachment/crossingqrcode/";
        // 生成的文件名
        $fileName = $path.$merchant_id.time().'.png';
        \QRcode::png($data, $fileName, $level, $size);

        $this->json_Response('success',$datas['pack_no'],array('xj_code' => $fileName));
    }

    //扫描二维码(首页-进入店铺)
    public function qrcode_sao_merchant($datas){
        $this->check_user_id($datas);//判断用户是否登录
        $data = $this->get_datas($datas);//获取数据

        $type=$data['type'];
        if($type==3){
            $this->json_Response('failed',$datas['pack_no'],array('qrcode_other' => 'qrcode_merchant'));//请到我是商家的商家账号扫描
        }else if($type==2){
            $this->json_Response('failed',$datas['pack_no'],array('qrcode_other' => 'qrcode_other'));//请用浏览器进行扫描
        }else if($type==1){
            $merchant_id=$data['merchant_id'];//上家id
            $exist_merchant= $this->merchant->where(array('id'=>$merchant_id))->find();//dump($exist_pid);exit;
            if(!$exist_merchant){
                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'no_merchant'));//不存在此店铺
            }else{
                $this->json_Response('success',$datas['pack_no'],array('id' => $merchant_id,'type' => $type));
            }
        }
    }

    //更换手机号
    public function change_mobile($datas){
        $this->check_user_id($datas);//判断用户是否登录
        $data = $this->get_datas($datas); //从请求中获取数据
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
         //$data['user_id']=9837;
        $uid=$data['user_id'];

        $arr = array();
        (!$arr['mobile'] = check_mobile($data['mobile']))&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile'));//手机号码格式错误或为空
        (!$code = $data['code']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'code'));//请填写验证码
        (!$password = check_pwd($data['password'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'password'));//由大小写字母跟数字组成并且长度在3-16字符直接

        $yz_code=$this->sms->where(array('mobile'=>$arr['mobile']))->order('create_time desc')->field('code')->select();
        $yz_code1=$yz_code[0]['code'];
        if($code!==$yz_code1){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'code_err'));//验证码错误
        }

        $old_mobile=$this->member->where(array('id'=>$uid))->field('mobile,password');
        if($old_mobile['password']!==st_md5($password))//
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'err_password'));//登陆密码错误

        if($old_mobile['mobile']==$arr['mobile'] )//
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'old_mobile'));//原来的手机


        if($this->member->where(array('mobile'=>$arr['mobile']))->getField('id'))//验证手机号码是否已注册
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'mobile_error'));


        $this->member->startTrans();

        $res = $this->member->where(array('id'=>$uid))->save($arr);

        if($res){
            $this->json_Response('success',$datas['pack_no']);
        }else{
            $this->member->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }




























}