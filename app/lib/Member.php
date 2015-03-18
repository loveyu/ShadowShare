<?php
/**
 * User: loveyu
 * Date: 2015/2/14
 * Time: 21:20
 */

namespace ULib;


use ULib\Member\Dao;

class Member{
	protected $dao;
	protected $_error;
	private $status = false;
	private $m_id;
	private $m_name;
	private $m_email;
	private $m_avatar;
	private $cookie_domain_callback;

	public function __construct(){
		//必须先定义，在autoLogin中有调用
		$this->cookie_domain_callback = function ($domain){
			if(preg_match("/[a-zA-Z0-9-]+\\.[a-zA-Z0-9-]+$/", $domain, $match) == 1){
				return $match[0];
			}
			return $domain;
		};
		$this->autoLogin();
	}

	private function autoLogin(){
		$session = class_session();
		$member = $session->get('member');
		if(isset($member['m_id'])){
			//检测一项信息验证是否存在
			$this->status = true;
			$this->m_id = $member['m_id'];
			$this->m_name = $member['m_name'];
			$this->m_email = $member['m_email'];
			$this->m_avatar = $member['m_avatar'];
		} else{
			$token = req()->cookie('token');//包含用户ID和用户登录验证数据
			if($token === NULL || strlen($token) < 40 || strpos($token, "\t") < 1){
				//TOKEN由ID和40位随机字符组成
				return;
			}
			list($id, $token) = explode("\t", $token);
			$id = intval($id);
			if($id < 1){
				return;
			}
			$data = $this->getDao()->get_base_info($id);
			if(!isset($data['m_id']) || $data['m_id'] != $id){
				//登录数据查询失败
				return;
			}
			if($data['m_login_token'] != $token){
				//错误的登录
				return;
			}
			if($data['m_login_expire'] < NOW_TIME){
				//Token超时
				return;
			}
			$member = [];
			foreach([
				'm_id',
				'm_name',
				'm_email',
				'm_avatar'
			] as $v){
				$member[$v] = $data[$v];
				$this->$v = $data[$v];
			}
			$session->set('member', $member);
			$this->status = true;
			if($data['m_login_expire'] + 86400 > NOW_TIME){
				//在一天之内COOKIE会过期则自动对Cookie续期
				$this->setToken();
			}
		}
	}

	/**
	 * 使用表单登录
	 * @param $email
	 * @param $password_hash
	 * @return bool
	 */
	public function formLogin($email, $password_hash){
		$data = $this->getDao()->get_base_info_by_email($email);
		if(!isset($data['m_email']) || $data['m_email'] != $email){
			$this->_error = "未找到当前用户";
			return false;
		}
		if(salt_hash($password_hash, $data['m_salt']) !== $data['m_password']){
			$this->_error = "密码不正确";
			return false;
		}
		$this->oauth2login($data['m_id'], "form");
		return true;
	}


	/**
	 * @param $email
	 * @return bool
	 */
	public function PwdResetCodeSend($email){
		if(!$this->getDao()->has_email($email)){
			$this->_error = "用户未找到";
			return false;
		}
		$data = [
			'email' => $email,
			'code' => md5(salt())
		];
		class_session()->set('password_reset', $data);
		lib()->load('MailTemplate');
		$mail = new MailTemplate("register.html");
		$mail->setValues(['code' => htmlspecialchars($data['code'])]);
		try{
			$mail->mailSend($email, $email);
		} catch(\Exception $ex){
			$this->_error = "邮件发送失败";
			return false;
		}
		return true;
	}

	public function PwdReset($email, $code, $pwd_hash){
		$data = class_session()->get('password_reset');
		if(!isset($data['email']) || $data['email'] != $email || $data['code'] != $code){
			$this->_error = "数据验证失败，请重试";
			return false;
		}
		$salt = salt(12);
		if($this->getDao()->update_by_email($email, [
				'm_salt' => $salt,
				'm_password' => salt_hash($pwd_hash, $salt)
			]) === 1
		){
			return true;
		}
		$this->_error = "更新失败，数据库异常";
		return false;
	}

	/**
	 * 更新当前Token
	 */
	private function setToken(){
		$token = salt(40);
		if($this->getDao()->update_token($this->m_id, $token, NOW_TIME + 7 * 86400)){
			//Cookie有效期7天
			$index = hook()->add("Cookie_domain", $this->cookie_domain_callback);
			class_cookie()->set('token', $this->m_id . "\t" . $token, NOW_TIME + 7 * 86400);
			hook()->remove("Cookie_domain", $index);
			//销毁SESSION
			class_session()->destroy();
		}
	}


