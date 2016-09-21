<?php

namespace Library\Entities;

if (!defined("EVE_APP"))
	exit();

class routes extends \Library\Entity {
	protected $id;
	protected $url;
	protected $module;
	protected $action;
	protected $vars;
	protected $admin_lvl;
	protected $changeable;
	protected $page_type;
	protected $title;
	protected $description;
	protected $parent_id;
	protected $on_menu;
	protected $only_dyn;
	protected $user_id;
	protected $date_crea;
	
	protected $parent_route;
	protected $user;
	
	protected $liste_route = array();

	CONST INVALID_ID = 1;
	CONST INVALID_CLEF = 1;
	CONST INVALID_VALEUR = 2;
	CONST INVALID_LANG = 3;
	
	public function setParent_route($route) {
		if ($route instanceof \Library\Route)
			$this->parent_route = $route;
	}
	
	public function setUser($user) {
		if ($user instanceof \Library\Entities\user)
			$this->user = $user;
	}
	
	public function parent_route() {
		if (isset($this->parent_route))
			return $this->parent_route;
		else
			return new \Library\Route();
	}
	
	public function user() {
		if (isset($this->user))
			return $this->user;
		else
			return new \Library\Entities\user();
	}
	
	public function initListe_route() {
		$this->liste_route = array();
	}
	
	public function addListe_route($pVal) {
		if (is_array($pVal))
			foreach ($pVal AS $e)
				$this->addListe_route($e);
		elseif ($pVal instanceof \Library\Entities\routes)
			$this->liste_route[] = $pVal;
	}
	
	public function setListe_route($pVal) {
		$this->initListe_route();
		$this->addListe_route($pVal);
	}
	
	public function liste_route($key = null) {
		if (exists_key($key, $this->liste_route))
			return $this->liste_route[$key];
		else
			return $this->liste_route;
	}
}

?>