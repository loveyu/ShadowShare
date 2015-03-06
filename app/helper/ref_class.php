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
 * @param string       $type    {Url|File|Text|Markdown}
 * @param array|string $require 依赖额其他类
 * @return \ULib\Share
 */
function class_share($type, $require = NULL){
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
		if(is_string($require)){
			$lib->load('Share/Share' . $require);
		} elseif(is_array($require)){
			foreach($require as $v){
				$lib->load('Share/Share' . $v);
			}
		}
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

/**
 * 获取Session对象
 * @return \CLib\Session
 */
function class_session(){
	static $session = NULL;
	if($session !== NULL){
		return $session;
	}
	$lib = c_lib();
	$session = $lib->using('CSession');
	if($session === false){
		$lib->load('session');
		$session = new \CLib\Session();
		$lib->add("CSession", $session);
	}
	return $session;
}

/**
 * 获取Cookie对象
 * @return \CLib\Cookie
 */
function class_cookie(){
	static $cookie = NULL;
	if($cookie !== NULL){
		return $cookie;
	}
	$lib = c_lib();
	$cookie = $lib->using('CCookie');
	if($cookie === false){
		$lib->load('cookie');
		$cookie = new \CLib\Cookie(cfg()->get('cookie', 'enable'), cfg()->get('cookie', 'key'));
		$lib->add("CCookie", $cookie);
	}
	return $cookie;
}