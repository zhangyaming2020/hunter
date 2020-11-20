<?php
namespace Admin\Controller;
class MemberController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Member');
		$this->set_mod('Member');
    }
    
	
	
	protected function _search() {
        $map = array();
        if( $keyword = I('keyword','', 'trim') ){
            $map['_string'] = "nickname like '%".$keyword."%' OR mobile like '%".$keyword."%' OR id like '%".$keyword."%'";
        }
		$status = I('get.status') ;
		if($status===''){
			
		}else if($status=='0'){
			$map['status'] = 0;
		}else{
			$map['status'] = $status;	
		}
        $this->assign('search', array('keyword' => $keyword,'status' => $status,));
        return $map;
    }


    public function _before_insert($data) {
        if( ($data['password']!='')&&(trim($data['password'])!='') ){
            $data['password'] = $data['password'];
        }else{
            unset($data['password']);
        }
        return $data;
    }
	public function _before_edit(){
		$id = I('id','','intval');
		$invite = D('Member')->field('invite_id')->find($id);
		if($invite) $nickname = D('Member')->field('nickname,mobile')->find($invite['invite_id']);
		$this->assign('nickname',$nickname);
	}
    public function _before_update($data){
		if($data['password']==''&&trim($data['password'])=='') unset($data['password']);
		else $data['password']=st_md5($data['password']);
		return $data;
    }
	
	//	余额详情
	public function balance(){
		$uid = I('id','intval');
		$balance = M('MemberLog')-> where(array('member_id'=>$uid)) -> order('id desc')  -> select();	
		$this->assign('log_type',C('log_types'));
		$this->assign('balance', $balance);
		if (IS_AJAX) {
			$response = $this->fetch();
			$this->ajax_return(1, '', $response);
		}else {
			$this->display();
		}
    }
	
    public function user_thumb($id,$img){
        $img_path= avatar_dir($id);
        //会员头像规格
        $avatar_size = explode(',', C('pin_avatar_size'));
        $paths =C('pin_attach_path');

        foreach ($avatar_size as $size) {
            if($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg'){
                @unlink($paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg');
            }
            !is_dir($paths.'avatar/'.$img_path) && mkdir($paths.'avatar/'.$img_path, 0777, true);
            Image::thumb($paths.'avatar/temp/'.$img, $paths.'avatar/'.$img_path.'/' . md5($id).'_'.$size.'.jpg', '', $size, $size, true);
        }

        @unlink($paths.'avatar/temp/'.$img);
    }

    public function ajax_upload_imgs() {
        $file_name = 'avatar';
        $member_id = I('get.member_id',0,'intval');
        if (!empty($_FILES[$file_name]['name'])) {
			 $date_dir = date('ym/d/'); //上传目录
             $result = $this->_upload($_FILES[$file_name], 'avatar/'.$date_dir,  array(
                 'width'=>C('pin_item_simg.width'),
                 'height'=>C('pin_item_simg.height'),
                 'suffix' => '_s',
             ));
             if ($result['error']) {
                $this->ajax_return(0, L('illegal_parameters'));
             }else{
                 $data['thumb_img'] = 'data/attachment/avatar/'.$date_dir .$result['info'][0]['savename'];
				 $this->ajax_return(1, L('operation_success'), $data['thumb_img'].'?t='.time());
             }
           /* $result = $this->_upload($_FILES[$file_name], 'avatar/',  array('width'=>100, 'height'=>100,'remove_origin'=>true, 'remove_origin'=>true),$member_id);
            if ($result['error']) {
                $this->ajax_return(0, L('illegal_parameters'));
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $result['info'][0]['savename'].'?t='.time();
                $data['thumb_img'] = str_replace('.' . $ext, '_thumb.' . $ext, $result['info'][0]['savename']);
                $this->ajax_return(1, L('operation_success'), $data['thumb_img'].'?t='.time());
            }*/
        }
    }
    /**
     * ajax检测邮箱是否存在
     */
    public function ajax_check_val() {
        $key = I('clientid','','trim');
        $val = I($key,'','trim');
        $id = I('id',0, 'intval');
        $result = $this->_mod->where(array($key=>$val,'id'=>array('neq',$id)))->getField('id');
        if ($result) {
            $this->ajax_return(0, '该邮箱已经存在');
        } else {
            $this->ajax_return();
        }
    }
	//正常会员导入
	public function upload() {
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
        if(!$info) {// 上传错误提示错误信息
              $this->error($upload->getError());
          }else{// 上传成功
                  $this->goods_import($filename, $exts);
        }
    }

    //导入数据页面
    public function import()
    {
        $this->display('goods_import');
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
        $PHPExcel=$PHPReader->load($filename);
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
            }

        }
        $this->save_import($data);
    }

    //保存导入数据
    public function save_import($data)
    {
        //print_r($data);exit;

        $Goods = M('member');
        //$add_time = date('Y-m-d H:i:s', time());
        foreach ($data as $k=>$v){
			
			$date['id'] = $v['A'];
			$date['wxname'] = $v['B'];
			$date['realname'] = $v['C'];
			$date['mobile']=$v['D'];
			$date['address']=$v['E'];
			$date['integral']=$v['F'];
			
			$res=M('member')->field('id')->where('id='.$v['A'])->find();
			if(!$res){
				$result = M('member')->add($date);
				} 
			
        }
        if($result){
            $this->success('导入成功');
        }else{
            $this->error('导入失败');
        }
        //print_r($info);

    }
	
	//黑名单导入
	public function uploadh(){
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
        if(!$info) {// 上传错误提示错误信息
              $this->error($upload->getError());
          }else{// 上传成功
                  $this->goods_importh($filename, $exts);
        }
    }
	
    //导入数据页面
    public function importh()
    {
        $this->display('goods_import');
    }

    //导入数据方法
    protected function goods_importh($filename, $exts='xls')
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
        $PHPExcel=$PHPReader->load($filename);
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
            }

        }
        $this->save_importh($data);
    }

    //保存导入数据
    public function save_importh($data)
    {
        //print_r($data);exit;
        $Goods = M('member');
        //$add_time = date('Y-m-d H:i:s', time());
        foreach ($data as $k=>$v){
			$date['id'] = $v['A'];
			$date['wxname'] = $v['B'];
			$date['realname'] = $v['C'];
			$date['mobile']=$v['D'];
			$date['address']=$v['E'];
			$date['integral']=$v['F'];
			$date['status']=0;
			$result = M('member')->add($date);
        }
        if($result){
            $this->success('导入成功');
        }else{
            $this->error('导入失败');
        }
        //print_r($info);
    }
	
	//会员导出
    public function dao_member1(){
		ob_end_clean();
		$type = I('get.type');
		if($type == 'normal'){ //正常
		$map['status'] = 1;
		$filename="正常会员";	
		}else{   // 黑名单 black
		$map['status'] = 0;	
		$filename="黑名单";	
		}
		$member = M('member') -> where($map) -> order('id desc') -> select();
		if(!$member){
			$this -> error('没有用户');
		}
		$data = array();
		foreach($member as $i => $val){
			$data[$i]['id'] = $val['id'];
			$data[$i]['wxname'] = $val['wxname'];
			$data[$i]['realname'] = $val['realname'];
			$data[$i]['mobile'] = $val['mobile'];
			$data[$i]['address'] = $val['address'];
			$data[$i]['integral'] = $val['integral'];
		}
	    foreach ($data as $field=>$v){
			if($field == 'id'){
                $headArr[]='顾客ID';
            }
			if($field == 'wxname'){
                $headArr[]='微信昵称';
            }
			if($field == 'realname'){
                $headArr[]='姓名';
            }
			if($field == 'mobile'){
                $headArr[]='电话';
            }
			if($field == 'address'){
                $headArr[]='地址';
            }
			if($field == 'integral'){
                $headArr[]='积分';
            }
		}
		
        $this->getExcelpei($filename,$headArr,$data);
	}
				
   private  function getExcelpei($fileName,$headArr,$data){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls";
        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        //print_r($headArr);exit;
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        //print_r($data);exit;
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value){// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        //$objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }
	
	//骑手管理
	public function loyer(){
		$count = D('Loyer')->where($data)->count();
		$page = new \Think\Page($count,50);
		$show = $page->show();
		$this->assign('page',$show);
		$list = D('Loyer')->where($data)->order($order)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->display();
	}
	//骑手删除
	public function loyer_del($id){
		$db = D('Loyer')->where(array('id'=>$id))->delete();
		$this->ajax_return($db? 1 :0, $db ? L('operation_success') : L('operation_failure'), '', 'edit');
	}
	
	

}