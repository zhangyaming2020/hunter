<?php
namespace Common\Model;
use Think\Model;
class ProgressModel extends Model {

    protected $_auto = array(
        array('add_time', 'time', 1, 'function'),
        array('creator_id', 'admin_info', 1, 'function'),
    );

	protected function admin_info(){
		return $_SESSION['admin']['id'];
	}
}