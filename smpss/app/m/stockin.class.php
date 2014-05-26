<?php

/**
 * 库存管理模型 
 * @author wolf5x
 */
class m_stockin extends base_m {

    public function primarykey() {
        return 'stockin_sn';
    }

    public function tableName() {
        return base_Constant::TABLE_PREFIX . 'stockin';
    }

    public function relations() {
        return array();
    }

    public function create($data, $upd = true) {
        $stockin_sn = $data['stockin_sn'];
        $is_create = empty($stockin_sn);

        if ($is_create) {
            if (!$data['goods_id']) {
                $this->setError(0, '商品编号不能为空');
                return false;
            }
            $goodsObj = base_mAPI::get('m_goods', $data['goods_id']);
            $rs = $goodsObj->get();
        } else {
            $snrs = $this->get();
            if ($snrs['stockin_sn'] != $stockin_sn) {
                $this->setError(0, "入库单编号非法");
                return false;
            }
            $goodsObj = base_mAPI::get('m_goods');
            $rs = $goodsObj->getTheGoods($data['goods_sn'], $data['goods_name_chn'], $data['goods_name_tha']);
        }
        if ($upd && !$rs) {
            $this->setError(0, '商品信息不存在');
            return false;
        }

        if ($rs['goods_sn'] != $data['goods_sn'] || $rs['goods_name_chn'] != $data['goods_name_chn'] || $rs['goods_name_tha'] != $data['goods_name_tha'] || $rs['goods_pack_size'] != $data['goods_pack_size']
        ) {
            $this->setError(0, "商品数据异常!");
            return false;
        }

        $goods_pack_num = (int) $data['goods_pack_num'];
        if ($goods_pack_num <= 0) {
            $this->setError(0, "入库数量必须大于0!");
            return false;
        }

        $stockin_opttime = date('YmdHis');
        $stockin_sn_flag = "0"; //必须和stockout,stockback的不同

        if ($is_create) {
            //临时数据表
            $ConstCountLength = 6;
            $configObj = base_mAPI::get("m_config");
            $last_stockin_sn = $configObj->getValue('last_stockin_sn');
            $now_date = substr($stockin_opttime, 0, 8);
            $last_date = substr($last_stockin_sn, 0, 8);
            $last_cnt = substr($last_stockin_sn, 8, $ConstCountLength);
            if (empty($last_stockin_sn) || $now_date != $last_date) {
                $stockin_sn = $now_date . $stockin_sn_flag . str_pad('1', $ConstCountLength - 1, '0', STR_PAD_LEFT);
            } else {
                $stockin_sn = $last_date . str_pad($last_cnt + 1, $ConstCountLength, '0', STR_PAD_LEFT);
            }
            if (!$configObj->setValue('last_stockin_sn', $stockin_sn)) {
                $this->setError(0, $configObj->getError());
                return false;
            }
        }

        //$content = $data ['content'] ? $data ['content'] : "增加入库：名称：{$rs ['goods_name']},数量：{$data ['in_num']}";
        $this->set("stockin_sn", $stockin_sn);
        $this->set("goods_sn", $data ['goods_sn']);
        $this->set("goods_name_chn", $data ['goods_name_chn']);
        $this->set("goods_name_tha", $data ['goods_name_tha']);
        $this->set("goods_pack_num", $goods_pack_num);
        $this->set("goods_pack_size", $data ['goods_pack_size']);
        $this->set("stockin_note", $data ['stockin_note']);
        $this->set("stockin_opttime", $stockin_opttime);
        if ($is_create) {
            $res = $this->save(false);
            if ($res) {
                if ($upd) {
                    $res = $goodsObj->setStock($rs['goods_id'], $goods_pack_num); //更新库存
                }
                if ($res) {
                    return true;
                }
                $this->set('isdel', 1);
                $this->save($stockin_sn);
                $this->setError(0, "更新商品库存失败:" . $goodsObj->getError());
                return false;
            }
            $this->setError(0, "写入入库单数据失败:" . $this->getError());
            return false;
        } else {
            if ($upd) {
                $res = $goodsObj->setStock($rs['goods_id'], $goods_pack_num - $snrs['goods_pack_num']);
                if (!$res) {
                    $this->setError(0, "更新商品库存失败:" . $goodsObj->getError());
                    return false;
                }
            }
            $res = $this->save($stockin_sn);
            if (!$res) {
                $this->setError(0, "写入入库单数据失败:" . $this->getError());
                return false;
            }
            return true;
        }
    }

