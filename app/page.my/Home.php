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
		parent::__construct();
	}

	public function main(){
		$member = class_member();
		if($member->getLoginStatus()){
			header("Content-Type: text/plain; charset=utf-8");
			echo $member->getName(), "\n";
			echo $member->getEmail(), "\n";
			echo $member->getAvatar(), "\n";
			echo "页面加载", c()->getTimer()->get_second(), " 秒， 数据库查询 ", get_db_query_count(), " 次。\n";
		} else{
			redirect([
				'Home',
				'login'
			]);
		}
	}

	public function logout(){
		class_member()->logout();
		redirect([],'refresh',302,false);
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