<?php
/**
 * User: loveyu
 * Date: 2015/2/13
 * Time: 22:56
 */

namespace ULib\Share;

use ULib\Share;

class ShareUrl extends Share{

	public function __construct(){
		$this->share_type = self::TYPE_URL;
		parent::__construct();
	}
}