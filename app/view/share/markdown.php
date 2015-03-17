<?php
/**
 * @var $this    UView\Home
 * @var $__share ULib\Share\ShareMarkdown
 */
$this->get_header("Markdown分享");
?>
	<div class="share-box share-box-markdown">
		<h3>Markdown分享:</h3>

		<div role="tabpanel">

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation"><a href="#m_markdown" aria-controls="m_markdown" role="tab" data-toggle="tab">Markdown</a></li>
				<li role="presentation" class="active"><a href="#m_html" aria-controls="m_html" role="tab" data-toggle="tab">Html</a></li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane" id="m_markdown">
					<pre><code lang="markdown"><?php echo htmlspecialchars($__share->getPrimaryData()) ?></code></pre>
				</div>
				<div role="tabpanel" class="tab-pane active" id="m_html"><?php echo $__share->getHtml() ?></div>
			</div>
		</div>
	</div>
<?php
$this->get_footer();