<?php
/**
 * User: loveyu
 * Date: 2015/3/14
 * Time: 15:27
 */

namespace UView;


use ULib\MailTemplate;
use ULib\Member\Dao;
use ULib\RestApi;

class Member extends RestApi{
	public function __construct($check_call = NULL, $cfg = []){
		parent::__construct($check_call, $cfg);
		header('Access-Control-Allow-Origin: ' . $this->_origin());
		header("Access-Control-Allow-Credentials: true");
	}

	/**
	 * 查询当前用户的登录信息
	 */
	public function loginInfo(){
		if(!$this->_run_check()){
			return;
		}
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

	/**
	 * 检测一个邮箱的地址是否被注册
	 * 未被注册返回TRUE
	 */
	public function emailRegisterCheck(){
		if(!$this->_run_check()){
			return;
		}
		$email = $this->__req->req('email');
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$this->_set_status(false, 5011, "邮箱地址不合法");
			return;
		}
		lib()->load('Member/Dao');
		$dao = new Dao();
		if($dao->has_email($email)){
			$this->_set_status(false, 5012, "邮箱已被注册");
			return;
		}
		$this->_set_status(true, 0, '邮箱有效，未被注册');
	}

	/**
	 * 发送邮箱注册验证码
	 */
	public function sendEmailRegisterCode(){
		if(!$this->_run_check()){
			return;
		}
		$email = trim(strtolower($this->__req->req('email')));
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$this->_set_status(false, 5011, "邮箱地址不合法");
			return;
		}
		if(!class_member()->SendRegisterCode($email)){
			$this->_set_status(false, 5012, class_member()->getError());
		} else{
			$this->_set_status(true, 0, "验证码已发送");
		}
	}
}