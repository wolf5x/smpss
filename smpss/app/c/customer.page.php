<?php
/**
 * 商品管理
 * @author 齐迹  email:smpss2012@gmail.com
 *
 */
class c_customer extends base_c {
	function __construct($inPath) {
		parent::__construct ();
		if (self::isLogin () === false) {
			$this->ShowMsg ( "请先登录！", $this->createUrl ( "/main/index" ) );
		}
		if (self::checkRights ( $inPath ) === false) {
			//$this->ShowMsg("您无权操作！",$this->createUrl("/system/index"));
		}
		$this->params['inpath'] = $inPath;
		$this->params ['head_title'] = "客户管理-" . $this->params ['head_title'];
	}
	
	function pageindex($inPath) {
		$url = $this->getUrlParams ( $inPath );
		$page = $url['page']?(int)$url['page']:1;
		$url['ac'] = $url['ac']?$url['ac']:"";
		$condi = '';
		
		$customerObj = new m_customer();
		switch ($url ['ac']) {
		case "del":
			$customerObj=new m_customer((int)$url['cid']);
			if ($url ['cid']) {
				if ($customerObj->deleteOne ( $url ['cid'] )) {
					$this->ShowMsg ( "删除成功！", $this->createUrl ( "/customer/index" ), 2, 1 );
				}
				$this->ShowMsg ( "删除出错！原因：" . $customerObj->getError () );
			}
			break;
		default:
			if($_POST){
				$key = base_Utils::getStr($_POST['key'],'html');
				$this->params['key'] = $key;
				$tableName = $customerObj->tableName();
				if($key){
					$condi .= "{$tableName}.customer_name like '%{$key}%'";
				}
			}
			$rs = $customerObj->getCustomerList($condi, $page);
		}
		$this->params ['customer'] = $rs->items;
		$this->params ['pagebar'] = $this->PageBar ( $rs->totalSize, base_Constant::PAGE_SIZE, $page, $inPath );
		
		return $this->render ( 'customer/index.html', $this->params );
	}
		
	function pageaddcustomer($inPath) {
		$url = $this->getUrlParams ( $inPath );
		$customer_id = ( int ) $url ['cid'] > 0 ? ( int ) $url ['cid'] : ( int ) $_POST ['customer_id'];
		$mode=(int)$url['cid']>0?0:1;
		$customerObj = new m_customer($customer_id);
		if($_POST){
			
			/******************************/
			$post = base_Utils::shtmlspecialchars ( $_POST );
			if ($customerObj->create ( $post )) {
			//	base_Utils::ssetcookie(array('cat_id'=>$post['cat_id']));
				$this->ShowMsg ( "操作成功！", $this->createUrl ( "/customer/addcustomer" ), 2, 1 );
			}
			$this->ShowMsg ( "操作失败" . $customerObj->getError () );
		}
	//	$categoryObj = new m_category ();
	//	$this->params['cat_id'] = (int)$_COOKIE['cat_id'];
	//	$this->params['catelist'] = $categoryObj->getOrderCate('&nbsp;&nbsp;&nbsp;&nbsp;');
		$this->params['customer'] = $customerObj->selectOne("customer_id={$customer_id}");

	//	$this->params['goods']['goods_pic']= $this->dealPicture($this->params['goods']['goods_pic']);
		if($mode===0)	
			return $this->render ( 'customer/editcustomer.html', $this->params );
		else if($mode===1)
			return $this->render ( 'customer/addcustomer.html', $this->params );
	}
	

	
}
