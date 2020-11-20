<?php
namespace Admin\Controller;
use Admin\Org\Tree;
class DicController extends AdminCoreController {
	public function _initialize() {
		$this->_mod = D('Dic');
        $this->set_mod('Dic');
    }
	 public function index() {
	 	$dic=array(
    		'0'=>array('name'=>'人才意向','remark'=>'设置人才意向过滤。','pid'=>2),
    		'1'=>array('name'=>'职能分类','remark'=>'对职能类别进行分类','pid'=>2),
    		'2'=>array('name'=>'行业分类','remark'=>'对行业类别进行分类','pid'=>2),
    	);
    	//dump(M('dic')->addAll($dic));
	 	$dic=$this->_mod->where(array('pid'=>0))->field('id,name,remark')->select();
        foreach($dic as $k=>$v){
        	$dic[$k]['child']=$this->_mod->where(array('pid'=>$v['id']))->field('id,name,remark')->select();
        }
        //dump($dic);
        $this->assign('dic',$dic);
        $this->display();
    }
     public function detail(){
        $id=I('id');
        $remark=I('remark');
        $this->assign('pid',$id);
        $width=500;
        if(in_array($id,array('4','102','103'))){
        	$sort = I("sort", 'id', 'trim');
	        $order = I("order", 'ASC', 'trim');
	        $tree = new Tree();
	        $tree->icon = array('│ ','├─ ','└─ ');
	        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        	if($id==4){
		    	$url=U('place/add',array('dic_id'=>$id,'remark'=>$remark));
		    	if(F('place_list')){
		            $result = F('place_list');
		        }else{
		            $result =D('Place')->order($sort . ' ' . $order)->select();
		            F('place_list',$result);
		        }
		        $mod_name='place';
	        	$ajax_url=U('place/ajax_edit');
		        }
	        else if($id==102){
	        	$url=U('job/add',array('dic_id'=>$id,'remark'=>$remark));
		    	if(F('job_list')){
		            $result = F('job_list');
		        }else{
		            $result =D('Job')->order($sort . ' ' . $order)->select();
		            F('Job_list',$result);
		        }
		        $mod_name='job';
		        $ajax_url=U('job/ajax_edit');
		       
	        }
	        else if($id==103){
	        	$url=U('industries/add',array('dic_id'=>$id,'remark'=>$remark));
		    	if(F('industries_list')){
		            $result = F('industries_list');
		        }else{
		            $result =D('Industries')->order($sort . ' ' . $order)->select();
		            F('industries_list',$result);
		        }
		        $mod_name='industries';
		        $ajax_url=U('industries/ajax_edit');
		        }
	        $array = array();
	        //dump($result);exit;
	        foreach($result as $r) {
	        	$r['str_manage'] = '<button type="button" data-acttype="ajax" data-uri="'.U($mod_name.'/delete',array('id'=>$r['id'])).'"   data-dialog="'.u('dic/detail', array('remark'=>$remark,'id'=>$id)).'" data-id="detail" data-msg="'.sprintf(L('confirm_delete_one'),$r['name']).'" class="J_confirmurl remove" style="color:#F76741" title="'.L('delete').'"></button>';
				
	            $r['parentid_node'] = ($r['pid'])? ' class="child-of-node-'.$r['pid'].'"' : '';
	            $array[] = $r;
	        }
	        $str  = "<tr node-nums='\$id' id='node-\$id' \$parentid_node class='tr_body'>
	                
	                <td align='left' width='0'></td>
	                <td align='left' width='0'></td>
	                <td>\$spacer
	                <span data-tdtype='edit' data-field='name' data-id='\$id' class=''  style='color:\$fcolor'>\$name
	            	</span>
	            	<span class='act' style='display:none;'>
	            	<button type='button' data-width='80' class='edit' data-field='name' data-id='' data-content='' title='编辑' onfocus='this.blur();'></button>
	            	\$str_manage
	               </td>
	                </tr>";
	        $tree->init($array);
	        $list = $tree->get_tree(0, $str);
	        $list='<div class="" data-acturi="'.$ajax_url.'"><table width="100%" cellspacing="0" id="J_cate_tree"><tbody>'.$list.'</tbody></table></div>';	
	    }   
        else{
        	
        	$list=$this->_mod->where(array('pid'=>$id))->field('id,name,remark')->select();
        	$url=U('dic/add',array('pid'=>$id));
        	$width=272;
        }
        //dump($url);
        $this->assign('list',$list);
        $this->assign('url',$url);
        $this->assign('ajax_url',$ajax_url);
        $this->assign('width',$width);
        $this->assign('remark',$remark);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
        } else {
            $this->display();
        }
    }
    public function _before_add(){
        $pid=I('pid');
        $remark=$this->_mod->where(array('id'=>$pid))->getField('remark');
        $this->assign('pid',$pid);
        $this->assign('remark',$remark);
    }
}