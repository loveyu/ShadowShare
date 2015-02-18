<?php
/**
 * User: loveyu
 * Date: 2015/2/18
 * Time: 11:01
 */

namespace ULib\Share;


use ULib\Share;

class ShareText extends Share{

	private $text = NULL;

	/**
	 * 初始化
	 */
	function __construct(){
		$this->share_type = self::TYPE_TEXT;
		parent::__construct();
	}

	/**
	 * 设置对应要分享的内容
	 * @param mixed $data
	 * @return bool 设置的状态
	 */
	public function setData($data){
		return class_db()->d_share_text_insert($this->base_data['s_id'], $data);
	}

	/**
	 * 初始化拓展基本信息
	 * @throws \Exception
	 * @return void
	 */
	public function initExtData(){
		$ex_data = class_db()->d_share_text_get($this->base_data['s_id']);
		if(!isset($ex_data['s_id'])){
			throw new \Exception("数据获取异常");
		}
		$this->text = $ex_data['st_text'];
	}

	/**
	 * 返回主要的信息
	 * @return mixed
	 */
	public function getPrimaryData(){
		return $this->text;
	}

}