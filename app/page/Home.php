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
									break;
							}
							break;
						case Share::VIEW_HTML:
							switch($share->getShareType()){
								case Share::TYPE_MARKDOWN:
									header("Content-Type: text/html; charset=utf-8");
									header("Content-Disposition: inline; filename=" . $share->getUname() . ".html");
									/**
									 * @var $share \ULib\Share\ShareMarkdown
									 */
									echo $share->getHtml();
									$share->activeSet();
									break;
								default:
									$this->__load_404();
									break;
							}
							break;
						case Share::VIEW_RAW:
							switch($share->getShareType()){
								case Share::TYPE_TEXT:
									header("Content-Type: text/plain; charset=utf-8");
									echo $share->getPrimaryData();
									$share->activeSet();
									break;
								case Share::TYPE_MARKDOWN:
									header("Content-Type: text/plain; charset=utf-8");
									header("Content-Disposition: inline; filename=" . $share->getUname() . ".md");
									echo $share->getPrimaryData();
									$share->activeSet();
									break;
								case Share::TYPE_FILE:
									/**
									 * @var $share \ULib\Share\ShareFile
									 */
									$share->downloadFile();
									$share->activeSet();
									break;
								default:
									$this->__load_404();
									break;
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
								case Share::TYPE_FILE:
									//不下载，不触发记录
									$this->__view("share/file.php", ['share' => $share]);
									break;
								case Share::TYPE_MARKDOWN:
									$this->__view("share/markdown.php", ['share' => $share]);
									$share->activeSet();
									break;
								default:
									$this->__load_404();
									break;
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
			case "file":
				$this->__view("add/file.php");
				break;
			case "markdown":
				$this->__view("add/markdown.php");
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
		foreach($parse->getAllId() as $value){
			echo "<p>", date("Y-m-d H:i:s", $value['s_time_share']), ",", $parse->getShareTypeName($value['s_type']), ":<a href='" . get_url($value['s_uname']) . "'>" . get_url($value['s_uname']) . "</a></p>\n";
		}
		$this->get_footer();
	}

	/**
	 * 404
	 */
	public function not_found(){
		send_http_status(404);
		$this->__view("home/404.php");
	}
}