<?php
namespace Admin\Controller;
use Admin\Org\Image;
use Admin\Org\Tree;
class DuanXinController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Duanxin');
        $this->set_mod('Duanxin');
    }
    
	public function _before_index() {
        $p = I('p',1,'intval');   //序号
        $this->assign('p',$p);
    }
	
	protected function _search() {
        $map = array();
        ($keyword = I('keyword','', 'trim')) && $map['mobile|name'] = array('like', '%'.$keyword.'%');
        $this->assign('search', array(
            'keyword' => $keyword,
        ));
        return $map;
    }
	
	
	private function _set($mobile,$content){
        $post_data = array(
            "account" => C('sms.account'),
            "password" => C('sms.password'),
            "destmobile" => $mobile,
            "msgText" =>$mess ,
            "msgText" => $content,
            "sendDateTime" => '',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
        $post_data = http_build_query($post_data);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_URL, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/http/sendBatchMessage');
       	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   	$buf = curl_exec($ch);  
    }

    //已支付成功订单达到推送基数后自动推送短信（因客户改需求，弃用2016/4/19）
	public function ts_duanxin(){
		//验证今天是否已发送过短信
		$check_time = M('duanxin_jilu')->order('add_time desc')->getfield('add_time');
		if(date('Y-m-d',$check_time) == date('Y-m-d')){
			$this->error('今天已发送过短信！',U('DuanXin/index'));
		}else{
			//上一天支付成功订单统计
			$nums = M('order')->where("FROM_UNIXTIME(pay_time, '%Y%m%d' ) = FROM_UNIXTIME(".strtotime('-1 day').", '%Y%m%d' )")->count();
			//短信的内容
			$content = "昨日支付成功".$nums.C('pin_ts_ln');
			//达到短信推送基数的倍数则发送短信
			$list = M('duanxin')->field('mobile')->where(array('status'=>1))->select();
			foreach($list as $val){
				$this->_set($val['mobile'],$content);  
			}     
			M('duanxin_jilu')->add(array('add_time'=>time()));
			$this->error('短信发送成功！',U('DuanXin/index'));  
		}
	}
	
	  //商品导入
	
	public function upload()
    {
        header("Content-Type:text/html;charset=utf-8");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类
        $upload->savePath  =      '/'; // 设置附件上传目录
// 		$upload->savePath =  '/data/attachment/editer/image/';
        // 上传文件
        $info   =   $upload->uploadOne($_FILES['excelData']);
        $filename = './Uploads'.$info['savepath'].$info['savename'];
        $exts = $info['ext'];
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
                $data[$currentRow][$currentColumn]=(string)$currentSheet->getCell($address)->getValue();
            }

        }
        $this->save_import($data);
    }

    //保存导入数据
    public function save_import($data)
    {
        $Goods = $this->_mod;
//      $add_time = time();
        $data_list = "";
        foreach ($data as $k=>$v){  
			$id = $v['A'];
			$name = $v['B'];
			$mobile = $v['C'];
			$add_time = time();
            $data_list .= "('$id','$mobile','$name','$add_time'),";			
		}
		$data_list = substr($data_list,0,-1);
	   	$result = $Goods->execute("insert into jrkj_duanxin (id,mobile,name,add_time) values $data_list");
		if($result){
            $this->success('导入成功');
        }else{
            $this->error('导入失败');
        }
    }
}