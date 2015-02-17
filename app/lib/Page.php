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

	function __construct(){
		parent::__construct();
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
		}
		$this->__view("common/header.php");
	}

	public function get_footer(){
		$this->__view("common/footer.php");
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
			return "长大俱乐部 - 阅后即隐，分享一小会";
		} else{
			return $this->title . " | 长大俱乐部";
		}
	}

	public function get_bootstrap($file, $version = '3.3.1', $cache_code = ''){
		return $this->get_asset("bootstrap/{$version}/{$file}", $cache_code);
	}

	public function get_asset($file, $cache_code = ''){
		return get_file_url([
			'asset',
			$file
		]) . ((!empty($cache_code) && is_string($cache_code)) ? "?_v=" . $cache_code : "");
	}

	public static function __un_register(){
		return get_class_methods("ULib\\Page");
	}

}