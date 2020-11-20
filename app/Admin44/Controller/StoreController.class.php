<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class StoreController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Store');
        $this->set_mod('Store');
    }
 public function ajax_store_img(){
        //上传图片
        if (!empty($_FILES['file']['name'])) {
            $date_dir = date('ym/d/'); //上传目录
            $result = $this->_upload($_FILES['file'], 'store/'.$date_dir, array(
                'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'),
                'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
                'suffix' => '_b,_m,_s',
            ));

            if ($result['error']) {
                echo json_encode(array("error" => "请上传2M以内的图片文件!"));

            } else {
                $data['thumb_img'] = $date_dir .$result['info'][0]['savename'];
                if($data['thumb_img']){
                    echo json_encode(array("error" => "0", "src" => $data['thumb_img'], "name" => $result['info'][0]['savename']));

                }else{
                    echo json_encode(array("error" => "上传有误，清检查服务器配置！"));
                }

            }
        } else {
            echo json_encode(array("error" => "您还未选择图片"));
        }
    }

public function Ajax_sh(){
  $id=I('id');
  $result=M('Merchant')->where(array('id'=>$id))->setField('status','4');
  if ($result) {
    $member_id = M('Merchant')->where(array('id'=>$id))->getfield("member_id");
    $m_result=M('Member')->where(array('id'=>$member_id))->setField('status','1');
    if ($m_result) {
        echo 1;
      }else {
        echo 2;
      }
    }else {
      echo 0;
  }
}
    protected function _search() {
        $map = array();
        //'status'=>1
        ($time_start = I('time_start','', 'trim')) && $map['create_time'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','',  'trim')) && $map['create_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
       
       
        if( $_GET['status']==null ){
            $status = -1;
        }else{
            $status = intval($_GET['status']);
        }
		 if( $_GET['shop_type']==null ){
            $shop_type = -1;
        }else{
            $shop_type = intval($_GET['shop_type']);
        }
        $status>=0 && $map['status'] = array('eq',$status);
		$shop_type>=0 && $map['shop_type'] = array('eq',$shop_type);
        ($keyword = I('keyword','',  'trim')) && $map['title'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
           
            'status' =>$status,
           
            'keyword' => $keyword,
        ));
        return $map;
    }
	 public function _before_index(){
		 $shop_type =M('item_cate')->where(array('pid'=>154,'status'=>1))->field('id,name')->select();
  		$this->assign('shop_type',$shop_type);
    }  //上传图片
    public function _before_insert($data){
		$point=explode(',',I('long_lat','','trim'));
		$data['longitude']=$point[1];
		$data['latitude']=$point[0];
		$data['address']=I('address','','trim');
		$label =implode('|',I('label'));
		$data['label']=$label;
		$data['create_time']=time();
		//dump($data);exit;
		return $data;
        //$brand=M('BrandCate')->where('pid=0')->order('id desc')->select();
//        $this->assign('brand',$brand);
//        $this->assign('settlement_type' ,C('settlement_type'));
//        $this->assign('status',C('merchant_status'));
    }  //上传图片
    public function ajax_upload_img() {
        $type = I('type', 'logo', 'trim');
        if (!empty($_FILES[$type]['name'])) {
            $dir = date('ym/d/');
            $result = $this->_upload($_FILES[$type], 'advert/'. $dir );
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $savename = $dir . $result['info'][0]['savename'];
                $this->ajax_return(1, L('operation_success'), $savename);
            }
        } else {
            $this->ajax_return(0, L('illegal_parameters'));
        }
    }
	public function _before_add(){
		$res =M('item_cate')->where(array('pid'=>154))->field('id,name')->select();
	
        $this->assign('shop_type',$res);
    }
    public function _before_edit(){
		$id=I('id','','intval');
		$res =M('item_cate')->where(array('pid'=>154))->field('id,name')->select();
		 //相册
		$img_list = M('store_img')->where(array('store_id'=>$id))->select();
		$this->assign('img_list', $img_list);			
        $this->assign('shop_type',$res);
    }
    protected function _before_update($data) {
       if(!empty($_FILES)){
                $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
                $upload->maxSize = 3292200;
                $config = array(
                    'rootPath'=>'data/attachment/',
                    'savePath'=>'logo/',
                );
                $upload = new \Think\Upload($config);
                $z = $upload->uploadOne($_FILES['avatar']);
                if($z){
                    $bigimg = $z['savepath'].$z['savename'];
                    $data['logo']=date('Y-m-d',time()).'/'.$z['savename'];
                    $image = new \Think\Image();
                    $srcimg = $upload->rootPath.$bigimg;
                    $image->open($srcimg);
                }
        }
		//dump($data);exit;
		$imgs=I('imgs');
		if($imgs){
			//M('store_img')->where(array('store_id'=>$data['id']))->delete();
                foreach($imgs as $key=>$val ){
                    $store_imgs[$key] = array(
                        'store_id' => $data['id'],
                        'url'     =>  $val,
                        'add_time'=> time(),
                    );
					$find[$key]=M('store_img')->where(array('url'=>$val))->getField('id');
					!$find[$key]&& M('store_img')->add($store_imgs[$key]);
                }
//              }
            }
       	$point=explode(',',I('long_lat','','trim'));
		$data['longitude']=$point[1];
		$data['latitude']=$point[0];
		$data['address']=I('address','','trim');
		//dump($data);exit;
        return $data;
    }
    public function map(){
        if (IS_AJAX) {
            $response = $this->fetch();
			exit;
            $this->ajax_return(1,'',$response,'map');
        } else {
            $this->display();
        }
    }
  
	  function delete_album() {

        $album_mod = M('store_img');

        $album_id = I('album_id',0,'intval');

        $album_img = $album_mod->where('id='.$album_id)->getField('url');

        if( $album_img ){

            $ext = array_pop(explode('.', $album_img));

            $album_min_img = C('pin_attach_path') . 'store/' . str_replace('.' . $ext, '_s.' . $ext, $album_img);

            is_file($album_min_img) && @unlink($album_min_img);

            $album_img = C('pin_attach_path') . 'store/' . $album_img;

            is_file($album_img) && @unlink($album_img);

            $album_mod->delete($album_id);

        }

        echo '1';

        exit;

    }
 public function geocoder(){

       $lng = I('lng');

       $lat = I('lat');

       $url = "http://api.map.baidu.com/geocoder?location=$lat,$lng&coord_type=bd09ll&output=json";

       $data = file_get_contents($url);

       echo $data;

   }


  public function get_cityid(){
	   $place_id=I('place_id','','intval')?I('place_id','','intval'):216;
	   $place_spid = D('place')->where(array('id' => $place_id))->getField('spid');
       $place_spid_arr = explode('|',$place_spid.$place_id.'|');
       //$data['province_id'] = isset($place_spid_arr[0])?$place_spid_arr[0]:0;
       $data['city_id'] = isset($place_spid_arr[1])?$place_spid_arr[1]:216;
	  
	   $cityname=D('place')->where(array('id'=>$data['city_id']))->getfield('name');

	   if($cityname){
	       $this->ajaxReturn($cityname);
	   }
  }

    //测试腾讯地图选点
    public function getpoint(){
      $this->display();
    }
}
