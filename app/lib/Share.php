<?php
/**
 * User: loveyu
 * Date: 2015/2/13
 * Time: 22:55
 */

namespace ULib;


use Core\Log;

abstract class Share{
	const TYPE_TEXT = 0;
	const TYPE_FILE = 1;
	const TYPE_CODE = 2;
	const TYPE_MULTI_TEXT = 3;
	const TYPE_URL = 4;
	const TYPE_PICTURE = 5;
	const TYPE_MARKDOWN = 6;
	const TYPE_PICTURE_TEXT = 7;

	protected $share_type = NULL;
	protected $base_data;

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
		if(class_db()->d_share_delete($this->base_data['s_id'])){
			Log::write("一条数据基本数据删除失败" . $this->base_data['s_id'], Log::NOTICE);
		}
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
}