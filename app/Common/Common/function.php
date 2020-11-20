<?php
		
     function position(){
		$uid = $_COOKIE['userid'];
		//获取定位相关信息
        $visit_position = cookie('visit_position');
		if(!$visit_position){ //如果没定位城市
			$city_id = M('member') -> where(array('id'=>$uid)) -> getField('city_id');
			$city = M('place') -> where(array('type'=>2,'bd_city_code'=>$city_id))-> find();
			if($city_id){	//是否个人中心设置
				$visit_position = array(
                'city_id' => $city['id'],
                'city_code' => $city['bd_city_code'],
                'city_name' => $city['name'],
           		 );
			}else{	//默认南昌
				 $visit_position = array(
                'city_id' => 16,
                'city_code' => 163,
                'city_name' => '南昌',
           		 );
			}
		}
		return $visit_position ;
	}

    //获取城市信息
    function csxx(){
        $visit_position = cookie('visit_position');
        $city = M('place') ->where(array('id'=>$visit_position,'status'=>1))->find();
        return $city ;
    }
	
function getcate($apply){
   $cate = unserialize($apply);
   if(is_array($cate)){
   	   $pid = end($cate);
	   return M('item_cate')->where(array('id'=>$pid))->getField("name");
   }else{
   		return M("item")->where(array('id'=>$cate))->getField("title");
   }
}


/*二维数组按指定的键值排序*/
function array_sort($array,$keys,$type='asc'){
	 if(!isset($array) || !is_array($array) || empty($array)){
		 return '';
	 }
	 if(!isset($keys) || trim($keys)==''){
		 return '';
	 }
	 if(!isset($type) || $type=='' || !in_array(strtolower($type),array('asc','desc'))){
		 return '';
	 }
	 $keysvalue=array();
	 foreach($array as $key=>$val){
		  $val[$keys] = str_replace('-','',$val[$keys]);
		  $val[$keys] = str_replace(' ','',$val[$keys]);
		  $val[$keys] = str_replace(':','',$val[$keys]);
		  $keysvalue[] =$val[$keys];
	 }
	 asort($keysvalue); //key值排序
	 reset($keysvalue); //指针重新指向数组第一个
	 foreach($keysvalue as $key=>$vals) {
		  $keysort[] = $key;
	 }
	 $keysvalue = array();
	 $count=count($keysort);
	 if(strtolower($type) != 'asc'){
		  for($i=$count-1; $i>=0; $i--) {
			 $keysvalue[] = $array[$keysort[$i]];
		  }
	 }else{
		  for($i=0; $i<$count; $i++){
			 $keysvalue[] = $array[$keysort[$i]];
		  }
	 }
	 return $keysvalue;
}

 /**
     * 获取分类下面的所有子分类的ID集合
     * 
     * @param int $id
     * @param bool $with_self
     * @return array $array 
     */
     function get_child_ids($id, $with_self=false) {
        $spid = D('item_cate')->where(array('id'=>$id,'status'=>1))->getField('spid'); 
        $spid = $spid ? $spid .= $id .'|' : $id .'|';
        $id_arr = D('item_cate')->field('id')->where(array('spid'=>array('like', $spid.'%'),'status'=>1))->select();
        $array = array();
        foreach ($id_arr as $val) {
            $array[] = $val['id'];
        }
        $with_self && $array[] = $id;
        return $array;
    }
 

/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 */
 
 
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

//含密钥md5加密
function st_md5($str = ''){
	return md5(C('st_encryption_key').$str);
	//return md5($str);
}
//邀请码
function id2invite($id=0){
    $id = empty($id)?0:intval($id);
    return $id + 12101;
}

function invite2id($code){
    if(empty($code)){
        return 0;
    }else{
        return abs($code - 12101);
    }
}
/*
 * 获取缩略图
 */

function get_thumb($img, $suffix = '_thumb') {
    if (false === strpos($img, 'http://')) {
        $ext = array_pop(explode('.', $img));
        $thumb = str_replace('.' . $ext, $suffix . '.' . $ext, $img);
    } else {
        if (false !== strpos($img, 'taobaocdn.com') || false !== strpos($img, 'taobao.com')) {
            switch ($suffix) {
                case '_s':
                    $thumb = $img . '_100x100.jpg';
                    break;
                case '_m':
                    $thumb = $img . '_210x1000.jpg';
                    break;
                case '_b':
                    $thumb = $img . '_480x480.jpg';
                    break;
            }
        }
    }
    return $thumb;
}

