<?php
/**
 * User: loveyu
 * Date: 2015/3/18
 * Time: 15:28
 * @var $this     ULib\Page
 * @var $__error  string 错误信息
 */
$this->get_header("修改密码");
?>
	<h3>修改用户密码</h3>
	<form id="EditNameForm" method="post" action="">
		<?php if($__error === NULL): ?>
			<p class="alert alert-success">成功更新</p>
		<?php else:if(!empty($__error)): ?>
			<p class="alert alert-danger"><?php echo $__error ?></p>
		<?php endif; endif; ?>
		<div class="form-group">
			<label class="sr-only" for="InputOld">旧密码</label>

			<div class="input-group">
				<div class="input-group-addon">旧密码</div>
				<input type="password" name="old" class="form-control" id="InputOld"  placeholder="输入旧密码">
			</div>
		</div>
		<div class="form-group">
			<label class="sr-only" for="InputNew">新密码</label>

			<div class="input-group">
				<div class="input-group-addon">新密码</div>
				<input type="password" name="new" class="form-control" id="InputNew" placeholder="输入新密码">
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-warning">确认修改</button>
		</div>
	</form>
<?php $this->get_footer();