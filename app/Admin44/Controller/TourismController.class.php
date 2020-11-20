<?php



namespace Admin\Controller;

use Admin\Org\Tree;

class TourismController extends AdminCoreController {



    public function _initialize() {



        parent::_initialize();



        $this->_mod = D('tourism');



        $this->_cate_mod = D('ItemCate');



        $this->set_mod('tourism');



    }



    public function tourism_cate() {

        $sort = I("sort", 'ordid', 'trim');

        $order = I("order", 'ASC', 'trim');

        $tree = new Tree();

        $tree->icon = array('│ ','├─ ','└─ ');

        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $result = $this->_cate_mod->order($sort . ' ' . $order)->select();

        $array = array();

        foreach($result as $r) {

            $r['str_img'] = $r['img'] ? '<span class="img_border"><img src="'.attach($r['img'], 'item_cate').'" style="width:26px; height:26px;" class="check-image" data-bimg="'.attach($r['img'], 'item_cate').'" /></span>' : '';

            $r['str_status'] = '<img data-tdtype="toggle" data-id="'.$r['id'].'" data-field="status" data-value="'.$r['status'].'" src="'.C('TMPL_PARSE_STRING.__STATIC__').'/images/toggle_' . ($r['status'] == 0 ? 'disabled' : 'enabled') . '.gif" />';

            $r['str_manage'] = '<a href="javascript:;" class="J_showdialog" data-uri="'.U('item_cate/add',array('pid'=>$r['id'])).'" data-title="'.L('add_item_cate').'" data-id="add" data-width="520" data-height="360">'.L('add_item_subcate').'</a> |



                                <a href="javascript:;" class="J_showdialog" data-uri="'.U('item_cate/edit',array('id'=>$r['id'])).'" data-title="'.L('edit').' - '. $r['name'] .'" data-id="edit" data-width="520" data-height="360">'.L('edit').'</a> |

                                <a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="'.U('item_cate/delete',array('id'=>$r['id'])).'" data-msg="'.sprintf(L('confirm_delete_one'),$r['name']).'">'.L('delete').'</a>';

            $r['parentid_node'] = ($r['pid'])? ' class="child-of-node-'.$r['pid'].'"' : '';

            $array[] = $r;

        }

        $str  = "<tr id='node-\$id' \$parentid_node>

                <td align='center'><input type='checkbox' value='\$id' class='J_checkitem'></td>

                <td align='center'>\$id</td>

                <td align='center'>\$spacer<span data-tdtype='edit' data-field='name' data-id='\$id' class='tdedit'  style='color:\$fcolor'>\$name</span></td>

                <td align='center'>\$str_img</td>

                <td align='center'><span data-tdtype='edit' data-field='ordid' data-id='\$id' class='tdedit'>\$ordid</span></td>

                <td align='center'>\$str_status</td>

                <td align='center'>\$str_manage</td>

                </tr>";

        $tree->init($array);

        $list = $tree->get_tree(146, $str);

        //dump($list);exit;

        $this->assign('list', $list);

        $this->assign('list_table', true);

        $this->display();

    }

