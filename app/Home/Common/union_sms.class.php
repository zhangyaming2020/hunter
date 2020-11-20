<?php
    namespace Home\Common;
	class Sms
	{
		private $user;
		private $account;
		private $appver;
		private $pwd;
		private $timestamp;
		private $token;
		
		//定义一个构造方法初始化赋值
		function __construct($account,$pwd) {
			$this->user = $account."_dev";
			$this->account = $account;
			$this->pwd = $pwd;
			$this->appver = 1;
			$this->timestamp = date("YmdHis");
		}
		
		//获取token
		public function get_token(){
			//组合参数 
			$arr = array(
				'appver' => $this->appver,
				'timestamp' => $this->timestamp,
				'user' => $this->user,
			);
			$arr = $this->_secret($arr,$this->pwd);
			
			//登录获取token
			$get_token = $this->_curl("http://xtapi.union400.com/api/union400Login.action",http_build_query($arr));
			$this->token = $get_token['data']['token'];
		}
		
		//发送短信
		public function send($mobile,$data,$id){
			//组合参数
			$arr_sms = array(
				'appver' => $this->appver,
				'user' => $this->user,
				'timestamp' => $this->timestamp,
				'account' => $this->account,
				'data' => $data,
				'mobile' => $mobile,
				'templateId' => $id,      
			); 
			$arr_sms = $this->_secret($arr_sms,$this->token);   
			
			//发送短信  
			$result = $this->_curl("http://xtapi.union400.com/api/sms/requestSmsSend.action",http_build_query($arr_sms));  
			return $result;
		}
		
		//生成secret
		private function _secret($arr,$param){
			//重新排序
			ksort($arr);
			//生成secret
			$str = "";
			foreach($arr as $k=>$v){
				$str .=$k.$v;
			}
			$secret = md5(urlencode($str.$param));
			$arr['secret'] = $secret;
			
			return $arr;
		}
		
		//发送请求
		private function _curl($url,$data){
			$header = array(
				'Content-Type:application/x-www-form-urlencoded',
			);
			
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$result = curl_exec($ch);
			curl_close($ch);
			
			return json_decode($result,true);
		}
		
		
	}