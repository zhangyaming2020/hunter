<?php
namespace Admin\Controller;
class ArticlePageController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->set_mod('ArticlePage');
		$page_cate = array(
			1=>'关于我们',
			2=>'新手指引'
		);
		$this->assign('page_cate',$page_cate);
    }
}