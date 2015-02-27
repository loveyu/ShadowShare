<?php
send_http_status(500);
/**
 * @var $__msg string
 * @var $this  \UView\Home
 */
$this->get_header("服务器错误"); ?>
	<div class="share-error">
		<h4 class="text-danger"><?php echo $__msg ?></h4>
	</div>
<?php $this->get_footer();