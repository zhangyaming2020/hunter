<?php
namespace App\Controller;

class OrderController extends ApiController{
    private $order              = null;
    private $_mod               = null;
    private $OrderList          = null;
    private $member_recharge    = null;
    private $order_recharge     = null;
	public function _initialize()
	{ 
		parent::_initialize();
		$this->order            = D('Order');               //订单表
		$this->_mod             = D('Member');              //用户表
		$this->OrderList        = D('OrderList');           //订单详情表
        $this->member_recharge  = D('MemberRecharge');      //用户消费记录表
        $this->order_recharge   = D('OrderRecharge');       //微信支付宝记录表
	}

   //微信支付处理
	private function process_Wx_pay($datas,$user_id,$pay_type,$pay_money){
		if(!is_numeric($user_id) || !is_numeric($pay_type) || !is_numeric($pay_money))
			$this->print_error_status('params_error',$datas['pack_no'],array('Error_Param_Format' => 'pay_type,pay_money'));

		//获取用户实体
		$entity_user = $this->_mod->get_info($user_id,'nickname');
		if($entity_user==NULL) $this->print_error_status('params_error',$datas['pack_no'],array('Error' => 'User Not Esixts'));	
		
		//存入数据库的充值金额转换
		//微信充值的单位是：分，所以这里要进行元转换 分除以100即为元;
		$order_money = $pay_money*100;
		
		$C_pay_type = C('pay_type');
		//装配实体信息
		$order_id = make_order_id('OrderRecharge');
		$order_recharge = array(
			'order_id'      => $order_id,
			'uid'           => $user_id,
			'pay_type'      => $pay_type,
			'order_money'   => $pay_money,
			'fee_type'      => 'CNY',
			'trade_type'    => 'APP',
			'body'          => '绿农贸易用户充值.',
			'detail'        => $entity_user['nickname']." 申请充值 $pay_money 元，充值类型为 ".$C_pay_type[$pay_type]['type_name'],
			'wx_sign'       => '',
			'add_time'      => time()
		);
		
		$C_base_pay_info = C('base_pay_info');
		$C_wx_pay_config = C('wx_pay_config');
		if($C_wx_pay_config == NULL || $C_base_pay_info == NULL)
			$this->json_Response('failed',$datas['pack_no']);
		
		//构造签名的参数
		$appid              = $C_wx_pay_config['AppID'];
		$AppSecret          = $C_wx_pay_config['AppSecret'];
		$mch_id             = $C_wx_pay_config['mch_id'];
		$wx_key             = $C_wx_pay_config['key'];
		
		$nonce_str          = make_rand_str();//生成随机码
		$body               = $order_recharge['body'];
		$out_trade_no       = $order_recharge['order_id'];
		$total_fee          = $order_money;
		$spbill_create_ip   = GetHostByName($_SERVER['SERVER_NAME']);//获取当前服务器IP地址 GetHostByName 此方法为PHP自带的获取当前服务器IP的方法
		$notify_url         = $C_base_pay_info['wx_notify_url'];
		$trade_type         = $order_recharge['trade_type'];
		
		//微信请求参数列表
		$arr_params['appid']            = $appid;
		$arr_params['mch_id']           = $mch_id;
		$arr_params['nonce_str']        = $nonce_str;
		$arr_params['body']             = $body;
		$arr_params['out_trade_no']     = $out_trade_no;
		$arr_params['total_fee']        = $total_fee;
		$arr_params['spbill_create_ip'] = $spbill_create_ip;
		$arr_params['notify_url']       = $notify_url;
		$arr_params['trade_type']       = $trade_type;


		//生成签名 PayCommon /支付支持处理类
		$PayCommon = new \Think\PayCommon();
		$sign = $PayCommon->build_sign($arr_params,$wx_key);
		//设置微信签名
		$order_recharge['wx_sign']      = $sign;
		$arr_params['sign']             = $sign;
		
/*		//开户事务
		$this->_users->startTrans();*/
		
		//持久化订单
		$new_id = $this->order_recharge->add_order($order_recharge);

				
		if($new_id !== false && $this->member_recharge->record($user_id,1,2,$pay_money,0,$order_id) !== false){
			//构造支付请求参数 XML字符串
			$wx_xml = $PayCommon->build_xml($arr_params);

			if($wx_xml!=''){
				//向微信支付网关发送支付请求
				$response_wx_xml_data = $PayCommon->send($C_wx_pay_config['pay_gateway'],$wx_xml);

				if($response_wx_xml_data['return_code'] == 'SUCCESS' && $response_wx_xml_data['result_code'] == 'SUCCESS' && $response_wx_xml_data['appid'] == $arr_params['appid'] && $response_wx_xml_data['mch_id'] == $arr_params['mch_id']){
					//获取微信返回的签名
					$back_sign = $response_wx_xml_data['sign'];
					
					//微信返回的预支付编号
					$prepay_id = $response_wx_xml_data['prepay_id'];
					
					//更新微信预支付编号
					$this->order_recharge->change_field('wx_prepay_id',$prepay_id,$new_id);
					
					//微信返回的签名不参与验证签名生成
					unset($response_wx_xml_data['sign']);
					
					//根据微信返回的信息生成验证签名
					$back_params_sign = $PayCommon->build_sign($response_wx_xml_data,$wx_key);
					
					//验证签名是否正确
					if($back_sign == $back_params_sign){
						
						//生成返回给APP端的信息
						$response_App_datas = array();
						$response_App_datas['appid']        = $appid;//APP ID
						$response_App_datas['partnerid']    = $mch_id;//商户编号
						$response_App_datas['prepayid']     = $prepay_id; //微信返回的预支付编号
						$response_App_datas['package']      = 'Sign=WXPay';//package 微信规定用固定值
						$response_App_datas['noncestr']     = make_rand_str();//生成随机字符串 必须全是字母
						$response_App_datas['timestamp']    = time();//时间戳
                            
						//APP端支付签名
						$response_APP_sign                  = $PayCommon->build_sign($response_App_datas,$wx_key);
						$response_App_datas['sign']         = $response_APP_sign;
						
						//用户支付订单编号  ###注意这个值APP端不需要传给支付宝接口###
						$response_App_datas['order_id']     = $order_id;

						$this->json_Response('success',$datas['pack_no'],$response_App_datas);
					}else{
						$this->json_Response('failed',$datas['pack_no']);
					}
				}else{
					$this->json_Response('failed',$datas['pack_no']);
				}
			}else{
				$this->json_Response('failed',$datas['pack_no']);
			}
		}		
	}

	
	//处理支付宝充值
	private function process_ZFB_pay($datas,$user_id,$pay_type,$pay_money){
		if(!is_numeric($user_id) || !is_numeric($pay_type) || !is_numeric($pay_money))
			$this->print_error_status('params_error',$datas['pack_no'],array('Error_Param_Format' => 'pay_type,pay_money'));
		
		//获取用户实体
		$entity_user = $this->_mod->get_info($user_id,'nickname');
		if($entity_user==NULL) $this->print_error_status('params_error',$datas['pack_no'],array('Error' => 'User Not Esixts'));

        $name = '充值';

		$C_pay_type = C('pay_type');
		//装配实体信息
        $str=time().rand(10000,99999);
        $order_id = substr_replace($str, 88,1,5);

		$order_recharge = array(
            'order_id'      => $order_id,
            'uid'           => $user_id,
            'type'          => 1,//1为支付   2为充值
            'pay_type'      => $pay_type,
            'order_money'   => $pay_money,
            'fee_type'      => 'CNY',
            'trade_type'    => 'APP',
            'body'          => '绿农用户'.$name,
            'detail'        => $entity_user['nickname']." 申请".$name."$pay_money 元，".$name."类型为 ".$C_pay_type[$pay_type]['type_name'],
            'wx_sign'       => '',
            'add_time'      => time()
		);
		
		$C_base_pay_info    = C('base_pay_info');
		$C_zfb_pay_config   = C('zfb_pay_config');
		if($C_base_pay_info == NULL || $C_zfb_pay_config == NULL)
			$this->json_Response('failed',$datas['pack_no']);
		
		//构建签名参数
		$arr_sign = array();
		$arr_sign['service']        = $C_zfb_pay_config['service'];
		$arr_sign['partner']        = $C_zfb_pay_config['partner'];
		$arr_sign['_input_charset'] = $C_zfb_pay_config['_input_charset'];
		$arr_sign['notify_url']     = $C_base_pay_info['zfb_notify_url'];
		$arr_sign['app_id']         = $C_zfb_pay_config['app_id'];
		$arr_sign['out_trade_no']   = $order_id;
		$arr_sign['subject']        = $order_recharge['body'];
		$arr_sign['payment_type']   = $C_zfb_pay_config['payment_type'];
		$arr_sign['seller_id']      = $C_zfb_pay_config['seller_id'];
		$arr_sign['total_fee']      = $order_recharge['order_money'];
		$arr_sign['body']           = $order_recharge['detail'];

		//生成签名 PayCommon /支付支持处理类
		$PayCommon                  = new \Think\PayCommon();
		$sign_str                   = $PayCommon->build_zfb_sign_str($arr_sign);
		$sign                       = $PayCommon->rsaSign($sign_str,'./pay_zfb/key/rsa_private_key.pem');
//		$a = $PayCommon->rsaVerify($sign_str,'./pay_zfb/key/rsa_public_key.pem',$sign);dump($a);exit;

		//设置支付宝签名
		$order_recharge['zfb_sign'] = $sign;		
		$arr_sign['sign']           = $sign;
		$arr_sign['sign_type']      = $C_zfb_pay_config['sign_type'];

		//### 生成待返回APP端的支付字符串 #
		$alipay_info = $PayCommon->build_zfb_sign_str($arr_sign);

		//开户事务
//		M()->startTrans();

		//持久化订单
		$new_id = $this->order_recharge->add_order($order_recharge);

		if($new_id !== false && $this->member_recharge->record($user_id,2,2,$pay_money,0,$order_id) !== false){
//			M()->commit();
			$this->json_Response('success',$datas['pack_no'],array('pay_info' => $alipay_info));
		}else{
//			M()->rollback();
			$this->json_Response('failed',$datas['pack_no']);
		}		
	}

