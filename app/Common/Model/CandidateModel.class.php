<?php
namespace Common\Model;
use Think\Model\RelationModel;
class CandidateModel extends RelationModel
{
	protected $_link = array(
      'resume' => array(
          'mapping_type' => self::BELONGS_TO,
          'class_name' => 'resume',
          'foreign_key' => 'resume_id',
		  'mapping_name'=>  'resume',
		  'as_fields'=>  'owner_id,sex,resuname,id:resume_id,educations',
		  
      ),
      'report' => array(
	          'mapping_type' => self::HAS_MANY,
	          'class_name' => 'report',
	          'foreign_key' => 'project_id',
			  'mapping_name'=>  'report',
			  'mapping_fields'=> 'id',
	      ),
	   'admin' => array(
	          'mapping_type' => self::BELONGS_TO,
	          'class_name' => 'admin',
	          'foreign_key' => 'operate_id',
			  'mapping_name'=>  'admin',
			  'as_fields'=> 'nickname:username',
	      ),
    );
}
