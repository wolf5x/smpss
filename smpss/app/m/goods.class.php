<?php

/**
 * 商品表数据模型
 * @author 齐迹  email:smpss2012@gmail.com
 */
class m_goods extends base_m {

    public function primarykey() {
        return 'goods_id';
    }

    public function tableName() {
        return base_Constant::TABLE_PREFIX . 'goods';
    }

    public function relations() {
        return array();
    }

    public function getGoodsList($condition = '', $page = 1) {
        $this->setCount(true);
        $this->setPage($page);
        $this->setLimit(base_Constant::PAGE_SIZE);
        $goodsTableName = $this->tableName();
        //	$cateTableName = base_Constant::TABLE_PREFIX . 'category';
        $rs = $this->select($condition, '', '', "order by goods_id desc");
        //	print_r($rs);
        if ($rs)
            return $rs;
        return array();
    }

    public function create($data) {
        if (!$data ['goods_sn'] or ! $data ['goods_name_chn'] or ! $data['goods_name_tha']) {
            $this->setError(0, "商品编码,商品中文名,商品泰文名不能为空!");
            return false;
        }
        //	print_r($data);
        /* 	$snRs = $this->get ( "goods_sn='{$data ['goods_sn']}'", 'goods_id' );
          if ($snRs ['goods_id'] != $data ['goods_id']) {
          $this->setError ( 0, "条形码重复" );
          return false;
          } */
        $snRs = $this->getTheGoods($data['goods_sn'], $data['goods_name_chn']);
        if (($snRs ['goods_id'] != $data ['goods_id'] && $data['goods_id'] && $snRs['goods_id']) || (!$data ['goods_id'] && $snRs['goods_id'])) {
            $this->setError(0, "该商品编码+商品中文名已存在");
            return false;
        }

        if ($data['goods_stock'] < 0) {
            $this->setError(0, "库存量不能为负!");
            return false;
        }

        if ($data['goods_pack_size'] <= 0) {
            $this->setError(0, "装箱数量必须大于0!");
            return false;
        }
//		$data ['market_price'] = $data ['market_price'] ? $data ['market_price'] : $data ['out_price'] * 1.2;
//		$this->set ( "cat_id", ( int ) $data ['cat_id'] );
//		$this->set ( "goods_name", $data ['goods_name'] );
        $this->set("goods_sn", $data ['goods_sn']);
        $this->set("goods_name_chn", $data['goods_name_chn']);
        $this->set("goods_name_tha", $data['goods_name_tha']);
        //	$this->set ( "weight", $data ['weight'] );
        //	$this->set ( "unit", $data ['unit'] );
        //	$this->set ( "warn_stock", ( int ) $data ['warn_stock'] );
        //	$this->set ( "in_price", $data ['in_price'] );
        //	$this->set ( "out_price", $data ['out_price'] );
        //	$this->set ( "market_price", $data ['market_price'] );
        //	$this->set ( "promote_price", $data ['promote_price'] );
        //	$this->set ( "ispromote", $data ['ispromote'] );
        //	$this->set ( "promote_start_date", $data ['promote_start_date'] );
        //	$this->set ( "promote_end_date", $data ['promote_end_date'] );
        //	$this->set ( "ismemberprice", $data ['ismemberprice'] );
        //	$this->set ( "creatymd", date ( 'Y-m-d', $this->_time ) );
        //	$this->set ( "creatdateline", $this->_time );
        if ($data ['goods_id'])
            $this->set("goods_stock", $data ['goods_stock']);
        $this->set("goods_pack_size", $data ['goods_pack_size']);
        $this->set("goods_note", $data ['goods_note']);
        $this->set("goods_pic", $data ['goods_pic']);
        //	echo $data['goods_pic'];
        //$content = $data ['goods_id'] ? "修改商品：{$data ['goods_name']}" : "新增商品：{$data ['goods_name']}";
        $rs = $this->save($data ['goods_id']);
        if ($rs) {
//			$logObj = base_mAPI::get ( "m_log" );
//			$logObj->create ( $rs, $content, 0 );
            if (!$data ['goods_id'] && $data['goods_stock'] > 0) { //新增商品才可以同时入库
                $stockinObj = base_mAPI::get("m_stockin");
                $stockin ['goods_id'] = $rs;
                $stockin ['goods_sn'] = $data ['goods_sn'];
                $stockin ['goods_name_chn'] = $data ['goods_name_chn'];
                $stockin ['goods_name_tha'] = $data ['goods_name_tha'];
                $stockin ['goods_pack_num'] = $data ['goods_stock'];
                $stockin ['goods_pack_size'] = $data ['goods_pack_size'];
                if (!$stockinObj->create($stockin)) {
                    $this->setError(0, "初始入库失败: " . $stockinObj->getError());
                    return false;
                }
            }
            return $rs;
        }
        $this->setError(0, "保存数据失败: " . $this->getError());
        return false;
    }

