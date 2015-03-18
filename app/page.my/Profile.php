<?php
/**
 * User: loveyu
 * Date: 2015/3/18
 * Time: 15:15
 */

namespace UView;


use ULib\Page;

class Profile extends Page{

	public function __construct(){
		$this->header_view_file = "common/my_header.php";
		$this->footer_view_file = "common/my_footer.php";
		parent::__construct();
	}

	public function edit(){
		$member = class_member();
		if(!$member->getLoginStatus()){
			redirect('Home/login', 'refresh', 302, false);
		}
		$name = $this->__req->post('name');
		$error = "";
		if(!empty($name)){
			if(!$member->setName($name)){
				$error = $member->getError();
			} else{
				$error = NULL;
			}
		}
		$this->__view("profile/edit_name.php", [
			'name' => $member->getName(),
			'error' => $error
		]);
	}

	public function avatar(){
		$member = class_member();
		if(!$member->getLoginStatus()){
			redirect('Home/login', 'refresh', 302, false);
		}
		$error = "";
		$type = $this->__req->post('type');
		if(!empty($type)){
			if(!$member->setAvatar($type, $this->__req->post('value'))){
				$error = $member->getError();
			} else{
				$error = NULL;
			}
		}
		$this->__view("profile/edit_avatar.php", ['error' => $error]);
	}

	public function password(){
		$member = class_member();
		if(!$member->getLoginStatus()){
			redirect('Home/login', 'refresh', 302, false);
		}
		$password = trim($this->__req->post('new'));
		$old = trim($this->__req->post('old'));
		$error = "";
		if(!empty($password)){
			if(empty($password) || empty($old)){
				$error = "不允许空密码";
			} elseif($password == $old){
				$error = "新旧密码不能一致";
			} else{
				if(!$member->setPassword(_md5($old), _md5($password))){
					$error = $member->getError();
				} else{
					$error = NULL;
				}
			}
		}
		$this->__view("profile/edit_password.php", [
			'error' => $error
		]);
	}

	public function access_token(){
		if(!class_member()->getLoginStatus()){
			redirect('Home/login', 'refresh', 302, false);
		}
		$reset = $this->__req->post('reset');
		if($reset === "true"){
			$access_token = class_member()->resetAccessToken();
		} else{
			$access_token = class_member()->getAccessToken();
		}
		$this->__view("profile/access_token.php", compact('access_token'));
	}
}