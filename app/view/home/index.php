<?php
/**
 * @var $this UView\Home
 */
$this->get_header();
?>
	<h1>阅后即隐，分享一小会</h1>
	<p><a href="<?php echo get_url('Home','all_list')?>">全部分享</a></p>
	<div>
		<?php
		foreach([
			'url' => '网址',
			'text' => '文本',
			'file' => '文件',
			'code' => '代码',
			'markdown' => 'MarkDown',
			'picture' => '图片',
			'picture-text' => '图文',
			'multi-text' => '多行文本'
		] as $name => $value):
			?>
			<a href="<?php echo get_url('add',$name)?>" class="label label-primary"><?php echo $value?></a>
		<?php
		endforeach;
		?>
	</div>
<?php $this->get_footer();