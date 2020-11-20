<?php



function is_login(){

    //return empty($_SESSION['admin']['id'])?0:$_SESSION['admin']['id'];

	return empty($_SESSION['userid']);

}

	

	//蛇形转化为驼峰

function snake_case($str = ''){

    return strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $str));

}

function attach($attach, $type,$path=false) {

    if (false === strpos($attach, 'http://')) {

        //本地附件

        $img_url = __ROOT__ . '/' . C('pin_attach_path') . $type . '/' . $attach;

        $img_path = realpath(__ROOT__).'/' . C('pin_attach_path') . $type . '/' . $attach;

	return $img_url;

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


//验证是否店长登录
function check_dz(){
	if($_SESSION['admin']['role_id'] == 3){
		return TRUE;
	}else{
		return FALSE;
	}
}


function	zffsa($aa){

    				$str = '';

    			if($aa==1){

    				$str='余额支付';

    			}elseif($aa==2){

    				$str=" 微信支付";

    			}elseif($aa==3){

    				$str="银联支付";

    			}elseif($aa==4){

    				$str="支付宝支付";

    			}

    			return $str	;

}
				
				
				
				function xgstatusa($id){

	if($id==0){

    	$str="未支付";

    }elseif($id==1){

    	$str="已支付";

    }elseif($id==2){

    	$str="已完成";

    }elseif($id==3){

    	$str="作废";

    }elseif($id==4){

    	$str="已发货";

    }elseif($id==6){

    	$str="已评价";

    }elseif($id==7){

    	$str="退款单";

    }elseif($id==5){
    
      $str="已确认";
    }

    return $str;

}
//门店查询
function store_info(){
	 $store_info=M('store')->where(array('status'=>1))->field('id,title')->select();
		 
	 return $store_info;
}
//分类名称
function cate_name($ids){
	if($ids)
	{
	$where['id']=array('in',$ids);
	$result =M('item_cate')->where($where)->field('name')->getField('name',true);
	return implode('，',$result);
	}
}
//酒品类型
function origin_cate($id){
	return M('item_cate')->where(array('id'=>$id))->getField('name');
	}
//时间转换
function time_turn($val){
	return strtotime($val);
}