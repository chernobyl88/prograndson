<?php

namespace Library\Models;

if (!defined("EVE_APP"))
	exit();

interface dynamic_pageManager {
	
	public function getDynamicFromRoute($pRoute_id);
}

?>