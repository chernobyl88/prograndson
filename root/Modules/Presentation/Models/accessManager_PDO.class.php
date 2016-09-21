<?php

namespace Modules\Presentation\Models;

if (!defined("EVE_APP"))
	exit();

class accessManager_PDO extends \Library\Manager_PDO implements accessManager {
	function getGroupeIdFromPres($pId) {
		if (!is_numeric($pId))
			throw new \InvalidArgumentException();
		
		$query = $this->dao()->prepare("
				SELECT
					groupe_id
				FROM
					presentation_access p1
				WHERE
					presentation_main_id = :pId
				AND
					(SELECT
						COUNT(*)
					FROM
						presentation_access p2
					WHERE
						p2.groupe_id = p1.groupe_id
					) < 2
				;");
		
		$query->bindValue(":pId", $pId, \PDO::PARAM_INT);
		$query->execute();
		
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
}

?>