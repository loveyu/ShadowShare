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
	return md5($str."\xFF\xFF");
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

