<?php

/**
 * 进货管理
 * @author 齐迹  email:smpss2012@gmail.com
 *
 */
class c_stockin extends base_c {

    function __construct($inPath) {
        parent::__construct();
        if (self::isLogin() === false) {
            $this->ShowMsg("请先登录！", $this->createUrl("/main/index"));
        }
        if (self::checkRights($inPath) === false) {
            //$this->ShowMsg("您无权操作！",$this->createUrl("/system/index"));
        }
        $this->params ['inpath'] = $inPath;
        $this->params ['head_title'] = "入库管理-" . $this->params ['head_title'];
    }

    function pageindex($inPath) {
        $ac = !empty($url['ac']) ? $url['ac'] : (!empty($_POST['ac']) ? $_POST['ac'] : 'idx');
        $url = $this->getUrlParams($inPath);
        $page = !empty($url ['page']) ? (int) $url ['page'] : 1;
        $this->params['ac'] = $ac;

        switch ($ac) {
            case 'impch':
                $this->params['ac'] = 'import';
            case 'import':
                if ($ac == 'import' && $_POST) {
                    if (!isset($_FILES['import_file']) || !isset($_FILES['import_file']['name'])) {
                        $this->ShowMsg('请选择要导入的.xls文件!');
                    }
                    $inObj = base_mAPI::get('m_stockin');
                    $res = $inObj->importFromFile($_FILES['import_file']);
                    if ($res === false) {
                        $this->ShowMsg('导入失败: ' . $inObj->getError());
                    }
                    return;
                }
                break;
            default :
                $stockinObj = new m_stockin ();
                $condition = "isdel=0";
                if ($_POST) {
                    $subFilter = isset($_POST['filter']);
                    $subExport = isset($_POST['export']);
                    $subImport = isset($_POST['import']);
                    if ($subFilter + $subExport + $subImport > 1) {
                        $this->ShowMsg("呵呵");
                    }

                    if ($subImport) {
                        $this->params['ac'] = "impch";
                        break;
                    }

                    $key['ssn'] = base_Utils::getStr($_POST ['key_ssn']);
                    $key['gsn'] = base_Utils::getStr($_POST ['key_gsn']);
                    $key['name'] = base_Utils::getStr($_POST ['key_name']);
                    $key['date_start'] = base_Utils::getStr($_POST['date_start']);
                    $key['date_end'] = base_Utils::getStr($_POST['date_end']);

                    $this->params ['key'] = $key;
                }
                // export to excel
                if ($subExport) {
                    if (!$stockinObj->exportByCondition($key)) {
                        $this->ShowMsg('导出错误:' . $stockinObj->getError());
                        return false;
                    }
                    $this->ShowMsg('功能暂未开放');
                }

                $stockinObj->setCount(true);
                $stockinObj->setPage($page);
                $stockinObj->setLimit(base_Constant::PAGE_SIZE);
                $stockin = $stockinObj->getByCondition($key);
                if ($stockin === false) {
                    $this->ShowMsg($stockinObj->getError());
                }

                $this->params ['stockin'] = $stockin->items;
                $this->params ['pagebar'] = $this->PageBar($stockin->totalSize, base_Constant::PAGE_SIZE, $page, $inPath);
        }

        switch ($this->params['ac']) {
            case 'impch':
            case 'import':
                return $this->render('stockin/import.html', $this->params);
                break;
            default:
                return $this->render('stockin/index.html', $this->params);
                break;
        }
    }

