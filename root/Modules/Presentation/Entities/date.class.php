<?php

namespace Modules\Presentation\Entities;

if (!defined("EVE_APP"))
	exit();

class date extends \Library\Entity {
	protected $id;
	protected $val;
	
	const INVALID_ID = 1;
	const INVALID_VAL = 2;
}

?>