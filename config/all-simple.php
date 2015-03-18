<?php
return [
	'database' => [
		'database_type' => 'mysql',
		'server' => 'localhost',
		'username' => 'root',
		'password' => '123456',
		'charset' => 'utf8',
		'database_name' => 'changda',
		'option' => [ //PDO选项
					  PDO::ATTR_CASE => PDO::CASE_NATURAL,
					  PDO::ATTR_TIMEOUT => 5
		]
	],
	'register' => [
		'status' => 'open',
		'login_form' => 'open'
	],
	'url_map_host' => [//用于前端的地址分配
					   'root' => '',
					   //主域名,如changda.club,不设置取一个顶级域名
					   'home' => '%ROOT%',
					   //当前首页的域名
					   'api' => '%ROOT%/Api',
					   //API的域名地址
					   'my' => 'my.%ROOT%'
					   //用户中心的域名
	],
	'cdn_list' => [
		'bootstrap' => '//dn-loveyu-libs.qbox.me/bootstrap/3.3.4',
		'jquery' => '//dn-loveyu-libs.qbox.me/jquery/1.11.2',
		'gravatar' => [
			'https' => 'https://secure.gravatar.com/avatar',
			'http' => 'http://1.gravatar.com/avatar'
		],
	],
	'mail_template' => _ViewPath_ . '/mail_template',
	//邮件模板视图
	'mail' => [
		//邮件配置设置，参考PHPMailer
		'Mailer' => 'smtp',
		'Host' => 'smtp.exmail.qq.com',
		'SMTPAuth' => true,
		'Username' => 'share@xxx.xxx',
		'Password' => 'xxx',
		'From' => 'share@xxx.xxx',
		'FromName' => 'share',
		'Sender' => 'share@xxx.xxx',
		'XMailer' => 'Loveyu Mailer',
		'CharSet' => 'utf-8',
		"Encoding" => 'base64',
	],
];