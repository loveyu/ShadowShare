<?php
/**
 * @var $this    UView\Home
 * @var $__share ULib\Share\SharePicture
 */
$this->get_header("图片分享");
?>
	<div class="share-box">
		<h3>图片分享:</h3>
		<p>名称：<?php echo $__share->getFileName()?></p>
		<p>大小：<?php echo $__share->getFileReadSize()?></p>
		<p>分辨率：<?php echo $__share->getWidth()?>x<?php echo $__share->getHeight()?></p>
		<div class="share-picture-box">
			<img src="<?php echo get_url($__share->getUname())?>?m=img" class="img-responsive">
		</div>
	</div>
<?php
$this->get_footer();