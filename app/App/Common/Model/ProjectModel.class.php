<?php
namespace Common\Model;
use Think\Model;
use Think\Model\RelationModel;
class ProjectModel extends RelationModel {
    protected $_auto = array (
        array('add_time','time',1,'function'),
    );  
	
	//自动验证
	protected $_validate = array(
		 array('prtaName','require','项目名称不能为空'), //默认情况下用正则进行验证
		 array('prtaFunnel',array('0'),'请设置项目漏斗！',2,'notin'), // 当值不为空的时候判断是否在一个范围内
		 array('custom_id',array('0'),'请选择客户！',2,'notin'), // 当值不为空的时候判断是否在一个范围内
		 array('prtaContactIds',array('0'),'请选择联系人！',2,'notin'), // 当值不为空的时候判断是否在一个范围内
		 array('prtaWorkCity','require','工作地区不能为空'), //默认情况下用正则进行验证
		 array('prtaType',array('0'),'请选择职位类别！',2,'notin'), // 当值不为空的时候判断是否在一个范围内
		 array('prtaLevel',array('0'),'请设置职位级别！',2,'notin'), // 当值不为空的时候判断是否在一个范围内
		 
		 array('prtaPositionNum','require','招聘人数不能为空'), //默认情况下用正则进行验证
		 array('prtaCode','require','项目编号不能为空'), //默认情况下用正则进行验证
		 array('prtaRemuneration','require','薪资福利不能为空'), //默认情况下用正则进行验证
		
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
		   'as_fields'=>'username'
		  
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