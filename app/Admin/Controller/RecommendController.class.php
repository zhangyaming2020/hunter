<?php
namespace Admin\Controller;
use Think\Page;
class RecommendController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Recommend');
        $this->set_mod('Recommend');
		$this->rel_mod=M('item_cate');
    }
	
	  public function _search() {
      $map = array();
    	 if( $_GET['status']==null ){

            $status = -1;

        }else{

            $status = intval($_GET['status']);
			$map['status']=$status;

        }
		
		('' !== $store_id = I('store_id', '', 'trim')) && $map['store_id'] = $store_id;
		
		('' !== $cate_id = I('cate_id', '', 'trim')) && $map['cate_id'] = $cate_id;
		
        ($keyword = I('keyword','', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'cate_id' => $cate_id,
            'status'   => $status,
			'store_id'=>$store_id,
            'keyword' => $keyword,
        ));
        return $map;
    }
	//商品分类
    public function item_cate(){
		$item_cate=$this->rel_mod->where(array('status'=>1,'pid'=>0))->select();
		 
		 $this->assign('item_cate',$item_cate);
		}
	public function _before_add() {
         $this->item_cate();
		 $store_info=store_info();
		 $this->assign('store_info',$store_info);
    }
	
	public function _before_edit() {
         $this->item_cate();
		 $store_info=store_info();
		 $this->assign('store_info',$store_info);
    }
	public function _before_insert($data){
		foreach($_POST as $k=>$v){
			($k=='brand_id'||$k=='origin_id'||$k=='smell_id')&&$data['field_type']=$k;
			($k=='brand_id'||$k=='origin_id'||$k=='smell_id')&&$data['field_id']=$v;
			
		}
		return $data;
	}
	public function _before_index(){
		$store_info=store_info();
		$this->assign('store_info',$store_info);
		$this->item_cate();
	}
	//查找
	public function ajax_find(){
		$cid=I('cid','','intval');
		$type=I('type','','intval');
		$wh=array();
		switch($type){
			case 1:$table='tag';$field='smell_id';break;
			case 2:$table='item_brand';$field='brand_id';break;
			case 3:$table='nation';$field='origin_id';break;
		}
		if($type!=1){
			if($type==2){
				$wh['_string'] = "cate_id like '%".','.$cid."' or cate_id like '".$cid.",%' or cate_id like '%,".$cid.",%' or cate_id=".$cid;
			}
			else{
				$wh['pid']=$cid;
			}
		}
		$result=M($table)->where($wh)->field('id,name')->select();
		//dump(M($table)->getLastSql());exit;
		$str='<select class="action" name="'.$field.'">';
		if($result){
			foreach($result as $k=>$v){
				$str .='<option value='.$v['id'].'>'.$v['name'].'</option>';
			}
			$str .='</select>';
		}
		$this->ajaxReturn(array('str'=>$str));
	}
	public function edit(){
		$id=I('id','','intval');
		if(IS_POST){
			$data = $this->_mod->create();
			$a=$this->_mod->where(array('id'=>$id))->save($data);
			if($a){
				 IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
			}else{
				 IS_AJAX && $this->ajax_return(0, $mod->getError());
			}
			
		}else{
			$info=$this->_mod->where(array('id'=>$id))->find();
			$this->assign("id",$id); 
		 	$this->assign("info",$info);
			$response = $this->fetch();
            $this->ajax_return(1, '', $response);
		}
	}
   
}