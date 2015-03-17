<?php
/**
 * User: loveyu
 * Date: 2015/3/16
 * Time: 15:27
 * @var $this    ULib\Page
 * @var $__count array 统计数据
 */
$this->get_header("我的分享中心");
?>
	<div class="row box-sc-row">
		<?php foreach($__count as $type => $value_map): ?>
			<div class="col-sm-3 box-sc-share sc-<?php echo strtolower($value_map['name']) ?>">
				<a href="<?php echo get_url('list', strtolower($value_map['name'])) ?>">
					<span class="desc"><?php echo $value_map['desc'] ?></span>
					<span class="count"><?php echo $value_map['count'] ?></span>
				</a>
			</div>

		<?php endforeach; ?>
	</div>
<?php $this->get_footer();