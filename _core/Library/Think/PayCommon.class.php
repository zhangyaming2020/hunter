<?php
// +----------------------------------------------------------------------
// | HDPY APP Pay Common Class
// | Date 2016/03/19
// | Desc APP端支付支付类
// +----------------------------------------------------------------------

namespace Think;

class PayCommon{
	
	//生成签名【MD5】
	public function build_sign($configs,$key){
		if($configs == NULL)
			return '';
		
		//进行升序排序{ASCII升序}
		ksort($configs);
		
		$temp_arrs = array();
		foreach($configs as $_key => $val){
			$temp_arrs[] = "$_key=$val";
		}
		
		$string_query = implode('&',$temp_arrs);
		
		$string_query .= "&key=$key";
		
		$sign = strtoupper(md5($string_query));
		
		return $sign;
	}

	
	//生成微信支付的请求XML字符串
	public function build_xml($params){
		if($params == NULL) return "";	
		
		//进行升序排序{ASCII升序}
		ksort($params);	
		
		$xml_str = '<xml>';
		foreach($params as $key => $val){
			switch($key){
				case 'appid':
					$xml_str .= '<appid>'.$val.'</appid>'; 
					break;
				case 'mch_id':
					$xml_str .= '<mch_id>'.$val.'</mch_id>'; 
					break;
				case 'nonce_str':
					$xml_str .= '<nonce_str>'.$val.'</nonce_str>'; 
					break;
				case 'sign':
					$xml_str .= '<sign>'.$val.'</sign>'; 
					break;
				case 'body':
					$xml_str .= '<body>'.$val.'</body>'; 
					break;
				case 'out_trade_no':
					$xml_str .= '<out_trade_no>'.$val.'</out_trade_no>'; 
					break;
				case 'total_fee':
					$xml_str .= '<total_fee>'.$val.'</total_fee>'; 
					break;
				case 'spbill_create_ip':
					$xml_str .= '<spbill_create_ip>'.$val.'</spbill_create_ip>'; 
					break;
				case 'notify_url':
					$xml_str .= '<notify_url>'.$val.'</notify_url>';
					break;
				case 'trade_type':
					$xml_str .= '<trade_type>'.$val.'</trade_type>';
					break;
			}
		}
		$xml_str .= '</xml>';
		
		return $xml_str;
	}
	
	//解析微信返回的XML内容
	public function parsing_wx_xml($xml_str){
		if($xml_str=='') return NULL;
		
		$xml_data = array();
		
		$xml_data = (array)simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
		
		return $xml_data;
	}
	
	//发送支付请求
	public function send($url,$params){
		if($url=='' || $params==NULL) return NULL;	
		
		$response = $this->postCurl($url,$params,NULL);
		
		if($response['status']==1 && $response['data']!='')
			return $this->parsing_wx_xml($response['data']);
		
		return '';
	}
	
	/****
	** postCurl方法
	****/
	public function postCurl($url, $body, $header = array(), $method = "POST")
	{
		//array_push($header, 'Accept:application/json');
		//array_push($header, 'Content-Type:application/json');
		//array_push($header, 'http:multipart/form-data');
	
		$ch = curl_init();//启动一个curl会话
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		switch ($method){
			case "GET" : 
				curl_setopt($ch, CURLOPT_HTTPGET, true);
				break; 
			case "POST": 
				curl_setopt($ch, CURLOPT_POST,true); 
				break; 
			case "PUT" : 
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
				break; 
			case "DELETE":
				curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
				break;
		}
		
		curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		
		if (isset($body{3}) > 0) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}
		
		if (count($header) > 0) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
	
		$response = curl_exec($ch);
		$err = curl_error($ch);	
		
		curl_close($ch);
		//clear_object($ch);
		//clear_object($body);
		//clear_object($header);
		
		if ($err) {
			return array('error' => 1 , 'message' => $err);
		}
	
		return array('status' => 1 , 'data' => $response);
	}
	
	/* *
	 * 支付宝接口RSA函数
	 * 详细：RSA签名、验签、解密
	 * 版本：3.3
	 * 日期：2012-07-23
	 * 说明：
	 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	 */
	 
	 /*	*
	 *	生成支付宝签名参数
	 *	*/
	public function build_zfb_sign_str($configs){
		if($configs == NULL)
			return '';	
		
		$temp_arrs = array();
		foreach($configs as $_key => $val){
			if($val!=''){
				$temp_arrs[] = "$_key=\"$val\"";
			}
		}
		
		$string_query = implode('&',$temp_arrs);
		
	
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){ 
			$string_query = stripslashes($string_query);
		}
		
		return $string_query;
	}

	/**
	 * RSA签名
	 * @param $data 待签名数据
	 * @param $private_key_path 商户私钥文件路径
	 * return 签名结果
	 */
	public function rsaSign($data, $private_key_path) {
		$priKey = file_get_contents($private_key_path);
		$res = openssl_get_privatekey($priKey);
		openssl_sign($data, $sign, $res);
		openssl_free_key($res);
		//base64编码
		$sign = base64_encode($sign);
		$sign = urlencode($sign);
		return $sign;
	}
	
	/**
	 * RSA验签
	 * @param $data 待签名数据
	 * @param $ali_public_key_path 支付宝的公钥文件路径
	 * @param $sign 要校对的的签名结果
	 * return 验证结果
	 */
	public function rsaVerify($data, $ali_public_key_path, $sign){
		$pubKey = file_get_contents($ali_public_key_path);
		$res = openssl_get_publickey($pubKey);dump($res);exit;
		$result = (bool)openssl_verify($data, base64_decode($sign), $res);
		openssl_free_key($res);    
		return $result;
	}
	
	/**
	 * RSA解密
	 * @param $content 需要解密的内容，密文
	 * @param $private_key_path 商户私钥文件路径
	 * return 解密后内容，明文
	 */
	public function rsaDecrypt($content, $private_key_path) {
		$priKey = file_get_contents($private_key_path);
		$res = openssl_get_privatekey($priKey);
		//用base64将内容还原成二进制
		$content = base64_decode($content);
		//把需要解密的内容，按128位拆开解密
		$result  = '';
		for($i = 0; $i < strlen($content)/128; $i++  ) {
			$data = substr($content, $i * 128, 128);
			openssl_private_decrypt($data, $decrypt, $res);
			$result .= $decrypt;
		}
		openssl_free_key($res);
		return $result;
	}
	
}
?>