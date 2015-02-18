<?php
/**
 * @var $this UView\Home
 */
$this->get_header("创建一个网址分享");
?>
	<div class="share-box">
		<h2>分享你的网址</h2>

		<form id="ShareUrl" class="form-inline" action="<?php echo get_url('Api', 'Create', 'url') ?>" method="post">
			<div class="form-group">
				<label class="sr-only" for="InputUrl">网址</label>

				<div class="input-group">
					<div class="input-group-addon">#</div>
					<input type="url" name="url" class="form-control" id="InputUrl" placeholder="网址">
				</div>
			</div>
			<button type="submit" class="btn btn-primary">分享</button>
			<div id="Result" style="display: none"></div>
		</form>
		<script type="text/javascript" src="<?php echo $this->get_asset('js/jquery.form.js'); ?>"></script>
		<script>
			$("#ShareUrl").ajaxForm(function (data) {
				if (!data.status) {
					alert(data.msg);
				} else {
					$("#Result").html("<p>分享地址为 : <span>" + data.data.url + "</span><br>跳转地址为 : <span>" + data.data.redirect + "</span></p>").slideDown("slow");
					$("#InputUrl").val("");
				}
			});
		</script>
	</div>
<?php $this->get_footer();