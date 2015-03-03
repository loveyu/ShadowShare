<?php
/**
 * User: loveyu
 * Date: 2015/3/1
 * Time: 21:59
 */

namespace ULib\Share;


use ULib\Share;

/**
 * Class ShareCode
 * @package ULib\Share
 */
class ShareCode extends Share{

	/**
	 * @var string
	 */
	private $text = NULL;
	/**
	 * @var string
	 */
	private $lang = "text";

	/**
	 * @var array
	 */
	protected $lang_map = array(
		'as3' => array(
			'name' => 'AS3',
			'ex' => 'as',
			'head' => 'application/x-actionscript',
		),
		'applescript' => array(
			'name' => 'AppleScript',
			'ex' => 'scpt',
			'head' => 'text/applescript',
		),
		'bash' => array(
			'name' => 'Bash',
			'ex' => 'sh',
			'head' => 'application/x-sh',
		),
		'csharp' => array(
			'name' => 'C#',
			'sn' => 'CSharp',
			'ex' => 'cs',
			'head' => 'text/plain',
		),
		'coldfusion' => array(
			'name' => 'ColdFusion',
			'ex' => 'cf',
			'head' => 'text/plain',
		),
		'cpp' => array(
			'name' => 'Cpp',
			'ex' => 'cpp',
			'head' => 'text/cpp',
		),
		'css' => array(
			'name' => 'Css',
			'ex' => 'css',
			'head' => 'text/css',
		),
		'delphi' => array(
			'name' => 'Delphi',
			'ex' => 'delphi',
			'head' => 'text/plain',
		),
		'diff' => array(
			'name' => 'Diff',
			'ex' => 'diff',
			'head' => 'text/plain',
		),
		'erlang' => array(
			'name' => 'Erlang',
			'ex' => 'erl',
			'head' => 'text/plain',
		),
		'groovy' => array(
			'name' => 'Groovy',
			'ex' => 'groovy',
			'head' => 'text/plain',
		),
		'js' => array(
			'name' => 'JavaScript',
			'sn' => 'JScript',
			'ex' => 'js',
			'head' => 'application/javascript',
		),
		'json' => array(
			'name' => 'JSON',
			'sn' => 'JScript',
			'ex' => 'json',
			'head' => 'application/json',
		),
		'java' => array(
			'name' => 'Java',
			'ex' => 'java',
			'head' => 'text/x-java-source',
		),
		'javafx' => array(
			'name' => 'JavaFX',
			'ex' => 'fx',
			'head' => 'text/plain',
		),
		'perl' => array(
			'name' => 'Perl',
			'ex' => 'pl',
			'head' => 'text/plain',
		),
		'php' => array(
			'name' => 'Php',
			'ex' => 'php',
			'head' => 'text/php',
		),
		'plain' => array(
			'name' => 'Plain',
			'ex' => 'txt',
			'head' => 'text/plain',
		),
		'powershell' => array(
			'name' => 'PowerShell',
			'ex' => 'ps1',
			'head' => 'application/octet-stream',
		),
		'python' => array(
			'name' => 'Python',
			'ex' => 'py',
			'head' => 'text/plain',
		),
		'ruby' => array(
			'name' => 'Ruby',
			'ex' => 'rb',
			'head' => 'text/ruby',
		),
		'sass' => array(
			'name' => 'Sass',
			'ex' => 'sass',
			'head' => 'text/plain',
		),
		'scala' => array(
			'name' => 'Scala',
			'ex' => 'scala',
			'head' => 'text/plain',
		),
		'sql' => array(
			'name' => 'Sql',
			'ex' => 'sql',
			'head' => 'application/x-sql',
		),
		'vb' => array(
			'name' => 'Vb',
			'ex' => 'vb',
			'head' => 'text/plain',
		),
		'xml' => array(
			'name' => 'Xml',
			'ex' => 'xml',
			'head' => 'application/xml',
		),
	);

	/**
	 * 初始化
	 */
	function __construct(){
		$this->share_type = self::TYPE_CODE;
		parent::__construct();
	}

	/**
	 * 设置对应要分享的内容
	 * @param mixed $data
	 * @return bool 设置的状态
	 */
	public function setData($data){
		return class_db()->d_share_code_insert($this->base_data['s_id'], $data['code'], $data['lang']);
	}

	/**
	 * 初始化拓展基本信息
	 * @throws \Exception
	 * @return void
	 */
	public function initExtData(){
		$ex_data = class_db()->d_share_code_get($this->base_data['s_id']);
		if(!isset($ex_data['s_id'])){
			throw new \Exception("数据获取异常");
		}
		$this->text = $ex_data['sc_code'];
		$this->lang = $ex_data['sc_lang'];
	}

	/**
	 * 返回主要的信息
	 * @return mixed
	 */
	public function getPrimaryData(){
		return $this->text;
	}

	/**
	 * 获取当前代码的语言
	 * @return string
	 */
	public function getLang(){
		return $this->lang;
	}

	/**
	 * 获取全部语言列表
	 * @return array
	 */
	public function getLangMap(){
		return $this->lang_map;
	}

	/**
	 * 是否存在某一语言
	 * @param $lang
	 * @return bool
	 */
	public function hasLang($lang){
		return isset($this->lang_map[$lang]);
	}

	/**
	 * 获取某一语言对应的文件简称
	 * @param $lang
	 * @return string
	 */
	public function getLangValue($lang){
		return isset($this->lang_map[$lang]) ? (isset($this->lang_map[$lang]['sn']) ? $this->lang_map[$lang]['sn'] : $this->lang_map[$lang]['name']) : "Plain";
	}

	/**
	 * 设置对应代码的头信息
	 */
	public function setContentType(){
		if(isset($this->lang_map[$this->lang])){
			$map = $this->lang_map[$this->lang];
		} else{
			$map = [
				'name' => 'plain',
				'ex' => 'txt',
				'head' => 'text/plain'
			];
		}
		header("Content-Type: {$map['head']}; charset=utf-8");
		header("Content-Disposition: inline; filename=" . $this->getUname() . ".{$map['ex']}");
	}

	/**
	 * 返回高亮后的代码
	 * @return string
	 */
	public function getHtml(){
		//TODO 未处理其他语言
		return highlight_string($this->text, true);
	}
}