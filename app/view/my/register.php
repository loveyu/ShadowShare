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
				<div id="ErrorBox">
					<?php if(!empty($__error)): ?>
						<p id="ErrorOutput" class="well well-sm text-danger"><?php echo $__error ?></p>
					<?php endif; ?>
				</div>
				<div class="form-group">
					<label class="control-label sr-only" for="InputEmail">邮箱 : </label>

					<div class="input-group">
						<div class="input-group-addon">邮箱</div>
						<input type="email" class="form-control" value="<?php echo req()->post('email') ?>" name="email" id="InputEmail">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label sr-only" for="InputName">名称 : </label>

					<div class="input-group">
						<div class="input-group-addon">名称</div>
						<input type="text" class="form-control" value="<?php echo req()->post('name') ?>" name="name" id="InputName">
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
					<label class="control-label sr-only" for="InputCaptcha">验证码 : </label>

					<div class="input-group">
						<div class="input-group-addon">验证码</div>
						<input type="text" class="form-control" name="captcha" id="InputCaptcha">

						<div class="input-group-addon"><label id="SendEmailCaptcha" class="label label-danger">获取邮箱验证码</label></div>
					</div>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit">注册</button>
				</div>
			</fieldset>
		</form>
	</div>
	<script type="text/javascript">
		jQuery(function ($) {
			var has_e_error = false;
			var email_success = false;
			var has_p_error = false;
			var send_email_captcha = $("#SendEmailCaptcha");
			var email_change = function () {
				var email = $(this).val();
				$.getJSON(URL_MAP.api + "Member/emailRegisterCheck", {email: email}, function (result) {
					if (result.status) {
						has_e_error = false;
						email_success = true;
						$("#ErrorBox").html("");
					} else {
						has_e_error = true;
						email_success = false;
						if ($("#ErrorOutput").length < 1) {
							$("#ErrorBox").html('<p id="ErrorOutput" class="well well-sm text-danger"></p>');
						}
						$("#ErrorOutput").html(result.msg);
					}
				});
			};
			var send_email = function () {
				if (!email_success) {
					alert("邮箱未验证");
					return false;
				}
				var email = $("#InputEmail").val();
				$.getJSON(URL_MAP.api + "Member/sendEmailRegisterCode", {email: email}, function (result) {
					console.log(result);
				});
				return false;
			};
			$("#InputEmail").blur(email_change);
			send_email_captcha.click(send_email);

		});
	</script>

<?php $this->get_footer();