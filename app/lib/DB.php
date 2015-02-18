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
	 * 获取一条基本分享数据
	 * @param int $s_id
	 * @return array|bool
	 */
	public function d_share_get($s_id){
		return $this->driver->get("share", "*", ['s_id' => $s_id]);
	}

	/**
	 * 更新一条分享的访问次数
	 * @param int $s_id
	 * @return int
	 */
	public function d_share_update_count_add($s_id){
		return $this->driver->update("share", [
			"s_share_count[+]" => 1,
			's_time_last' => NOW_TIME
		], ['s_id' => $s_id]);
	}

	/**
	 * 更新一条分享的失败访问次数
	 * @param int $s_id
	 * @return int
	 */
	public function d_share_update_over_add($s_id){
		return $this->driver->update("share", ["s_share_over[+]" => 1], ['s_id' => $s_id]);
	}

	/**
	 * 获取一条基本分享URL数据
	 * @param int $s_id
	 * @return array|bool
	 */
	public function d_share_url_get($s_id){
		return $this->driver->get("share_url", "*", ['s_id' => $s_id]);
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

	/**
	 * 创建文本内容分享
	 * @param int    $s_id 基本信息表ID
	 * @param string $su_text
	 * @return bool
	 */
	public function d_share_text_insert($s_id, $su_text){
		return $this->driver->insert("share_text", [
			's_id' => $s_id,
			'st_text' => $su_text
		]) !== -1;
	}

	/**
	 * 获取一条文本分享数据
	 * @param $s_id
	 * @return array|bool
	 */
	public function d_share_text_get($s_id){
		return $this->driver->get("share_text", "*", ['s_id' => $s_id]);
	}

	/**
	 * 插入文件分享数据
	 * @param $s_id
	 * @param $sf_md5
	 * @param $sf_sha1
	 * @param $sf_name
	 * @param $sf_type
	 * @param $sf_size
	 * @param $sf_save_name
	 * @param $sf_save_path
	 * @return bool
	 */
	public function d_share_file_insert($s_id, $sf_md5, $sf_sha1, $sf_name, $sf_type, $sf_size, $sf_save_name, $sf_save_path){
		return $this->driver->insert("share_file", compact('s_id', 'sf_md5', 'sf_sha1', 'sf_name', 'sf_type', 'sf_size', 'sf_save_name', 'sf_save_path')) !== -1;
	}

	/**
	 * 获取文件分享的数据
	 * @param $s_id
	 * @return array|bool
	 */
	public function d_share_file_get($s_id){
		return $this->driver->get("share_file", "*", ['s_id' => $s_id]);
	}
}