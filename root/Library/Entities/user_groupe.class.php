<?php

namespace Library\Entities;

if (!defined("EVE_APP"))
	exit();

class user_groupe extends \Library\Entity {
	protected $id;
	protected $groupe_id;
	protected $user_id;

	const INVALID_ID = 1;
	const INVALID_GROUPE_ID = 1;
	const INVALID_USER_ID = 1;
}

?>