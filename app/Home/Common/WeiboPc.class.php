<?php

 namespace Home\Common;

 header("Content-type: text/html; charset=utf-8");   

/**

 * 微信授权相关接口

 * 

 * @link http://www.phpddt.com

 */

 

class WeiboPc {

    

    //高级功能-》开发者模式-》获取

    private $app_id ='3925223668';

    private $app_secret ='d857c44647f205616fefbf2e0ae3ec1e';      

 

 

    /**   

     * 获取微信授权链接

     * 

     * @param string $redirect_uri 跳转地址

     * @param mixed $state 参数

     */
  public function get_authorize_url($redirect_uri = '')

    {
    	
//      $state=md5(uniqid(rand(),TRUE)); 
//		session('weibo',$state);
        $redirect_uri = urlencode($redirect_uri);      
        return "https://open.weibo.cn/oauth2/authorize?client_id={$this->app_id}&response_type=code&redirect_uri={$redirect_uri}&state={$state}";    
 
    }

    /**

     * 获取授权token

     * 

     * @param string $code 通过get_authorize_url获取到的code

     */

    public function get_access_token($redirect_uri = '', $code = '')

    {
	
			 $data = array ('client_id' =>$this->app_id,'client_secret'=>$this->app_secret,'grant_type'=>'authorization_code','redirect_uri'=>$redirect_uri,'code'=>$code);
             $data = http_build_query($data);
			 $opts=array('http'=>array('method'=>'POST','header'=> 'Content-type:application/x-www-form-urlencoded','content' => $data));
             $context = stream_context_create($opts);
             $token_date=file_get_contents('https://api.weibo.com/oauth2/access_token',false,$context);
			 $token_date=json_decode($token_date,TRUE);
	         return $token_date;
   
    }

    

    /**

     * 获取授权后的微信用户信息

     * 

     * @param string $access_token

     * @param string $open_id

     */

    public function get_user_info($access_token = '', $uid = '')

    {

        if($access_token && $uid)

        {
            $info_url = "https://api.weibo.com/2/users/show.json?access_token={$access_token}&uid={$uid}";
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