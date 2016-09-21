<?php

namespace Library\Entities;

if (!defined("EVE_APP"))
	exit();

class file extends \Library\Entity {
	protected $id;
	protected $file_name; // nom du fichier sur le serveur
	protected $file_src; // racine du fichier si nécessaire
	protected $file_pub_name; // nom du fichier pour l'utilisateur
	protected $user_id;
	protected $dynamic;
	protected $date_upload;
	
	protected $cst_name;
	
	protected $user;

	CONST INVALID_ID = 1;
	CONST INVALID_FILE_NAME = 2;
	CONST INVALID_FILE_SRC = 3;
	CONST INVALID_FILE_PUB_NAME = 4;
	
	public function setUser($pVal) {
		if ($pVal instanceof \Library\Entities\user)
			$this->user = $pVal;
	}
	
	public function user() {
		return ($this->user instanceof \Library\Entities\user) ? $this->user : new \Library\Entities\user();
	}
}

?>