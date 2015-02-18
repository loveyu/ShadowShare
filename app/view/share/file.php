<?php
/**
 * @var $this    UView\Home
 * @var $__share ULib\Share\ShareFile
 */
$this->get_header("文件分享");
?>
	<div class="share-box">
		<h3>文件分享:</h3>
		<p>文件名：<?php echo $__share->getFileName()?></p>
		<p>文件大小：<?php echo $__share->getFileReadSize()?></p>
		<p>下载地址：<a href="<?php echo get_url($__share->getUname())?>?m=bin"><?php echo get_url($__share->getUname())?>?m=bin</a></p>
	</div>
<?php
$this->get_footer();