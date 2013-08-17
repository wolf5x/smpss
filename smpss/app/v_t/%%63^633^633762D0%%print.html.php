<?php /* Smarty version 2.6.26, created on 2013-08-17 01:03:25
         compiled from simpla/stockout/print.html */ ?>
﻿<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/printheader.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
	<div id="main-content">
		<table >
			<thead>
				<tr> 
					<th colspan="7" style="text-align:center;">出库单</th>
					<tr>
					</thead>
					<tbody>
						<th colspan="7">出库单号：<?php echo $this->_tpl_vars['index']['stockout_sn']; ?>
　顾客名称：<?php echo $this->_tpl_vars['index']['customer_name']; ?>
　订单时间：<?php echo $this->_tpl_vars['index']['stockout_opttime']; ?>
</td>
					</tr>
					<tr>
						<th>商品编号รหัสสินค้า</th>
						<th>商品名称รายการ</th>
						<th>数量（件）จำนวน</th>
						<th>装箱数量จำนวนต่อลัง</th>
						<th>单价ราคา</th>
						<th>小计จำนวนเงิน</th>
						<th>商品备注</th>
					</tr>
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['detail']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
					<tr>
						<td><?php echo $this->_tpl_vars['detail'][$this->_sections['i']['index']]['goods_sn']; ?>
</td>
						<td><?php echo $this->_tpl_vars['detail'][$this->_sections['i']['index']]['goods_name_tha']; ?>
</td>
						<td><?php echo $this->_tpl_vars['detail'][$this->_sections['i']['index']]['goods_pack_num']; ?>
</td>
						<td><?php echo $this->_tpl_vars['detail'][$this->_sections['i']['index']]['goods_pack_size']; ?>
</td>
						<td><?php echo $this->_tpl_vars['detail'][$this->_sections['i']['index']]['goods_unitprice']; ?>
</td>
						<td><?php echo $this->_tpl_vars['detail'][$this->_sections['i']['index']]['goods_totalprice']; ?>
</td>
						<td><?php echo $this->_tpl_vars['detail'][$this->_sections['i']['index']]['goods_note']; ?>
</td>
					</tr>
					<?php endfor; endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="7">总金额รวมเงิน：<?php echo $this->_tpl_vars['index']['stockout_totalprice']; ?>
</td>
					</tr>
					<tr>
						<td colspan="7">ผู้รับสินค้า：<?php echo $this->_tpl_vars['index']['stockout_note']; ?>
</td>
					</tr>
				</tfoot>
			</table>
			<span class="noprint">
				<center><input id="btn_print" type="button" value="打印" onclick="window.print();"/><input id="btn_close" type="button" value="关闭" onclick="window.close()"/> </center>
			</span>
		</table>
	</div>
</body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>