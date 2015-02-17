<?php
/**
 * @var $this ULib\Page
 */
header("Content-Type: text/html; charset=utf-8");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $this->getTitle() ?></title>
	<link rel="stylesheet" href="<?php echo $this->get_bootstrap('css/bootstrap.min.css'); ?>"/>
	<link rel="stylesheet" href="<?php echo $this->get_bootstrap('css/bootstrap-theme.min.css'); ?>"/>
	<script type="text/javascript" src="<?php echo $this->get_asset('js/jquery.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo $this->get_bootstrap('js/bootstrap.min.js'); ?>"></script>
	<link rel="stylesheet" href="<?php echo $this->get_asset('style/main.css'); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<script>
		var BASE_URL = <?php echo json_encode(get_url(''))?>;
	</script>
</head>
<body>
<div class="container">
	<h1 class="main-title">阅后即隐，分享一小会</h1>
