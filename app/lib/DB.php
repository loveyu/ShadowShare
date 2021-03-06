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
	 * 创建代码内容分享
	 * @param int    $s_id 基本信息表ID
	 * @param string $sc_code
	 * @param string $sc_lang
	 * @return bool
	 */
	public function d_share_code_insert($s_id, $sc_code, $sc_lang){
		return $this->driver->insert("share_code", compact('s_id', 'sc_code', 'sc_lang')) !== -1;
	}

	/**
	 * 获取一条代码分享数据
	 * @param $s_id
	 * @return array|bool
	 */
	public function d_share_code_get($s_id){
		return $this->driver->get("share_code", "*", ['s_id' => $s_id]);
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

	/**
	 * 插入markdown数据
	 * @param int    $s_id
	 * @param string $sm_content
	 * @param string $sm_title
	 * @return int
	 */
	public function d_share_markdown_insert($s_id, $sm_content, $sm_title = ''){
		return $this->driver->insert("share_markdown", compact('s_id', 'sm_content', 'sm_title')) !== -1;
	}


	/**
	 * 获取Markdown分享的数据
	 * @param $s_id
	 * @return array|bool
	 */
	public function d_share_markdown_get($s_id){
		return $this->driver->get("share_markdown", "*", ['s_id' => $s_id]);
	}

	/**
	 * 插入分的图片数据
	 * @param int $s_id
	 * @param int $sp_width
	 * @param int $sp_height
	 * @return bool
	 */
	public function d_share_picture_insert($s_id, $sp_width, $sp_height){
		return $this->driver->insert("share_picture", compact('s_id', 'sp_width', 'sp_height')) !== -1;
	}

	/**
	 * 获取图片分享的数据
	 * @param $s_id
	 * @return array|bool
	 */
	public function d_share_picture_get($s_id){
		return $this->driver->get("share_picture", "*", ['s_id' => $s_id]);
	}

	/**
	 * 插入分的图文数据
	 * @param int    $s_id
	 * @param int    $spt_image_width
	 * @param int    $spt_image_height
	 * @param string $spt_text
	 * @param int    $spt_position
	 * @return bool
	 */
	public function d_share_picture_text_insert($s_id, $spt_image_width, $spt_image_height, $spt_text, $spt_position){
		return $this->driver->insert("share_picture_text", compact('s_id', 'spt_image_width', 'spt_image_height', 'spt_text', 'spt_position')) !== -1;
	}

	/**
	 * 获取图文分享的数据
	 * @param $s_id
	 * @return array|bool
	 */
	public function d_share_picture_text_get($s_id){
		return $this->driver->get("share_picture_text", "*", ['s_id' => $s_id]);
	}

	/**
	 * 创建多行文本内容分享
	 * @param int    $s_id 基本信息表ID
	 * @param string $smt_text
	 * @param int    $smt_max
	 * @param int    $smt_expire
	 * @return bool
	 */
	public function d_share_multi_text_insert($s_id, $smt_text, $smt_max, $smt_expire){
		return $this->driver->insert("share_multi_text", compact('s_id', 'smt_text', 'smt_max', 'smt_expire')) !== -1;
	}

	/**
	 * 获取一条多行文本分享数据
	 * @param $s_id
	 * @return array|bool
	 */
	public function d_share_multi_text_get($s_id){
		return $this->driver->get("share_multi_text", "*", ['s_id' => $s_id]);
	}

	public function d_share_multi_text_update($s_id, $data){
		return $this->driver->update("share_multi_text", $data, ['s_id' => $s_id]);
	}

	public function d_share_multi_text_map_insert($s_id, $smtm_ip, $smtm_time, $smt_index, $smtm_count = 0){
		return $this->driver->insert("share_multi_text_map", compact('s_id', 'smtm_ip', 'smtm_time', 'smt_index', 'smtm_count')) !== -1;
	}

	/**
	 * 获取某一IP最后访问的一条记录
	 * @param int    $s_id
	 * @param string $smtm_ip
	 * @return array
	 */
	public function d_share_multi_text_map_get_last($s_id, $smtm_ip){
		return $this->driver->get("share_multi_text_map", "*", [
			'AND' => compact('s_id', 'smtm_ip'),
			'ORDER' => 'smtm_time DESC'
		]);
	}

	public function d_share_multi_text_map_update_count($s_id, $smtm_ip, $smtm_time){
		return $this->driver->update("share_multi_text_map", ['smtm_count[+]' => 1], ['AND' => compact('s_id', 'smtm_ip', 'smtm_time')]) == 1;
	}
}