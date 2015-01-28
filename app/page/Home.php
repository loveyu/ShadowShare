<?php
namespace UView;

use Core\Page;

class Home extends Page{

	function __construct(){
		parent::__construct();
	}

	/**
	 * Home page
	 */
	public function main(){
		echo URL_NOW;
	}

	/**
	 * 404
	 */
	public function not_found(){
		send_http_status(404);
		echo "This is 404 page!";
	}
}