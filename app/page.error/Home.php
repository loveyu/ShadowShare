<?php
/**
 * User: loveyu
 * Date: 2015/2/18
 * Time: 10:09
 */

namespace UView;

use ULib\Page;

class Home extends Page{

	function __construct(){
		parent::__construct();
	}

	public function not_found(){
		send_http_status(404);
		$this->__view("home/404.php");
	}
}