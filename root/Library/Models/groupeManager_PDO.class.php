<?php

namespace Library\Models;

if (!defined("EVE_APP"))
	exit();

use Library\Models\userManager;

class groupeManager_PDO extends \Library\Manager_PDO implements userManager {
	
	public function getFromConst($pCst) {
		
		$ret = parent::getList(array("txt_cst = :pCst"), array(array("key" => ":pCst", "val" => $pCst, "type" => \PDO::PARAM_STR)));
		
		if (count($ret))
			return $ret[0];
		else
			return null;
		
	}
	
	public function getListFromUser($pId) {
		if (!is_numeric($pId) || $pId < 1)
			return array();
		
		$query = $this->dao()->prepare("
				SELECT
					DISTINCT(" . $this->getShortName() . ".id),
					" . $this->fullSelect() . "
				FROM
					groupe " . $this->getShortName() . "
				INNER JOIN
					user_groupe ug
				ON
					ug.groupe_id = " . $this->getShortName() . ".id
				WHERE
					ug.user_id = :pId
				");
		
		$query->bindValue(":pId", $pId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		return $query->fetchAll();
	}
}

?>