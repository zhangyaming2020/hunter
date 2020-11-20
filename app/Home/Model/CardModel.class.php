<?php 
namespace Home\Model;

use Think\Model;

class CardModel extends Model{

	

	 protected $_validate = array(

     array('cardname','require','持卡人不能为空！'), 
     array('cardname','/^[\x{4e00}-\x{9fa5}]{2,4}$/u
','持卡人姓名格式不正确！',0,'regex'),
     array('cardid','require','卡号不能为空！'), 
     array('cardid','/^\d{16}|\d{19}$/','卡号格式不正确！',0,'regex'),  
     array('bankname','require','开户银行不能为空！'), 
     array('cardid','','卡号已经存在！',0,'unique',1),
   
    
     

   

   );

	

	

	

	

	

	

}

?>