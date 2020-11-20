<?php
namespace Admin\Controller;
use Think\Page;
header("Content-type:text/html; charset=utf-8");
class ResumeController extends AdminCoreController {
	public function _initialize() {
         parent::_initialize();
        $this->_mod = D('Resume');
        $this->set_mod('Resume');
        $this->page_size=14;
        $this->educate=array('','初中','高中','专科','大专','本科','硕士','博士');
        $this->assign('educate',$this->educate);
        $edu=array('初中'=>1,'高中'=>2,'专科'=>3,'大专'=>4,'本科'=>5,'硕士'=>6,'博士'=>7);
        //dump($edu['初中']);
 }



    public function _before_index() {
		//$str=array("养猪技术员","养牛技术员","养羊技术员","养禽技术员","养兔技术员","猪饲养员","禽饲养员","牛羊饲养员","水产饲养员","其他养殖技术员","人工授精技术员","胚胎移植技术员","产房技术员","孵化技术员","养殖场兽医专家","养殖场技术经理","兽医总监生产经理/主管","生产总监","环保总监","技术场长","副场长","畜牧场场长");
		$this->list_relation=true;
		
    	$this->_before_add();
    	$this->assign('educate',$this->educate);
    }

	protected function _search() {
		
		$map = array();

        //'status'=>1

        ($time_start = I('time_start','', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));

        ($time_end = I('time_end','',  'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));

        $resuname = I('resuname','',  'trim');
		if($resuname){
			$field= "info like '%".$resuname."%' or resuName like '%".$resuname."%' or resuContactInfo like '%".$resuname."%' or resuEmail like '%".$resuname."%' or id in(select resume_id from jrkj_progress where type=2 and content like '%".$resuname."%')";
		    $map['_string']=$field;
		}
        ($project_id = I('project_id'));
		//$map['creator_id'] = $_SESSION['admin']['id'];
        if(isset($_GET['custom_id'])){//客户下的项目
				$map['custom_id'] = $custom_id;
        }
        
        if($_GET['type']==1){//
				$map['type'] = $_GET['type']-1;
        }
        
        if($_GET['type']==2){//
				$map['owner_id'] = $_SESSION['admin']['id'];
        }
        $this->assign('search', array(

            'time_start' => $time_start,

            'time_end' => $time_end,

            'price_min' => $price_min,

            'price_max' => $price_max,

            'type' => $_GET['type'],

            'status' =>$status,

            'selected_ids' => $spid,

            'cate_id' => $cate_id,

            'resuname' => $resuname,

            'custom_id' =>$custom_id,
            
            'resume_id'=>$resume_id,
            
            'is_tab'=>$is_tab,
            
            'is_add'=>$is_add,
        ));
        //dump($map);
        return $map;


    }
    //
    public function ajax_list(){ 
    	$model=M('custom');
    	$map=array();
    	($name = I('name','',  'trim')) && $map['cutaName'] = array('like', '%'.$name.'%');
    	$count = $model->where($map)->count();
        $pager = new Page($count, 10);
        $select = $model->field('id,cutaName')->where($map); 
		//$total=$model->count();
		$list=$select->limit($pager->firstRow.','.$pager->listRows)->select();
		$pager->setConfig("last","末页");
		$pager->setConfig("first","首页");
        $page = $pager->show();
        $this->assign("page", $page);
        $this->assign("list", $list);
    	$this->display();
    }
    /**
     * 加入项目中
     */
    public function resume_project()
    {
        $mod = D('resume_project');
        $resume_id = I('resume_id');
        $project_id = I('id');
        if ($resume_id&&$project_id) {
        	$username=$mod->alias('m')->where(array('m.resume_id'=>$resume_id,'m.project_id'=>$project_id))
        	->join('left join __ADMIN__ a on a.id=m.operate_id')
        	->getField('username');
        	if($username){
        		IS_AJAX && $this->ajax_return(0, '对不起，'.$username.'已经在本项目中推荐过此简历！');
        	}
        	$data=array(
        		'resume_id'=>$resume_id,
        		'project_id'=>$project_id,
        		'operate_id'=>$_SESSION['admin']['id'],
        		'add_time'=>time()
        		);
            if (false !== $mod->add($data)) {
                IS_AJAX && $this->ajax_return(1, '恭喜您,加入成功');
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
    public function ajax_contact_list(){
    	$id=I('custom_id');
    	$id&&$list=M('linkman')->where(array('customer_id'=>$id))->field('id,cucoName,cucoOfficeTel')->order('add_time desc')->select();
        if($list){
        	$str='';
        	foreach($list as $k=>$v){
        		$str.='<li class="current" style="height:30px;line-height:30px;">';
				$str.='<label style="width:100%;margin-left:15px; float: none; text-align: left;"><a style="text-decoration:none;" href="javascript:;"><input type="checkbox" name="cucoId" class="name" cuconame="'.$v['cuconame'].'" value="'.$v['id'].'">&nbsp;&nbsp;'.$v['cuconame'].'&nbsp;&nbsp;'.$v['cucoofficetel'].'</a></label>';
				$str.='</li>';
						
        	}
        	$this->ajaxReturn($str);
        }
    }
    public function _before_add() {
    	$educate=$this->educate;
    	//地区
       	$place['province']=M('place')->where(array('pid'=>0))->field('name,pid,id')->select();
    	$city=array();
    	foreach($place['province'] as $k=>$v){
    		$city[$v['id']]=M('place')->where(array('pid'=>$v['id']))->field('name,pid,id')->select();
    	}
    	
    	//岗位
       	$job=M('job')->where(array('pid'=>0))->field('name,pid,id')->select();
    	$job_sel=array();
    	foreach($job as $k=>$v){
    		$job_sel[$v['id']]=M('job')->where(array('pid'=>$v['id']))->field('name,pid,id')->select();
    	}
    	//行业
    	$arr=M('industries')->select();
		$industries=get_childs($arr,0);
        $id=I('id');
        $this->assign('project_id', I('project_id'));
        $this->assign('id', $id);
    	$this->assign('place',$place);
    	$this->assign('city',$city);
    	$this->assign('job',$job);
    	$this->assign('educate',$educate);
    	$this->assign('job_sel',$job_sel);
    	$this->assign('industries',$industries);
    }
    
    public function detail(){
        $id=I('id');
        $this->assign('resume_id',$id);
        $info=M('Resume')->where(array('id'=>$id))->field('type')->find();
        $this->assign('info',$info);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
        } else {
            $this->display();
        }
    }
    public function _after_update($id){
    	$type=I('file_type');
    	if(in_array($type,array('xls','xlsx'))){
    		$name=I('pdf_name');
    		$info=I('info');
    		$info&&file_put_contents('./uploads/resume/'.$name.'.html',$info);
    	}
    }
    
    public function detail_info(){
        $id=I('resume_id');
        $educate=$this->educate;
        $admin=M('admin')->where(array('status'=>1))->field('username,id')->select();
    	$info=D('Resume')->where(array('id'=>$id))->relation(true)->find();
    	$edu=explode(',',$info['educations']);
    	$str='';
    	foreach($edu as $k=>$v){
    		if($v>0){
    			$str.=$educate[$v];
    		}
    		else{
    			$info['str']=$info['educations'];
    		}
    	}
    	$info['str']=$str;
    	$info['download_name']=$info['pdf_name']?$info['pdf_name']:$info['org_name'];
    	if(in_array($info['file_type'],array('xls','xlsx'))){
    		$info['download_name']=$info['pdf_name'].'.'.$info['file_type'];
    		$info['info']=file_get_contents('./uploads/resume/'.$info['pdf_name'].'.html');
    	}
        $this->assign('resume_id',$id);
        $this->assign('admin',$admin);
        $this->assign('info',$info);//dump($info);
        if (IS_AJAX) {
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
        } else {
            $this->display();
        }
    }
	/**
     * 获取紧接着的下一级分类ID
     */
    public function ajax_job_getchilds() {
        $id = I('id',0, 'intval');
        $type = I('type', 0, 'intval');
        $map = array('pid'=>$id);
        if (!empty($type)) {
            $map['type'] = $type;
        }
        $return = M('job')->field('id,name')->where($map)->select();
        if ($return) {
            $this->ajax_return(1, L('operation_success'), $return);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }
	/**
     * 获取紧接着的下一级分类ID
     */
    public function ajax_indus_getchilds() {
        $id = I('id',0, 'intval');
        $type = I('type', 0, 'intval');
        $map = array('pid'=>$id);
        if (!empty($type)) {
            $map['type'] = $type;
        }
        $return = M('industries')->field('id,name')->where($map)->select();
        if ($return) {
            $this->ajax_return(1, L('operation_success'), $return);
        } else {
            $this->ajax_return(0, L('operation_failure'));
        }
    }
    public function _before_insert($data='') {
    	$data['owner_id']=$data['creator_id']=$_SESSION['admin']['id'];
        return $data;
    }
    
    public function _after_insert($id) {
    	$data['operate_id']=$_SESSION['admin']['id'];
        $data['resume_id']=$id;
        $data['project_id']=$project_id=I('project_id');
        $data['type']=0;//未完善
        $data['add_time']=time();
        if($project_id&&$id){
        	M('candidate')->add($data);
        }
    }
    
    public function doc_import() {
    	if(IS_POST){
    		exit;//见 file_upload
    	}
    	else{
    		$project=I('project_id');
    		$this->assign('project_id',$project);
    		if (IS_AJAX) {
    			
                $response = $this->fetch();
                $this->ajax_return(1,'',$response,'detail');
	        } else {
	            echo "非法访问";exit;
	        }
    	}
    }

    public function _before_edit() {
        $this->_before_add();
        $is_ajax=I('is_ajax','0');
        $this->assign('is_ajax', $is_ajax);
        $this->_relation=true;
    }
    
    public function _after_edit($info) {
    	$educate=$this->educate;
    	$edu=explode(',',$info['educations']);
    	$info['edu_name']='';
    	if($edu){
    		foreach($edu as $k=>$v){
    			$info['edu_name'].=$educate[$v].',';
    		}
    		$info['edu_name']=mb_substr($info['edu_name'],0,-1);
    	}
    	else{
    		$info['edu_name']='学历';
    	}
    	
    	
        $place_ids=explode(';',$info['locations']);
        $place_ids&&$info['locations_name']=implode(',',M('place')->where(array('id'=>array('in',$place_ids)))->getField('name',true));
    	
    	$trades_ids=explode(';',$info['trades']);
    	$trades_ids&&$info['trades_name']=implode(',',M('job')->where(array('id'=>array('in',$trades_ids)))->getField('name',true));
    	
    	return $info;
    }
    public function ajax_check_name() {
        $name = I('cutaShortName','', 'trim');
        $id = I('id','', 'intval');
        if ($this->_mod->name_exists($name, $id)) {
            $this->ajaxReturn(array('status'=>0));
        } else {
            $this->ajaxReturn(array('status'=>1));
        }
    }
   	public function test(){
   		$this->display();
   	}
   	
   	public function file_upload(){
   		$filename =$resuname= iconv('UTF-8', 'GB2312', $_FILES['file']['name']); 
   		//dump($_FILES["file"]['name']);exit;
		$key = $_POST['key'];
		$key2 = $_POST['key2'];
		if ($filename) {
        	$find=M('resume')->where(array('org_name'=>iconv("GB2312","UTF-8",$filename)))->getField('id');
        	if($find)
        	{
        	IS_AJAX && $this->ajax_return(2, '简历已存在',U('Resume/detail',array('id'=>$find)));
        	}
		   	$str=strtolower(pathinfo($_FILES["file"]['name'], PATHINFO_EXTENSION));
		   	//echo $str;exit;
		    //保存上传文件至指定目录
		    if($str=='pdf'){
		    	$name=md5(time()).'.pdf';
		    	$res=move_uploaded_file($_FILES["file"]["tmp_name"],"./uploads/resume/pdf/" . $name);//如何保存成功，则进行下一步
		    	
		    }
		    elseif(strtolower($str)=='xls' || $str=='xlsx'){//excel转html
		    	$name=md5(time());
		    	$res=move_uploaded_file($_FILES["file"]["tmp_name"],"./uploads/resume/excel/".$name.'.'.$str);//如何保存成功，则进行下一步
		    	
	    	}
		    else{
		    	$res=move_uploaded_file($_FILES["file"]["tmp_name"],"./uploads/resume/" . $filename);//如何保存成功，则进行下一步
			}
		    if($res){
				// 取文件后缀名
	    		
	    		//dump($_FILES["file"]);exit;
	    		if($str=='pdf'){//pdf转txt
	    			$res=$this->pdftotext($_FILES["file"]['name'],$name);
	    		}
	    		else if(strtolower($str)=='xls' || $str=='xlsx'){//excel转html
		    		$name=md5(time());
	    			$res=$this->exceltohtml($name,$str);
	    			$da['info']=file_get_contents('./uploads/resume/excel/'.$name.'.html');
	    		}
	    		else{
	    			include "./doc.php";
	        		$res=word_upload("/uploads/resume/".$filename);
	    		}
				
			//dump($res);exit;
	            if($res['sex']=='女'){
	            	$data['sex']=0;
	            }
	            if($res['sex']=='男'){
	            	$data['sex']=1;
	            }
	    		$res['email']&&$data['resuEmail']=$res['email'];
	    		$res['pdf_name']&&$data['pdf_name']=$res['pdf_name'];
	    		$res['educations']&&$data['educations']=$res['educations'];
	    		$data['resuContactInfo']=$res['mobile'];
	    		$data['info']=$res['html']?$res['html']:'';
	    		$data['add_time']=time();
	    		$data['file_type']=$str;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
	    		$data['owner_id']=$data['creator_id']=$_SESSION['admin']['id'];
	    		$data['resuName']=$data['org_name']=iconv("GB2312","UTF-8",$filename);
	    		if(!$res['sex']|!$data['resuName']){//未完善的简历：2个不能为空
	    			$data['type']==0;
	    		}
	    		else{
	    			$data['type']=1;
	    		}
	    		//dump($data);exit;
	    		 if (false === $da=M('resume')->create($data)) {
	                IS_AJAX && $this->ajax_return(0, $mod->getError());
	                $this->error($mod->getError());
            	}
            	
	    		$mod=M('resume');$mod->startTrans();//开启事务
	    		$result=$mod->add($da);
	    		$project_id=I('project_id');
				$result1=1;
				if($project_id){//通过项目导入简历时 自动加入候选人中
					$result1=M('candidate')->add(array(
						'resume_id'=>$result,
						'add_time'=>time(),
						'project_id'=>$project_id,
						'operate_id'=>$_SESSION['admin']['id']
					));
				}
	    		if($result&&$result1){
	    			$mod->commit();
	                IS_AJAX && $this->ajax_return(1, L('operation_success'), U('Resume/edit',array('id'=>$result)), 'doc_import');
	                $this->success(L('operation_success'));
	            } else {
	            	$mod->rollback();
	                IS_AJAX && $this->ajax_return(0, L('operation_failure'));
	                $this->error(L('operation_failure'));
					
	            }
			}
			
		}
		else{
				IS_AJAX && $this->ajax_return(0, '文件未识别');
		}
	}
	public function pdftotext($file_name,$name){
		$dir_name=dirname(dirname(dirname(dirname(__FILE__))))."\\uploads\\resume\\pdf\\".$name;
		//dump(array($file_name,$name));exit;
		$content = shell_exec("C:\\xpdf\pdftotext $dir_name  && echo  1 ");
		$data=array();
		if($content==1){//数据整理
			$str="";
			$file=str_replace('.pdf','.txt',$dir_name);//新的文件
			$cbody = file($file); //file（）函数作用是返回一行数组，txt里有三行数据，因此一行被识别为一个数组，三行被识别为三个数组
			 for($i=0;$i<count($cbody);$i++){ //count函数就是获取数组的长度的，长度为3 因为一行被识别为一个数组 有三行
			 
			 $str.=$cbody[$i].'<br/>'; //最后是循环输出每个数组，在每个数组输出完毕后 ，输出一个换行，这样就可以达到换行效果
			 }
			preg_match('/(男|女)/',$str, $sex);
	 		preg_match('/(初中|中专|大专|专科|高中|本科|硕士|博士)/',$str, $education);
	 		$email=$this->getEmail($str); 
	 		$mobile=$this->findThePhoneNumbers($str);
	 		$data['mobile']=$mobile[0];
	 		$data['sex']=$sex[0];
	 		$data['email']=$email[0];
	 		$edu=array('初中'=>1,'高中'=>2,'专科'=>3,'大专'=>4,'本科'=>5,'硕士'=>6,'博士'=>7);
	 		$data['educations']=$edu[$education[0]];
	 		$data['html']=$str;//var_dump($data);exit;
	 		//$data['resuName']=$file_name;
	 		$data['pdf_name']='pdf/'.$name;
		}
	 	return $data;
	}
	
	public function getEmail($str) {                                 //匹配邮箱内容
		$pattern = "/([a-z0-9\-_\.]+@[a-z0-9]+\.[a-z0-9\-_\.]+)/"; 
		preg_match_all($pattern,$str,$emailArr); 
		return $emailArr[0]; 
	} 
	public function findThePhoneNumbers($oldStr = ""){
	  // 检测字符串是否为空
	  $oldStr=$newStr=trim($oldStr);
	  $numbers = array();
	  if(empty($oldStr)){
	    return $numbers;
	  }
	  /*
	  // 手机号的获取
	  $reg='/\D(?:86)?(\d{11})\D/is';//匹配数字的正则表达式
	  preg_match_all($reg,$oldStr,$result);*/
	  // 手机号的获取
	  $reg='/\D(?:86)?(\d{11})\D/is';//匹配数字的正则表达式
	  preg_match_all($reg,$newStr,$result);
	  
	  $numbers= $result[1];
	  // 返回最终数组
	  return $numbers;
	}
	
	public function exceltohtml($name='',$type='xls'){
		//这里引入PHPExcel类
		$savePath = './uploads/resume/excel/'.$name.'.html'; //这里记得将文件名包含进去
		fopen($savePath, "w");

		$filePath ='./uploads/resume/excel/'.$name.'.'.$type;
		//dump(array($savePath,$filePath));exit;
		include "./_core/Library/Org/Util/PHPExcel/IOFactory.php";
		import("Org.Util.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $fa=new \PHPExcel_IOFactory();
		$fileType = $fa->identify($filePath); //文件名自动判断文件类型
		$objReader = $fa->createReader($fileType);//dump($objReader);exit;
		$objPHPExcel = $objReader->load($filePath);//exit;
		import("Org.Util.HTML");
		if (!class_exists('PHPExcel_Writer_HTML')){
		    include "./_core/Library/Org/Util/PHPExcel/Writer/HTML.php";
		}
		//dump($objPHPExcel);exit;
		$objWriter = new \PHPExcel_Writer_HTML($objPHPExcel); 
		$objWriter->setSheetIndex(0); //可以将括号中的0换成需要操作的sheet索引
		$objWriter->save($savePath); //保存为html文件
		//exit;
		$htmlContent=file_get_contents($savePath);
		$data=array();
		preg_match('/(男|女)/',$htmlContent, $sex);
 		preg_match('/(初中|中专|大专|专科|高中|本科|硕士|博士)/',$htmlContent, $education);
 		$email=$this->getEmail($htmlContent); 
 		$mobile=$this->findThePhoneNumbers($htmlContent);
 		$data['mobile']=$mobile[0];
 		$data['sex']=$sex[0];
 		$data['email']=$email[0];
 		$edu=array('初中'=>1,'高中'=>2,'专科'=>3,'大专'=>4,'本科'=>5,'硕士'=>6,'博士'=>7);
 		$data['educations']=$edu[$education[0]];
 		//$data['html']=$htmlContent;//var_dump($data);exit;
 		$data['pdf_name']='excel/'.$name;
	 	return $data;
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
        for($currentRow=3;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                //读取到的数据，保存到数组$arr中
                $data[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
            	if($data[$currentRow][$currentColumn] instanceof PHPExcel_RichText){ 
            	//echo 33;
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
        //$this->success('该功能正在内测中',U('Member/index'));
        $mod=M($this->_name);
        //$mod->startTrans();//事务开启
        //dump($data);
        //dump($mod->getDbFields());
        $field=array('B'=>'resuCurCompany','C'=>'contact_person','E'=>'resuContactInfo','F'=>'resuEmail');
        $time=time();
        $creator_id=$_SESSION['admin']['id'];
        $res=1;
        	
        foreach($data as $k=>$v){
        	$da[$k]=array(
        		'resuCurCompany'=>$v['B'],
        		'contact_person'=>$v['C'],
        		'resuName'=>$v['C'],
        		'job_first'=>$v['D'],
        		'resuContactInfo'=>$v['E'],
        		'resuEmail'=>$v['F'],
        		'owner_id'=>$_SESSION['admin']['id'],
        		'creator_id'=>$creator_id,
        		'add_time'=>time()
        	);
        	$find=$mod->where(array('resuEmail'=>$v['F'],'resuContactInfo'=>$v['E']))->getField('id');
        	if($find){
        		//echo 1;
        		$resume_id=$find;
        	}
        	else{
        		//echo 2;
        		$res=$resume_id=$mod->add($da[$k]);
        	}
        	//dump($v);
        	//dump(array($v['G'],$resume_id));exit;
        	if($resume_id&&$v['G']){//添加沟通记录
        		$res=M('progress')->add(array(
        			'content'=>$v['G'],
        			'resume_id'=>$resume_id,
        			'creator_id'=>$creator_id,
        			'type'=>2,
        			'add_time'=>time()
        			)
        		);
        	}
        }
        if($res){
            $this->success('导入成功');
        }else{
            $this->error('导入失败');
        }
    }

}