<?php
namespace Common\Model;
use Think\Model;
use Think\Model\RelationModel;
class ResumeModel extends RelationModel {
    protected $_auto = array (
        array('add_time','time',1,'function'),
    );  
	
	//自动验证
	protected $_validate = array(
		 array('resuName','require','姓名不能为空！'), // 当值不为空的时候判断是否在一个范围内
		 array('sex','require','请选择性别！'), // 当值不为空的时候判断是否在一个范围内
		 
		 array('resuEmail','require','邮箱不能为空'), //默认情况下用正则进行验证
		 array('resuContactInfo','require','联系方式不能为空'), //默认情况下用正则进行验证
		
	);
	 protected $_link = array(
      'custom' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'custom',
          'foreign_key' => 'custom_id',
		   'mapping_name'=>  'custom',
		   'as_fields'=>'cutaname'
		  
      ),
      'admin' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'admin',
          'foreign_key' => 'owner_id',
		   'mapping_name'=>  'admin',
		   'as_fields'=>'nickname:username'
		  
      ),
      'progress' => array(
	          'mapping_type' => self::HAS_MANY,
	          'class_name' => 'progress',
	          'foreign_key' => 'resume_id',
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
        $where = "cutaShortName='" . $name . "'  AND ". $pk ."<>'" . $id . "'";
        $result = $this->where($where)->count($pk);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}