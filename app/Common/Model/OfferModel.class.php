<?php
namespace Common\Model;
use Think\Model\RelationModel;
class OfferModel extends RelationModel
{
    protected $_auto = array (
        array('add_time','time',1,'function'),
    );  
	
	//自动验证
	protected $_validate = array(
		 array('money','require','年薪或服务费不能为空'), //默认情况下用正则进行验证
		 array('accessoryFile','require','请上传附件'), //默认情况下用正则进行验证
		 array('sendDate','require','请选择下达日期'), //默认情况下用正则进行验证
		 array('guaranteeEndDate','require','请选择保证期'), //默认情况下用正则进行验证
		 array('contract_id','require','请选择合同'), //默认情况下用正则进行验证
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
      
      'project' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'project',
          'foreign_key' => 'project_id',
		  'mapping_name'=>  'project',
		  'as_fields'=>  'prtaname,custom_id:cus_id',
		  
      ),
      
      'resume' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'resume',
          'foreign_key' => 'resume_id',
		  'mapping_name'=>  'resume',
		  'as_fields'=>  'resuname',
		  
      ),
      
      'contract' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'contract',
          'foreign_key' => 'contract_id',
		  'mapping_name'=>  'contract',
		  'as_fields'=> 'cotaonepoint,cotatwopoint,cotacommissionpoint',
		  
      ),
       'progress' => array(
	          'mapping_type' => self::HAS_MANY,
	          'class_name' => 'progress',
	          'foreign_key' => 'offer_id',
			  'mapping_name'=>  'progress',
			  'mapping_fields'=> 'content,add_time,creator_id',
			  'mapping_order' => 'add_time desc',
			  'mapping_limit'=>1
	      ),
    );
	
}