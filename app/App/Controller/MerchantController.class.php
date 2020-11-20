<?php

namespace App\Controller;

class MerchantController extends ApiController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->member = D('Member');    //用户表
//      $this->place=D('Place');//地区表
        $this->merchant=D('Merchant');//店铺
        $this->merchant_cate = D('MerchantCate');//行业类型表
        $this->merchant_img = D('MerchantImg');//店铺图片表
    }

    //申请店铺     30000
    public function merchant_add($datas)
    {
        $this->check_user_id($datas);
        $data = $this->get_datas($datas);
        ($data == NULL || count($data)<=0) && $this->print_error_status('params_error',$datas['pack_no']);
        (!$arr['title'] = $data['title']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '店铺名称不能为空'));
        (!$arr['cate_id'] = $data['cate_id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '所属行业不能为空'));
        (!check_mobile($data['relation_id'])) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '推荐人电话格式不正确'));
        //获取推荐人id
        $relation_id = $this->member->where(array('mobile'=>$data['relation_id']))->find();
        (!$arr['relation_id'] = $relation_id['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '推荐人不存在'));
        (!$arr['tel'] = $data['tel']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '店铺电话不能为空'));
        (!$arr['province_id'] = $data['province_id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '店铺所在省不能为空'));
        (!$arr['city_id'] = $data['city_id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '店铺所在市不能为空'));
        (!$arr['address'] = $data['address']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '店铺定位不能为空'));

        //所属行业
        $cate = $this->merchant_cate->where(array('id'=>$data['cate_id'],'status'=>1))->field('id,name,pid')->select();


        //店铺地址
        if($data['district_id'])     $arr['district_id'] = $data['district_id'];
        $arr['uid'] = $data['user_id'];
        $arr['add_time'] = time();
        //营业执照
        $arr['yy_img'] = $this->upload_file('img','merchant');
//        (!$arr['yy_img']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '营业执照不能为空'));

        $start = M();
        $start->startTrans();
        $merchant = $this->merchant->add($arr);
        if($merchant){
            //店铺图片
            $date_dir = date('ym/d/');
            $imgs = $this->_uploads('merchant/'.$date_dir);
            die;
            foreach ($imgs['uploadinfos'] as $k => $v) {
                    (count($imgs['uploadinfos']) > 8) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '店铺图片不能超过8张'));
                $ar['img'] =  $date_dir . $v['savename'];
                $ar['merchant_id'] = $merchant;
                $ar['add_time'] = time();
                $res = $this->merchant_img->add($ar);
            }
        }else{
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
        if($res){
            $start->commit();
            $this->json_Response('success',$datas['pack_no']);
        }else{
            $start->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    //设置店铺    30001
    public function merchant_set($datas)
    {
        $data = $this->get_datas($datas); //从请求中获取数据
        ($data == NULL || count($data) <= 0) && $this->print_error_status('params_error', $datas['pack_no']);
        $arr = array();
        //判断错误
        (!$data['title']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '店铺名称不能为空'));//店铺名称不能为空
        $merchant = $this->merchant->where(array('uid'=>$data['user_id']))->field('title')->find();
        ($merchant['title'] == $data['title']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '新名称不能和旧名称相同'));//新名称不能和旧名称相同
        (!$data['start']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '开始营业时间不能为空'));//开始营业时间不能为空
        (!$data['end']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => '结束营业时间不能为空'));//结束营业时间不能为空
        //获取提交数据
        $arr['title'] = $data['title'];
        $arr['start'] = $data['start'];
        $arr['end'] = $data['end'];

        //开始事务
        $this->merchant->startTrans();
        //准备SQL语句
        $set = $this->merchant->where(array('uid'=>$data['user_id']))->save($arr);
        if($set){
            //提交事务
            $this->merchant->commit();
            $this->json_Response('success',$datas['pack_no']);
        }else{
            //回滚事务
            $this->merchant->rollBack();
            $this->json_Response('success',$datas['pack_no']);
        }
    }

    //展示店铺    30002
    public function merchant($datas)
    {
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        $merchant=$this->merchant->where(array('uid'=>$data['user_id']))->field('status,ewm,gold_acer,gold_fruit,title,start,end,cate_id,tel,zftype,img,set_coin,address')->find();
        $merchant['cate_id'] = $this->merchant_cate->where(array('id'=>$merchant['cate_id']))->field('name')->find();
        $this->json_Response('success',$datas['pack_no'],array('merchant'=>$merchant));
    }

    //关闭店铺  && 开启店铺   30003
    public function merchant_close_open($datas)
    {
        $this->check_user_id($datas);//验证用户ID是否存在
        $data = $this->get_datas($datas);
        $uid = $data['user_id'];
        $id = $data['id'];

        $merchant = $this->merchant->where(array('id'=>$id,'uid'=>$uid))->field('id,status')->find();
        (!$merchant['id']) && $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '数据异常'));
        if(!in_array($merchant['status'],[2,3])){
            $this->print_error_status('params_error',$datas['pack_no'],array('ERROR_Param_Format' => '系统繁忙'));
        }
        $this->merchant->startTrans();
        $status = ($merchant['status']==2) ? 3 :2;
        $b = $this->merchant->where(array('uid'=>$uid,'id'=>$id))->setfield('status',$status);
        if($b){
            $this->merchant->commit();
            $this->json_Response('success',$datas['pack_no']);
        }else{
            $this->merchant->rollback();
            $this->json_Response('failed',$datas['pack_no']);
        }
    }
}