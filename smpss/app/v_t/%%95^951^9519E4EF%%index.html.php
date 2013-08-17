<?php /* Smarty version 2.6.26, created on 2012-10-05 08:44:39
         compiled from simpla/goods/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/goods/index.html', 12, false),array('modifier', 'cat', 'simpla/goods/index.html', 71, false),)), $this); ?>
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
    <p id="page-intro">查看和管理所有已有的商品。</p>
    <div class="clear"></div>
    <div class="content-box">
      <div class="content-box-header">
        <h3>商品管理</h3>
        <ul class="content-box-tabs">
            <li><a href="#tab1" class="default-tab">商品管理</a></li>
            <li><a href="<?php echo smarty_function_get_url(array('rule' => "/goods/addgoods"), $this);?>
">添加商品</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
        <div class="form">
          <form action="<?php echo smarty_function_get_url(array('rule' => '/goods/index'), $this);?>
" method="post" id="js-form" enctype="multipart/form-data">
            <fieldset class="clearfix">
				
                  <p>商品条形码：<span><input type="text" value="<?php echo $this->_tpl_vars['key']['sn']; ?>
" class="text-input small-input" name="key_sn" />
				     &nbsp;商品中文名称：<input type="text" value="<?php echo $this->_tpl_vars['key']['chn']; ?>
" class="text-input small-input" name="key_chn" />
				    &nbsp; 商品泰文名称：<input type="text" value="<?php echo $this->_tpl_vars['key']['tha']; ?>
" class="text-input small-input" name="key_tha" />
								  &nbsp; <input type="submit" name="" id="button" class="button" value="查询" /></span></p>
            </fieldset>
          </form>
        </div>
        <hr />
          <table>
            <thead>
              <tr>
                <th>商品条形码</th>
                <th>商品中文名称</th>
				<th>商品泰文名称</th>
                <!--<th>所属分类</th>-->
                <!--<th>售价(元)</th>-->
                <!--<th>促销价(元)</th>-->
                <!--<th>市场价(元)</th>-->
                <th>库存(件)</th>
				<th>装箱数量</th>
				<th>图片</th>
				<th>简介</th>
                <!--<th>库存总额(元)</th>
                <th>销售总额(元)</th>-->
                <th>管理</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="6"><?php echo $this->_tpl_vars['pagebar']; ?>
</td>
              </tr>
            </tfoot>
            <tbody>
                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['goods']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                  <td><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_sn']; ?>
</td>
                  <td><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_name_chn']; ?>
</td>
				  <td><a href="http://www.baidu.com"><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_name_tha']; ?>
</a></td>
                  <!--<td><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['cat_name']; ?>
</td>-->
                  <!--<td><span class="red fb"><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['out_price']; ?>
</span></td>-->
                  <!--<td><?php if ($this->_tpl_vars['goods'][$this->_sections['i']['index']]['ispromote'] == 1): ?><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['promote_price']; ?>
<?php else: ?>未促销<?php endif; ?></td>-->
                  <!--<td><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['market_price']; ?>
</td>-->
                  <!--<td><?php if ($this->_tpl_vars['goods'][$this->_sections['i']['index']]['stock'] > $this->_tpl_vars['goods'][$this->_sections['i']['index']]['warn_stock']): ?><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['stock']; ?>
<?php else: ?><span class="red fb" title="低于最小库存"><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['stock']; ?>
(缺)</span><?php endif; ?></td>-->
				  <td><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_stock']; ?>
</td>
				  <td><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_pack_size']; ?>
</td>
				  <td><a href="<?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_pic']['imageurl']; ?>
"><img src="<?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_pic']['thumburl']; ?>
"></a></td>
				  <td width=25%><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_note']; ?>
</td>
                  <!--<td><span class="red"><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['countamount']; ?>
</span></td>
                  <td><span class="red"><?php echo $this->_tpl_vars['goods'][$this->_sections['i']['index']]['salesamount']; ?>
</span></td>-->
                  <td><a href="<?php echo smarty_function_get_url(array('rule' => '/goods/editgoods','data' => ((is_array($_tmp='gid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_id']))), $this);?>
" title="编辑"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/pencil.png" alt="编辑" /></a>&nbsp;<a href="<?php echo smarty_function_get_url(array('rule' => '/purchase/purchase','data' => ((is_array($_tmp='gid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_id']))), $this);?>
" title="入库"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/hammer_screwdriver.png" alt="入库" /></a>&nbsp;<a onclick="return(confirm('只有入库错误的时候才使用，你确认入库错误?'))" href="<?php echo smarty_function_get_url(array('rule' => '/goods/index','data' => ((is_array($_tmp='ac=del&gid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['goods'][$this->_sections['i']['index']]['goods_id']))), $this);?>
" title="删除"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/cross.png" alt="删除" /></a></td>
                </tr>
                <?php endfor; endif; ?>
            </tbody>
          </table>
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