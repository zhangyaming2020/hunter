<?php
namespace Home\Widget;
use Think\Controller;
class WgWidget extends Controller {
		
		
		//public function footer(){
//		帮助中心
//		$help = M('Article') ->where(array('cate_id'=>11,'status'=>1))->order('ordid desc,id')->select(); 
//		产品中心
//		$product = M('Item_cate') ->where(array('pid'=>0,'status'=>1))->order('ordid,id') ->limit(3) ->select(); 
//		新闻资讯
//		$news  = M('ArticleCate') ->where(array('pid'=>2,'status'=>1))->order('ordid desc,id')->select(); 
//		关于我们
//		$about  = M('Article') ->where(array('cate_id'=>1,'status'=>1))->order('ordid desc,id')->select(); 
//	print_r($flink);	
//		$this->assign('help',$help);	
//		$this->assign('product',$product);	
//		$this->assign('news',$news);	
//		$this->assign('about',$about);
//        $this->display('Widget:footer');
//	}
		//走进头部
		public function nav1(){
		  $aa=M('ad')->where(array('id'=>16,'status'=>1))->select();
		  $lux=M('ArticleCate')->where(array('pid'=>0,'status'=>1))->order('id desc,ordid desc')->select();
		  foreach($lux as $k=>$v){
			$lux[$k]['xx']=D('ArticleCate')->where(array('id'=>$v['id'],'status'=>1))->order('id desc,ordid desc')->select(); 
		  }
		  $this->assign('lux',$lux);
		  $this->assign('aa',$aa);
		  $this->display('Widget:nav1');
		}
		

}