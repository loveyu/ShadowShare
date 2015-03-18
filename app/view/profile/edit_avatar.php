<?php
/**
 * User: loveyu
 * Date: 2015/3/18
 * Time: 15:28
 * @var $this     ULib\Page
 * @var $__error  string 错误信息
 */
$member = class_member();
$avatar_param = $member->parseAvatarParam();
$google = $member->getAvatarByGoogle();
$this->get_header("自定义头像");
?>
	<h3>自定义你的头像</h3>
	<form action="" method="post" class="my-avatar-edit" style="max-width: 600px">
		<?php if($__error === NULL): ?>
			<p class="alert alert-success">成功更新</p>
		<?php else:if(!empty($__error)): ?>
			<p class="alert alert-danger"><?php echo $__error ?></p>
		<?php endif; endif; ?>
		<?php if(!empty($google)): ?>
			<div class="radio">
				<label>
					<input name="type" type="radio" value="google"<?php echo $avatar_param['type'] == "google" ? " checked=\"checked\"" : "" ?>>
					我的Google头像，如 : <img src="<?php echo $google . "?sz=25" ?>" alt="google avatar">
				</label></div>
		<?php endif; ?>
		<div class="radio">
			<label>
				<input name="type" type="radio" value="default"<?php echo $avatar_param['type'] == "default" ? " checked=\"checked\"" : "" ?>>
				使用默认随机头像，如 : <img src="<?php echo get_url_map('my') . "Home/avatar_rand?size=25" ?>" alt="default avatar">
			</label></div>
		<div class="radio">
			<label>
				<input name="type" type="radio" value="gravatar"<?php echo $avatar_param['type'] == "gravatar" ? " checked=\"checked\"" : "" ?>>
				使用Gravatar头像，如 : <img src="<?php echo $member->getGravatar(25) ?>" alt="gravatar">
			</label>
		</div>
		<div class="radio">
			<label>
				<input name="type" type="radio" value="custom"<?php echo $avatar_param['type'] == "custom" ? " checked=\"checked\"" : "" ?>>
				自定义地址<?php if($avatar_param['type'] == "custom"): ?>
					，如 : <img src="<?php echo $avatar_param['value'] ?>" alt="custom avatar">
				<?php endif; ?>
			</label>

			<p class="form-inline custom_value"><label for="InputValue">自定义值：</label>
				<input type="url" name="value" class="form-control"<?php if($avatar_param['type'] == "custom"){
					echo "value=\"" . htmlspecialchars($avatar_param['value']) . "\"";
				} ?> id="InputValue"></p>
		</div>
		<div class="form-group">
			<button class="btn btn-success" type="submit">修改头像</button>
		</div>
	</form>
<?php $this->get_footer();