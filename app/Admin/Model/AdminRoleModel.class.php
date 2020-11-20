<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class AdminRoleModel extends RelationModel {

    /**
     * 生成spid
     *
     * @param int $pid 父级ID
     */
    public function get_spid($pid) {
        if (!$pid) {
            return 0;
        }
        $pspid = $this->where(array('id'=>$pid))->getField('spid');
        if ($pspid) {
            $spid = $pspid . $pid . '|';
        } else {
            $spid = $pid . '|';
        }
        return $spid;
    }

    /**
     * 获取分类下面的所有子分类的ID集合
     *
     * @param int $id
     * @param bool $with_self
     * @return array $array
     */
    public function get_child_ids($id, $with_self=false) {
        $spid = $this->where(array('id'=>$id))->getField('spid');
        $spid = $spid ? $spid .= $id .'|' : $id .'|';
        $id_arr = $this->field('id')->where(array('spid'=>array('like', $spid.'%')))->select();
        $array = array();
        foreach ($id_arr as $val) {
            $array[] = $val['id'];
        }
        $with_self && $array[] = $id;
        return $array;
    }

    /**
     * 检测分类是否存在
     *
     * @param string $name
     * @param int $pid
     * @param int $id
     * @return bool
     */
    public function name_exists($name, $pid, $id=0) {
        $where = "name='" . $name . "' AND pid='" . $pid . "' AND id<>'" . $id . "'";
        $result = $this->where($where)->count('id');
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    //当前账号下级角色集合
    public function his_subordinates($select=true){
        //当前登录账号下级ID
        $id_all = $this->get_child_ids($_SESSION['admin']['role_id']);
        if($_SESSION['admin']['role_id'] == 1) $id_all[] = $_SESSION['admin']['role_id'];
        if(!$id_all) return false;
        $mode = $this->where(array('id'=>array('in',$id_all)));
        if($select){
            return $mode->order('ordid ASC')->select();
        }else{
            return $mode->getfield('id,name');
        }
    }

    protected $_link = array(
        'role_priv' => array(
            'mapping_type'  => MANY_TO_MANY,
            'class_name'    => 'menu',
            'foreign_key'   => 'role_id',
            'relation_foreign_key'=>'menu_id',
            'relation_table' => 'admin_auth',
			'mapping_name' => 'role_priv',
            'auto_prefix' => true
        )
    );
	
    protected $_validate = array(
        array('name','require','{%role_name_empty}'),
        array('name','','{%role_name_exists}',0,'unique',1),
    );

    public function check_name($name, $id='')
    {
        $where = "name='$name'";
        if ($id) {
            $where .= " AND id<>'$id'";
        }
        $id = $this->where($where)->getField('id');
        if ($id) {
            return false;
        } else {
            return true;
        }
    }
}