<?php
/**
 * @var $this UView\Home
 */
$this->get_header([
	'title' => '分享你的代码',
	"js" => "js/jquery.form.js",
]);
?>
	<div class="share-box">
		<h2>写下要分享的代码</h2>

		<form id="ShareCode" action="<?php echo get_url('Api', 'Create', 'code') ?>" method="post">
			<div class="form-group">
				<label for="InputText" class="sr-only">代码</label>
				<textarea id="InputText" rows="10" name="code" class="form-control"></textarea>
			</div>
			<div class="form-inline">
				<div class="form-group">
					<label class="sr-only" for="InputLanguage">代码语言</label>

					<div class="input-group">
						<div class="input-group-addon">代码语言</div>
						<select id="InputLanguage" name="lang" class="form-control">
							<?php
							/**
							 * @var $share \ULib\Share\ShareCode
							 */
							$share = class_share("Code");
							echo html_option(list2keymapSK($share->getLangMap(), "name"), "plain") ?>
						</select>
					</div>
				</div>
				<button type="submit" class="btn btn-primary">分享</button>
			</div>
		</form>
		<div id="Result" style="display: none"></div>
		<script>
			$("#ShareCode").ajaxForm(function (data) {
				if (!data.status) {
					alert(data.msg);
				} else {
					$("#Result").html("<p>分享地址为 : <span>" +
					data.data.url + "</span><br>纯代码为 : <span>" + data.data.raw
					+ "</span><br>高亮富文本为 : <span>" + data.data.html
					+ "</span><br>可引用脚本为 : <span>" + data.data.script
					+ "</span></p>").slideDown("slow");
					$("#ShareCode").slideUp("slow");

				}
			});
		</script>
	</div>
<?php $this->get_footer();