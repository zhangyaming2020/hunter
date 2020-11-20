<?php
namespace app\Controller;
use Think\Controller;
use Think\ApiCommon;
use Think\Rc4;
use Admin\Org\UploadFile;
class ApiController extends Controller{    
	private $request_datas = NULL;    
	private static $api;    
	private static $api_status_code;    
	private static $api_list;    
	public function _initialize()    {
		$setting = [];        
		if(F('setting')){            
			$setting = F('setting');        
		}
		else
		{            
				$setting = D('Setting')->setting_cache();            
				F('setting',$setting);        
		}        
		C($setting);        
		if(NULL == self::$api )  
		self::$api = new ApiCommon();        
		if(NULL == self::$api_status_code)
		include "api_list.php";         
		self::$api_status_code = $Status_Code;        
		if(NULL == self::$api_list)     
		self::$api_list = $api_list;    
	}    
	public function wang()
	{       
		 echo "1";    exit;
	}    //获取API状态码    
	protected function get_api_status_code()
	{        
		return self::$api_status_code;    
	}    //获取API列表    
	protected function get_api_list()
	{        
		return self::$api_list;    
	}    //处理API请求    
	public function processing()
	{   
		//验证请求参数        
		$params = $this->check_input();        
		(NULL == $params) && self::$api->Error(self::$api_status_code,'params_error');        //验证请求合法性 
       $this->Authenticating_API($params);        //解密用户ID  
       $params['user_id'] = $this->decode_user_id($params['user_id']);        //存储请求数据  
       $this->request_datas = $params;        //派发请求处理 
       $this->Distribution_Request();    
    }    //检查客户端的请求参数 
	private function check_input()
	{        
		//从APP请求中获取数据        
		$json_request_str = I('requestData','','trim');       
	 	('' == $json_request_str) && self::$api->Error(self::$api_status_code,'params_error'); 
	 	//解析JSON请求参数,并转换成数组        
	 	$requestData = json_decode($json_request_str,true);
		//var_dump($requestData);exit;
	 	(NULL == $requestData || 0 >= count($requestData)) && self::$api->Error(self::$api_status_code,'params_error');        
	 	$pack_no    = $requestData['pack_no'];        
	 	$date       = $requestData['date'];        
	 	$user_id    = $requestData['user_id'];        
	 	$deviceId   = $requestData['deviceId'];        
	 	$token      = $requestData['token'];        
	 	$roles      = $requestData['roles'];        
	 	$data       = $requestData['data'];        
	 	('' == $pack_no || '' == $date || '' == $deviceId) && self::$api->Error(self::$api_status_code,'params_error');        
	 	return ['pack_no' => $pack_no , 'date' => $date , 'user_id' => $user_id , 'deviceId' => $deviceId , 'token' => $token , 'roles' => $roles , 'data' => $data];    
	}    
 	//解密客户端传过来的用户ID    
 	private function decode_user_id($user_id)
 	{   
 		$rc4 = new Rc4();        
 		$C_userid_encryption_key = '#fkj664@';//C('USERID_ENCRYPTION_KEY'); 
 		//dump($rc4->authcode(str_replace('|jia|','+',$user_id),'DECODE',$C_userid_encryption_key));exit;      
 		if(NULL != $rc4 && '' != $C_userid_encryption_key && '' != $user_id)            
 		return $rc4->authcode(str_replace('|jia|','+',$user_id),'DECODE',$C_userid_encryption_key);
 		return $user_id;    
    }    
	//验证API请求    
	private function Authenticating_API($params)
	{        
		include "api_list.php"; 
		$Api_key =$key;       
	    $C_Api_List = $api_list;  
	    ('' == $Api_key || NULL == $C_Api_List) && self::$api->Error(self::$api_status_code,'server_error',$params['pack_no']);        //验证请求的API是否存在    
	    $Exists_API = $C_Api_List[$params['pack_no']];
	    (NULL == $Exists_API || 0 >= count($Exists_API)) && self::$api->Error(self::$api_status_code,'server_error',$params['pack_no']);        //生成验证串   
	    $chk_str = md5($params['pack_no'].$params['user_id'].$params['date'].$Api_key);        //验证token是否正确        
	   //dump(array($chk_str,$params['token']));exit;
	   (strtoupper($chk_str) != strtoupper($params['token'])) && self::$api->Error(self::$api_status_code,'authenticating_api_failed',$params['pack_no']);
	    
	    return true;    
	}    
	//派发请求    
	private function Distribution_Request()
	{        
		//获取API        
		$request_api = self::$api_list[$this->request_datas['pack_no']]; 
		(NULL == $request_api) && self::$api->Error(self::$api_status_code,'server_error',$this->request_datas['pack_no']);        
		$controller = $request_api['controller'];        
		$action = $request_api['action'];        
		('' == $controller || '' == $action) && self::$api->Error(self::$api_status_code,'server_error',$this->request_datas['pack_no']);        
		//执行控制器跳转，派发请求处理        
		eval("\$execute = new \$controller();");        
		eval("\$execute->\$action(\$this->request_datas);");    
	}    
	//响应APP端    
	protected function json_Response($response_code,$pack_no,$data = NULL)
	{        
		include "api_list.php"; 
		$C_Status_Code = $Status_Code;        
		//清空缓冲区内容        
		ob_end_clean();        
		//设置json响应头        
		header("Content-type: application/json");        
		if(NULL == $data)            
		$data = ['list' => NULL];        
		if(NULL != $C_Status_Code[$response_code])
		{            
			$response_json_array = ['data' => $data , 'pack_no' => $pack_no , 'status' =>  $C_Status_Code[$response_code]];
			echo json_encode($response_json_array,true);        
		}
		else
		{            
			echo json_encode(['data' => $data , 'pack_no' => $pack_no , 'status' => ['code' => -1 , 'message' => 'response_error']]);        
		}        
		exit();    
	}   
	//从请求中获取处理数据   
	protected function get_datas($request_datas)
	{    	        
		$data = $request_datas['data'];        //
		($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$request_datas['pack_no']);        $data['user_id'] = $request_datas['user_id'];        
		return $data;    
	}    
	//实现输出操作状态码    
	protected function print_error_status($error_status,$pack_no,$data = NULL)
	{        
		self::$api->Error(self::$api_status_code,$error_status,$pack_no,$data);    
	}    
	//验证用户ID是否存在    
	protected function check_user_id($datas)
	{        
		if(NULL == $datas || '' == $datas['user_id'])            
		$this->print_error_status('params_error',$datas['pack_no'],['ERROR_Param_Empty' => '请先登录']);    
	}    
		
