<?php

namespace Modules\Presentation\Models;

if (!defined("EVE_APP"))
	exit();

interface categorieManager {
	function getListFromMain($mainId);
}

?>