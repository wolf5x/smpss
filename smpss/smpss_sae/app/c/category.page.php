<?php
/**
 * 分类管理
 * @author 齐迹  email:smpss2012@gmail.com
 *
 */
class c_category extends base_c {
	
	function __construct($inPath) {
		parent::__construct ();
		if (self::isLogin () === false) {
			$this->ShowMsg ( "请先登录！", $this->createUrl ( "/main/index" ) );
		}
		if (self::checkRights ( $inPath ) === false) {
			//$this->ShowMsg("您无权操作！",$this->createUrl("/system/index"));
		}
		$this->params ['inpath'] = $inPath;
		$this->params ['head_title'] = "分类管理-" . $this->params ['head_title'];
	}
	
	function pageindex($inPath) {
		$url = $this->getUrlParams ( $inPath );
		$page = $url ['page'] ? $url ['page'] : 1;
		$categoryObj = new m_category ();
		$this->params ['category'] = $categoryObj->getOrderCate ( '|__' );
		return $this->render ( 'category/index.html', $this->params );
	}
	
	function pagecategory($inPath) {
		$url = $this->getUrlParams ( $inPath );
		$catid = ( int ) $url ['catid'] > 0 ? ( int ) $url ['catid'] : ( int ) $_POST ['cat_id'];
		$categoryObj = new m_category ( $catid );
		$this->params ['categorylist'] = $categoryObj->getOrderCate ( '&nbsp;&nbsp;&nbsp;&nbsp;' );
		if ($_POST) {
			$post = base_Utils::shtmlspecialchars ( $_POST );
			if ($catid) {
				if ($categoryObj->create ( $post )) {
					$this->ShowMsg ( "修改成功！", $this->createUrl ( "/category/index" ), '', 1 );
				}
				$this->ShowMsg ( "修改失败" . $categoryObj->getError () );
			} else {
				if ($categoryObj->create ( $post )) {
					$this->ShowMsg ( "添加成功！", $this->createUrl ( "/category/index" ), '', 1 );
				}
				$this->ShowMsg ( "添加失败，原因：" . $categoryObj->getError () );
			}
		} else {
			if ($catid) {
				$this->params ['category'] = $categoryObj->get ();
			}
			return $this->render ( 'category/category.html', $this->params );
		}
	}
}
?>