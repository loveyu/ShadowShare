<?php
/**
 * User: loveyu
 * Date: 2015/2/26
 * Time: 18:21
 */

namespace ULib\Share;


use ULib\Markdown\Parsedown;
use ULib\Share;
use ULib\Xss\XssHtml;

class ShareMarkdown extends Share{
	/**
	 * @var string markdown主要数据
	 */
	private $markdown;

	public function __construct(){
		$this->share_type = self::TYPE_MARKDOWN;
		parent::__construct();
	}

	/**
	 * 设置对应要分享的内容
	 * @param mixed $data
	 * @return bool 设置的状态
	 */
	public function setData($data){
		return class_db()->d_share_markdown_insert($this->base_data['s_id'], $data);
	}

	/**
	 * 初始化拓展基本信息
	 * @throws \Exception
	 * @return void
	 */
	public function initExtData(){
		$ex_data = class_db()->d_share_markdown_get($this->base_data['s_id']);
		if(!isset($ex_data['s_id'])){
			throw new \Exception("数据获取异常");
		}
		$this->markdown = $ex_data['sm_content'];
	}

	/**
	 * 返回主要的信息
	 * @return mixed
	 */
	public function getPrimaryData(){
		return $this->markdown;
	}

	/**
	 * 获取HTML数据
	 * @return string
	 * @throws \Exception
	 */
	public function getHtml(){
		lib()->load('Markdown/Parsedown', 'Xss/XssHtml');
		$parse = new Parsedown();
		$xss = new XssHtml($parse->text(htmlspecialchars($this->markdown)));
		return $xss->getHtml();
	}
}