<?php
/**
 * User: loveyu
 * Date: 2015/1/6
 * Time: 19:00
 */

namespace ULib;


use CLib\Router;

class Hook{
	private $hook;

	function __construct(){
		$this->hook = hook();
	}

	public function add(){
		$this->router();
		l_h('system.php', 'ref_class.php');
		lib()->load('Page', 'RestApi');
	}

	private function router(){
		c_lib()->load('router');
		$router = new Router();
		$this->hook->add('UriInfo_process', [
			$router,
			'result'
		]);
		switch($_SERVER['HTTP_HOST']){
			case "changda.club":
			case "cd.loc":
				$router->add_preg("|^([0-9A-Za-z]{4,})$|", 'Home/share/[1]');
				$router->add_preg("|^add/([a-z-]{3,})$|", 'Home/add/[1]');
				break;
			case "my.cd.loc":
			case "my.changda.club":
				$router->add_preg("|^list/([a-z-]{3,})$|", 'MyList/select/[1]');
				break;
		}
	}
} 