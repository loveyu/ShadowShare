<?php
namespace UView;

use ULib\Page;
use ULib\Share;

class Home extends Page{

	function __construct(){
		parent::__construct();
	}

	/**
	 * Home page
	 */
	public function main(){
		$this->__view("home/index.php");
	}


	public function share($id = NULL){
		/**
		 * @var $parse \ULib\Share\ShareParse
		 */
		$parse = class_share('Parse');
		$share = $parse->parse($id);
		if(is_object($share)){
			switch($share->activeCheck()){
				case Share::ACTIVE_NORMAL:
					switch($share->getViewType()){
						case Share::VIEW_REDIRECT:
							//强制跳转模式
							switch($share->getShareType()){
								case Share::TYPE_URL:
									redirect($share->getPrimaryData(), 'refresh', 302, false);
									$share->activeSet();
									break;
								default:
									$this->__load_404();
							}
							break;
						case Share::VIEW_RAW:
							switch($share->getShareType()){
								case Share::TYPE_TEXT:
									header("Content-Type: text/plain; charset=utf-8");
									echo $share->getPrimaryData();
									$share->activeSet();
									break;
								default:
									$this->__load_404();
							}
							break;
						default:
							//默认查看视图模式
							switch($share->getShareType()){
								case Share::TYPE_URL:
									$this->__view("share/url.php", ['share' => $share]);
									$share->activeSet();
									break;
								case Share::TYPE_TEXT:
									$this->__view("share/text.php", ['share' => $share]);
									$share->activeSet();
									break;
								default:
									$this->__load_404();
							}
							break;
					}
					break;
				case Share::ACTIVE_404:
					$this->__load_404();
					break;
				default:
					$this->__view("home/share_error.php", ['msg' => $share->activeErrorMsg()]);
					break;
			}
		} else{
			$this->__load_404();
		}
	}

	public function add($type = NULL){
		switch($type){
			case "url":
				$this->__view("add/url.php");
				break;
			case "text":
				$this->__view("add/text.php");
				break;
			default:
				$this->__load_404();
		}
	}

	public function all_list(){
		//DEBUG 方法
		/**
		 * @var $parse \ULib\Share\ShareParse
		 */
		$parse = class_share('Parse');
		$this->get_header();
		foreach($parse->getAllId() as $uname){
			echo "<p><a href='" . get_url($uname) . "'>" . get_url($uname) . "</a></p>\n";
		}
		$this->get_header();
	}

	/**
	 * 404
	 */
	public function not_found(){
		send_http_status(404);
		$this->__view("home/404.php");
	}
}