/**
 * 获取用户头像
 */
function avatar($uid, $size) {
    if($size){
        $avatar_size = explode(',', C('pin_avatar_size'));
        $size = in_array($size, $avatar_size) ? $size : '100';
        $avatar_dir = avatar_dir($uid);
        $avatar_file = $avatar_dir . md5($uid) . "_{$size}.jpg";
    }else{
        $avatar_file = $uid . "_thumb.jpg";
    }

    if (!is_file(C('pin_attach_path') . 'avatar/' . $avatar_file)) {
        $avatar_file = "default.jpg";
    }
    return __ROOT__ . '/' . C('pin_attach_path') . 'avatar/' . $avatar_file;
}

function avatar_dir($uid) {
    $uid = abs(intval($uid));
    $suid = sprintf("%09d", $uid);
    $dir1 = substr($suid, 0, 3);
    $dir2 = substr($suid, 3, 2);
    $dir3 = substr($suid, 5, 2);
    return $dir1 . '/' . $dir2 . '/' . $dir3 . '/';
}

//获取一个随机且唯一的订单号；
function make_order_id($mode = 'order',$field='id'){
    $Ord=M($mode);
    $dingdan = date('ymd').substr(time(),-5).substr(microtime(),2,5);
    $olddan=$Ord->where(array('dingdan'=>$dingdan))->getField($field);
    if($olddan){
        make_order_id($mode);
    }else{
        return $dingdan;
    }
}




function check_pwd($password){
    $RegExp='/^[a-zA-Z0-9_]{6,16}$/'; //由大小写字母跟数字组成并且长度在3-16字符直接
    return preg_match($RegExp,$password)?$password:false;
}

function check_nickname($Argv){
    $RegExp='/^[a-zA-Z0-9_]{4,16}$/'; //由大小写字母跟数字组成并且长度在3-16字符直接
//  $RegExp='^([\u4e00-\u9fa5]{3,15}|[0-9a-zA-Z_]{6,30})$'; //由大小写字母跟数字组成并且长度在4-10字符直接
    return preg_match($RegExp,$Argv)?$Argv:false;
}

function check_mobile($Argv){
    $RegExp='/^(?:13|15|17|18)[0-9]{9}$/';
    return preg_match($RegExp,$Argv)?$Argv:false;
}

function check_email($Argv){
    $RegExp='/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
    return preg_match($RegExp,$Argv)?$Argv:false;
}

function code2html($code){
    return htmlspecialchars_decode($code);
}
function think_send_mail($user,$to, $name, $subject = '', $body = '', $attachment = array('uploads/customer_import_template.xls')){
//dump($user);exit;
//$user='';
$config = C('THINK_EMAIL');

vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
vendor('SMTP');
$mail = new PHPMailer(); //PHPMailer对象

$mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码

$mail->IsSMTP(); // 设定使用SMTP服务

$mail->SMTPDebug = 0; // 关闭SMTP调试功能

// 1 = errors and messages

// 2 = messages only

$mail->SMTPAuth = true; // 启用 SMTP 验证功能

$mail->SMTPSecure = 'ssl'; // 使用安全协议

$mail->Host = $config['SMTP_HOST']; // SMTP 服务器

$mail->Port = $config['SMTP_PORT']; // SMTP服务器的端口号

$mail->Username = ($user['email']&&$user['smtp_pwd'])?$user['email']:$config['SMTP_USER']; // SMTP服务器用户名

$mail->Password = ($user['email']&&$user['smtp_pwd'])?$user['smtp_pwd']:$config['SMTP_PASS']; // SMTP服务器密码

$mail->SetFrom(($user['email']&&$user['smtp_pwd'])?$user['email']:$config['FROM_EMAIL'], $config['FROM_NAME']);

$replyEmail = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];

$replyName = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];

$mail->AddReplyTo($replyEmail, $replyName);

$mail->Subject = $subject;

$mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端"; 

$mail->MsgHTML($body);

$mail->AddAddress($to, $name);
if(is_array($attachment)){ // 添加附件

foreach ($attachment as $file){

is_file($file) && $mail->AddAttachment($file);

}

}

