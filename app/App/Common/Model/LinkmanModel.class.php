<?php
namespace Common\Model;
use Think\Model;
class LinkmanModel extends Model
{
    protected $_auto = array (
        array('add_time','time',1,'function'),
    );
	//自动验证
	protected $_validate = array(
		 array('cucoSex',array('1','2'),'请选择性别！',2,'in'), //默认情况下用正则进行验证
		 array('mobile','require','手机号码不能为空'), //默认情况下用正则进行验证
		 array('cucoEmail','require','邮箱不能为空'), //默认情况下用正则进行验证
		  
		 /* 验证邮箱 */
		array('cucoEmail', 'email', '邮箱格式不正确', self::EXISTS_VALIDATE), //邮箱格式不正确
		
	);
		 //关联关系
    protected $_link = array(
       // 'apply' => array(
//            'mapping_type'      =>  self::BELONGS_TO,
//            'class_name'        =>  'apply',
//            'mapping_name'      =>  'apply',
//            'foreign_key'       =>  'type_id',
//			'as_fields'			=>'typename',
//        ),
    );
	  
}