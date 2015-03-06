<?php
if(PHP_VERSION < 5.4){
	die(_("PHP Version must be greater than 5.4"));
}

//设置时区
date_default_timezone_set("PRC");

/**
 * 定义唯一名称
 * 当修改语言标示后需要修改才名称
 */
define("_AppName_", "Yin");

/**
 * 调试模式，基本未使用
 */
define('_Debug_', true);

/**
 * 转义是否开启标志
 */
define('MAGIC_QUOTES_GPC', PHP_VERSION < 6 && get_magic_quotes_gpc());

/**
 * 路由器分割字符
 */
define('ROUTER_SPLIT_CHAR', '/');

/**
 * COOKIE加密密钥
 */
define('COOKIE_KEY', 'y[-^ZCIy1&KQ=]gUDYWev@vW[#;vRN@o+q{?1Ae}p58A#\'nHe~');

/**
 * COOKIE前缀
 */
define('COOKIE_PREFIX', 'cd_');

/**
 * 系统根目录，对应着包含sys,app,install的目录，需要修改时可以调整该目录参数，一般调整整个系统文件结构，无需改变任何内容
 */
define("_RootPath_", dirname(__DIR__));

/**
 * 系统路径
 */
define("_SysPath_", __DIR__);

/**
 * 基本路径
 */
define("_BasePath_", _RootPath_ . "/web");

/**
 * App应用文件路径
 */
define("_AppPath_", _RootPath_ . "/app");
/**
 * 核心路径
 */
define("_CorePath_", _SysPath_ . "/core");

/**
 * 日志文件路径
 */
define("_LogPath_", _AppPath_ . "/log");

/**
 * 语言文件包路径
 */
define("_Language_", _AppPath_ . "/language");

/**
 * 缓存目录
 */
define("_Cache_", _AppPath_ . "/cache");

if(isset($_SERVER['HTTP_HOST'])){
	switch(strtolower($_SERVER['HTTP_HOST'])){
		case "my.changda.club":
		case "my.cd.loc":
			$page_path = "/page.my";
			break;
		case "changda.club":
		case "cd.loc":
			$page_path = "/page";
			break;
		default:
			$page_path = "/page.error";
			break;
	}
} else{
	$page_path = "/page";
}
/**
 * 页面路径
 */
define("_PagePath_", _AppPath_ . $page_path);
unset($page_path);

/**
 * 功能函数路径
 */
define("_HelperPath_", _AppPath_ . "/helper");

/**
 * 视图路径
 */
define("_ViewPath_", _AppPath_ . "/view");

/**
 * 类库路径
 */
define("_LibPath_", _AppPath_ . "/lib");

/**
 * 文件存储相对目录
 */
define("_DATA_FILE_", "/data/file");

//设置运行错误信息
if(_Debug_){
	ini_set('display_errors', 'on');
	error_reporting(E_ALL | E_STRICT);
} else{
	ini_set('display_errors', 'off');
	error_reporting(0);
}

//加载接口文件
require(_CorePath_ . "/interface.php");