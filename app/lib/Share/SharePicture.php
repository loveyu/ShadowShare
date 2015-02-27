<?php
/**
 * User: loveyu
 * Date: 2015/2/27
 * Time: 17:55
 */

namespace ULib\Share;


/**
 * Class SharePicture
 * @package ULib\Share
 */
class SharePicture extends ShareFile{

	/**
	 * @var int 宽度
	 */
	protected $width;
	/**
	 * @var int 高度
	 */
	protected $height;

	/**
	 * 初始化
	 */
	public function __construct(){
		parent::__construct(self::TYPE_PICTURE);
		$this->upload_config = [
			'max_size' => 1024 * 1024 * 5,
			'image_info' => true
		];
	}

	/**
	 * 设置分享的数据
	 * @param mixed $data
	 * @return bool
	 */
	public function setData($data){
		if(!parent::setData($data)){
			return false;
		}
		return class_db()->d_share_picture_insert($this->base_data['s_id'], $this->info['image']['width'], $this->info['image']['height']);
	}

	/**
	 * 初始化数据
	 * @throws \Exception
	 */
	public function initExtData(){
		parent::initExtData();
		$wh = class_db()->d_share_picture_get($this->base_data['s_id']);
		if(!isset($wh['s_id'])){
			throw new \Exception("数据获取异常，图片信息丢失");
		}
		$this->width = $wh['sp_width'];
		$this->height = $wh['sp_height'];
	}

	/**
	 * @return int
	 */
	public function getWidth(){
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight(){
		return $this->height;
	}

}