<?php /* Smarty version 2.6.26, created on 2013-01-30 03:00:31
         compiled from simpla/system/setting.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/system/setting.html', 115, false),)), $this); ?>
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
	<p id="page-intro">系统配置。注意：修改后需要重新登录</p>
	<div class="clear"></div>
	<div class="content-box">
		<div class="content-box-header">
			<h3>系统配置</h3>
			<div class="clear"></div>
		</div>
		<div class="content-box-content">
			<div class="tab-content default-tab" id="tab1">
				<div class="form">
					<form id="settingform">
						<fieldset class="clearfix">
							<p>
							<label><font class="red"> * </font>系统名称</label>
							<span>
								<input type="text" value="<?php echo $this->_tpl_vars['system_name']; ?>
" class="text-input" size="48" name="system_name" readonly/>
							</span> </p><p>
							<label><font class="red"> * </font>Cookie密匙</label>
							<span>
								<input type="text" value="<?php echo $this->_tpl_vars['cookie_key']; ?>
" class="text-input" size="48" name="cookie_key" readonly/>
							</span> </p>
							<p>
							<label><font class="red"> * </font>是否启用伪静态</label>
							<span>
								<input type="radio" value="1" name="rewrite" <?php if ($this->_tpl_vars['rewrite'] == 1): ?> checked="checked"<?php endif; ?> disabled/>启用<input type="radio" value="0" name="rewrite" <?php if ($this->_tpl_vars['rewrite'] == 0): ?> checked="checked"<?php endif; ?> disabled/>禁用
							</span> </p>
							<table>
								<thead>
									<tr>
										<th colspan="2">入库单模板</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="inline-label">导入模板：<span style="float:right"><a href="javascript:;" id="btn_rii">还原默认</a>&nbsp;<a href="javascript:;">参数说明</a>　</span></td>
										<td class="inline-label">导出模板：<span style="float:right"><a href="javascript:;" id="btn_rie">还原默认</a>&nbsp;<a href="javascript:;">参数说明</a>　</span></td>
									</tr>
									<tr>
										<td valign="top">
											<div><textarea name="import_stockin_template" id="import_stockin_template" class="text-input textarea h_textarea"><?php echo $this->_tpl_vars['import_stockin_template']; ?>
</textarea></div>
										</td>
										<td valign="top">

											<div><textarea name="export_stockin_template" id="export_stockin_template" class="text-input textarea h_textarea"><?php echo $this->_tpl_vars['export_stockin_template']; ?>
</textarea></div>

										</td>
									</tr>
								</tbody>
							</table>
							<table>
								<thead>
									<tr>
										<th colspan="2">出库单模板</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="inline-label">导入模板：<span style="float:right"><a href="javascript:;" id="btn_roi">还原默认</a>&nbsp;<a href="javascript:;">参数说明</a>　</span></td>
										<td class="inline-label">导出模板：<span style="float:right"><a href="javascript:;" id="btn_roe">还原默认</a>&nbsp;<a href="javascript:;">参数说明</a>　</span></td>
									</tr>
									<tr>
										<td valign="top">
											<div><textarea name="import_stockout_template" id="import_stockout_template" class="text-input textarea h_textarea"><?php echo $this->_tpl_vars['import_stockout_template']; ?>
</textarea></div>
										</td>
										<td valign="top">

											<div><textarea name="export_stockout_template" id="export_stockout_template" class="text-input textarea h_textarea"><?php echo $this->_tpl_vars['export_stockout_template']; ?>
</textarea></div>

										</td>
									</tr>
								</tbody>
							</table>

							<p><label>清空数据</label><input type="checkbox" name="cleartable" id="chk_clr" value="1" /><span>勾选将清空商品和会员所有数据。管理帐号不会清空。</span></p>
							<p>
							<input type="button" name="" class="button" id="btn_edit" value="修改" />
							</p>
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
<script type="text/javascript">
	$(function(){
			$("#chk_clr").change(function(){
				if($(this).attr("checked")){
					alert("选中此项后提交修改，将清除所有商品、顾客、入库、出库、退货记录，请慎重操作！");
				}
				return true;
			});
			$("#btn_rii").click(function(){
				$("#import_stockin_template").val(<?php echo $this->_tpl_vars['import_stockin_template_default']; ?>
);
			});
			$("#btn_rie").click(function(){
				$("#export_stockin_template").val(<?php echo $this->_tpl_vars['export_stockin_template_default']; ?>
);
			});
			$("#btn_roi").click(function(){
				$("#import_stockout_template").val(<?php echo $this->_tpl_vars['import_stockout_template_default']; ?>
);
			});
			$("#btn_roe").click(function(){
				$("#export_stockout_template").val(<?php echo $this->_tpl_vars['export_stockout_template_default']; ?>
);
			});
			$("#btn_edit").click(function(){
				$.ajax({
					type: "post",
					dataType: "json",
					url: "<?php echo smarty_function_get_url(array('rule' => '/system/setting','data' => 'ac=ajaxedityes'), $this);?>
",
					data: $("#settingform").serialize(),
					beforeSend: function() {
						if($("#chk_clr").attr("checked")) {
							if(!confirm("您确定要清空所有数据吗？")){
								return false;
							}
						}
						$().message("处理中..");
					},
					error: function(jqXHR, textStatus, errorThrown) {
							   $().message("请求失败: " + textStatus);
						   },
					success: function(rsp) {
								 var errcode, errmsg;
								 errcode = rsp.errcode;
								 errmsg = rsp.errmsg;
								 if(errcode == 0){
								 	var delay = 2;
									 $().message("修改成功！" + delay + "秒后刷新页面");
									 setInterval(function(){window.location.reload(true)}, delay*1000);
								 } else{
									 $().message("修改失败：" +  errmsg);
								 }

							 }
					

				});
			});
	});
</script>