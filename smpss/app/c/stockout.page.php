<?php
/**
 * 销售管理
 * @author 齐迹  email:smpss2012@gmail.com
 *
 */
class c_stockout extends base_c {
	function __construct($inPath) {
		parent::__construct ();
		if (self::isLogin () === false) {
			$this->ShowMsg ( "请先登录！", $this->createUrl ( "/main/index" ) );
		}
		if (self::checkRights ( $inPath ) === false) {
			//$this->ShowMsg("您无权操作！",$this->createUrl("/system/index"));
		}
		$this->params ['inpath'] = $inPath;
		$this->params ['head_title'] = "出库管理-" . $this->params ['head_title'];
	}

	function pageindex($inPath) {
		//$str = "%abcde|你好%%defg% \nhaha %hi_jk|我不\n h_ao %";

		$url = $this->getUrlParams ( $inPath );
		$goods_id = $url ['gid'] ?: $_POST ['goods_id'];
		$stockout_sn = $url['sid'] ?: $_POST['stockout_sn'];
		$ac = $url['ac'] ?: $_POST['ac'];
		$ac = trim($ac);
		$this->params['ac'] = $ac;
        // get filter params from url
        $key['customer_name'] = $url['customer_name'];
        $key['stockout_sn'] = $url['stockout_sn'];
        $key['goods_sn'] = $url['goods_sn'];
        $key['goods_name'] = $url['goods_name'];
        $key['date_start'] = $url['date_start'];
        $key['date_end'] = $url['date_end'];
        $key = array_filter($key);

		switch ($ac) {
		case 'detail':
			if(empty($stockout_sn)) {
				$this->ShowMsg('出库单编号不能为空！');
			}
			$idxObj = base_mAPI::get('m_stockoutindex', $stockout_sn);
			$idxRs = $idxObj->get();
			if(!$idxRs) {
				$this->ShowMsg('错误：' . $idxObj->getError());
			}
			$idxRs['stockout_totalprice'] = number_format($idxRs['stockout_totalprice'], 2, '.', '');
			$detObj = base_mAPI::get('m_stockoutdetail');
			$detRs = $detObj->getBySn($stockout_sn);
			if(!$detRs){
				$this->ShowMsg('错误：' . $detObj->getError());
			}
			$this->params['index'] = $idxRs;
			$this->params['detail'] = $detRs;

			return $this->render ( 'stockout/detail.html', $this->params );
			break;
		case 'del':
			if(empty($stockout_sn)) {
				$this->ShowMsg('出库单编号不能为空！');
			}
			$idxObj = base_mAPI::get('m_stockoutindex', $stockout_sn);
			$res = $idxObj->deleteOne($stockout_sn);
			if(!$res) {
				$this->ShowMsg('删除错误：' . $idxObj->getError());
			}
            $lastfilter = unserialize(urldecode($url['lastfilter']));
            unset($url['sid'], $url['ac'], $url['lastfilter']);
            $url = array_merge($url, $lastfilter);
            $newurl = self::createUrl("/{$inPath[1]}/{$inPath[2]}/", $url);
            $this->redirectToUrl($newurl);

        case 'query':
            $subFilter = isset($_POST['filter']);
            $subExport = isset($_POST['export']);
            if($subFilter && $subExport){
                $this->ShowMsg("呵呵");
            }

            // export
			if ($subExport) {
				$soutDet = base_mAPI::get('m_stockoutdetail');
                $lastfilter = unserialize(urldecode($_POST['lastfilter']));
				if(!$soutDet->exportByCondition($lastfilter)) {
					$this->ShowMsg('导出失败:'. $soutDet->getError());
				}
				return;
			} else if ($subFilter) {
                $key['customer_name'] = $key['customer_name'] ?: base_Utils::getStr($_POST ['key_cname']);
                $key['stockout_sn'] = $key['stockout_sn'] ?: base_Utils::getStr($_POST ['key_ssn']);
                $key['goods_sn'] = $key['goods_sn'] ?: base_Utils::getStr($_POST ['key_gsn']);
                $key['goods_name'] = $key['goods_name'] ?: base_Utils::getStr($_POST ['key_name']);
                $key['date_start'] = $key['date_start'] ?: base_Utils::getStr($_POST['date_start']);
                $key['date_end'] = $key['date_end'] ?: base_Utils::getStr($_POST['date_end']);
                // redirect to the url containing query params
                $key = array_filter(array_merge($url, $key));
                unset($key['ac']);
                $newurl = self::createUrl("/{$inPath[1]}/{$inPath[2]}/", $key);
                $this->redirectToUrl($newurl);
            }

        default:
            $page = (int)($url['page'] ?: 1);
            $soutIdx = new m_stockoutindex ();

            $this->params ['key'] = $key;

            $soutIdx->setCount ( true );
            $soutIdx->setPage ( $page );
            $soutIdx->setLimit ( base_Constant::PAGE_SIZE );
            $stockout = $soutIdx->getByCondition($key);

            $this->params['lastfilter'] = urlencode(serialize($key));
            $this->params['stockout'] = $stockout->items;
            $this->params ['pagebar'] = $this->PageBar ( $stockout->totalSize, base_Constant::PAGE_SIZE, $page, $inPath );
            return $this->render ( 'stockout/index.html', $this->params );
        }
    }

