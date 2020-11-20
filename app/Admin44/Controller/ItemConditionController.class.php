<?php

namespace Admin\Controller;
use Admin\Org\Tree;
class ItemConditionController extends AdminCoreController {

    public function _initialize() {

        parent::_initialize();

        $this->_mod = D('ItemCondition');

        $this->_cate_mod = D('ItemCate');

        $this->set_mod('ItemCondition');

    }
	public function _before_edit(){
		$cate=M('item_cate')->where(array('status'=>1,'pid'=>'143'))->field('id,name')->select();
		$this->assign('cate',$cate);
	}
	public function _before_add(){
		$cate=M('item_cate')->where(array('status'=>1,'pid'=>'143'))->field('id,name')->select();
		$this->assign('cate',$cate);
	}
    public function index() {
        $sort = I("sort", 'ordid', 'trim');
        $order = I("order", 'ASC', 'trim');
        $tree = new Tree();
        $tree->icon = array('│ ','├─ ','└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = $this->_mod->order($sort . ' ' . $order)->select();//dump($result);
        $array = array();
        foreach($result as $r) {
            //$r['str_img'] = $r['img'] ? '<span class="img_border"><img src="'.attach($r['img'], 'item_cate').'" style="width:26px; height:26px;" class="check-image" data-bimg="'.attach($r['img'], 'item_cate').'" /></span>' : '';
            $r['str_status'] = '<img data-tdtype="toggle" data-id="'.$r['id'].'" data-field="status" data-value="'.$r['status'].'" src="'.C('TMPL_PARSE_STRING.__STATIC__').'/images/toggle_' . ($r['status'] == 0 ? 'disabled' : 'enabled') . '.gif" />';
            					//'<a href="javascript:;" class="J_showdialog" data-uri="'.U('tickets_type/add',array('pid'=>$r['id'])).'" data-title="'.L('add_item_cate').'" data-id="add" data-width="520" data-height="360">'.L('add_item_subcate').'</a> |

            $r['str_manage'] = '<a href="javascript:;" class="J_showdialog" data-uri="'.U('item_condition/edit',array('id'=>$r['id'])).'" data-title="'.L('edit').' - '. $r['name'] .'" data-id="edit" data-width="500" data-height="20">'.L('edit').'</a> |
                                <a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="'.U('item_condition/delete',array('id'=>$r['id'])).'" data-msg="'.sprintf(L('confirm_delete_one'),$r['name']).'">'.L('delete').'</a>';
            $r['parentid_node'] = ($r['pid'])? ' class="child-of-node-'.$r['pid'].'"' : '';
            $array[] = $r;
        }
        $str  = "<tr id='node-\$id' \$parentid_node>
                <td align='center'><input type='checkbox' value='\$id' class='J_checkitem'></td>
                <td align='center'>\$id</td>
				<td align='center' class='name' data-id='\$cate_id'>\$cate_id</td>
                <td align='center'>\$spacer<span data-tdtype='edit' data-field='min_value' data-id='\$id' class='tdedit'  style='color:\$fcolor'>\$min_value</span></td>
				<td align='center'>\$spacer<span data-tdtype='edit' data-field='max_value' data-id='\$id' class='tdedit'  style='color:\$fcolor'>\$max_value</span></td>
                <td align='center'><span data-tdtype='edit' data-field='ordid' data-id='\$id' class='tdedit'>\$ordid</span></td>
                <td align='center'>\$str_status</td>
                <td align='center'>\$str_manage</td>
                </tr>";
        $tree->init($array);
        $list = $tree->get_tree(0, $str);
        $cate=M('item_cate')->where(array('status'=>1,'pid'=>'143'))->field('id,name')->select();
		$this->assign('cate',json_encode($cate));
        $this->assign('list', $list);
        $this->assign('list_table', true);
        $this->display();
    }
}


