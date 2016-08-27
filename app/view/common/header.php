<?php
/**
 * @var $this ULib\Page
 */
header("Content-Type: text/html; charset=utf-8");
?><!doctype html>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title><?php echo $this->getTitle() ?></title>
	<link rel="icon" href="<?php echo $this->get_asset('favicon.ico') ?>">
	<link rel="stylesheet" href="<?php echo $this->get_bootstrap('css/bootstrap.min.css'); ?>"/>
	<link rel="stylesheet" href="<?php echo $this->get_bootstrap('css/bootstrap-theme.min.css'); ?>"/>
	<script type="text/javascript" src="<?php echo $this->get_cdn('jquery.min.js', 'jquery', 'js/jquery.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo $this->get_bootstrap('js/bootstrap.min.js'); ?>"></script>
	<link rel="stylesheet" href="<?php echo $this->get_asset('style/main.css'); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<script>
		var URL_MAP = <?php echo json_encode(get_url_map())?>;
	</script>
	<script src="<?php echo $this->get_asset('style/main.js') ?>"></script>
	<?php header_hook(); ?>
</head>
<body>
<div class="container">
	<div id="Header">
		<h1 class="main-title"><a href="<?php echo get_url_map('home') ?>">阅后即隐，分享一小会</a>
			<small class="btn-sm">Beta</small>
		</h1>
		<div class="login_status"><a href="<?php echo get_url_map('my') ?>">登陆/注册</a></div>
	</div>
