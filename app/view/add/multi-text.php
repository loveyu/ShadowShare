<?php
/**
 * @var $this UView\Home
 */
$this->get_header("多行文本分享");
?>
	<div class="share-box">
		<h2>你要分享的内容</h2>

		<form id="ShareMultiText" action="<?php echo get_url('Api', 'Create', 'multi_text') ?>" method="post">
			<div class="form-group">
				<label for="InputText" class="text-left help-block">文本内容，每行一个，会移除多余空行，每次分享顺序取一条数据</label>
				<textarea id="InputText" rows="5" name="text" class="form-control"></textarea>
			</div>
			<button type="submit" class="btn btn-primary">分享</button>
		</form>
		<div id="Result" style="display: none"></div>
		<script type="text/javascript" src="<?php echo $this->get_asset('js/jquery.form.js'); ?>"></script>
		<script>
			$("#ShareMultiText").ajaxForm(function (data) {
				if (!data.status) {
					alert(data.msg);
				} else {
					$("#Result").html("<p>分享地址为 : <span>" +
					data.data.url + "</span><br>纯文本为 : <span>" + data.data.raw
					+ "</span></p>").slideDown("slow");
					$("#ShareMultiText").slideUp("slow");

				}
			});
		</script>
	</div>
<?php $this->get_footer();