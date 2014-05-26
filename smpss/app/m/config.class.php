<?php

/**
 * key-value配置模型
 * @author wolf5x
 */
class m_config extends base_m {

    public function primarykey() {
        return 'key';
    }

    public function tableName() {
        return base_Constant::TABLE_PREFIX . 'config';
    }

    public function relations() {
        return array();
    }

    public function getValue($key) {
        $this->setPkid($key);
        $rs = $this->get();
        if ($rs) {
            return $rs['value'];
        } else {
            $this->setError(0, "没有这个配置项");
            return false;
        }
    }

    public function setValue($key, $value) {
        $rs = $this->getValue($key);
        $this->set('key', $key);
        $this->set('value', $value);
        if ($rs === false) {
            $res = $this->save(false);
        } else {
            $res = $this->save($key);
        }
        if ($res) {
            return true;
        } else {
            $this->setError(0, "保存配置失败:" . $this->getError());
            return false;
        }
    }

}