    //退货
    public function return_of_the_goods($datas)
    {
        $this->check_user_id($datas);//判断用户是否登录
        $data = $this->get_datas($datas);//获取数据
        $uid = $datas['user_id'];//用户id

        (!$dingdan = $data['dingdan']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format'=>'dingdan'));//订单号不能为空
        (!$info = $data['info']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format'=>'info'));//理由不能为空
        (!$status = $data['status']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format'=>'status'));//2:退货 3：换货

        $order = $this->order->validation($uid,$dingdan);
        (empty($order)) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format'=>'dingdan_error'));//订单不存在
        (($order['status'] == 2) || ($order['status'] == 3)) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format'=>'goods_error'));//正在处理中
        (((int)$order['addtime'] + 864000) < time()) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format'=>'goods_time'));//已过了退货期

        $paths = $this->img('order',array(
        'width'=>C('pin_item_bimg.width'),
        'height'=>C('pin_item_bimg.height'),
        'suffix' => '_b',
    ));
        foreach ($paths as $k=>$path) {
            $paths[$k] = strstr($path,'.',true).'_b'.strstr($path,'.');
        }
        $image = implode(',',$paths);
        if ($this->order->set_validation($uid,$dingdan,$info,$image,$status) !== false)
            $this->json_Response('success',$datas['pack_no']);
        else
            $this->json_Response('failed',$datas['pack_no']);
    }

    //订单列表
    public function get_order($datas)
    {
        $this->check_user_id($datas);//判断用户是否登录
        $data = $this->get_datas($datas);//获取数据
        $map['uid'] = $datas['user_id'];//用户id

        (!$type = $data['type']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format'=>'type'));//1:正常订单 2:退换货订单 3:申请退换货订单

        switch ($type){//1:正常订单 2:退换货订单 3:申请退换货订单列表
            case 1:
                $map['status'] = array('in','1,4');
                (!empty($data['start_time']) && is_numeric($data['start_time'])) && $map['addtime'][] = array('egt',$data['start_time']);
                (!empty($data['end_time']) && is_numeric($data['end_time'])) && $map['addtime'][] = array('elt',$data['end_time']);
                if(!empty($data['start_time']) && !empty($data['end_time']) && date('d',$data['start_time']) == date('d',$data['end_time'])){
                    unset($map['addtime']);
                    $map['addtime'] = array('between',array(strtotime(date('Y-m-d 00:00:00',$data['start_time'])),strtotime(date('Y-m-d 23:59:59',$data['end_time']))));
                }
                $filed = 'id,info,dingdan,addtime,status,Is_ok,realprices,totalprices,aid,logistics_company,logistics_single_number';
                break;
            case 2:
                $map['status'] = array('in','2,3');
                $filed = 'id,info,dingdan,addtime,status,Is_ok,totalprices,aid,image';
                break;
            case 3:
                $map['status'] = array('in','1,4');
                $map['addtime'] = array('gt',time()-864000);
                $filed = 'id,info,dingdan,addtime,status,Is_ok,realprices,totalprices,aid,logistics_company,logistics_single_number';
                break;
        }
        $list = $this->order->orders($map,$filed,$data['page'],$data['pagesize']);

        $this->json_Response('success',$datas['pack_no'],$list);
    }

    //充值
    public function top_up($datas)
    {
        $this->check_user_id($datas);//判断用户是否登录
        $data = $this->get_datas($datas);//获取数据
        $uid = $datas['user_id'];//用户id

        (!$type = $data['type']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format'=>'type'));//1:微信 2:支付宝
        (!$total = $data['total']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format'=>'total'));//金额

        switch ($type){//1:微信 2：支付宝
            case 1:
                $this->process_Wx_pay($datas,$uid,$type,$total);
                break;
            case 2:
                $this->process_ZFB_pay($datas,$uid,$type,$total);
                break;
        }
    }

}