     public function ajax_upload_img(){

    //上传图片

            if (!empty($_FILES['file']['name'])) {

                $date_dir = date('ym/d/'); //上传目录

                $result = $this->_upload($_FILES['file'], 'item/'.$date_dir, array(

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



    public function _before_index() {

        //显示模式



        $sm = I('sm', '','trim');



        $this->assign('sm', $sm);





        //分类信息



        $res = $this->_cate_mod->field('id,name,pid')->select();



        $cate_list = array();



        foreach ($res as $val) {



            $cate_list[$val['id']] = $val['name'];



            $big_cate2[$val['id']] = $this->_cate_mod -> where(array('id'=>$val['pid'])) ->getField('name');



        }



//		print_r($big_cate2);



        $this->assign('cate_list', $cate_list);



        $this->assign('big_cate2', $big_cate2);



//	print_r($res);



        //默认排序



        $this->sort = 'ordid ASC,';



        $this->order ='id desc,add_time DESC';



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



    public function add_img(){

        $this -> display();

    }



    public function add() {

		

        if (IS_POST) {

            //获取数据
            if (false === $data = $this->_mod->create()) {

                $this->error($this->_mod->getError());

            }

            if( !$data['cate_id']||!trim($data['cate_id']) ){

                $this->error('请选择商品分类');

            }

            //必须上传图片

            if (empty($_FILES['img']['name'])) {

                $this->error('请上传商品图片');

            }

            //发布用户

            $user_rand = array_rand($users);

            $data['uid'] = $users[$user_rand]['id'];

            //$data['uname'] = $users[$user_rand]['username'];

            //上传图片

            $date_dir = date('ym/d/'); //上传目录

            $item_imgs = array(); //相册

            $result = $this->_upload($_FILES['img'], 'item/'.$date_dir, array(

                'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'),

                'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),

                'suffix' => '_b,_m,_s',

            ));

            if ($result['error']) {

                $this->error($result['info']);

            } else {

                $data['img'] = $date_dir . $result['info'][0]['savename'];

                //保存一份到相册

                $item_imgs[] = array(

                    'url'     => $data['img'],

                );

            }

            //上传相册

            $file_imgs = array();

            foreach( $_FILES['imgs']['name'] as $key=>$val ){

                if( $val ){

                    $file_imgs['name'][] = $val;

                    $file_imgs['type'][] = $_FILES['imgs']['type'][$key];

                    $file_imgs['tmp_name'][] = $_FILES['imgs']['tmp_name'][$key];

                    $file_imgs['error'][] = $_FILES['imgs']['error'][$key];

                    $file_imgs['size'][] = $_FILES['imgs']['size'][$key];

                }

            }

            if( $file_imgs ){

                $result = $this->_upload($file_imgs, 'item/'.$date_dir, array(

                    'width'=>C('pin_item_bimg.width').','.C('pin_item_simg.width'),

                    'height'=>C('pin_item_bimg.height').','.C('pin_item_simg.height'),

                    'suffix' => '_b,_s',

                ));

                if ($result['error']) {

                    $this->error($result['info']);

                } else {

                    foreach( $result['info'] as $key=>$val ){

                        $item_imgs[] = array(

                            'url'    => $date_dir . $val['savename'],

                            'order'  => $key + 1,

                        );

                    }

                }

            }

            $data['imgs'] = $item_imgs;

            //添加商品



            $item_id=$this->_mod->add($data);



//            $imgss=I('imgss');

//            if($imgss){

//                foreach($imgss as $key=>$val ){

//                    $item_imgs[] = array(

//                        'url'    => $val,

//                        'order'  => $key + 1,

//                        'add_time' =>time(),

//                        'item_id' =>$item_id,

//                        'type'=>1

//                    );

//                }

////              }

//            }dump($item_imgs);exit;

//

//            $items=M('item_img')->addALL($item_imgs);

            //商品编码

            /*$cate_id = I('cate_id','','trim');

            $where['id'] = $cate_id;

            $big_cate = $this -> _cate_mod -> where($where) -> getField('pid');

            $data1['product_id'] =sprintf("%02d", $big_cate).sprintf("%02d", $cate_id).sprintf("%04d", $item_id);

            $this -> _mod ->where(array('id'=>$item_id))-> data($data1)-> save();*/



//			echo $this->_mod -> _sql();

//			 print_r($data1); exit;

            //$item_id = $this->_mod->publish($data);

            //if(!$item_id){

//            	$this->success(L('_OPERATION_FAIL_'));

//			}else{

//				$this->success(L('_OPERATION_SUCCESS_'));

//			}

            //附加属性

            //附加属性

            $attr = I('attr');

            if($attr){

                foreach( $attr['name'] as $key=>$val ){

                    if($val&&$attr['value'][$key]){

                        $atr['pid'] = $attr['sx'][$key];

                        $atr['item_id'] = $item_id;

                        $atr['attr_name'] = $val;

                        $atr['attr_value'] = $attr['value'][$key];

                        $atr['attr_num'] = $attr['num'][$key];

                        $atr['attr_price'] = $attr['price'][$key];

                        $atr['attr_oldprice'] = $attr['oldprice'][$key];

                        //var_dump($attr['sx'][$key]);exit;

                        M('item_attr')->add($atr);

                    }

                }

            }

            $this->success(L('operation_success'));

        } else {



            $this->display();

        }

    }



    public function edit() {

        if (IS_POST) {

            //var_dump($this->_mod->create());exit();

            //获取数据

            if (false === $data = $this->_mod->create()) {

                $this->error($this->_mod->getError());

            }

            if( !$data['cate_id']||!trim($data['cate_id']) ){

                $this->error('请选择商品分类');

            }

            $data['tj']=I("post.tj");

            if(!$data['tj']){

                $data['tj']=2;

            }

            $rm = $data['rm'];

            if(!$data['rm']){

                $data['rm']=2;

            }

            $xp = $data['xp'];

            if(!$data['xp']){

                $data['xp']=2;

            }

            $yj = $data['yj'];

            if(!$data['yj']){

                $data['yj']=2;

            }



            $item_id = $data['id'];

            $date_dir = date('ym/d/'); //上传目录

            $item_imgs = array(); //相册

            //修改图片

            if (!empty($_FILES['img']['name'])) {

                $result = $this->_upload($_FILES['img'], 'item/'.$date_dir, array(

                    'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'),

                    'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),

                    'suffix' => '_b,_m,_s',

                ));

                if ($result['error']) {

                    $this->error($result['info']);

                } else {

                    $data['img'] = $date_dir . $result['info'][0]['savename'];

                    //保存一份到相册

//                  $item_imgs[] = array(

//                      'item_id' => $item_id,

//                      'url'     => $data['img'],

//                  );

                }

            }

            //上传相册



            $imgs=I('imgs');

            //dump($imgs);exit;

//          foreach( $_FILES['imgs']['name'] as $key=>$val ){

//

//              if( $val ){

//

//                  $file_imgs['name'][] = $val;

//

//                  $file_imgs['type'][] = $_FILES['imgs']['type'][$key];

//

//                  $file_imgs['tmp_name'][] = $_FILES['imgs']['tmp_name'][$key];

//

//                  $file_imgs['error'][] = $_FILES['imgs']['error'][$key];

//

//                  $file_imgs['size'][] = $_FILES['imgs']['size'][$key];

//

//              }

//

//          }



            if($imgs){

//              $result = $this->_upload($file_imgs, 'item/'.$date_dir, array(

//                  'width'=>C('pin_item_bimg.width').','.C('pin_item_simg.width'),

//                  'height'=>C('pin_item_bimg.height').','.C('pin_item_simg.height'),

//                  'suffix' => '_b,_s',

//              ));

//              if ($result['error']) {

//                  $this->error($result['info']);

//              } else {

                foreach($imgs as $key=>$val ){

                    $item_imgs[] = array(

                        'item_id' => $item_id,

                        'url'     =>  $val,

                        'order'   => $key + 1,

                        'add_time'=> time(),

                        'type'=>1

                    );

                }

//              }

            }

            //标签

            /*$tags = I('tags','', 'trim');

            if (!isset($tags) || empty($tags)) {

                $tag_list = D('Tag')->get_tags_by_title($data['intro']);

            } else {

                $tag_list = explode(' ', $tags);

            }

            if ($tag_list) {

                $item_tag_arr = $tag_cache = array();

                $tag_mod = M('tag');

                foreach ($tag_list as $_tag_name) {

                    $tag_id = $tag_mod->where(array('name'=>$_tag_name))->getField('id');

                    !$tag_id && $tag_id = $tag_mod->add(array('name' => $_tag_name)); //标签入库

                    $item_tag_arr[] = array('item_id'=>$item_id, 'tag_id'=>$tag_id);

                    $tag_cache[$tag_id] = $_tag_name;

                }

                if ($item_tag_arr) {

                    $item_tag = M('item_tag');

                    //清除关系

                    $item_tag->where(array('item_id'=>$item_id))->delete();

                    //商品标签关联

                    $item_tag->addAll($item_tag_arr);

                      $data['tag_cache'] = serialize($tag_cache);

                }

            }		*/

            //更新商品



            M('item_img')->where(array('item_id'=>$item_id))->delete();  

            $this->_mod->where(array('id'=>$item_id))->save($data);

            //更新图片和相册

            //var_dump($item_imgs);

            $item_imgs && M('item_img')->addAll($item_imgs);

            //echo M()->_sql();exit;



            //附加属性

            $attr = I('attr');

            if($attr){

                foreach( $attr['name'] as $key=>$val ){

                    if($val&&$attr['value'][$key]){

                        $atr['pid'] = $attr['sx'][$key];

                        $atr['item_id'] = $item_id;

                        $atr['attr_name'] = $val;

                        $atr['attr_value'] = $attr['value'][$key];

                        $atr['attr_num'] = $attr['num'][$key];

                        $atr['attr_price'] = $attr['price'][$key];

                        $atr['attr_oldprice'] = $attr['oldprice'][$key];

                        //var_dump($attr['sx'][$key]);exit;

                        M('item_attr')->add($atr);

                    }

                }

            }

            $this->success(L('operation_success'));

        } else {

            $id = I('id',0,'intval');

            $item = $this->_mod->where(array('id'=>$id))->find();

            $this->assign('tj', $item['tj']);

            $this->assign('rm', $item['rm']);

            $this->assign('xp', $item['xp']);

            $this->assign('yj', $item['yj']);

            //分类

            $cate = $this->_cate_mod->where(array('pid'=>146))->field('id,name')->select();
			$this->assign('cate',$cate); //分类选中

            $tag_cache = unserialize($item['tag_cache']);

            $item['tags'] = implode(' ', $tag_cache);

            $item['yhq'] = explode(',', $item['isyhq']);

            $this->assign('info', $item);

            //相册

            $img_list = M('item_img')->where(array('item_id'=>$id,'type'=>1))->select();

            $this->assign('img_list', $img_list);

            //类别属性

            $attr_sx=M('item_attr')->where("item_id=".$id." and pid=0")->select();

            $this->assign('attr_sx', $attr_sx);

            //属性

            $attr_list = M('item_attr')->where(array('item_id'=>$id))->select();

            $this->assign('attr_list', $attr_list);

            //成人价格

            $price=M('tourism_date')->where(array('item_id'=>$id,'type'=>0))->field('price,start_time')->select();
			
            foreach($price as $k=>$v) {

                $price[$k]['start_time']=date('Y-m-d',$v['start_time']);

                $price[$k]['price']=$v['price'];

            }
			
            $countss=count($price);
			 //儿童价格

            $price2=M('tourism_date')->where(array('item_id'=>$id,'type'=>1))->field('id,price,start_time')->select();
			
            foreach($price2 as $k=>$v) {

                $price2[$k]['start_time']=date('Y-m-d',$v['start_time']);

                $price2[$k]['price']=$v['price'];

            }
			//成人价
			$countss=count($price);

            $this->assign('countss',$countss);

            $this->assign('price',$price);
			$this->assign('price1',json_encode($price));
			//儿童价
			$countss2=count($price2);
			$this->assign('countss2',$countss2);

            $this->assign('price2',json_encode($price2));
			

            $this->display();

        }

    }



    /**

     * 删除

     */

    public function delete()

    {

        $mod = D($this->_name);

        $pk = $mod->getPk();

        $ids = trim(I($pk), ',');

        if ($ids) {

            if (false !== $mod->delete($ids)&&false !== D('Item_img')->delete($ids)) {

                IS_AJAX && $this->ajax_return(1, L('operation_success'));

                $this->success(L('operation_success'));

            } else {

                IS_AJAX && $this->ajax_return(0, L('operation_failure'));

                $this->error(L('operation_failure'));

            }

        } else {

            IS_AJAX && $this->ajax_return(0, L('illegal_parameters'));

            $this->error(L('illegal_parameters'));

        }

    }





    function delete_album() {



        $album_mod = M('item_img');



        $album_id = I('album_id',0,'intval');



        $album_img = $album_mod->where('id='.$album_id)->getField('url');



        if( $album_img ){



            $ext = array_pop(explode('.', $album_img));



            $album_min_img = C('pin_attach_path') . 'item/' . str_replace('.' . $ext, '_s.' . $ext, $album_img);



            is_file($album_min_img) && @unlink($album_min_img);



            $album_img = C('pin_attach_path') . 'item/' . $album_img;



            is_file($album_img) && @unlink($album_img);



            $album_mod->delete($album_id);



        }



        echo '1';



        exit;



    }







    function delete_attr() {



        $attr_mod = M('item_attr');



        $attr_id = I('attr_id',0,'intval');



        $attr_mod->delete($attr_id);



        echo '1';



        exit;



    }







    /**



     * 商品审核



     */



    public function check() {



        //分类信息



        $res = $this->_cate_mod->field('id,name')->select();



        $cate_list = array();



        foreach ($res as $val) {



            $cate_list[$val['id']] = $val['name'];



        }



        $this->assign('cate_list', $cate_list);



        //商品信息



        //$map = $this->_search();



        $map=array();



        $map['status']=0;



        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));



        ($time_end = I('time_end','', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));



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



        ($keyword = I('keyword', 'trim')) && $map['title'] = array('like', '%'.$keyword.'%');



        $this->assign('search', array(



            'time_start' => $time_start,



            'time_end' => $time_end,



            'selected_ids' => $spid,



            'cate_id' => $cate_id,



            'keyword' => $keyword,



        ));



        //分页



        $count = $this->_mod->where($map)->count('id');



        $pager = new Page($count, 20);



        $select = $this->_mod->field('id,title,img,tag_cache,cate_id,uid,uname')->where($map)->order('id DESC');



        $select->limit($pager->firstRow.','.$pager->listRows);



        $page = $pager->show();



        $this->assign("page", $page);



        $list = $select->select();



        foreach ($list as $key=>$val) {



            $tag_list = unserialize($val['tag_cache']);



            $val['tags'] = implode(' ', $tag_list);



            $list[$key] = $val;



        }



        $this->assign('list', $list);



        $this->assign('list_table', true);



        $this->display();



    }







    /**



     * 审核操作



     */



    public function do_check() {



        $mod = D($this->_name);



        $pk = $mod->getPk();



        $ids = trim(I($pk), ',');



        $datas['id']=array('in',$ids);



        $datas['status']=1;



        if ($datas) {



            if (false !== $mod->save($datas)) {



                IS_AJAX && $this->ajax_return(1, L('operation_success'));



            } else {



                IS_AJAX && $this->ajax_return(0, L('operation_failure'));



            }



        } else {



            IS_AJAX && $this->ajax_return(0, L('illegal_parameters'));



        }







    }







    /**



     * ajax获取标签



     */



    public function ajax_gettags() {



        $title = I('title','', 'trim');



        $tag_list = D('Tag')->get_tags_by_title($title);



        $tags = implode(' ', $tag_list);



        $this->ajax_return(1, L('operation_success'), $tags);



    }







    public function delete_search() {



        $items_mod = D('Item');



        $items_cate_mod = D('ItemCate');



        $items_pics_mod = D('ItemImg');



        $items_tags_mod = D('ItemTag');



        $items_comments_mod = D('ItemComment');







        if (isset($_REQUEST['dosubmit'])) {



            if ($_REQUEST['isok'] == "1") {



                //搜索



                $where = '1=1';



                $keyword = trim($_POST['keyword']);



                $cate_id = trim($_POST['cate_id']);



                $cate_id = trim($_POST['cate_id']);



                $time_start = trim($_POST['time_start']);



                $time_end = trim($_POST['time_end']);



                $status = trim($_POST['status']);



                $min_price = trim($_POST['min_price']);



                $max_price = trim($_POST['max_price']);



                $min_rates = trim($_POST['min_rates']);



                $max_rates = trim($_POST['max_rates']);







                if ($keyword != '') {



                    $where .= " AND title LIKE '%" . $keyword . "%'";



                }



                if ($cate_id != ''&&$cate_id!=0) {



                    $where .= " AND cate_id=" . $cate_id;



                }



                if ($time_start != '') {



                    $time_start_int = strtotime($time_start);



                    $where .= " AND add_time>='" . $time_start_int . "'";



                }



                if ($time_end != '') {



                    $time_end_int = strtotime($time_end);



                    $where .= " AND add_time<='" . $time_end_int . "'";



                }



                if ($status != '') {



                    $where .= " AND status=" . $status;



                }



                if ($min_price != '') {



                    $where .= " AND price>=" . $min_price;



                }



                if ($max_price != '') {



                    $where .= " AND price<=" . $max_price;



                }



                if ($min_rates != '') {



                    $where .= " AND rates>=" . $min_rates;



                }



                if ($max_rates != '') {



                    $where .= " AND rates<=" . $max_rates;



                }



                $ids_list = $items_mod->where($where)->select();



                $ids = "";



                foreach ($ids_list as $val) {



                    $ids .= $val['id'] . ",";



                }



                if ($ids != "") {



                    $ids = substr($ids, 0, -1);



                    $items_pics_mod->where("item_id in(" . $ids . ")")->delete();



                    $items_tags_mod->where("item_id in(" . $ids . ")")->delete();



                    $items_comments_mod->where("item_id in(" . $ids . ")")->delete();



                    M('album_item')->where("item_id in(" . $ids . ")")->delete();



                    M('item_attr')->where("item_id in(" . $ids . ")")->delete();







                }



                $items_mod->where($where)->delete();







                //更新商品分类的数量



                $items_nums = $items_mod->field('cate_id,count(id) as items')->group('cate_id')->select();



                foreach ($items_nums as $val) {



                    $items_cate_mod->save(array('id' => $val['cate_id'], 'items' => $val['items']));



                    M('album')->save(array('cate_id' => $val['cate_id'], 'items' => $val['items']));



                }







                $this->success('删除成功', U('item/delete_search'));



            } else {



                $this->success('确认是否要删除？', U('item/delete_search'));



            }



        } else {



            $res = $this->_cate_mod->field('id,name')->select();







            $cate_list = array();



            foreach ($res as $val) {



                $cate_list[$val['id']] = $val['name'];



            }



            //$this->assign('cate_list', $cate_list);



            $this->display();



        }



    }

















//商品导入



    public function upload()

    {

        header("Content-Type:text/html;charset=utf-8");

        $upload = new \Think\Upload();// 实例化上传类

        $upload->maxSize   =     3145728 ;// 设置附件上传大小

        $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类

        $upload->savePath  =      '/'; // 设置附件上传目录

// 		$upload->savePath =  '/data/attachment/editer/image/';

        // 上传文件

        $info   =   $upload->uploadOne($_FILES['excelData']);

        $filename = './Uploads'.$info['savepath'].$info['savename'];

        $exts = $info['ext'];

        if(!$info) {// 上传错误提示错误信息

            $this->error($upload->getError());

        }else{// 上传成功

            $this->goods_import($filename, $exts);

        }

    }



    //导入数据页面

    public function import()

    {

        $this->display('goods_import');

    }



    //导入数据方法

    protected function goods_import($filename, $exts='xls')

    {

        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入

        import("Org.Util.PHPExcel");

        //创建PHPExcel对象，注意，不能少了\

        $PHPExcel=new \PHPExcel();

        //如果excel文件后缀名为.xls，导入这个类

        if($exts == 'xls'){

            import("Org.Util.PHPExcel.Reader.Excel5");

            $PHPReader=new \PHPExcel_Reader_Excel5();

        }else if($exts == 'xlsx'){

            import("Org.Util.PHPExcel.Reader.Excel2007");

            $PHPReader=new \PHPExcel_Reader_Excel2007();

        }





        //载入文件

        $PHPExcel=$PHPReader->load($filename);

        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推

        $currentSheet=$PHPExcel->getSheet(0);

        //获取总列数

        $allColumn=$currentSheet->getHighestColumn();

        //获取总行数

        $allRow=$currentSheet->getHighestRow();

        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始

        for($currentRow=2;$currentRow<=$allRow;$currentRow++){

            //从哪列开始，A表示第一列

            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){

                //数据坐标

                $address=$currentColumn.$currentRow;

                //读取到的数据，保存到数组$arr中

                $data[$currentRow][$currentColumn]=(string)$currentSheet->getCell($address)->getValue();

            }



        }

        $this->save_import($data);

    }



