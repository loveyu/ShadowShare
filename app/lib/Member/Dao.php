<?php
/**
 * User: loveyu
 * Date: 2015/3/6
 * Time: 17:47
 */

namespace ULib\Member;

use CLib\Sql;

class Dao{
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
	 * 返回用户注册ID
	 * @param string $m_name
	 * @param string $m_email
	 * @param string $m_avatar
	 * @param string $m_salt
	 * @param string $m_password
	 * @param string $m_login_token
	 * @param int    $m_login_expire
	 * @return int
	 */
	public function insert($m_name, $m_email, $m_avatar, $m_salt = "", $m_password = "", $m_login_token = "", $m_login_expire = 0){
		return $this->driver->insert("member", compact('m_name', 'm_salt', 'm_avatar', 'm_email', 'm_password', 'm_login_token', 'm_login_expire'));
	}


	/**
	 * 获取最近的一条记录
	 * @param int $m_id
	 * @return array|bool
	 */
	public function get_base_info($m_id){
		return $this->driver->get("member", '*', ['m_id' => $m_id]);
	}

	/**
	 * 获取最近的一条记录
	 * @param int $email
	 * @return array|bool
	 */
	public function get_base_info_by_email($email){
		return $this->driver->get("member", '*', ['m_email' => $email]);
	}

	/**
	 * 通过Email更新数据
	 * @param $email
	 * @param $data
	 * @return int
	 */
	public function update_by_email($email, $data){
		return $this->driver->update("member", $data, ['m_email' => $email]);
	}

	/**
	 * 更新用户Token
	 * @param int    $m_id
	 * @param string $m_login_token
	 * @param int    $m_login_expire
	 * @return bool
	 */
	public function update_token($m_id, $m_login_token, $m_login_expire){
		return $this->driver->update("member", compact('m_login_token', 'm_login_expire'), compact('m_id')) === 1;
	}

	/**
	 * 判断某个邮箱是否已经注册
	 * @param string $email
	 * @return bool
	 */
	public function has_email($email){
		return $this->driver->has("member", ['m_email' => $email]);
	}
}