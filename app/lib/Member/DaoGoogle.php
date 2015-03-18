<?php
/**
 * User: loveyu
 * Date: 2015/3/6
 * Time: 17:02
 */

namespace ULib\Member;

use CLib\Sql;


class DaoGoogle{
	/**
	 * @var Sql
	 */
	private $driver;

	function __construct(){
		$this->driver = class_db()->getDriver();
	}

	/**
	 * 返回数据库驱动
	 * @return Sql
	 */
	public function getDriver(){
		return $this->driver;
	}

	/**
	 * 返回数据库的错误信息
	 * @return array
	 */
	public function get_error(){
		return $this->driver->error();
	}


	/**
	 * 通过google唯一UID查询当前的用户，并取得ID值
	 * @param string $mg_uid
	 * @return array|bool
	 */
	public function getInfoByUid($mg_uid){
		return $this->driver->get("member_google", "*", ['mg_uid' => $mg_uid]);
	}

	/**
	 * 通过用户ID查询信息
	 * @param string $m_id
	 * @return array|bool
	 */
	public function getInfoByMid($m_id){
		return $this->driver->get("member_google", "*", ['m_id' => $m_id]);
	}

	/**
	 * 插入数据到Google用户表
	 * @param int    $m_id
	 * @param string $mg_uid
	 * @param string $mg_app_uid
	 * @param string $mg_avatar
	 * @return bool
	 */
	public function insertData($m_id, $mg_uid, $mg_app_uid, $mg_avatar){
		return $this->driver->insert("member_google", compact('m_id', 'mg_uid', 'mg_app_uid', 'mg_avatar')) !== -1;
	}
}