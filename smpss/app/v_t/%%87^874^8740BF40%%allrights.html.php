<?php /* Smarty version 2.6.26, created on 2013-01-19 08:20:17
         compiled from simpla/system/allrights.html */ ?>
<div class="rights">
<p><a href="javascript:void(0)">系统权限</a></p>
<ul>
  <li>
    <input type="checkbox" name="system[]" value="系统信息:index" checked="checked" />
    系统信息</li>
  <li>
    <input type="checkbox" name="system[]" value="系统配置:setting" <?php if ($this->_tpl_vars['action']['menu']['system']['setting']): ?>checked="checked"<?php endif; ?> />
    系统配置</li>
  <li>
    <input type="checkbox" name="system[]" value="权限管理:rights" <?php if ($this->_tpl_vars['action']['menu']['system']['rights']): ?>checked="checked"<?php endif; ?> />
    权限管理</li>
  <li>
    <input type="checkbox" name="system[]" value="添加管理组:addrights" <?php if ($this->_tpl_vars['action']['menu']['system']['addrights']): ?>checked="checked"<?php endif; ?> />
    添加管理组</li>
  <li>
    <input type="checkbox" name="system[]" value="系统日志:log" <?php if ($this->_tpl_vars['action']['menu']['system']['log']): ?>checked="checked"<?php endif; ?> />
    日志管理</li>
</ul>
</div>
<div class="rights">
<p><a href="javascript:void(0)">帐号权限</a></p>
<ul>
  <li>
    <input type="checkbox" name="account[]" value="帐号管理:index" <?php if ($this->_tpl_vars['action']['menu']['account']['index']): ?>checked="checked"<?php endif; ?> />
    帐号管理</li>
  <li>
    <input type="checkbox" name="account[]" value="添加帐号:addaccount" <?php if ($this->_tpl_vars['action']['menu']['account']['addaccount']): ?>checked="checked"<?php endif; ?> />
    添加帐号</li>
  <li>
    <input type="checkbox" name="account[]" value="密码修改:modifypwd" <?php if ($this->_tpl_vars['action']['menu']['account']['modifypwd']): ?>checked="checked"<?php endif; ?> />
    密码修改</li>
</ul>
</div>
<div class="rights">
<p><a href="javascript:void(0)">会员管理权限</a></p>
<ul>
  <li>
    <input type="checkbox" name="member[]" value="帐号管理:index" <?php if ($this->_tpl_vars['action']['menu']['member']['index']): ?>checked="checked"<?php endif; ?> />
    帐号管理</li>
  <li>
    <input type="checkbox" name="member[]" value="添加帐号:addmember" <?php if ($this->_tpl_vars['action']['menu']['member']['addmember']): ?>checked="checked"<?php endif; ?> />
    密码修改</li>
  <li>
    <input type="checkbox" name="member[]" value="添加用户组:group" <?php if ($this->_tpl_vars['action']['menu']['member']['group']): ?>checked="checked"<?php endif; ?> />
    添加用户组</li>
</ul>
</div>
<div class="rights">
<p><a href="javascript:void(0)">客户管理权限</a></p>
<ul>
  <li>
    <input type="checkbox" name="customer[]" value="客户管理:index" <?php if ($this->_tpl_vars['action']['menu']['customer']['index']): ?>checked="checked"<?php endif; ?> />
    客户管理</li>
  <li>
    <input type="checkbox" name="customer[]" value="添加客户:addcustomer" <?php if ($this->_tpl_vars['action']['menu']['customer']['addcustomer']): ?>checked="checked"<?php endif; ?> />
    添加客户</li>
</ul>
</div>
<div class="rights">
<p><a href="javascript:void(0)">分类管理权限</a></p>
<ul>
  <li>
    <input type="checkbox" name="category[]" value="分类管理:index" <?php if ($this->_tpl_vars['action']['menu']['category']['index']): ?>checked="checked"<?php endif; ?> />
    分类管理</li>
  <li>
    <input type="checkbox" name="category[]" value="添加分类:category" <?php if ($this->_tpl_vars['action']['menu']['category']['category']): ?>checked="checked"<?php endif; ?> />
    添加分类</li>
</ul>
</div>
<div class="rights">
<p><a href="javascript:void(0)">商品管理权限</a></p>
<ul>
  <li>
    <input type="checkbox" name="goods[]" value="商品管理:index" <?php if ($this->_tpl_vars['action']['menu']['goods']['index']): ?>checked="checked"<?php endif; ?> />
    商品管理</li>
  <li>
    <input type="checkbox" name="goods[]" value="添加商品:addgoods" <?php if ($this->_tpl_vars['action']['menu']['goods']['addgoods']): ?>checked="checked"<?php endif; ?> />
    添加商品</li>
</ul>
</div>
<div class="rights">
<p><a href="javascript:void(0)">入库管理权限</a></p>
<ul>
  <li>
    <input type="checkbox" name="stockin[]" value="入库单管理:index" <?php if ($this->_tpl_vars['action']['menu']['stockin']['index']): ?>checked="checked"<?php endif; ?> />
    入库单管理</li>
  <li>
    <input type="checkbox" name="stockin[]" value="商品入库:stockin" <?php if ($this->_tpl_vars['action']['menu']['stockin']['stockin']): ?>checked="checked"<?php endif; ?> />
    商品入库</li>
</ul>
</div>
<div class="rights">
<p><a href="javascript:void(0)">出库管理权限</a></p>
<ul>
  <li>
    <input type="checkbox" name="stockout[]" value="出库单管理:index" <?php if ($this->_tpl_vars['action']['menu']['stockout']['index']): ?>checked="checked"<?php endif; ?> />
    出库单管理</li>
  <li>
    <input type="checkbox" name="stockout[]" value="商品出库:stockout" <?php if ($this->_tpl_vars['action']['menu']['stockout']['stockout']): ?>checked="checked"<?php endif; ?> />
    商品出库</li>
<!--  <li>
    <input type="checkbox" name="stockout[]" value="商品退货:stockback" <?php if ($this->_tpl_vars['action']['menu']['stockout']['stockback']): ?>checked="checked"<?php endif; ?> />
    商品退货</li>-->
</ul>
</div>
<div class="rights">
<p><a href="javascript:void(0)">退货管理权限</a></p>
<ul>
  <li>
    <input type="checkbox" name="stockback[]" value="退货单管理:index" <?php if ($this->_tpl_vars['action']['menu']['stockback']['index']): ?>checked="checked"<?php endif; ?> />
    退货单管理</li>
  <li>
    <input type="checkbox" name="stockback[]" value="商品退货:stockback" <?php if ($this->_tpl_vars['action']['menu']['stockback']['stockback']): ?>checked="checked"<?php endif; ?> />
    商品退货</li>
<!--  <li>
    <input type="checkbox" name="stockout[]" value="商品退货:stockback" <?php if ($this->_tpl_vars['action']['menu']['stockout']['stockback']): ?>checked="checked"<?php endif; ?> />
    商品退货</li>-->
</ul>
</div>
<div class="rights">
<p><a href="javascript:void(0)">统计管理</a></p>
<ul>
  <li>
    <input type="checkbox" name="statistics[]" value="销售统计:index" <?php if ($this->_tpl_vars['action']['menu']['statistics']['index']): ?>checked="checked"<?php endif; ?> />
    销售统计</li>
  <li>
    <input type="checkbox" name="statistics[]" value="进货统计:sales" <?php if ($this->_tpl_vars['action']['menu']['statistics']['sales']): ?>checked="checked"<?php endif; ?> />
    进货统计</li>
</ul>
</div>