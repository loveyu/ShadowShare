<?php
/**
 * User: loveyu
 * Date: 2015/2/18
 * Time: 9:53
 */

namespace UView;


use ULib\Page;

class Home extends Page{

	function __construct(){
		$this->header_view_file = "common/my_header.php";
		$this->footer_view_file = "common/my_footer.php";
		parent::__construct();
	}

	function avatar_rand(){
		$this->__view("my/rand_avatar.php");
	}

	public function main(){
		$member = class_member();
		if($member->getLoginStatus()){
			$this->__view("member/home.php", [
				'name' => $member->getName(),
				'email' => $member->getEmail(),
				'avatar'=>$member->getAvatar()
			]);
		} else{
			redirect([
				'Home',
				'login'
			]);
		}
	}

	public function logout(){
		class_member()->logout();
		redirect([], 'refresh', 302, false);
	}

	public function login_form($post = NULL){
		if(cfg()->get('register', 'login_form') != "open"){
			$this->__load_404();
			return;
		}
		$member = class_member();
		if($member->getLoginStatus()){
			$this->__view("home/server_error.php", ['msg' => '当前用户已登录，还登录个啥子。']);
			return;
		}
		$error = "";
		if($post == "post" && $this->__req->is_post()){
			//注册
			//跳转
			$email = strtolower(trim($this->__req->post('email')));
			$password = trim($this->__req->post('password'));
			if(empty($email) || empty($password)){
				$error = "有空值，请检查";
			} else{
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
					$error = "邮箱格式有误，请检查";
				} else{
					if($member->formLogin($email, _md5($password))){
						redirect(get_url(), "refresh", 302, false);//重新跳转到用户首页
						return;
					} else{
						$error = $member->getError();
					}
				}
			}
		}
		$this->__view("my/login_form.php", ['error' => $error]);
	}

	public function register($post = NULL){
		if(cfg()->get('register', 'status') != "open"){
			$this->__load_404();
			return;
		}
		$member = class_member();
		if($member->getLoginStatus()){
			$this->__view("home/server_error.php", ['msg' => '当前用户已登录，还注册个啥子。']);
			return;
		}
		$error = "";
		if($post == "post" && $this->__req->is_post()){
			//注册
			//跳转
			$name = trim($this->__req->post('name'));
			$email = strtolower(trim($this->__req->post('email')));
			$password = trim($this->__req->post('password'));
			if(empty($name) || empty($email) || empty($password)){
				$error = "有空值，请检查";
			} else{
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
					$error = "邮箱格式有误，请检查";
				} else{
					if(($id = $member->registerByPassword($name, $email, _md5($password))) > 0){
						$member->oauth2login($id, "register");
						redirect(get_url(), "refresh", 302, false);//重新跳转到用户首页
						return;
					} else{
						$error = $member->getError();
					}
				}
			}
		}
		$this->__view("my/register.php", ['error' => $error]);
	}

	public function password_reset(){
		$email = strtolower($this->__req->post('email'));
		$status = "";
		if(!empty($email)){
			$member = class_member();
			if($member->PwdResetCodeSend($email)){
				redirect([
					'Home',
					'password_reset_input',
					$email
				], 'refresh', 302, false);
				return;
			} else{
				$status = $member->getError();
			}
		}
		$this->__view("my/password_reset.php", ['status' => $status]);
	}

	public function password_reset_input($email = NULL){
		$error = '';
		if($this->__req->is_post()){
			$code = $this->__req->post('code');
			$password = $this->__req->post('password');
			if(empty($code) || empty($password)){
				$error = "存在空值";
			} else{
				if(class_member()->PwdReset($email, $code, _md5($password))){
					$error = "ok";
				} else{
					$error = class_member()->getError();
				}
			}
		}
		$this->__view("my/password_reset_input.php", compact('email', 'error'));
	}

	public function login(){
		if(func_num_args() > 0){
			$this->__load_404();
			return;
		}
		$this->__view("my/login.php");
	}

	public function not_found(){
		send_http_status(404);
		$this->__view("home/404.php");
	}
}