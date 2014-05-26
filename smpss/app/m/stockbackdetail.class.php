<?php

/**
 * 库存管理模型 
 * @author wolf5x
 */
class m_stockbackdetail extends base_m {

    public function primarykey() {
        return 'stockback_detail_id';
    }

    public function tableName() {
        return base_Constant::TABLE_PREFIX . 'stockback_detail';
    }

    public function relations() {
        return array();
    }

    public function create($data, $upd = true) {
        //数据检查由stockback_index作
        if (!$data) {
            $this->setError(0, '出库列表不能为空');
            return false;
        }

        $this->setPkid(false);
        $this->set('stockback_sn', $data['stockback_sn']);
        $this->set('goods_sn', $data['goods_sn']);
        $this->set('goods_name_chn', $data['goods_name_chn']);
        $this->set('goods_name_tha', $data['goods_name_tha']);
        $this->set('goods_pack_num', $data['goods_pack_num']);
        $this->set('goods_pack_size', $data['goods_pack_size']);
        $this->set('goods_note', $data['goods_note']);
        //	$this->set('goods_unitprice', $data['goods_unitprice']);
        $this->set('goods_totalprice', $data['goods_totalprice']);
        $this->set('stockback_opttime', $data['stockback_opttime']);
        $lastInsertId = $this->save();

        if ($lastInsertId === false) {
            $this->setError(0, '写入出库明细失败：' . $this->getDbError());
            return false;
        }

        $goodsObj = base_mAPI::get("m_goods");
        $res = $goodsObj->setStock($data['goods_id'], $data['goods_pack_num']);
        if (!$res) {
            $this->set('isdel', 1);
            $this->save($lastInsertId);
            $this->setError(0, '更新商品库存失败：' . $goodsObj->getError());
            return false;
        }

        return true;
    }

    public function delBySn($stockback_sn, $upd = true) {
        if (!$stockback_sn) {
            $this->setError(0, '出库单编号不能为空');
            return false;
        }
        $srs = $this->getBySn($stockback_sn);
        if (!$srs) {
            return true;
        }
        $goodsObj = base_mAPI::get('m_goods');
        foreach ($srs as $g) {
            $grs = $goodsObj->getTheGoods($g['goods_sn'], $g['goods_name_chn'], $g['goods_name_tha']);
            if (!$grs) {
                continue;
            }
            if ($upd) {
                $res = $goodsObj->setStock($grs['goods_id'], -$g['goods_pack_num']);
                if (!$res) {
                    $this->setError(0, '恢复商品库存失败: ' . $goodsObj->getError());
                    return false;
                }
            }
            $res = $this->update(
                    array(
                'stockback_sn' => $stockback_sn,
                'goods_sn' => $g['goods_sn'],
                'goods_name_chn' => $g['goods_name_chn'],
                'goods_name_tha' => $g['goods_name_tha']
                    ), array('isdel' => '1')
            );
            if ($res === null || $res === false) {
                $this->setError(0, '删除明细条目失败：' . $this->getDbError());
                return false;
            }
        }
        return true;
    }

    public function getByCondition($conds, $nodel = true, $isjoin = false) {
        if ($nodel !== false) {
            $nodel = true;
        }

        $idxObj = base_mAPI::get('m_stockbackindex');
        $tblDet = $this->tableName();
        $tblIdx = $idxObj->tableName();

        if (is_string($conds)) {
            $condition = $nodel ? "{$tblDet}.isdel=0" : '';
            if ($conds) {
                $condition .= ($condition ? ' and ' : '') . $conds;
            }
        } else if (is_array($conds)) {
            $detCond = $nodel ? "{$tblDet}.isdel=0" : 'true';
            $idxCond = '';

            // stockbackindex 
            $tk = base_Utils::getStr($conds ['customer_name']);
            if (!empty($tk)) {
                $idxCond .= " and {$tblIdx}.customer_name='{$tk}'";
            }

            $tk = base_Utils::getStr($conds ['stockback_sn']);
            if (!empty($tk)) {
                $idxCond .= " and {$tblIdx}.stockback_sn='{$tk}'";
            }

            $tk = base_Utils::getStr($conds['date_start']);
            $dk = explode('-', $tk);
            if (count($dk) == 3 && checkdate($dk[1], $dk[2], (integer) $dk[0])) {
                $idxCond .= " and {$tblIdx}.stockback_opttime>='{$tk}'";
            }

            $tk = base_Utils::getStr($conds['date_end']);
            $dk = explode('-', $tk);
            if (count($dk) == 3 && checkdate($dk[1], $dk[2], (integer) $dk[0])) {
                $idxCond .= " and {$tblIdx}.stockback_opttime<'{$tk}' + INTERVAL 1 DAY";
            }

            // stockbackdetail
            $tk = base_Utils::getStr($conds ['goods_sn']);
            if (!empty($tk)) {
                $detCond .= " and {$tblDet}.goods_sn='{$tk}'";
            }

            $tk = base_Utils::getStr($conds ['goods_name']);
            if (!empty($tk)) {
                $detCond .= " and ({$tblDet}.goods_name_chn='{$tk}' or {$tblDet}.goods_name_tha='{$tk}')";
            }

            $tk = base_Utils::getStr($conds ['goods_name_chn']);
            if (!empty($tk)) {
                $detCond .= " and {$tblDet}.goods_name_chn='{$tk}'";
            }

            $tk = base_Utils::getStr($conds ['goods_name_tha']);
            if (!empty($tk)) {
                $detCond .= " and {$tblDet}.goods_name_tha='{$tk}'";
            }

            // final condition
            $condition = $detCond;
            //if($idxCond){
            $idxCond = ($nodel ? "{$tblIdx}.isdel=0" : 'true') . $idxCond;
            $in = "SELECT DISTINCT({$tblIdx}.stockback_sn) FROM {$tblIdx} WHERE {$idxCond}";
            $condition .= " AND {$tblDet}.stockback_sn IN ({$in})";
            //}
        } else {
            $this->setError(0, '筛选条件参数无效！');
            return false;
        }

        if ($isjoin) { // TODO
            $join = array(
                $idxObj->tableName() => "{$tblDet}.stockback_sn={$tblIdx}.stockback_sn"
            );
        } else {
            $join = '';
        }

        $rs = $this->select($condition, '', '', '', $join);
        if (!$rs) {
            $this->setError(0, '筛选失败: ' . $this->getDbError());
            return false;
        }

        return $rs;
    }

