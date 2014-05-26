<?php

/**
 * 库存管理模型 
 * @author wolf5x
 */
class m_stockbackindex extends base_m {

    public function primarykey() {
        return 'stockback_sn';
    }

    public function tableName() {
        return base_Constant::TABLE_PREFIX . 'stockback_index';
    }

    public function relations() {
        return array();
    }

    /**
     * @param $data 退货索引：customer_name,operator
     * @param $detail 退货明细数组：需手动添加stockback_sn
     */
    public function create($data, $detail) {
        $stockback_sn = $data['stockback_sn'];
        $is_create = empty($stockback_sn);
        //TODO 暂不支持修改
        if (!$is_create) {
            $this->setError(0, "暂不支持修改退货单");
            return false;
        }

        $detail = $this->checkDetail($detail, $is_create);
        if (!$detail) {
            return false;
        }
        $stockback_totalprice = 0;
        $goods_brief = "";
        $BriefLength = 100;
        foreach ($detail as $g) {
            $stockback_totalprice += $g['goods_totalprice'];
            $blen = strlen($goods_brief);
            if ($blen < $BriefLength) {
                $goods_brief .= ($blen == 0) ? '' : ', ';
                $goods_brief .= $g['goods_name_tha'] . "*" . strval($g['goods_pack_num']);
                if (strlen($goods_brief) >= $BriefLength) {
                    $goods_brief = substr($goods_brief, 0, $BriefLength - 2) . '..';
                }
            }
        }

        $stockback_opttime = date('YmdHis');
        $stockback_sn_flag = "9";

        if ($is_create) {
            //临时数据表
            $ConstCountLength = 6;
            $configObj = base_mAPI::get("m_config");
            $last_stockback_sn = $configObj->getValue('last_stockback_sn');
            $now_date = substr($stockback_opttime, 0, 8);
            $last_date = substr($last_stockback_sn, 0, 8);
            $last_cnt = substr($last_stockback_sn, 8, $ConstCountLength);
            if (empty($last_stockback_sn) || $now_date != $last_date) {
                $stockback_sn = $now_date . $stockback_sn_flag . str_pad('1', $ConstCountLength - 1, '0', STR_PAD_LEFT);
            } else {
                $stockback_sn = $last_date . str_pad($last_cnt + 1, $ConstCountLength, '0', STR_PAD_LEFT);
            }
            if (!$configObj->setValue('last_stockback_sn', $stockback_sn)) {
                $this->setError(0, $configObj->getError());
                return false;
            }
        }

        if (!isset($data['customer_name']) || empty($data['customer_name'])) {
            $this->setError(0, "客户姓名不能为空！");
            return false;
        }
        $cusObj = base_mAPI::get("m_customer");
        $cusObj->create(array('customer_name' => $data['customer_name']));

        //$content = $data ['content'] ? $data ['content'] : "增加入库：名称：{$rs ['goods_name']},数量：{$data ['in_num']}";
        $this->set('stockback_sn', $stockback_sn);
        $this->set('customer_name', $data['customer_name']);
        $this->set("stockback_note", $data ['stockback_note']);
        $this->set("stockback_opttime", $stockback_opttime);
        $this->set('goods_brief', $goods_brief);
        $this->set('stockback_totalprice', $stockback_totalprice);

        if ($is_create) {
            $detObj = base_mAPI::get("m_stockbackdetail");
            foreach ($detail as $g) {
                $g['stockback_sn'] = $stockback_sn;
                $g['stockback_opttime'] = $stockback_opttime;
                $res = $detObj->create($g, true);
                if (!$res) {
                    //TODO 回滚(包括回滚库存)
                    $detObj->delBySn($g['stockback_sn'], true);
                    $this->setError(0, '保存退货单明细失败：' . $detObj->getError());
                    return false;
                }
            }
            $res = $this->save(false);
            if ($res) {
                return $stockback_sn;
            }
            //TODO $detObj->deletestockback($g['stockback_sn'], true);
            $this->setError(0, "保存退货单索引失败:" . $this->getError());
            return false;
        } else {
            return false;
        }
    }

    /**
     * 删除单个库存入库记录
     * @param string $id: stockback_sn
     * @praram $upd: update goods_stock
     */
    public function deleteOne($stockback_sn, $upd = true) {
        if (!$stockback_sn) {
            $this->setError(0, "退货单编号无效");
            return false;
        }

        $detObj = base_mAPI::get('m_stockbackdetail');
        $res = $detObj->delBySn($stockback_sn, $upd);
        if (!$res) {
            $this->setError(0, '删除退货明细失败：' . $detObj->getError());
            return false;
        }
        $res = $this->update(array('stockback_sn' => $stockback_sn), array('isdel' => '1'));
        if ($res === null || $res === false) {
            $this->setError(0, '删除退货索引记录失败：' . $this->getError());
            return false;
        }
        return true;
    }

