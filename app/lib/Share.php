<?php
/**
 * User: loveyu
 * Date: 2015/2/13
 * Time: 22:55
 */

namespace ULib;


use Core\Log;

/**
 * 分享的抽象类
 * Class Share
 * @package ULib
 */
abstract class Share{
	const TYPE_TEXT = 0;
	const TYPE_FILE = 1;
	const TYPE_CODE = 2;
	const TYPE_MULTI_TEXT = 3;
	const TYPE_URL = 4;
	const TYPE_PICTURE = 5;
	const TYPE_MARKDOWN = 6;
	const TYPE_PICTURE_TEXT = 7;

	/**
	 * 普通查看模式
	 */
	const VIEW_NORMAL = 0;
	/**
	 * 二进制文本查看模式
	 */
	const VIEW_RAW = 1;
	/**
	 * 网址跳转模式
	 */
	const VIEW_REDIRECT = 2;

	const ACTIVE_NORMAL = 0;
	const ACTIVE_404 = 1;
	const ACTIVE_DENY = 2;
	const ACTIVE_STATUS = 3;
	const ACTIVE_LIMIT = 4;

	/**
	 * @var string 错误信息提示
	 */
	protected $active_error_msg;

	/**
	 * @var int 分享的数据类型，由子类赋值并实现
	 */
	protected $share_type = NULL;

	/**
	 * @var ShareBaseInfo 基本信息对象
	 */
	protected $base_object;

	/**
	 * @var array 基本数据
	 */
	protected $base_data;

	/**
	 * 分享类型对应的分类描述
	 * @var array
	 */
	protected $type_map = [
		0 => 'Text',
		1 => 'File',
		2 => 'Code',
		3 => 'Multi-Text',
		4 => 'Url',
		5 => 'Picture',
		6 => 'Markdown',
		7 => 'Picture-Text',
	];

	/**
	 * 初始化检查
	 * @throws \Exception
	 */
	public function __construct(){
		if(!is_int($this->share_type)){
			throw new \Exception("Share type is not define, please check you code.");
		}
	}

	/**
	 * 创建一个基本的数据分享结构
	 * @param int        $mid
	 * @param array|null $more
	 * @return bool
	 */
	public function create($mid, $more = NULL){
		$id = $this->getNextID();
		if(!is_int($id) || $id < 0){
			return false;
		}
		$db = class_db();
		$this->base_data = [
			's_uname' => id2uname($id),
			's_type' => $this->share_type,
			's_mid' => $mid,
			's_time_share' => NOW_TIME,
		];
		if(is_array($more)){
			foreach(array_keys($more) as $v){
				if(!in_array($v, [
					's_key',
					's_share_max'
				])
				){//允许自定义的数据
					unset($more[$v]);
				}
			}
			$this->base_data = array_merge($this->base_data, $more);
		}
		$rt = $db->d_share_update($id, $this->base_data);
		$this->base_data['s_id'] = $id;
		if($rt !== 1){
			Log::write(print_r($db->get_error(), true));
			$this->delete_failed_share();
			return false;
		}
		return true;
	}

	/**
	 * 设置对应要分享的内容
	 * @param mixed $data
	 * @return bool 设置的状态
	 */
	public abstract function setData($data);

	/**
	 * 初始化拓展基本信息
	 * @return void
	 */
	public abstract function initExtData();

	/**
	 * 返回主要的信息
	 * @return mixed
	 */
	public abstract function getPrimaryData();

	/**
	 * 设置基础信息
	 * @param array $base_data
	 */
	public function setBaseData($base_data){
		$this->base_data = $base_data;
		lib()->load('ShareBaseInfo');
		$this->base_object = new ShareBaseInfo($this->base_data);
	}

	/**
	 * 访问检测
	 * @return int
	 */
	public function activeCheck(){
		if(!is_int($this->share_type)){
			return self::ACTIVE_404;
		}

		switch($this->base_object->getStatus()){
			case ShareBaseInfo::STATUS_DELETE:
				$this->active_error_msg = "该分享被删除";
				return self::ACTIVE_404;
			case ShareBaseInfo::STATUS_INVALID:
				$this->active_error_msg = "该分享已失效";
				return self::ACTIVE_STATUS;
		}
		switch($this->base_object->getDeny()){
			case ShareBaseInfo::DENY_NORMAL:
				$this->active_error_msg = "分享被禁用或取消";
				return self::ACTIVE_DENY;
			case ShareBaseInfo::DENY_VIOLATION:
				$this->active_error_msg = "分享违规被禁用";
				return self::ACTIVE_DENY;
		}
		if($this->base_object->getShareCount() >= $this->base_object->getShareMax() && !$this->base_object->userInShareTable()){
			$this->active_error_msg = "超过最大分享次数限制";
			$this->activeOverSet();
			return self::ACTIVE_LIMIT;
		}
		return self::ACTIVE_NORMAL;
	}

	/**
	 * 访问错误信息
	 * @return string
	 */
	public function activeErrorMsg(){
		return is_null($this->active_error_msg) ? "未知原因" : $this->active_error_msg;
	}

	/**
	 * 访问标志设置
	 */
	public function activeSet(){
		if($this->base_object->getMid() === 0 || $this->base_object->getMid() !== class_member()->getUid()){
			//自己访问不增加计数器
			class_db()->d_share_update_count_add($this->base_data['s_id']);
		}
	}

	/**
	 * 最大访问次数超出记录
	 */
	private function activeOverSet(){
		class_db()->d_share_update_over_add($this->base_data['s_id']);
	}

	/**
	 * 获取唯一字符串
	 * @return bool|string
	 */
	public function getUname(){
		if(isset($this->base_data['s_uname'])){
			return $this->base_data['s_uname'];
		} else{
			return false;
		}
	}

	/**
	 * 操作失败时删除数据
	 */
	public function delete_failed_share(){
		if(!isset($this->base_data['s_id'])){
			return;
		}
		if(!class_db()->d_share_delete($this->base_data['s_id'])){
			Log::write("一条数据基本数据删除失败" . $this->base_data['s_id'], Log::NOTICE);
		}
		Log::write("删除失败的数据:" . $this->base_data['s_id'], Log::NOTICE);
	}

	/**
	 * 获取下一条自动增长的ID
	 * @return false|int
	 */
	private function getNextID(){
		$id = class_db()->d_share_create_nextID();
		if(is_numeric($id)){
			return intval($id);
		}
		return false;
	}

	/**
	 * 获取分类的数据描述
	 * @param int $type {TYPE_TEXT|TYPE_FILE}
	 * @return string
	 */
	public function getShareTypeName($type){
		return isset($this->type_map[$type]) ? $this->type_map[$type] : "Unknown";
	}

	/**
	 * 获取分享的类型
	 * @return int
	 */
	public function getShareType(){
		return $this->share_type;
	}

	/**
	 * 获取当前要查看的类型
	 * @return int
	 */
	public function getViewType(){
		$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : "null";
		$mode = strtolower(trim($mode));
		switch($mode){
			case "raw":
			case "bin":
			case "binary":
				return self::VIEW_RAW;
			case "redirect":
			case "jump":
			case "go":
				return self::VIEW_REDIRECT;
			default:
				return self::VIEW_NORMAL;
		}
	}

}