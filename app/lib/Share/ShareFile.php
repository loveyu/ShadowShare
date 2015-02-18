<?php
/**
 * User: loveyu
 * Date: 2015/2/18
 * Time: 12:47
 */

namespace ULib\Share;


use CLib\Upload;
use ULib\Share;

class ShareFile extends Share{
	private $info;

	public function __construct(){
		$this->share_type = self::TYPE_FILE;
		parent::__construct();
	}

	/**
	 * 设置对应要分享的内容
	 * @param mixed $data
	 * @return bool 设置的状态
	 */
	public function setData($data){
		c_lib()->load('upload');
		$upload = new Upload([
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
		], 'Local', ['server_root_path' => _RootPath_]);
		$info = $upload->uploadOne($data);
		$type = mime_get($info['ext']);
		if($type != $info['type'] && !empty($type) && (empty($info['type']) || $info['type'] == "application/octet-stream")){
			$info['type'] = $type;
		}
		return class_db()->d_share_file_insert($this->base_data['s_id'], $info['md5'], $info['sha1'], $info['name'], $info['type'], $info['size'], $info['save_name'], $info['save_path']);
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

	public function downloadFile(){
		if(empty($this->info['sf_type'])){
			header("Content-Disposition: attachment; filename=" . $this->info['sf_name']);
		}else{
			header("Content-Disposition: inline; filename=" . $this->info['sf_name']);
		}
		header("Content-Type: " . (empty($this->info['sf_type']) ? "application/force-download" : $this->info['sf_type']) . ";");
		header("Content-Length: " . $this->info['sf_size']);
		flush();
		$name = _RootPath_ . _DATA_FILE_ . "/" . $this->info['sf_save_path'] . $this->info['sf_save_name'];
		$fp = fopen($name, "r");
		while(!feof($fp)){
			echo fread($fp, 65536);
			flush();
		}
		fclose($fp);
	}

	public function getFileName(){
		return $this->info['sf_name'];
	}

	public function getFileSize(){
		return $this->info['sf_size'];
	}

	public function getFileReadSize(){
		return file_h_size($this->info['sf_size']);
	}
}