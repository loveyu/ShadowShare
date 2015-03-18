<?php
/**
 * User: loveyu
 * Date: 2015/3/7
 * Time: 23:58
 * @var $this    \ULib\Page
 * @var $__error string
 */
$this->get_header("用户登录");
?>
	<h3 class="text-center">用户登录</h3>

	<div class="register" style="margin: 20px auto;max-width: 500px">
		<form action="<?php echo get_url('Home', 'login_form', 'post') ?>" method="post">
			<fieldset>
				<?php if(!empty($__error)): ?>
					<p class="well well-sm text-danger"><?php echo $__error ?></p>
				<?php endif; ?>
				<div class="form-group">
					<label class="control-label sr-only" for="InputEmail">邮箱 : </label>

					<div class="input-group">
						<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
						<input type="email" class="form-control" name="email" value="<?php echo req()->post('email') ?>" id="InputEmail"
							   placeholder="用户邮箱">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label sr-only" for="InputPassword">密码 : </label>

					<div class="input-group">
						<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
						<input type="password" class="form-control" name="password" id="InputPassword" placeholder="用户密码">
					</div>
				</div>
				<div class="form-group">
					<button class="btn btn-success" type="submit">登录</button>
					&nbsp;<a href="<?php echo get_url("Home", 'password_reset') ?>">忘记密码?</a>
					<?php if(allow_register()): ?>
						&nbsp;<a href="<?php echo get_url("Home", 'register') ?>">需要注册?</a>
					<?php endif; ?>
				</div>
			</fieldset>

		</form>
	</div>
<?php $this->get_footer();