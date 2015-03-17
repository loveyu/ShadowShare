<?php
namespace UView;

use Core\Log;
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
		try{
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
										class_member();//优先调用用户登录类
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
										class_member();//优先调用用户登录类
										/**
										 * @var $share \ULib\Share\ShareMarkdown
										 */
										echo $share->getHtml();
										$share->activeSet();
										break;
									case Share::TYPE_PICTURE_TEXT:
										header("Content-Type: text/html; charset=utf-8");
										header("Content-Disposition: inline; filename=" . $share->getUname() . ".html");
										class_member();//优先调用用户登录类
										/**
										 * @var $share \ULib\Share\SharePictureText
										 */
										echo $share->getHtml();
										$share->activeSet();
										break;
									case Share::TYPE_CODE:
										/**
										 * @var $share \ULib\Share\ShareCode
										 */
										header("Content-Type: text/html; charset=utf-8");
										header("Content-Disposition: inline; filename=" . $share->getUname() . ".html");
										class_member();//优先调用用户登录类
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
									case Share::TYPE_CODE:
									case Share::TYPE_MULTI_TEXT:
										header("Content-Type: text/plain; charset=utf-8");
										class_member();//优先调用用户登录类
										echo $share->getPrimaryData();
										$share->activeSet();
										break;
									case Share::TYPE_MARKDOWN:
										header("Content-Type: text/plain; charset=utf-8");
										header("Content-Disposition: inline; filename=" . $share->getUname() . ".md");
										class_member();//优先调用用户登录类
										echo $share->getPrimaryData();
										$share->activeSet();
										break;
									case Share::TYPE_FILE:
									case Share::TYPE_PICTURE:
									case Share::TYPE_PICTURE_TEXT:
										/**
										 * @var $share \ULib\Share\ShareFile
										 */
										class_member();//优先调用用户登录类
										if(($msg = $share->downloadFile()) !== true){
											Log::write($msg . ":" . $share->getUname());
											$this->__view("home/server_error.php", ['msg' => $msg]);
										} else{
											$share->activeSet();
										}
										break;
									default:
										$this->__load_404();
										break;
								}
								break;
							case Share::VIEW_TEXT:
								switch($share->getShareType()){
									case Share::TYPE_PICTURE_TEXT:
										header("Content-Type: text/plain; charset=utf-8");
										class_member();//优先调用用户登录类
										/**
										 * @var $share \ULib\Share\SharePictureText
										 */
										echo $share->getText();
										$share->activeSet();
										break;
									case Share::TYPE_CODE:
										header("Content-Type: text/plain; charset=utf-8");
										class_member();//优先调用用户登录类
										echo $share->getPrimaryData();
										$share->activeSet();
										break;
									default:
										$this->__load_404();
										break;
								}
								break;
							case Share::VIEW_SCRIPT:
								switch($share->getShareType()){
									case Share::TYPE_CODE:
										class_member();//优先调用用户登录类
										/**
										 * @var $share \ULib\Share\ShareCode
										 */
										$share->setContentType();
										echo $share->getPrimaryData();
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
										class_member();//优先调用用户登录类
										$this->__view("share/url.php", ['share' => $share]);
										$share->activeSet();
										break;
									case Share::TYPE_TEXT:
										class_member();//优先调用用户登录类
										$this->__view("share/text.php", ['share' => $share]);
										$share->activeSet();
										break;
									case Share::TYPE_CODE:
										class_member();//优先调用用户登录类
										$this->__view("share/code.php", ['share' => $share]);
										$share->activeSet();
										break;
									case Share::TYPE_FILE:
										//不下载，不触发记录
										$this->__view("share/file.php", ['share' => $share]);
										break;
									case Share::TYPE_MARKDOWN:
										class_member();//优先调用用户登录类
										$this->__view("share/markdown.php", ['share' => $share]);
										$share->activeSet();
										break;
									case Share::TYPE_PICTURE:
										//图片信息为引用方式，不触发
										$this->__view("share/picture.php", ['share' => $share]);
										break;
									case Share::TYPE_PICTURE_TEXT:
										class_member();//优先调用用户登录类
										$this->__view("share/picture-text.php", ['share' => $share]);
										$share->activeSet();
										break;
									case Share::TYPE_MULTI_TEXT:
										class_member();//优先调用用户登录类
										$this->__view("share/multi-text.php", ['share' => $share]);
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
		} catch(\Exception $ex){
			$this->__view("home/server_error.php", ['msg' => $ex->getMessage()]);
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
			case "code":
				$this->__view("add/code.php");
				break;
			case "file":
				$this->__view("add/file.php");
				break;
			case "markdown":
				$this->__view("add/markdown.php");
				break;
			case "picture":
				$this->__view("add/picture.php");
				break;
			case "picture-text":
				$this->__view("add/picture-text.php");
				break;
			case "multi-text":
				$this->__view("add/multi-text.php");
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