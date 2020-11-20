<?php
 namespace Home\Common;
 header("Content-type: text/html; charset=utf-8");   
/**
 * 微信授权相关接口
 * 
 * @link http://www.phpddt.com
 */
 
class Wechat {
    
    //高级功能-》开发者模式-》获取
    private $app_id ='wxf553e7211690fc08';
    private $app_secret ='f3aaee1dcf7a97929dfacb44dcc9bfef';    
 
 
    /**   
     * 获取微信授权链接
     * 
     * @param string $redirect_uri 跳转地址
     * @param mixed $state 参数
     */
    public function get_authorize_url($redirect_uri = '')
    {
//      $redirect_uri = urlencode($redirect_uri);
//      $state=md5(uniqid(rand(),TRUE)); 		session('wechat',$state); 
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
    }
    
    /**
     * 获取授权token
     * 
     * @param string $code 通过get_authorize_url获取到的code
     */
    public function get_access_token($app_id = '', $app_secret = '', $code = '')
    {
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$code}&grant_type=authorization_code";
//		dump($token_url);exit;
        $token_data = $this->http($token_url);
        
        if($token_data[0] == 200)
        {
            return json_decode($token_data[1], TRUE);
        }
        
        return FALSE;
    }
    
    /**
     * 获取授权后的微信用户信息
     * 
     * @param string $access_token
     * @param string $open_id
     */
    public function get_user_info($access_token = '', $open_id = '')
    {
        if($access_token && $open_id)
        {
            $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
            $info_data = $this->http($info_url);
            
            if($info_data[0] == 200)
            {
                return json_decode($info_data[1], TRUE);
            }
        }
        
        return FALSE;
    }
    
    public function http($url)
    {
        $return = file_get_contents($url);  
	    $count = count(json_decode($return, TRUE));
	    if($count >= 5){
	   	 	return array(200,$return);	
	    }else{   
	   		return array(0,$return);
	    }
    }
 
}