    //保存导入数据

    public function save_import($data)

    {

        $Goods = M('item');

        $add_time = time();

        foreach ($data as $k=>$v){

            if(trim($v['G'])=='开启'){

                $kutai=1;

            }elseif(trim($v['G'])=='关闭'){

                $kutai=0;

            }



            if(trim($v['I'])=='正常'){

                $shangtai=1;

            }elseif(trim($v['I'])=='停售'){

                $shangtai=0;

            }

            //echo$shangtai;exit;



            $date['product_id'] = $v['A'];

            $date['title'] = $v['B'];

            $date['unit'] = $v['C'];

            $date['price']=$v['D'];

            $date['integral']=$v['E'];

            $date['intro']=$v['F'];

            $date['inv_status']=$kutai;

            $date['inventory']=$v['H'];

            $date['status']=$shangtai;

            $date['add_time']=$add_time;

//			$fileName="0000/00/".$v['J'];

//			$img =  explode('.' , $v['J']);

            if($v['J']){

                $img =  explode(';' , $v['J']);

                foreach($img as $i => $val){

                    $img_name1 = $img[0];

                    $img1 =  explode('.' , $img_name1);

//				$imgs[] = $img[$i] ;

//				unset($imgs[0]);

                }

// 			$date_dir = date('ym/d/');

                $img_name1 = md5($img_name1);

                $fileName="image/0000/00/".$img_name1.".".end($img1);

//			$fileName="0000/00/".uniqid().".".end($img);

//			$fileName=$date_dir.$v['J'];

//			$img = iconv('UTF-8', 'GBK', $fileName);

                //dump($img);exit;

            }else{

                $fileName='';

            }

//			print_r($img) ; exit;

            $date['img']=$fileName;

            $date['img_cn']=$v['J'];

            $date['sales']=$v['K'];

            $date['label']=$v['L'];

            $date['brand']=$v['M'];

            $date['oldprice']=$v['N'];

            $date['quota']=$v['O'];

            $date['origin']=$v['P'];

            $date['cate_id']= cate_to($v['Q']);

            $date['ordid']=$v['R'];

            $date['id']= $v['S'];

            $date['city_id']= $v['T'];

            $date['nums']= $v['U'];

//			print_r($date); exit;

            $result = M('item')->add($date);

//		   echo M() -> _sql(); exit;

            $data1['item_id']  = $result	;

            foreach($img as $i => $val){

                if($val){

                    $img_name2 = md5($val);

                    $img2 =  explode('.' , $val);

                    $fileName2="image/0000/00/".$img_name2.".".end($img2);

                    $data1['url'] = $fileName2;

//			print_r($val);

                    M('Item_img') -> add($data1) ;

                }



            }

//			print_r($imgs);



        }

        if($result){

            $this->success('导入成功');

        }else{

            $this->error('导入失败');

        }

        //print_r($info);

    }







