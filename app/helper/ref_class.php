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
function class_db(){
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

/**
 * 返回一个分享的类
 * @param string $type {Url}
 * @return \ULib\Share
 */
function class_share($type){
	static $map = [];
	if(isset($map[$type])){
		return $map[$type];
	}
	$lib = lib();
	if(!class_exists('ULib\Share')){
		$lib->load('Share');
	}
	$map[$type] = $lib->using('UShare' . $type);
	if($map[$type] === false){
		$lib->load('Share/Share' . $type);
		$class_name = 'ULib\Share\Share' . $type;
		$map[$type] = new $class_name();
		$lib->add("UShare" . $type, $map[$type]);
	}
	return $map[$type];
}

/**
 * 获取用户类
 * @return \ULib\Member
 */
function class_member(){
	static $member = NULL;
	if($member !== NULL){
		return $member;
	}
	$lib = lib();
	$member = $lib->using('UMember');
	if($member === false){
		$lib->load('Member');
		$member = new \ULib\Member();
		$lib->add("UMember", $member);
	}
	return $member;
}