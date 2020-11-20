<?php
namespace Common\Model;
use Think\Model\RelationModel;
class ItemCommentModel extends RelationModel {
    protected $_auto = array (array('add_time','time',1,'function'));

    /**
     * 新增评论更新商品评论数和评论缓存字段
     */
    protected function _after_insert($data,$options) {
        $item_mod = D('Item');
        $item_mod->where(array('id'=>$data['item_id']))->setInc('comments');
        $item_mod->update_comments($data['item_id'], array(
            'id' => $data['id'],
            'uid' => $data['uid'],
            'uname' => $data['uname'],
            'info' => $data['info']
        ));
    }
	
	
   protected $_link = array(
    	'img'=>array(
        'mapping_type'      => self::HAS_MANY,
        'class_name'        => 'ItemCommentImg',
        'foreign_key'   => 'item_comment_id'
        ),
		
		'store'=>array(
        'mapping_type'      => self::BELONGS_TO,
        'class_name'        => 'store',
        'foreign_key'   => 'store_id',
		'mapping_fields'   => 'title',
		'as_fields'   => 'title:store_name',
        ),
		
		'item'=>array(
        'mapping_type'      => self::BELONGS_TO,
        'class_name'        => 'item',
        'foreign_key'   => 'item_id',
		'mapping_fields'   => 'title',
		'as_fields'   => 'title:item_name',
        ),
		
		'member'=>array(
        'mapping_type'      => self::BELONGS_TO,
        'class_name'        => 'member',
        'foreign_key'   => 'member_id',
		'as_fields'   => 'nickname,mobile:user_mobile',
        ),
    );
	
	
	
}