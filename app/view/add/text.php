<?php
/**
 * @var $this UView\Home
 */
$this->get_header("分享一段文本");
?>
	<div class="share-box">
		<h2>写下你想要分享的内容</h2>

		<form id="ShareText" action="<?php echo get_url('Api', 'Create', 'text') ?>" method="post">
			<div class="form-group">
				<label for="InputText" class="sr-only">文本内容</label>
				<textarea id="InputText" rows="5" name="text" class="form-control"></textarea>
			</div>
			<button type="submit" class="btn btn-primary">分享</button>
		</form>
		<div id="Result" style="display: none"></div>
		<script type="text/javascript" src="<?php echo $this->get_asset('js/jquery.form.js'); ?>"></script>
		<script>
			$("#ShareText").ajaxForm(function (data) {
				if (!data.status) {
					alert(data.msg);
				} else {
					$("#Result").html("<p>分享地址为 : <span>" +
					data.data.url + "</span><br>纯文本为 : <span>" + data.data.raw
					+ "</span></p>").slideDown("slow");
					$("#ShareText").slideUp("slow");

				}
			});
		</script>
	</div>
<?php $this->get_footer();