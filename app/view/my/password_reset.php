<?php
/**
 * User: loveyu
 * Date: 2015/3/7
 * Time: 23:58
 * @var $this    \ULib\Page
 * @var $__status string
 */
$this->get_header("密码重置");
?>
<h3 class="text-center">密码重置</h3>
	<div class="register" style="margin: 20px auto;max-width: 500px">
		<form  method="post">
			<fieldset>
				<?php if(!empty($__status)): ?>
					<p class="well well-sm text-danger"><?php echo $__status ?></p>
				<?php endif; ?>
				<div class="form-group">
					<label class="control-label sr-only" for="InputEmail">邮箱 : </label>

					<div class="input-group">
						<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
						<input type="email" class="form-control" name="email" value="<?php echo req()->post('email')?>" id="InputEmail" placeholder="用户邮箱">
					</div>
				</div>

				<div class="form-group">
					<button class="btn btn-success" type="submit">发送重置信息</button>
				</div>
			</fieldset>

		</form>
	</div>
<?php $this->get_footer();