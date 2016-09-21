<?php

namespace Library\Models;

if (!defined("EVE_APP"))
	exit();

use Library\Models\userManager;

class userManager_PDO extends \Library\Manager_PDO implements userManager {
	
	public function getList(array $conditions = array(), array $param = array(), array $order = array(), $length = -1) {
		$list = parent::getList($conditions, $param);
		
		$me = $this;
		
		return array_map(function($arg) use ($me) {return $arg->setAttr($me->getUserAttribute($arg->id()));}, $list);
	}
	
	public function get($pId) {
		$user = parent::get($pId);
		
		if ($user instanceof \Library\Entities\user)
			$user->setAttr($this->getUserAttribute($pId));
		
		return $user;
	}
	
	public function getListSubUser() {
		return $this->getList(array("id NOT IN (SELECT reference_user FROM user)"));
	}

	public function getSubUser($pId) {
		return $this->getList(array("reference_user = :pId"), array(array("key" => ":pId", "val" => $pId, "type" => \PDO::PARAM_INT)));		
	}
	
	public function insert(\Library\Entity $pUser) {
		if (!$pUser instanceof \Library\Entities\user)
			return -3;
		
		$query = $this->dao->prepare("
										SELECT
											count(*) AS nbr
										FROM
											user
										WHERE
											login = :pLogin
									");
		
		$query->bindValue(":pLogin", $pUser->login());
		
		$query->execute();
		
		$info = $query->fetch(\PDO::FETCH_ASSOC);
		
		if ($info["nbr"] != 0) {
			return -1;
		}
		
		if ($ret = parent::insert($pUser)) {
			foreach ($pUser->getListeParam() AS $key => $param)
				$this->sendAttribute($pUser->id(), $key, $param);
			
			return 1;
		} else {
			return $ret;
		}
		
		
	}
	
	public function update(\Library\Entity $pUser) {
		$query = $this->dao->prepare("
										SELECT
											count(*) AS nbr
										FROM
											user
										WHERE
											login = :pLogin
										AND
											id != :pId
									");

		$query->bindValue(":pLogin", $pUser->login());
		$query->bindValue(":pId", $pUser->id(), \PDO::PARAM_INT);
		
		$query->execute();
		
		$info = $query->fetch(\PDO::FETCH_ASSOC);
		
		if ($info["nbr"] != 0) {
			return -1;
		}
		
		return parent::update($pUser);
	}
	
	public function getUserForOptionForUser($pId) {
		$query = $this->dao->prepare("
				SELECT
					id AS `KEY`,
					CONCAT(nom, ' ', prenom) AS `VALUE`
				FROM
					user
				WHERE
					id = :user_id
				OR
					reference_user = :user_id
				;");
		
		$query->bindValue(":user_id", $pId, \PDO::PARAM_INT);
		
		if ($query->execute()) {
			return $query->fetchAll(\PDO::FETCH_ASSOC);
		} else {
			return array();
		}
	}
	
	public function getUserFromGroup(array $listeGroup) {
		$sql = "SELECT login, email, civilite, prenom, nom FROM user u INNER JOIN user_in_groupe_struct gs ON gs.user_id = u.id WHERE gs.groupe_struct_id IN (" . implode(", ", array_map(function ($arg) {
				if ($arg instanceof \Library\Entities\groupe_struct)
					return $arg->id();
				else
					return -1;
			}, $listeGroup)) . ")";
			
		$query = $this->dao->prepare($sql);
		
		$query->execute();
		
		$query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Entities\user');
		
		return $query->fetchAll();
	}
	
	public function isInGroup($pUserId, $pGroupeId) {
		if (!(is_numeric($pUserId) && is_numeric($pGroupeId)))
			return false;
		
		$query = $this->dao->prepare("SELECT
										COUNT(*) AS nbr
									FROM
										user_groupe
									WHERE
										groupe_id = :pGroupeId
									AND
										user_id = :pUserId
									;");

		$query->bindValue(":pUserId", $pUserId, \PDO::PARAM_INT);
		$query->bindValue(":pGroupeId", $pGroupeId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$data = $query->fetch(\PDO::FETCH_OBJ);
		
		return $data->nbr > 0;
	}
	
	public function userHasGroupe($pUserId) {
		if (!(is_numeric($pUserId)))
			return false;
		
		$query = $this->dao->prepare("SELECT
										COUNT(*) AS nbr
									FROM
										user_groupe
									WHERE
										user_id = :pUserId
									;");

		$query->bindValue(":pUserId", $pUserId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$data = $query->fetch(\PDO::FETCH_OBJ);
		
		return $data->nbr > 0;
	}
	
	/**
	 * Check for unicity of group
	 * 
	 * @param int $pUserId
	 * @param int $pGroupeId
	 * @return int
	 */
	public function sendInOneGroup($pUserId, $pGroupeId) {
		if (!(is_numeric($pUserId) && is_numeric($pGroupeId)))
			return -1;
		
		if (!$this->userHasGroup($pUserId, $pGroupeId)) {
			return $this->updateGroupeUser($pUserId, $pGroupeId);
		} else {
			return $this->insertInGroup($pUserId, $pGroupeId);
		}
		
	}
	
	public function insertInGroup($pUserId, $pGroupeId) {
		if (!(is_numeric($pUserId) && is_numeric($pGroupeId)))
			return -1;
		
		if (!$this->isInGroup($pUserId, $pGroupeId)) {
			$query = $this->dao->prepare("
					INSERT INTO
						user_groupe (
							id, 
							groupe_id,
							user_id
						)
					VALUES
						(
						null,
						:pGroupeId,
						:pUserId
						)
					;");

			$query->bindValue(":pGroupeId", $pGroupeId, \PDO::PARAM_INT);
			$query->bindValue(":pUserId", $pUserId, \PDO::PARAM_INT);
			
			if (!$query->execute())
				return -1;
		}
		
		return 1;
	}
	
	public function removeInGroup($pUserId, $pGroupeId) {
		if (!(is_numeric($pUserId) && is_numeric($pGroupeId)))
			return -1;

		$query = $this->dao->prepare("
				DELETE FROM
					user_groupe
				WHERE
					groupe_id = :pGroupeId
				AND
					user_id = :pUserId
				;");

		$query->bindValue(":pGroupeId", $pGroupeId, \PDO::PARAM_INT);
		$query->bindValue(":pUserId", $pUserId, \PDO::PARAM_INT);
			
		$query->execute();
		return 1;
		
	}
	
	public function removeUserFromGroup($pUserId) {
		if (!(is_numeric($pUserId)))
			return -1;

		$query = $this->dao->prepare("
				DELETE FROM
					user_groupe
				WHERE
					user_id = :pUserId
				;");

		$query->bindValue(":pUserId", $pUserId, \PDO::PARAM_INT);
			
		$query->execute();
		return 1;
		
	}
	
	protected function updateGroupeUser($pUserId, $pGroupeId) {
		if (!(is_numeric($pUserId) && is_numeric($pGroupeId)))
			return -1;

		$query = $this->dao->prepare("
				UPDATE
					user_groupe
				SET
					groupe_id = :pGroupeId
				WHERE
					user_id = :pUserId
				;");

		$query->bindValue(":pGroupeId", $pGroupeId, \PDO::PARAM_INT);
		$query->bindValue(":pUserId", $pUserId, \PDO::PARAM_INT);
			
		$query->execute();
		return 1;
	}
	
	public function getAttribute($pId, $pAttr) {
		if (!(is_numeric($pId)))
			return "";
		
		$query = $this->dao->prepare("SELECT
					id,
					user_id,
					user_attr,
					user_val
				FROM
					user_attr
				WHERE
					user_id = :pUserId
				AND
					user_attr = :pUserAttr
				;");

		$query->bindValue(":pUserId", $pId, \PDO::PARAM_INT);
		$query->bindValue(":pUserAttr", $pAttr, \PDO::PARAM_STR);
		
		$query->execute();
		
		$return = $query->fetch(\PDO::FETCH_OBJ);
		
		if ($return != null) {
			return $return->user_val;
		} else {
			return "";
		}
	}
	
	public function sendAttribute($pId, $pAttr, $pVal) {
		if (!is_numeric($pId))
			return -1;
		
		$test = $this->getAttribute($pId, $pAttr);
		
		if ($test == "" || $test == null)
			return $this->insertAttribute($pId, $pAttr, $pVal);
		else
			return $this->updateAttribute($pId, $pAttr, $pVal);
	}
	
	protected function insertAttribute($pId, $pAttr, $pVal) {
		$query = $this->dao->prepare("
				INSERT INTO user_attr (
					id,
					user_id,
					user_attr,
					user_val
					)
				VALUES (
					null,
					:pUserId,
					:pUserAttr,
					:pUserVal
					);
				");

		$query->bindValue(":pUserId", $pId, \PDO::PARAM_INT);
		$query->bindValue(":pUserAttr", $pAttr, \PDO::PARAM_STR);
		$query->bindValue(":pUserVal", $pVal, \PDO::PARAM_STR);
		
		if ($query->execute())
			return 1;
		else
			return -2;
	}
	
	protected function updateAttribute($pId, $pAttr, $pVal) {
		$query = $this->dao->prepare("
				UPDATE
					user_attr
				SET
					user_val = :pUserVal
				WHERE 
					user_id = :pUserId
				AND
					user_attr = :pUserAttr
				");

		$query->bindValue(":pUserId", $pId, \PDO::PARAM_INT);
		$query->bindValue(":pUserAttr", $pAttr, \PDO::PARAM_STR);
		$query->bindValue(":pUserVal", $pVal, \PDO::PARAM_STR);
		
		if ($query->execute())
			return 1;
		else
			return -3;
	}
	
	public function deleteAttribute($pId, $pAttr) {
		$query = $this->dao->prepare("
				DELETE FROM
					user_attr
				WHERE
					user_id = :pUserId;
				AND
					user_attr = :pUserAttr
				");

		$query->bindValue(":pUserId", $pId, \PDO::PARAM_INT);
		$query->bindValue(":pUserAttr", $pAttr, \PDO::PARAM_STR);
		
		$query->execute();
		
		return 1;
	}
	
	public function getUserAttribute ($pId) {
		if (!(is_numeric($pId)))
			return array();
		
		$query = $this->dao->prepare("SELECT
					id,
					user_id,
					user_attr,
					user_val
				FROM
					user_attr
				WHERE
					user_id = :pUserId
				;");

		$query->bindValue(":pUserId", $pId, \PDO::PARAM_INT);
		
		$query->execute();
		
		$dataSet = $query->fetchAll(\PDO::FETCH_OBJ);
		$return = array();
		foreach ($dataSet AS $data) {
			$return[$data->user_attr] = $data->user_val;
		}
		
		return $return;
	}
	
	public function deleteUserAttribute($pId) {
		$query = $this->dao->prepare("
				DELETE FROM
					user_attr
				WHERE
					user_id = :pUserId;
				");

		$query->bindValue(":pUserId", $pId, \PDO::PARAM_INT);
		
		$query->execute();
		
		return 1;
	}
	
	public function getGroupeIdFromUser($pId) {
		if (!is_numeric($pId))
			return array();
		
		$query = $this->dao()->prapare("
				SELECT
					groupe_id
				FROM
					user_groupe
				WHERE
					user_id = :pId
				;");
		
		$query->bindValue(":pId", $pId, \PDO::PARAM_INT);
		
		$query->execute();
		
		return $query->fetchAll(\PDO::FETCH_OBJ);
	}
}

?>