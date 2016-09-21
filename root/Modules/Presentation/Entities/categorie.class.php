<?php

namespace Modules\Presentation\Entities;

if (!defined("EVE_APP"))
	exit();

class categorie extends \Library\Entity {
	protected $id;
	protected $cst_var;
	protected $default_name;

	CONST INVALID_ID = 1;
}

?>