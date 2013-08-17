<?php /* Smarty version 2.6.26, created on 2013-05-02 02:06:39
         compiled from simpla/account/modifypwd.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/account/modifypwd.html', 11, false),)), $this); ?>
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
  <p id="page-intro">修改帐号密码。</p>
  <div class="clear"></div>
  <div class="content-box">
    <div class="content-box-header">
      <h3>修改帐号密码</h3>
      <ul class="content-box-tabs">
        <li><a href="<?php echo smarty_function_get_url(array('rule' => "/account/index"), $this);?>
">帐号管理</a></li>
        <li><a href="<?php echo smarty_function_get_url(array('rule' => "/account/addaccount"), $this);?>
">添加帐号</a></li>
        <li><a href="#tab1" class="default-tab">密码修改</a></li>
      </ul>
      <div class="clear"></div>
    </div>
    <div class="content-box-content">
      <div class="tab-content default-tab" id="tab1">
        <div class="form">
          <form action="<?php echo smarty_function_get_url(array('rule' => '/account/modifypwd'), $this);?>
" method="post" id="js-form">
            <fieldset class="clearfix">
                <p>
                  <label><font class="red"> * </font>原密码：</label>
                  <span>
                  <input type="password" value="" class="text-input small-input" name="old_pwd" />
                  </span> </p>
                <p>
                  <label><font class="red"> * </font>新密码：</label>
                  <span>
                  <input type="password" value="" class="text-input small-input" name="new_pwd" />
                  </span> </p>
                <p>
                  <label><font class="red"> * </font>新密码：</label>
                  <span>
                  <input type="password" value="" class="text-input small-input" name="new_pwd2" />
                  </span> </p>
              <dt>
                <input type="submit" name="" class="button" value="修改" />
              </dt>
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