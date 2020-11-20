<?php
namespace Admin\Controller;
use Think\Page;
class AdminCoreController extends StoneController {
    protected $_name = '';
    protected $menuid = 0;
    public function _initialize() {
        parent::_initialize();
        $this->check_priv();
        $this->menuid = I('menuid', 0,'trim');
        if ($this->menuid) {
            $sub_menu = D('menu')->sub_menu($this->menuid, $this->big_menu);

            $selected = '';
            foreach ($sub_menu as $key=>$val) {
                $sub_menu[$key]['class'] = '';
                if (CONTROLLER_NAME  == $val['controller_name'] && ACTION_NAME == $val['action_name'] && strpos(__SELF__, $val['data'])) {
                    $sub_menu[$key]['class'] = $selected = 'on';
                }
            }
            if (empty($selected)) {
                foreach ($sub_menu as $key=>$val) {
                    if (CONTROLLER_NAME  == $val['controller_name'] && ACTION_NAME == $val['action_name']) {
                        $sub_menu[$key]['class'] = 'on';
                        break;
                    }
                }
            }
            $this->assign('sub_menu', $sub_menu);
        }
        $this->assign('menuid', $this->menuid);
    }

    public function set_mod($mod){
    	
        $this->_name = $mod;
    }
    /**
     * 列表页面
     */
    public function index() {
        $map = $this->_search();
        $mod = D($this->_name);
        !empty($mod) && $this->_list($mod, $map,'','','',$this->page_size);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'index');
        } else {
            $this->display();
        }
    }


    public function ajax_index(){
    	$this->index();
    }
    /**
     * 添加
     */
    public function add() {
        //echo 1;exit;
        $mod = D($this->_name);
        if (IS_POST) {
            if (false === $data = $mod->create()) {
            	
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());
            }
		    
            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }  
			
			//dump($data);exit;
            if( $mod->add($data) ){
                if( method_exists($this, '_after_insert')){
                    $id = $mod->getLastInsID();
                    $this->_after_insert($id,$data);
                }
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
				
            }
        } else {
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'add');
            } else {
                $this->display();
            }
        }
    }

    /**
     * 修改
     */
    public function edit()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST) {
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajax_return(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_update')) {
                $data = $this->_before_update($data);
            }
            if (false !== $mod->save($data)) {
            //dump($_POST);exit;
                if( method_exists($this, '_after_update')){
                    $id = $data['id'];
                    $this->_after_update($id,$_POST);
                }
                IS_AJAX && $this->ajax_return(1, L('operation_success'), '', 'edit');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            $id = I($pk, 'intval');
            $this->_relation && $mod->relation(true);
            $info = $mod->find($id);//dump($info);exit;
         	if(method_exists($this, '_after_edit')){
                $info=$this->_after_edit($info);
          }
            $this->assign('info', $info);
            $this->assign('open_validator', true);
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
    }
    
    /**
     * 导入
     */
    public function import()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        if (IS_POST) {
        } else {
            if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1, '', $response);
            } else {
                $this->display();
            }
        }
    }
    
    
    //导入
	public function upload() {
        header("Content-Type:text/html;charset=utf-8");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类
        $upload->savePath  =      '/'; // 设置附件上传目录
        // 上传文件
        
         	dump($_POST);exit;
        $info   =   $upload->uploadOne($_FILES['excelData']);
        $filename = './uploads'.$info['savepath'].$info['savename'];
        $exts = $info['ext'];
        if(!$info) {// 上传错误提示错误信息
              $this->error($upload->getError());
        }else{// 上传成功
                  $this->goods_import($filename, $exts);
        }
    }
    
    
    //导入数据方法
    protected function goods_import($filename, $exts='xls')
    {
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        if($exts == 'xls'){
            import("Org.Util.PHPExcel.Reader.Excel5");
            $PHPReader=new \PHPExcel_Reader_Excel5();
        }else if($exts == 'xlsx'){
            import("Org.Util.PHPExcel.Reader.Excel2007");
            $PHPReader=new \PHPExcel_Reader_Excel2007();
        }


        //载入文件
        $PHPExcel=$PHPReader->load(strtolower($filename));
		//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet=$PHPExcel->getSheet(0);
        //获取总列数
        $allColumn=$currentSheet->getHighestColumn();
        //获取总行数
        $allRow=$currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for($currentRow=2;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                //读取到的数据，保存到数组$arr中
                $data[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
            	if($data[$currentRow][$currentColumn] instanceof PHPExcel_RichText){ 
				        //富文本转换字符串 这里非常的重要 www.bcty365.com 
				          $data[$currentRow][$currentColumn] = $data[$currentRow][$currentColumn]->__toString();  
				}
            
            
            }

        }
        $this->save_import($data);
    }
    //保存导入数据
    public function save_import($data)
    {
        //print_r($data);
        //$this->success('该功能正在内测中',U('Member/index'));
        $fields=M($this->_name)->getDbFields();
        //dump($fields);
        foreach($data as $k=>$v){
        	foreach($v as $key=>$val){
        		if($fields[getalphnum($key)]=='add_time'){
        			$val=strtotime($val);
        		}
        		if($val=='男'){
        			$val=1;
        		}
        		if($val=='女'){
        			$val=0;
        		}
        		$date[$fields[getalphnum($key)]]=$val?$val:0;
        	}
    		$res=M($this->_name)->where(array($fields[getalphnum('A')]=>$v['A']))->find();
			if(!$res){
				($type = I('type','',  'trim')) && $date['type'] = $type;//客户字段
				($fuctions = I('fuctions','',  'trim')) && $date['fuctions'] = $fuctions;//简历中岗位字段
				($trades = I('trades','',  'trim')) && $date['trades'] = $trades;//简历中行业字段
				($creator_id = I('creator_id','',  'trim')) && $date['creator_id'] = $creator_id;//简历中行业字段
				$date['owner_id']=$_SESSION['admin']['id'];
				$date=D($this->_name)->create($date);
				$result =D($this->_name)->add($date);
			} 
        }
        if($result){
            $this->success('导入成功');
        }else{
            $this->error('导入失败');
        }
    }

    /**
     * ajax修改单个字段值
     */
    public function ajax_edit()
    {
        //AJAX修改数据
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $id = I($pk, 'intval');
        $field = I('field', 'trim');
        $val = I('val', 'trim');
        //允许异步修改的字段列表  放模型里面去 TODO
        $mod->where(array($pk=>$id))->setField($field, $val);
        $this->ajax_return(1);
    }

    /**
     * 删除
     */
    public function delete()
    {
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $ids = trim(I($pk), ',');
        if ($ids) {
            if (false !== $mod->delete($ids)) {
                IS_AJAX && $this->ajax_return(1, L('operation_success'));
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            IS_AJAX && $this->ajax_return(0, L('illegal_parameters'));
            $this->error(L('illegal_parameters'));
        }
    }

    /**
     * 获取请求参数生成条件数组
     */
    protected function _search() {
        //生成查询条件
        $mod = D($this->_name);
        $map = array();
        foreach ($mod->getDbFields() as $key => $val) {
            if (substr($key, 0, 1) == '_') {
                continue;
            }
            if (I($val)) {
                $map[$val] = I($val);
            }
        }
        return $map;
    }

    /**
     * 列表处理
     *
     * @param obj $model  实例化后的模型
     * @param array $map  条件数据
     * @param string $sort_by  排序字段
     * @param string $order_by  排序方法
     * @param string $field_list 显示字段
     * @param intval $pagesize 每页数据行数
     */
    protected function _list($model, $map = array(), $sort_by='', $order_by='', $field_list='*', $pagesize=20,$join_field='')
    {
		
	
        $mod_pk = $model->getPk();
		
        if (I("sort",'', 'trim')) {
            $sort = I("sort",'', 'trim');
        } else if (!empty($sort_by)) {
            $sort = $sort_by;
        } else if ($this->sort) {
            $sort = $this->sort;
        } else {
            $sort = $mod_pk;
        }
		
		if($this->_name=='Item'){
			if(!$sort){
				 $sort='id,add_time DESC,';  
			}
                    			
		}
		//dump($sort);
        if (I("order",'', 'trim')) {
            $order = I("order",'', 'trim');
        } else if (!empty($order_by)) {
            $order = $order_by;
        } else if ($this->order) {
            $order = $this->order;
        } else {
            $order = 'DESC';
        }
        //如果需要分页
        if ($pagesize) {
            $count = $model->where($map)->count($mod_pk);
            $pager = new Page($count, $pagesize);
			//$count_show =$pager->config();
        }
        $select = $model->field($field_list)->where($map)->order($sort . ' ' . $order); 
		//$total=$model->count();
        $this->list_relation && $select->relation(true);
        if ($pagesize) {
            $select->limit($pager->firstRow.','.$pager->listRows);
			$pager->setConfig("prev","上一页");
			$pager->setConfig("next","下一页");
			$pager->setConfig("first","«首页");
            $page = $pager->show();
            if(ceil($count/$pagesize)>1){
            	$this->assign("page", $page);
            }
            else{
            	$this->assign("page", '<div style="padding-left:20px;color:red;line-height:20px;">没有更多了</div>');
            }
        }
		  


        $list = $select->select();
        //dump($list);
        empty($list)&&$list='暂无数据';
       //dump($select->getLastSql());
        $this->assign('list', $list);
        $this->assign('list_table', true);
    }
	
	//检查权限
    public function check_priv() {
        if (in_array(CONTROLLER_NAME,array('Candidate','Attachment','Email','Progress','Linkman','Custom','Project','Resume','Contract','Offer','Report'))) {
        	
            return true;
        }
        //echo CONTROLLER_NAME;exit;
        if (in_array(ACTION_NAME,array('attachment','ajax_mail_test','upload','save_import','goods_import'))) {
            return true;
        }
        
        if ( (!isset($_SESSION['admin']) || !$_SESSION['admin']) && !in_array(ACTION_NAME, array('login','verify_code')) ) {
            $this->redirect('index/login');
        }
        if($_SESSION['admin']['role_id'] == 1) {
            return true;
        }
        $controller_name = snake_case(CONTROLLER_NAME);
        $action_name = strtolower(ACTION_NAME);

		//echo $controller_name.'--' ;  echo $action_name.'--'	;exit;
        if (in_array($controller_name , explode(',','index'))) {
            return true;
        }
        $menu_mod = M('Menu');
        $menu_id = $menu_mod->where(array('controller_name'=>$controller_name , 'action_name'=>$action_name))->getField('id');
		//echo $menu_id.'--'; echo $_SESSION['admin']['role_id'].'--';exit;
        $priv_mod = D('Admin_auth');
        $r = $priv_mod->where(array('menu_id'=>$menu_id, 'role_id'=>$_SESSION['admin']['role_id']))->count();

        if (!$r) {
            $this->error(L('_VALID_ACCESS_'));
        }
    }
    
	protected function ajax_return($status=1, $msg = '', $data = '', $dialog = ''){
		$ajax_data = array(
			'status' => $status,
			'msg' => $msg,
			'data' => $data,
			'dialog' => $dialog
		);
		$this->ajaxReturn($ajax_data);
	}
	
    protected function update_config($new_config, $config_file = '') {
        !is_file($config_file) && $config_file = CONF_PATH . 'home/config.php';
        if (is_writable($config_file)) {
            $config = require $config_file;
            $config = array_merge($config, $new_config);
            file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
            @unlink(RUNTIME_FILE);
            return true;
        } else {
            return false;
        }
    }

    protected function _get_imgdir($img,$model){
        return realpath(__ROOT__).attach($img,$model);
    }
	
	//正常会员导入
	public function ru_upload(){
        header("Content-Type:text/html;charset=utf-8");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类
        $upload->savePath  =      '/'; // 设置附件上传目录
        // 上传文件
        $info   =   $upload->uploadOne($_FILES['excelData']);
        $filename = './Uploads'.$info['savepath'].$info['savename'];
        $exts = $info['ext'];
        //print_r($info);exit;
        
        if(!$info){// 上传错误提示错误信息
          	$this->error($upload->getError());
      	}else{// 上传成功
          	$this->goods_import($filename, $exts);
        }
    }

    
	
	
}