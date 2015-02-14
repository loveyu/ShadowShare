<?php
/**
 * User: loveyu
 * Date: 2015/1/31
 * Time: 16:34
 */

namespace UView;


use ULib\RestApi;

class Create extends RestApi{
	public function __construct(){
		parent::__construct(NULL, ['out' => 'dump']);
	}

	public function url(){
		$url = trim($this->__req->post('url'));
		if(!filter_var($url, FILTER_VALIDATE_URL)){
			$this->_set_status(false, 3001, "URL验证出错");
			return;
		}
		$share = class_share('Url');
		var_dump($share);
		$this->_set_status(true,0);
		$this->_set_data(['uname'=>'14f']);

	}

}