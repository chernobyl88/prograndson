<?php

namespace Modules\Presentation\Models;

if (!defined("EVE_APP"))
	exit();

interface mainManager {
	function getListForUser($pId);
	function addToCategorie($mainId, $catId);
	function search($pSearch, $pListeWeight = array(), $pLength = 5, $pListeType = array());
	function getListDated($pType);
}

?>