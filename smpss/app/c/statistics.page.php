<?php

/**
 * 销售统计
 * @author 齐迹  email:smpss2012@gmail.com
 */
class c_statistics extends base_c {

    function __construct($inPath) {
        parent::__construct();
        if (self::isLogin() === false) {
            $this->ShowMsg("请先登录！", $this->createUrl("/main/index"));
        }
        if (self::checkRights($inPath) === false) {
            //$this->ShowMsg("您无权操作！",$this->createUrl("/system/index"));
        }
        $this->params ['inpath'] = $inPath;
        $this->params ['head_title'] = "统计管理-" . $this->params ['head_title'];
    }

    function pageindex($inPath) {
        $ymd = date('Y-m-d', time());
        if ($_POST) {
            $salesObj = new m_stockoutdetail();
            $type = (int) $_POST ['type'] ? (int) $_POST ['type'] : 1;
            $condi = 'true';
            $start = base_Utils::getStr($_POST ['start']);
            $end = base_Utils::getStr($_POST ['end']);
            if ($start) {
                $condi = " and stockout_opttime>='{$start}'";
            }
            $condi .= $end ? " and stockout_opttime<='{$end}'" : " and stockout_opttime<='{$ymd}'";
            switch ($type) {
                case 1 :
                    $this->params ['title'] = "销售统计(不含退款)";
                    $rs = $salesObj->select($condi, "stockout_opttime as dateymd,sum(goods_totalprice) as money", "group by date(stockout_opttime)")->items;
                    break;
                case 2 : # TODO
                    $condi .= $condi ? " and refund_type>0" : " refund_type>0";
                    $this->params ['title'] = "退货统计";
                    $rs = $salesObj->select($condi, "dateymd,sum(refund_amount) as money", "group by dateymd")->items;
                    break;
                default:
                    break;
            }
            var_dump($rs);
            $this->params ['start'] = $start;
            $this->params ['end'] = $end;
            $this->params ['type'] = $type;
            $this->params ['line'] = $this->linedata($rs);
        }
        return $this->render('statistics/index.html', $this->params);
    }

    function pagesales($inPath) {
        $ymd = date('Y-m-d', time());
        if ($_POST) {
            $purchaseObj = new m_purchase();
            $condi = '';
            $start = base_Utils::getStr($_POST ['start']);
            $end = base_Utils::getStr($_POST ['end']);
            if ($start) {
                $condi = "dateymd>='{$start}'";
                $condi .= $end ? " and dateymd<='{$end}'" : " and dateymd<='{$ymd}'";
            }
            $this->params ['title'] = "进货统计";
            $rs = $purchaseObj->select($condi, "dateymd,sum(in_num*in_price) as money", "group by dateymd")->items;
            $this->params ['start'] = $start;
            $this->params ['end'] = $end;
            $this->params ['line'] = $this->linedata($rs);
        }
        return $this->render('statistics/sales.html', $this->params);
    }

    private function linedata($arr) {
        if (!is_array($arr)) {
            $this->params ['null'] = "没有相关数据";
            return '';
        }
        $line = '';
        foreach ($arr as $k => $v) {
            $line .= "['{$v['dateymd']}', {$v['money']}],";
        }
        return rtrim($line, ',');
    }

}
