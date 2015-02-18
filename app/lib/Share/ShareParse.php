<?php
/**
 * User: loveyu
 * Date: 2015/2/15
 * Time: 14:22
 */

namespace ULib\Share;


use ULib\Share;

/**
 * 分享解析类，用于解析一个特定的分享对象
 * Class ShareParse
 * @package ULib\Share
 */
class ShareParse extends Share{

	/**
	 * @var int 覆盖父类接口
	 */
	protected $share_type = 0;

	/**
	 * 设置对应要分享的内容
	 * @param mixed $data
	 * @return bool 设置的状态
	 */
	public function setData($data){
		return false;
	}

	/**
	 * 移除创建对象
	 * @param int  $mid
	 * @param null $more
	 * @return false
	 */
	public function create($mid, $more = NULL){
		return false;
	}


	/**
	 * 解析数据
	 * @param string $uname
	 * @return Share|false
	 */
	public function parse($uname){
		$id = uname2id($uname);
		if($uname != id2uname($id)){
			return false;
		}
		$info = $this->baseInfo($id, $uname);
		if(!isset($info['s_id'])){
			return false;
		}
		switch($info['s_type']){
			case self::TYPE_URL:
				$share = class_share("Url");
				break;
			case self::TYPE_TEXT:
				$share = class_share("Text");
				break;
			default:
				return false;
		}
		$share->setBaseData($info);
		$share->initExtData();
		return $share;
	}

	private function baseInfo($s_id, $uname){
		$info = class_db()->d_share_get($s_id);
		if(isset($info['s_id']) && $info['s_uname'] == $uname){
			return $info;
		}
		return false;
	}

	/**
	 * 初始化拓展基本信息
	 * @return void
	 */
	public function initExtData(){
	}

	/**
	 * 返回主要的信息
	 * @return mixed
	 */
	public function getPrimaryData(){
		return NULL;
	}


	public function getAllId(){
		//DEBUG 方法
		$list = class_db()->getDriver()->select("share", "s_uname");
		return $list;
	}
}