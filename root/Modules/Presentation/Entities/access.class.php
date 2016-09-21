<?php

namespace Modules\Presentation\Entities;

if (!defined("EVE_APP"))
	exit();

class access extends \Library\Entity {
	protected $id;
	protected $groupe_id;
	protected $presentation_main_id;

	CONST INVALID_ID = 1;
	CONST INVALID_GROUPE_ID = 2;
	CONST INVALID_PRESENTATION_MAIN_ID = 3;
}

?>