<?php
namespace Admin\Controller;
use Admin\Org\Image;
use Admin\Org\Tree;
class CouponCateController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Yhq_cate');
        $this->set_mod('Yhq_cate');
		
		$this->assign('list_table', true);
    }
	
	//优惠券生成
	public function coupon(){
		$yhq=M("yhq");
		$act=I("get.act");
		if($act=='yhq'){    
			$data['types']=1;
//			$num=I("post.snnum");
//			$data['aid']=I("post.id");
			$data['status']=0;
			$data['title']=I("post.title");
			$data['price']=I("post.price");
			$data['minprice']=I("post.minprice");
			$data['apply']=I("post.apply");
			$data['introductions']=I("post.introductions");
			$data['starttime']=strtotime(I("post.starttime"));	
			$data['endtime']=strtotime(I("post.endtime"));	
			$data['city_id']=I("post.city_id");	
			$data['exchange']=I("post.exchange");	
			$data['integral']=I("post.integral");	  
			if( $data['price'] && $data['endtime']){
//					$data['sncode']=uniqid(mt_rand(10, 99));
					$this->_mod->add($data);
			$this ->redirect(U('coupon_cate/index',array('menuid'=>$menuid)));	
//				echo "<script>alert('success');window.location='".U('coupon/index',array('menuid'=>$menuid))."';</script>";
				exit;
			}else{
				echo "<script>alert('请补全信息再提交！');window.location='".U('coupon/index',array('menuid'=>$menuid))."';</script>";
				exit;
			}
		}
		$this->display();
	}
	
//	//优惠券发放
//	public function coupon_issue(){
//		$yhq=M("yhq");
//		$act=I("get.act");
//		if($act=='yhq'){
//			$data['types']=1;
//			$data['status']=0;
//			$num=I("post.snnum");
//			$data['uid']=I("post.uid");
//			$data['cate_id'] = $id = I("post.cate_id");   //优惠券id
//			$yhq_cate = M('yhq_cate')  -> where(array('id'=>$id)) -> find();
//			$data['title'] = $yhq_cate['title'] ;
//			$data['price']= $yhq_cate['price'] ;
//			$data['minprice']= $yhq_cate['minprice'] ;
//			$data['apply']=$yhq_cate['apply'] ;
//			$data['introductions']=$yhq_cate['introductions'] ;
//			$data['starttime']=$yhq_cate['starttime'] ;
//			$data['endtime']=$yhq_cate['endtime'] ;
//			for ($x=1; $x<=$num; $x++){
//					$data['sncode']=uniqid(mt_rand(10, 99));
//				$rs = $yhq->add($data);
//				}
//			$this -> redirect(U('coupon/index_issue'));	
//		}	
//		$this->display();
//	}
	


    public function _before_index() {
        //区域划分
        $city_id = region_division();
		$this->assign('city_id',$city_id);
		//地区名称
		$city = M('place')->where(array('type'=>2,'status'=>1))->select();
		$city_name = array();
		foreach ($city as $val) {
            $city_name[$val['id']] = $val['name'];
        }
		$this->assign('city_name',$city_name);
		//序号
        $p = I('p',1,'intval');
        $this->assign('p',$p);
		
		$list = $this ->_mod -> order('id desc')  -> select();
		$this ->  assign('list',$list);
    }
	
	
