<?php
namespace Admin\Controller;
use Admin\Org\Image;
use Admin\Org\SmsDemo;
class IndexController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();//echo 343;exit;
        $this->_mod = D('Menu');
    }
	public function index() {
        $top_menus = $this->_mod->admin_menu(0);
        foreach($top_menus as $k=>$v){
        	$top_menus[$k]['sub']=M('menu')->where(array('pid'=>$v['id']))->select();
        }
        $this->assign('top_menus', $top_menus);
		$city_id = $_SESSION['admin']['city_id'];
		$name = M('place') -> where(array('type'=>2,'bd_city_code'=>$city_id)) -> getField('name');
        $my_admin = array('username'=>$_SESSION['admin']['username'], 'rolename'=>$_SESSION['admin']['role_id'],'name'=>$name);
        $this->assign('my_admin', $my_admin);
        $this->display();
        //$Home = A('Home/Home');
        //$Home->auto();
    }
    public function test(){
        //header("Content-type:text/html;charset=utf-8");
       // $lang = json_decode(L('js_lang'));
        $stone_name = L('stone_name');
        $js_lang = L('js_lang_st');
        echo($js_lang);
        echo($stone_name);

    }

    public function panel() {
        $message = array();
        if (is_dir('./install')) {
            $message[] = array(
                'type' => 'error',
                'content' => "您还没有删除 install 文件夹，出于安全的考虑，我们建议您删除 install 文件夹。",
            );
        }
        if (APP_DEBUG == true) {
            $message[] = array(
                'type' => 'error',
                'content' => "您网站的 DEBUG 没有关闭，出于安全考虑，我们建议您关闭程序 DEBUG。",
            );
        }

        $this->assign('message', $message);
        $system_info = array(
            'pinphp_version' => PIN_VERSION . ' RELEASE '. PIN_RELEASE .' [<a href="http://www.pinphp.com/" class="blue" target="_blank">查看最新版本</a>]',
            'server_domain' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            'server_os' => PHP_OS,
            'web_server' => $_SERVER["SERVER_SOFTWARE"],
            'php_version' => PHP_VERSION,
            'mysql_version' => mysql_get_server_info(),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time') . '秒',
            'safe_mode' => (boolean) ini_get('safe_mode') ?  L('yes') : L('no'),
            'zlib' => function_exists('gzclose') ?  L('yes') : L('no'),
            'curl' => function_exists("curl_getinfo") ? L('yes') : L('no'),
            'timezone' => function_exists("date_default_timezone_get") ? date_default_timezone_get() : L('no')
        );
        $this->assign('system_info', $system_info);
        $this->display();
    }
