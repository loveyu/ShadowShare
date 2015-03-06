<?php
/**
 * User: loveyu
 * Date: 2015/3/5
 * Time: 14:53
 */

namespace UView;


use Ulib\Page;
use ULib\OAuth2\Google;

class OAuth2 extends Page{
	private $oauth_config;

	function __construct(){
		parent::__construct();
		$cfg = cfg()->load(_RootPath_ . "/config/oauth2.php");
		if(isset($cfg['OAuth2'])){
			$this->oauth_config = $cfg['OAuth2'];
		} else{
			$this->oauth_config = false;
		}
	}

	public function google_login(){
		if(!isset($this->oauth_config['google'])){
			$this->error("谷歌登录未启用");
			return;
		}
		$state = md5(rand() . NOW_TIME);
		class_session()->set("oauth2_google_state", $state);
		$cfg = $this->oauth_config['google'];
		redirect($cfg['auth_uri'] . "?" . http_build_query([
				'client_id' => $cfg['client_id'],
				'response_type' => 'code',
				'scope' => 'profile email',
				'redirect_uri' => $cfg['redirect_uris'][0],
				'state' => $state
			]), 'refresh', 302, false);
	}

	public function google_callback(){
		if(!isset($this->oauth_config['google'])){
			$this->error("谷歌登录未启用");
			return;
		}
		$state = $this->__req->get('state');
		$code = $this->__req->get('code');
		if(empty($state) || empty($code)){
			$this->error("服务器未返回任何可用数据");
			return;
		}
		if(class_session()->get('oauth2_google_state') !== $state){
			$this->error("服务器返回的验证数据无效");
			return;
		}
		$this->__lib->load('OAuth2/Google');
		$google = new Google($this->oauth_config['google']);
		if(!$google->readData($code)){
			//无法从远程服务器读取数据，意味着失败
			$this->error($google->getError());
			return;
		}
		if($google->isMember()){
			//获取登录用户ID进行登录
			class_member()->oauth2login($google->getMemberId(), "google");
			redirect(get_url(), "refresh", 302, false);//重新跳转到用户首页
			return;
		}
		if(!$google->getEmailVerified()){
			//也就是在用户登录过程中已注册的账户不验证其是否注册
			$this->error("该用户邮箱未经过验证禁止登录");
			return;
		}

		//开始注册
		$name = $google->getName();
		if(empty($name)){
			//无法获取用户的名称
			$this->error($google->getError());
			return;
		}

		$member = class_member();
		//将数据注册到用户表
		$m_id = $member->register($name, $google->getEmail(), $member->createAvatarStore($google->getAvatar(), "google"));

		if(!$m_id){
			//如果无法创建用户数据
			$this->error($member->getError());
		} else{
			//插入用户数据到Google数据表
			if(!$google->insertMember($m_id)){
				$this->error($google->getError());
			} else{
				//成功注册数据
				$member->oauth2login($m_id, "google");
				redirect(get_url(), "refresh", 302, false);//重新跳转到用户首页
			}
		}
	}

	/**
	 * 调用错误输出
	 * @param string $msg
	 */
	private function error($msg){
		header("Content-Type: text/html; charset=utf-8");
		$this->__view("home/server_error.php", ['msg' => $msg]);
	}


}