<?php
namespace Admin\Controller;
class OrderController extends AdminCoreController {
	public function _initialize() {
		parent::_initialize();
		$this->_mod = D('Order');
		$this->set_mod('Order');	
	}
	
	public function index(){
		($order_sn = I('order_sn','','trim'))&&$data['order_sn']=array('like','%'.$order_sn.'%');
		($username = I('username','','trim'))&&$data['name']=array('like','%'.$username.'%');
		($id = I('id','','trim'))&&$data['member_id']=$id;
		($tel = I('tel','','trim'))&&$data['tel']=$tel;
		$status = I('status','-1','trim');
		if($status!=''&&$status!=-1) $data['status']=$status;
		($time_start = I('time_start','','trim'))&&$data['create_time'][]=array('egt',strtotime($time_start));
		($time_end = I('time_end','','trim'))&&$data['create_time'][]=array('elt',strtotime($time_end.' +1 days'));
		
		$count = D('Order')->relation(true)->count();
		$pages = new \Think\Page($count,50);
		$show = $pages->show(); 
	 	$this->assign('page',$show);
		$list = D('Order')->relation(true)
						  ->field('order_sn,id,member_id,address_id,create_time,status,amount,zf_type,coupons_amount')
						  ->order('id desc')
						  ->limit($pages->firstRow.','.$pages->listRows)
						  ->where($data)
						  ->select();
		$this->assign('list',$list);
		$this->assign('ser',array(
			'order_sn'=>$order_sn,'username'=>$username,
			'id'=>$id,'tel'=>$tel,'status'=>$status,
			'time_start'=>$time_start,'time_end'=>$time_end,
		));
		$this->assign('order_status',C('order_status'));
		$this->assign('zf_type',C('zf_type'));
		$this->display();
	}
	//列表
	public function lists(){
		$id = I('id', '','intval');
		$info = D('Order')->relation(true)->field('order_sn,id,member_id,address_id,create_time,status,amount,zf_type,coupons_amount')->find($id);
		$this->assign('info',$info);
		$this->assign('open_validator', true);
		if (IS_AJAX) {
			$response = $this->fetch();
			$this->ajax_return(1, '', $response);
		} else {
			$this->display();
		}
	}
	
	//列表
	public function confirm(){
		$id = I('id','', 'intval');
		$info = D('Order')->relation(true)->field('order_sn,id,member_id,address_id,create_time,status,amount,zf_type')->where(array('id'=>$id))->find();
		$this->assign('info',$info);
		//配送员
		$list = D('loyer')->select();
		$this->assign('list',$list);
		$this->assign('open_validator', true);
		if (IS_AJAX) {
			$response = $this->fetch();
			$this->ajax_return(1, '', $response);
		} else {
			$this->display();
		}
	}
	
	//配送
	public function order_change(){
		$type = I('type','','intval');
		$order_sn = I('order_sn');
		if($type==1){
			$loyer_id = I('express_man_id'); 
			$open_id = D('Loyer')->where(array('id'=>$loyer_id))->getField('openid');
			$response = $this->set_msssg($open_id,$order_sn);
			$response = json_decode($response,true);
			if($response['errcode']==0){
				$db = D('Order')->where(array('order_sn'=>$order_sn))->setField('status',3);
				$db ? $this->success('发货成功,请尽快安排该配送员取货配送','',1) : $this->error('操作失败,请稍后重试或安排其他配送员配送','',1);
			}else{
				$this->error('请让配送员关注配送公众号');
			}
		}else{
			$express_name = I('express_name','','trim');
			$express_sn = I('express_sn');
			$db = D('Order')->where(array('order_sn'=>$order_sn))->setField(array('status'=>3,'express_name'=>$express_name,'express_sn'=>$express_sn));
			$db ? $this->success('操作成功,请尽快快递配送','',1) : $this->error('操作失败,请稍后重试','',1);
		}
	}
	
	//获取微信token
	public function get_access_token(){
		$appid = 'wx783cca6e401593fa';
		$appsecret = 'b1c389a023a8f4b484291da706233425';
		$url ="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		//cookie('access_token',$data['access_token']);
		return $data['access_token'];
	}
	//发送模板消息
	public function set_msssg($openid,$order_sn){
		$order = D('Order')->relation(true)->where(array('order_sn'=>$order_sn))->find();
		foreach($order['sub'] as $k =>$v){
			$info = D('ItemAttr')->where(array('id'=>$v['item_attr']))->find();
			$shop.= $v['item_name'].'['.$info['attr_value'].']*'.$v['number'].' ';
		}
        $access_token=$this->get_access_token();//订单外送通知
		//http://dm.0791jr.com/index.php?m=Home&c=Order&a=send_info&order_sn='.$order_sn.'
		$formwork = '{
				   "touser":"'.$openid.'",
				   "template_id":"khhUJ_sxsFoTSJFiymjo7m43vNimnNtuun8iBMkb7z0",
				   "url":"", 
                   "topcolor":"#FF0000",           
				   "data":{
						   "first": {
							   "value":"您有新的配送订单,订单号为：'.$order_sn.'！",
							   "color":"#173177"
						   },
						   "keyword1":{
							   "value":"'.date('Y-m-d H:i:s',$order['create_time']).'",
							   "color":"#173177"
						   },
						   "keyword2": {
							   "value":"'.get_addr_name($order['place_id']).$order['ads'].'",
							   "color":"#173177"
						   },
						   "keyword3":{
							   "value":"'.$order['amount'].'元",
							   "color":"#173177"
						   },
						   "keyword4": {
							   "value":"收货人：'.$order['name'].'，手机：'.$order['tel'].'",
							   "color":"#173177"
						   },
						   "remark":{
							   "value" : "商品详情：'.$shop.'",
							   "color" : "#FF0000"
						   }
				   }
			   }';		
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
		$curl = curl_init($url);
		$header = array();
		$header[] = 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		// 不输出header头信息
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 伪装浏览器
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
		// 保存到字符串而不是输出
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
		// post数据
		curl_setopt($curl, CURLOPT_POST, 1);
		// 请求数据
		curl_setopt($curl,CURLOPT_POSTFIELDS,$formwork);
		
		$response = curl_exec($curl);	
		curl_close($curl);
		return $response;
	}
	
}
?>