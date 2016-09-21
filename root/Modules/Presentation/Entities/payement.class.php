<?php

namespace Modules\Presentation\Entities;

if (!defined("EVE_APP"))
	exit();

class payement extends \Library\Entity {
	protected $id;
	protected $date_payement;
	protected $presentation_main_id;
	
	const INVALID_ID = 1;
	const INVALID_DATE_PAYEMENT = 2;
	const INVALID_PRESENTATION_MAIN_ID = 3;
}

?>