	/**
	 * 销毁session和Cookie退出登录
	 * @throws \Exception
	 */
	public function logout(){
		class_session()->destroy();
		$index = hook()->add("Cookie_domain", $this->cookie_domain_callback);
		class_cookie()->del('token');
		hook()->remove("Cookie_domain", $index);
	}


	/**
	 * 此处返回Int类型值，用于全等于判断
	 * @return int
	 */
	public function getUid(){
		return intval($this->m_id);
	}

	public function getLoginStatus(){
		return $this->status;
	}

	/**
	 * @return mixed
	 */
	public function getName(){
		return $this->m_name;
	}

	/**
	 * @return mixed
	 */
	public function getEmail(){
		return $this->m_email;
	}

	/**
	 * 开始自动登录，设置完成后自动跳转
	 * @param int    $login_id
	 * @param string $type
	 */
	public function oauth2login($login_id, $type){
		$data = $this->getDao()->get_base_info($login_id);
		if(!isset($data['m_id']) || $data['m_id'] != $login_id){
			//登录数据查询失败
			return;
		}
		$member = [];
		foreach([
			'm_id',
			'm_name',
			'm_email',
			'm_avatar'
		] as $v){
			$member[$v] = $data[$v];
			$this->$v = $data[$v];
		}
		class_session()->set('member', $member);
		$this->setToken();//设置Token
		$this->status = true;
		$this->loginRecord('oauth2', $type);
	}

	/**
	 * 记录登录数据
	 * @param string $type 登录类型 {oauth2|form}
	 * @param string $from {google|qq|weibo}
	 */
	private function loginRecord($type, $from){
		//TODO 记录用户登录数据
	}

	/**
	 * 最简洁信息注册
	 * @param string $name
	 * @param string $email
	 * @param string $avatar
	 * @return int
	 */
	public function register($name, $email, $avatar){
		if($this->getDao()->has_email($email)){
			$this->_error = "当前邮箱已经被注册";
			return false;
		}
		$uid = $this->getDao()->insert($name, $email, $avatar);
		if($uid > 0){
			return $uid;
		} else{
			$this->_error = "当前用户无法注册，请稍后再试";
			return false;
		}
	}

	/**
	 * 通过密码注册
	 * @param string $name
	 * @param string $email
	 * @param string $password 经过HASH之后的密码值
	 * @param string $code     邮箱验证码
	 * @return bool|int
	 */
	public function registerByPassword($name, $email, $password, $code){
		if($this->getDao()->has_email($email)){
			$this->_error = "当前邮箱已经被注册";
			return false;
		}
		$captcha = class_session()->get('EmailRegisterCode');
		if(!isset($captcha['email']) || !isset($captcha['code'])){
			$this->_error = "邮箱验证码未设置，请重新发送验证码";
			return false;
		}
		if($captcha['email'] != $email){
			$this->_error = "当前邮箱已变更，请重新发送邮件";
			return false;
		}
		if($captcha['code'] != $code){
			$this->_error = "验证码无效，或已过期";
			return false;
		}
		$salt = salt(12);
		$pwd = salt_hash($password, $salt);
		$uid = $this->getDao()->insert($name, $email, $this->createAvatarStore("rand", "default"), $salt, $pwd);
		if($uid > 0){
			return $uid;
		} else{
			$this->_error = "当前用户无法注册，请稍后再试";
			return false;
		}
	}

	/**
	 * @return string
	 */
	public function getError(){
		return $this->_error;
	}

	/**
	 * 创建并生成数据库对象
	 * @return Dao
	 * @throws \Exception
	 */
	private function getDao(){
		if($this->dao === NULL){
			lib()->load('Member/Dao');
			$this->dao = new Dao();
		}
		return $this->dao;
	}

	/**
	 * 为不同的头像创建不同数据
	 * @param string $data
	 * @param string $type
	 * @return string
	 */
	public function createAvatarStore($data, $type){
		return $type . "\t" . $data;
	}

	/**
	 * 获取当前头像
	 * @param int $size
	 * @return string
	 */
	public function getAvatar($size = 50){
		list($type, $value) = explode("\t", $this->m_avatar);
		switch($type){
			case "google":
				return $value . "?sz=" . $size;
			case "default":
				return get_url_map("my") . "Home/avatar_rand?size=" . $size;
		}
		return $value;
	}

}