	//从请求中获取用户ID    
	protected function get_user_id($datas)
	{   
		if(NULL == $datas || '' == $datas['user_id'])
		$this->print_error_status('params_error',$datas['pack_no'],['ERROR_Param_Empty' => 'user_id']);        
		return $datas['user_id'];    
	}    
	//*****文件上传 (开始)*****    //处理文件上传、图片上传    
	protected function upload_file($file_name,$dir='item')
	{        
		if('' == $file_name)            
		return "";        
		$filepath = "";        
		if('' != $_FILES[$file_name]['name'] && $_FILES['img']['size'] > 0 )
		{            
			$up_info = $this->_upload($_FILES[$file_name],$dir);            
			if(0 == $up_info['error'])
			{//                
				$filepath = '/'.$up_info['info'][0]['savepath'].$up_info['info'][0]['savename'];
				$filepath = $up_info['info'][0]['savename'];
			}        
		}        
		return $filepath;    
	}    
	/**     * 上传文件默认规则定义     */
    public function _upload_init($upload) 
    {        
    	$allow_max = C('pin_attr_allow_size'); 
    	//读取配置        
    	$allow_exts = explode(',', C('pin_attr_allow_exts')); 
    	//读取配置        //如果从数据库读取失败，则从配置文件中加载        
    	if('' == $allow_max) $allow_max = C('yb_attr_allow_size');        
    	if(NULL == $allow_exts || 0 >= count($allow_exts) || NULL == $allow_exts[0]) $allow_exts =explode(',', C('yb_attr_allow_exts')); //读取配置        
    	$allow_max && $upload->maxSize = $allow_max * 1024;   
    	//文件大小限制        
    	$allow_exts && $upload->allowExts = $allow_exts;  //文件类型限制   
        $upload->saveRule = 'uniqid';
     	return $upload;    
     }    
	/**     * 上传文件     */    
	public function _upload($file, $dir = '', $thumb = [], $save_rule='uniqid') 
	{        
		$upload = new UploadFile();        
		if ($dir) 
		{            
			$base_path = C('pin_attach_path');            //如果从数据库读取失败，则从配置文件中加载 
			if('' == $base_path) $base_path = C('yb_attach_path');
			{
			    $upload_path = $base_path . $dir . '/';
			    $upload-> savePath = $upload_path;        
			}        
		    if ($thumb) 
		    {
		    	$upload->thumb = true;            
		    	$upload->thumbMaxWidth = $thumb['width'];
		    	$upload->thumbMaxHeight = $thumb['height'];            
		    	$upload->thumbPrefix = '';            
		    	$upload->thumbSuffix = isset($thumb['suffix']) ? $thumb['suffix'] : '_thumb';            
		    	$upload->thumbExt = isset($thumb['ext']) ? $thumb['ext'] : '';            
		    	$upload->thumbRemoveOrigin = isset($thumb['remove_origin']) ? true : false;        
		    }        
	    	//自定义上传规则        
	    	$upload = $this->_upload_init($upload);        
	    	if('uniqid' != $save_rule){            
	    		$upload->saveRule = $save_rule;        
	    	}        
	    	if ($result = $upload->uploadOne($file)) {            
    		return ['error'=>0, 'info'=>$result];        
    		} 
    		else 
    		{            
	    		return ['error'=>1, 'info'=>$upload->getErrorMsg()];        
	    	}    
		}
	}    
    //多文件上传   
     public function _uploads($dir='',$thumb = [])
     {        
			     	
		$upload = new UploadFile();        
		if (!empty($thumb)) 
		{            
			$upload->thumb = true;            
			$upload->thumbMaxWidth = $thumb['width'];            
			$upload->thumbMaxHeight = $thumb['height'];            
			$upload->thumbPrefix = '';            
			$upload->thumbSuffix = isset($thumb['suffix']) ? $thumb['suffix'] : '_thumb';            
			$upload->thumbExt = isset($thumb['ext']) ? $thumb['ext'] : '';            
			$upload->thumbRemoveOrigin = isset($thumb['remove_origin']) ? true : false;        
		}        
		if ($dir) 
		{            
			$base_path = C('DATA_PATH');//            dump($base_path);die;            
			//如果从数据库读取失败，则从配置文件中加载            
			if('' == $base_path)
			{
			$base_path = C('yb_attach_path');            
			$upload_path = $base_path . $dir . '/';            
			$upload->savePath = $upload_path;        
			}        
			//自定义上传规则        
			$upload = $this->_upload_init($upload);        
			$result = $upload->upload();        
			$maps = ['result' => $result,            
			'uploadinfos' => $upload->uploadFileInfo];//        dump($result);die;        dump($upload->getErrorMsg());die;        
			return $maps; 
		}   
	}    
	//*****文件上传 (结束)*****    //上传图片    
	public function img($list = 'order',$thumb)    
	{        //
		$list .= '/'.date('ym/d/');        
		$files_path = $this->_uploads($list,$thumb);        
		if(NULL != $files_path && 1 == $files_path['result'])
		{            
			foreach($files_path['uploadinfos'] as $item)
			{                
				$paths[] = $item['savename'];            
			}        
		}        
				return  $paths;  
    }  
}