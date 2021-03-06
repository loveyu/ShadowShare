<?php
/**
 * User: loveyu
 * Date: 2015/1/29
 * Time: 0:16
 */

/**
 * 将十进制转为任意进制的数组
 * @param number $number
 * @param number $to_base
 * @return array|false
 */
function dec_base_convert($number, $to_base){
	if($number < 0 || $to_base < 2){
		return false;
	}
	$rt = [];
	while($number > 0){
		$rt[] = $number % $to_base;
		$number = floor($number / $to_base);
	}
	return array_reverse($rt);
}

/**
 * 将ID转换为唯一名称
 * @param int $id
 * @param int $len 最小长度
 * @return bool|string
 */
function id2uname($id, $len = 4){
	$str = "9Hw4xIldK2BafFCRZVyro8OJcMztS1gYUnsqk3PmELQv5XhbWpeTjDui76AGN";
	$x = dec_base_convert($id, 61);
	if(!is_array($x)){
		return false;
	}
	$now_len = count($x);
	if($len > $now_len){
		for(; $now_len < $len; $now_len++){
			array_unshift($x, 0);
		}
	}
	$rt = "";
	for($i = 0; $i < $now_len; $i++){
		$p = $x[$i] + $i;
		if($p > 60){
			$p -= 61;
		}
		if($p < 0){
			return false;
		}
		$rt .= $str[$p];
	}
	return $rt;
}

/**
 * 将一个名称转换为对应的ID
 * @param string $name
 * @return bool|int
 */
function uname2id($name){
	$str = "9Hw4xIldK2BafFCRZVyro8OJcMztS1gYUnsqk3PmELQv5XhbWpeTjDui76AGN";
	$list = array_flip(str_split($str));
	$len = strlen($name);
	$id = 0;
	$base = 1;
	for($i = $len - 1; $i >= 0; $i--){
		if(!isset($list[$name[$i]])){
			return false;
		}
		$p = $list[$name[$i]] - $i;
		if($p < 0){
			$p += 61;
		}
		$id += $p * $base;
		$base *= 61;
	}
	return $id;
}

/**
 * 生成随机字符
 * @param int $len
 * @return string
 */
function salt($len = 12){
	$output = '';
	for($a = 0; $a < $len; $a++){
		$output .= chr(mt_rand(33, 126)); //生成php随机数
	}
	return $output;
}

/**
 * Md5的包装器
 * @param string $str
 * @return string
 */
function _md5($str){
	return md5($str . "\xFF\xFF");
}

/**
 * 通过加盐生成hash值
 * @param $hash
 * @param $salt
 * @return string
 */
function salt_hash($hash, $salt){
	$count = count($salt);
	return _hash(substr($salt, 0, $count / 3) . $hash . $salt);
}

/**
 * 单独封装hash函数
 * @param      $str
 * @param bool $raw_output 为true时返回二进制数据
 * @return string
 */
function _hash($str, $raw_output = false){
	return hash("sha256", $str, $raw_output);
}

/**
 * 将数据列表转为KeyMap
 * @param array        $list
 * @param string       $key
 * @param string|array $value
 * @param callback     $value_callback
 * @return array
 */
function list2keymap($list, $key, $value, $value_callback = NULL){
	if(!isset($list[0])){
		return [];
	}
	$rt = [];
	if(is_array($value)){
		foreach($list as $v){
			$rt[$v[$key]] = [];
			foreach($value as $v2){
				$rt[$v[$key]][$v2] = $v[$v2];
			}
		}
	} else{
		foreach($list as $v){
			$rt[$v[$key]] = $v[$value];
		}
	}
	if(is_callable($value_callback)){
		$rt = array_map($value_callback, $rt);
	}
	return $rt;
}

/**
 * 获取域名的最顶级
 * @param int $top_length 取域名的长度
 * @return string
 */
function get_top_domain($top_length = 2){
	$domain = u()->getUriInfo()->getHttpHost();
	if(preg_match("/^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$/", $domain) == 1){
		//匹配IP直接返回
		return $domain;
	}
	$p = [];
	for($i = 0; $i < $top_length; $i++){
		$p[] = "[a-zA-Z0-9-]+";
	}
	if($top_length < 1){
		//错误的域名
		return $domain;
	}
	if(preg_match("/" . implode("\\.", $p) . "$/", $domain, $match) == 1){
		return $match[0];
	}
	return $domain;
}

/**
 * 将数据列表转为KeyMap，保留键名
 * @param array        $list
 * @param string|array $value
 * @param callback     $value_callback
 * @return array
 */
function list2keymapSK($list, $value, $value_callback = NULL){
	if(!is_array($list)){
		return [];
	}
	$rt = [];
	if(is_array($value)){
		foreach($list as $key => $v){
			$rt[$key] = [];
			foreach($value as $v2){
				$rt[$key][$v2] = $v[$v2];
			}
		}
	} else{
		foreach($list as $key => $v){
			$rt[$key] = $v[$value];
		}
	}
	if(is_callable($value_callback)){
		$rt = array_map($value_callback, $rt);
	}
	return $rt;
}

/**
 * 可阅读的文件大小
 * @param int $size
 * @return bool|float
 */
