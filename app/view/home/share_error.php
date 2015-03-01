<?php
/**
 * @var $__msg string
 * @var $this  \UView\Home
 */
header("HTTP/1.1 423 Locked");
$this->get_header("分享获取失败"); ?>
	<div class="share-error">
		<h4 class="text-danger"><?php echo $__msg ?></h4>
	</div>
<?php $this->get_footer();