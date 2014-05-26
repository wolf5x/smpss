<?php

/**
 * 商品表数据模型
 * @author 齐迹  email:smpss2012@gmail.com
 */
class m_customer extends base_m {

    public function primarykey() {
        return 'customer_id';
    }

    public function tableName() {
        return base_Constant::TABLE_PREFIX . 'customer';
    }

    public function relations() {
        return array();
    }

    public function getCustomerList($condition = '', $page = 0) {
        $customerTableName = $this->tableName();
        $this->setCount(true);
        if ($page > 0) {
            $this->setPage($page);
            $this->setLimit(base_Constant::PAGE_SIZE);
        } else {
            $this->setPage(0);
            $this->setLimit(0);
        }

        $rs = $this->select($condition, "", "", "order by customer_id asc");
        return $rs;
    }

    public function create($data) {
        if (!$data ['customer_name']) {
            $this->setError(0, "客户名称不能为空!");
            return false;
        }

        $snRs = $this->getTheCustomer($data['customer_name']);
        //	echo $data['customer_id'];
        if ($snRs ['customer_id'] && $snRs ['customer_id'] != $data['customer_id']) {
            $this->setError(0, "该客户已存在");
            return false;
        }

        $this->set("customer_name", $data ['customer_name']);

        $rs = $this->save($data ['customer_id']);
        if ($rs) {
//			$logObj = base_mAPI::get ( "m_log" );
//			$logObj->create ( $rs, $content, 0 );
            return $rs;
        }
        $this->setError(0, "保存数据失败: " . $this->getError());
        return false;
    }

    public function getTheCustomer($name = "") {
        if (!isset($name)) {
            $this->setError(0, "客户名称不能为空");
            return false;
        }
        $condition = "customer_name='{$name}'";

        $ret = $this->get($condition);
        if (!$ret) {
            $this->setError(0, "该客户不存在");
            return false;
        }
        return $ret;
    }

    /**
     * 删除单个客户记录
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

}