function file_h_size($size){
	$a = [
		"B",
		"KB",
		"MB",
		"GB",
		"TB",
		"PB",
		"EB",
		"ZB",
		"YB"
	];
	$pos = 0;
	if($size < 0){
		return false;
	}
	while($size > 1024){
		$size /= 1024;
		$pos++;
	}
	return round($size, 2) . $a[$pos];
}

/**
 * 头文件输出钩子
 */
function header_hook(){
	hook()->apply("header_hook", NULL);
}

/**
 * 页脚文件输出钩子
 */
function footer_hook(){
	hook()->apply("footer_hook", NULL);
}


/**
 * 生成一个js引入连接
 * @param array $list 传入名称列表
 * @return string
 */
function html_js($list){
	if(!isset($list['type'])){
		$list['type'] = 'text/javascript';
	}
	$d = "";
	foreach($list as $n => $v){
		$d .= " " . $n . '="' . $v . '"';
	}
	return "<script$d></script>";
}

/**
 * 生成css引入连接
 * @param array|string $list 传入名称列表
 * @return string
 */
function html_css($list){
	if(!is_array($list)){
		$r = $list;
		$list = [];
		$list['href'] = $r;
	}
	if(!isset($list['rel'])){
		$list['rel'] = 'stylesheet';
	}
	if(!isset($list['type'])){
		$list['type'] = 'text/css';
	}
	return html_link($list);
}

/**
 * 生成引入连接
 * @param array $list
 * @return string
 */
function html_link($list){
	$d = "";
	foreach($list as $n => $v){
		$d .= " " . $n . '="' . $v . '"';
	}
	return "<link$d />";
}

/**
 * 生成标签
 * @param array $list
 * @return string
 */
function html_meta($list){
	$d = "";
	foreach($list as $n => $v){
		$d .= " " . $n . '="' . $v . '"';
	}
	return "<meta$d />";
}

/**
 * 获取资源文件路径
 * @param string $file
 * @param string $cache_code
 * @return string
 */
function get_asset($file, $cache_code = ''){
	return get_file_url([
		'asset',
		$file
	]) . ((!empty($cache_code) && is_string($cache_code)) ? "?_v=" . $cache_code : "");
}

/**
 * 过滤网址
 * @param string $url
 * @return string
 */
function filter_url($url){
	foreach(['/^javascript:/'] as $v){
		$x = preg_match($v, $url);
		if($x){
			return "#";
		}
	}
	if(!filter_var($url, FILTER_VALIDATE_URL)){
		return htmlspecialchars($url);
	}
	return $url;
}

/**
 * 生成OPTION数据
 * @param array  $value_list
 * @param string $select
 * @return string
 */
function html_option($value_list, $select){
	$rt = "";
	foreach($value_list as $value => $name){
		$rt .= "<option value=\"{$value}\"" . ($value == $select ? " selected" : "") . ">{$name}</option>";
	}
	return $rt;
}

/**
 * 返回数据库的查询次数
 * @return int
 */
function get_db_query_count(){
	/**
	 * @var $db \ULib\Db
	 */
	$db = lib()->using('UDB');
	if($db === false){
		return 0;
	} else{
		return $db->getDriver()->get_query_count();
	}
}

/**
 * 获取控制域名的列表
 * @param string $index
 * @return array
 */
function get_url_map($index = ''){
	static $cfg = NULL;
	if($cfg === NULL){
		$cfg = [
			'root' => '',
			//主域名,如changda.club,不设置取一个顶级域名
			'home' => '%ROOT%',
			//当前首页的域名
			'api' => '%ROOT%/Api',
			//API的域名地址
			'my' => 'my.%ROOT%'
			//用户中心的域名
		];
		$cfg2 = cfg()->get('url_map_host');
		$cfg = array_merge($cfg, is_array($cfg2) ? $cfg2 : []);
		if(empty($cfg['root'])){
			if(defined('_HOST_ROOT_')){
				$cfg['root'] = _HOST_ROOT_;
			} else{
				$cfg['root'] = u()->getUriInfo()->getHttpHost();
			}
		}
		foreach(array_keys($cfg) as $v){
			$cfg[$v] = str_replace("%ROOT%", $cfg['root'], $cfg[$v]);
		}
		foreach(array_keys($cfg) as $v){
			$cfg[$v] = (is_ssl() ? "https://" : "http://") . preg_replace("/[\\/]{1,}/", "/", $cfg[$v] . "/");
		}
	}
	if(!empty($index)){
		return isset($cfg[$index]) ? $cfg[$index] : "";
	}
	return $cfg;
}

/**
 * 判断是否允许表单登录
 * @return bool
 */
function allow_form_login(){
	$cfg = cfg()->get('register', 'login_form');
	return in_array($cfg, [
		'open',
		1,
		true
	]);
}

/**
 * 是否允许注册
 * @return bool
 */
function allow_register(){
	$cfg = cfg()->get('register', 'status');
	return in_array($cfg, [
		'open',
		1,
		true
	]);
}

/**
 * 获取网站标题
 * @return string
 */
function site_title(){
	return "阅后即隐";
}

/**
 * 获取网站描述
 * @return string
 */
function site_desc(){
	return "分享一小会";
}