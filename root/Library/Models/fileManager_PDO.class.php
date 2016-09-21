<?php

namespace Library\Models;

if (!defined("EVE_APP"))
	exit();

class fileManager_PDO extends \Library\Manager_PDO implements fileManager {
	public function getListFromUser($pId, $admin) {
		$sql = 
		
				"
				SELECT
					DISTINCT(" . $this->getShortName() . ".id),
					" . $this->fullSelect() . "
				FROM
					file " . $this->getShortName() . "
				INNER JOIN
					document_access da
				ON
					da.file_id = " . $this->getShortName() . ".id
				";
				if (!$admin)
					$sql .= "INNER JOIN
						user_groupe ug
					ON
						ug.groupe_id = da.groupe_id
					INNER JOIN
						user u
					ON
						u.id = ug.user_id
					WHERE
						u.id = :pId
					OR
						u.admin >= :pAdmin
					";
		$sql .= "ORDER BY da.groupe_id;";
		$query = $this->dao()->prepare($sql);
		$query->bindValue(":pId", $pId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		return $query->fetchAll();
	}
}

?>