    function pagestockout($inPath) {
        $url = $this->getUrlParams ( $inPath );
        $stockout_sn = !empty($url ['sid']) ? $url['sid'] : $_POST['stockout_sn'];
        $ac = isset($url['ac']) ? $url['ac'] : (isset($_POST['ac']) ? $_POST['ac'] : '');
        $this->params['ac'] = $ac;

        session_start ();
        $order_id = $_SESSION ['order_id'];
        if (! $order_id) {
            $order_id = date ( "mdHis", time () ) . base_Utils::random ( 4, 1 );
            $_SESSION ['order_id'] = $order_id;
        }
        $infos = &$_SESSION['infos']['stockout'];

        switch($ac) {
        case 'ajaxaddyes':
            $rsp = new stdclass();
            $data = &$_POST;
            $goodsObj = base_mAPI::get ( "m_goods");
            $grs = $goodsObj->getTheGoods($data['goods_sn'], $data['goods_name_chn'], $data['goods_name_tha']);
            $goods_id = $grs['goods_id'];
            if( !$goods_id ){
                $this->ajaxReturn(1, "商品信息错误: ". $goodsObj->getError());
            }
            $d['goods_id'] = $goods_id;
            $d['goods_sn'] = $data ['goods_sn'];
            $d['goods_name_tha'] = $data ['goods_name_tha'];
            $d['goods_name_chn'] = $data ['goods_name_chn'];
            $d['goods_pack_num'] = $data['goods_pack_num'];
            $d['goods_pack_size'] = $data ['goods_pack_size'];
            $d['goods_unitprice'] = $data ['goods_unitprice'];
            $d['goods_totalprice'] = $data ['goods_totalprice'];
            $d['goods_note'] = $data['goods_note'];

            $d['goods_unitprice'] = number_format($d['goods_unitprice'], 2, '.', '');
            $d['goods_totalprice'] = number_format($d['goods_totalprice'], 2, '.', '');

            if($d['goods_sn'] != $grs['goods_sn']
                || $d['goods_name_chn'] != $grs['goods_name_chn']
                || $d['goods_name_tha'] != $grs['goods_name_tha']
                || $d['goods_pack_size'] != $grs['goods_pack_size'])
            {
                $this->ajaxReturn(1, '商品信息非法！');
            }

            if(empty($d['goods_pack_num'])){
                //
            } else if($d['goods_pack_num'] <= 0 || intval($d['goods_pack_num']) != $d['goods_pack_num']) {
                $this->ajaxReturn(1, '商品数量必须为大于0的整数！');
            }

            if(empty($d['goods_unitprice'])){
                //$this->ajaxReturn(1, '未设置商品单价！');
                //return;
            } else if($d['goods_unitprice'] < 0) {
                $this->ajaxReturn(1, '商品单价不能小于0！');
                return;
            }

            $tt = round((float)$d['goods_pack_num'] * $d['goods_pack_size'] * $d['goods_unitprice'], 2);

            if($tt != $d['goods_totalprice'])
            {
                $this->ajaxReturn(1, '商品总价非法！');
                return;
            }

            $ishas = false;
            $stockout_totalprice = $d['goods_totalprice'];
            if($infos['detail']) {
                foreach($infos['detail'] as &$g){
                    $stockout_totalprice += $g['goods_totalprice'];
                    if($d['goods_id'] == $g['goods_id']) {
                        $this->ajaxReturn(1, "订单中已有该商品");
                        /*
                        if($d['goods_pack_size'] !== $g['goods_pack_size']){
                            $this->ajaxReturn(1, "订单中已有该商品,但装箱数量为" . $g['goods_pack_size']);
                        }
                        if($d['goods_unitprice'] !== $g['goods_unitprice']){
                            $this->ajaxReturn(1, "订单中已有该商品，但单价为". $g['goods_unitprice']);
                        }
                        $g['goods_pack_num'] += $d['goods_pack_num'];
                        $g['goods_totalprice'] += $d['goods_totalprice'];
                        $g['goods_note'] .= $d['goods_note'];
                        $ishas = true;
                         */
                    }
                    // format the price to 2 decimals
                    $g['goods_unitprice'] = number_format($g['goods_unitprice'], 2, '.', '');
                    $g['goods_totalprice'] = number_format($g['goods_totalprice'], 2, '.', '');
                }
            }
            $stockout_totalprice = number_format($stockout_totalprice, 2, '.', '');
            $infos['index']['stockout_totalprice'] = $stockout_totalprice;
            if( !$ishas ){
                $infos['detail'][] = $d;
            }
            $this->ajaxReturn();
        case 'au':
            switch($url['autype']){
            case 'list'://商品列表
                $minLength = isset($_GET['minLength']) ? $_GET['minLength'] : 0;
                $prefix = isset($_GET['prefix']) ? $_GET['prefix'] : '';
                $rsp = new stdclass();
                $rows = null;
                if(!($minLength < 0 
                    || count($prefix) < $minLength)){
                        $gObj = base_mAPI::get("m_goods");
                        $rows = $gObj->select("goods_sn like '{$prefix}%'", '', '','ORDER BY goods_sn ASC')->items;
                    }
                $rsp->rows = $rows ? $rows : array();
                $this->ajaxReturn('', '', $rsp);
            case 'ginfo'://一件商品详细信息
                $goods_id = isset($_GET['goods_id']) ? $_GET['goods_id'] : '';
                $rsp = new stdclass();
                if($goods_id) {
                    $gObj = base_mAPI::get("m_goods", $goods_id);
                    $rows = $gObj->get();
                } else{
                }
                $rsp = $rows ? $rows : array();
                $this->ajaxReturn('','',$rsp);
            case 'cus'://客户列表
                $minLength = isset($_GET['minLength']) ? $_GET['minLength'] : 0;
                $prefix = isset($_GET['prefix']) ? $_GET['prefix'] : '';
                $rows = null;
                if($minLength >= 0 && $minLength <= count($prefix)) {
                    $cusObj = base_mAPI::get("m_customer");
                    $rows = $cusObj->select("customer_name like '{$prefix}%'", '', '', 'ORDER BY `customer_name` ASC')->items;
                }
                $rsp = new stdclass();
                $rsp->rows = $rows ? $rows : array();
                $this->ajaxReturn('','',$rsp);
            default:
                break;
            }
            return;
            case 'ajaxdelrow':
                for($i = count($infos['detail'])-1; $i >= 0; $i--){
                    if($infos['detail'][$i]['goods_id'] == $_POST['goods_id']) {
                        array_splice($infos['detail'], $i, 1);
                    }
                }
                $this->ajaxReturn();
            case 'ajaxeditrow':
                $d = &$_POST;
                $goods_id = isset($d['goods_id']) ? $d['goods_id'] : '';
                $flag = false;
                $rsp = new stdclass();
                foreach($infos['detail'] as &$g) {
                    if($g['goods_id'] == $d['goods_id']){
                        $gObj = base_mAPI::get('m_goods', $g['goods_id']);
                        $grs = $gObj->get();
                        $flag = true;
                        if(empty($d['goods_pack_num'])) {
                            //
                        } else if($d['goods_pack_num'] <= 0 
                            || intval($d['goods_pack_num']) != $d['goods_pack_num']) {
                                $this->ajaxReturn(1, '商品数量必须为大于0的整数！');
                            } else if($d['goods_pack_num'] > $grs['goods_stock']) {
                                $this->ajaxReturn(1, '商品数量不能超过库存！');
                            }


                        if(empty($d['goods_unitprice'])) {
                            //$this->ajaxReturn(1, '未设置商品单价！');
                        } else if($d['goods_unitprice'] < 0) {
                            $this->ajaxReturn(1, '商品单价不能小于0！');
                        }
                        $g['goods_pack_num'] = $d['goods_pack_num'];
                        $g['goods_unitprice'] = $d['goods_unitprice'];
                        $g['goods_totalprice'] = $d['goods_unitprice'] * $g['goods_pack_size'] * $d['goods_pack_num'];
                        $g['goods_note'] = $d['goods_note'];

                        $g['goods_unitprice'] = number_format($g['goods_unitprice'], 2, '.', '');
                        $g['goods_totalprice'] = number_format($g['goods_totalprice'], 2, '.', '');
                        $rsp->goods_totalprice = $g['goods_totalprice'];
                        $rsp->goods_stock = $grs['goods_stock'];
                        break;
                    }
                }
                if(!$flag){
                    $this->ajaxReturn(1, '商品不在出库列表中!');
                } 
                $this->ajaxReturn('', '', $rsp);

            case 'ajaxclr':
                $infos['index'] = null;
                $infos['detail'] = null;
                $this->ajaxReturn();
            case 'ajaxgetlist':
                $result = &$infos['detail'];
                $count = count($result);
                $start = 0;
                $rsp->records = $count;
                for($i = 0; $i < $count; $i++) {
                    $row = &$result[$start+$i];
                    $rsp->rows[$i]['id'] = $row['goods_id'];
                    $gObj = base_mAPI::get('m_goods', $row['goods_id']);
                    $grs = $gObj->getData('goods_stock');
                    if($grs === false) {
                        $grs = '?';
                    }
                    $row['goods_stock'] = $grs;
                    $rsp->rows[$i]['cell'] = array(
                        '',
                        $row['goods_sn'],
                        $row['goods_name_chn'] . '<br>' . $row['goods_name_tha'],
                        $row['goods_pack_num'],
                        $row['goods_stock'],
                        $row['goods_pack_size'],
                        $row['goods_unitprice'],
                        $row['goods_totalprice'],
                        $row['goods_note']
                    );
                }
                $this->ajaxReturn('','',$rsp);
            case 'ajaxout':
                $infos['index']['customer_name'] = base_Utils::getStr($_POST['customer_name']);
                $infos['index']['stockout_note'] = base_Utils::getStr($_POST['stockout_note']);
                $idxObj = new m_stockoutindex();
                $res = $idxObj->create($infos['index'], $infos['detail']);
                if($res){
                    $infos['index'] = array();
                    $infos['detail'] = array();
                    $stockout_sn = $res;

                    $rsp = new stdclass();
                    $rsp->stockout_sn = $stockout_sn;
                    $this->ajaxReturn('','',$rsp);
                } else {
                    //出库失败
                    $this->ajaxReturn(9876, $idxObj->getError());
                    //$this->ShowMsg("出库失败: " . $idxObj->getError());
                }
            case 'print':
                $idxObj = base_mAPI::get('m_stockoutindex', $stockout_sn);
                $idxRs = $idxObj->get();
                if(!$idxRs) {
                    $this->ShowMsg('打印失败：出库单编号异常');
                }
                $detObj = base_mAPI::get('m_stockoutdetail');
                $detRs = $detObj->getBySn($stockout_sn);
                if($detRs === false) {
                    $this->ShowMsg('打印失败：' . $detObj->getError());
                }
                foreach($detRs as &$g) {
                    $g['goods_unitprice'] = number_format($g['goods_unitprice'], 2, '.', '');
                    $g['goods_totalprice'] = number_format($g['goods_totalprice'], 2, '.', '');
                }
                $idxRs['stockout_totalprice'] = number_format($idxRs['stockout_totalprice'], 2, '.', '');
                $this->params['index'] = $idxRs;
                $this->params['detail'] = $detRs;
                $this->params['print_type'] = '出库单';
                $this->params['print_sn'] = $stockout_sn;
                break;
            case 'ajaxaddrow':
            default:
                break;
        }

        switch($this->params['ac']){
        case 'ajaxaddrow':
            return $this->render('stockout/addchoose.html', $this->params);
        case 'print':
            return $this->render('stockout/print.html', $this->params);
        default:
            return $this->render ( 'stockout/add.html', $this->params );
        }
    }
}
