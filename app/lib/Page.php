<?php
/**
 * User: loveyu
 * Date: 2015/1/8
 * Time: 21:14
 */

namespace ULib;


class Page extends \Core\Page{

	/**
	 * @var string
	 */
	private $title;

	private static $_header = [];
	private static $_footer = [];

	protected $header_view_file = "common/header.php";
	protected $footer_view_file = "common/footer.php";

	function __construct(){
		parent::__construct();
		$hook = $this->__core->getHook();
		$hook->add('header_hook', "ULib\\Page::__out_header");
		$hook->add('footer_hook', "ULib\\Page::__out_footer");
	}

	/**
	 * 加载头文件
	 * @param mixed $info 包含的信息，默认为字符串标签
	 */
	public function get_header($info = NULL){
		if(is_string($info)){
			$this->setTitle($info);
		} else if(is_array($info)){
			if(isset($info['title'])){
				if(is_array($info['title'])){
					call_user_func_array([
						$this,
						'setTitle'
					], $info['title']);
				} else{
					$this->setTitle($info['title']);
				}
			}
			if(isset($info['js'])){
				$this->_set_header($info['js'], 'js');
			}
			if(isset($info['css'])){
				$this->_set_header($info['css'], 'css');
			}
			if(isset($info['data'])){
				$this->_set_header($info['data'], 'data');
			}
		}
		$this->__view($this->header_view_file);
	}

	protected function _set_header($data, $type = 'data'){
		if(is_array($data)){
			foreach($data as $v){
				self::$_header[] = [
					'data' => $v,
					'type' => $type
				];
			}
		} else{
			self::$_header[] = compact('data', 'type');
		}
	}

	protected function _set_footer($data, $type = 'data'){
		if(is_array($data)){
			foreach($data as $v){
				self::$_footer[] = [
					'data' => $v,
					'type' => $type
				];
			}
		} else{
			self::$_footer[] = compact('data', 'type');
		}
	}

	public static function __out_header(){
		foreach(self::$_header as $v){
			switch($v['type']){
				case 'data':
					echo $v['data'];
					break;
				case "js":
					echo html_js(['src' => get_asset($v['data'])]), "\n";
					break;
				case "css":
					echo html_css(['href' => get_asset($v['data'])]), "\n";
					break;
			}
		}
	}

	public static function __out_footer(){
		foreach(self::$_footer as $v){
			switch($v['type']){
				case 'data':
					echo $v['data'];
					break;
				case "js":
					echo html_js(['src' => get_asset($v['data'])]), "\n";
					break;
				case "css":
					echo html_css(['href' => get_asset($v['data'])]), "\n";
					break;
			}
		}
	}

	public function get_footer($info = NULL){
		if(isset($info['js'])){
			$this->_set_footer($info['js'], 'js');
		}
		if(isset($info['css'])){
			$this->_set_footer($info['css'], 'css');
		}
		if(isset($info['data'])){
			$this->_set_footer($info['data'], 'data');
		}
		$this->__view($this->footer_view_file);
	}

	/**
	 * 设置标题
	 * @param $title string
	 * @param $_     string 多级标题
	 */
	public function setTitle($title, $_ = NULL){
		$this->title = implode(" - ", func_get_args());
	}

	public function getTitle(){
		if(empty($this->title)){
			return "阅后即隐 - 分享一小会";
		} else{
			return $this->title . " | 阅后即隐";
		}
	}

	public function get_bootstrap($file, $version = '3.3.1', $cache_code = ''){
		return $this->get_asset("bootstrap/{$version}/{$file}", $cache_code);
	}

	public function get_asset($file, $cache_code = ''){
		return get_asset($file, $cache_code);
	}

	public static function __un_register(){
		return get_class_methods("ULib\\Page");
	}

}