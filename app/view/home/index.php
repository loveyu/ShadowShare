<?php
/**
 * @var $this UView\Home
 */
$this->get_header();
?>
	<div class="main-share-list row">
		<?php
		foreach([
			'url' => '网址',
			'text' => '文本',
			'file' => '文件',
			'code' => '代码',
			'markdown' => 'Markdown',
			'picture' => '图片',
			'picture-text' => '图文',
			'multi-text' => '多行文本'
		] as $name => $value):
			?>
			<div class="col-xs-3">
				<a href="<?php echo get_url('add', $name)?>"><?php echo $value?></a>
			</div>
		<?php
		endforeach;
		?>
	</div>
<?php $this->get_footer();