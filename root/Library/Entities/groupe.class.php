<?php

namespace Library\Entities;

if (!defined("EVE_APP"))
	exit();

use Library\Entity;

class groupe extends \Library\Entity {
	protected $id;
	protected $txt_cst;
	protected $def_val;
	protected $parent_id;
}

?>