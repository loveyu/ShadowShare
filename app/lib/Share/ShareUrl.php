<?php
/**
 * User: loveyu
 * Date: 2015/2/13
 * Time: 22:56
 */

namespace ULib\Share;

use ULib\Share;

class ShareUrl extends Share{

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


}