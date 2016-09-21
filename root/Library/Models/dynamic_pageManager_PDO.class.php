<?php

namespace Library\Models;

if (!defined("EVE_APP"))
	exit();

class dynamic_pageManager_PDO extends \Library\Manager_PDO implements dynamic_pageManager {
	
	public function getDynamicFromRoute($pRoute_id) {
		if (!is_numeric($pRoute_id))
			throw new \InvalidArgumentException();
		
		$requete = $this->dao()->prepare("
				SELECT
					id,
					date_add,
					date_modif,
					page_content,
					date_end,
					visible,
					routes_id
				FROM
					dynamic_page
				WHERE
					routes_id = :pId
				ORDER BY
					date_modif DESC
				LIMIT 0, 1
				;");
		


		$requete->bindValue(":pId", $pRoute_id, \PDO::PARAM_INT);
		
		$requete->execute();
		
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Dynamic_page');
		
		$ret = $requete->fetch();
		
		if ($ret instanceof \Library\Dynamic_page)
			return $ret;
		else
			return new \Library\Dynamic_page();
	}
	
	/*
	public function update(\Library\Entity $pEntity) {
		return false;
	}
	
	public function insert(\Library\Entity $pEntity) {
		return false;
	}
	
	public function delete($pId) {
		return false;
	}
	
	public function deleteList(array $cond = array(), array $param = array()) {
		return false;
	}//*/
	
}

?>