<?php

namespace Modules\Presentation\Models;

if (!defined("EVE_APP"))
	exit();

class categorieManager_PDO extends \Library\Manager_PDO implements categorieManager {
	function getListFromMain($mainId) {
		if (!is_numeric($mainId))
			return array();
		
		$query = $this->dao()->prepare("
				SELECT
					" . $this->fullSelect() . "
				FROM
					presentation_categorie AS " . $this->getShortName() . "
				INNER JOIN
					presentation_categorie_main pcm
				ON
					pcm.presentation_categorie_id = " . $this->getShortName() . ".id
				WHERE
					pcm.presentation_main_id = :pMainId
				");
		
		$query->bindValue(":pMainId", $mainId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		return $query->fetchAll();
	}
}

?>