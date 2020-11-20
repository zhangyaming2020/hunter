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
		  'as_fields'=>  'resuname,id:resume_id',
		  
      ),
    );
}
