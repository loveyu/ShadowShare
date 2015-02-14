<?php
/**
 * @var $this UView\Home
 */
$this->get_header();
?>
	<h1>分享你的网址</h1>
	<form class="form-inline" action="<?php echo get_url('Api','Create','url')?>" method="post">
		<div class="form-group">
			<label class="sr-only" for="InputUrl">网址</label>
			<div class="input-group">
				<div class="input-group-addon">#</div>
				<input type="url" name="url" class="form-control" id="InputUrl" placeholder="网址">
			</div>
		</div>
		<button type="submit" class="btn btn-primary">分享</button>
	</form>
<?php $this->get_footer();