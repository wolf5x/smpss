<?php

/**
 * 销售管理
 * @author 齐迹  email:smpss2012@gmail.com
 *
 */
class c_stockback extends base_c {

    function __construct($inPath) {
        parent::__construct();
        if (self::isLogin() === false) {
            $this->ShowMsg("请先登录！", $this->createUrl("/main/index"));
        }
        if (self::checkRights($inPath) === false) {
            //$this->ShowMsg("您无权操作！",$this->createUrl("/system/index"));
        }
        $this->params ['inpath'] = $inPath;
        $this->params ['head_title'] = "退货管理-" . $this->params ['head_title'];
    }

    function pageindex($inPath) {
        //$str = "%abcde|你好%%defg% \nhaha %hi_jk|我不\n h_ao %";

        $url = $this->getUrlParams($inPath);
        $goods_id = !empty($url ['gid']) ? $url ['gid'] : $_POST ['goods_id'];
        $stockback_sn = !empty($url ['sid']) ? $url['sid'] : $_POST['stockback_sn'];
        $ac = !empty($url['ac']) ? $url['ac'] : (!empty($_POST['ac']) ? $_POST['ac'] : '');
        $ac = trim($ac);
        $ac = !empty($ac) ? $ac : 'list';
        $this->params['ac'] = $ac;

        switch ($ac) {
            case 'detail':
                if (empty($stockback_sn)) {
                    $this->ShowMsg('退货单编号不能为空！');
                }
                $idxObj = base_mAPI::get('m_stockbackindex', $stockback_sn);
                $idxRs = $idxObj->get();
                if (!$idxRs) {
                    $this->ShowMsg('错误：' . $idxObj->getError());
                }
                $idxRs['stockback_totalprice'] = number_format($idxRs['stockback_totalprice'], 2, '.', '');
                $detObj = base_mAPI::get('m_stockbackdetail');
                $detRs = $detObj->getBySn($stockback_sn);
                if (!$detRs) {
                    $this->ShowMsg('错误：' . $detObj->getError());
                }
                $this->params['index'] = $idxRs;
                $this->params['detail'] = $detRs;

                return $this->render('stockback/detail.html', $this->params);
                break;
            case 'del':
                if (empty($stockback_sn)) {
                    $this->ShowMsg('退货单编号不能为空！');
                }
                $idxObj = base_mAPI::get('m_stockbackindex', $stockback_sn);
                $res = $idxObj->deleteOne($stockback_sn);
                if (!$res) {
                    $this->ShowMsg('删除错误：' . $idxObj->getError());
                }
                unset($this->params['ac']);

            case'list':
            default:
                $page = !empty($url ['page']) ? (int) $url ['page'] : 1;
                $soutIdx = new m_stockbackindex ();
                if ($_POST) {
                    $subFilter = isset($_POST['filter']);
                    $subExport = isset($_POST['export']);
                    if ($subFilter && $subExport) {
                        $this->ShowMsg("呵呵");
                    }

                    $key['customer_name'] = base_Utils::getStr($_POST ['key_cname']);
                    $key['stockback_sn'] = base_Utils::getStr($_POST ['key_ssn']);
                    $key['goods_sn'] = base_Utils::getStr($_POST ['key_gsn']);
                    $key['goods_name'] = base_Utils::getStr($_POST ['key_name']);
                    $key['date_start'] = base_Utils::getStr($_POST['date_start']);
                    $key['date_end'] = base_Utils::getStr($_POST['date_end']);
                }

                if ($subExport) {
                    $soutDet = base_mAPI::get('m_stockbackdetail');
                    if (!$soutDet->exportByCondition($key)) {
                        $this->ShowMsg('导出失败:' . $soutDet->getError());
                    }
                    return;
                }

                $this->params ['key'] = $key;

                $soutIdx->setCount(true);
                $soutIdx->setPage($page);
                $soutIdx->setLimit(base_Constant::PAGE_SIZE);
                $stockback = $soutIdx->getByCondition($key);

                $this->params['stockback'] = $stockback->items;
                $this->params ['pagebar'] = $this->PageBar($stockback->totalSize, base_Constant::PAGE_SIZE, $page, $inPath);
                return $this->render('stockback/index.html', $this->params);
        }
    }