    //导出数据方法

    public function goods_export()

    {

        ob_end_clean();

        $res = $this->_cate_mod->field('id,name,pid')->select();



        $cate_list = array();



        foreach ($res as $val) {



            $cate_list[$val['id']] = $val['name'];



            $big_cate2[$val['id']] = $this->_cate_mod -> where(array('id'=>$val['pid'])) ->getField('name');



        }



//		print_r($big_cate2);



        $this->assign('cate_list', $cate_list);



        $this->assign('big_cate2', $big_cate2);

        //区域划分

        $map = array();

        if($_SESSION['admin']['role_id'] != 1){

            $map['city_id'] = $_SESSION['admin']['city_id'];

        }



        $goods_list = M('item')->where($map)->select();

//        print_r($goods_list);exit;

        $data = array();

        foreach ($goods_list as $k=>$goods_info){

            if(!$big_cate2[$goods_info['cate_id']] && !$cate_list[$goods_info['cate_id']]){

                $fenlei= "";

            }else if(!$big_cate2[$goods_info['cate_id']]){

                $fenlei= $cate_list[$goods_info['cate_id']];

            }else{

                $fenlei=$big_cate2[$goods_info['cate_id']]."|".$cate_list[$goods_info['cate_id']];

            }

            //echo $goods_info['product_id'];exit;

            //商品编号	商品名	商品单位	商品价格	商品积分	商品描述	库存状态	库存量	商品状态	商品图片	销量	商品标签	品牌	市场价格	限购数量	产地	分类	排序

            if($goods_info['inv_status'] == 1){

                $kutai='开启';

            }elseif($goods_info['inv_status'] == 0){

                $kutai='关闭';

            }



            if($goods_info['status'] == 1){

                $shangtai='正常';

            }elseif($goods_info['status'] == 0){

                $shangtai= '停售';

            }

            $data[$k][product_id] = ' '.$goods_info['product_id'];//商品编号

            $data[$k][title] = $goods_info['title']; //	商品名

            $data[$k][unit] = $goods_info['unit']; //商品单位

            $data[$k][price] = $goods_info['price']; //商品价格

            $data[$k][integral] = $goods_info['integral']; //商品积分

            $data[$k][intro] = $goods_info['intro'];//商品描述

            $data[$k][inv_status] = $kutai;//库存状态

            $data[$k][inventory] = $goods_info['inventory']; //库存量

            $data[$k][status] = $shangtai; //商品状态

            $data[$k][img_cn] = $goods_info['img_cn']; //商品图片

//			   $data[$k][img] = $goods_info['img']; //商品图片

            $data[$k][sales] = $goods_info['sales'];//销量

            $data[$k][label] = $goods_info['label']; //商品标签

            $data[$k][brand] = $goods_info['brand']; //品牌

            $data[$k][oldprice] = $goods_info['oldprice']; //市场价格

            $data[$k][quota] = $goods_info['quota']; //限购数量

            $data[$k][origin] = $goods_info['origin']; //产地

            $data[$k][cate_id]=$fenlei;// 分类

            $data[$k][ordid] = $goods_info['ordid']; //排序

            $data[$k][id] = $goods_info['id'];// ID

            $data[$k][city_id] = $goods_info['city_id'];// 城市ID

            $data[$k][nums] = $goods_info['nums'];// 计量

        }

        //echo strlen($goods_info['product_id']);exit;

        //print_r($goods_list);

        //print_r($data);exit;

        foreach ($data as $field=>$v){

            if($field == 'product_id'){

                $headArr[]='商品编号';

            }

            if($field == 'title'){

                $headArr[]='商品名';

            }

            if($field == 'unit'){

                $headArr[]='商品单位';

            }

            if($field == 'price'){

                $headArr[]='商品价格';

            }

            if($field == 'integral'){

                $headArr[]='商品积分';

            }

            if($field == 'intro'){

                $headArr[]='商品描述';

            }

            if($field == 'inv_status'){

                $headArr[]='库存状态';

            }

            if($field == 'inventory'){

                $headArr[]='库存量';

            }

            if($field == 'status'){

                $headArr[]='商品状态';

            }

            if($field == 'img_cn'){

                $headArr[]='商品图片';

            }

            if($field == 'sales'){

                $headArr[]='销量';

            }

            if($field == 'label'){

                $headArr[]='商品标签';

            }





            if($field == 'brand'){

                $headArr[]='品牌';

            }



            if($field == 'oldprice'){

                $headArr[]='市场价格';

            }

            if($field=='quota'){

                $headArr[]='限购数量';

            }

            if($field=='origin'){

                $headArr[]='产地';

            }



            if($field=='cate_id'){

                $headArr[]='分类';



            }

            if($field=='ordid'){

                $headArr[]='排序';

            }

            if($field=='id'){

                $headArr[]='ID';

            }

            if($field=='city_id'){

                $headArr[]='城市ID';

            }

            if($field=='nums'){

                $headArr[]='计量';

            }



        }



        $filename="goods_list";



        $this->getExcel($filename,$headArr,$data);

    }





