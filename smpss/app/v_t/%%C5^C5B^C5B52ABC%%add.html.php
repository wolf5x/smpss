<?php /* Smarty version 2.6.26, created on 2013-07-03 05:57:39
         compiled from simpla/stockin/add.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/stockin/add.html', 11, false),array('modifier', 'date_format', 'simpla/stockin/add.html', 56, false),)), $this); ?>
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
	<p id="page-intro">商品入库。带<font class="red"> * </font>为必填</p>
	<div class="clear"></div>
	<div class="content-box">
		<div class="content-box-header">
			<h3>新增入库</h3>
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
" class="text-input" size="24" name="goods_sn" id="goods_sn" readonly/>
									</td>
								</tr>
								<tr>
									<td class="input-label"><font class="red">* </font>商品名称(中)：</td>
									<td>
										<input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_name_chn']; ?>
" class="text-input" size="48" name="goods_name_chn" id="goods_name_chn" readonly/>
									</td>
								</tr>
								<tr>
									<td class="input-label"><font class="red"></font>商品名称(泰)：</td>
									<td>
										<input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_name_tha']; ?>
" class="text-input" size="48" name="goods_name_tha" id="goods_name_tha" readonly/>
									</td>
								</tr>
								<tr>
									<td class="input-label"><font class="red">* </font>数量(件)：</td>
									<td>
										<input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_pack_num']; ?>
" class="text-input" size="24" name="goods_pack_num" id="goods_pack_num" />
									</td>
								</tr>
								<tr>
									<td class="input-label"><font class="red"></font>装箱数量：</td>
									<td>
										<input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_pack_size']; ?>
" class="text-input" size="24" name="goods_pack_size" id="goods_pack_size" readonly/>
									</td>
								</tr>
								<!--p><label class="inline"><font class="red"></font>入库时间:</label>
								<span><input type="text" value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
" class="text-input small-input" name="stockin_opttime" id="stockin_opttime" readonly/></span> 
								</p-->
								<tr>
									<td class="input-label">备注：</td>
									<td>
										<textarea value="<?php echo $this->_tpl_vars['goods']['stockin_note']; ?>
" class="text-input textarea" name="stockin_note" id="stockin_note"><?php echo $this->_tpl_vars['goods']['stockin_note']; ?>
</textarea>
									</td>
								</tr>
								<tr>	
									<td colspan="2">
										<center><input type="submit" name="" class="button" id="button" value="确认入库" /></center>
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
 ?> </div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>