    public function packNumByCondition($conds, $nodel = true) {

        $rs = $this->getByCondition($conds, $nodel);
        if ($rs === false) {
            return false;
        }
        $sum = 0;
        //print_r($rs);
        foreach ((array) $rs->items as $g) {
            $sum += $g['goods_pack_num'];
        }
        return $sum;
    }

    public function getBySn($stockback_sn, $nodel = true) {
        if (empty($stockback_sn)) {
            $this->setError(0, '出库单编号不能为空');
            return false;
        }
        $condition = $nodel ? 'isdel=0' : '';
        $condition .= ($condition ? ' and ' : '') . "stockback_sn={$stockback_sn}";
        $rs = $this->select($condition);
        if (!$rs) {
            $this->setError(0, '读取出库单明细出错: ' . $this->getDbError());
            return false;
        }
        return $rs->items;
    }

    public function exportByCondition($conds) {
        // TODO 用group by, 合并index和detail的数据
        $rs = $this->getByCondition($conds, '', true);
        if ($rs === false) {
            $this->setError(0, '筛选记录出错!');
            return false;
        }
        $items = $rs->items;

        $cfgObj = base_mAPI::get('m_config');
        $tpl = $cfgObj->getValue('export_stockback_template');
        if (!$tpl) {
            $tpl = $cfgObj->getValue('export_stockback_template_default');
        }

        $ptn = '%(?P<key>\w+)(\|(?P<comment>[^\%]+))?%';
        $err = preg_match_all($ptn, $tpl, $mat);
        if (!$err) {
            $this->setError(0, '导出模板不正确,请到系统配置中设置正确的模板!');
            return false;
        }

        require_once(PHPEXCEL_CLASS);
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("")
                ->setLastModifiedBy("")
                ->setTitle("导出退货单")
                ->setSubject("")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory("");

        // Add some data
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        // 标题名称, 数据表中列名, excel单元格格式描述
        $cols = array(
            'stockback_sn' => array("退货单编号", 'stockback_sn', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'customer_name' => array("顾客名称", 'customer_name', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_sn' => array("商品编码", 'goods_sn', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_name_chn' => array("商品名称(中)", 'goods_name_chn', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_name_tha' => array("商品名称(泰)", 'goods_name_tha', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_pack_num' => array("出库数量(件)", 'goods_pack_num', PHPExcel_Style_NumberFormat::FORMAT_NUMBER),
            'goods_pack_size' => array("装箱数量", 'goods_pack_size', PHPExcel_Style_NumberFormat::FORMAT_NUMBER),
            //	'goods_unitprice' => array("单价", 'goods_unitprice', PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00),
            'goods_totalprice' => array("总价", 'goods_totalprice', PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00),
            'goods_note' => array("备注", 'goods_note', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'stockback_opttime' => array("出库日期", 'stockback_opttime', 'yyyy-mm-dd hh:mm:ss'),
            'customer_name' => array("顾客名称", 'customer_name', PHPExcel_Style_NumberFormat::FORMAT_TEXT)
        );

        //var_dump($mat['key']);
        //var_dump($mat['comment']);
        $colCount = count($mat['key']);

        for ($i = 0; $i < $colCount; $i++) {
            if (!isset($cols[$mat['key'][$i]])) {
                $this->setError(0, '导入模板错误,不正确的参数 ' . $mat['key'][$i]);
                return false;
            }
        }

        for ($i = 0; $i < $colCount; $i++) {
            $colName = PHPExcel_Cell::stringFromColumnIndex($i);
            $sheet->setCellValue($colName . '1', $mat['comment'][$i]);
            $sheet->getColumnDimension($colName)->setAutoSize(true);
        }
        for ($i = 0, $l = count($items); $i < $l; $i++) {
            //Convert mysql date string to excel time object
            $items[$i]['stockback_opttime'] = PHPExcel_Shared_Date::StringToExcel($items[$i]['stockback_opttime']);
            for ($j = 0; $j < $colCount; $j++) {
                $cellName = sprintf("%s%d", PHPExcel_Cell::stringFromColumnIndex($j), $i + 2);
                $sheet->setCellValue($cellName, $items[$i][$mat['key'][$j]]);
                $sheet->getStyle($cellName)->getNumberFormat()->setFormatCode($cols[$mat['key'][$j]][2]);
            }
        }

        // Rename worksheet
        $sheet->setTitle('退货单明细');

        // Redirect output to a client’s web browser (Excel5)
        $xlsFilename = "stockback_export_" . date("Ymdhis") . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$xlsFilename}'");
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

}
