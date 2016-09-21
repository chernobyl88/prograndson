<?php

namespace Modules\Document\Entities;

if (!defined("EVE_APP"))
	exit();

class access extends \Library\Entity {
	protected $id;
	protected $groupe_id;
	protected $file_id;
}

?>