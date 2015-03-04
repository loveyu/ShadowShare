<?php
/**
 * User: loveyu
 * Date: 2015/3/3
 * Time: 18:20
 */

namespace ULib\Share;


use CLib\Ip;
use ULib\Share;

class ShareMultiText extends Share{

	private $text = [];
	private $index = 0;
	private $max_count = 0;
	private $expire = 0;

	/**
	 * 新的索引值，如取新的数值就将此记录添加1，让后增加一次记录
	 * @var null|int
	 */
	private $new_index = NULL;

	/**
	 * @var string 当前访问IP
	 */
	private $now_ip;

	private $last_visit_data;

	/**
	 * 初始化
	 */
	function __construct(){
		$this->share_type = self::TYPE_MULTI_TEXT;
		parent::__construct();
	}


	/**
	 * 设置当前的分享字符串集
	 * @param string $text
	 * @return bool
	 */
	public function setText($text){
		$list = explode("\n", $text);
		$text_list = [];
		foreach($list as $v){
			$v = trim($v);
			if($v === ""){
				continue;
			}
			$text_list[] = $v;
		}
		if(empty($text_list)){
			return false;
		}
		$this->text = $text_list;
		return true;
	}

	/**
	 * 重写创建类，添加更多信息
	 * @param int  $mid
	 * @param null $more
	 * @return bool
	 */
	public function create($mid, $more = NULL){
		$c = count($this->text);
		if($c < 1){
			return false;
		}
		return parent::create($mid, ['s_share_max' => $c]);
	}

	/**
	 * 重新检查数据
	 * @return int
	 */
	public function activeCheck(){
		$rt = parent::activeCheck();
		if($rt != self::ACTIVE_NORMAL){
			return $rt;
		}
		c_lib()->load('ip');
		$this->now_ip = Ip::getInstance()->realip();
		$this->last_visit_data = class_db()->d_share_multi_text_map_get_last($this->base_data['s_id'], $this->now_ip);
		if(isset($this->last_visit_data['smtm_ip'])){
			//纯在最后访问数据，为重复访问
			if($this->expire == 0 || $this->last_visit_data['smtm_time'] + $this->expire > NOW_TIME){
				//未超时
				$this->index = $this->last_visit_data['smt_index'];//当前访问序列变为原始序列
				return self::ACTIVE_NORMAL;
			}
		}

		if($this->index >= $this->max_count || !isset($this->text[$this->index])){
			$this->active_error_msg = "当前分享已无效，数据分享完毕";
			return self::ACTIVE_LIMIT;
		}

		//添加新记录
		$this->new_index = $this->index + 1;

		return self::ACTIVE_NORMAL;
	}

	public function activeSet(){
		if($this->base_object->getMid() === 0 || $this->base_object->getMid() !== class_member()->getUid()){
			//开始设置访问数据
			if(is_int($this->new_index) && $this->new_index >= 0){
				//判断新的索引是否变化
				parent::activeSet();//原纪录访问
				$db = class_db();
				$db->d_share_multi_text_update($this->base_data['s_id'], ['smt_index[+]' => 1]);
				$db->d_share_multi_text_map_insert($this->base_data['s_id'], $this->now_ip, NOW_TIME, $this->index, 1);
			} else{
				//更新计数器
				class_db()->d_share_multi_text_map_update_count($this->last_visit_data['s_id'], $this->last_visit_data['smtm_ip'], $this->last_visit_data['smtm_time']);
			}
		}
	}


	/**
	 * 设置对应要分享的内容
	 * @param mixed $data
	 * @return bool 设置的状态
	 */
	public function setData($data){
		/**
		 * 设置一天的超时
		 */
		return class_db()->d_share_multi_text_insert($this->base_data['s_id'], implode("\n", $this->text), count($this->text), 24 * 3600);
	}

	/**
	 * 初始化拓展基本信息
	 * @throws \Exception
	 * @return void
	 */
	public function initExtData(){
		$ex_data = class_db()->d_share_multi_text_get($this->base_data['s_id']);
		if(!isset($ex_data['s_id'])){
			throw new \Exception("数据获取异常");
		}
		$this->text = explode("\n", $ex_data['smt_text']);
		$this->index = $ex_data['smt_index'];
		$this->max_count = $ex_data['smt_max'];
		$this->expire = $ex_data['smt_expire'];
	}

	/**
	 * 返回主要的信息
	 * @return mixed
	 */
	public function getPrimaryData(){
		if(!isset($this->text[$this->index])){
			false;
		}
		return $this->text[$this->index];
	}

}