<?php
/**
 * User: loveyu
 * Date: 2015/2/18
 * Time: 12:47
 */

namespace ULib\Share;


use CLib\Upload;
use Core\Log;
use ULib\Share;

/**
 * Class ShareFile
 * @package ULib\Share
 */
class ShareFile extends Share{
	/**
	 * @var array 文件数据信息
	 */
	protected $info;

	/**
	 * @var array 上传文件大小
	 */
	protected $upload_config = [];

	/**
	 * 初始化
	 * @param int $share_type 初始化类型，设置为可继承
	 * @throws \Exception
	 */
	public function __construct($share_type = self::TYPE_FILE){
		$this->share_type = $share_type;
		parent::__construct();
	}

	/**
	 * 设置对应要分享的内容
	 * @param mixed $data
	 * @return bool 设置的状态
	 */
	public function setData($data){
		c_lib()->load('upload');
		$upload = new Upload(array_merge([
			'root_path' => _DATA_FILE_,
			'max_size' => 1024 * 1024,
			'sub_status' => true,
			'make_hash' => true,
			'save_ext' => 'dt',
			'replace' => 'true',
			'sub_path' => [
				function ($info){
					return implode("/", str_split(substr($info['md5'], 0, 4), 2));
				},
				'__FILE_INFO__'
			],
			'name_callback' => [
				function ($info){
					return $info['md5'] . "_" . $info['sha1'];
				},
				'__FILE_INFO__'
			]
		], $this->upload_config), 'Local', ['server_root_path' => _RootPath_]);
		try{
			$info = $upload->uploadOne($data);
			$type = mime_get($info['ext']);
			if($type != $info['type'] && !empty($type) && (empty($info['type']) || $info['type'] == "application/octet-stream")){
				$info['type'] = $type;
			}
			$this->info = $info;//用于子类处理数据
			return class_db()->d_share_file_insert($this->base_data['s_id'], $info['md5'], $info['sha1'], $info['name'], $info['type'], $info['size'], $info['save_name'], $info['save_path']);
		} catch(\Exception $ex){
			Log::write($ex->getMessage());
			return false;
		}
	}

	/**
	 * 初始化拓展基本信息
	 * @throws \Exception
	 * @return void
	 */
	public function initExtData(){
		$this->info = class_db()->d_share_file_get($this->base_data['s_id']);
		if(!isset($this->info['s_id'])){
			throw new \Exception("数据获取异常");
		}
	}

	/**
	 * 返回主要的信息
	 * @return mixed
	 */
	public function getPrimaryData(){
		return $this->info;
	}

	/**
	 * 开始下载当前文件
	 */
	public function downloadFile(){
		$name = _RootPath_ . _DATA_FILE_ . "/" . $this->info['sf_save_path'] . $this->info['sf_save_name'];
		if(!file_exists($name)){
			return "服务器文件丢失";
		}
		if(!is_readable($name)){
			return "服务器文件读取失败";
		}
		if(empty($this->info['sf_type'])){
			header("Content-Disposition: attachment; filename=" . $this->info['sf_name']);
		} else{
			header("Content-Disposition: inline; filename=" . $this->info['sf_name']);
		}
		header("Content-Type: " . (empty($this->info['sf_type']) ? "application/force-download" : $this->info['sf_type']) . ";");
		header("Content-Length: " . $this->info['sf_size']);
		flush();
		$fp = fopen($name, "r");
		while(!feof($fp)){
			echo fread($fp, 65536);
			flush();
		}
		fclose($fp);
		return true;
	}

	/**
	 * 返回Base64编码的链接
	 * @return false|string
	 */
	public function getBase64Encode(){
		$name = _RootPath_ . _DATA_FILE_ . "/" . $this->info['sf_save_path'] . $this->info['sf_save_name'];
		if(!file_exists($name) || !is_readable($name)){
			return false;
		}
		$data = base64_encode(file_get_contents($name));
		return "data:" . (empty($this->info['sf_type']) ? "application/force-download" : $this->info['sf_type']) . ";base64," . $data;
	}

	/**
	 * 返回文件名
	 * @return string
	 */
	public function getFileName(){
		return $this->info['sf_name'];
	}

	/**
	 * 返回文件大小
	 * @return int
	 */
	public function getFileSize(){
		return $this->info['sf_size'];
	}

	/**
	 * 返回一个可读的文件大小
	 * @return string
	 */
	public function getFileReadSize(){
		return file_h_size($this->info['sf_size']);
	}
}