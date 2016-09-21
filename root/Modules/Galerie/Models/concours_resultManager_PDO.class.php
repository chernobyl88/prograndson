<?php

namespace Modules\Galerie\Models;

if (!defined("EVE_APP"))
	exit();

class concours_resultManager_PDO extends \Library\Manager_PDO implements concours_resultManager {
	public function getListLastConcoursWinnerId($pNum = 1) {
		if (!is_numeric($pNum))
			return array();
		$sql = "
				SELECT
					DISTINCT(" . $this->getShortName() . ".id), " . $this->fullSelect() . ", gm.date_result
				FROM
					galerie_concours_result " . $this->getShortName() . "
				INNER JOIN
					galerie_main gm
				ON
					gm.id = " . $this->getShortName() . ".galerie_main_id
				WHERE
					gm.concours = 1
				AND
					gm.show_result = 1
				AND
					" . $this->getShortName() . ".rang = 1
				ORDER BY
					gm.date_result DESC
				LIMIT 0, :pNum
				;";
							
		$query = $this->dao()->prepare($sql);
		
		$query->bindValue(":pNum", $pNum, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		return $query->fetchAll();
	}
	
}

?>