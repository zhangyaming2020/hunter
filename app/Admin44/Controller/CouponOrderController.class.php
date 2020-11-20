<?php



namespace Admin\Controller;

use Admin\Org\Tree;

class CouponOrderController extends AdminCoreController {



    public function _initialize() {



        parent::_initialize();



        $this->_mod = D('coupon_order');

        $this->set_mod('coupon_order');



    }
    public function _before_index() {

        //显示模式

      /*  $sm = I('sm', '','trim');



        $this->assign('sm', $sm);




//	print_r($res);



        //默认排序



        $this->sort = 'ordid ASC,';



        $this->order ='id desc,create_time DESC';*/
		



    }







    protected function _search() {



        $map = array();



        //'status'=>1



        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));



        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));



        ($price_min = I('price_min','',  'trim')) && $map['price'][] = array('egt', $price_min);



        ($price_max = I('price_max','',  'trim')) && $map['price'][] = array('elt', $price_max);



        ($rates_min = I('rates_min','',  'trim')) && $map['rates'][] = array('egt', $rates_min);



        ($rates_max = I('rates_max','',  'trim')) && $map['rates'][] = array('elt', $rates_max);



        ($uname = I('uname','',  'trim')) && $map['uname'] = array('like', '%'.$uname.'%');

        //赠品规则

        ($glif = I('glif','','intval')) && $this->assign('glif',$glif);



        $cate_id = I('cate_id',0, 'intval');



        if ($cate_id) {



            $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);



            $map['cate_id'] = array('IN', $id_arr);



            $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');



            if( $spid==0 ){



                $spid = $cate_id;



            }else{



                $spid .= $cate_id;



            }



        }



        if( $_GET['status']==null ){



            $status = -1;



        }else{



            $status = intval($_GET['status']);



        }



        $status>=0 && $map['status'] = array('eq',$status);



        ($keyword = I('keyword','',  'trim')) && $map['title'] = array('like', '%'.$keyword.'%');



        $this->assign('search', array(



            'time_start' => $time_start,



            'time_end' => $time_end,



            'price_min' => $price_min,



            'price_max' => $price_max,



            'rates_min' => $rates_min,



            'rates_max' => $rates_max,



            'uname' => $uname,



            'status' =>$status,



            'selected_ids' => $spid,



            'cate_id' => $cate_id,



            'keyword' => $keyword,



            'type' => $type,



        ));



        //区域划分

        if($_SESSION['admin']['role_id'] != 1){

            $map['city_id'] = $_SESSION['admin']['city_id'];

        }



        return $map;



    }
}















