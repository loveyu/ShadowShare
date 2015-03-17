<?php
/**
 * User: loveyu
 * Date: 2015/3/17
 * Time: 14:50
 */

namespace ULib;

use ULib\Member\DaoShare;

/**
 * 用户分享列表查询或操作
 * Class ShareList
 * @package ULib
 */
class ShareList{
	/**
	 * 用户ID
	 * @var int
	 */
	private $m_id;

	/**
	 * 数据库操作对象
	 * @var DaoShare
	 */
	private $dao;

	/**
	 * 多行文本类型
	 */
	const TYPE_MULTI_TEXT = 3;

	/**
	 * @var array 数据类型描述
	 */
	private $type_map = [
		0 => [
			'name' => 'Text',
			'desc' => '文本'
		],
		1 => [
			'name' => 'File',
			'desc' => '文件'
		],
		2 => [
			'name' => 'Code',
			'desc' => '代码'
		],
		3 => [
			'name' => 'Multi-Text',
			'desc' => '多行文本'
		],
		4 => [
			'name' => 'Url',
			'desc' => '网址'
		],
		5 => [
			'name' => 'Picture',
			'desc' => '图片'
		],
		6 => [
			'name' => 'Markdown',
			'desc' => 'Markdown'
		],
		7 => [
			'name' => 'Picture-Text',
			'desc' => '图文'
		],
	];

	/**
	 * @param int $m_id 必须通过用户ID进行初始化
	 * @throws \Exception
	 */
	public function __construct($m_id){
		$this->m_id = intval($m_id);
		lib()->load('Share');//导入ULib\Share对象
		if(empty($this->m_id)){
			throw new \Exception("当前用户ID不允许为空");
		}
	}

	/**
	 * 分析用户的分享信息
	 * @return array
	 */
	public function analyse(){
		$list = list2keymap($this->getDao()->count_share_by_id($this->m_id), 's_type', 'count', 'intval');
		foreach(array_keys($list) as $key){
			$list[$key] = [
				'count' => $list[$key],
				'name' => $this->type_map[$key]['name'],
				'desc' => $this->type_map[$key]['desc']
			];
		}
		return $list;
	}

	public function select($type){
		$data = $this->getDao()->select($this->m_id, $type);
		$rt = [];
		$url = get_url_map('home');
		foreach($data as $v){
			$rt[$v['s_id']] = [
				'id' => $v['s_id'],
				'url' => $url . $v['s_uname'],
				'password' => $v['s_key'],
				'time' => date("Y-m-d H:i:s", $v['s_time_share']),
				'count' => $v['s_share_count'],
				'less' => $v['s_share_max'] - $v['s_share_count']
			];
			if($type == ShareList::TYPE_MULTI_TEXT){
				//修正多行文本统计次数多一的情况
				$rt[$v['s_id']]['count']--;
				$rt[$v['s_id']]['less']--;
			}
			if($rt[$v['s_id']]['count'] < 0){
				$rt[$v['s_id']]['count'] = 0;
			}
			if($rt[$v['s_id']]['less'] < 0){
				$rt[$v['s_id']]['less'] = 0;
			}
		}
		return $rt;
	}

	/**
	 * 检查一个名称是否存在列表中
	 * @param string $type
	 * @param string $name 返回一个描述名称
	 * @return int|null 失败返回NULL，全等判断
	 */
	public function checkTypeName($type, &$name){
		$list = array_flip(list2keymapSK($this->type_map, 'name', 'strtolower'));
		if(isset($list[$type])){
			$name = $this->type_map[$list[$type]]['desc'];
			return $list[$type];
		}
		$name = NULL;
		return NULL;
	}

	/**
	 * 获取数据库对象
	 * @return DaoShare
	 * @throws \Exception
	 */
	private function getDao(){
		if($this->dao === NULL){
			lib()->load('Member/DaoShare');
			$this->dao = new DaoShare();
		}
		return $this->dao;
	}
}