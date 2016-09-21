<?php

namespace Modules\Presentation\Models;

if (!defined("EVE_APP"))
	exit();

class itemManager_PDO extends \Library\Manager_PDO implements itemManager {
	protected $dateManager;
	
	public function setDateManager(\Modules\Presentation\Models\dateManager $dateManager) {
		$this->dateManager = $dateManager;
	}
	
	public function dateManager() {
		if (isset($this->dateManager))
			return $this->dateManager;
		
		throw new \RuntimeException();
	}
	
	public function getFromPres($pId, \Modules\Presentation\Models\texteManager $textManager) {
		if (!is_numeric($pId))
			return array();
		
		$listeItem = $this->getList(array("presentation_main_id = :pId", "liste_id IS NULL"), array(array("key" => ":pId", "val" => $pId, "type" => \PDO::PARAM_INT)));
		
		foreach ($listeItem AS $item) {
			if ($item instanceof \Modules\Presentation\Entities\item)
				switch ($item->item()) {
					case "text":
						$item->setTexte($textManager->get($item->key()));
						break;
					case "list":
						$item->setListe_elem($this->getFromList($item->id(), $textManager));
						break;
					case "date":
						$item->setDate($this->dateManager()->get($item->key()));
						break;
					case "img":
					case "elem":
					default:
				}
		}
		
		return $listeItem;
	}
	
	public function getFromList($pId, \Modules\Presentation\Models\texteManager $textManager) {
		if (!is_numeric($pId))
			return array();
		
		$listeItem = $this->getList(array("liste_id = :pId"), array(array("key" => ":pId", "val" => $pId, "type" => \PDO::PARAM_INT)));
	
		foreach ($listeItem AS $item) {
			if ($item instanceof \Modules\Presentation\Entities\item)
				switch ($item->item()) {
					case "text":
						$item->setTexte($textManager->get($item->key()));
						break;
					case "list":
						$item->setListe_elem($this->getFromList($item->id(), $textManager));
						break;
					case "date":
						$item->setDate($this->dateManager()->get($item->key()));
						break;
					case "img":
					case "elem":
					default:
			}
		}
		
		return $listeItem;
		
	}
	
	public function getLastText($pres_id, $side) {
		if (!is_numeric($pres_id))
			return null;
		
		$query = $this->dao()->prepare("
				SELECT
					" . $this->fullSelect() . "
				FROM
					presentation_item " . $this->getShortName() . "
				WHERE
					".$this->getShortName().".presentation_main_id = :pId
				AND
					".$this->getShortName().".name = :pSide
				AND
					".$this->getShortName().".item = 'list'
				AND
					".$this->getShortName().".id NOT IN (
						SELECT
							p1.id
						FROM
							presentation_item p1
						INNER JOIN
							presentation_item p2
						ON
							p1.id = p2.liste_id
						WHERE
							p1.presentation_main_id = :pId
						AND
							p2.item = 'list'
					)
				;");
		
		$query->bindValue(":pId", $pres_id, \PDO::PARAM_INT);
		$query->bindValue(":pSide", $side, \PDO::PARAM_STR);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		$listeText = $query->fetchAll();
		
		if (count($listeText) == 0)
			return new \Modules\Presentation\Entities\item(array("name" => $side));
		else
			return $listeText[count($listeText)-1];
	}
	
	public function getTimeSlot($slot_id) {
		if (!is_numeric($slot_id))
			throw new \InvalidArgumentException("ID Dans un format incorecte", 1);
		
		$query = $this->dao()->prepare("
				SELECT
					i2.id,
					CONCAT(i1.name, '_', i2.name) AS `name`
				FROM
					presentation_item i1
				INNER JOIN
					presentation_item i2
				ON
					i1.id = i2.liste_id
				WHERE
					i1.liste_id = :pId
				");
		$query->bindValue(":pId", $slot_id, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_OBJ);
		
		return $query->fetchAll();
	}
	
	public function delete($pId) {
		$listeSub = $this->getList(array("liste_id = :pId"), array(array("key" => ":pId", "val" => $pId, "type" => \PDO::PARAM_INT)));

		foreach ($listeSub AS $sub)
				$this->delete($sub->id());
		
		return parent::delete($pId);
	}
	
	public function addToCategorie($mainId, $catId) {
		if (!(is_numeric($mainId) && is_numeric($catId)))
			throw new \InvalidArgumentException("Invalid ID");
		
		$query = $this->dao()->prepare("
				INSERT INTO
					presentation_categorie_main (id, presentation_main_id, presentation_categorie_id)
				VALUES
					NULL,
					:pMainId,
					:pCatId
				;");

		$query->bindValue(":pMainId", $mainId, \PDO::PARAM_INT);
		$query->bindValue(":pCatId", $catId, \PDO::PARAM_INT);
		
		$query->execute();
		
		return $this->dao()->lastInsertId();
	}
	
	public function getLastList($mainId) {
		if (!is_numeric($mainId))
			throw new \InvalidArgumentException("Not numeric ID");
		
		$query = $this->dao()->prepare("
				SELECT
					" . $this->fullSelect() . "
				FROM
					presentation_item " . $this->getShortName() . "
				WHERE
					item = 'list'
				AND
					presentation_main_id = :pId
				AND
					(
						name = 'tail'
					OR
						name = 'list_information'
					)
				ORDER BY
					id DESC
				LIMIT 0, 1
				;");
		
		$query->bindValue(":pId", $mainId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->entity_name);
		
		$listeItem = $query->fetchAll();
		
		if (count($listeItem) == 0)
			return new \Modules\Presentation\Entities\item(array("presentation_main_id" => $mainId, "item" => "list"));
		else
			return $listeItem[0];
	}
}

?>