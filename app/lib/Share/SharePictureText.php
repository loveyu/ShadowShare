<?php
/**
 * User: loveyu
 * Date: 2015/3/1
 * Time: 12:52
 */

namespace ULib\Share;


class SharePictureText extends ShareFile{
	/**
	 * @var int 宽度
	 */
	protected $width;
	/**
	 * @var int 高度
	 */
	protected $height;

	/**
	 * @var int 图片定位,0:顶部居中,1:左浮动,2右浮动,3:底部
	 */
	protected $position;

	/**
	 * @var string 分享文本
	 */
	protected $text;

	/**
	 * 初始化
	 */
	public function __construct(){
		parent::__construct(self::TYPE_PICTURE_TEXT);
		$this->upload_config = [
			'max_size' => 204800,
			'image_info' => true
		];
	}

	/**
	 * 设置分享的数据
	 * @param array $data
	 * @return bool
	 */
	public function setData($data){
		if(!parent::setData($data['file'])){
			return false;
		}

		return class_db()->d_share_picture_text_insert($this->base_data['s_id'], $this->info['image']['width'], $this->info['image']['height'], $data['text'], $data['position']);
	}

	/**
	 * 初始化数据
	 * @throws \Exception
	 */
	public function initExtData(){
		parent::initExtData();
		$wh = class_db()->d_share_picture_text_get($this->base_data['s_id']);
		if(!isset($wh['s_id'])){
			throw new \Exception("数据获取异常，图片信息丢失");
		}
		$this->width = $wh['spt_image_width'];
		$this->height = $wh['spt_image_height'];
		$this->text = $wh['spt_text'];
		$this->position = $wh['spt_position'];
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

	/**
	 * @return int
	 */
	public function getPosition(){
		return $this->position;
	}

	/**
	 * @return string
	 */
	public function getText(){
		return $this->text;
	}

	/**
	 * 获取HTML输出的内容
	 * @return string
	 */
	public function getHtml(){
		$text = "\n";
		foreach(explode("\n", $this->text) as $v){
			$text .= "\t\t<p>" . str_replace(" ", "&nbsp;", htmlspecialchars(trim($v))) . "</p>\n";
		}
		switch($this->position){
			case 1:
				return "<div id='SharePictureTextData' class='clearfix'>
	<div class='image image-left'>
		<img src=\"" . $this->getBase64Encode() . "\" alt=\"" . $this->getFileName() . "\" />
	</div>
	<div class=\"text\">" . $text . "</div>
	<div style=\"clear:both\"></div>
</div>";
				break;
			case 2:
				return "<div id='SharePictureTextData' class='clearfix'>
	<div class='image image-right'>
		<img src=\"" . $this->getBase64Encode() . "\" alt=\"" . $this->getFileName() . "\" />
	</div>
	<div class=\"text\">" . $text . "</div>
	<div style=\"clear:both\"></div>
</div>";
				break;
			case 3:
				return "<div id='SharePictureTextData' class='clearfix'>
	<div class=\"text\">" . $text . "</div>
	<div class='image'>
		<img src=\"" . $this->getBase64Encode() . "\" alt=\"" . $this->getFileName() . "\" />
	</div>
</div>";
				break;
			default:
				return "<div id='SharePictureTextData' class='clearfix'>
	<div class='image'>
		<img src=\"" . $this->getBase64Encode() . "\" alt=\"" . $this->getFileName() . "\" />
	</div>
	<div class=\"text\">" . $text . "</div>
</div>";
		}
	}
}