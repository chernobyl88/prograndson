<?php

namespace Modules\Galerie\Models;

if (!defined("EVE_APP"))
	exit();

interface concours_resultManager {
	function getListLastConcoursWinnerId($pNum = 1);
}

?>