    /**
     * 修改库存
     * @param int $goods_id
     * @param float $amount
     * @param int $isadd 1加 0减
     */
    public function setStock($goods_id, $amount = 0, $isadd = 1) {
        if (!$goods_id) {
            $this->setError(0, "商品编码不能为空");
            return false;
        }
        /* 	$purchaseObj = base_mAPI::get ( "m_purchase" );
          $stock = $purchaseObj->getStockAmount ( $goods_id );
          if ($stock) {
          $this->setPkid ( $goods_id );
          if ($amount > 0) {
          if ($isadd == 1) {
          $salesamount = $this->getData ( "salesamount" ) + $amount;
          $this->set ( "salesamount", $salesamount );
          } else {
          $salesamount = $this->getData ( "salesamount" ) - $amount;
          $this->set ( "salesamount", $salesamount );
          }
          }
          $this->set ( "countamount", $stock ['countamount'] );
          $this->set ( "goods_stock", $stock ['stock'] );
          $this->set ( "lastinymd", date ( "Y-m-d", $this->_time ) );
          $this->set ( "lastindateline", $this->_time );
          if ($this->save ())
          return true;
          }
          $this->setError ( 0, "库存异常" );
          return false; */
        //TODO lock
        $this->setPkid($goods_id);
        $stock = $this->getData("goods_stock");
        if (!$stock) {
            $this->setError(0, "库存未找到");
            return false;
        }
        if ($isadd == 0) {
            $amount = -$amount;
        }
        if ($stock + $amount < 0) {
            $this->setError(0, "库存不能小于0");
            return false;
        }
        
        $res = $this->updateInc('', array('goods_stock' => $amount));
        if (!$res) {
             $this->setError(0, "保存数据失败" . $this->getError());
            return false;
        }
        return true;
    }

    /**
     * 获取商品的实际售价和优惠情况
     * @param int $goods_id
     * @return Array
     */
    public function getSalePrice($goods_sn) {
        /* 	$goods = $this->get ( "goods_sn='{$goods_sn}'" );
          if (! $goods)
          return false;
          $data = array ();
          $data ['goods_name'] = $goods ['goods_name'];
          $data ['goods_sn'] = $goods ['goods_sn'];
          $data ['stock'] = $goods ['stock'];
          $data ['goods_id'] = $goods ['goods_id'];
          $data ['cat_id'] = $goods ['cat_id'];
          $data ['out_price'] = $goods ['out_price'];
          $data ['p_discount'] = 0;
          $data ['ismemberprice'] = $goods ['ismemberprice'];
          $data ['ispromote'] = $goods ['ispromote'];
          $ymd = date ( "Y-m-d", $this->_time );
          if ($goods ['ispromote'] == 1 and $ymd > $goods ['promote_start_date'] and $ymd < $goods ['promote_end_date']) {
          $data ['promote_price'] = $goods ['promote_price'];
          $data ['p_discount'] = sprintf ( "%01.2f", $goods ['out_price'] - $goods ['promote_price'] ); //促销优惠
          }
          return $data; */
    }

    /**
     * 计算商品平均进价
     * @param array 商品ID 数组 也可是单个ID
     */
    function getAvgPrice($goods_ids) {
        /* 	$avgrice = array ();
          if (is_array ( $goods_ids )) {
          $goods_ids = join(",", $goods_ids);
          $rs = $this->select ( "goods_id in({$goods_ids})", "goods_id,stock,countamount" )->items;
          if ($rs) {
          foreach ( $rs as $k => $v ) {
          $avgrice [$v ['goods_id']] = sprintf ( "%01.2f", $rs ['countamount'] / $rs ['stock'] );
          }
          }
          } else {
          $rs = $this->selectOne ( "goods_id={$goods_ids}", "stock,countamount" );
          if ($rs)
          return sprintf ( "%01.2f", $rs ['countamount'] / $rs ['stock'] );
          }
          return $avgrice; */
    }

    public function getTheGoods($sn, $name_chn = "", $name_tha = "") {
        if (!isset($sn)) {
            $this->setError(0, "商品编码不能为空");
            return false;
        }
        if (!isset($name_chn) && !isset($name_tha)) {
            $this->setError(0, "商品中文名与泰文名必须至少指定一个");
            return false;
        }
        $condition = "goods_sn='{$sn}'";
        $condition .= ($name_chn ? " and goods_name_chn='{$name_chn}'" : "");
        $condition .= ($name_tha ? " and goods_name_tha='{$name_tha}'" : "");
        $ret = $this->get($condition);
        if (!$ret) {
            $this->setError(0, "该商品不存在");
            return false;
        }
        return $ret;
    }

    /**
     * 删除单个商品记录
     * @param int $id
     */
    public function deleteOne($id) {
        if (!$id) {
            $this->setError(0, "缺少必要参数");
            return false;
        }
        $this->setPkid($id);
        $rs = $this->get();
        if (!$rs) {
            $this->setError(0, "待删记录不存在");
            return false;
        }
        /* if ($rs ['out_num'] > 0) {
          $this->setError ( 0, "已经存在销售不能够删除！" );
          return false;
          } */
        if (!$this->del()) {
            $this->setError(0, "删除记录失败！");
            return false;
        }
        return true;
    }

    public function getByCondition($key, $page = 1) {
        $this->setCount(true);
        $this->setPage($page);
        $this->setLimit(base_Constant::PAGE_SIZE);
        //	$condition = $nodel ? 'isdel=0' : 'true';
        $condition = "";
        //	print_r($key);
        $flag = 0;
        if (!empty($key['sn'])) {
            $condition .= "goods_sn like '%{$key['sn']}%'";
            $flag = 1;
        }

        if (!empty($key['chn'])) {
            if ($flag)
                $condition.=" and ";
            $condition .= "goods_name_chn like '%{$key['chn']}%'";
            $flag = 1;
        }

        if (!empty($key['tha'])) {
            if ($flag)
                $condition.=" and ";
            $condition .= "goods_name_tha like '%{$key['tha']}%'";
        }
        //	echo $condition;
        $rs = $this->select($condition, '', '', 'order by goods_id desc');
        if ($rs === false) {
            $this->setError(0, '条件查询失败!');
            return false;
        }
        return $rs;
    }

}
