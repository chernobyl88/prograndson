<?php

namespace Modules\Galerie\Entities;

if (!defined("EVE_APP"))
	exit();

class main_file extends \Library\Entity {
	protected $id;
	protected $galerie_main_id;
	protected $file_id;
	protected $accepted;
	protected $nom;
	protected $galerie_groupe_id;
	
	protected $file;
	protected $user;
	protected $groupe;
	
	public function setGroupe($pVal) {
		if ($pVal instanceof \Modules\Galerie\Entities\groupe && $groupe->id() > 0) {
			$this->groupe = $pVal;
			return 1;
		} elseif ($this->galerie_groupe_id == 0)
			return 1;
		return 0;
	}
	
	public function groupe() {
		return ($this->groupe instanceof \Modules\Galerie\Entities\groupe) ? $this->groupe : new \Modules\Galerie\Entities\groupe();
	}
	
	public function setUser($pVal) {
		if ($pVal instanceof \Library\Entities\user && $pVal->id() > 0) {
			$this->user = $pVal;
			return 1;
		}
		return 0;
	}
	
	public function user() {
		return ($this->user instanceof \Library\Entities\user) ? $this->user : new \Library\Entities\user();
	}
	
	public function setFile($pVal) {
		if ($pVal instanceof \Library\Entities\file && $pVal->id() > 0) {
			$this->file = $pVal;
			return 1;
		}
		return 0;
	}
	
	public function file() {
		return ($this->file instanceof \Library\Entities\file) ? $this->file : new \Library\Entities\file();
	}
}

?>