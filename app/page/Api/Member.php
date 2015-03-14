<?php
/**
 * User: loveyu
 * Date: 2015/3/14
 * Time: 15:27
 */

namespace UView;


use ULib\RestApi;

class Member extends RestApi{
	public function loginInfo(){
		header('Access-Control-Allow-Origin: ' . $this->_origin());
		header("Access-Control-Allow-Credentials: true");
		$member = class_member();
		if(!$member->getLoginStatus()){
			$this->_set_status(false, 5001, '当前用户未登陆');
		} else{
			$this->_set_status(true, 0);
			$this->_set_data([
				'id' => $member->getUid(),
				'name' => $member->getName(),
				'avatar' => $member->getAvatar(25)
			]);
		}
	}
}