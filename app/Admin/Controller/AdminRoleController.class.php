<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class AdminRoleController extends AdminCoreController {
	public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('AdminRole');
        $this->set_mod('AdminRole');
    }
	
	/*public function index(){
	 	$admin = session('admin');
		if($admin['role_id']==1){
			$member =	$this->_mod->relation(true) ->select();
		}else{
			$member =	$this->_mod ->where('id!=1')->relation(true) ->select();
		}

		$this->assign('list',$member);
		$this->assign('list_table', true);		
		$this ->display();
    }*/

    public function index() {
        $tree = new Tree();
        $tree->icon = array('│ ','├─ ','└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = $this->_mod->select();
//        dump($result);exit;   $result = $this->_mod->his_subordinates();
        empty($result) && $this->error('您没有下级角色，无权访问');
        $array = array();
        foreach($result as $r) {
            $r['str_status'] = '<img data-tdtype="toggle" data-id="'.$r['id'].'" data-field="status" data-value="'.$r['status'].'" src="'.C('TMPL_PARSE_STRING.__STATIC__').'/images/toggle_' . ($r['status'] == 0 ? 'disabled' : 'enabled') . '.gif" />';
            $r['str_manage'] = '<a href="'.U('admin_role/auth', array('id'=>$r['id'],'menuid'=>65)).'">'.L('role_auth').'</a> | <a href="javascript:;" class="J_showdialog" data-uri="'.U('admin_role/add',array('pid'=>$r['id'])).'" data-title="添加角色" data-id="add" data-width="600" data-height="60">添加下级角色</a> |
                                <a href="javascript:;" class="J_showdialog" data-uri="'.U('admin_role/edit',array('id'=>$r['id'])).'" data-title="'.L('edit').' - '. $r['name'] .'" data-id="edit" data-width="600" data-height="60">'.L('edit').'</a> |
                                <a href="javascript:;" data-acttype="ajax" class="J_confirmurl" data-uri="'.U('admin_role/delete',array('id'=>$r['id'])).'" data-msg="'.sprintf(L('confirm_delete_one'),$r['name']).'">'.L('delete').'</a>';
            $array[] = $r;
        }
        $str  = "<tr >
                <td align='center'><input type='checkbox' value='\$id' class='J_checkitem'></td>
                <td align='center'>\$id</td>
                <td>\$spacer<span data-tdtype='edit' data-field='name' data-id='\$id' class='tdedit'>\$name</span></td>
                <td align='center'>\$remark</td>
                <td align='center'><span data-tdtype='edit' data-field='ordid' data-id='\$id' class='tdedit'>\$ordid</span></td>

                <td align='center'>\$str_status</td>
                <td align='center'>\$str_manage</td>
                </tr>";

        $tree->init($array);
        $list = $tree->get_tree($result[0][pid], $str);

        $big_menu = array(
            'title' => '添加角色',
            'iframe' => U('admin_role/add'),
            'id' => 'add',
            'width' => '520',
            'height' => '190',
        );

        $this->assign('big_menu', $big_menu);
        $this->assign('list', $list);
        $this->assign('list_table', true);
        $this->display();
    }


    /**
     * 添加子菜单上级默认选中本栏目
     */
    public function _before_add() {
        $pid = I('pid',$_SESSION['admin']['role_id'] == 1 ? 0 : $_SESSION['admin']['role_id'],'intval');
        if ($pid) {
            $spid = $this->_mod->where(array('id'=>$pid))->getField('spid');
            $spid = $spid ? $spid.$pid : $pid;
            $this->assign('spid', $spid);
        }
    }

    /**
     * 入库数据整理
     */
    protected function _before_insert($data = '') {
        //检测分类是否存在
        if($this->_mod->name_exists($data['name'], $data['pid'])){
            $this->ajax_return(0, L('article_cate_already_exists'));
        }
        //生成spid
        $data['spid'] = $this->_mod->get_spid($data['pid']);
        return $data;
    }

    /**
     * 修改提交对数据
     */
    protected function _before_update($data = '') {
        if ($this->_mod->name_exists($data['name'], $data['pid'], $data['id'])) {
            $this->ajax_return(0, L('article_cate_already_exists'));
        }
        $old_pid = $this->_mod->field('pid')->where(array('id'=>$data['id']))->find();
        if ($data['pid'] != $old_pid['pid']) {
            //不能把自己放到自己或者自己的子目录们下面
            $wp_spid_arr = $this->_mod->get_child_ids($data['id'], true);
            if (in_array($data['pid'], $wp_spid_arr)) {
                $this->ajax_return(0, L('cannot_move_to_child'));
            }
            //重新生成spid
            $data['spid'] = $this->_mod->get_spid($data['pid']);
        }
        return $data;
    }

    public function auth()
    {
        $menu_mod = D('Menu');
        $auth_mod = D('Admin_auth');
        if (IS_POST) {
            $id = intval($_POST['id']);
            //清空权限
            $auth_mod->where(array('role_id'=>$id))->delete();
            if (is_array($_POST['menu_id']) && count($_POST['menu_id']) > 0) {
                foreach ($_POST['menu_id'] as $menu_id) {
                    $auth_mod->add(array(
                        'role_id' => $id,
                        'menu_id' => $menu_id
                    ));
                }
            }
            $this->success(L('operation_success'));
        } else {

            $id = I('id','', 'intval');
            $tree = new Tree();
            $tree->icon = array('│ ','├─ ','└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $result = $menu_mod->his_Jurisdiction();
            empty($result) && $this->error('您没有相应权限，不能分配给下级');
            //获取被操作角色权限
            $role_data = $this->_mod->relation('role_priv')->find($id);
			$admin_auth = D('Admin_auth');
			$role_priv = $admin_auth->where(array('role_id'=>$id))->select();
            $priv_ids = array();
            foreach ($role_priv as $val) {
                $priv_ids[] = $val['menu_id'];
            }
            foreach($result as $k=>$v) {
                $result[$k]['level'] = $menu_mod->get_level($v['id'],$result);
                $result[$k]['checked'] = (in_array($v['id'], $priv_ids))? ' checked' : '';
                $result[$k]['parentid_node'] = ($v['pid'])? ' class="child-of-node-'.$v['pid'].'"' : '';
            }
            $str  = "<tr id='node-\$id' \$parentid_node>" .
                        "<td style='padding-left:10px;'>\$spacer<input type='checkbox' name='menu_id[]' value='\$id' class='J_checkitem' level='\$level' \$checked> \$name</td>
                    </tr>";
            $tree->init($result);
            $menu_list = $tree->get_tree(0, $str);
            $this->assign('list', $menu_list);
            $this->assign('role', $role_data);
            $this->display();
        }
    }

    public function ajax_getchilds() {
        $id = I('id','', 'intval');
        //当前登录账号下级ID
        $id_all = $this->_mod->get_child_ids($_SESSION['admin']['role_id'],true);
        $return = $this->_mod->field('id,name')->where(array('pid'=>$id))->select();
        if ($return) {
            if(in_array($id,$id_all)){
                echo json_encode(array(
                    'status'=>1,
                    'msg'=>L('operation_success'),
                    'data'=>$return,
                    'type'=>1
                ));
            }else{
                echo json_encode(array(
                    'status'=>1,
                    'msg'=>L('operation_success'),
                    'data'=>$return,
                    'type'=>2
                ));
            }
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }

    }



}