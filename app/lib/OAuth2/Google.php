<?php
/**
 * User: loveyu
 * Date: 2015/3/5
 * Time: 20:19
 */

namespace ULib\OAuth2;


use ULib\Member\DaoGoogle;

class Google{
	//config
	protected $client_id;
	protected $client_secret;
	protected $redirect_uris;
	protected $token_uri;

	//get
	protected $access_token;
	protected $id_token;
	protected $uid = NULL;
	protected $app_uid = NULL;
	protected $email = false;
	protected $email_verified = NULL;

	//get for register
	protected $name = NULL;
	protected $avatar;

	protected $_error;
	protected $dao = NULL;//数据库对象
	protected $member_table_info = NULL;//用户信息表中的数据,值为False时表示失败

	public function __construct($config){
		$this->client_id = $config['client_id'];
		$this->client_secret = $config['client_secret'];
		$this->token_uri = $config['token_uri'];
		$this->redirect_uris = $config['redirect_uris'];
	}

	/**
	 * 从Google服务器读取数据
	 * @param string $code
	 * @return bool
	 */
	public function readData($code){
		$data = $this->RemoteData($this->token_uri, [
			'code' => $code,
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'redirect_uri' => $this->redirect_uris[0],
			'grant_type' => 'authorization_code'
		], "post");
		$json_data = json_decode($data, true);
		if(!isset($json_data['access_token'])){
			$this->_error = "无法解析登录数据或数据已失效";
			return false;
		}
		$this->access_token = $json_data['access_token'];
		if(!$this->parseIdToken($json_data['id_token'])){
			$this->_error = "无法解析用户数据";
			return false;
		}
		return true;
	}

	private function readInfo(){
		$data = $this->RemoteData("https://www.googleapis.com/plus/v1/people/" . $this->getUid(), ['access_token' => $this->access_token]);
		$msg = json_decode($data, true);
		if(isset($msg['error'])){
			$this->_error = "查询失败:" . $msg['message'];
			return false;
		}
		if(isset($msg['id']) && $this->getUid() == $msg['id']){
			$this->name = trim($msg['displayName']);
			$this->avatar = isset($msg['image']['url']) ? $msg['image']['url'] : "";
			if(!empty($this->avatar)){
				$i = strpos($this->avatar, '?');
				if($i > 0){
					$this->avatar = substr($this->avatar, 0, $i);
				}
			}
			return true;
		}
		$this->_error = "解析失败或数据未返回";
		return false;
	}

	/**
	 * 解析id token 数据
	 * @param $data
	 * @return bool
	 */
	protected function parseIdToken($data){
		list(, $msg) = explode(".", $data);
		if(empty($msg)){
			return false;
		}
		$data = json_decode(base64_decode($msg), true);
		if(!isset($data['sub'])){
			return false;
		}
		$this->uid = $data['sub'];
		$this->app_uid = $data['aud'];
		$this->email = $data['email'];
		$this->email_verified = $data['email_verified'];
		return true;
	}


	/**
	 * @return string|null
	 */
	public function getAppUid(){
		return $this->app_uid;
	}

	/**
	 * @return string|null
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * @return bool
	 */
	public function getEmailVerified(){
		return $this->email_verified;
	}


	/**
	 * 查询当前已经登录的用户ID
	 * @return int|false
	 */
	public function getMemberId(){
		if(!$this->isMember()){
			return false;
		}
		return $this->member_table_info['m_id'];
	}

	/**
	 * 判断当前用户是否为一个已注册或登录的用户
	 * @return bool
	 */
	public function isMember(){
		if($this->member_table_info === false){
			return false;
		}
		if($this->member_table_info === NULL){
			//查询新的数据
			$uid = $this->getUid();
			if(!$uid){
				return false;
			}
			$data = $this->getDao()->getInfoByUid($uid);
			if(isset($data['mg_uid']) && $uid == $data['mg_uid']){
				//查询到合适的值
				$this->member_table_info = $data;
			} else{
				$this->member_table_info = false;
			}
		}
		if(isset($this->member_table_info['m_id'])){
			return true;
		}
		return false;
	}

	/**
	 * 获取当前的用户名
	 * @return string|false
	 */
	public function getName(){
		if($this->name === NULL && !$this->readInfo()){
			return false;
		}
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getError(){
		return $this->_error;
	}


	public function insertMember($m_id){
		if($this->getDao()->insertData($m_id, $this->getUid(), $this->getAppUid(), $this->avatar)){
			return true;
		}
		$this->_error = "无法创建Google用户信息";
		return false;
	}

	/**
	 * Google用户ID，为字符串
	 * @return bool|string
	 */
	public function getUid(){
		if(!empty($this->uid)){
			return $this->uid;
		}
		$this->_error = "用户ID未初始化";
		return false;
	}

	/**
	 * @return mixed
	 */
	public function getAvatar(){
		return $this->avatar;
	}



	private function RemoteData($url, $param, $method = 'get'){
		switch($method){
			case "get":
				return file_get_contents($url . "?" . http_build_query($param));
				break;
			case "post":
				$options = array(
					'http' => array(
						'method' => "POST",
						'header' => "Content-type: application/x-www-form-urlencoded\r\n",
						'content' => http_build_query($param)
					)
				);
				$context = stream_context_create($options);
				return file_get_contents($url, false, $context);
				break;
		}
		return false;
	}

	/**
	 * 创建并生成数据库对象
	 * @return DaoGoogle
	 * @throws \Exception
	 */
	private function getDao(){
		if($this->dao === NULL){
			lib()->load('Member/DaoGoogle');
			$this->dao = new DaoGoogle();
		}
		return $this->dao;
	}
}