return $mail->Send() ? true : $mail->ErrorInfo;

}
/*
* 关键词中的空格替换为','
*/
function empty_replace($str) {
    $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
    $alltext = "";
    $start = 1;
    for($i=0;$i<strlen($str);$i++)
    {
        if($start==0 && $str[$i]==">")
        {
            $start = 1;
        }
        else if($start==1)
        {
            if($str[$i]=="<")
            {
                $start = 0;
                $alltext .= " ";
            }
            else if(ord($str[$i])>31)
            {
                $alltext .= $str[$i];
            }
        }
    }
    $alltext = str_replace("　","",$alltext);
    $alltext = preg_replace("/&([^;&]*)(;|&)/","",$alltext);
    $alltext = preg_replace("/[ ]+/s"," ",$alltext);
    return $alltext;
}
/**
 * google api 二维码生成【QRcode可以存储最多4296个字母数字类型的任意文本，具体可以查看二维码数据格式】
 * @param string $chl 二维码包含的信息，可以是数字、字符、二进制信息、汉字。
 不能混合数据类型，数据必须经过UTF-8 URL-encoded
 * @param int $widhtHeight 生成二维码的尺寸设置
 * @param string $EC_level 可选纠错级别，QR码支持四个等级纠错，用来恢复丢失的、读错的、模糊的、数据。
 *       L-默认：可以识别已损失的7%的数据
 *       M-可以识别已损失15%的数据
 *       Q-可以识别已损失25%的数据
 *       H-可以识别已损失30%的数据
 * @param int $margin 生成的二维码离图片边框的距离
 */
function qr_code($chl,$widhtHeight ='170',$EC_level='L',$margin='0'){
    $chl = urlencode($chl);
    echo '<img src="http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl.'" alt="QR code" widhtHeight="'.$widhtHeight.'" widhtHeight="'.$widhtHeight.'"/>';
}

function get_w_o_line($apply_money,$provide_rate=1){

    $provide_fee = $apply_money;
    if($provide_fee <= 50){
        $warnning_line = $provide_fee * 1.1;
        $open_line = $provide_fee * 1.06;
    }elseif($provide_fee > 50 && $provide_fee < 200){
        $warnning_line = $provide_fee * 1.1;
        $open_line = $provide_fee * 1.06;
    }elseif($provide_fee > 200 && $provide_fee <= 2000){
        $warnning_line = $provide_fee * 1.12;
        $open_line = $provide_fee * 1.08;
    }elseif($provide_fee > 2000 && $provide_fee <= 5000){
        $warnning_line = $provide_fee * 1.14;
        $open_line = $provide_fee * 1.1;
    }
    $data = array(
        'warnning_line' => $warnning_line*10000,
        'open_line' => $open_line*10000
    );
    return $data;
}

/*计算生成利息 默认回月息*/
function interest_member($invest_amount = 0,$rate=0,$month = 1){
    $amount = ($rate * $month * $invest_amount)/12;
    return sprintf("%.2f", $amount);
}

/*
 * 计算时间差
 * $part 还回时间类型
 * */
function st_date_diff($part, $begin, $end)
{
    $diff = strtotime($end) - strtotime($begin);
    switch($part)
    {
        case "y": $retval = bcdiv($diff, (60 * 60 * 24 * 365)); break;
        case "m": $retval = bcdiv($diff, (60 * 60 * 24 * 30)); break;
        case "w": $retval = bcdiv($diff, (60 * 60 * 24 * 7)); break;
        case "d": $retval = bcdiv($diff, (60 * 60 * 24)); break;
        case "h": $retval = bcdiv($diff, (60 * 60)); break;
        case "n": $retval = bcdiv($diff, 60); break;
        case "s": $retval = $diff; break;
    }
    return $retval;
}

function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}

function encrypt($data, $key='stone') {
    $prep_code = serialize($data);
    $block = mcrypt_get_block_size('des', 'ecb');
    if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
        $prep_code .= str_repeat(chr($pad), $pad);
    }
    $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
    return base64_encode($encrypt);
}

function decrypt($str, $key='stone') {
    $str = base64_decode($str);
    $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
    $block = mcrypt_get_block_size('des', 'ecb');
    $pad = ord($str[($len = strlen($str)) - 1]);
    if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
        $str = substr($str, 0, strlen($str) - $pad);
    }
    return unserialize($str);
}

