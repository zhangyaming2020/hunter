<?php
namespace Admin\Controller;
class BannerController extends AdminCoreController {
	private $_ad_type = array('image'=>'图片', 'code'=>'代码', 'flash'=>'Flash', 'text'=>'文字');
    public $list_relation = true;
    public function _initialize() {
        parent::_initialize();
        $this->set_mod('Banner');
        $this->_mod = D('Banner');
        $this->_adboard_mod = D('Adboard');
    }

    public function _search() {
      $map = array();
      ($start_time = I('start_time','', 'trim')) && $map['start_time'] = array('elt', strtotime($start_time));
	   ($end_time = I('end_time','', 'trim')) && $map['end_time']= array('egt', strtotime($end_time)+(24*60*60-1));
      if( $_GET['status']==null ){

            $status = -1;

        }else{

            $status = intval($_GET['status']);
			$map['status']=$status;

        }
		
		('' !== $store_id = I('store_id', '', 'trim')) && $map['store_id'] = $store_id;
		
        ($keyword = I('keyword','', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status'   => $status,
			'store_id'=>$store_id,
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function _before_index() {
        $store_info=store_info();
		$this->assign('store_info',$store_info);
		
    }

    public function _before_add() {
        $this->assign('ad_type_arr', $this->_ad_type);
		$store_info=store_info();
		$this->assign('store_info',$store_info);
    }

    protected function _before_insert($data) {
        //判断开始时间和结束时间是否合法
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        if ($data['start_time'] >= $data['end_time']) {
            $this->ajax_return(0, L('ad_endtime_less_startime'));
        }

        switch ($data['type']) {
            case 'text':
                $data['content'] = I('text','', 'trim');
                break;
            case 'image':
                $data['content'] = I('img','', 'trim');
                break;
            case 'code':
                $data['content'] = I('code','', 'trim');
                break;
            case 'flash':
                $data['content'] = I('flash','', 'trim');
                break;
            default :
                $this->ajax_return(0, L('ad_type_error'));
                break;
        }
        return $data;
    }

    public function _before_edit() {
        $this->assign('ad_type_arr', $this->_ad_type);
		
		$store_info=store_info();
		$this->assign('store_info',$store_info);
    }

    protected function _before_update($data) {
        //判断开始时间和结束时间是否合法
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        if ($data['start_time'] >= $data['end_time']) {
            $this->ajax_return(0, L('ad_endtime_less_startime'));
        }
        switch ($data['type']) {
            case 'text':
                $data['content'] = I('text','', 'trim');
                break;
            case 'image':
                $data['content'] = I('img','', 'trim');
                break;
            case 'code':
                $data['content'] = I('code','', 'trim');
                break;
            case 'flash':
                $data['content'] = I('flash','', 'trim');
                break;
            default :
                $this->ajax_return(0, L('ad_type_error'));
                break;
        }
        return $data;
    }

    //上传图片
    public function ajax_upload_img() {
        $type = I('type', 'img', 'trim');
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
	
	  /**
     * 添加
     */
    public function add() {
        $mod = D($this->_name);
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());
            }
		    
            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }  
			$ids=I('ids')?I('ids'):array('0');
			foreach($ids as $k=>$v){
				$arr[$k]=$data;
				$arr[$k]['store_id']=$v;
			}  
            if( $mod->addAll($arr) ){
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
				
            }
        } else {
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'add');
            } else {
                $this->display();
            }
        }
    }
}