    function pagestockin($inPath) {
        $url = $this->getUrlParams($inPath);
        $goods_id = !empty($url ['gid']) ? $url ['gid'] : $_POST ['goods_id'];
        $stockin_sn = !empty($url ['sid']) ? $url['sid'] : $_POST['stockin_sn'];
        $ac = !empty($url['ac']) ? $url['ac'] : (!empty($_POST['ac']) ? $_POST['ac'] : 'add');
        $this->params['ac'] = $ac;
        switch ($ac) {
            case "add":
                if ($goods_id) {
                    $goodsObj = base_mAPI::get("m_goods", $goods_id);
                    $goodsRs = $goodsObj->get();
                    if (!$goodsRs) {
                        $this->ShowMsg("商品信息错误: " . $goodsObj->getError());
                    }
                    $this->params['goods'] = $goodsRs;
                    $this->params['ac'] = 'addyes';
                    break;
                }
                $this->params['ac'] = 'addch';
            case "addch":
                if ($ac == "addch") {
                    $goodsObj = base_mAPI::get("m_goods");
                    $goodsRs = $goodsObj->getTheGoods($_POST['goods_sn'], $_POST['goods_name_chn'], $_POST['goods_name_tha']);
                    if (!$goodsRs) {
                        $this->ShowMsg("商品信息错误: " . $goodsObj->getError());
                    }
                    $this->params['goods'] = $goodsRs;
                    $this->params['ac'] = 'addyes';
                }
                break;
            case "addyes" :
                $stockinObj = base_mAPI::get("m_stockin");
                $goodsObj = base_mAPI::get("m_goods", $goods_id);
                $grs = $goodsObj->get();
                if (!$grs) {
                    $this->ShowMsg("商品信息错误: " . $goodsObj->getError());
                }
                if ($ac == 'addyes') {
                    unset($data['stockin_sn']);
                    $data ['goods_id'] = $goods_id;
                    $data ['goods_sn'] = $_POST ['goods_sn'];
                    $data ['goods_name_tha'] = $_POST ['goods_name_tha'];
                    $data ['goods_name_chn'] = $_POST ['goods_name_chn'];
                    $data ['goods_pack_num'] = $_POST['goods_pack_num'];
                    $data ['goods_pack_size'] = $_POST ['goods_pack_size'];
                    $data ['stockin_note'] = $_POST['stockin_note'];
                    //$data ['stockin_opttime'];
                    //$data ['content'] = base_Utils::getStr ( $_POST ['content'] );
                    $op = "入库";
                    if ($stockinObj->create($data)) {
                        $this->ShowMsg($op . "成功！", $this->createUrl("/stockin/index"), 2, 1);
                    }
                    $this->ShowMsg($op . "出错！原因：" . $stockinObj->getError());
                }
                $this->params ['goods'] = $grs;
                break;
            case 'mod':
                $this->params['ac'] = 'modyes';
            case 'modyes':
                if (empty($stockin_sn)) {
                    $this->ShowMsg("修改出错: 入库单编号不能为空! ");
                }
                $stockinObj = base_mAPI::get("m_stockin", $stockin_sn);
                $srs = $stockinObj->get();
                if (!$srs) {
                    $this->ShowMsg("入库单编号无效!");
                }
                if ($ac == 'modyes') {
                    $goodsObj = base_mAPI::get("m_goods");
                    $grs = $goodsObj->getTheGoods($srs['goods_sn'], $srs['goods_name_chn'], $srs['goods_name_tha']);
                    $upd = !empty($grs);
                    $data = array();
                    $data['stockin_sn'] = $stockin_sn;
                    if ($upd) {
                        $data['goods_id'] = $grs['goods_id'];
                    }
                    $data['goods_sn'] = $_POST['goods_sn'];
                    $data ['goods_name_tha'] = $_POST ['goods_name_tha'];
                    $data ['goods_name_chn'] = $_POST ['goods_name_chn'];
                    $data ['goods_pack_num'] = $_POST['goods_pack_num'];
                    $data ['goods_pack_size'] = $_POST ['goods_pack_size'];
                    $data ['stockin_note'] = $_POST['stockin_note'];
                    $op = "修改入库单";
                    if ($stockinObj->create($data, $upd)) {
                        $this->ShowMsg($op . "成功！", $this->createUrl("/stockin/index"), 2, 1);
                    }
                    $this->ShowMsg($op . "出错！原因：" . $stockinObj->getError());
                }
                $this->params['goods'] = $srs;
                break;
            case 'print'://可打印页面
                break;
            case "del" :
                $stockinObj = base_mAPI::get("m_stockin", $stockin_sn);
                if ($stockin_sn) {
                    if ($stockinObj->deleteOne($stockin_sn)) {
                        $this->ShowMsg("删除成功！", $this->createUrl("/stockin/index"), 2, 1);
                    }
                    $this->ShowMsg("删除出错！原因：" . $stockinObj->getError());
                }
                $this->ShowMsg("删除出错: 入库单编号不能为空");
                break;
            default:
                return;
        }
        switch ($this->params['ac']) {
            case 'add':
            case 'addch':
                return $this->render('stockin/choose.html', $this->params);
                break;
            case 'mod':
            case 'modyes':
                return $this->render('stockin/mod.html', $this->params);
            case 'addyes':
            case 'del':
            default:
                return $this->render('stockin/add.html', $this->params);
                break;
        }
    }

}
