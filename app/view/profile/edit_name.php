<?php
/**
 * User: loveyu
 * Date: 2015/3/18
 * Time: 15:28
 * @var $this     ULib\Page
 * @var $__name   string 用户昵称
 * @var $__error  string 错误信息
 */
$this->get_header("修改名称");
?>
	<h3>修改用户昵称</h3>
	<form id="EditNameForm" method="post" class="form-inline" action="">
		<?php if($__error === NULL): ?>
			<p class="alert alert-success">成功更新</p>
		<?php else:if(!empty($__error)): ?>
			<p class="alert alert-danger"><?php echo $__error ?></p>
		<?php endif; endif; ?>
		<div class="form-group">
			<label class="sr-only" for="InputName">名称</label>

			<div class="input-group">
				<div class="input-group-addon">名称</div>
				<input type="text" name="name" class="form-control" id="InputName" value="<?php echo $__name ?>" placeholder="输入新名称">
			</div>

		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-warning">确认修改</button>
		</div>
	</form>
<?php $this->get_footer();