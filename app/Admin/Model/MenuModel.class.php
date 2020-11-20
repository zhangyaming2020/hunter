<?php
namespace Admin\Model;
use Think\Model;
class MenuModel extends Model {
    
    protected $_validate = array(
        array('name', 'require', '{%menu_name_require}'), //菜单名称为必须
        array('name', 'require', '{%module_name_require}'), //模块名称必须
        array('name', 'require', '{%action_name_require}'), //方法名称必须
    );

    public function admin_menu($pid, $with_self=false) {
        $pid = intval($pid);
        $condition = array('pid' => $pid);
        if ($with_self) {
            $condition['id'] = $pid;
            $condition['_logic'] = 'OR';
        }
        $map['_complex'] = $condition;
        $map['display'] = 1;
        //根据权限调用栏目
        if($_SESSION['admin']['role_id'] != 1){
            //查看管理员的权限范围
            $fw = M('AdminAuth')->where(array('role_id'=>$_SESSION['admin']['role_id']))
                ->getfield('menu_id',true);
            if($fw){
                $map['id'] = array('in',$fw);
            }else{
                $map['id'] = 100000000000000;
            }
        }
        $menus = M("menu")->where($map)->order('ordid,dialog desc')->select();
		//dump(M("menu")->getLastSql());
        return $menus;
    }
    
    public function sub_menu($pid = '', $big_menu = false) {
        $array = $this->admin_menu($pid, false);
        /*$numbers = count($array);
        if ($numbers==1 && !$big_menu) {
            return '';
        }*/
        return $array;
    }
    
    public function get_level($id,$array=array(),$i=0) {
        foreach($array as $n=>$value){
            if ($value['id'] == $id) {
                if($value['pid']== '0') return $i;
                $i++;
                return $this->get_level($value['pid'],$array,$i);
            }
        }
    }

    //获取当前角色拥有权限
    public function his_Jurisdiction(){
        if($_SESSION['admin']['role_id'] == 1){
            $where = "";
        }else{
            $auth = M('AdminAuth')->where(array('role_id'=>$_SESSION['admin']['role_id']))->getfield('menu_id',true);
            if(!$auth) return false;
            $where = array('id'=>array('in',$auth));
        }
        return $this->where($where)->order('ordid')->select();
    }



}