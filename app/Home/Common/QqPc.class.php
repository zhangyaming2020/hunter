<?php

 namespace Home\Common;

 header("Content-type: text/html; charset=utf-8");   

/**

 * 微信授权相关接口

 * 

 * @link http://www.phpddt.com

 */

 

class QqPc {


    //高级功能-》开发者模式-》获取

    private $app_id ='101422606';

    private $app_secret ='66fa6524a59615e967c05f473fefe75a';    

 

 

    /**   

     * 获取qq授权链接

     * 

     * @param string $redirect_uri 跳转地址

     * @param mixed $state 参数

     */
	//pc端
    public function get_authorize_url($redirect_uri = '')

    {
        $state=md5(uniqid(rand(),TRUE)); 
		session('qq',$state);
        $redirect_uri = urlencode($redirect_uri);

        return "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id={$this->app_id}&redirect_uri={$redirect_uri}&state={$state}&display="; 

    }

    /**

     * 获取授权token

     * 

     * @param string $code 通过get_authorize_url获取到的code

     */

    public function get_access_token($code = '',$redirect_uri = '')
    {
    	
        $redirect_uri = urlencode($redirect_uri);
        $token_url ="https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id={$this->app_id}&client_secret={$this->app_secret}&code={$code}&redirect_uri={$redirect_uri}";
         $ss=file_get_contents($token_url);
		
         parse_str($ss,$token_data); 
		 return $token_data;   

  }
	//得到Open_id
	public function get_open_id($token=''){
		
		$open_url="https://graph.qq.com/oauth2.0/me?access_token={$token}"; 
		  
		$open_id = $this->http($open_url);
		 
        if($open_id[0] == 200)
     
        {

            return $open_id[1];

        }
		   return FALSE;
		 
		
	}

    /**

     * 获取授权后的qq用户信息

     * 

     * @param string $access_token

     * @param string $open_id

     */

    public function get_user_info($access_token = '',$client_id='', $open_id = '')

    {
        if($access_token && $open_id && $client_id)

        {
            $info_url = "https://graph.qq.com/user/get_user_info?access_token={$access_token}&oauth_consumer_key={$client_id}&openid={$open_id}";
            $info=file_get_contents($info_url);
			$info=json_decode($info,TRUE);
			return $info;

        }
           return FALSE;
    }

    
 
    public function http($url)
    {

      $response = file_get_contents($url); 
	if (strpos($response, "callback") !== false)
   {
      $lpos = strpos($response, "(");
      $rpos = strrpos($response, ")");
      $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
      $msg = json_decode($response,TRUE);
      if (isset($msg->error))
      {
        return array(0,$msg);  
      }else{   
      	
	    return array(200,$msg);	
     }
   } 
	      return false;


    }

 

}