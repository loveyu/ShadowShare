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
];