    function pagestockback($inPath) {
        $url = $this->getUrlParams($inPath);
        $stockback_sn = !empty($url ['sid']) ? $url['sid'] : $_POST['stockback_sn'];
        $ac = isset($url['ac']) ? $url['ac'] : (isset($_POST['ac']) ? $_POST['ac'] : '');
        $this->params['ac'] = $ac;

        session_start();
        $order_id = $_SESSION ['order_id'];
        if (!$order_id) {
            $order_id = date("mdHis", time()) . base_Utils::random(4, 1);
            $_SESSION ['order_id'] = $order_id;
        }
        $infos = &$_SESSION['infos']['stockback'];

        switch ($ac) {
            case 'ajaxaddyes':
                $rsp = new stdclass();
                $data = &$_POST;
                $goodsObj = base_mAPI::get("m_goods");
                $grs = $goodsObj->getTheGoods($data['goods_sn'], $data['goods_name_chn'], $data['goods_name_tha']);
                $goods_id = $grs['goods_id'];
                if (!$goods_id) {
                    $this->ajaxReturn(1, "商品信息错误: " . $goodsObj->getError());
                }
                $d['goods_id'] = $goods_id;
                $d['goods_sn'] = $data ['goods_sn'];
                $d['goods_name_tha'] = $data ['goods_name_tha'];
                $d['goods_name_chn'] = $data ['goods_name_chn'];
                $d['goods_pack_num'] = $data['goods_pack_num'];
                $d['goods_pack_size'] = $data ['goods_pack_size'];
                //	$d['goods_unitprice'] = $data ['goods_unitprice'];
                $d['goods_totalprice'] = $data ['goods_totalprice'];
                $d['goods_note'] = $data['goods_note'];

                //	$d['goods_unitprice'] = number_format($d['goods_unitprice'], 2, '.', '');
                $d['goods_totalprice'] = number_format($d['goods_totalprice'], 2, '.', '');

                if ($d['goods_sn'] != $grs['goods_sn'] || $d['goods_name_chn'] != $grs['goods_name_chn'] || $d['goods_name_tha'] != $grs['goods_name_tha'] || $d['goods_pack_size'] != $grs['goods_pack_size']) {
                    $this->ajaxReturn(1, '商品信息非法！');
                }

                if (empty($d['goods_pack_num'])) {
                    //
                } else if ($d['goods_pack_num'] <= 0 || intval($d['goods_pack_num']) != $d['goods_pack_num']) {
                    $this->ajaxReturn(1, '商品数量必须为大于0的整数！');
                }

                /* if(empty($d['goods_unitprice'])){
                  //$this->ajaxReturn(1, '未设置商品单价！');
                  //return;
                  } else if($d['goods_unitprice'] < 0) {
                  $this->ajaxReturn(1, '商品单价不能小于0！');
                  return;
                  } */

                /* $tt = round((float)$d['goods_pack_num'] * $d['goods_pack_size'] * $d['goods_unitprice'], 2);

                  if($tt != $d['goods_totalprice'])
                  {
                  $this->ajaxReturn(1, '商品总价非法！');
                  return;
                  } */

                $ishas = false;
                $stockback_totalprice = $d['goods_totalprice'];
                if ($infos['detail']) {
                    foreach ($infos['detail'] as &$g) {
                        $stockback_totalprice += $g['goods_totalprice'];
                        if ($d['goods_id'] == $g['goods_id']) {
                            $this->ajaxReturn(1, "退货单中已有该商品");
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
                        //		$g['goods_unitprice'] = number_format($g['goods_unitprice'], 2, '.', '');
                        $g['goods_totalprice'] = number_format($g['goods_totalprice'], 2, '.', '');
                    }
                }
                $stockback_totalprice = number_format($stockback_totalprice, 2, '.', '');
                $infos['index']['stockback_totalprice'] = $stockback_totalprice;
                if (!$ishas) {
                    $infos['detail'][] = $d;
                }
                $this->ajaxReturn();
            case 'au':
                switch ($url['autype']) {
                    case 'list'://商品列表
                        $minLength = isset($_GET['minLength']) ? $_GET['minLength'] : 0;
                        $prefix = isset($_GET['prefix']) ? $_GET['prefix'] : '';
                        $rows = null;
                        if (!($minLength < 0 || count($prefix) < $minLength)) {
                            $gObj = base_mAPI::get("m_goods");
                            $rows = $gObj->select("goods_sn like '{$prefix}%'", '', '', 'ORDER BY goods_sn ASC')->items;
                        }
                        $rsp = array('rows' => $rows ? $rows : array());
                        $this->ajaxReturn(0, '', $rsp);
                    case 'ginfo'://一件商品详细信息
                        $goods_id = isset($_GET['goods_id']) ? $_GET['goods_id'] : '';
                        if ($goods_id) {
                            $gObj = base_mAPI::get("m_goods", $goods_id);
                            $rows = $gObj->get();
                        } else {
                        }
                        $rsp = $rows ? $rows : array();
                        $this->ajaxReturn(0, '', $rsp);
                    case 'cus'://客户列表
                        $minLength = isset($_GET['minLength']) ? $_GET['minLength'] : 0;
                        $prefix = isset($_GET['prefix']) ? $_GET['prefix'] : '';
                        $rows = null;
                        if ($minLength >= 0 && $minLength <= count($prefix)) {
                            $cusObj = base_mAPI::get("m_customer");
                            $rows = $cusObj->select("customer_name like '{$prefix}%'", '', '', 'ORDER BY `customer_name` ASC')->items;
                        }
                        $rsp = array('rows' => $rows ? $rows : array());
                        $this->ajaxReturn(0, '', $rsp);
                    default:
                        break;
                }
                return;
            case 'ajaxdelrow':
                for ($i = count($infos['detail']) - 1; $i >= 0; $i--) {
                    if ($infos['detail'][$i]['goods_id'] == $_POST['goods_id']) {
                        array_splice($infos['detail'], $i, 1);
                    }
                }
                $this->ajaxReturn();
            case 'ajaxeditrow':
                $d = &$_POST;
                $goods_id = isset($d['goods_id']) ? $d['goods_id'] : '';
                $flag = false;
                foreach ($infos['detail'] as &$g) {
                    if ($g['goods_id'] == $d['goods_id']) {
                        $gObj = base_mAPI::get('m_goods', $g['goods_id']);
                        $grs = $gObj->get();
                        $flag = true;
                        if (empty($d['goods_pack_num'])) {
                            //
                        } else if ($d['goods_pack_num'] <= 0 || intval($d['goods_pack_num']) != $d['goods_pack_num']) {
                            $this->ajaxReturn(1, '商品数量必须为大于0的整数！');
                        } else if ($d['goods_pack_num'] > $grs['goods_stock']) {
                            //$this->ajaxReturn(1, '商品数量不能超过库存！');
                        }
                        $g['goods_pack_num'] = $d['goods_pack_num'];
                        //	$g['goods_unitprice'] = $d['goods_unitprice'];
                        $g['goods_totalprice'] = $d['goods_totalprice']; // * $g['goods_pack_size'] * $d['goods_pack_num'];
                        $g['goods_note'] = $d['goods_note'];
                        $g['goods_totalprice'] = number_format($g['goods_totalprice'], 2, '.', '');
                        $rsp = array( 'goods_totalprice' => $g['goods_totalprice'],
                            'goods_stock' => $grs['goods_stock']);
                        break;
                    }
                }
                if (!$flag) {
                    $this->ajaxReturn(1, '商品不在退货列表中!');
                }
                $this->ajaxReturn(0, '', $rsp);

            case 'ajaxclr':
                $infos['index'] = null;
                $infos['detail'] = null;
                $this->ajaxReturn();
            case 'ajaxgetlist':
                $result = &$infos['detail'];
                $count = count($result);
                $start = 0;
                $rsp = array();
                $rsp['records'] = $count;
                for ($i = 0; $i < $count; $i++) {
                    $row = &$result[$start + $i];
                    $rsp['rows'][$i]['id'] = $row['goods_id'];
                    $gObj = base_mAPI::get('m_goods', $row['goods_id']);
                    $grs = $gObj->getData('goods_stock');
                    if ($grs === false) {
                        $grs = '?';
                    }
                    $row['goods_stock'] = $grs;
                    $rsp['rows'][$i]['cell'] = array(
                        '',
                        $row['goods_sn'],
                        $row['goods_name_chn'] . '<br>' . $row['goods_name_tha'],
                        $row['goods_pack_num'],
                        $row['goods_stock'],
                        $row['goods_pack_size'],
                        //$row['goods_unitprice'],
                        $row['goods_totalprice'],
                        $row['goods_note']
                    );
                }
                $this->ajaxReturn(0, '', $rsp);
            case 'ajaxout':
                $infos['index']['customer_name'] = base_Utils::getStr($_POST['customer_name']);
                $infos['index']['stockback_note'] = base_Utils::getStr($_POST['stockback_note']);
                $idxObj = new m_stockbackindex();
                $res = $idxObj->create($infos['index'], $infos['detail']);
                if ($res) {
                    unset($infos['index']);
                    unset($infos['detail']);
                    $stockback_sn = $res;
                    $rsp = array('stockback_sn' => $stockback_sn);
                    $this->ajaxReturn(0, '', $rsp);
                } else {
                    //退货失败
                    $this->ajaxReturn(1, $idxObj->getError());
                }
            case 'print':
                $idxObj = base_mAPI::get('m_stockbackindex', $stockback_sn);
                $idxRs = $idxObj->get();
                if (!$idxRs) {
                    $this->ShowMsg('打印失败：退货单编号异常');
                }
                $detObj = base_mAPI::get('m_stockbackdetail');
                $detRs = $detObj->getBySn($stockback_sn);
                if ($detRs === false) {
                    $this->ShowMsg('打印失败：' . $detObj->getError());
                }
                foreach ($detRs as &$g) {
                    //		$g['goods_unitprice'] = number_format($g['goods_unitprice'], 2, '.', '');
                    $g['goods_totalprice'] = number_format($g['goods_totalprice'], 2, '.', '');
                }
                $idxRs['stockback_totalprice'] = number_format($idxRs['stockback_totalprice'], 2, '.', '');

                $this->params['index'] = $idxRs;
                $this->params['detail'] = $detRs;
                $this->params['print_type'] = '退货单';
                $this->params['print_sn'] = $stockback_sn;
                break;
            case 'ajaxaddrow':
            default:
                break;
        }

        switch ($this->params['ac']) {
            case 'ajaxaddrow':
                return $this->render('stockback/addchoose.html', $this->params);
            case 'print':
                return $this->render('stockback/print.html', $this->params);
            default:
                return $this->render('stockback/add.html', $this->params);
        }
    }

}