if(!function_exists('array_column')){
    function array_column($input, $columnKey, $indexKey=null){
        $columnKeyIsNumber      = (is_numeric($columnKey)) ? true : false;
        $indexKeyIsNull         = (is_null($indexKey)) ? true : false;
        $indexKeyIsNumber       = (is_numeric($indexKey)) ? true : false;
        $result                 = array();
        foreach((array)$input as $key=>$row){
            if($columnKeyIsNumber){
                $tmp            = array_slice($row, $columnKey, 1);
                $tmp            = (is_array($tmp) && !empty($tmp)) ? current($tmp) : null;
            }else{
                $tmp            = isset($row[$columnKey]) ? $row[$columnKey] : null;
            }
            if(!$indexKeyIsNull){
                if($indexKeyIsNumber){
                    $key        = array_slice($row, $indexKey, 1);
                    $key        = (is_array($key) && !empty($key)) ? current($key) : null;
                    $key        = is_null($key) ? 0 : $key;
                }else{
                    $key        = isset($row[$indexKey]) ? $row[$indexKey] : 0;
                }
            }
            $result[$key]       = $tmp;
        }
        return $result;
    }
}


function get_merchant_bond($member_id = 0){
    $member_id = empty($member_id)?is_login():$member_id;
    $task_ids = M('Task')->where(array('member_id'=>$member_id))->getField('id',true);
    if($task_ids){
        //
        $map['task_id'] = array('IN',$task_ids);
        $map['status'] = array('IN',array(3,4,5,6));
        $task_apply = D('TaskApply')->field('id')->where($map)->relation('apply_info')->select();

        $amount = array_column($task_apply,'amount');
        $commission_amount = array_column($task_apply,'commission_amount');

        return array_sum($amount)+array_sum($commission_amount);
    }else{
        return 0;
    }
}

function is_vip(){
    $vip = M('MemberVip')->where(array('member_id'=>is_login()))->find();
    if(!empty($vip)){
        $time_end = date("Y-m-d H:i:s", strtotime("+".$vip['month']." months", strtotime($vip['time_start'])));
        if($time_end < date('Y-m-d H:i:s')){
            return false;
        }
        return $vip;
    }else{
        return false;
    }
}

function export_csv($data=array(),$filename='')
{
    $filename = empty($filename)? date('YmdHis') . ".csv":$filename;

    header("Content-Type: application/vnd.ms-excel; charset=GB2312");
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment;filename=$filename ");
    header("Content-Transfer-Encoding: binary ");

    $csvContent = array_to_str($data);

    echo $csvContent;
}

function array_to_str($data){
    $str = '';
    foreach($data as $val){
        $linestr = implode(',',$val);
        //$str .= iconv("utf-8","gb2312",$linestr)." \n";
        $str .= mb_convert_encoding($linestr,"gbk","utf-8")." \n";
    }
    return $str;
}

function stripslashes_deep($value) {
	if ( is_array($value) ) {
		$value = array_map('stripslashes_deep', $value);
	} elseif ( is_object($value) ) {
		$vars = get_object_vars( $value );
		foreach ($vars as $key=>$data) {
			$value->{$key} = stripslashes_deep( $data );
		}
	} elseif ( is_string( $value ) ) {
		$value = stripslashes($value);
	}
	return $value;
}
	
	//商品栏目id中文替换数字
	 function cate_to($val){
	 	$val1 =  explode('|' , $val); 
		$val2 = end($val1);
		$item_cate = D('ItemCate') -> field('name,id') -> where(array('status'=>1)) -> select();
		foreach($item_cate as $i => $val){
			if(trim($val2) == trim($val['name'])){
				$val3 = $val['id'];
			}
		}
		return $val3;
	 }

//截取字符长度
function subtext($text, $length){
    if(mb_strlen($text, 'utf8') > $length)
        return mb_substr($text, 0, $length, 'utf8');
    return $text;
}
 /* 求两个已知经纬度之间的距离,单位为米
 *
 * @param lng1 $ ,lng2 经度
 * @param lat1 $ ,lat2 纬度
 * @return float 距离，单位米
 * @author www.Alixixi.com
 */
function getdistance($lng1, $lat1, $lng2, $lat2) {
    // 将角度转为狐度
    $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
    $radLat2 = deg2rad($lat2);
    $radLng1 = deg2rad($lng1);
    $radLng2 = deg2rad($lng2);
    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
    return $s;
}
//留言类型名
function type_name($type_id){
	return M('apply')->where(array('id'=>$type_id))->getField('typename');
	}
