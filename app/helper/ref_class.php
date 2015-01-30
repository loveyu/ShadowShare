<?php
/**
 * User: loveyu
 * Date: 2015/1/29
 * Time: 21:52
 */

/**
 * 获取数据库类
 * @return \ULib\DB
 */
function db_class(){
	static $db = NULL;
	if($db !== NULL){
		return $db;
	}
	$lib = lib();
	$db = $lib->using('UDB');
	if($db === false){
		$lib->load('DB');
		$db = new \ULib\DB();
		$lib->add("UDB", $db);
	}
	return $db;
}