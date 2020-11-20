<?php
namespace Common\Model;
use Think\Model;
class JobModel extends Model {

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('update_time', 'time', 3, 'function'),
    );

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
     * 获取和分类关联的标签ID集合
     */
    public function get_tag_ids($Job_id) {
        $res = M('item_Job_tag')->field('tag_id')->where(array('Job_id'=>$Job_id))->select();
        $ids = array();
        foreach($res as $tag) {
            $ids[] = $tag['tag_id'];
        }
        return $ids;
    }

    /**
     * 根据ID获取分类名称
     */
    public function get_name($id) {
        //分类数据
        if (false === $Job_data = F('Job_data')) {
            $Job_data = $this->Job_data_cache();
        }
        return $Job_data[$id]['name'];
    }

    /**
     * 获取标签分类紧接上级实体分类
     */
    public function get_pentity_id($id) {
        $pentity_id = 0;
        if (false === $Job_data = F('Job_data')) {
            $Job_data = $this->Job_data_cache();
        }
        $spid = array_reverse(explode('|', trim($Job_data[$id]['spid'], '|')));
        foreach ($spid as $val) {
            if ($Job_data[$val]['type'] == 0) {
                $pentity_id = $val;
                break;
            }
        }
        return $pentity_id;
    }

    /**
     * 读取写入缓存(有层级的分类数据)
     */
    public function Job_cache() {
        $Job_list = array();
        $Job_data = $this->field('id,pid,name,fcolor,type')->where('status=1')->order('ordid')->select();
        foreach ($Job_data as $val) {
            if ($val['pid'] == '0') {
                $Job_list['p'][$val['id']] = $val;
            } else {
                $Job_list['s'][$val['pid']][$val['id']] = $val;
            }
        }
        F('Job_list', $Job_list);
        return $Job_list;
    }

    /**
     * 读取写入缓存(无层级分类列表)
     */
    public function Job_data_cache() {
        $Job_data = array();
        //$result = $this->field('id,pid,spid,name,fcolor,type,seo_title,seo_keys,seo_desc')->where('status=1')->order('ordid')->select();
        $result = $this->field('id,pid,spid,name,fcolor,type')->where('status=1')->order('ordid')->select();
        foreach ($result as $val) {
            $Job_data[$val['id']] = $val;
        }
        F('Job_data', $Job_data);
        return $Job_data;
    }

    /**
     * 分类关系读取写入缓存
     */
    public function relate_cache() {
        $Job_relate = array();
        $Job_data = $this->field('id,pid,spid')->where('status=1')->order('ordid')->select();
        foreach ($Job_data as $val) {
            $Job_relate[$val['id']]['sids'] = $this->get_child_ids($val['id']); //子孙
            if ($val['pid'] == '0') {
                $Job_relate[$val['id']]['tid'] = $val['id']; //祖先
            } else {
                $Job_relate[$val['id']]['tid'] = array_shift(explode('|', $val['spid'])); //祖先
            }
        }
        F('Job_relate', $Job_relate);
        return $Job_relate;
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

    /**
     * 更新则删除缓存
     */
    protected function _before_write(&$data) {
        F('Job_data', NULL);
        F('Job_list', NULL);
        F('Job_relate', NULL);
        F('index_Job_list', NUll);
    }

    /**
     * 删除也删除缓存
     */
    protected function _after_delete($data, $options) {
        F('Job_data', NULL);
        F('Job_list', NULL);
        F('Job_relate', NULL);
        F('index_Job_list', NUll);
    }

}