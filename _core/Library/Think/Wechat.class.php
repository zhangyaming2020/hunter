<?php
class Wechat {
    /**   
    public function get_authorize_url($redirect_uri = '', $state = '')
    /**
    
    /**
    public function get_user_info($access_token = '', $open_id = ''){
	/**
    public function http($url)
    protected function http_request($url,$data = null){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($data)){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
}