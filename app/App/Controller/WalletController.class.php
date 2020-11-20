<?php
namespace App\Controller;
use Think\Rc4;

/*
 *我的钱包
 *
 */

class WalletController extends ApiController
{
	
    public function _initialize()
    {
        parent::_initialize();
//      $this->member = D('Member');    //用户表
////      $this->place=D('Place');//地区表
//      $this->merchant=D('Merchant');//店铺
//      $this->MemberBankcard=D('MemberBankcard');//用户银行卡
//      $this->account = D('Account');//会员明细表
//      $this->withdraw_member = D('WithdrawMember');//会员提现申请表
//      $this->withdraw_merchant = D('WithdrawMerchant');//商家提现申请表
//      $this->withdraw_qd = D('WithdrawQd');//商家提现申请表
//      $this->account_shop = D('AccountShop');//商家明细表
//      $this->member_jbp = D('MemberJbp');//聚宝盆订单列表
    } 
    
    
    //我的钱包      20001
    public function wallet($datas){
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $mem_info=$this->member->field('gold_acer,silver_coin,gold_fruit,prices')->find($data['user_id']);
        $this->json_Response('success',$datas['pack_no'],array('mem_info'=>$mem_info));
    }

    //我的钱包》银行卡展示   20002
    public function w_bank($datas){
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $b_card = D('MemberBankcard')->field('id,nums,name,title,add_time')
            ->where(array('member_id'=>$data['user_id'],'status'=>1))
            ->order('add_time desc,id desc')->select();//title分行名
        foreach($b_card as $k=>$v){
            //  **** 加密卡号 ***
            $b_card[$k]['nums'] = substr($v['nums'], 0, 4) . '********' . substr($v['nums'], -4);
        }
        $this->json_Response('success',$datas['pack_no'],array('b_card'=>$b_card));

    }

    //添加银行卡    20003
    public function add_bank($datas){
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        $C_userid_encryption_key=C('USERID_ENCRYPTION_KEY');
		
		
		$header=apache_request_headers();
		//dump($token);exit;
		file_put_contents('text.html',json_encode($header));
		
		$token=trim(str_replace('Bearer','',$header['authorization']));
		if($token){
			
		}
		else{
			echo 33;exit;
			 http_response_code(401);
		}
		$cate_list=M('item_cate')->where(array('status'=>1,'is_index'=>1))->field('id,img as url,name')->select();
        $array=array(
        'title'=>' ',
        'img'=>M('ad')->where(array('status'=>1))->order('ordid desc')->getField('img',true),
        'cate_list'=>$cate_list
        );
		//http_response_code(401);//
		$header=apache_request_headers();
        //file_put_contents('test.html',json_encode(apache_request_headers()));
		//dump(file_get_contents('test.html'));exit;
		// http_response_code(401);//设置鉴权
        $token=$header['authorization'];
		$token=trim(str_replace('Bearer','',$token));
		//dump(array($token,session('token')));exit;
//dump(session('token'));exit;
		if(!$token){
			//dump(array($token,session('token')));
			http_response_code(401);exit;
		}
		else{

		}
		//$str="Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjU5Nzg1MmFjOGRjYmQ4MWViM2I1Mzk2MSIsImlhdCI6MTU1Nzk4ODc4NiwiZXhwIjoxNTU3OTkyMzg2fQ.yQ8sl8C6E01Xxz8fh3JbgdY2OQRq23JCdmjic5A3z5g";
        //dump(trim(str_replace('Bearer','',$str)));exit;
        $this->json_Response('success',$datas['pack_no'],$array);
        
    }

    //解绑银行卡    20004
    public function del_bank($datas){
       //$this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $data=array(
	        0=>array(
	        	'name'=>'青椒小炒肉',
	        	'price'=>15,
	        	'types'=>1,
	        	'images'=>'https://www.skyvow.cn/uploads/KYzlMtqwg3IYNQE7sAyYQo8MHurqXZO8ujfQ7RJSUtVT2EEHvDZTKLdDxt45iRuzXqJjG5jrx9LeVfi7XNqovm4veqXieOkc81556111819000.png',
	        	'create_at'=>time()
	        ),
	        1=>array(
	        'name'=>'鱼香肉丝',
	        'price'=>12,
	        'types'=>1,
	        'images'=>'https://www.skyvow.cn/uploads/jVZPF7ySxYrAkFbiejpXb83QfAERVHcO2GnvCRkt3uX7HsnyoQmj1556111843000.png',
	        'create_at'=>time()
	        )
        );
        dump($data);exit;
        $card=M('items')->addAll($data);
        
