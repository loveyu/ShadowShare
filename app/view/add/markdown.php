<?php
/**
 * @var $this \UView\Home
 */
$this->get_header([
	"title" => "创建Markdown分享",
	"js" => "plugins/markdown/bootstrap-markdown.js",
	"css" => "plugins/markdown/bootstrap-markdown.min.css"
]);
?>
	<div id="ShareMarkdownBox">
		<label for="InputMarkdown" class="sr-only">输入内容</label>
		<textarea id="InputMarkdown" rows="8" class="form-control" data-provide="markdown" name="markdown-data"></textarea>
	</div>
	<div class="share-box" style="display: none">
		<div id="Result"></div>
	</div>
	<script>
		$("#InputMarkdown").markdown({
			savable: true,
			language: "zh",
			onPreview: function (e) {
				var previewContent;
				var originalContent = e.getContent();
				var status = $.ajaxSettings.async;
				$.ajaxSetup({
					async: false
				});
				$.post("<?php echo get_url("Api","Tool","parse_markdown")?>", {data: originalContent}, function (data) {
					if (data['status']) {
						previewContent = data['data'];
					} else {
						previewContent = "异常信息：" + data['msg'];
					}
					$.ajaxSetup({
						async: status
					});
				});
				return previewContent;
			},
			onSave: function (e) {
				var data = e.getContent();
				e.disableButtons('all');
				$.post('<?php echo get_url('Api', 'Create', 'markdown') ?>', {data: data}, function (result) {
					e.enableButtons('all');
					if (result.status) {
						$("#Result").html("<p>分享地址为 : <span>" +
						result.data.url + "</span><br>纯Markdown内容 : <span>" + result.data.raw
						+ "</span><br>HTML富文本内容 : <span>" + result.data.html
						+ "</span></p>");
						$("#ShareMarkdownBox").slideUp("slow");
						$(".share-box").slideDown("slow");
					} else {
						alert(result.msg);
					}
				});
			}
		});
	</script>
<?php
$this->get_footer();