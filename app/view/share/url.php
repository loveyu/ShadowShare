<?php
/**
 * @var $this    UView\Home
 * @var $__share ULib\Share\ShareUrl
 */
$this->get_header();
?>
<div class="share-box">
	<h3>网址分享:</h3>
	<p><a href="<?php echo $__share->getUrl()?>"><?php echo $__share->getUrl()?></a></p>
</div>
<?php
$this->get_footer();