<?php

namespace Library\Models;

if (!defined("EVE_APP"))
	exit();

use Library\Manager;

interface groupeManager {
	public function getFromConst($pCst);
}

?>