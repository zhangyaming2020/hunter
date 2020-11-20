<?php



function is_login(){

    //return empty($_SESSION['admin']['id'])?0:$_SESSION['admin']['id'];

	return empty($_SESSION['userid']);

}	

function user_name($id){
	$name = D('Member')->field('nickname,mobile')->find($id);
	return $name['nickname']? : $name['mobile'];
}


	//蛇形转化为驼峰

function snake_case($str){
    return strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/','_',$str));
}


function attach($attach, $type,$path=false) {
	
	if(false !== strpos($attach,'data/attachment/avatar')){
		return $attach;
	}
    if (false === strpos($attach, 'http://')) {
        //本地附件
        $img_url = __ROOT__ . '/' . C('pin_attach_path') . $type . '/' . $attach;
		
        $img_path = realpath(__ROOT__).'/' . C('pin_attach_path') . $type . '/' . $attach;

        if(is_file($img_path) || $path){
            return $img_url;
        }else{
            return __ROOT__ . '/data/image/nopicture.gif';
        }
        //远程附件
        //todo...
    } else {
        //URL链接
        return $attach;

    }

}



////判断登录会员所在城市

//	function member_city(){

//		$admin = session('admin');

//		

//	}
//生成优惠券号
function make_sn_no($name = 'MemberCoupons',$length = 11)
{
	$model = D($name);
	$str = range('a','z');
	$str2 = range('0','9');
	$arr = array_merge($str,$str2);
	shuffle($arr);
	for($i = 0;$i < $length; $i++){
		$no .= $arr[rand(0,32)];
	}
	$sn_no = 'Jtjt'.date('mdHis').substr(uniqid(),8,5).$no.rand(1000,9999);
	$id = $model->where(array('sn_no'=>$sn_no))->getField('id');
	if($id){
		make_sn_no($name,$length);
	}else{
		return $sn_no;
	}
}



//销售统计表产品详情
function item_info($id,$string){
	if($string == "cate"){
		$cate_id = M('item')->where(array('id'=>$id))->getfield('cate_id');
		$return = M('item_cate')->where(array('id'=>$cate_id))->getfield('name');
	}else{
		$return = M('item')->where(array('id'=>$id))->getfield($string);
	}
	return $return;
}

//销售统计表会员详情
function user_info($uid,$string){
 	return  M('member')->where(array('id'=>$uid))->getfield($string);
}

//区域划分
function region_division(){
	if($_SESSION['admin']['role_id'] == 1){
		$city_id = M('place')->where('type=2 and status=1')->select();
	}else{
		$city_id = $_SESSION['admin']['city_id'];
	}
	return $city_id;
}

//进销存添加修改日志
function inventory_edit($aa,$info,$sess){
	if($info[$aa] == $sess[$aa]){
		$bb = $sess[$aa];
	}else if($info[$aa] > $sess[$aa]){
		$cc = $info[$aa] - $sess[$aa];
		$bb = $sess[$aa].'+'.$cc.'='.$info[$aa];
	}else{
		$cc = $sess[$aa] - $info[$aa];
		$bb = $sess[$aa].'-'.$cc.'='.$info[$aa];
	}
	return $bb;
}

//截取字符串数字前的部分
function jie_string($s){
//  return preg_replace('/^([^\d]+).*/','$1',$s);
	$array = explode('/', $s);
	return $array[0];	
}

//通过名称或分类查询商品ID
function search_id($title,$cate){
	if($title){
		$map['title'] = array('like','%'.$title.'%');
	}
	
	if($cate){
		//是否查询的是一级栏目
		$pid = M("item_cate")->where(array('id'=>$cate))->getfield('pid');
		if($pid > 0){
			//查询二级栏目
			$map['cate_id'] = $cate;
		}else{
			//查询一级栏目
			$cate_id = M("item_cate")
			->field("group_concat(id) cate_id")->where(array('pid'=>$cate))->group('pid')->select();
			if($cate_id){
				$map['cate_id'] = array('in',$cate_id[0]['cate_id']);
			}else{
				$map['cate_id'] = $cate;
			}
		}
	}
	  
    $id_all = M('item')->where($map)->field('id')->select();
//	echo M()->_sql();
	$id = array();
	foreach($id_all as $k=>$v){
		$id[]=$v['id'];
	}
	return $id;
}
function cate($id){
    $emm=M('tickets_cate')->where(array('id'=>$id))->getField('title');
    return $emm;
}
