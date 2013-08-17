<?php /* Smarty version 2.6.26, created on 2013-08-18 02:03:22
         compiled from simpla/stockin/choose.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/stockin/choose.html', 11, false),)), $this); ?>
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
	<p id="page-intro">商品入库。带<font class="red"> * </font>为必填。商品中文名与泰文名必须至少填写一个。</p>
	<div class="clear"></div>
	<div class="content-box">
		<div class="content-box-header">
			<h3>选择入库商品</h3>
			<ul class="content-box-tabs">
				<li><a href="<?php echo smarty_function_get_url(array('rule' => '/stockin/index'), $this);?>
">入库单管理</a></li>
				<li><a href="#tab1" class="default-tab">新增入库</a></li>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="content-box-content">
			<div class="tab-content default-tab" id="tab1">
				<div class="form">
					<form action="<?php echo smarty_function_get_url(array('rule' => '/stockin/stockin'), $this);?>
" method="post" id="js-form">
						<fieldset class="clearfix">
							<input type="hidden" name="goods_id" value="<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
" />
							<input type="hidden" name="stockin_sn" value="<?php echo $this->_tpl_vars['goods']['stockin_sn']; ?>
" />
							<input type="hidden" name="ac" value="<?php echo $this->_tpl_vars['ac']; ?>
" />
							<table>
								<tr>
									<td class="input-label short-label"><font class="red">* </font>商品编码：</td>
									<td>
										<input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_sn']; ?>
" class="text-input" size="24" name="goods_sn" id="goods_sn" />
									</td>
								</tr>
								<tr>
									<td class="input-label"><font class="red">* </font>商品名称(中)：</td>
									<td>
										<input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_name_chn']; ?>
" class="text-input" size="48" name="goods_name_chn" id="goods_name_chn" />
									</td>
								</tr>
								<tr>
									<td class="input-label"><font class="red">* </font>商品名称(泰)：</td>
									<td>
										<input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_name_tha']; ?>
" class="text-input" size="48" name="goods_name_tha" id="goods_name_tha" />
									</td>
									<tr>
										<td colspan="2">
											<center><input type="submit" name="" class="button" id="button" value="选定商品" /></center>
										</td>
									</tr>
								</table>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/copy.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
		</div>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>