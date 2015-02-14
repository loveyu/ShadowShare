<?php
/**
 * User: loveyu
 * Date: 2015/2/13
 * Time: 22:55
 */

namespace ULib;


abstract class Share{
	const TYPE_TEXT = 0;
	const TYPE_FILE = 1;
	const TYPE_CODE = 2;
	const TYPE_MULTI_TEXT = 3;
	const TYPE_URL = 4;
	const TYPE_PICTURE = 5;
	const TYPE_MARKDOWN = 6;
	const TYPE_PICTURE_TEXT = 7;

	protected $share_type = NULL;

	public function __construct(){
		if(!is_int($this->share_type)){
			throw new \Exception("Share type is not define, please check you code.");
		}
	}


	public function create($uid){
//		var_dump($uid);
	}
}