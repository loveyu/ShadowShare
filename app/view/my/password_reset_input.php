<?php
/**
 * User: loveyu
 * Date: 2015/3/7
 * Time: 23:58
 * @var $this    \ULib\Page
 * @var $__email string
 * @var $__error string
 */
$this->get_header("密码重置");
?>
	<div class="register" style="margin: 20px auto;max-width: 500px">
		<?php if($__error == "ok"): ?>
			<div class="well well-lg">
				<p>密码重置成功，<a href="<?php echo get_url('Home','login_form')?>">前往登录</a>。</p>
			</div>
		<?php else: ?>
			<form method="post">
				<fieldset>
					<h3>输入新密码与验证码</h3>
					<?php if(!empty($__error)): ?>
						<p class="well well-sm text-danger"><?php echo $__error ?></p>
					<?php endif; ?>
					<div class="form-group">
						<label class="control-label sr-only" for="InputEmail">邮箱 : </label>

						<div class="input-group">
							<div class="input-group-addon">邮箱</div>
							<input type="email" readonly class="form-control" name="email" id="InputEmail" value="<?php echo $__email ?>"
								   placeholder="用户邮箱">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label sr-only" for="InputCode">验证码 : </label>

						<div class="input-group">
							<div class="input-group-addon">验证码</div>
							<input type="text" class="form-control" name="code" id="InputCode" placeholder="验证码">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label sr-only" for="InputPassword">新密码 : </label>

						<div class="input-group">
							<div class="input-group-addon">新密码</div>
							<input type="password" class="form-control" name="password" id="InputPassword" placeholder="新密码">
						</div>
					</div>

					<div class="form-group">
						<button class="btn btn-success" type="submit">重置</button>
					</div>
				</fieldset>

			</form>
		<?php endif; ?>
	</div>
<?php $this->get_footer();