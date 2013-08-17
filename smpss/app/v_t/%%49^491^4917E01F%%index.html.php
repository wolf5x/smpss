<?php /* Smarty version 2.6.26, created on 2013-08-17 01:02:26
         compiled from simpla/customer/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/customer/index.html', 12, false),array('modifier', 'cat', 'simpla/customer/index.html', 44, false),)), $this); ?>
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
        <h3>客户管理</h3>
        <ul class="content-box-tabs">
            <li><a href="#tab1" class="default-tab">客户管理</a></li>
            <li><a href="<?php echo smarty_function_get_url(array('rule' => "/customer/addcustomer"), $this);?>
">添加客户</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
        <div class="form">
          <form action="<?php echo smarty_function_get_url(array('rule' => '/customer/index'), $this);?>
" method="post" id="js-form" enctype="multipart/form-data">
            <fieldset>
                  <p>&nbsp;关键字：<input type="text" value="<?php echo $this->_tpl_vars['key']; ?>
" class="text-input small-input" name="key" />
                    <span>（客户名称）<input type="submit" name="" id="button" class="button" value="查询" /></span></p>
            </fieldset>
          </form>
        </div>
        <hr />
          <table>
            <thead>
              <tr>
                <th>客户名称</th>
                
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
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['customer']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                  <td><?php echo $this->_tpl_vars['customer'][$this->_sections['i']['index']]['customer_name']; ?>
</td>
                  
                  <td><a href="<?php echo smarty_function_get_url(array('rule' => '/customer/addcustomer','data' => ((is_array($_tmp='cid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['customer'][$this->_sections['i']['index']]['customer_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['customer'][$this->_sections['i']['index']]['customer_id']))), $this);?>
" title="编辑"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/pencil.png" alt="编辑" /></a>&nbsp;<a onclick="return(confirm('你确认要删除该客户?'))" href="<?php echo smarty_function_get_url(array('rule' => '/customer/index','data' => ((is_array($_tmp='ac=del&cid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['customer'][$this->_sections['i']['index']]['customer_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['customer'][$this->_sections['i']['index']]['customer_id']))), $this);?>
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