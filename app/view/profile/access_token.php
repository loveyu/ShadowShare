<?php
/**
 * User: loveyu
 * Date: 2015/3/18
 * Time: 15:28
 * @var $this            ULib\Page
 * @var $__access_token  string Token
 */
$this->get_header("我的授权访问参数");
?>
	<h3>我的授权访问参数</h3>

	<form action="" method="post">
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-addon">Access Token</div>
				<input type="text" class="form-control" value="<?php echo $__access_token ?>">
			</div>
		</div>
		<div class="form-group">
			<div class="input-group">
				<div class="input-group-addon">创建时间</div>
				<input type="text" class="form-control" value="<?php echo date("Y-m-d H:i:s", explode(".", $__access_token)[1]) ?>">
			</div>
		</div>
		<div class="form-group">
			<input type="hidden" name="reset" value="true"/>
			<button type="submit" class="btn btn-danger">重置</button>
		</div>
	</form>
<?php $this->get_footer();