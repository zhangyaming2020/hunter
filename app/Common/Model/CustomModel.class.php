<?php
namespace Common\Model;
use Think\Model\RelationModel;
class CustomModel extends RelationModel
{
    protected $_auto = array (
        array('add_time','time',1,'function'),
    );  
	
	//自动验证
	protected $_validate = array(
		 array('cutaName','require','公司名称不能为空'), //默认情况下用正则进行验证
		 array('pid',array('0'),'请选择地区！',2,'notin'), // 当值不为空的时候判断是否在一个范围内
		 array('cucoFunction','require','称谓不能为空'), //默认情况下用正则进行验证
		 array('cucoSex',array('1','2'),'请选择性别！',2,'in'), // 当值不为空的时候判断是否在一个范围内
		 array('cutaFunnel','require','请选择开发漏斗'), //默认情况下用正则进行验证
		 array('cutaImporta','require','请选择重要程度'), //默认情况下用正则进行验证
		 array('cutaIndustr','require','请选择所属行业'), //默认情况下用正则进行验证
		 array('cutaType','require','请选择客户类型'), //默认情况下用正则进行验证
		 array('cutaTelephone','require','总机电话不能为空'), //默认情况下用正则进行验证
		  //默认情况下用正则进行验证
		 //默认情况下用正则进行验证
		  
		 /* 验证邮箱 */
		 //邮箱格式不正确  
		
	);
	protected $_link = array(
	      
	      'admin' => array(
	          'mapping_type' => self::BELONGS_TO,
	          'class_name' => 'admin',
	          'foreign_key' => 'creator_id',
			  'mapping_name'=>  'admin',
			  'as_fields'=>  'nickname:username',
			  
	      ),
	      
	      'place' => array(
	          'mapping_type' => self::BELONGS_TO,
	          'class_name' => 'place',
	          'foreign_key' => 'cutaprovince',
			  'mapping_name'=>  'place',
			  'as_fields'=>  'name:proname',
			  
	      ),
	      
	      'contract' => array(
	          'mapping_type' => self::HAS_MANY,
	          'class_name' => 'contract',
	          'foreign_key' => 'custom_id',
			  'mapping_name'=>  'contract',
			  'mapping_fields'=> 'validity,status,id',
			  'mapping_order' => 'review_time asc',
	      ),
	      'progress' => array(
	          'mapping_type' => self::HAS_MANY,
	          'class_name' => 'progress',
	          'foreign_key' => 'custom_id',
			  'mapping_name'=>  'progress',
			  'mapping_fields'=> 'content,add_time,creator_id',
			  'mapping_order' => 'add_time desc',
			  'mapping_limit'=>1
	      ),
	      
	    );
	/**
     * 检测标签是否存在
     *
     * @param string $name
     * @param int $pid
     * @return bool
     */
   public function name_exists($name, $id=0)
    {
        $pk = $this->getPk();
        $where = "cutaName='" . $name . "'  AND ". $pk ."<>'" . $id . "'";
        $result = $this->where($where)->count($pk);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}