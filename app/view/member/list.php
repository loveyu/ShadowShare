<?php
/**
 * User: loveyu
 * Date: 2015/3/16
 * Time: 15:27
 * @var $this    ULib\Page
 * @var $__data  array 数据数据
 * @var $__name  string 名称
 */
$this->get_header("我的 {$__name} 分享");
?>
	<h2>我的<strong><?php echo $__name ?></strong>分享</h2>
	<ul class="list-unstyled my-list-share">
		<?php foreach($__data as $value): ?>
			<li>
				<p>分享地址 : <a href="<?php echo $value['url'] ?>" target="_blank"><?php echo $value['url'] ?></a>&nbsp;<?php
					if(!empty($value['password'])): ?>
						<span class="password">密码 : <span><?php echo $value['password'] ?></span></span>
					<?php endif; ?></p>

				<p>分享时间 : <span class="time"><?php echo $value['time'] ?></span>&nbsp;<span
						class="less">剩余分享 : <span><?php echo $value['less'] ?></span>次</span></p>
			</li>
		<?php endforeach; ?>
	</ul>
<?php $this->get_footer();