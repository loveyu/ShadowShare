<?php
/**
 * @var $this    UView\Home
 * @var $__share ULib\Share\ShareCode
 */
$this->get_header([
	'title' => '代码分享',
	'js' => [
		'plugins/syntaxhighlighter/scripts/shCore.js',
		'plugins/syntaxhighlighter/scripts/shBrush'.$__share->getLangValue($__share->getLang()).'.js'
	],
	'css' => 'plugins/syntaxhighlighter/styles/shCoreDefault.css'
]);
?>
	<div class="share-box share-code-box">
		<h3>代码分享:</h3>
		<pre class="brush: <?php echo $__share->getLang() ?>;"><?php echo htmlspecialchars($__share->getPrimaryData()) ?></pre>
		<script type="text/javascript">SyntaxHighlighter.all();</script>
	</div>
<?php
$this->get_footer();