//单个汉字的拼音首字母，支持GBK和UTF8编码：
function getfirstchar($s0){
    $fchar = ord($s0{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "I";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return null;
}

//商城价相关信息
function origin_price($store_id,$item_id,$did=0){
	$wh=array(
		'store_id'=>$store_id,
		'item_id'=>$item_id,
	);
	($did)&&$wh['did']=$did;
	return M('store_item')->where($wh)->getField('refer_price');
}
//是否有活动
function is_activity($store_id,$item_id,$did=''){
	$now_time=strtotime(date('Y-m-d H:i:s'));//活动时间
	$wh=array(
			'store_id' =>$store_id,
			'item_id' =>$item_id,
			'status'=>1
	);
	($did)&&$wh['did']=$did;
    //活动相关 开始时间， 结束时间 活动类型
	$info =M('activity')->where($wh)->field('store_item_id,number,start_time,end_time,times,cate_id,owned_nums')->find();
	//dump($wh);exit;
	if($info['cate_id']==2||$info['cate_id']==3){
	    if($info['owned_nums'] >= $info['number']){
            return 0;
        }else{
            if($info['start_time']<=$now_time && $info['end_time']>=$now_time){
                return $info['cate_id'];
            } else{
                return 0;
            }
        }
	} else if($info['cate_id']==1){
        if($info['owned_nums'] >= $info['number']) return 0;
        //当前门店所有秒杀活动产品
        $list = M('activity')->alias('a')
            ->join('__STORE_ITEM__ s on a.item_id = s.item_id')
            ->where(array('a.status'=>1,'a.cate_id'=>1,'a.store_id'=>$store_id,'s.store_id'=>$store_id))
            ->field('a.id,a.item_id,a.number,a.times')
            ->select();

        //根据时间段区分
        $new_list =array();
        //dump($list);
        foreach ($list as $k=>$v){
            $new_list[strtotime($v['times'])][] = $v;
        }
        ksort($new_list);
        //状态判断
        $check = 0;
        $time = time();
        $act_time = "";
        foreach ($new_list as $k=>$v){
            if($k < $time){
                $old_key = $k;
            }else if($k > $time && $check == 0 && $old_key){
                $act_time = $old_key;
                $check = 1;
            }
        }

        if(count($new_list) > 0 && $check==0 && $old_key){
            $act_time = $old_key;
        }

	    if($act_time){
            if($act_time == strtotime($info['times'])){
                return $info['cate_id'];
            }else{
                return 0;
            }
        }else{
	        return 0;
        }

	} else if($info['cate_id']==8){
		$stock = M('store_item')->where(array('id'=>$info['store_item_id']))->getfield('stock');
		if($info['owned_nums'] >= $stock){
			return 0;
		}else{
			return $info['cate_id'];
		}
	} else{
		return 0;
	}
}
//团购人数
function group_nums($store_id,$item_id,$did=''){
	($did)&&$wh['did']=$did;
	$start_time=M('activity')->where(array('store_id'=>$store_id,'item_id'=>$item_id))->getField('start_time');
	$result = M('order_list')->alias('ol')->join("__ORDER__ o on o.id=ol.oid")
	->where(array('o.store_id'=>$store_id,'ol.is_act'=>2,'item_id'=>$item_id,'o.add_time'=>array('egt',$start_time)))
	->field('ol.member_id')->group('ol.member_id')->select();
	//dump(count($result));exit;
	return count($result);
}

//优惠券获取关联分类名称
function coupon_get_item_cate($item_str){
    if($item_str){
        $item_cate = M('ItemCate')->where(array('pid'=>0))->getfield("id,name");
        $id_all = explode(',',$item_str);
        $str = "";
        foreach ($id_all as $v){
            $str .=  $item_cate[$v]."|";
        }
        return rtrim($str,'|');
    }else{
        return "全部";
    }
}
//满减商品门店价
function store_price($sid,$item_id){
	$wh=array(
	'store_id'=>$sid,
	'item_id'=>$item_id
	);
	return M('store_item')->where($wh)->getField('market_price');
}
//图片清晰度 小图转大图
function img_data($da){
	return str_replace('_s','_b',$da);
	}
//每日每人限购数量判断
function limit_nums($uid,$sid,$item_id){
	//限购数量查询
	$y = date('Y');$m = date('m');$d = date('d');//年月日
	$t = mktime(0,0,0,$m,$d,$y);
	$limit_info=M('activity')->where(array('store_id'=>$sid,'item_id'=>$item_id))->field('id,per_nums,number')->find();
	if($limit_info['per_nums']==0){
		$store_stock=M('store_item')->where()->getField();
		$nums=$limit_info['number'];//数量未填时 默认为活动数量
	}
	else{
		$whe['ol.member_id']=$uid;
		$whe['ol.act_id']=$limit_info['id'];
		$whe['o.status']=array('neq',9);
		$whe['o.add_time'][] = array('egt', $t);
		$whe['o.add_time'][] = array('elt', $t+(24*60*60-1));
		$bought_nums=M('order_list')->alias('ol')->join('__ORDER__ o on ol.oid=o.id')->where($whe)->sum('ol.nums');
		$nums=$limit_info['per_nums'];
		if($bought_nums){
			$nums-=$bought_nums;
		}
	}
	return $nums;
}
//签到
function qiandao($uid){
	$y = date('Y');$m = date('m');$d = date('d');//年月日
	$t = mktime(0,0,0,$m,$d,$y);
	//获得当前月之前日数
	$month_day=array();
	for($i=1;$i<=intval($d)-1;$i++){
		$month_day[$i-1]=strtotime(date('Y-m-d',$t-3600*24*$i));
	}
	//获得当前月之前日数 end
	$nums =0;
	foreach($month_day as $k=>$v){
		$res[$k] =M('integral')->where(array('types'=>8,'member_id'=>$uid,'add_time'=>$v))->getField('id');
		if($res[$k]){
			$nums+=1;
			}
		else{
			break;
			}
	}
	return $nums;
}
/*针对任意键值来进行去重*/  
function getArrayUniqueByKeys($arr)  
{  
   $arr_out =array();  
   foreach($arr as $k => $v)  
   {  
		$key_out = $v['item_id']."-".$v['title']; //提取内部一维数组的key(name age)作为外部数组的键  

		if(array_key_exists($key_out,$arr_out)){  
			continue;  
		}  
		else{  
			 $arr_out[$key_out] = $arr[$k]; //以key_out作为外部数组的键  
			 $arr_wish[$k] = $arr[$k];  //实现二维数组唯一性  
		}  
   }  
   return $arr_wish;  
}  
//PC端头部信息
function header_info(){
	//首页顶部分类信息
	$cate=M('item_cate')->where(array('status'=>1,'pid'=>0))->field('name,img,id')->limit(0,16)->select();
	foreach($cate as $k=>$v){
		//品牌
		$map[$k] = "status=1 and cate_id like '%".','.$v['id']."' or cate_id like '".$v['id'].",%' or cate_id like '%,".$v['id'].",%' or cate_id=".$v['id'];
		$left_map[$k] = "(cate_id like '%".','.$v['id']."' or cate_id like '".$v['id'].",%' or cate_id like '%,".$v['id'].",%' or cate_id=".$v['id'].') and left_show=1 and status=1 ';
		$cate[$k]['brand']=M('item_brand')->where($map[$k])->field('id,name,cate_id,is_focus')->limit(0,16)->select();
		$cate[$k]['left_brand']=M('item_brand')->where($left_map[$k])->order('left_ordid desc')->field('left_show,left_ordid,id,name,cate_id,is_focus')->limit(0,16)->select();
	  // dump($cate[$k]['left_brand']);
		//白酒香型
		if($v['id']==2){
		$cate[$k]['smell']=M('tag')->limit(0,16)->select();
		}
		else if($v['id']==3){
			$cate[$k]['second']=M('item_cate')->where(array('status'=>1,'pid'=>$v['id']))->field('name,img,id')->limit(0,16)->select();
		}
		//产地
		$cate[$k]['nation']=M('nation')->where(array('pid'=>$v['id'],'status'=>1))->limit(0,16)->select();
		//价格区间
		$cate[$k]['price']=M('price')->where(array('pid'=>$v['id']))->select();
	}//热门搜索
	return $cate;
}
//热门搜索
function hot_search(){
	$res =M('SearchLog')->field("count(keywords) nums,keywords")->group('keywords')->order('nums desc')->limit(7)->select();
	return $res;
}
//手机端营业门店门店
function working_stores(){
	return M('store')->where(array('title'=>array('notlike','%电脑端%'),'status'=>1))->field('id,title,longitude,latitude')->select();
}
//底部分类
function footer_info(){
	//尾部
	$where['id|pid']=array('in',array('10','11','12','13','14'));
	$where['status']=1;
	$result =M('article_cate')->where($where)
	->field('id,pid,name,img')->select();
	$res=footer_menu($result,0);
	return $res;
}
//多级分类
function get_childs($arr,$pid){
	$menu=array();
	foreach($arr as $key => $val){
		if($val['pid']==$pid){
			$val['sub_menu']=get_childs($arr,$val['id']);
			$menu[]=$val;
			}
		}
		return $menu;
}
//商品分类一级ID查询
function get_first_pid($ids) {
	$array=array();
	foreach($ids as $k=>$v){
        $spid = M('item_cate')->where(array('id'=>$v))->getField('spid');
        $spid = $spid ? $spid : $v.'|';
		$cate[$k]=explode('|',$spid);
		$array[]=$cate[$k][0];
	}
	return $array;
}

//商品库存
function item_stock($did,$sid,$item_id){
	if($did){
		$stock=M('store_attr')->where(array('id'=>$did))->getField('attr_num');
	}
	else{
		$cate =is_activity($sid,$item_id);
		if($cate&&$cate!=8){
			$stock=M('activity')->where(array('store_id'=>$sid,'item_id'=>$item_id))->getField('number');
		}
		else{
			$stock=M('store_item')->where(array('store_id'=>$sid,'item_id'=>$item_id))->getField('stock');
		}
	}
	return $stock;
}
//购物车总数
function cart_nums($uid,$sid){
	$count =  M('cart')->where(array('member_id'=>$uid))->field('sum(nums) as num')->select();
	return $count[0]['num'];
}
function taobaoIP($clientIP){
        $taobaoIP = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$clientIP;
        $IPinfo = json_decode(file_get_contents($taobaoIP));
        $data['province'] = $IPinfo->data->region;
        $data['city'] = $IPinfo->data->city;
        return $data['city'];
}
//商品图片
function item_img($id){
	return M('item')->where(array('id'=>$id))->getField('img');
}
//商品图片
function item_name($id){
	return M('item')->where(array('id'=>$id))->getField('title');
}
//pc配送费用
function ship_fee($store_id,$lng,$lat,$sum){
	$info=M('store')->where(array('id'=>$store_id))->field('longitude,latitude')->find();
	$length = getdistance($info['latitude'],$info['longitude'],$lng,$lat);
	$km = round($length/1000,2);
	//运费
	if( $sum >= C('pin_is_qb')){
		$yun_fei = 0.00;
	}
	if($km <= C('pin_distance')){
		$yun_fei = 0.00;
	}else if(($km > C('pin_distance') && $km <= C('pin_commonly_distance')) && $sum >= C('pin_is_qb')){
		$yun_fei = 0;
	}else if($km > C('pin_distance') && $km <= C('pin_commonly_distance')){
		$yun_fei = C('pin_freight');
	}else{
		$yun_fei = round($km)*C('pin_far_distance');
		$yun_fei=$yun_fei>10?10:$yun_fei;
	}
	return $yun_fei;
}
//门店ID
function store_id($item_id){
	$store_id =M('store_item')->where(array('item_id'=>$item_id,'store_id'=>array('neq',18)))->field('store_id')->find();
	return $store_id;
}

function getalphnum($char){
	//$array=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	$array= array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$len=strlen($char);
	for($i=0;$i<$len;$i++){
	$index=array_search($char[$i],$array);
	$sum+=($index+1)*pow(26,$len-$i-1);
	}
	return $sum;
}
//去除字符串中的空格和换位符
function  DeleteHtml($str) 
{ 
    $str = trim($str); //清除字符串两边的空格
    $str = preg_replace("/\t/","",$str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
    $str = preg_replace("/\r\n/","",$str); 
    $str = preg_replace("/\r/","",$str); 
    $str = preg_replace("/\n/","",$str); 
    $str = preg_replace("/ /","",$str);
    $str = preg_replace("/  /","",$str);  //匹配html中的空格
    return trim($str); //返回字符串
}

function strip_html_tags($tags,$str){
    $html=array();
    foreach ($tags as $tag) {
        $html[]="/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";
    }
    $data=preg_replace($html, '', $str);
    return $data;
}
   /**
  * 验证姓名是否为百家姓
 * @param type $user_name
 * @return boolean
 */
function username($user_name)
{
    $array = array( '赵', '钱', '孙', '李', '周', '吴', '郑', '王', '冯', '陈', '楮', '卫', '蒋', '沈', '韩', '杨',
                    '朱', '秦', '尤', '许', '何', '吕', '施', '张', '孔', '曹', '严', '华', '金', '魏', '陶', '姜',
                    '戚', '谢', '邹', '喻', '柏', '水', '窦', '章', '云', '苏', '潘', '葛', '奚', '范', '彭', '郎',
                    '鲁', '韦', '昌', '马', '苗', '凤', '花', '方', '俞', '任', '袁', '柳', '酆', '鲍', '史', '唐',
                    '费', '廉', '岑', '薛', '雷', '贺', '倪', '汤', '滕', '殷', '罗', '毕', '郝', '邬', '安', '常',
                    '乐', '于', '时', '傅', '皮', '卞', '齐', '康', '伍', '余', '元', '卜', '顾', '孟', '平', '黄',
                    '和', '穆', '萧', '尹', '姚', '邵', '湛', '汪', '祁', '毛', '禹', '狄', '米', '贝', '明', '臧',
                    '计', '伏', '成', '戴', '谈', '宋', '茅', '庞', '熊', '纪', '舒', '屈', '项', '祝', '董', '梁',
                    '杜', '阮', '蓝', '闽', '席', '季', '麻', '强', '贾', '路', '娄', '危', '江', '童', '颜', '郭',
                    '梅', '盛', '林', '刁', '锺', '徐', '丘', '骆', '高', '夏', '蔡', '田', '樊', '胡', '凌', '霍',
                    '虞', '万', '支', '柯', '昝', '管', '卢', '莫', '经', '房', '裘', '缪', '干', '解', '应', '宗',
                    '丁', '宣', '贲', '邓', '郁', '单', '杭', '洪', '包', '诸', '左', '石', '崔', '吉', '钮', '龚',
                    '程', '嵇', '邢', '滑', '裴', '陆', '荣', '翁', '荀', '羊', '於', '惠', '甄', '麹', '家', '封',
                    '芮', '羿', '储', '靳', '汲', '邴', '糜', '松', '井', '段', '富', '巫', '乌', '焦', '巴', '弓',
                    '牧', '隗', '山', '谷', '车', '侯', '宓', '蓬', '全', '郗', '班', '仰', '秋', '仲', '伊', '宫',
                    '宁', '仇', '栾', '暴', '甘', '斜', '厉', '戎', '祖', '武', '符', '刘', '景', '詹', '束', '龙',
                    '叶', '幸', '司', '韶', '郜', '黎', '蓟', '薄', '印', '宿', '白', '怀', '蒲', '邰', '从', '鄂',
                    '索', '咸', '籍', '赖', '卓', '蔺', '屠', '蒙', '池', '乔', '阴', '郁', '胥', '能', '苍', '双',
                    '闻', '莘', '党', '翟', '谭', '贡', '劳', '逄', '姬', '申', '扶', '堵', '冉', '宰', '郦', '雍',
                    '郤', '璩', '桑', '桂', '濮', '牛', '寿', '通', '边', '扈', '燕', '冀', '郏', '浦', '尚', '农',
                    '温', '别', '庄', '晏', '柴', '瞿', '阎', '充', '慕', '连', '茹', '习', '宦', '艾', '鱼', '容',
                    '向', '古', '易', '慎', '戈', '廖', '庾', '终', '暨', '居', '衡', '步', '都', '耿', '满', '弘',
                    '匡', '国', '文', '寇', '广', '禄', '阙', '东', '欧', '殳', '沃', '利', '蔚', '越', '夔', '隆',
                    '师', '巩', '厍', '聂', '晁', '勾', '敖', '融', '冷', '訾', '辛', '阚', '那', '简', '饶', '空',
                    '曾', '毋', '沙', '乜', '养', '鞠', '须', '丰', '巢', '关', '蒯', '相', '查', '后', '荆', '红',
                    '游', '竺', '权', '逑', '盖', '益', '桓', '公', '仉', '督', '晋', '楚', '阎', '法', '汝', '鄢',
                    '涂', '钦', '岳', '帅', '缑', '亢', '况', '后', '有', '琴', '归', '海', '墨', '哈', '谯', '笪',
                    '年', '爱', '阳', '佟', '商', '牟', '佘', '佴', '伯', '赏'
                 );
    $double_array = array(  "万俟", "司马", "上官", "欧阳", "夏侯", "诸葛", "闻人", "东方", "赫连", "皇甫", "尉迟", "公羊",
                            "澹台", "公冶", "宗政", "濮阳", "淳于", "单于", "太叔", "申屠", "公孙", "仲孙", "轩辕", "令狐",
                            "锺离", "宇文", "长孙", "慕容", "鲜于", "闾丘", "司徒", "司空", "丌官", "司寇", "子车", "微生",
                            "颛孙", "端木", "巫马", "公西", "漆雕", "乐正", "壤驷", "公良", "拓拔", "夹谷", "宰父", "谷梁",
                            "段干", "百里", "东郭", "南门", "呼延", "羊舌", "梁丘", "左丘", "东门", "西门", "南宫"
                     );

   return array_merge($array,$double_array);
}