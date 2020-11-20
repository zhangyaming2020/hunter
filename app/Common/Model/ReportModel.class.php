<?php
namespace Common\Model;
use Think\Model;
use Think\Model\RelationModel;
class ReportModel extends RelationModel {
    protected $_auto = array (
        array('add_time','time',1,'function'),
    );  
	
	//自动验证
	protected $_validate = array(
		 array('resume_id','require','请选择候选人'), //默认情况下用正则进行验证
		 array('rereCurrentCity','require','请填写目前所在地'), // 当值不为空的时候判断是否在一个范围内
		 array('rereExpectMoney','require','请填写期望薪金！'), // 当值不为空的时候判断是否在一个范围内
		
		 array('rereExpectMoney','require','请上传附件'), //默认情况下用正则进行验证
	
		
	);
	
	protected $_link = array(
      'admin' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'admin',
          'foreign_key' => 'recommend_id',
		   'mapping_name'=>  'admin',
		   'as_fields'=>'nickname:recommend_name'
		  
      ),
      
      'project' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'project',
          'foreign_key' => 'project_id',
		   'mapping_name'=>  'project',
		   'as_fields'=>'prtaname,owner_id,custom_id:report_custom_id'
		  
      ),
      
      'resume' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'resume',
          'foreign_key' => 'resume_id',
		   'mapping_name'=>  'resume',
		   'as_fields'=>'resuname'
		  
      ),
      
      'candidate' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'candidate',
          'foreign_key' => 'candidate_id',
		   'mapping_name'=>  'candidate',
		   'as_fields'=>'resume_id:res_id,project_id:pro_id'
		  
      ),
      'progress' => array(
	          'mapping_type' => self::HAS_MANY,
	          'class_name' => 'progress',
	          'foreign_key' => 'report_id',
			  'mapping_name'=>  'progress',
			  'mapping_fields'=> 'content,add_time,creator_id',
			  'mapping_order' => 'add_time desc',
			  'mapping_limit'=>1
	      ),
	   'interview' => array(
	          'mapping_type' => self::HAS_MANY,
	          'class_name' => 'interview',
	          'foreign_key' => 'report_id',
			  'mapping_name'=>  'interview',
			  'mapping_order' => 'add_time desc',
	      ),
    );
	 
	
}