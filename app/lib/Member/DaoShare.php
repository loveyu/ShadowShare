<?php
/**
 * User: loveyu
 * Date: 2015/3/17
 * Time: 15:09
 */

namespace ULib\Member;

use CLib\Sql;

class DaoShare{
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
	 * 查询某一用户的统计数据
	 * @param int $m_id
	 * @return array
	 */
	public function count_share_by_id($m_id){
		$m_id = intval($m_id);
		$sql = "SELECT
	`s_type`,
	COUNT(`s_mid`) AS `count`
FROM
	`share`
WHERE
	`s_mid` = {$m_id} AND `s_status` in (0, 1) AND `s_deny` = 0
GROUP BY
	`s_type`
ORDER BY
	`count` DESC;";
		$stmt = $this->driver->query($sql);
		$rt = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $rt;
	}

	/**
	 * 查询用户的某一类分享数据
	 * @param int       $m_id
	 * @param int|array $s_type
	 * @param null      $limit
	 * @return array|bool
	 */
	public function select($m_id, $s_type, $limit = NULL){
		$m_id = intval($m_id);
		$s_type = is_array($s_type) ? array_unique(array_map('intval', $s_type)) : intval($s_type);
		$where = [
			'AND' => [
				's_mid' => $m_id,
				's_type' => $s_type,
				's_status' => [
					0,
					1
				],
				's_deny' => 0
			],
			'ORDER'=>'s_id DESC'
		];
		if(!empty($limit)){
			$where['LIMIT'] = $limit;
		}
		return $this->driver->select("share", "*", $where);
	}

}