//	//兑换优惠券
//	public function exchange(){
//		$list = $this ->_mod -> where(array('types'=>2))-> order('id desc')  -> select();
//		$this -> assign('list',$list);
//		  $p = I('p',1,'intval');
//      $this->assign('p',$p);
//		$this -> display();
//	}
	
	
    protected function _search() {
        $map = array();
        ($time_start = I('time_start','', 'trim')) && $map['starttime'][] = array('egt', strtotime($time_start));
        ($time_end = I('time_end','', 'trim')) && $map['endtime'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        ($status = I('status','', 'trim')) && $map['status'] = $status;
		($price = I('price','', 'trim')) && $map['price'] = $price;
		($sncode = I('sncode','', 'trim')) && $map['sncode'] = array('like', '%'.$sncode.'%');
        ($keyword = I('keyword','', 'trim')) && $map['title'] = array('like', '%'.$keyword.'%');
//      $cate_id = I('cate_id','', 'intval');
//      $selected_ids = '';
//      if ($cate_id) {
//          $id_arr = $this->_cate_mod->get_child_ids($cate_id, true);
//          $map['cate_id'] = array('IN', $id_arr);
//          $spid = $this->_cate_mod->where(array('id'=>$cate_id))->getField('spid');
//          $selected_ids = $spid ? $spid . $cate_id : $cate_id;
//      }
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'cate_id' => $cate_id,
//          'selected_ids' => $selected_ids,
            'status'  => $status,
            'keyword' => $keyword,
        ));
		
		//区域划分
		if($_SESSION['admin']['role_id'] != 1){
    		$map['city_id'] = $_SESSION['admin']['city_id'];
    	}
        return $map;
    }

    public function _before_add()
    {
        $author = $_SESSION['pp_admin']['username'];
        $this->assign('author',$author);

        $site_name = D('setting')->where(array('name'=>'site_name'))->getField('data');
        $this->assign('site_name',$site_name);

        $first_cate = $this->_cate_mod->field('id,name')->where(array('pid'=>0))->order('ordid DESC')->select();
        $this->assign('first_cate',$first_cate);

        //取属性列表
        $attrs = D('Attr')->select();
        $this->assign('attrs',$attrs);
    }

    protected function _before_insert($data) {

        //上传图片
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d/');
            $result = $this->_upload($_FILES['img'], 'article/' . $art_add_time, array('width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time .'/'. str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        }
        return $data;
    }

    public function _after_insert($id){
        //获取属性
        $attrs = I('attr');
        //添加
        $article_attr = array();
        foreach($attrs as $val){
            $article_attr[] = array(
                'article_id' => $id,
                'attr_id' => $val,
            );
        }
        M('Article_attr')->addAll($article_attr);
    }

    public function _before_edit(){
        $id = I('id','','intval');
        $article = $this->_mod->field('id,cate_id')->where(array('id'=>$id))->relation('attr')->find();
        $attr_list = array();
        $attr_list = array_map('array_shift', $article['attr']);
        $spid = $this->_cate_mod->where(array('id'=>$article['cate_id']))->getField('spid');
        if( $spid==0 ){
            $spid = $article['cate_id'];
        }else{
            $spid .= $article['cate_id'];
        }
        $this->assign('selected_ids',$spid);
        //取属性列表
        $attrs = D('Attr')->select();
        $this->assign('attrs',$attrs);
        $this->assign('attr_list',$attr_list);
    }

    protected function _before_update($data) {
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d/');
            //删除原图
            $old_img = $this->_mod->where(array('id'=>$data['id']))->getField('img');
            $old_img = $this->_get_imgdir($old_img,'article');
            is_file($old_img) && @unlink($old_img);
            //上传新图
            $result = $this->_upload($_FILES['img'], 'article/' . $art_add_time, array('width'=>C('pin_article_img.width'), 'height'=>C('pin_article_img.height'), 'remove_origin'=>true));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time .'/'. str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        } else {
            unset($data['img']);
        }
        //获取属性
        $attrs = I('attr');
        //删除
        M('Article_attr')->where(array('article_id'=>$data['id']))->delete();
        //添加
        $article_attr = array();
        foreach($attrs as $val){
            $article_attr[] = array(
                'article_id' => $data['id'],
                'attr_id' => $val,
            );
        }
        M('Article_attr')->addAll($article_attr);
        return $data;
    }

    /**
     * 单页管理
     */
    public function page() {
        $prefix = C('DB_PREFIX');
        $sort = I("sort", 'ordid', 'trim');
        $order = I("order", 'DESC', 'trim');

        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = $this->_cate_mod->field('id,pid,name,last_time')->join($prefix .'article_page on '.$prefix .'article_page.cate_id ='.$prefix .'article_cate.id')->where(array('type'=>1))->order($sort . ' ' . $order)->select();
        $array = array();
        foreach($result as $r) {
            //是否有下一级
            if ($this->_cate_mod->where(array('pid'=>$r['id']))->count('id')) {
                $r['str_manage'] = '';
            } else {
                $r['str_manage'] = '<a href="'.U('article/page_edit', array('cate_id'=>$r['id'])).'">'.L('edit').'</a>';
            }
            $r['parentid_node'] = ($r['pid'])? ' class="child-of-node-'.$r['pid'].'"' : '';
            $r['last_time'] = $r['last_time'] ? date('Y-m-d H:i:s', $r['last_time']) : '-';
            $array[] = $r;
        }
        $str  = "<tr id='node-\$id' \$parentid_node>
                <td align='center'>\$id</td>
                <td>\$spacer\$name</td>
                <td align='center'>\$last_time</td>
                <td align='center'>\$str_manage</td>
                </tr>";
        $tree->init($array);
        $list = $tree->get_tree(0, $str);
        $this->assign('list', $list);
        $this->assign('list_table', true);
        $this->display();
    }

    /**
     * 单页内容编辑
     */
    public function page_edit() {
        $page_mod = D('article_page');
        if (IS_POST) {
            if (false === $data = $page_mod->create()) {
                $this->error($page_mod->getError());
            }
            if (!$page_mod->where(array('cate_id'=>$data['cate_id']))->count()) {
                $page_mod->add($data);
            } else {
                $page_mod->save($data);
            }
            $this->success(L('operation_success'), U('article/page'));
        } else {
            $cate_id = I('cate_id','','intval');
            $cate_info = $this->_cate_mod->field('id,name')->where(array('type'=>1, 'id'=>$cate_id))->find();
            !$cate_info && $this->redirect('article/page');
            $this->assign('cate_info', $cate_info);
            $info = $page_mod->where(array('cate_id'=>$cate_id))->find();
            $this->assign('info', $info);
            $this->display();
        }
    }


    /**
     * ajax获取标签
     */
    public function ajax_gettags() {
        $title = I('title','', 'trim');
        if ($title) {
            $tags = D('Tag')->get_tags_by_title($title);
            $tags = implode(' ', $tags);
            $this->ajax_return(1, L('operation_success'), $tags);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }

    public function get_attach_data($data){
        if (!empty($_FILES['img']['name'])) {
            $art_add_time = date('ym/d/');
            //删除原图
            $old_img = $this->_mod->where(array('id'=>$data['id']))->getField('img');
            $old_img = attach('$old_img','artcile');
            is_file($old_img) && @unlink($old_img);
            //上传新图
            $result = $this->_upload($_FILES['img'], 'article/' . $art_add_time, array('width'=>'130', 'height'=>'100', 'remove_origin'=>true));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $art_add_time .'/'. str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
            }
        } else {
            unset($data['img']);
        }

        return $data;
    }

}