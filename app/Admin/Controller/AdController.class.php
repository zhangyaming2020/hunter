<?php
namespace Admin\Controller;
class AdController extends AdminCoreController {
	public function _initialize() {
        parent::_initialize();
        $this->set_mod('Ad');
        $this->_mod = D('Ad');
    }

    public function _search() {
        $map = array();
        ($start_time_min = I('start_time_min','', 'trim')) && $map['start_time'][] = array('egt', strtotime($start_time_min));
        ($start_time_max = I('start_time_max','', 'trim')) && $map['start_time'][] = array('elt', strtotime($start_time_max)+(24*60*60-1));
        ($end_time_min = I('end_time_min','', 'trim')) && $map['end_time'][] = array('egt', strtotime($end_time_min));
        ($end_time_max = I('end_time_max','', 'trim')) && $map['end_time'][] = array('elt', strtotime($end_time_max)+(24*60*60-1));
        $board_id = I('board_id','', 'intval');
        $board_id && $map['board_id'] = $board_id;
        $style = I('style','', 'trim');
        $style && $map['type'] = array('eq',$style);
        ($keyword = I('keyword','', 'trim')) && $map['name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'start_time_min' => $start_time_min,
            'start_time_max' => $start_time_max,
            'end_time_min' => $end_time_min,
            'end_time_max' => $end_time_max,
            'board_id' => $board_id,
            'style'   => $style,
            'keyword' => $keyword,
        ));
        return $map;
    }

    public function _before_index() {
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


    protected function _before_update($data) {
        //判断开始时间和结束时间是否合法
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        if ($data['start_time'] >= $data['end_time']) {
            $this->ajax_return(0, L('ad_endtime_less_startime'));
        }
        return $data;
    }

    //上传图片
    public function ajax_upload_img() {
        $type = I('type', 'img', 'trim');
//		print_r($_FILES[$type]['name']);  exit;
        if (!empty($_FILES[$type]['name'])) {
            $dir = date('ym/d/');
            $result = $this->_upload($_FILES[$type], 'ad/'. $dir );
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
}