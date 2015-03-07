<?php
/**
 * User: loveyu
 * Date: 2015/3/7
 * Time: 23:58
 * @var $this    \ULib\Page
 * @var $__error string
 */
$this->get_header("用户注册");
?>
	<div class="register" style="margin: 20px auto;max-width: 500px">
		<form action="<?php echo get_url('Home', 'register', 'post') ?>" method="post">
			<fieldset>
				<legend>注册</legend>
				<?php if(!empty($__error)): ?>
					<p class="well well-sm text-danger"><?php echo $__error ?></p>
				<?php endif; ?>
				<div class="form-group">
					<label class="control-label sr-only" for="InputEmail">邮箱 : </label>

					<div class="input-group">
						<div class="input-group-addon">邮箱</div>
						<input type="email" class="form-control" value="<?php echo req()->post('email')?>" name="email" id="InputEmail">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label sr-only" for="InputName">姓名 : </label>

					<div class="input-group">
						<div class="input-group-addon">姓名</div>
						<input type="text" class="form-control" value="<?php echo req()->post('name')?>" name="name" id="InputName">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label sr-only" for="InputPassword">密码 : </label>

					<div class="input-group">
						<div class="input-group-addon">密码</div>
						<input type="password" class="form-control" name="password" id="InputPassword">
					</div>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit">注册</button>
				</div>
			</fieldset>

		</form>
	</div>
<?php $this->get_footer();