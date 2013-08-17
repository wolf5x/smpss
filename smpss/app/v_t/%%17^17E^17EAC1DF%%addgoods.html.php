<?php /* Smarty version 2.6.26, created on 2013-08-17 00:58:56
         compiled from simpla/goods/addgoods.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/goods/addgoods.html', 12, false),)), $this); ?>
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
<style>.promote{display:none;}</style>
<div id="main-content">
  <h2>欢迎您 <?php echo $this->_tpl_vars['_adminname']; ?>
</h2>
  <p id="page-intro">1.你可以在这里添加新的商品或者编辑已有的商品。2.新添加商品的时候可以同时进行入库(入库必须有数量和进价)！3.带 <span class="red">*</span> 的项目必填</p>
  <div class="clear"></div>
  <div class="content-box">
    <div class="content-box-header">
      <h3>添加商品</h3>
      <ul class="content-box-tabs">
        <li><a href="<?php echo smarty_function_get_url(array('rule' => "/goods/index"), $this);?>
">商品管理</a></li>
        <li><a href="#tab1" class="default-tab">添加商品</a></li>
      </ul>
      <div class="clear"></div>
    </div>
    <div class="content-box-content">
      <div class="tab-content default-tab" id="tab1">
        <div class="form">
          <form action="<?php echo smarty_function_get_url(array('rule' => '/goods/addgoods'), $this);?>
" method="post" id="js-form" enctype="multipart/form-data">
            <fieldset class="clearfix">
              <input type="hidden" value="<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
" name="goods_id" />
              <p>
                <label><font class="red"> * </font>商品条形码：</label>
                <input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_sn']; ?>
" class="text-input small-input" name="goods_sn" id="goods_sn" />
                <span></span><input type="button" id="getbarcode" class="button" value="生成条形码" /> &nbsp;<a id="img" style="display:none" href="" target="_blank">查看条形码</a></p>
              <p>
                <label><font class="red"> * </font>商品中文名称：</label>
                <input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_name_chn']; ?>
" class="text-input small-input" name="goods_name_chn" id="goods_name_chn" />
                <span></span> </p>
			  <p>
                <label><font class="red"> * </font>商品泰文名称：</label>
                <input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_name_tha']; ?>
" class="text-input small-input" name="goods_name_tha" id="goods_name_tha" />
                <span></span> </p>
			  <p>
                <label>库存(件)：</label>
                <input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_stock']; ?>
" class="text-input small-input" name="goods_stock" id="goods_stock" />
                <span></span> </p>
              <p>
                <label><font class="red"> * </font>装箱数量：</label>
                <input type="text" value="<?php echo $this->_tpl_vars['goods']['goods_pack_size']; ?>
" class="text-input small-input" name="goods_pack_size" id="goods_pack_size" />
                <span></span> </p>
			  <p>
			    <label>商品简介：</label>
                <textarea name="goods_note" class="text-input textarea"><?php echo $this->_tpl_vars['goods']['goods_note']; ?>
</textarea>
                <span> </span> <br /><small>不超过100个汉字</small></p>
			  <p>
                <label>商品图片：</label>
				<input type="file" name="goods_pic_origin" id="goods_pic_origin" value="" onchange="checkPic();"/>
			  </p>
              <dt>
                <input type="submit" name="" id="button" class="button" value="<?php if ($this->_tpl_vars['goods']['goods_id']): ?>编辑<?php else: ?>添加<?php endif; ?>" />
              </dt>
            </fieldset>
          </form>
        </div>
      </div>
      <!-- End #tab1 --> 
    </div>
    <!-- End .content-box-content --> 
  </div>
  <!-- End .content-box --> 
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
<script type="text/javascript" language="javascript">
        function checkPic() {
			//alert("fuck");
            var picPath = document.getElementById("goods_pic").value;
		//	alert(picPath);
            var type = picPath.substring(picPath.lastIndexOf(".") + 1, picPath.length).toLowerCase();
         //   alert(type);
			if (type != "jpg" && type != "bmp" && type != "gif" && type != "png") {
                alert("请上传正确的图片格式");
				document.getElementById("goods_pic").value="";
                return false;
            }
            return true;
        }
        //图片预览
        function PreviewImage(divImage, upload, width, height) {
            if (checkPic()) {
                try {
                    var imgPath;      //图片路径

                    var Browser_Agent = navigator.userAgent;
                    //判断浏览器的类型
                    if (Browser_Agent.indexOf("Firefox") != -1) {
                        //火狐浏览器

//getAsDataURL在Firefox7.0 无法预览本地图片，这里需要修改
                        imgPath = upload.files[0].getAsDataURL();
                        document.getElementById(divImage).innerHTML = "<img id='imgPreview' src='" + imgPath + "' width='" + width + "' height='" + height + "'/>";
                    } else {
                        //IE浏览器 ie9 必须在兼容模式下才能显示预览图片
                        var Preview = document.getElementById(divImage);
                        Preview.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = upload.value;
                        Preview.style.width = width;
                        Preview.style.height = height;
                    }
                } catch (e) {
					alert(e);
                    alert("请上传正确的图片格式");
                }
            }
        }
</script>
<script type="text/javascript">
    $(function() { 
		e = $(".ispromote:checked").val();
		if(e==1) $(".promote").show();
        $(".ispromote").click (function(){
			if($(".ispromote").attr("checked")){
				$(".promote").show();
			}else{
				$(".promote").hide();
			}
		});
		$("#getbarcode").click(function(){
			$.post("<?php echo smarty_function_get_url(array('rule' => "/ajax/getbarcode"), $this);?>
",{},function(result){
				if(result){
					$("#goods_sn").val(result.code);
					$("#img").show();
					$("#img").attr("href",result.imgsrc);
				}else{
					alert("生成出错！");
				}
			},"json")
		})
    });
</script>