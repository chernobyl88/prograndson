<?php

namespace Modules\Presentation\Entities;

if (!defined("EVE_APP"))
	exit();

class sended_mail extends \Library\Entity {
	protected $id;
	protected $date_sent;
	protected $ip;
	protected $presentation_main_id;
	protected $used_mail;

	CONST INVALID_ID = 1;
	CONST INVALID_GROUPE_ID = 2;
	CONST INVALID_PRESENTATION_MAIN_ID = 3;
}

?>