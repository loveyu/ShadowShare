<?php
/**
 * @var $this    UView\Home
 * @var $__share ULib\Share\SharePictureText
 */
$this->get_header("图文分享");
?>
	<div class="share-box">
		<h3>图文分享:</h3>
		<?php echo $__share->getHtml() ?>
	</div>
<?php
$this->get_footer();