    private  function getExcel($fileName,$headArr,$data){

        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入

        import("Org.Util.PHPExcel");

        import("Org.Util.PHPExcel.Writer.Excel5");

        import("Org.Util.PHPExcel.IOFactory.php");



        $date = date("Y_m_d",time());

        $fileName .= "_{$date}.xls";



        //创建PHPExcel对象，注意，不能少了\

        $objPHPExcel = new \PHPExcel();

        $objProps = $objPHPExcel->getProperties();



        //设置表头

        $key = ord("A");

        //print_r($headArr);exit;

        foreach($headArr as $v){

            $colum = chr($key);

            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);

            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);

            $key += 1;

        }



        $column = 2;

        $objActSheet = $objPHPExcel->getActiveSheet();



        //print_r($data);exit;

        foreach($data as $key => $rows){ //行写入

            $span = ord("A");

            foreach($rows as $keyName=>$value){// 列写入

                $j = chr($span);

                $objActSheet->setCellValue($j.$column, $value);

                $span++;

            }

            $column++;

        }



        $fileName = iconv("utf-8", "gb2312", $fileName);

        //重命名表

        //$objPHPExcel->getActiveSheet()->setTitle('test');

        //设置活动单指数到第一个表,所以Excel打开这是第一个表

        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header("Content-Disposition: attachment;filename=\"$fileName\"");

        header('Cache-Control: max-age=0');



        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        $objWriter->save('php://output'); //文件通过浏览器下载

        exit;

    }

    public function tourism_order(){

        ($order_sn = I('order_sn','','trim'))&&$data['order_sn']=array('like','%'.$order_sn.'%');

        ($username = I('username','','trim'))&&$data['name']=array('like','%'.$username.'%');

        ($id = I('id','','trim'))&&$data['member_id']=$id;

        ($tel = I('tel','','trim'))&&$data['mobile']=$tel;

        $status = I('status','-1','trim');

        if($status!=''&&$status!=-1) $data['status']=$status;

        ($time_start = I('time_start','','trim'))&&$data['create_time'][]=array('egt',strtotime($time_start));

        ($time_end = I('time_end','','trim'))&&$data['create_time'][]=array('elt',strtotime($time_end.' +1 days'));



        $count = D('tourism_order')->count();

        $pages = new \Think\Page($count,50);

        $show = $pages->show();

        $this->assign('page',$show);

        $list = D('tourism_order')

            ->field('order_sn,id,member_id,name,mobile,id_code,create_time,item_id,man,mans,amount')

            ->order('id desc')

            ->limit($pages->firstRow.','.$pages->listRows)

            ->where($data)

            ->select();

        foreach($list as $k=>$v){

            $list[$k]['title']=M('tourism')->where(array('id'=>$v['item_id']))->getField('title');

        }

        $this->assign('list',$list);

        $this->assign('ser',array(

            'order_sn'=>$order_sn,'username'=>$username,

            'id'=>$id,'tel'=>$tel,'status'=>$status,

            'time_start'=>$time_start,'time_end'=>$time_end,

        ));

        $this->assign('order_status',C('order_status'));

        $this->assign('zf_type',C('zf_type'));

        $this->display();

    }

    public function tourism_article(){

        $this->display();

    }

    public function tourisms(){

        ($order_sn = I('order_sn','','trim'))&&$data['order_sn']=array('like','%'.$order_sn.'%');

        ($username = I('username','','trim'))&&$data['name']=array('like','%'.$username.'%');

        ($id = I('id','','trim'))&&$data['member_id']=$id;

        ($tel = I('tel','','trim'))&&$data['mobile']=$tel;

        $status = I('status','-1','trim');

        if($status!=''&&$status!=-1) $data['status']=$status;

        ($time_start = I('time_start','','trim'))&&$data['add_time'][]=array('egt',strtotime($time_start));

        ($time_end = I('time_end','','trim'))&&$data['add_time'][]=array('elt',strtotime($time_end.' +1 days'));



        $count = D('customs')->count();

        $pages = new \Think\Page($count,50);

        $show = $pages->show();

        $this->assign('page',$show);

        $list = D('customs')

            ->order('id desc')

            ->limit($pages->firstRow.','.$pages->listRows)

            ->where($data)

            ->select();

        foreach($list as $k=>$v){

            $list[$k]['hotel']=M('hotel')->where(array('id'=>$v['hotel_id']))->getField('title');

            $list[$k]['tickets']=M('tickets')->where(array('id'=>$v['tickets_id']))->getField('title');

        }

        $this->assign('list',$list);

        $this->assign('ser',array(

            'order_sn'=>$order_sn,'username'=>$username,

            'id'=>$id,'tel'=>$tel,'status'=>$status,

            'time_start'=>$time_start,'time_end'=>$time_end,

        ));

        $this->assign('order_status',C('order_status'));

        $this->assign('zf_type',C('zf_type'));

        $this->display();

    }

    public function xtx(){

        $id=I('id');

        M('customs')->where(array('id'=>$id))->delete();

        $this->success(L('operation_success'));

    }

    public function xtxs(){

        $id=I('id');

        M('tourism_order')->where(array('id'=>$id))->delete();

        $this->success(L('operation_success'));

    }

    public function date(){

        if(IS_POST){
            $data['price']=I('price');

            $data['start_time']=strtotime(I('startday'));

            $data['item_id']=I('id');
			$data['type']=I('type');
            $da=M('tourism_date')->where(array('item_id'=>$data['item_id'],'start_time'=>$data['start_time'],'type'=>$data['type']))->find();;
 			if($da){

               $aa=M('tourism_date')->where(array('start_time'=>$data['start_time']))->save($data);

            }else{

                $aa=M('tourism_date')->add($data);

            }

            $return=array(

                'status'=>$aa?1:0,

            );

           $this->ajaxReturn($return);

        }

    }

}