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
			<a href="<?php echo get_url('OAuth2', 'google_login') ?>"><img src="<?php echo get_asset("style/images/sign-in-with-google.png") ?>"
																		   alt="sign-in-with-google"></a>
		</div>
	</div>

<?php $this->get_footer();