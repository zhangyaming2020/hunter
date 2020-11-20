<?php

function attach($attach, $type,$path=false) {

    if (false === strpos($attach, 'http://')) {
        //本地附件

        $img_url = __ROOT__ . 'data/attachment/' . $type . '/' . $attach;

        $img_path = realpath(__ROOT__).'/data/attachment/' . $type . '/' . $attach;

	//return $img_url; 

        if(is_file($img_path) || $path){

            return $img_url;

        }else{

            return __ROOT__ . '/data/nopicture.jpg';

        }

        //远程附件

        //todo...

    } else {

        //URL链接

        return $attach;

    }

}

//生成二维码
function set_qrcode($url,$level=3,$size=4){
    Vendor('phpqrcode');

    //二维码名称
    $ewm_name = uniqid().rand(1000,9999).'.png';
    //二维码存储路径
    $path = C('pin_attach_path').'ewm/'.$ewm_name;
    $errorCorrectionLevel =intval($level) ;//容错级别
    $matrixPointSize = intval($size);//生成图片大小
    //生成二维码图片
    $object = new \QRcode();
    $object->png($url,$path, $errorCorrectionLevel, $matrixPointSize, 2);

    return $ewm_name;
}

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login(){
//  $user = cookie('user_auth');
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
		return $user['id'];
        //return session('user_auth')==data_auth_sign($user) ? $user['id'] : 0;    
    }
}


function make_order_sn($mode = 'Address', $field = 'order_sn') {
    $Ord = M($mode);
    $ordcode = date('ymd') . substr(md5(rand(100000,999999)),rand(0,20),6).substr(time(), -4) . substr(microtime(), 2, 5);
    $oldcode = $Ord -> where(array($field => $ordcode)) -> getField($field);
    if ($oldcode) {
        make_order_sn();
    } else {
        return $ordcode;
    }
}

//生成订单号
function create_order_sn(){
    $order_sn=substr_replace($_SERVER['REQUEST_TIME'].rand(10000,99999), 88,1,5);
    return $order_sn;
}

//付款人数
function payer($item_id){
	$res =M('order_list')->query('select distinct uid from jrkj_order_list where item_id='.$item_id);
	return count($res);
}

/*会员币种明细
      * type币种1工资2金元宝3金果4银币
      * account_type流水类型0无特殊定义1下线会员收益2下线商家收益3金果转好友4元宝转好友5线上消费
      * attach_field附加参数：对方电话（金果转好友）/下线会员id(推荐新会员)/下线商家id(推荐新商家)
      */
function account_arr($type,$uid,$totalprices,$change_desc,$add_time,$oid=0,$account_type=0,$attach_field=0){
    $arr= [
        'type' 			=> $type,//币种1工资2金元宝3金果4银币
        'uid'			=> $uid,
        'totalprices'	=> $totalprices,
        'change_desc'	=> $change_desc,
        'add_time'		=> $add_time,
        'oid'           => $oid,
        'account_type'  => $account_type,
        'attach_field'  => $attach_field,
    ];
    return $arr;
}

//商家币种明细 //$type币种1工资2金元宝3金果4银币
function account_shop_arr($type,$shop_id,$totalprices,$change_desc,$add_time,$oid=0){
    $arr= [
        'type' 			=> $type,//币种1工资2金元宝3金果4银币
        'shop_id'		=> $shop_id,
        'oid'           => $oid,
        'totalprices'	=> $totalprices,
        'change_desc'	=> $change_desc,
        'add_time'		=> $add_time
    ];
    return $arr;
}


//	//添加用户
//	function add_member($nickname,$mobile){
//		$data['vips'] = 1;
//		$data['sex'] = 0;//默认0未选择
//		$data['password'] = 'c6f4b02c1aa65a08af09cb8423c53c07';
//		$data['paypassword'] = 'c6f4b02c1aa65a08af09cb8423c53c07';
//		$data['nickname'] = $nickname;
//		$data['realname'] = $nickname;
//		$data['mobile'] = $mobile;//默认0未选择
//		$data['reg_time'] = time();//默认0未选择
//		$data['last_login_time'] = $data['reg_time'];//默认0未选择
//		$data['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('login/register',array('ewid'=>$data['mobile'])));
//		$uid = D('Member')->add($data);
//		return $uid;
//	
//	}
//成为商家
//	function be_shop($title,$mobile,$uid,$rangli,$set_coin){
//		
//		$datas['title']=$title;
//		$datas['tel']=$mobile;
//		$datas['cate_id']=13;
//		$datas['shop_hours']='9:00-22:00';
//		$datas['desc']='南昌';
//	    $datas['uid']=$uid;
//	    $datas['add_time']=time();
//	  	$datas['zftype']='1,2,3';
//	  	$datas['rangli']=$rangli;
//	  	$datas['set_coin']=$set_coin;
//		$datas['status']=2;//商家申请状态  0为未审核 1为驳回 2为通过	        
//	    //推荐二维
////		$datas['ewm_tj'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/add_shop',array('tel'=>$mobile)));
//		//收款二维码(商户表电话、返银币倍数)
//	   	$datas['ewm'] =  $this->set_qrcode("http://".$_SERVER['HTTP_HOST'].U('member/w_shaped_pay',array('tel'=>$mobile)));
//	
//		D('Member')->where(array('id'=>$uid))->save(array('type'=>2));//商家
//		$row = D('Merchant')->add($datas);
//		return $row;
//	}
	

