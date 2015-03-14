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
	'url_map_host'=>[//用于前端的地址分配
		'root'=>'',//主域名,如changda.club,不设置取一个顶级域名
		'home'=>'%ROOT%',//当前首页的域名
		'api'=>'%ROOT%/Api',//API的域名地址
		'my'=>'my.%ROOT%'//用户中心的域名
	],
];