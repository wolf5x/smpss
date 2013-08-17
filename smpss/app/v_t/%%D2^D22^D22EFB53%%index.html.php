<?php /* Smarty version 2.6.26, created on 2012-12-10 14:50:36
         compiled from simpla/stockin/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/stockin/index.html', 12, false),array('modifier', 'cat', 'simpla/stockin/index.html', 90, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="main-content">
	<h2>欢迎您 <?php echo $this->_tpl_vars['_adminname']; ?>
</h2>
	<p id="page-intro">查看和管理商品库存。商品名称可以输入中文或泰文名</p>
	<div class="clear"></div>
	<div class="content-box">
		<div class="content-box-header">
			<h3>入库单列表</h3>
			<ul class="content-box-tabs">
				<li><a href="#tab1" class="default-tab">入库单管理</a></li>
				<li><a href="<?php echo smarty_function_get_url(array('rule' => "/stockin/stockin"), $this);?>
">新增入库</a></li>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="content-box-content">
			<div class="tab-content default-tab" id="tab1">
				<div class="form">
					<form action="<?php echo smarty_function_get_url(array('rule' => '/stockin/index'), $this);?>
" method="post" id="js-form">
						<fieldset class="clearfix">
							<table>
								<tr>
									<td class="input-label short-label">
										入库单编号：
									</td>
									<td>
										<span><input type="text" name="key_ssn" value="<?php echo $this->_tpl_vars['key']['ssn']; ?>
" class="text-input" size="24" /></span>
									</td>
									<td class="input-label short-label"></td>
									<td></td>
								</tr>
								<tr>
									<td class="input-label">
										商品编号：
									</td>
									<td>
										<span><input type="text" name="key_gsn" value="<?php echo $this->_tpl_vars['key']['gsn']; ?>
" class="text-input" size="24"/></span>
									</td>
									<td class="input-label">商品名称：</td>
									<td>
										<span><input type="text" name="key_name" value="<?php echo $this->_tpl_vars['key']['name']; ?>
" class="text-input" size="48"/></span>
									</td>
								</tr>
								<tr>
									<td class="input-label">日期范围：</td>
									<td colspan="2">
										<input type="text" name="date_start" id="date_start_picker" class="text-input" size="12" value="<?php echo $this->_tpl_vars['key']['date_start']; ?>
"/>&nbsp;--&nbsp;<input type="text" name="date_end" id="date_end_picker" class="text-input" size="12" value="<?php echo $this->_tpl_vars['key']['date_end']; ?>
"/>（格式1900-01-01）
									</td>
									<td>
										<input type="submit" name="filter" id="button" class="button" value="查询" />
										&nbsp;&nbsp;<input type="submit" name="export" id="button" class="button" value="导出" />
										&nbsp;&nbsp;<input type="submit" name="import" id="button" class="button" value="导入.." />
									</td>
								</tr>
							</table>
						</fieldset>
					</form>
				</div>
				<hr />
				<table class="centertable">
					<thead>
						<tr>
							<th>入库单编号</th>
							<th>商品编码</th>
							<th>商品名称(中)</th>
							<th>商品名称(泰)</th>
							<th>数量(件)</th>
							<th>装箱数量</th>
							<th>备注</th>
							<th>日期</th>
							<th>管理</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="9"><?php echo $this->_tpl_vars['pagebar']; ?>
</td>
						</tr>
					</tfoot>
					<tbody>
						<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['stockin']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
							<td><?php echo $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['stockin_sn']; ?>
</td>
							<td><?php echo $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['goods_sn']; ?>
</td>
							<td><?php echo $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['goods_name_chn']; ?>
</td>
							<td><?php echo $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['goods_name_tha']; ?>
</td>
							<td><?php echo $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['goods_pack_num']; ?>
</td>
							<td><?php echo $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['goods_pack_size']; ?>
</td>
							<td><?php echo $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['stockin_note']; ?>
</td>
							<td><?php echo $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['stockin_opttime']; ?>
</td>
							<td><a onclick="return(confirm('只有入库错误的时候才使用，你确认入库错误?'))" href="<?php echo smarty_function_get_url(array('rule' => '/stockin/stockin','data' => ((is_array($_tmp='ac=del&sid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['stockin_sn']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['stockin_sn']))), $this);?>
" title="删除"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/cross.png" alt="删除" /></a>&nbsp;<a href="<?php echo smarty_function_get_url(array('rule' => '/stockin/stockin','data' => ((is_array($_tmp='ac=mod&sid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['stockin_sn']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['stockin'][$this->_sections['i']['index']]['stockin_sn']))), $this);?>
" title="修改"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/hammer_screwdriver.png" alt="修改" /></a></td>
						</tr>
						<?php endfor; else: ?>
						<tr>
							<td colspan="9" align="center">没有数据</td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/copy.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> </div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
	$(function() {
		$("#date_start_picker").datepicker();
		$("#date_end_picker").datepicker();
	});
</script>