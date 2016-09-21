<?php

namespace Modules\Presentation\Models;

if (!defined("EVE_APP"))
	exit();

class mainManager_PDO extends \Library\Manager_PDO implements mainManager {
	
	public function getListForUser($pId) {
		
		$query = $this->dao()->prepare("
				SELECT
					DISTINCT(".$this->getShortName().".id), " . $this->fullSelect() . "
				FROM
					presentation_main " . $this->getShortName() . "
				INNER JOIN
					presentation_access a
				ON
					a.presentation_main_id = " . $this->getShortName() . ".id
				INNER JOIN
					groupe g
				ON
					g.id = a.groupe_id
				INNER JOIN
					user_groupe ug
				ON
					ug.groupe_id = g.id
				WHERE
					ug.user_id = :pId
				AND
					(
						" . $this->getShortName() . ".`type` = 0
					OR
						" . $this->getShortName() . ".`type` = 1
					)
				AND
					" . $this->getShortName() . ".deleted = 0
				");
		
		$query->bindValue(":pId", $pId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		return $query->fetchAll();
	}
	
	public function addToCategorie($mainId, $catId) {
		if (!(is_numeric($mainId) && is_numeric($catId)))
			return -1;
		
		$query = $this->dao()->prepare("
				INSERT INTO
					presentation_categorie_main (id, presentation_main_id, presentation_categorie_id)
				VALUES (
					NULL,
					:pMainId,
					:pCatId
				);");

		$query->bindValue(":pMainId", $mainId, \PDO::PARAM_INT);
		$query->bindValue(":pCatId", $catId, \PDO::PARAM_INT);
		
		$query->execute();
		
		return 1;
	}
	
	public function deleteFromCategorie($mainId, $catId = null) {
		if (!(is_numeric($mainId)))
			return -1;
		
		$query = $this->dao()->prepare("
				DELETE FROM
					presentation_categorie_main
				WHERE
					presentation_main_id = :pId
				" . (($catId != null) ? "AND " . ((is_numeric($catId) ? "presentation_categorie_id = " . $catId : ((is_array($catId)) ? "presentation_categorie_id IN (" . implode(", ", $catId) . ")" : "TRUE"))) : "") . "
				;");

		$query->bindValue(":pId", $mainId, \PDO::PARAM_INT);
		
		$query->execute();
		
		return 1;
	}
	
	public function search($pSearch, $pListeWeight = array(), $pLength = 5, $pListeType = array()) {
		$checkedListe = array_filter($pListeType, function ($a) {return is_numeric($a);});
		
		$search = array_filter(explode(" ", $pSearch), function($a) {
			return strlen($a) >= 3;
		});
		
// 		$search = array($pSearch);
		
		$keys = array_keys($search);
		
		if (strlen($pSearch) == 0 || count($search) == 0) {
			$sql = "SELECT
					" . $this->fullSelect() . ",
					pcm.presentation_categorie_id AS categorie_id
				FROM
					presentation_main " . $this->getShortName() . "
				LEFT JOIN
					presentation_categorie_main pcm
				ON
					" . $this->getShortName() . ".id = pcm.presentation_main_id";
					if (count($checkedListe)) {
						$sql .= "
								AND
						" . $this->getShortName() . ".`type` IN (" . implode(", ", $checkedListe) . ")
								";
					}
					
				$sql .= "WHERE
							" . $this->getShortName() . ".deleted = 0
						GROUP BY
					id 
				;";
							
			$query = $this->dao()->prepare($sql);
			
			if ($pLength > 0)
				$query->bindValue(":pLength", $pLength, \PDO::PARAM_INT);
			
		} else {
			$that = $this;
			$needUniqueName = (count($pListeWeight) == 1);
			
			$sql = "SELECT
						" . $this->fullSelect() . ",
						" . ((count($pListeWeight)) ? "t1.f_weight" : "0") . " AS f_weight,
						pcm.presentation_categorie_id AS categorie_id
					FROM
						presentation_main " . $this->getShortName() . "
					" . ((count($pListeWeight)) ?"INNER JOIN
						(SELECT id, SUM(weight) AS f_weight FROM (
							" . implode(" UNION ALL ", array_map(function ($args) use ($that, $needUniqueName, $keys) {
								$tName = "t" . rand();
								if (!(key_exists("type", $args) && key_exists("weight", $args) && is_numeric($args["weight"]) && ($args["type"] == "main" || $args["type"] == "cate" || key_exists("name", $args))))
									return "(SELECT 0 AS id, 0 AS weight) " . (($needUniqueName) ? $tName : "");
								
								switch ($args["type"]) {
									case "main":
										
										return "(
												SELECT
													pm.id AS id,
													(COUNT(*) * " . $args["weight"] . ") AS weight
												FROM
													presentation_main pm
												WHERE
													" . implode(" OR ", array_map(function ($k) use ($args) { return " pm.nom  LIKE :pPartSearch" . $k;}, $keys)) . "
												GROUP BY
													id
												) " . (($needUniqueName) ? $tName : "");
										break;
									case "txt":
										return implode(" UNION ALL ", array_map(function ($k) use ($args, $tName) { return "(
												SELECT
													id,
													(SUM(w) * " . $args["weight"] . ")  AS weight
												FROM
													(SELECT
														pi.presentation_main_id AS id,
														ROUND (
															(
																LENGTH(pt.val)
																- LENGTH( REPLACE ( LOWER(pt.val), :pSearch" . $k . ", '') )
															) / LENGTH(:pSearch" . $k . ")
														) AS  w
													FROM
														presentation_texte pt
													INNER JOIN
														presentation_item pi
													ON
														pt.id = pi.val
													WHERE
														pi.item = 'text'
													AND
														pi.name = '" . \Utils::protect($args["name"]) . "'
													) " . $tName . "
													GROUP BY
														id
												) ";}, $keys)) . (($needUniqueName && count($search) == 1) ? "t" . rand() : "");
										break;
									case "item":
										return implode(" UNION ALL ", array_map(function ($k) use ($args, $tName) { return "(
												SELECT
													id,
													(SUM(w) * " . $args["weight"] . ") AS weight
												FROM
													(SELECT
														pi.presentation_main_id AS id,
														ROUND (
															(
																LENGTH(pi.val)
																- LENGTH( REPLACE ( LOWER(pi.val), :pSearch".$k.", '') )
															) / LENGTH(:pSearch".$k.")
														) AS  w
													FROM
														presentation_item pi
													WHERE
														pi.name = '" . $args["name"] . "'
													) " . $tName . "
													GROUP BY
														id
												) ";}, $keys)) . (($needUniqueName && count($search) == 1) ? "t".rand() : "");
									case "cate":
										return "(
												SELECT
													pcm.presentation_main_id AS id,
													" . $args["weight"] . " AS weight
												FROM
													presentation_categorie_main pcm
												INNER JOIN
													presentation_categorie pc
												ON
													pc.id = pcm.presentation_categorie_id
												WHERE
													" . implode(" OR ", array_map(function ($k) use ($args) { return " pc.default_name  LIKE :pPartSearch" . $k;}, $keys)) . "
												GROUP BY
													id
												) " . (($needUniqueName) ? $tName : "");
									default:
										return "(SELECT 0 AS id, 0 AS weight) " . $tName;
								}
							}, $pListeWeight)) . "
						) " . ((!$needUniqueName) ? "t".rand() : "") . "
						GROUP BY id) t1
					ON
						" . $this->getShortName() . ".id = t1.id":"") . "
					LEFT JOIN
						presentation_categorie_main pcm
					ON
						" . $this->getShortName() . ".id = pcm.presentation_main_id
					WHERE
						f_weight > 0
					AND
						" . $this->getShortName() . ".published = 1
					AND
						" . $this->getShortName() . ".deleted = 0";
					if (count($checkedListe)) {
						$sql .= "
								AND
						" . $this->getShortName() . ".`type` IN (" . implode(", ", $checkedListe) . ")
								";
					}
					
				$sql .= "GROUP BY
						id 
					ORDER BY
						f_weight DESC,
						id ASC
					" . (($pLength > 0) ? "LIMIT 0, :pLength" : "") . 
					";";
				
			$query = $this->dao()->prepare($sql);
			
			if ($pLength > 0)
				$query->bindValue(":pLength", $pLength, \PDO::PARAM_INT);
			
			foreach ($search AS $k => $s) {
				$query->bindValue(":pSearch" . $k, strtolower($s), \PDO::PARAM_STR);
				$query->bindValue(":pPartSearch" . $k, "%".strtolower($s)."%", \PDO::PARAM_STR);
			}
		}
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		return $query->fetchAll();
	}
	
	public function getListDated($pType, $published = true) {
		$query = $this->dao()->prepare("
				SELECT
					DISTINCT(" . $this->getShortName() . ".id), 
					" . $this->fullSelect() . ",
					t.val
				FROM
					presentation_main " . $this->getShortName() . "
				LEFT JOIN
					(
					SELECT
						pi.presentation_main_id AS presentation_main_id,
						pd.val AS val
					FROM
						presentation_item pi
					LEFT JOIN
						presentation_date pd
					ON
						pi.val = pd.id
					WHERE
						pi.name = 'date_event'
					AND
						pi.item = 'date'
					) t
				ON
					" . $this->getShortName() . ".id = t.presentation_main_id
				WHERE
					" . $this->getShortName() . ".`type` = :pType
				" . (($published) ?  " AND " . $this->getShortName() . ".published = 1" : "") . "
				AND
					" . $this->getShortName() . ".deleted = 0
				ORDER BY
					t.val ASC,
					" . $this->getShortName() . ".id ASC
				;");
		
		$query->bindValue(":pType", $pType, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		return $query->fetchAll();
	}
	
	public function getListOfCommerce($pUserId) {
		if (!is_numeric($pUserId) || $pUserId < 1)
			return array();
		
		$query = $this->dao()->prepare("
				SELECT
					DISTINCT(" . $this->getShortName() . ".id),
					" . $this->fullSelect() . "
				FROM
					presentation_main " . $this->getShortName() . "
				INNER JOIN
					presentation_access pa
				ON
					pa.presentation_main_id = " . $this->getShortName() . ".id
				INNER JOIN
					groupe g
				ON
					g.id = pa.groupe_id
				INNER JOIN
					user_groupe ug
				ON
					ug.groupe_id = g.id
				WHERE
					ug.user_id = :pId
				AND
					" . $this->getShortName() . ".type IN (0, 1)
				AND
					" . $this->getShortName() . ".deleted = 0
				");
		
		$query->bindValue(":pId", $pUserId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		return $query->fetchAll();
	}
	
	public function getListeUserForPres($pId) {
		if (!is_numeric($pId) || $pId < 1)
			return array();
		
		$query = $this->dao()->prepare("
				SELECT
					DISTINCT(ug.user_id) AS id
				FROM
					presentation_access pa
				INNER JOIN
					user_groupe ug
				ON
					ug.groupe_id = pa.groupe_id
				INNER JOIN
					presentation_main pm
				ON
					pm.id = pa.presentation_main_id
				WHERE
					pa.presentation_main_id = :pId
				AND
					pm.deleted = 0
				;");
		
		$query->bindValue(":pId", $pId, \PDO::PARAM_INT);
		
		$query->execute();
		
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
}

?>