<?php
namespace Common\Model;
use Think\Model\RelationModel;
class ContractModel extends RelationModel
{
    protected $_auto = array (
        array('add_time','time',1,'function'),
    );  
	
	//自动验证
	protected $_validate = array(
		 array('cotaName','require','合同名称不能为空'), //默认情况下用正则进行验证
		 array('pushUserName','require','存放入不能为空'), //默认情况下用正则进行验证
		  //默认情况下用正则进行验证
		 //默认情况下用正则进行验证
		  
		 /* 验证邮箱 */
		 //邮箱格式不正确  
		
	);
	
	protected $_link = array(
      'custom' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'custom',
          'foreign_key' => 'custom_id',
		  'mapping_name'=>  'custom',
		  'as_fields'=>  'cutaname',
		  
      ),
      
      'admin' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'admin',
          'foreign_key' => 'creator_id',
		  'mapping_name'=>  'admin',
		  'as_fields'=>  'nickname:username',
		  
      ),
      'contract_attach' => array(
	          'mapping_type' => self::HAS_MANY,
	          'class_name' => 'contract_attach',
	          'foreign_key' => 'contract_id',
			  'mapping_name'=>  'contract_attach',
			  'mapping_order' => 'add_time desc',
	      ),
    );
	
}