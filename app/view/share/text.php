<?php
/**
 * @var $this    UView\Home
 * @var $__share ULib\Share\ShareText
 */
$this->get_header("文本分享");
?>
	<div class="share-box share-text-box">
		<h3>文本分享:</h3>

		<p><?php echo str_replace("\n", "<br>", htmlspecialchars($__share->getPrimaryData())) ?></p>
	</div>
<?php
$this->get_footer();