    public function checkDetail($det, $iscreate = true) {
        //TODO 暂不支持修改
        $errmsg = '商品明细错误：';
        if (!$det || count($det) == 0) {
            $this->setError(0, $errmsg . '商品列表为空！');
            return false;
        }

        $res = array();

        $goodsObj = base_mAPI::get("m_goods");

        foreach ($det as &$g) {
            $rs = $goodsObj->getTheGoods($g['goods_sn'], $g['goods_name_chn'], $g['goods_name_tha']);
            if (!isset($rs['goods_id'])) {
                $this->setError(0, $errmsg . $goodsObj->getError());
                return false;
            }
            $g['goods_id'] = $rs['goods_id'];
            if ($g['goods_pack_size'] != $rs['goods_pack_size']) {
                $this->setError(0, $errmsg . '商品数据异常！');
                return false;
            }
            if ($g['goods_pack_num'] <= 0) {
                $this->setError(0, $errmsg . '商品数量必须大于0！');
                return false;
            }
            /* 		if($g['goods_unitprice'] < 0){
              $this->setError(0, $errmsg . '商品单价必须大于等于0！');
              return false;
              } */
            /* 		$totprice = $g['goods_pack_num'] * $g['goods_unitprice'] * $g['goods_pack_size'];
              if($this->floatcmp($totprice, $g['goods_totalprice']) != 0){
              $this->setError(0, $errmsg . '商品总价异常！');
              return false;
              } */
            $has = false;
            foreach ($res as &$h) {
                if ($h['goods_id'] == $g['goods_id']) {
                    $has = true;
                    $h['goods_pack_num'] += $g['goods_pack_num'];
                    $packnum = $h['goods_pack_num'];
                    break;
                }
            }
            if (!$has) {
                $res[] = $g;
                $packnum = $g['goods_pack_num'];
            }
            /* if($packnum > $rs['goods_stock']){
              $this->setError(0, $errmsg . "商品库存不足（{$rs['goods_sn']}，{$rs['goods_name_chn']}，{$rs['goods_name_tha']}：{$rs['goods_stock']}）");
              return false;
              } */
        }
        if (count($res) == 0) {
            $this->setError(0, $errmsg . "有效商品为空");
            return false;
        }
        return $res;
    }

    public function floatcmp($f1, $f2, $precision = 3) {
        $f1 = number_format($f1, $precision, '.', '');
        $f2 = number_format($f2, $precision, '.', '');
        return $f1 == $f2 ? 0 : ($f1 < $f2 ? -1 : 1);
    }

    public function getByCondition($key) {
        $soutIdx = $this;
        $idxCond = "isdel=0";
        $soutDet = base_mAPI::get('m_stockbackdetail');
        $detCond = "";

        $tk = $key['customer_name'];
        if (!empty($tk)) {
            $idxCond .= " and customer_name='{$tk}'";
        }

        $tk = $key['stockback_sn'];
        if (!empty($tk)) {
            $idxCond .= " and stockback_sn='{$tk}'";
        }

        $tk = $key['goods_sn'];
        if (!empty($tk)) {
            $detCond .= " and goods_sn='{$tk}'";
        }

        $tk = $key['goods_name'];
        if (!empty($tk)) {
            $detCond .= " and (goods_name_chn='{$tk}' or goods_name_tha='{$tk}')";
        }

        $tk = $key['date_start'];
        $dk = explode('-', $tk);
        if (checkdate($dk[1], $dk[2], (integer) $dk[0])) {
            $idxCond .= " and stockback_opttime>='{$tk}'";
            $key['date_start'] = $tk;
        }

        $tk = $key['date_end'];
        $dk = explode('-', $tk);
        if (checkdate($dk[1], $dk[2], (integer) $dk[0])) {
            $idxCond .= " and stockback_opttime<'{$tk}' + INTERVAL 1 DAY";
            $key['date_end'] = $tk;
        }

        $condition = $idxCond;
        if ($detCond) {
            $tbl = $soutDet->tableName();
            $detCond = "isdel=0" . $detCond;
            $in = "SELECT DISTINCT(stockback_sn) FROM {$tbl} WHERE {$detCond}";
            $condition .= " AND stockback_sn IN ({$in})";
        }

        $idxRs = $soutIdx->select($condition, "", "", "order by stockback_sn desc");
        return $idxRs;
    }

}
