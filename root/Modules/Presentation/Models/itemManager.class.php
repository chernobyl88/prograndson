<?php

namespace Modules\Presentation\Models;

if (!defined("EVE_APP"))
	exit();

interface itemManager {
	function getFromPres($pId, \Modules\Presentation\Models\texteManager $textManager);
	function getFromList($pId, \Modules\Presentation\Models\texteManager $textManager);
	function getLastText($pres_id, $side);
	function addToCategorie($mainId, $catId);
}

?>