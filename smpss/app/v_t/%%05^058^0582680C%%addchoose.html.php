<?php /* Smarty version 2.6.26, created on 2012-12-15 07:10:35
         compiled from simpla/stockback/addchoose.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_url', 'simpla/stockback/addchoose.html', 73, false),)), $this); ?>
<div>
	<h2>添加商品</h2>
	<div class="clear"></div>
	<div class="content-box">
		<div class="content-box-content">
			<div class="tab-content default-tab" id="tab1">
				<form id="outaddoneform">
				<table class="centertable selectlist">
					<input type="hidden" id="goods_id" name="goods_id"/>
					<tbody id="goodsinfo">
						<tr>
							<td class="input-label short-label">商品编号:</td>
							<td><input type="text" id="goods_sn" name="goods_sn" class="text-input" ondblclick="$(this).autocomplete('search');" size="32"/></td>
							<td style="padding: 0; text-align:left;"><a href="javascript:;" tabindex="-1" id="goods_sn_sel">&nbsp;▼&nbsp;</a></td>
						</tr>
						<tr>
							<td class="input-label short-label">商品名称(中):</td>
							<td><input type="text" id="goods_name_chn" name="goods_name_chn" class="text-input" size="32" readonly/></td>
						</tr>
						<tr>
							<td class="input-label short-label">商品名称(泰):</td>
							<td><input type="text" id="goods_name_tha" name="goods_name_tha" class="text-input" size="32" readonly/></td>
						</tr>
						<!--tr>
							<td class="input-label short-label">数量(件):</td>
							<td><input type="text" id="goods_pack_num" name="goods_pack_num" class="text-input" onchange="setTotalPrice()" size="32"/></td>
						</tr-->
						<tr>
							<td class="input-label short-label">装箱数量:</td>
							<td><input type="text" id="goods_pack_size" name="goods_pack_size" class="text-input" size="32" onchange="setTotalPrice()" readonly/></td>
						</tr>
						<tr>
							<td class="input-label short-label">库存:</td>
							<td><input type="text" id="goods_stock" name="goods_stock" class="text-input" size="32" readonly/></td>
						</tr>

						<!--tr>
							<td class="input-label short-label">单价:</td>
							<td><input type="text" id="goods_unitprice" name="goods_unitprice" class="text-input" size="32" onchange="setTotalPrice()" /></td>
						</tr-->
						<!--tr>
							<td class="input-label short-label">总价:</td>
							<td><input type="text" id="goods_totalprice" name="goods_totalprice" class="text-input" size="32" onchange="setTotalPrice()" readonly/></td>
						</tr-->
						<!--tr>
							<td class="input-label short-label">商品备注:</td>
							<td><textarea id="goods_note" name="goods_note" class="text-input textarea"></textarea></td>
						</tr-->
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2">
								<input type="button" class="button" id="btn_yes" value="确认"/>
								<input type="button" class="button" id="btn_no" value="取消"/>
							</td>
						</tr>
					</tfoot>
				</table>
			</form>
				<div id="log"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function setTotalPrice() {
	$("#goods_totalprice").val($("#goods_pack_num").val() * $("#goods_pack_size").val() * $("#goods_unitprice").val());
}
$(function() {
	$("#btn_yes").click(function(){
		$.ajax({
			type: "POST",
		url: "<?php echo smarty_function_get_url(array('rule' => '/stockback/stockback','data' => 'ac=ajaxaddyes'), $this);?>
",
			dataType: "json",
			data: $('#outaddoneform').serialize(),
			beforeSend: function() {
				$().message("处理中..");
			},
			success: function(result) {
				var err = result.errcode;
				var msg = result.errmsg;
				if(err == 0){
					$.fancybox.close();
					$().message("添加成功");
					$("#jqlist").trigger("reloadGrid");
				} else{
					$().message("添加失败: " + msg);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$().message("请求失败: " + textStatus);
			}
		});
	});
	$("#btn_no").click(function(){
		$.fancybox.close();
	});
	function log(msg) {
		$("<div/>").text(msg).prependTo("#log");
		$("#log").scrollTop(0);
	}

	gsn = $( "#goods_sn" ).autocomplete({
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
					$("#goods_id").val(data.goods_id);
					$("#goods_sn").val(data.goods_sn);
					$("#goods_name_chn").val(data.goods_name_chn);
					$("#goods_name_tha").val(data.goods_name_tha);
					$("#goods_pack_size").val(data.goods_pack_size);
					$("#goods_stock").val(data.goods_stock);
					//setTotalPrice();
				}
			});
		}
	});
	$("#goods_sn_sel").click(function(){
		gsn.focus();
		if(gsn.autocomplete("widget").is(":visible")){
			gsn.autocomplete("close");
			return;
		}
		$(this).blur();
		gsn.autocomplete("search", "");
	});

});
</script>