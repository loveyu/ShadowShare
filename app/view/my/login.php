<?php
/**
 * User: loveyu
 * Date: 2015/3/4
 * Time: 22:14
 * @var $this \ULib\Page
 */
$this->get_header('用户登录');
?>
	<div class="login">

		<h2 class="text-center">用户登录</h2>

		<div id="GoogleLoginButton">
			<p>
				<a href="<?php echo get_url('OAuth2', 'google_login') ?>">
					<img src="<?php echo get_asset("style/images/sign-in-with-google.png") ?>" alt="sign-in-with-google"/>
				</a>
			</p>
			<?php if(allow_form_login() || allow_register()): ?>
				<p class="help-block">
					<span>没有谷歌账号或无法登录?</span>
					<?php if(allow_form_login()): ?>
						<a class="email_login label label-success" href="<?php echo get_url('Home', 'login_form') ?>">邮箱登陆</a>
					<?php endif;
					if(allow_form_login()): ?>
						<a class="email_register label label-warning" href="<?php echo get_url('Home', 'register') ?>">注册</a>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

<?php $this->get_footer();