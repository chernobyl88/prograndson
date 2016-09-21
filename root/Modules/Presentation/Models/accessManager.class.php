<?php

namespace Modules\Presentation\Models;

if (!defined("EVE_APP"))
	exit();

interface accessManager {
	function getGroupeIdFromPres($pId);
}

?>