    /**
     * 删除单个库存入库记录
     * @param string $id: stockin_sn
     * @praram $upd: update goods_stock
     */
    public function deleteOne($id, $upd = true) {
        if (!$id) {
            $this->setError(0, "入库单编号无效");
            return false;
        }
        $this->setPkid($id);
        $rs = $this->get();
        if (!$rs) {
            $this->setError(0, "入库单不存在");
            return false;
        }
        //删除当前行
        $this->set('isdel', 1);
        $res = $this->save();
        if ($res) {
            if ($upd) {
                $goodsObj = base_mAPI::get("m_goods");
                $goods = $goodsObj->getTheGoods($rs['goods_sn'], $rs['goods_name_chn'], $rs['goods_name_tha']);
                if (!$goods) {
                    return true;
                }
                // 修改库存
                if ($goodsObj->setStock($goods['goods_id'], -$rs['goods_pack_num'])) {
                    //$logObj = base_mAPI::get ( "m_log" );
                    //$logObj->create ( $rs ['goods_id'], "删除入库ID为 {$id} 的记录", 1 );
                    return true;
                } else {
                    $this->set('isdel', 0);
                    $this->save();
                    $this->setError(0, "更新商品库存失败:" . $goodsObj->getError());
                    return false;
                }
            }
        } else {
            $this->setError(0, "删除入库记录失败:" . $this->getError());
            return false;
        }
    }

    public function getByCondition($key, $nodel = true) {
        $condition = $nodel ? 'isdel=0' : 'true';

        if (!empty($key['ssn'])) {
            $condition .= " and stockin_sn='{$key['ssn']}'";
        }

        if (!empty($key['gsn'])) {
            $condition .= " and goods_sn='{$key['gsn']}'";
        }

        if (!empty($key['name'])) {
            $condition .= " and (goods_name_chn='{$key['name']}' or goods_name_tha='{$key['name']}')";
        }

        $k = explode('-', $key['date_start']);
        if (checkdate($k[1], $k[2], (integer) $k[0])) {
            $condition .= " and stockin_opttime>='{$key['date_start']}'";
        }

        $k = explode('-', $key['date_end']);
        if (checkdate($k[1], $k[2], (integer) $k[0])) {
            $condition .= " and stockin_opttime<'{$key['date_end']}' + INTERVAL 1 DAY";
        }
        //	echo "fuck".$condition;
        $rs = $this->select($condition, '', '', 'order by stockin_opttime desc');
        if ($rs === false) {
            $this->setError(0, '条件查询失败!');
            return false;
        }
        return $rs;
    }

