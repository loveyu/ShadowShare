<?php
/**
 * User: loveyu
 * Date: 2015/1/29
 * Time: 21:53
 */

namespace ULib;

use CLib\Sql;

class DB{
	/**
	 * @var Sql
	 */
	private $driver;

	function __construct(){
		c_lib()->load('sql');
		$this->driver = new Sql(cfg()->get('database'));
		if(!$this->driver->status()){
			throw new \Exception("Can't connect mysql server.");
		}
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
	 * 返回下一条自动增长的数据
	 * @return int
	 */
	public function d_share_create_nextID(){
		return $this->driver->insert("share", [
			's_type' => 0,
			's_time_share' => NOW_TIME
		]);
	}

	/**
	 * 更新数据分享表
	 * @param int   $s_id
	 * @param array $data
	 * @return int
	 */
	public function d_share_update($s_id, $data){
		return $this->driver->update("share", $data, ['s_id' => $s_id]);
	}

	/**
	 * 删除某条记录，有关基本分享表
	 * @param int $s_id
	 * @return bool
	 */
	public function d_share_delete($s_id){
		return $this->driver->delete("share", ['s_id' => $s_id]) == 1;
	}

	/**
	 * 创建URL的分享
	 * @param int    $s_id 基本信息表ID
	 * @param string $su_url
	 * @return bool
	 */
	public function d_share_url_insert($s_id, $su_url){
		return $this->driver->insert("share_url", [
			's_id' => $s_id,
			'su_url' => $su_url
		]) !== -1;
	}
}