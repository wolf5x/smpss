<?php /* Smarty version 2.6.26, created on 2012-12-15 07:10:33
         compiled from simpla/stockback/add.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/stockback/add.html', 11, false),)), $this); ?>
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
	<p id="page-intro">商品退货。带<font class="red"> * </font>为必填项目。先把商品加入订单。所有商品都加入订单后点<font class="red">确认提交</font>完成！</p>
	<div class="clear"></div>
	<div class="content-box">
		<div class="content-box-header">
			<h3>商品退货订单</h3>
			<ul class="content-box-tabs">
				<li><a href="<?php echo smarty_function_get_url(array('rule' => "/stockback/index"), $this);?>
">退货单管理</a></li>
				<li><a href="#tab1" class="default-tab">商品退货</a></li>
				<!--	<li><a href="<?php echo smarty_function_get_url(array('rule' => "/stockback/stockback"), $this);?>
">商品退货</a></li> -->
			</ul>
			<div class="clear"></div>
		</div>
		<div class="content-box-content">
			<div class="tab-content default-tab" id="tab1">
				<div class="form">
					<form action="<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=out'), $this);?>
" method="post" id="outidxform">
						<fieldset class="clearfix">
							<table>
								<tr>
									<td class="input-label short-label"><font class="red">* </font>客户名称:
									</td>
									<td>
										<input type="text" name="customer_name" id="customer_name" value="<?php echo $this->_tpl_vars['info']['index']['customer_name']; ?>
" class="text-input" size='60' ondblclick="$(this).autocomplete('search','')"/><a href="javascript:;" tabindex="-1" id="customer_name_sel">&nbsp;▼&nbsp;</a>
									</td>
									<td class="input_label short-label"></td><!--日期-->
									<td></td>
								</tr>
								<tr>
									<td class="input-label">退货单备注:</td>
									<td colspan="3"><textarea name="stockback_note" id="stockback_note" class="text-input textarea"><?php echo $this->_tpl_vars['info']['index']['stockback_note']; ?>
</textarea></td>
								</tr>
							</table>
							<table id="jqlist"></table>
							<div id="jqpager"></div>
							<div id="jqopt" align="center">
								<!--
								<input type="button" class="button" id="clr_btn" value="清空" />
								<input type="button" class="button" id="add_btn" value="新增" />
								-->
								&nbsp;&nbsp;<input type="button" class="button" id="out_btn" value="确认退货" />
							</div>
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
	var lastSel = 0;
	$(function(){
		$('#jqlist').jqGrid({
			//caption: '退货商品列表',
			mtype: "POST",
			url: "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=ajaxgetlist'), $this);?>
",
			editurl: "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=ajaxeditrow'), $this);?>
",
			//editurl: 'clientArray',
			datatype: "json",
			colNames:['操作', '编号','商品名称(中/泰)','数量','库存','装箱数量','总价','备注'],
			colModel:[
		{name:'op',index:'op',width:"30",sortable:false},
			{name:'goods_sn',index:'goods_sn',width:"80"},
			{name:'goods_name',index:'goods_name',width:"160"},
			{name:'goods_pack_num',index:'goods_pack_num',width:"40",editable:true},
			{name:'goods_stock',index:'goods_stock',width:"40"},
			{name:'goods_pack_size',index:'goods_pack_size',width:"40"},
		//	{name:'goods_unitprice',index:'goods_unitprice',width:"50",editable:true
		//	},
			{name:'goods_totalprice',index:'goods_totalprice',width:"60", editable: true},
			{name:'goods_note',index:'goods_note',width:"80",editable:true}
		],
			rowNum: -1,
			//page:"curpage";
			//total:"totalpage";
			//rowList: [10,20,50],
			//	pager: '#jqpager',
			autowidth: true,
			autoheight: true,
			height: 'auto',
			viewrecords: true,
			//multiselect: true,
			sortable: true,
			sortname: 'goods_sn',
			sortorder: 'desc',
			footerrow: true,
			gridComplete: function() {
				//$("#jqlist").closest(".ui-jqgrid-bdiv").css({ "overflow-x" : "hidden" });
				//id, rows[#], total
				var total = 0;
				var ids = jQuery("#jqlist").jqGrid('getDataIDs');
				for(var i = 0; i < ids.length; i++) {
					setListRowIcon($(this), ids[i]);
				}
				ladd = "<a href='#' title='新增' onclick='addRow()'><span class='ui-button ui-icon ui-icon-plus'></span></a>";
				lclr = "<a href='#' title='清空' onclick='clearList()'><span class='ui-button ui-icon ui-icon-trash'></span></a>";
				//ladd = "<a href='#' title='新增' onclick='addRow()'>新增</a>";
				//lclr = "<a href='#' title='清空' onclick='clearList()'>清空</a>";
				opr = lclr + ladd;
				updateGridTotalPrice($(this));
				$(this).jqGrid('footerData', 'set', {op: opr});
			},
			beforeSelectRow: function(id) {
								 return false;
							 },
			ondblClickRow: function(rowid, iRow, iCol, e) {
							   //if(id && id !== lastSel) {
							   $("#jqlist").jqGrid('restoreRow', rowid);
							   //	 lastSel = id;
							   //}
							   modifyRow(rowid, iRow, iCol, e);
						   }

		});

		cname = $("#customer_name").autocomplete({
			delay: 0,
			  minLength: 0,
			  source: function(request, response){
				  $.ajax({
					  url: "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=au&autype=cus'), $this);?>
",
				  dataType: "json",
				  data: {
					  prefix: request.term,
				  minLength: 0
				  },
				  success: function(data) {
							   response($.map(data.rows, function(item){
								   return {
									   label: item.customer_name,
							   value: item.customer_name,
							   id: item.customer_id
								   }
							   }));
						   }
				  });
			  }
		});
		$("#customer_name_sel").click(function(){
			cname.focus();
			if(cname.autocomplete("widget").is(":visible")){
				cname.autocomplete("close");
				return;
			}
			$(this).blur();
			cname.autocomplete("search", "");
		});
		$("#out_btn").click(function(){
			if(confirm("确认退货?")) {
				$.ajax({
					type: "post",
					dataType: "json",
					url: "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=ajaxout'), $this);?>
",
					data: $("#outidxform").serialize(),
					beforeSend: function() {
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
									 $().message("退货成功, 跳转到打印页面");
									 var u = "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=print&sid=${}'), $this);?>
";
									 u = u.replace("\${}",rsp.stockback_sn);
									 //window.location.href = u;
									 $().message("退货成功!");
									 window.location.href = "<?php echo smarty_function_get_url(array('rule' => '/stockback/index'), $this);?>
";
									 newWindow(u);
								 } else{
									 $().message("退货失败: " +  errmsg);
								 }

							 }

				});

			}
		});
		$("#add_btn").click(function(){
			addRow();
		});

		$("#clr_btn").click(function(){
			clearList();
		});
	});
function clog(obj) {
	var s = "";
	for(var p in obj) {
		s = s + p + " : " + obj[p] + "\n";
	}
	console.log(s);
}
function addRow() {
	$.fancybox({
		'type':'ajax',
		'href':"<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=ajaxaddrow'), $this);?>
"
	});
}
function clearList() {
	var sels = $("#jqlist").jqGrid('getGridParam','selarrrow');
	if(false && sels==""){
		$().message("请选择要移除的商品！");
	} else{
		if(confirm("确定清空？")) {
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=ajaxclr'), $this);?>
",
				//data: "ids="+sels,
				befordSend: function() {
					$().message("处理中..");
				},
				error: function(jqXHR, textStatus, errorThrown) {
						   $().message("请求失败: " + textStatus);
					   },
				success: function(result) {
							 var err = result.errcode;
							 var msg = result.errmsg;
							 if(err == 0){
								 /*	var arr = msg.split(',');
									$.each(arr, function(i,n){
									if(arr[i]!=""){
									$("#jqlist").jqGrid('delRowData',n);
									}
									});*/
								 $("#jqlist").trigger("reloadGrid");
								 $().message("清空成功！");
							 } else{
								 $().message("操作失败: " + msg);
							 }
						 }
			});
		}
	}
}
function modifyRow(rowid, iRow, iCol, e) {
	var grid = $("#jqlist");
	setEditRowIcon($("#jqlist"), rowid);
	grid.jqGrid('editRow', rowid, 
			{
				keys: true,
		restoreAfterError: false,
		extraparam: { goods_id: rowid },
		oneditfunc: g_oneditfunc,
		successfunc: g_successfunc,
		aftersavefunc: g_aftersavefunc,
		errorfunc: g_errorfunc,
		afterrestorefunc: g_afterrestorefunc
			}
			);
	// set focus to the clicked column
	if(e){
		$("input, select", e.target).focus();
	}
}
function saveRow(id) {
	$("#jqlist").jqGrid('saveRow', id,
			{
				restoreAfterError: false,
	extraparam: { goods_id: id },
	oneditfunc: g_oneditfunc,
	successfunc: g_successfunc,
	aftersavefunc: g_aftersavefunc,
	errorfunc: g_errorfunc,
	afterrestorefunc: g_afterrestorefunc
			}
			);
}
function cancelRow(id) {
	$("#jqlist").jqGrid('restoreRow', id,
			{
				afterrestorefunc: g_afterrestorefunc
			}
			);
}
function updateGridTotalPrice(grid) {
	var totalprice = 0;
	var ids = jQuery("#jqlist").jqGrid('getDataIDs');
	for(var i = 0; i < ids.length; i++) {
		id = ids[i];
		totalprice += parseFloat(grid.jqGrid('getCell', id, 'goods_totalprice'));
	}
	grid.jqGrid('footerData', 'set', {goods_pack_size: '合计:' , goods_totalprice: totalprice.toFixed(2)});
}
function g_oneditfunc(id, iRow, iCol, e) {
	var grid = $("#jqlist");
	$("#"+id+"_goods_sn").autocomplete({
		delay: 0,
		minLength: 0,
		source: function(request, response){
			$.ajax({
				url: "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=au&autype=list'), $this);?>
",
			dataType: "json",
			data: {
				prefix: request.term,
			minLength: 0
			},
			success: function(data) {
						 response($.map(data.rows, function(item){
							 return {
								 label: item.goods_sn + ", " + item.goods_name_chn + ", " + item.goods_name_tha,
						 value: item.goods_sn,
						 id: item.goods_id
							 }
						 }));
					 }
			});
		},
		select: function( event, ui ) {
					$.ajax({
						url: "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=au&autype=ginfo'), $this);?>
",
					dataType: "json",
					data: {
						goods_id: ui.item.id
					},
					success: function(data) {
								 grid.jqGrid('setCell', id, 'goods_id', data.goods_id);
								 grid.jqGrid('setCell', id, 'goods_name', data.goods_name_chn + '<br>' + data.goods_name_tha);
								 $("#"+id+"_goods_pack_num").val(null);
								 grid.jqGrid('setCell', id, 'goods_pack_size', data.goods_pack_size);
							//	 $("#"+id+"_goods_unitprice").val(null);
								 grid.jqGrid('setCell', id, 'goods_totalprice', data.goods_totalprice);
								 $("#"+id+"_goods_note").val(null);
							 }
					});
				}
	});
}
function g_successfunc(rsp) {
	var rspmsg = $.parseJSON(rsp.responseText);
	var errcode = rspmsg.errcode;
	if(errcode == 0){
		return true;
	} else{
		return false;
	}
}
function g_aftersavefunc(id, rsp) {
	var grid = $("#jqlist");
	setListRowIcon(grid, id);
	var rspmsg = $.parseJSON(rsp.responseText);
	grid.jqGrid('setRowData', id, {goods_totalprice: rspmsg.goods_totalprice});
	updateGridTotalPrice(grid);
	$().message('修改成功');
}
function g_errorfunc(id, rsp) {
	var rspmsg = $.parseJSON(rsp.responseText);
	$().message('修改失败: ' + rspmsg.errmsg);
}
function g_afterrestorefunc(id) {
	setListRowIcon($("#jqlist"), id);
}
function setEditRowIcon(grid, id) {
	yes = "<a href='javascript:;' title='确认' onclick='saveRow(" + id + ")'><span class='ui-button ui-icon ui-icon-disk'></span></a>";
	cancel = "<a href='javascript:;' title='取消' onclick='cancelRow(" + id + ")'><span class='ui-button ui-icon ui-icon-cancel'></span></a>";
	//yes = "<a href='javascript:;' title='保存' onclick='saveRow(" + id + ")'>保存</a>";
	//cancel = "<a href='javascript:;' title='取消' onclick='cancelRow(" + id + ")'>取消</a>";
	grid.jqGrid('setRowData', id, {op: cancel + yes });
}
function setListRowIcon(grid, id) {
	mod = "<a href='javascript:;' title='编辑' onclick='modifyRow(" + id + ")'><span class='ui-button ui-icon ui-icon-pencil'></span></a>";
	del = "<a href='javascript:;' title='删除' onclick='deleteRow(" + id + ")'><span class='ui-button ui-icon ui-icon-close'></span></a>";
	//mod = "<a href='javascript:;' title='编辑' onclick='modifyRow(" + id + ")' class='button'>编辑</a>";
	//del = "<a href='javascript:;' title='删除' onclick='deleteRow(" + id + ")' class='button'>删除</a>";
	grid.jqGrid('setRowData', id, {op: del + mod });
}

function deleteRow(id) {
	if(confirm("确定移除该商品?")) {
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=ajaxdelrow'), $this);?>
",
			data: {
				goods_id: id
			},
			befordSend: function() {
							$().message("处理中..");
						},
			error: function(jqXHR, textStatus, errorThrown) {
					   $().message("请求失败: " + textStatus);
				   },
			success: function(rsp) {
						 errcode = rsp.errcode;
						 errmsg = rsp.errmsg;
						 if(errcode == 0){
							 $("#jqlist").trigger("reloadGrid");
							 $().message("移除成功");
						 } else{
							 $().message("操作失败: " + errmsg);
						 }
					 }
		});
	}	
}

function newWindow(href) {
	//
	//
	//
	//
	//
	window.open(href);
	return false;
}
</script>