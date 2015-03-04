<?php
/**
 * @var $this    UView\Home
 * @var $__share ULib\Share\ShareMultiText
 */
$this->get_header("动态文本分享");
?>
	<div class="share-box share-text-box">
		<h3>文本分享:</h3>

		<p><?php echo str_replace("\n", "<br>", htmlspecialchars($__share->getPrimaryData())) ?></p>
	</div>
	<p class="help-block"><small>当前IP : <?php echo $__share->getNowIp()?></small></p>
<?php
$this->get_footer();