<?php
/**
 * User: loveyu
 * Date: 2015/1/31
 * Time: 16:34
 */

namespace UView;


use ULib\RestApi;

class Create extends RestApi{
	public function __construct(){
		parent::__construct(NULL, ['method' => 'POST']);
		ignore_user_abort(true);//忽略用户中断
	}

	/**
	 * 文本分享API
	 */
	public function text(){
		if(!$this->_run_check()){
			return;
		}
		$text = $this->__req->post('text');
		$empty = trim($text);
		if(empty($empty)){
			$this->_set_status(false, 3011, "文本分享内容不能为空");
			return;
		}
		try{
			$share = class_share('Text');
			if($share->create(class_member()->getUid())){
				if($share->setData($text)){
					$this->_set_status(true, 0);
					$url = get_url($share->getUname());
					$this->_set_data([
						'uname' => $share->getUname(),
						'url' => $url,
						'raw' => $url . "?m=raw"
					]);
				} else{
					$share->delete_failed_share();
					$this->_set_status(false, 3013, '分享数据设置失败');
				}
			} else{
				$this->_set_status(false, 3012, '创建分享失败');
			}
		} catch(\Exception $ex){
			$this->_set_status(false, 3014, $ex->getMessage());
		}
	}

	/**
	 * 网址分享API
	 */
	public function url(){
		if(!$this->_run_check()){
			return;
		}
		$url = trim($this->__req->post('url'));
		if(!filter_var($url, FILTER_VALIDATE_URL)){
			$this->_set_status(false, 3001, "URL验证出错");
			return;
		}
		try{
			$share = class_share('Url');
			if($share->create(class_member()->getUid())){
				if($share->setData($url)){
					$this->_set_status(true, 0);
					$url = get_url($share->getUname());
					$this->_set_data([
						'uname' => $share->getUname(),
						'url' => $url,
						'redirect' => $url . "?m=go"
					]);
				} else{
					$share->delete_failed_share();
					$this->_set_status(false, 3003, '分享数据设置失败');
				}
			} else{
				$this->_set_status(false, 3002, '创建分享失败');
			}
		} catch(\Exception $ex){
			$this->_set_status(false, 3004, $ex->getMessage());
		}
	}

	/**
	 * 单文件分享
	 */
	public function file(){
		if(!$this->_run_check()){
			return;
		}
		if(!isset($_FILES['file']['error']) || $_FILES['file']['error'] != 0){
			$this->_set_status(false, 3021, "上传的文件有误，请重试");
			return;
		}
		try{
			$share = class_share('File');
			if($share->create(class_member()->getUid())){
				if($share->setData($_FILES['file'])){
					$this->_set_status(true, 0);
					$url = get_url($share->getUname());
					$this->_set_data([
						'uname' => $share->getUname(),
						'url' => $url,
						'download' => $url . "?m=bin"
					]);
				} else{
					$share->delete_failed_share();
					$this->_set_status(false, 3023, '分享数据设置失败');
				}
			} else{
				$this->_set_status(false, 3022, '创建分享失败');
			}
		} catch(\Exception $ex){
			$this->_set_status(false, 3024, $ex->getMessage());
		}
	}

	/**
	 * Markdown分享
	 */
	public function markdown(){
		if(!$this->_run_check()){
			return;
		}
		$data = trim($this->__req->post('data'));
		if(empty($data)){
			$this->_set_status(false, 3031, "数据不允许为空");
			return;
		}
		try{
			$share = class_share('Markdown');
			if($share->create(class_member()->getUid())){
				if($share->setData($data)){
					$this->_set_status(true, 0);
					$url = get_url($share->getUname());
					$this->_set_data([
						'uname' => $share->getUname(),
						'url' => $url,
						'raw' => $url . "?m=raw",
						'html' => $url . "?m=html"
					]);
				} else{
					$share->delete_failed_share();
					$this->_set_status(false, 3033, '分享数据设置失败');
				}
			} else{
				$this->_set_status(false, 3032, '创建分享失败');
			}
		} catch(\Exception $ex){
			$this->_set_status(false, 3034, $ex->getMessage());
		}
	}

	/**
	 * 图片分享
	 */
	public function picture(){
		if(!$this->_run_check()){
			return;
		}
		if(!isset($_FILES['picture']['error']) || $_FILES['picture']['error'] != 0){
			$this->_set_status(false, 3041, "上传的文件有误，请重试");
			return;
		}
		try{
			$share = class_share('Picture', 'File');
			if($share->create(class_member()->getUid())){
				if($share->setData($_FILES['picture'])){
					$this->_set_status(true, 0);
					$url = get_url($share->getUname());
					$this->_set_data([
						'uname' => $share->getUname(),
						'url' => $url,
						'image' => $url . "?m=img"
					]);
				} else{
					$share->delete_failed_share();
					$this->_set_status(false, 3043, '分享数据设置失败');
				}
			} else{
				$this->_set_status(false, 3042, '创建分享失败');
			}
		} catch(\Exception $ex){
			$this->_set_status(false, 3044, $ex->getMessage());
		}
	}
}