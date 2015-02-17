<?php
/**
 * User: loveyu
 * Date: 2015/2/17
 * Time: 12:31
 */

namespace ULib;


class ShareBaseInfo{
	/**
	 * 正常状态
	 */
	const STATUS_NORMAL = 0;
	/**
	 * 失效
	 */
	const STATUS_INVALID = 1;
	/**
	 * 被删除
	 */
	const STATUS_DELETE = 2;

	/**
	 * 未禁用
	 */
	const DENY_NO = 0;
	/**
	 * 普通禁用
	 */
	const DENY_NORMAL = 1;
	/**
	 * 违规禁用
	 */
	const DENY_VIOLATION = 2;

	private $id;
	private $uname;
	private $key;
	private $type;
	private $mid;
	private $status;
	private $deny;
	private $view;
	private $share_max;
	private $share_count;
	private $share_over;
	private $time_share;
	private $time_delete;
	private $time_renewal;
	private $time_last;

	function __construct($base_data){
		$list = [
			'id',
			'uname',
			'key',
			'type',
			'mid',
			'status',
			'deny',
			'view',
			'share_max',
			'share_count',
			'share_over',
			'time_share',
			'time_delete',
			'time_renewal',
			'time_last'
		];
		foreach($list as $v){
			if(isset($base_data['s_' . $v])){
				$this->$v = $base_data['s_' . $v];
			} else{
				$this->$v = NULL;
			}
		}
	}

	/**
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getUname(){
		return $this->uname;
	}

	/**
	 * @return string
	 */
	public function getKey(){
		return $this->key;
	}

	/**
	 * @return int
	 */
	public function getType(){
		return $this->type;
	}

	/**
	 * @return int
	 */
	public function getMid(){
		return intval($this->mid);
	}

	/**
	 * @return mixed
	 */
	public function getStatus(){
		return $this->status;
	}

	/**
	 * @return mixed
	 */
	public function getDeny(){
		return $this->deny;
	}

	/**
	 * @return mixed
	 */
	public function getView(){
		return $this->view;
	}

	/**
	 * @return mixed
	 */
	public function getShareMax(){
		return $this->share_max;
	}

	/**
	 * @return mixed
	 */
	public function getShareCount(){
		return $this->share_count;
	}

	/**
	 * @return mixed
	 */
	public function getShareOver(){
		return $this->share_over;
	}

	/**
	 * @return mixed
	 */
	public function getTimeShare(){
		return $this->time_share;
	}

	/**
	 * @return mixed
	 */
	public function getTimeDelete(){
		return $this->time_delete;
	}

	/**
	 * @return mixed
	 */
	public function getTimeRenewal(){
		return $this->time_renewal;
	}

	/**
	 * @return mixed
	 */
	public function getTimeLast(){
		return $this->time_last;
	}

	/**
	 * 判断用户是否在分享历史表中
	 * @return bool
	 */
	public function userInShareTable(){
		//TODO 检查重复性
		return false;
	}

}