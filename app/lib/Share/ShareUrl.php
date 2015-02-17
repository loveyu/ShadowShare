<?php
/**
 * User: loveyu
 * Date: 2015/2/13
 * Time: 22:56
 */

namespace ULib\Share;

use ULib\Share;

class ShareUrl extends Share{

	protected $title;
	protected $description;
	protected $url;

	public function __construct(){
		$this->share_type = self::TYPE_URL;
		parent::__construct();
	}

	/**
	 * 设置对应要分享的内容
	 * @param mixed $data
	 * @return bool 设置的状态
	 */
	public function setData($data){
		return class_db()->d_share_url_insert($this->base_data['s_id'], $data);
	}

	/**
	 * 返回主要的信息
	 * @return mixed
	 */
	public function getPrimaryData(){
		return $this->url;
	}


	/**
	 * 初始化拓展基本信息
	 * @throws \Exception
	 * @return void
	 */
	public function initExtData(){
		$ex_data = class_db()->d_share_url_get($this->base_data['s_id']);
		if(!isset($ex_data['s_id'])){
			throw new \Exception("数据获取异常");
		}
		$this->title = $ex_data['su_title'];
		$this->description = $ex_data['su_description'];
		$this->url = $ex_data['su_url'];
	}

	/**
	 * @return string|null
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getUrl(){
		return $this->url;
	}

}