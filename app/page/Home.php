<?php
namespace UView;

use ULib\Page;

class Home extends Page{

	function __construct(){
		parent::__construct();
	}

	/**
	 * Home page
	 */
	public function main(){
		$this->__view("home/index.php");
	}


	public function share($id = NULL){
		var_dump($id);
	}

	/**
	 * 404
	 */
	public function not_found(){
		send_http_status(404);
		echo "This is 404 page!";
	}
}