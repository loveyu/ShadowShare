<?php
/**
 * User: loveyu
 * Date: 2015/2/26
 * Time: 16:28
 */

namespace UView;


use ULib\Markdown\Parsedown;
use ULib\RestApi;
use ULib\Xss\XssHtml;

class Tool extends RestApi{
	public function __construct(){
		parent::__construct();
	}

	public function parse_markdown(){
		$data = $this->__req->post('data');
		if($data === NULL){
			$this->_set_status(false, 4001, "数据提交异常");
			return;
		}
		$this->__lib->load('Markdown/Parsedown', 'Xss/XssHtml');
		$parse = new Parsedown();
		$xss = new XssHtml($parse->text($data));
		$this->_set_status(true, 0);
		$this->_set_data($xss->getHtml());
	}
}