        if($card){
            $this->json_Response('success',$datas['pack_no']);
        }
        else{
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //银楼   20010
    public function silver_store($datas)
    {
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid = $data['user_id'];
        $nums = $data['nums'];
        $now = time();
        $member = $this->member->field('gold_acer,silver_coin,vips,paypassword')->find($uid);
//        $vip = $member['vips'];
        $vip = 3;
        ($vip == 1) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您还不是掌柜,请升级掌柜在来置换')); //您还不是掌柜,请升级掌柜在来置换
        (!$nums||$nums < 0) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确的元宝数')); //请输入正确的元宝数
        ($member['gold_acer'] < $nums) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '元宝余额不足')); //元宝余额不足
//        ($nums > 300) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '每次购买的个数不超过300元宝')); //每次购买的个数不超过300元宝
        (!$member['paypassword']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您还未设置支付密码')); //您还未设置支付密码
        ($member['paypassword'] !== st_md5($data['pos'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确支付密码')); //请输入正确支付密码
        $start = M();
        $start->startTrans();
        //计算当天时间
        $time_str = date('Y-m-d', $now);//今天0点
        $time_start = strtotime($time_str);//时间戳s
        $time_end = $time_start + 24 * 3600;
        $time_rule['add_time'] = array('between', array($time_start, $time_end));
        $time_rule['account_type'] = 5;//银楼置换
        $time_rule['type'] = 2;//变化的金额
        //等级规定每日所换银币数量
        $vip_max_nums = M("grade_rule")->where("id='$vip'")->getField("max_acer_nums");//当前级别最大元宝限制
        $account_nums = abs($this->account->where($time_rule)->getField("sum(totalprices)"));//当天元宝总置换金额
        $account_nums += $nums;
        $flag = $vip_max_nums < $account_nums;
//        $number = $vip_max_nums;
//        $str = vsprintf("当前级别最高可置换%u元宝",$number);
        $flag && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '当前级别最高可置换'.$vip_max_nums.'元宝')); //当前级别最高可置换元宝

        $yb_nums = $nums * 100;
        //减元宝&&加银币
        $save = [
            'gold_acer' => $member['gold_acer'] - $nums,
            'silver_coin' => $member['silver_coin'] + $yb_nums,
        ];
        $res_self = $this->member->where("id=$uid")->save($save);
        if (!$res_self) {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
        //$type,$uid,$totalprices,$change_desc,$add_time,$oid=0,$account_type=0,$account_nums,$attach_field=0
        //减元宝&&加银币明细
        $recharge[] = account_arr(2, $uid, '-' . $nums, '银楼置换', $now, 0, 5);
        $recharge[] = account_arr(4, $uid, $yb_nums, '银楼置换', $now, 0, 5);
        $res_account = $this->account->addAll($recharge);

        if ($res_account) {
            $start->commit();
            $this->json_Response('success',$datas['pack_no']);
        } else {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //金果回购   20011
    public function fruit_recycle($datas)
    {
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid = $data['user_id'];
        $nums = $data['nums'];
        $now = time();
        $member = $this->member->field('gold_fruit,prices,paypassword')->find($uid);

        (!$nums||$nums < 0) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确的金果数')); //请输入正确的金果数
        ($member['gold_fruit'] < $nums) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '金果余额不足')); //金果余额不足
        (!$member['paypassword']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您还未设置支付密码')); //您还未设置支付密码
        ($member['paypassword'] !== st_md5($data['pos'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确支付密码')); //请输入正确支付密码
        $start = M();
        $start->startTrans();
        $prices = $nums * C('pin_jg_scj');
        //减元宝&&加银币
        $save = [
            'gold_fruit' => $member['gold_fruit'] - $nums,
            'prices' => $member['prices'] + $prices,
        ];
        $res_self = $this->member->where("id=$uid")->save($save);
        if (!$res_self) {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
        //$type,$uid,$totalprices,$change_desc,$add_time,$oid=0,$account_type=0,$account_nums,$attach_field=0
        //减金果&&加余额明细
        $recharge[] = account_arr(3, $uid, '-' . $nums, '金果回购', $now, 0, 6);
        $recharge[] = account_arr(1, $uid, $prices, '金果回购', $now, 0, 6);
        $res_account = $this->account->addAll($recharge);

        if ($res_account) {
            $start->commit();
            $this->json_Response('success',$datas['pack_no'],array('member'=>$member['gold_fruit']));
        } else {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //金果转好友 && 元宝转好友   20012
    public function transfer_to_friend($datas)
    {
        $res = $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid = $data['user_id'];
        $transfer_type = $data['type'];//  1金果  2元宝
        if(!in_array($transfer_type,[1,2])){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '系统繁忙'));
        }
        $nums = $data['nums'];
        if ($transfer_type == 1){
            $pin_jg_sxf=C('pin_jg_sxf')/100;//金果转好友手续费%
            $dec_nums=$nums*($pin_jg_sxf+1);
            $type = 3;
        }else{
            $pin_yb_sxf=C('pin_yb_sxf')/100;//元宝转好友手续费%
            $dec_nums=$nums*($pin_yb_sxf+1);
            $type = 2;
        }
        $now = time();
        $account=$this->account;//用户币种明细表
        $member = $this->member->field('gold_fruit,gold_acer,paypassword,mobile')->find($uid);//己方
        //返回己方金果  元宝  数
        $info = $this->member->where(array('mobile'=>$data['mobile']))->find();//对方

        //不允许3s内重复操作
        $map=['totalprices'=>$nums,'uid'=>$uid,'type'=>$type,'add_time'=>['gt',$now-3]];
        $over_order=$account->where($map)->count();
        ($over_order)&&$this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请勿重复提交'));//请勿重复提交

        (!$info || !check_mobile($data['mobile'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '手机号码格式不正确'));//手机号码格式不正确
        ($info['status'] == 0) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '对方账号已被冻结'));//对方账号已被冻结
        ($member['mobile'] == $data['mobile']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请确定对方手机号码是否正确'));//请确定对方手机号码是否正确
        (!$nums||$nums < 0) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确的金额')); //请输入正确的金额
        if($transfer_type == 1){
            ($member['gold_fruit'] < $nums) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '金果余额不足')); //金果余额不足
        }else{
            ($member['gold_acer'] < $nums) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '元宝余额不足')); //元宝余额不足
        }
        (!$member['paypassword']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您还未设置支付密码')); //您还未设置支付密码
        ($member['paypassword'] !== st_md5($data['pos'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确支付密码')); //请输入正确支付密码

        if($transfer_type==1){
            $field='gold_fruit';$memos='金果互转';$type=3;//币种1工资2金元宝3金果4银币'
        }else{
            $field='gold_acer';$memos='元宝互转';$type=2;
        }
        $start = M();
        $start->startTrans();

        $res_self=$this->member->where(array('id'=>$uid))->setDec($field,$dec_nums);//己方减金果或元宝
        $res_other=$this->member->where(array('id'=>$info['id']))->setInc($field,$nums);//对方加金果或元宝
        if(!$res_self|| !$res_other){
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
        //己方流水、对方明细   //$type,$uid,$totalprices,$change_desc,$add_time,$oid=0,$account_type=0,$account_nums,$attach_field=0
        $recharge[] = account_arr($type, $uid,'-'.$dec_nums, $memos, $now,0,3,$data['mobile']);//减金果或元宝
        $recharge[] = account_arr($type, $info['id'],$nums, $memos, $now);//加金果或元宝

        $res_account=$account->addAll($recharge);
        if($res_account){
            $start->commit();
            $this->json_Response('success',$datas['pack_no']);
        }else{
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //币种展示 20005。item_type1金元宝 2银元宝 3金果 4余额 5金币 6银币
//    public function w_all_bz($datas){
//        $this->check_user_id($datas);//验证用户ID是否存在
//        $data = $this->get_datas($datas);
//        (!$data['item_type']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => 'item_type')); //币种类型
//
//        switch($data['item_type']){
//            case 1:$bz='gold_acer';break;
//            case 2:$bz='silver_acer';break;
//            case 3:$bz='gold_fruit';break;
//            case 4:$bz='prices';break;
//            case 5:$bz='gold_coin';break;
//            case 6:$bz='silver_coin';break;
//            default: echo "No find";
//        }
//        $prices_bz= D('Member')->where('id='.$data['user_id'])->getField($bz);
//        $list_liu=D('MemberRecharge')
//            ->field('add_time,totalprices,memos,type')
//            ->where(array('dingdan'=>0,'member_id'=>$data['user_id'],'item_type'=>$data['item_type']))->select();
//        $this->json_Response('success',$datas['pack_no'],array('prices_bz'=>$prices_bz,'$list_liu'=>$list_liu));
//    }

    //会员  && 区代  余额提现    20006
    public function w_extract($datas){
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid = $data['user_id'];
        $time = time();
        $nums = $data['nums'];
        $tx_sxf   = C('pin_tx_sxf');//余额提现手续费
        $tx_db_je = C('pin_tx_db_je')*10000;//单笔最高
        $tx_mr_je = C('pin_tx_mr_je')*10000;//每日最高

        $member = $this->member->where(array('id'=>$data['user_id']))->field("id,paypassword,prices,vips,is_qd")->find();
        $type = $member['is_qd'];//1  是区代   0 不是区代
        if($type == 1){  //区代
            $dec_nums = $nums;
        }else{ //会员
            $dec_nums = $nums + $tx_sxf;
        }
        $card = $this->MemberBankcard->where(array('member_id'=>$data['user_id'],'status'=>1))->find();
        if($card){
            ($member['vips'] < 2) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '亲,请升级掌柜再来提现')); //请升级掌柜再来提现
            (!$nums||$nums < 0) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确的金额')); //请输入正确的金额
            ($nums < 100 || $nums > $tx_db_je) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => "单笔最低提现100元  最高提现".$tx_db_je.'元'));
            ($member['prices'] < $nums) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您的余额不足')); //您的余额不足
            (!$member['paypassword']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您还未设置支付密码')); //您还未设置支付密码
            ($member['paypassword'] !== st_md5($data['pos'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确支付密码')); //请输入正确支付密码

            $start = M();
            $start->startTrans();
            //验证每日提现金额上限
            $time_str = date('Y-m-d', $time);//今天0点
            $time_start= strtotime($time_str);//时间戳s
            $time_end= $time_start+24*3600;
            $time_rule['add_time'] =  array('between',array($time_start,$time_end));
            $time_rule['uid'] = $data['user_id'];
            $time_rule['status']=array('in',array(1,3));
            if($type == 1){
                $last = $this->withdraw_qd->where($time_rule)->field('SUM(totalprices) total_prices')->select();
            }else{
                $last = $this->withdraw_member->where($time_rule)->field('SUM(totalprices) total_prices')->select();
            }
            $amount = $last[0]['total_prices'];
            $amount += $nums;
            ($amount > $tx_mr_je) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => "每日最高提现".$tx_mr_je.'元'));



            $da['uid']=$member['id'];
            $da['member_name'] = $card['member_name'];//持卡人姓名
            $da['nums']=$card['nums'];//卡号
            $da['name']=$card['name'];//银行名称
            $da['province']=$card['province'];//省
            $da['title']=$card['title'];//分行
            $da['city']=$card['city'];//市
            $da['district']=$card['district'] ? $card['district'] : "";//区
            $da['add_time']=time();
            $da['status']=1;//0表示未审核 1为驳回 2为通过
            $da['memos'] = $data['memos'] ? $data['memos'] : "";//提现留言
            $da['totalprices'] = $nums;
//            $da['bankcard_id']=$card['id'];//银行卡id
            if($type == 1){
                $yd = $this->withdraw_qd->data($da)->add();	//生成提现申请
                $price = $this->member->where(array('id'=>$member['id']))->setDec('prices',$dec_nums);//扣区代余额
            }else{
                $da['service_charge']=$tx_sxf;//用户提现手续费,
                $yd = $this->withdraw_member->data($da)->add();	//生成提现申请
                $price = $this->member->where(array('id'=>$member['id']))->setDec('prices',$dec_nums);//扣会员余额
            }
            if(!$yd|| !$price){
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
            //提现流水   //$type,$uid,$totalprices,$change_desc,$add_time,$oid=0,$account_type=0,$account_nums,$attach_field=0
            $memos=($type == 1)?"区代余额提现":"余额提现";
            $data_account=account_arr(1,$uid,'-'.$dec_nums,$memos,$time);//明细
            $res_account=$this->account->add($data_account);
            if($res_account){
                $start->commit();
                $this->json_Response('success',$datas['pack_no']);
            }else{
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
        }else{
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请先添加银行卡'));//请勿重复提交
        }
    }

    //商家金果提现 && 商家元宝提现    20007
    public function merchant_tixian($datas){
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid = $data['user_id'];
        $type = $data['type'] ? $data['type'] : 1;//1 元宝提现  2 金果提现

        $time = time();
        $nums = $data['nums'];
        $tx_db_je = C('pin_tx_db_je')*10000;//单笔最高
        $tx_mr_je = C('pin_tx_mr_je')*10000;//每日最高
        $jg_scj   = C('pin_jg_scj');//金果市场价

        $member = $this->member->where(array('id'=>$data['user_id']))->field("id,paypassword,vips")->find();
        $merchant = $this->merchant->where(array('uid'=>$data['user_id']))->field("id,gold_acer,gold_fruit")->find();
        $card = $this->MemberBankcard->where(array('member_id'=>$data['user_id'],'status'=>1))->find();
        $shop_id = $merchant['id'];
        //计算提现价格
        if($type == 2){  //金果提现
            $nums_prices = $jg_scj * $nums;
        }
        if($card){
//            ($member['vips'] < 2) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '亲,请升级掌柜再来提现')); //请升级掌柜再来提现
            (!$nums||$nums < 0) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确的金额')); //请输入正确的金额

            if($type == 1){
                ($nums > $tx_db_je) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => "单笔最高提现".$tx_db_je.'元'));
                ($merchant['gold_acer'] < $nums) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您的元宝不足')); //您的元宝不足
                $gold = "gold_acer";
            }
            if($type == 2){
                ($nums_prices > $tx_db_je) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => "单笔最高提现".$tx_db_je.'元'));
                ($merchant['gold_fruit'] < $nums) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您的金果不足')); //您的金果不足
                $da['fruit_price'] = $jg_scj;
                $gold = "gold_fruit";
            }
            (!$member['paypassword']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您还未设置支付密码')); //您还未设置支付密码
            ($member['paypassword'] !== st_md5($data['pos'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确支付密码')); //请输入正确支付密码

            $start = M();
            $start->startTrans();
            //验证每日提现金额上限
            $time_str = date('Y-m-d', $time);//今天0点
            $time_start= strtotime($time_str);//时间戳s
            $time_end= $time_start+24*3600;
            $time_rule['add_time'] =  array('between',array($time_start,$time_end));
            $time_rule['uid'] = $data['user_id'];
            $time_rule['status']=array('in',array(1,3));
            $time_rule['type'] = $type;
            $last = $this->withdraw_merchant->where($time_rule)->field('SUM(totalprices) total_prices')->select();

            $amount = $last[0]['total_prices'];
//            $amount = array_sum(array_column($last,'amount'));
            $amount += $nums;
//            dump($prices_today);die;
            ($amount > $tx_mr_je) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => "每日最高提现".$tx_mr_je.'元'));

            $da['uid'] = $member['id'];
            $da['shop_id'] = $shop_id;
            $da['member_name'] = $card['member_name'];//持卡人姓名
            $da['nums']=$card['nums'];//卡号
            $da['name']=$card['name'];//银行名称
            $da['province']=$card['province'];//省
            $da['title']=$card['title'];//分行
            $da['city']=$card['city'];//市
            $da['district']=$card['district'] ? $card['district'] : "";//区
            $da['add_time']=time();
            $da['status']=1;//0表示未审核 1为驳回 2为通过
            $da['memos'] = $data['memos'] ? $data['memos'] : "";//提现留言
            $da['totalprices'] = $nums;
            $da['type'] = $type;
            $yd = $this->withdraw_merchant->data($da)->add();	//生成提现申请
            $price = $this->merchant->where(array('uid'=>$member['id']))->setDec($gold,$nums);//扣商家元宝或金果

            if(!$yd|| !$price){
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
            //提现流水   //$type,$shop_id,$totalprices,$change_desc,$add_time,$oid=0
            if($type == 1){
                $memos = "商家元宝提现";
                $data_account = account_shop_arr(2,$shop_id,'-'.$nums,$memos,$time);//明细
                $res_account=$this->account_shop->add($data_account);
            }else{
                $memos = "商家金果提现";
                $data_account = account_shop_arr(3,$shop_id,'-'.$nums,$memos,$time);//明细
                $res_account=$this->account_shop->add($data_account);
            }
            if($res_account){
                $start->commit();
                $this->json_Response('success',$datas['pack_no']);
            }else{
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
        }else{
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请先添加银行卡'));//请勿重复提交
        }
    }

    //金果明细 && 元宝明细 && 银币明细 &&  怡买工资(余额)明细   20013
    public function member_account($datas){
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $type = $data['type'];//1余额  2元宝  3金果  4银币
        $merchant_id = $this->merchant->where(array('uid'=>$data['user_id']))->field('id')->find();
        if(false == $merchant_id){    //会员明细
            if(!in_array($type,[1,2,3,4])){
                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '系统繁忙'));
            }
            $count =$this->account->where(array('uid'=>$data['user_id'],'type'=>$type))->count();
            $pagesize = $data['pagesize'] ? $data['pagesize'] : 10;
            $page = $data['page'] ? $data['page'] : 1;
            $Page = new \Think\Page($count, $page, $pagesize);
            $account = $this->account->where(array('uid'=>$data['user_id'],'type'=>$type))->field('totalprices,change_desc,add_time')->limit($Page->firstRow.','.$Page->listRows)->order('add_time desc')->select();

        }else{
            if(!in_array($type,[2,3])){
                $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '系统繁忙'));
            }
            $count =$this->account_shop->where(array('shop_id'=>$merchant_id['id'],'type'=>$type))->count();
            $pagesize = $data['pagesize'] ? $data['pagesize'] : 10;
            $page = $data['page'] ? $data['page'] : 1;
            $Page = new \Think\Page($count, $page, $pagesize);
            $account = $this->account_shop->where(array('shop_id'=>$merchant_id['id'],'type'=>$type))->field('totalprices,change_desc,add_time')->limit($Page->firstRow.','.$Page->listRows)->order('add_time desc')->select();
        }
        if($account){
            $this->json_Response('success',$datas['pack_no'],array('account',$account));
        }else{
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //聚宝盆    20014
    public function basin($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $gold_acer_jc = $this->member->field('gold_acer_jc,gold_acer')->find($data['user_id']);
        //100元宝寄存7天可收益的银币个数
        $gold_acer_jc['basin'] = C('pin_jbp_bs') * 100;
        $this->json_Response('success',$datas['pack_no'],array('gold_acer_jc'=>$gold_acer_jc));
    }

    //元宝寄存    20015
    public function basin_in($datas)
    {
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid = $data['user_id'];
        $nums = $data['nums'];
        $now = time();
        $member = $this->member->field('gold_acer_jc,gold_acer,silver_coin,paypassword')->find($uid);
//        (!check_nums($nums)) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入数字')); //请输入数字
        (!$nums||$nums < 0) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确的元宝数')); //请输入正确的元宝数
        ($nums % 100 !== 0) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '每次寄存必须为100的倍数')); //每次寄存必须为100的倍数
        ($member['gold_acer'] < $nums) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '元宝余额不足')); //元宝余额不足
        (!$member['paypassword']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您还未设置支付密码')); //您还未设置支付密码
        ($member['paypassword'] !== st_md5($data['pos'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确支付密码')); //请输入正确支付密码
        $yb_nums = $nums * C('pin_jbp_bs');//送银币数量

        $start = M();
        $start->startTrans();
        //生成聚宝盆记录
        $jyb['member_id'] = $uid;
        $jyb['totalprices'] = $nums;
        $jyb['coin'] = $yb_nums;
        $jyb['jbp_zq'] = C('pin_jbp_zq');//寄存时候周期
        $jyb['memos'] = '聚宝盆存金元宝';
        $jyb['status'] = 1;//1寄存中/2未提取/3已提取
        $jyb['add_time'] = $now;
        $res_jyb =$this->member_jbp ->add($jyb);
        if (!$res_jyb) {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }

        //金额变化
        $acer['gold_acer_jc'] = $member['gold_acer_jc'] + $nums;//寄存加元宝
        $acer['gold_acer'] = $member['gold_acer'] - $nums;//减元宝
        $acer['silver_coin'] = $member['silver_coin'] + $yb_nums;

        $res_self = $this->member->where("id=$uid")->save($acer);
        if (!$res_self) {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
        //$type,$uid,$totalprices,$change_desc,$add_time,$oid=0,$account_type=0,$account_nums,$attach_field=0
        //减元宝&&加银币明细
        $recharge[] = account_arr(2, $uid, '-' . $nums, '聚宝盆存金元宝', $now);
        $recharge[] = account_arr(4, $uid, $yb_nums, '聚宝盆送银币', $now);
        $res_account = $this->account->addAll($recharge);

        if ($res_account) {
            $start->commit();
            $this->json_Response('success',$datas['pack_no']);
        } else {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //元宝提取    20016
    public function basin_out($datas)
    {
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        $uid = $data['user_id'];

        $id  = $data['id'];
        $now = time();
        $member = $this->member->field('gold_acer_jc,gold_acer,silver_coin')->find($uid);
        //查询可提取的元宝   status => 2
        $member_jbp = $this->member_jbp->where(array('id'=>$id,'status'=>2))->find();
        if(!$member_jbp){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '元宝已领取'));
        }
        ($member_jbp['totalprices'] > $member['gold_acer_jc'])&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '操作异常'));

        $start = M();
        $start->startTrans();

        $tq = $this->member_jbp->where(array('id' => $id))->save(['status' => 3]);//改订单状态
        if (!$tq) {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
        //金额变化
        $acer['gold_acer_jc'] = $member['gold_acer_jc'] - $member_jbp['totalprices'];//寄存减元宝
        $acer['gold_acer'] = $member['gold_acer'] + $member_jbp['totalprices'];//加元宝
        $res_self = $this->member->where("id=$uid")->save($acer);
        if (!$res_self) {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
        //$type,$uid,$totalprices,$change_desc,$add_time,$oid=0,$account_type=0,$account_nums,$attach_field=0
        //元宝流水单
        $recharge = account_arr(2, $uid, $member_jbp['totalprices'], '聚宝盆提取金元宝', $now);
        $res_account = $this->account->add($recharge);
        if ($res_account) {
            $start->commit();
            $this->json_Response('success',$datas['pack_no']);
        } else {
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //聚宝盆明细   20017
    public function basin_account($datas){
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        $status = $data['status'];//1 寄存中 2 可提取 3 已提取

        if($status == 2){    //可提取

            $sql= 'member_id = '.$data['user_id'].' and status=2 or (status=1 and add_time <('.time().'- (jbp_zq * 3600 * 24)))';
            $count =$this->member_jbp->where($sql)->count();
            $pagesize = $data['pagesize'] ? $data['pagesize'] : 2;
            $page = $data['page'] ? $data['page'] : 1;
            $Page = new \Think\Page($count, $page, $pagesize);
            $account = $this->member_jbp->where($sql)->limit($Page->firstRow.','.$Page->listRows)->order('add_time desc')->select();
            $jbp = $this->member_jbp->where($sql)->order('id desc')->select();
            foreach ($jbp as $k => $v) {
                $tq_ids[]=$v['id'];
                $jbp[$k]['status'] = 2;
            }

        }else{

            $count =$this->member_jbp->where(array('member_id'=>$data['user_id']))->count();
            $pagesize = $data['pagesize'] ? $data['pagesize'] : 10;
            $page = $data['page'] ? $data['page'] : 1;
            $Page = new \Think\Page($count, $page, $pagesize);
            $account = $this->member_jbp->where(array('member_id'=>$data['user_id']))->limit($Page->firstRow.','.$Page->listRows)->order('add_time desc')->select();

            $jbp = $this->member_jbp->where(array('member_id'=>$data['user_id']))->order('id desc')->select();
            $today = strtotime(date('Y-m-d', time()));//今天0点
            $tq_ids=[];
            foreach ($jbp as $k => $v) {
                if($v['status']==1){
                    $start = strtotime(date('Y-m-d', $v['add_time']));//开始时间
                    $tian = $v['jbp_zq'];//寄存时候周期
                    $aaa = ($start + $tian * 3600 * 24 - $today) / (24 * 3600);
                    $jbp[$k]['other_days'] = $aaa;//寄存中=>剩余天数
                    if ($aaa == 0 || $aaa < 0) {//找到时间到期的记录
                        $tq_ids[]=$v['id'];
                        $jbp[$k]['status'] = 2;
                    }
                }
            }
        }
        //寄存中到期修改为可提取
        !empty($tq_ids)&&$res = $this->member_jbp->where(array('id' => ['in',$tq_ids]))->save(array('status' => 2));
        if($account){
            $this->json_Response('success',$datas['pack_no'],array('account',$account));
        }else{
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //推荐的会员  && 推荐的商家    20018
    public function vip_list($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        $uid = $data['user_id'];
        $type = $data['type'];// 1 推荐的商家 2 推荐的会员

        if($type == 1){
            $count =$this->merchant->where(array('relation_id'=>$uid))->count();
            $pagesize = $data['pagesize'] ? $data['pagesize'] : 10;
            $page = $data['page'] ? $data['page'] : 1;
            $Page = new \Think\Page($count, $page, $pagesize);
            $merchant = $this->merchant->where(array('relation_id'=>$uid))->field('add_time,tel,img')->limit($Page->firstRow.','.$Page->listRows)->order('add_time desc')->select();
        }elseif ($type == 2){
            $count =$this->member->where(array('relation_id'=>$uid))->count();
            $pagesize = $data['pagesize'] ? $data['pagesize'] : 2;
            $page = $data['page'] ? $data['page'] : 1;
            $Page = new \Think\Page($count, $page, $pagesize);
            $merchant = $this->member->where(array('relation_id'=>$uid))->field('reg_time,mobile,avatar')->limit($Page->firstRow.','.$Page->listRows)->order('reg_time desc')->select();
        }
        if($merchant){
            $this->json_Response('success',$datas['pack_no'],array('merchant',$merchant));
        }else{
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //元宝充值   20019
    public function acer_buy($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        $uid = $data['user_id'];
        $nums = $data['nums'];
        $now  = time();
        (!$nums||$nums < 0) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确的元宝数')); //请输入正确的元宝数
        (!check_int($nums)) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '充值数值必须为整数'));

        $zftype = $data['zftype'];//支付方式   1=微信，2=支付宝, 3=余额',
        $order_recharge=M('order_recharge');
        $member_model = M('member');

        $start = M();
        $start->startTrans();

        //生成充值订单记录
        $data['dingdan']=create_order_sn();//生成订单号
        $data['uid'] = $uid;
        $data['totalprices'] = $nums;
        $data['type'] = 2;//1为支付 2为充值',
        $data['zftype']=$zftype;//'支付方式  0.未选择 1=微信，2=支付宝, 3=余额',
        $data['status']=1;//支付状态 1待支付 2支付成功 3支付失败',
        $data['add_time']= $now;
        $yd = $order_recharge->add($data);//生成临时订单
        if(!$yd){
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
        if($zftype == 3){
            $member = $member_model->where(['id'=>$data['user_id']])->find();
            $info = $order_recharge->find($yd);//充值订单记录
            $jyb = $info['totalprices'];
            ($member['prices'] < $jyb)&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您的余额不足'));
            (!$member['paypassword']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您还未设置支付密码')); //您还未设置支付密码
            ($member['paypassword'] !== st_md5($data['password'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确支付密码')); //请输入正确支付密码
        }elseif($zftype == 1){
            //微信支付
            $openid = $data['openid'];
            (!$openid) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '微信数据异常'));
            $wx = new \Mobile\Org\WeiXinAbout();
            $row = $wx->get_user_info($openid);
        }
            //更改订单状态
            $res_order = $order_recharge->where(array('id' => $info['id']))
                ->save(array('status' => 2, 'pay_time' => time(), 'zftype' => $zftype));

            if (!$res_order) {
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
            $yb = 0;
            //充值送银币规则 #该金额区间一天只能送银币一次
            $time_end=time();//当前时间
            $time_start= strtotime(date('Y-m-d',$time_end));//今天0点时间戳s
            $time_rule['add_time'] =  array('between',array($time_start,$time_end));
            $time_rule['uid'] = $uid;
            $buy_send_rule=M('buy_send')->select();
            foreach($buy_send_rule as $kk=>$vv){
                //判断充值所属等级
                $flag=$kk+1<count($buy_send_rule)?($jyb <$buy_send_rule[$kk+1]['money']):true;
                $jyb>=$vv['money'] &&$flag&&$buy_send_id =$vv['id'];//当前规则的id
            }
            if($buy_send_id){
                $time_rule['bs_id'] =$buy_send_id;//当前规则的id
                $hava_buy_send=M('buy_send_account')->where($time_rule)->find();
                (!$hava_buy_send) && $yb = M('buy_send')->where(['id'=>$buy_send_id])->field('send_coin')->find();
                $yb['send_coin'] = intval($yb['send_coin']);
                if($yb['send_coin'] > 0){
                    $save['silver_coin'] = $member['silver_coin'] + $yb['send_coin'];
                    //获得银币奖励记录
                    $res_buy_send=M('buy_send_account')->add(['uid'=>$uid,'bs_id'=>$buy_send_id,'add_time'=>$now]);

                    if (!$res_buy_send) {
                        $start->rollback();
                        $this->json_Response('failed',$datas['pack_no']);
                    }
                }
            }
            //充值成功金额变化
            $save['prices'] = $member['prices'] - $jyb;//减余额
            $save['gold_acer'] = $member['gold_acer'] + $jyb;//加元宝

            //本人加金元宝&&银币
            $res_member = $member_model->where(array('id' => $uid))->save($save);
            if (!$res_member) {
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
            //本人金元宝&&银币明细  1工资2金元宝3金果4银币
            $recharge[] = account_arr(1, $uid, '-'.$jyb, '充值元宝', $now);
            $recharge[] = account_arr(2, $uid, $jyb, '充值元宝', $now);
            $yb&&$recharge[] = account_arr(4, $uid, $yb['send_coin'], '充值元宝赠送', $now);

            //推荐人得银币&&明细
            if ($member['relation_id']) {
                #推荐人赠送银币(规则表)
                $relation_member = $member_model->where(array('id' => $member['relation_id']))->field('id,vips')->find();
                $relation_bl = D('GradeRule')->where(array('id' => $relation_member['vips']))->field('id,upgrade_one_price,tj_acer_silver')->find();
                $silver_coin_num = $jyb * $relation_bl['tj_acer_silver'];
                #银币明细
                if ($silver_coin_num > 0) {
                    $res_relation = $member_model->where(array('id' => $member['relation_id']))->setInc('silver_coin', $silver_coin_num);
                    if (!$res_relation) {
                        $start->rollback();
                        $this->json_Response('failed',$datas['pack_no']);
                    }
                    $recharge[] = account_arr(4, $member['relation_id'], $silver_coin_num, '下线充值元宝', $now);
                }
            }
            //各区代得银币&&明细
            $province_id=$member['province_id'];
            $city_id=$member['city_id'];
            $district_id=$member['district_id'];
            $where['is_qd']=1;//vips_qd区代等级 1区2市3省
            $where['_string']='(vips_qd =3 and province_id ='.$province_id.')'
                .'or ( vips_qd =2 and  city_id='. $city_id.')'
                .'or ( vips_qd =1 and district_id='.$district_id.')';
            $qds=$member_model->where($where)->select();//该会员所在地区的各个等级区代
            $qd_rule=M('qd_rule')->getField('id,name,reward_silver_multiple,upgrade_recharge');//区代等级规则
            if ($qds) {
                foreach($qds as $kk=>$vv){
                    $qd_silver_coin_num=$qd_rule[$vv['vips_qd']]['reward_silver_multiple']*$jyb;//区代所得银币
                    if($qd_silver_coin_num>0){
                        $recharge[]=account_arr(4,$vv['id'],$qd_silver_coin_num,'区域内会员充值元宝',$now);//明细
                        $res_qd=$member_model->where(['id'=>$vv['id']])->setInc('silver_coin',$qd_silver_coin_num);//改会员表
                        if (!$res_qd) {
                            $start->rollback();
                            $this->json_Response('failed',$datas['pack_no']);
                        }
                    }

                }
            }
            //添加所有明细
            $res_account = $this->account->addAll($recharge);
            if (!$res_account) {
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
            $start->commit();
            $this->json_Response('success',$datas['pack_no']);

    }

    //我要升级   20020
    public function upgrade($datas){
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        $uid = $data['user_id'];
        $now  = time();
        $zftype = $data['zftype'];//支付方式   1=微信，2=支付宝, 3=余额',
        $order_recharge=M('order_recharge');
        $member_model = M('member');
        $grade_rule   = M('grade_rule');
        $member = $member_model->where(['id'=>$data['user_id']])->find();
        $gr = $grade_rule->getField('id,name,upgrade_price,upgrade_condition
            ,upgrade_one_price,seller_none_silve,tj_acer_pay_silver,tj_acer_silver,upgrade_fruit,upgrade_silver');
        $member['vips_name'] = $gr[ $member['vips']]['name'];//会员等级对应名称
        $vips_next=$gr[($member['vips']+1)];//下一级等级
        $nums = $vips_next['upgrade_price'];//升级掌柜所需金额

        $start = M();
        $start->startTrans();

            //生成充值订单记录
            $data['dingdan']=create_order_sn();//生成订单号
            $data['uid'] = $uid;
            $data['totalprices'] = $nums;
            $data['type'] = 2;//1为支付 2为充值',
            $data['zftype']=$zftype;//'支付方式  0.未选择 1=微信，2=支付宝, 3=余额',
            $data['status']=1;//支付状态 1待支付 2支付成功 3支付失败',
            $data['add_time']=$now;
            $yd = $order_recharge->add($data);//生成临时订单
            if(!$yd){
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
            if($member['vips'] <= 1){   //会员等级为1  才需要升级为掌柜
                if($zftype == 3){
                    $info = $order_recharge->find($yd);//充值订单记录
                    $old_vip=$info['old_vip'];
                    $after_vip=$info['old_vip']+1;
                    $jyb = $info['totalprices'];
                    ($member['prices'] < $jyb)&& $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您的余额不足'));
                    (!$member['paypassword']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您还未设置支付密码')); //您还未设置支付密码
                    ($member['paypassword'] !== st_md5($data['password'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '请输入正确支付密码')); //请输入正确支付密码
                }
                //更改订单状态
                $res_order = $order_recharge->where(array('id' => $info['id']))
                    ->save(array('status' => 2, 'pay_time' => $now, 'zftype' => $zftype,'old_vip' => $old_vip, 'after_vip'=> $after_vip));
                if (!$res_order) {
                    $start->rollback();
                    $this->json_Response('failed',$datas['pack_no']);
                }
                //币种变动&&本人明细
                $totalprices=$info['totalprices'];
                $save['prices'] = $member['prices'] -$totalprices;//减余额

                //升级为掌柜
                $vips = $this->member->where("id='$uid'")->setfield('vips',$after_vip);
                if (!$vips) {
                    $start->rollback();
                    $this->json_Response('failed',$datas['pack_no']);
                }
                $recharge[] = account_arr(1, $uid, '-'.$totalprices, '升级会员:'.$gr[$after_vip]['name'], $now);
                $upgrade_fruit=$gr[$after_vip]['upgrade_fruit'];
                $upgrade_silver=$gr[$after_vip]['upgrade_silver'];

                if($upgrade_fruit>0){//加金果
                    $recharge[] = account_arr(3, $uid, $upgrade_fruit, '升级会员奖励金果', $now);
                    $save['gold_fruit'] = $member['gold_fruit'] + $upgrade_fruit;
                }

                if($upgrade_silver>0){//加银币
                    $recharge[] = account_arr(4, $uid, $upgrade_silver, '升级会员奖励银币', $now);
                    $save['silver_coin'] = $member['silver_coin'] +$upgrade_silver;
                }
                // $save['vips']=$info['after_vip'];//级别
                $save['vips']=$after_vip;//级别
                //修改本人
                $res_member = $member_model->where(array('id' => $uid))->save($save);
                if (!$res_member) {
                    $start->rollback();
                    $this->json_Response('failed',$datas['pack_no']);
                }
                //推荐人得奖励(加工资)
                if ($member['relation_id']) {
                    $relation_member = $member_model->where(array('id' => $member['relation_id']))->field('id,vips')->find();
                    $relation_price=$gr[$relation_member['vips']]['upgrade_one_price'];//掌柜推荐悦买工资奖励
                    #余额明细
                    if ($relation_price > 0) {
                        $res_relation = $member_model->where(array('id' => $member['relation_id']))->setInc('prices', $relation_price);
                        if (!$res_relation) {
                            $start->rollback();
                            $this->json_Response('failed',$datas['pack_no']);
                        }
                        $recharge[] = account_arr(1, $member['relation_id'], $relation_price, '下线成为掌柜', $now);
                    }
        }

        //各区代得银币&&明细
        if($after_vip==2){//第一次成为掌柜才有
            $province_id=$member['province_id'];
            $city_id=$member['city_id'];
            $district_id=$member['district_id'];
            $where['is_qd']=1;//vips_qd区代等级 1区2市3省
            $where['_string']='(vips_qd =3 and province_id ='.$province_id.')'
                .'or ( vips_qd =2 and  city_id='. $city_id.')'
                .'or ( vips_qd =1 and district_id='.$district_id.')';
            $qds=$member_model->where($where)->select();//区代列表
            $qd_rule=M('qd_rule')->getField('id,name,reward_silver_multiple,upgrade_recharge');//区代等级规则
            if (!empty($qds)) {
                foreach($qds as $kk=>$vv){
                    $qd_price=$qd_rule[$vv['vips_qd']]['upgrade_recharge'];//区代所得银币
                    if($qd_price>0){
                        $recharge[]=account_arr(1,$vv['id'],$qd_price,'区域内会员成为掌柜',$now);//明细
                        $res_qd=$member_model->where(['id'=>$vv['id']])->setInc('prices',$qd_price);//改会员表
                        if (!$res_qd) {
                            $start->rollback();
                            $this->json_Response('failed',$datas['pack_no']);
                        }
                    }

                }
            }
        }

        //添加所有明细
        $res_account = $this->account->addAll($recharge);

            if (!$res_account) {
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
            $start->commit();
            $this->json_Response('success',$datas['pack_no']);
        }else{
                $info = $order_recharge->find($yd);//升级记录
            if($member['vips'] == 5){
                $after_vip = $member['vips'];
            }else{
                $after_vip = $member['vips']+1;
            }
            $count=$this->member->where(['relation_id'=>$uid,'vips'=>['egt',2]])->count();//下线掌柜人数
            //升级验证(只能升下一级、升级有下线人数限制)
            ($member['vips'] >= $after_vip) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => "您已是".$gr[$after_vip]['name']));
            ($count < $gr[$after_vip]['upgrade_condition']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '您推荐的掌柜人数不足'));
                //掌柜升级记录
                $vips_code = $order_recharge->where(['id'=>$info['id']])->setfield(['old_vip'=>$member['vips'],'after_vip'=>$after_vip,'pay_time'=>$now]);
                if(!$vips_code){
                    $start->rollback();
                    $this->json_Response('failed',$datas['pack_no']);
                }
            //掌柜升级
            $vips = $this->member->where(['id'=>$uid])->setField('vips',$after_vip);
            if (!$vips) {
                $start->rollback();
                $this->json_Response('failed',$datas['pack_no']);
            }
            $start->commit();
            $this->json_Response('success',$datas['pack_no']);
        }


    }

}

?>