    public function exportByCondition($conds) {
        $rs = $this->getByCondition($conds);
        if ($rs === false) {
            $this->setError(0, '查询出错:' . $this->getError());
            return false;
        }
        $items = $rs->items;

        $cfgObj = base_mAPI::get('m_config');
        $tpl = $cfgObj->getValue('export_stockin_template');
        if (!$tpl) {
            $tpl = $cfgObj->getValue('export_stockin_template_default');
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
                ->setTitle("导出入库单")
                ->setSubject("")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory("");

        // Add some data
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        // 标题名称, 数据表中列名, excel单元格格式描述
        $cols = array(
            'stockin_sn' => array("入库单编号", 'stockin_sn', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_sn' => array("商品编码", 'goods_sn', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_name_chn' => array("商品名称(中)", 'goods_name_chn', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_name_tha' => array("商品名称(泰)", 'goods_name_tha', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_pack_num' => array("数量(件)", 'goods_pack_num', PHPExcel_Style_NumberFormat::FORMAT_NUMBER),
            'goods_pack_size' => array("装箱数量", 'goods_pack_size', PHPExcel_Style_NumberFormat::FORMAT_NUMBER),
            'stockin_note' => array("备注", 'stockin_note', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'stockin_opttime' => array("日期", 'stockin_opttime', 'yyyy-mm-dd hh:mm:ss')
        );

        $colCount = count($mat['key']);
        for ($i = 0; $i < $colCount; $i++) {
            $colName = PHPExcel_Cell::stringFromColumnIndex($i);
            $sheet->setCellValue($colName . '1', $mat['comment'][$i]);
            $sheet->getColumnDimension($colName)->setAutoSize(true);
        }
        for ($i = 0, $l = count($items); $i < $l; $i++) {
            //Convert mysql date string to excel time object
            $items[$i]['stockin_opttime'] = PHPExcel_Shared_Date::StringToExcel($items[$i]['stockin_opttime']);
            for ($j = 0; $j < $colCount; $j++) {
                $cellName = sprintf("%s%d", PHPExcel_Cell::stringFromColumnIndex($j), $i + 2);
                $sheet->setCellValue($cellName, $items[$i][$mat['key'][$j]]);
                $sheet->getStyle($cellName)->getNumberFormat()->setFormatCode($cols[$mat['key'][$j]][2]);
            }
        }

        // Rename worksheet
        $sheet->setTitle('入库单列表');

        // Redirect output to a client’s web browser (Excel5)
        $xlsFilename = "stockin_export_" . date("Ymdhis") . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$xlsFilename}'");
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function importFromFile($file) {
        var_dump($file);
        $cfgObj = base_mAPI::get('m_config');
        $tpl = $cfgObj->getValue('import_stockin_template');
        if (!$tpl) {
            $tpl = $cfgObj->getValue('import_stockin_template_default');
        }
        $ptn = '%(?P<key>\w+)%';
        $err = preg_match_all($ptn, $tpl, $mat);
        if (!$err) {
            $this->setError(0, '导入模板不正确,请到系统配置中设置正确的模板!');
            return false;
        }

        $name = $file['name'];
        $type = $file['type'];
        $srcfile = $file['tmp_name'];
        if ($type != 'application/vnd.ms-excel') { //TODO add filetype support
            $this->setError(0, '文件类型错误!');
            return false;
        }
        if (!file_exists($srcfile)) {
            $this->setError(0, '文件上传失败, 请重试!');
            return false;
        }

        require_once(PHPEXCEL_CLASS);

        $cols = array(
            'stockin_sn' => array("入库单编号", 'stockin_sn', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_sn' => array("商品编码", 'goods_sn', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_name_chn' => array("商品名称(中)", 'goods_name_chn', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_name_tha' => array("商品名称(泰)", 'goods_name_tha', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'goods_pack_num' => array("数量(件)", 'goods_pack_num', PHPExcel_Style_NumberFormat::FORMAT_NUMBER),
            'goods_pack_size' => array("装箱数量", 'goods_pack_size', PHPExcel_Style_NumberFormat::FORMAT_NUMBER),
            'stockin_note' => array("备注", 'stockin_note', PHPExcel_Style_NumberFormat::FORMAT_TEXT),
            'stockin_opttime' => array("日期", 'stockin_opttime', 'yyyy-mm-dd hh:mm:ss')
        );

        $objPHPExcel = PHPExcel_IOFactory::load($srcfile);

        $colCount = count($mat['key']);
        for ($i = 0; $i < $colCount; $i++) {
            if (!isset($cols[$mat['key'][$i]])) {
                $this->setError(0, '导入模板错误, 无法识别参数' . $mat['key'][$i]);
                return false;
            }
        }

        $res = array();

        $it = $objPHPExcel->getWorksheetIterator();
        for (; $it->valid(); $it->next()) {
            $sheet = $it->current();
            var_dump($sheet->getTitle());
        }


        return array();
    }

}
