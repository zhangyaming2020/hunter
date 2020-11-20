<?php
namespace App\Controller;
use Think\Rc4;
use Think\HxCommon;
/*
 *首页
 */
class IndexController extends ApiController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->ad=D('Ad');//广告表
        $this->article=D('Article');//文章消息表
        $this->article_member=D('ArticleMember');//文章读者关联表
        $this->member = D('Member');    //用户表
        $this->item = D('Item');          //商品表
        $this->item_cate = D('ItemCate'); //商品分类表
        $this->place=D('Place');//地区表
        $this->order=D('Address');//订单表
        $this->OrderList=D('OrderList');//订单详细
        $this->merchant=D('Merchant');//店铺

    }

    //首页
    public function index_info($datas){
        $data = $this->get_datas($datas);
	    dump($data);exit;
        //首页轮播
        $lux = $this->ad->where(array('board_id'=>22,'status'=>1))->order('ordid asc,id desc')->limit(3)->select();
        //新人报道
        $new_user = $this->ad->where(array('board_id'=>23,'status'=>1))->order('ordid asc,id desc')->find();
        //右上角消息提醒
        $uid=$data['id'];$is_read=0;//表示已读
        $new_list=$this->article->where('cate_id=23')->select();
        foreach($new_list as $k=>$v){
            $am=$this->article_member->where(array('aid'=>$v['id'],'uid'=>$uid))->find();
            (!$am)&& $is_read=1;
        }
        //消息推送
        $active_go = $this->article->where(array('cate_id'=>23,'status'=>1))->order('ordid asc,id desc')->select();
        //发现好货:标签fx
        $fl[0]=$this->item->where('fx=1 and status=1')->order('ordid asc,id desc')->limit(2)->select();
        //臻会买:标签zhm
        $fl[1]=$this->item->where('zhm=1 and status=1')->order('ordid asc,id desc')->limit(2)->select();
        //排行榜:销量
        $fl[2]=$this->item->where(array('status'=>1))->limit(2)->order('sales desc')->select();//hits或sales
        //臻怡家精选:标签jx
        $fl[3]=$this->item->where('jx=1 and status=1')->order('ordid asc,id desc')->limit(2)->select();
        //新品首发:add_time
        $fl[4]=$this->item->where(array('status'=>1))->limit(2)->order('ordid asc,add_time desc')->select();

//		//上新:
//		$fl[5]=$this->item->where(array('status'=>1,'tj'=>1))->order('id desc')->find();
//		//闪购
//		$fl[6]=$this->item->where(array('status'=>1,'rm'=>1))->order('id desc')->find();


        //爱生活(一级分类》二级分类》所属分类的商品):食品百货562
        $all_ash=D('ItemCate')->field('id,name')->where('pid=562 and status=1')->order('ordid asc,id desc')->limit(6)->select();
        foreach($all_ash as $k=>$v){
            $id_arr=M('item_cate')->where('FIND_IN_SET('.$all_ash[$k]['id'].',REPLACE (spid,"|",","))>0')->getField('id',true);
            if(!$id_arr){
                $item_pz=M('item')->where(array('cate_id'=>$all_ash[$k]['id']))->limit(2)->select();
            }else{
                $id_arr[]=$all_ash[$k]['id'];
                $item_pz=M('item')->where(array('cate_id'=>array('in',$id_arr)))
                    ->field('id,img')->order('ordid asc,id desc')->limit(2)->select();
            }
            $all_ash[$k]['xx']=$item_pz;
            if(!$item_pz) unset($all_ash[$k]);
        }

        //购品质(品牌) 
        $allpp=D('ItemBrand')->where(array('status'=>1,'pid'=>0))->order('ordid asc,id desc')->limit(6)->select();
        foreach($allpp as $k=>$v){
            $item_pz=$this->item->where(array('brand_id'=>$v['id'],'status'=>1))->field('id,img')->order('ordid asc,id desc')->limit(2)->select();
            $allpp[$k]['xx']=$item_pz;
        }

        //猜你喜欢:点击率
        $favour =$this->item->where(array('status'=>1))
            ->limit(4)->field('id,title,price,img,hits')->order('hits desc')->select();

        $this->json_Response('success',$datas['pack_no'],array(
            'lux'=>$lux,'new_user'=>$new_user,'is_read'=>$is_read,'active_go'=>$active_go
           ,'fl'=>$fl,'all_ash'=>$all_ash,'allpp'=>$allpp,'favour'=>$favour
        ));
    }

}

?>