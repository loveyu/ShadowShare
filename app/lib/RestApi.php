<?php
/**
 * User: loveyu
 * Date: 2015/1/31
 * Time: 15:39
 */

namespace ULib;

/**
 * 基本API设计
 * Class RestApi
 * @package ULib
 */
class RestApi extends \Core\Page{
	/**
	 * API返回格式
	 * @var array
	 */
	private $result = [
		'code' => -1,
		'msg' => NULL,
		'status' => false,
		'data' => NULL
	];

	/**
	 * 对应的配置信息
	 * @var array
	 */
	private $cfg = [
		'out' => 'json',
		'json_option' => 0,
		'method' => ''
	];

	/**
	 * API权限检测是否通过
	 * @var bool
	 */
	private $status = true;

	/**
	 * 初始化
	 * @param callback $check_call 初始化检测函数
	 * @param array    $cfg        默认配置文件
	 */
	public function __construct($check_call = NULL, $cfg = []){
		parent::__construct();
		$this->cfg = array_merge($this->cfg, $cfg);
		if(is_callable($check_call)){
			$this->status = call_user_func($check_call);
			if(!$this->status){
				$this->result['code'] = -2;
			}
		}
	}

	/**
	 * 对配置设置
	 * @param $cfg
	 */
	protected function _set_config($cfg){
		$this->cfg = array_merge($this->cfg, $cfg);
	}

	/**
	 * 设置全部数据
	 * @param array $result
	 */
	protected function _set($result){
		if($this->status){
			$this->result = array_merge($this->result, $result);
		}
	}

	/**
	 * 设置状态
	 * @param bool   $status
	 * @param int    $code
	 * @param string $msg
	 */
	protected function _set_status($status, $code, $msg = NULL){
		if(!$this->status){
			return;
		}
		$this->result['status'] = $status ? true : false;
		$this->result['code'] = intval($code);
		if($msg !== NULL){
			$this->result['msg'] = trim($msg);
		}
	}

	/**
	 * 设置要返回的数据
	 * @param string $msg
	 * @param mixed  $data
	 */
	protected function _set_msg($msg, $data){
		if(!$this->status){
			return;
		}
		$this->result['msg'] = trim($msg);
		$this->result['data'] = $data;
	}

	/**
	 * 设置要返回的数据
	 * @param mixed  $data
	 */
	protected function _set_data($data){
		if(!$this->status){
			return;
		}
		$this->result['data'] = $data;
	}

	/**
	 * 对象销毁并输出数据
	 */
	function __destruct(){
		if(!$this->result['status'] && $this->result['code'] < 0 && $this->result['msg'] === NULL){
			$this->result['msg'] = $this->_code_msg($this->result['code']);
		}
		switch($this->cfg['out']){
			case 'print_r':
			case 'print':
				header("Content-Type: text/plain; charset=utf-8");
				print_r($this->result);
				break;
			case "var_dump":
			case "dump":
				if(function_exists('xdebug_is_enabled') && xdebug_is_enabled()){
					header("Content-Type: text/html; charset=utf-8");
				} else{
					header("Content-Type: text/plain; charset=utf-8");
				}
				var_dump($this->result);
				break;
			default:
				header("Content-Type: application/json; charset=utf-8");
				echo json_encode($this->result, $this->cfg['json_option']);
				break;
		}
	}


	/**
	 * 进行API回调检测权限
	 * @param callback $call
	 * @param string   $error_msg
	 * @return bool
	 */
	protected function _run_check($call = NULL, $error_msg = NULL){
		if(!$this->status){
			return false;
		}
		if(!empty($this->cfg['method'])){
			//检测请求方式
			if(!isset($_SERVER['REQUEST_METHOD']) || strtolower(trim($this->cfg['method'])) != strtolower(trim($_SERVER['REQUEST_METHOD']))){
				$this->result['code'] = -3;
			}
		}
		if(!is_null($call) && is_callable($call)){
			$this->status = call_user_func($call);
		}
		if($this->status){
			return true;
		} else{
			$this->result['code'] = -4;
			$this->result['msg'] = $error_msg;
			return false;
		}
	}

	/**
	 * 返回一些默认的错误消息
	 * @param $code
	 * @return string
	 */
	protected function _code_msg($code){
		switch($code){
			case -1:
				return "Application undefined error.";
			case -2:
				return "Application initialization check error.";
			case -3:
				return "Request method error.";
			case -4:
				return "Application run check error.";
			default:
				return "Unknown error.";
		}
	}

	/**
	 * 路由的需要忽略的方法
	 * @return array
	 */
	public static function __un_register(){
		return get_class_methods("ULib\\RestApi");
	}
}