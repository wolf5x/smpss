<?php /* Smarty version 2.6.26, created on 2012-12-10 14:50:46
         compiled from simpla/stockback/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/stockback/index.html', 12, false),array('modifier', 'cat', 'simpla/stockback/index.html', 57, false),)), $this); ?>
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
  <p id="page-intro">查看商品的出库明细。</p>
  <div class="clear"></div>
  <div class="content-box">
    <div class="content-box-header">
      <h3>销售明细</h3>
      <ul class="content-box-tabs">
        <li><a href="#tab1" class="default-tab">退货单管理</a></li>
        <li><a href="<?php echo smarty_function_get_url(array('rule' => "/stockback/stockback"), $this);?>
">商品退货</a></li>
     <!--   <li><a href="<?php echo smarty_function_get_url(array('rule' => "/stockout/stockback"), $this);?>
">商品退货</a></li>-->
      </ul>
      <div class="clear"></div>
    </div>
    <div class="content-box-content">
      <div class="tab-content default-tab" id="tab1">
        <div class="form">
          <form action="<?php echo smarty_function_get_url(array('rule' => '/stockback/index'), $this);?>
" method="post" id="js-form">
            <fieldset class="clearfix">
				<p>
					客户姓名：<span><input type="text" name="key_cname" value="<?php echo $this->_tpl_vars['key']['customer_name']; ?>
" class="text-input small-input" /></span>
				</p>
				<p>
					退货单编号：<span><input type="text" name="key_ssn" value="<?php echo $this->_tpl_vars['key']['stockback_sn']; ?>
" class="text-input small-input" /></span>
					&nbsp;&nbsp;商品编号：<span><input type="text" name="key_gsn" value="<?php echo $this->_tpl_vars['key']['goods_sn']; ?>
" class="text-input small-input" /></span>
					</p>
				<p>
					商品名称：<span><input type="text" name="key_name" value="<?php echo $this->_tpl_vars['key']['goods_name']; ?>
" class="text-input medium-input" /></span>
				</p>
				<p>日期范围：<input type="text" name="date_start" id="date_start_picker" class="text-input min-input" value="<?php echo $this->_tpl_vars['key']['date_start']; ?>
"/>&nbsp;--&nbsp;<input type="text" name="date_end" id="date_end_picker" class="text-input min-input" value="<?php echo $this->_tpl_vars['key']['date_end']; ?>
"/>（格式1900-01-01）&nbsp;&nbsp;<input type="submit" name="filter" id="button" class="button" value="查询" />&nbsp;&nbsp;<input type="submit" name="export" id="button" class="button" value="导出" />
				</p>
            </fieldset>
          </form>
        </div>
        <hr />
        <table class="centertable">
          <thead>
            <tr>
              <th>退货单号</th>
              <th>客户姓名</th>
              <!--th>商品概要</th-->
              <th>总金额</th>
              <th>日期时间</th>
			  <th>管理</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td colspan="5"><?php echo $this->_tpl_vars['pagebar']; ?>
</td>
            </tr>
          </tfoot>
          <tbody>
          <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['stockback']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			  <td><a href="<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => ((is_array($_tmp='ac=print&sid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['stockback_sn']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['stockback_sn']))), $this);?>
" title="打印" target="_blank"><?php echo $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['stockback_sn']; ?>
</a></td>
            <td><?php echo $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['customer_name']; ?>
</td>
            <!--td><?php echo $this->_tpl_vars['stockout'][$this->_sections['i']['index']]['goods_brief']; ?>
</td-->
			<td><?php echo $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['stockback_totalprice']; ?>
</td>
			<td><?php echo $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['stockback_opttime']; ?>
</td>
			<td>
				<!--a href="<?php echo smarty_function_get_url(array('rule' => '/stockout/index','data' => ((is_array($_tmp='ac=detail&sid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['stockout'][$this->_sections['i']['index']]['stockout_sn']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['stockout'][$this->_sections['i']['index']]['stockout_sn']))), $this);?>
" title="详细"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/content.png" alt="详细" /></a-->
				<a href="<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => ((is_array($_tmp='ac=print&sid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['stockback_sn']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['stockback_sn']))), $this);?>
" title="打印" target="_blank"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/printer.png" alt="打印" /></a>
				<!--a href="<?php echo smarty_function_get_url(array('rule' => '/stockout/stockout','data' => ((is_array($_tmp='ac=mod&sid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['stockout'][$this->_sections['i']['index']]['stockout_sn']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['stockout'][$this->_sections['i']['index']]['stockout_sn']))), $this);?>
" title="编辑"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/hammer_screwdriver.png" alt="编辑" /></a-->
				<a onclick="return(confirm('确认要删除此退货单?'))" href="<?php echo smarty_function_get_url(array('rule' => '/stockback/index','data' => ((is_array($_tmp='ac=del&sid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['stockback_sn']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['stockback'][$this->_sections['i']['index']]['stockback_sn']))), $this);?>
" title="删除"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/cross.png" alt="删除" /></a>
			</td>
          </tr>
          <?php endfor; else: ?>
          <tr>
            <td colspan="5" align="center">没有数据</td>
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
	$(function(){
		$("#date_start_picker").datepicker();
		$("#date_end_picker").datepicker();
	});
</script>