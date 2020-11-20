<?php
namespace App\Controller;

use Think\HxCommon;
use Think\Rc4;

/**
 * 好友管理
 * Class UserController
 * @package App\Controller
 */
class UserController extends ApiController {

    private $hx_common = null;
    private $user = null;
    private $member = null;
    private $email = null;
    private $jpush = null;
    public function _initialize(){
        parent::_initialize();
		$this->hx_common = new HxCommon(C('hx_params'));//环信
        $this->user = D('User');//好友表
        $this->member = D('Member');//用户表
        $this->email = D('Email');//邮件表
        $this->jpush = new  \Common\Hdpy\TaskJpush();
    }

    /**
     * 好友搜索
     * @param $datas
     */
    public function search_for_friends($datas)
    {
        $this->check_user_id($datas);//验证是否登录
        $data = $this->get_datas($datas);//获取数据

        (!$keyword = $data['keyword']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'keyword'));//搜索姓名不能为空
        
        $list = $this->member->get_names($keyword);
        $this->json_Response('success',$datas['pack_no'],array('list'=>$list));
    }

    /**
     * 添加好友
     * @param $datas
     */
    public function add_buddy($datas)
    {
        $this->check_user_id($datas);//验证是否登录
        $data = $this->get_datas($datas);//获取数据
        $uid = $datas['user_id'];//用户id

        (!$id = $data['id']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'id'));//添加好友id不能为空

        $results = $this->member->get_info($id);
        (empty($results) || $uid == $id) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'user_error'));//用户不存在||不能自己添加自己

        $status = $this->user->get_friends($uid,$id);
        ($status == 2) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'has_error'));//您们已是好友
        ($status == 1) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'apply_error'));//您已申请添加好友请求，请勿重复申请添加好友

        $arr = array(
            'uid' => $uid,
            'h_id' => $id,
        );
        if ($this->user->add_user($arr) !== false){
            $jpushContentTemplate = C('jpushContentTemplate');
            $this->jpush->push_message($jpushContentTemplate['task'][1]['content'],encode($id),$jpushContentTemplate['time_to_live']);
            $this->json_Response('success',$datas['pack_no']);
        } else{
            $this->json_Response('failed',$datas['pack_no']);
        }

    }

    /**
     * 用户申请添加好友列表
     * @param $datas
     */
    public function user_apply($datas)
    {
        $this->check_user_id($datas);//验证是否登录
        $uid = $datas['user_id'];//用户id

        $user_list = $this->user->list_of_friend_requests($uid);
        $map['id'] = array('in',implode(',',array_column($user_list,'uid')));
        $list = $this->member->get_more_user($map);
        $this->json_Response('success',$datas['pack_no'],array('list'=>$list));
    }

    /**
     * 审核好友请求
     * @param $datas
     */
    public function audit($datas)
    {
        $this->check_user_id($datas);//验证是否登录
        $data = $this->get_datas($datas);//获取数据
        $uid = $datas['user_id'];//用户id

        (!$id = $data['id']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'id'));//添加好友id不能为空
        (!$status = $data['status']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'status'));//1：同意 2：不同意

        $status = $this->user->get_friends($uid,$id);
        (!$status) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'status_error'));//没有该申请
        ($status == 2) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'has_error'));//已是好友

        switch ($status){//1：同意 2：不同意
            case 1:
                $result = $this->user->agreed_to($uid,$id);
                break;
            case 2:
                $result = $this->user->agree_with($uid,$id);
                break;
        }
        if ($result !== false){
            if ($status == 1){
                $hx_result = $this->hx_common->addFriend($id,$uid);
                if (empty($hx_result['entities']))
                    $this->json_Response('failed',$datas['pack_no'],array('ERROR_hx_reg_failed' => 'hx_add'));//环信添加好友失败

                $jpushContentTemplate = C('jpushContentTemplate');
                $this->jpush->push_message($jpushContentTemplate['task'][1]['content'],encode($id),$jpushContentTemplate['time_to_live']);
            }
            $this->json_Response('success',$datas['pack_no']);
        }else{
            $this->json_Response('failed',$datas['pack_no']);
        }
    }

    /**
     * 删除好友
     * @param $datas
     */
    public function del_user($datas)
    {
        $this->check_user_id($datas);//验证是否登录
        $data = $this->get_datas($datas);//获取数据
        $uid = $datas['user_id'];//用户id

        (!$id = $data['id']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'id'));//添加好友id不能为空

        $user = $this->user->get_users($uid,$id);
        (empty($user) && $user['status'] == 1) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'user_error'));//您们不是好友不能删除

        $user_result = $this->user->agree_with($uid,$id);
        $request_id = $user['uid'];//请求id
        $the_requested_id = $user['h_id'];//被请求人id
        $hx_result = $this->hx_common->deleteFriend($request_id,$the_requested_id);
        if ($user_result !== false && !empty($hx_result['entities']))
            $this->json_Response('success',$datas['pack_no']);
        else
            $this->json_Response('failed',$datas['pack_no']);
    }

    /**
     * 好友列表
     * @param $datas
     */
    public function users_list($datas)
    {
        $this->check_user_id($datas);//验证是否登录
        $uid = $datas['user_id'];//用户id

        $user_list = $this->user->the_friends_list($uid);
        $list = null;
        if (count($user_list) !== 0){
            foreach ($user_list as $k=>$value){
                ($value['uid'] == $uid) && $arr[] = $value['h_id'];
                ($value['h_id'] == $uid) && $arr[] = $value['uid'];
            }
            $map['id'] = array('in',implode(',',$arr));
            $list = $this->member->get_more_user($map);
        }

        $this->json_Response('success',$datas['pack_no'],array('list'=>$list));
    }

    /**
     * 好友发商品邮件
     * @param $datas
     */
    public function email($datas)
    {
        $this->check_user_id($datas);//验证是否登录
        $data = $this->get_datas($datas);//获取数据
        $uid = $datas['user_id'];//用户id

        (!$id = $data['id']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'id'));//好友id不能为空
        (!$email_list['title'] = $data['title']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'title'));//标题不能为空
        (!$email_list['content'] = $data['content']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'content'));//内容不能为空

        $res = $this->member->get_info($id);
        (empty($res)) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'user_error'));//用户不存在
        (count($data['item'][0]) > 2) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'item_error'));//不能不发商品
        $arr['integral'] = implode(',',array_column($data['item'],'id'));
        $map = array(
            'user_id' => $uid,
            'item_id' => array('in',$arr['integral']),
        );
        $item = D('UserItem')->get_more_item($map);//查询自己的商品
        (count($data['item']) !== count($item)) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'item_there_error'));//您有商品不存在

        M()->startTrans();//开启事务
        foreach ($data['item'] as $k=>$v){//进行批对
            foreach ($item as $key=>$value) {
                if ($v['id'] == $value['item_id']){
                    ($v['nums'] > $value['inventory']) && $this->print_error_status('params_error', $datas['pack_no'], array('ERROR_Param_Format' => 'insufficient_inventory'));//您有商品库存不足
                    if (D('UserItem')->dec($value['id'],$v['nums']) === false){
                        M()->rollback();//回滚
                        $this->json_Response('failed',$datas['pack_no']);
                    }
                    unset($value[$key]);//使用 unset() 删除数组元素。
                    reset($item);//记得让数组内部指针“归位”
                    break;
                }
            }
        }
        $name = $this->member->get_a_value($uid,'nickname');
        $email_list['uid'] = $id;//收邮件人id
        $email_list['h_id'] = $uid;//发的邮件的人id
        $email_list['name'] = $name;//发邮件人姓名
        $email_list['email_type'] = 2;//1:奖励邮件 2：好友邮件 3：普通邮件
        $email_list['integral'] = implode(',',array_column($data['item'],'id'));//好友邮件商品id‘,’进去分割
        $email_list['silver'] = implode(',',array_column($data['item'],'nums'));//好友邮件 商品数量用‘,’进去分割
        $email_list['addtime'] = time();

        if ($this->email->add_a_email($email_list) !== false){
            $jpushContentTemplate = C('jpushContentTemplate');
            $this->jpush->push_message($jpushContentTemplate['task'][4]['content'],encode($id),$jpushContentTemplate['time_to_live']);
            M()->commit();//提交事务
            $this->json_Response('success',$datas['pack_no']);
        }else{
            M()->rollback();//回滚
            $this->json_Response('failed',$datas['pack_no']);
        }
    }
}