//发送成功返回true,结果仅供参数，不保证完全正确  
	function SendSMS($RecNum,$ParamString,$SignName,$TemplateCode,$AccessKeyId,$AccessKeySecret)  
	{  
	  $url='https://sms.aliyuncs.com/';//短信网关地址  
	  $Params['Action']='SingleSendSms';//操作接口名，系统规定参数，取值：SingleSendSms  
	  //$Params['RegionId']='cn-hangzhou';//机房信息  
	  $Params['AccessKeyId']=$AccessKeyId;//阿里云颁发给用户的访问服务所用的密钥ID  
	  //$Params['Format']='JSON';//返回值的类型，支持JSON与XML。默认为XML  
	  $Params['ParamString']=rawurlencode($ParamString);//短信模板中的变量；数字需要转换为字符串；个人用户每个变量长度必须小于15个字符。  
	  $Params['RecNum']=$RecNum;//目标手机号  
	  $Params['SignatureMethod']='HMAC-SHA1';//签名方式，目前支持HMAC-SHA1  
	  $Params['SignatureNonce']=time();//唯一随机数  
	  $Params['SignatureVersion']='1.0';//签名算法版本，目前版本是1.0  
	  $Params['SignName']=rawurlencode($SignName);//管理控制台中配置的短信签名（状态必须是验证通过）  
	  $Params['TemplateCode']=$TemplateCode;//管理控制台中配置的审核通过的短信模板的模板CODE（状态必须是验证通过）  
	  $Params['Timestamp']=rawurlencode(gmdate("Y-m-d\TH:i:s\Z"));//请求的时间戳。日期格式按照ISO8601标准表示，  
	                                                              //并需要使用UTC时间。格式为YYYY-MM-DDThh:mm:ssZ  
	  $Params['Version']='2016-09-27';//API版本号，当前版本2016-09-27  
	  ksort($Params);  
	  $PostData='';  
	  foreach ($Params as $k => $v) $PostData.=$k.'='.$v.'&';  
	  $PostData.='&Signature='.rawurlencode(base64_encode(hash_hmac('sha1','POST&%2F&'.rawurlencode(substr($PostData,0,-1)),$AccessKeySecret.'&',true)));  
	  $httphead['http']['method']="POST";  
	  $httphead['http']['header']="Content-type:application/x-www-form-urlencoded\n";  
	  $httphead['http']['header'].="Content-length:".strlen($PostData)."\n";  
	  $httphead['http']['content']=$PostData;  
	  $httphead=stream_context_create($httphead);  
	  $result=@simplexml_load_string(file_get_contents($url,false,$httphead));  
	  return !isset($result->Code);  
	}  

	 //发送验证码

	public function send_email($datas){   

	  $data = $this->get_datas($datas);dump($data);exit;
	  //判断当前短信模型下发送次数是否
	 // $res =M('msg_code')->where(array('type'=>$data['type'],'mobile'=>$data['mobile'],'add_time'=>array('egt',strtotime(date('Y-m-d'))),'add_time'=>array('elt',strtotime(date('Y-m-d')+24*3600-1))))->count();
	//  ($res>=6) && $this->print_error_status('params_error',$datas['pack_no'], array('ERROR_Param_Format' => '当前页面当天下短信不能超过5次,请明天再来'));
	  $code=mt_rand(111111, 999999);
	  
	  $string='验证码'.$code.',您正在注册成为新用户，感谢您的支持';
	  echo $this->SendSMS('15970615124',$string,'阿里云短信测试专用','SMS_117580241','LTAID4JdqQWpxHMR','H9C3cL2Jb7XwSBpCVFlMNJofLr0xPX');exit;
	  

	}     
    public function login() {
    	
        if (IS_POST) {
            $username = I('username','', 'trim');
            $password = I('password','', 'trim');
//          $verify_code = I('verify_code','', 'trim');
//          if(session('verify') != md5($verify_code)){
//              $this->error(L('verify_code_error'));
//          }
            $admin = M('Admin')->where(array('username'=>$username, 'status'=>1))->find();
          // dump(array(md5($password),));exit;
            if (!$admin) {
                $this->error(L('admin_not_exist'));
            }
            //dump(array($admin['password'],md5($password)));exit;
            if ($admin['password'] != md5($password)) {
                $this->error(L('password_error'));
            }
//print_r($admin);
            session('admin', array(
                'id' => $admin['id'],
                'role_id' => $admin['role_id'],
                'username' => $admin['username'],
                'city_id' => $admin['city_id'],
            ));
            M('Admin')->where(array('id'=>$admin['id']))->save(array('last_time'=>time(), 'last_ip'=>get_client_ip()));
            $this->success(L('login_success'), U('index/index'));
        } else {
//      	$code=mt_rand(111111, 999999);
//	 $sms =new SmsDemo();
//	 $response=$sms->sendSms();
//echo "发送短信(sendSms)接口返回的结果:\n";
//print_r($response);exit;
            $this->display();
        }
    }

    public function logout() {
        session('admin', null);
        $this->success(L('logout_success'), U('index/login'));
        exit;
    }

    public function verify_code() {
    	ob_clean();
        Image::buildImageVerify(4,1,'gif','50','24');
    }

    public function left() {
        $menuid = I('menuid', 'intval');
        if ($menuid) {
            $left_menu = $this->_mod->admin_menu($menuid);
            foreach ($left_menu as $key=>$val) {
                $left_menu[$key]['sub'] = $this->_mod->admin_menu($val['id']);
            }
        } else {
            $left_menu[0] = array('id'=>0,'name'=>L('common_menu'));
            $left_menu[0]['sub'] = array();
            if ($r = $this->_mod->where(array('often'=>1))->select()) {
                $left_menu[0]['sub'] = $r;
            }
            array_unshift($left_menu[0]['sub'], array('id'=>0,'name'=>L('common_menu_set'),'module_name'=>'index','action_name'=>'often_menu'));
        }
        $this->assign('left_menu', $left_menu);
        $this->display();
    }

    public function often() {
        if (isset($_POST['do'])) {
            $id_arr = isset($_POST['id']) && is_array($_POST['id']) ? $_POST['id'] : '';
            $this->_mod->where(array('ofen'=>1))->save(array('often'=>0));
            $id_str = implode(',', $id_arr);
            $this->_mod->where('id IN('.$id_str.')')->save(array('often'=>1));
            $this->success(L('operation_success'));
        } else {
            $r = $this->_mod->admin_menu(0);
            $list = array();
            foreach ($r as $v) {
                $v['sub'] = $this->_mod->admin_menu($v['id']);
                foreach ($v['sub'] as $key=>$sv) {
                    $v['sub'][$key]['sub'] = $this->_mod->admin_menu($sv['id']);
                }
                $list[] = $v;
            }
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function map() {
        $r = $this->_mod->admin_menu(0);
        $list = array();
        foreach ($r as $v) {
            $v['sub'] = $this->_mod->admin_menu($v['id']);
            foreach ($v['sub'] as $key=>$sv) {
                $v['sub'][$key]['sub'] = $this->_mod->admin_menu($sv['id']);
            }
            $list[] = $v;
        }
        $this->assign('list', $list);
        $this->display();
    }

    public function auto(){
        //每天自动侦测结算利息
        //实例化模式
        $LoanInterest = D('LoanInterest');
        $Loan = D('Loan');
        $Invest = D('Invest');
        $Member = D('Member');
        $Finance = D('Finance');

        $t_day = strtotime(date('Y-m-d'));
        $loan_interest = $LoanInterest->where(array('op_time'=>$t_day,'status'=>0))->select();
        $finance_data = array();
        if($loan_interest){
            foreach($loan_interest as $val){
                //启动事务，防止断点掉数据
                $Invest->startTrans();
                $loan = $Loan->find($val['loan_id']);
                //调用投资人信息
                $invest_list = $Invest->field('id,order_id,member_id,invest_amount')->where(array('loan_id'=>$loan['id'],'array'=>3))->select();
                //分配利息到投资人 记录资金流水
                $interest = interest($loan['total'] *10000,$loan['interest_rate']/100,1);//这个配资的每个月月息

                foreach($invest_list as $invest){
                    //回利息到余额
                    $member_interest_earning = ($interest * $invest['invest_amount'])/($loan['total'] * 10000);//各投资人的利息收入
                    $member_earning_op = $Member->where(array('id'=>$invest['member_id']))->setInc('balance',$member_interest_earning);
                    if(!$member_earning_op){
                        $this->error($Member->getError());
                    }
                    //生成资金流水记录
                    $finance_data[] = array(
                        'order_id' => make_order_id('Finance'),
                        'total' => $member_interest_earning,
                        'log_type' => 6,
                        'member_id' => $invest['member_id'],
                        'status' => 1,
                        'remark' => '订单号：'.$invest['order_id'].'的利息收入',
                        'item_id' => $invest['id'],
                        'create_time' => time(),
                    );
                }
                $member_interest_earning_finance_op = $Finance->addAll($finance_data);
                $loan_interest_op = $LoanInterest->where(array('id'=>$val['id']))->setField(array('status'=>1));
                if($member_earning_op && $member_interest_earning_finance_op && $loan_interest_op){
                    $Invest->commit();
                    IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                    $this->success('结算成功！');
                }else{
                    $Invest->rollback();//不成功，则回滚
                    $this->error('失败！');
                }
            }

        }
    }
   
}