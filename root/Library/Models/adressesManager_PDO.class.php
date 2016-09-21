<?php

namespace Library\Models;

if (!defined("EVE_APP"))
	exit();

use Library\Models\AdresseManager;

class adressesManager_PDO extends \Library\Manager_PDO implements adressesManager {
	
	public function getAdresseForUser($pId) {
		$query = $this->dao->prepare('
				SELECT
					id,
					user_id,
					entreprise,
					adresse,
					case_postale,
					localite
				FROM
					adresses
				WHERE
					(
						user_id = :user_id
					OR
						user_id IN (
								SELECT
									id
								FROM
									user
								WHERE
									reference_user = :user_id
								)
					)
				');
		
		$query->bindValue(":user_id", $pId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Adresse');
		
		return $query->fetchAll();
	}
	
	public function allowedAdresse($userId, $adresseId) {
		$query = $this->dao->prepare("
				SELECT
					COUNT(*) AS test
				FROM
					adresses
				WHERE (
						user_id = :user_id
					OR
						user_id IN (
								SELECT
									id
								FROM
									user
								WHERE
									reference_user = :user_id
								)
					)
				AND
					id = :adresse_id
				;");

		$query->bindValue(":user_id", $userId, \PDO::PARAM_INT);
		$query->bindValue(":adresse_id", $adresseId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$adresse = $query->fetch(\PDO::FETCH_ASSOC);
		
		return $adresse["test"] >= 1;
	}
	
	public function getFirstAdresse($pId) {
		$query = $this->dao->prepare('
				SELECT
					id,
					user_id,
					entreprise,
					adresse,
					case_postale,
					localite
				FROM
					adresses
				WHERE
					user_id = :id
				');
		
		$query->bindValue(":id", $pId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\Adresse');
		
		$adresse = $query->fetch();
		
		if ($adresse != null && $adresse instanceof \Library\Entities\Adresse) {
			return $adresse;
		} else {
			return new \Library\Entities\Adresse();
		}
	}
}

?>