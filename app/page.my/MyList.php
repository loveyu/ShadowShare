<?php
/**
 * User: loveyu
 * Date: 2015/3/17
 * Time: 17:12
 */

namespace UView;


use ULib\Page;
use ULib\ShareList;

class MyList extends Page{
	private $member;

	function __construct(){
		$this->header_view_file = "common/my_header.php";
		$this->footer_view_file = "common/my_footer.php";
		parent::__construct();
		$this->member = class_member();
	}

	public function select($type = NULL){
		if(!$this->member->getLoginStatus()){
			redirect([
				'Home',
				'login'
			], 'refresh', 302, false);
			return;
		}
		$this->__lib->load('ShareList');
		$sl = new ShareList($this->member->getUid());
		$type = $sl->checkTypeName($type, $name);
		if($type === NULL){
			//检查失败
			$this->__load_404();
			return;
		}
		$data = $sl->select($type);
		if(empty($data)){
			$this->__load_404();
		} else{
			$this->__view("member/list.php", [
				'data' => $data,
				'name' => $name
			]);
		}
	}
}