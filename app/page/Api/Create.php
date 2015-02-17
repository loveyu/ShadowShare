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
		parent::__construct(NULL, ['method' => 'POST']);
		ignore_user_abort(true);//忽略用户中断
	}

	public function url(){
		if(!$this->_run_check()){
			return;
		}
		$url = trim($this->__req->post('url'));
		if(!filter_var($url, FILTER_VALIDATE_URL)){
			$this->_set_status(false, 3001, "URL验证出错");
			return;
		}
		$share = class_share('Url');
		if($share->create(class_member()->getUid())){
			if($share->setData($url)){
				$this->_set_status(true, 0);
				$this->_set_data(get_url($share->getUname()));
			} else{
				$share->delete_failed_share();
				$this->_set_status(false, 3003, '分享数据设置失败');
			}
		} else{
			$this->_set_status(false, 3002, '创建分享失败');
		}
	}

}