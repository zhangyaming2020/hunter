<?php
namespace Home\Model;
use Think\Model;

class MemberModel extends Model {
	 protected $_validate = array(      
	  array('mobile','require','手机号码不能为空！'),  
	  array('mobile','/^1[3|4|5|7|8][0-9]{9}$/','手机格式不正确！',0,'regex'),       
	  array('mobile','','手机号码已经存在！',0,'unique',1),// 在新增的时候验证name字段是否唯一
      array('verify','require','验证码不能为空！'), //默认情况下用正则进行验证
      array('qypassword','password','两次密码不相等',0,'confirm'), // 验证确认密码是否和密码一致
      array('password','require','密码不能为空'), // 自定义函数验证密码格式
      array('qypassword','require','确认密码不能为空'),
   );	
}
	        