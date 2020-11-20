<?php
namespace Common\Model;
use Think\Model\RelationModel;
class MapModel extends RelationModel
{
    //自动完成
    protected $_auto = array(
        array('add_time', 'time', 1, 'function'),
		array('last_time', 'time', 3, 'function'),
    );
    //自动验证
    protected $_validate = array(
        array('title', 'require', '{%article_title_empty}'),
    );
    //关联关系
    protected $_link = array(
        'map_cate' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'article_cate',
            'foreign_key' => 'cate_id',
        ),

    );
    public function addtime()
    {
        return date("Y-m-d H:i:s",time());
    }
}