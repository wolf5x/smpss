<?php /* Smarty version 2.6.26, created on 2013-05-02 02:06:33
         compiled from simpla/member/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/member/index.html', 12, false),array('modifier', 'mbgroup', 'simpla/member/index.html', 53, false),array('modifier', 'cat', 'simpla/member/index.html', 58, false),)), $this); ?>
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
  <p id="page-intro">会员卡用户管理。</p>
  <div class="clear"></div>
  <div class="content-box">
    <div class="content-box-header">
      <h3>会员管理</h3>
      <ul class="content-box-tabs">
        <li><a href="#tab1" class="default-tab">帐号管理</a></li>
        <li><a href="<?php echo smarty_function_get_url(array('rule' => "/member/addmember"), $this);?>
">添加帐号</a></li>
      </ul>
      <div class="clear"></div>
    </div>
    <div class="content-box-content">
      <div class="tab-content default-tab" id="tab1">
        <div class="form">
          <form action="<?php echo smarty_function_get_url(array('rule' => '/member/index'), $this);?>
" method="post" id="js-form">
            <fieldset class="clearfix">
              <p>搜索： <span>
                <input type="text" value="<?php echo $this->_tpl_vars['key']; ?>
" class="text-input small-input" name="key" />
                <small>会员卡，会员名，电话，手机</small>
                <input type="submit" name="" class="button" value="查询" />
                </span> </p>
            </fieldset>
          </form>
        </div>
        <hr />
        <table>
          <thead>
            <tr>
              <th>会员卡卡号</th>
              <th>会员姓名</th>
              <th>会员等级</th>
              <th>会员积分</th>
              <th>折扣</th>
              <th>手机号</th>
              <th>座机号</th>
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
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['member']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <td><?php echo $this->_tpl_vars['member'][$this->_sections['i']['index']]['membercardid']; ?>
<?php if ($this->_tpl_vars['member'][$this->_sections['i']['index']]['state'] == 0): ?><span class="red">(禁用)</span><?php endif; ?></td>
            <td><?php echo $this->_tpl_vars['member'][$this->_sections['i']['index']]['realname']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['member'][$this->_sections['i']['index']]['grade'])) ? $this->_run_mod_handler('mbgroup', true, $_tmp, 'mgroup_name') : smarty_modifier_mbgroup($_tmp, 'mgroup_name')); ?>
</td>
            <td><?php echo $this->_tpl_vars['member'][$this->_sections['i']['index']]['credit']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['member'][$this->_sections['i']['index']]['grade'])) ? $this->_run_mod_handler('mbgroup', true, $_tmp, 'discount') : smarty_modifier_mbgroup($_tmp, 'discount')); ?>
%</td>
            <td><?php echo $this->_tpl_vars['member'][$this->_sections['i']['index']]['mobile']; ?>
</td>
            <td><?php echo $this->_tpl_vars['member'][$this->_sections['i']['index']]['phone']; ?>
</td>
            <td><a href="<?php echo smarty_function_get_url(array('rule' => '/member/addmember','data' => ((is_array($_tmp='mid=')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['member'][$this->_sections['i']['index']]['mid']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['member'][$this->_sections['i']['index']]['mid']))), $this);?>
" title="编辑"><img src="<?php echo $this->_tpl_vars['root_dir']; ?>
/assets/simpla/images/icons/pencil.png" alt="编辑" /></a></td>
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
 ?> </div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "simpla/common/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>