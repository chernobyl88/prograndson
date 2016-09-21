<?php

namespace Modules\Presentation\Entities;

if (!defined("EVE_APP"))
	exit();

class main extends \Library\Entity {
	protected $id;
	protected $nom;
	protected $type;
	protected $date_crea;
	protected $published;
	protected $categorie_id;
	protected $date_fin;
	protected $deleted;
	
	protected $f_weight;

	protected $listeUser = array();
	
	/**
	 * @var \Modules\Presentation\Entities\categorie
	 */
	protected $categorie;
	protected $payed;
	
	protected $attribute = array();
	
	const INVALID_NOM = 2;
	const INVALID_TYPE = 3;
	
	public function f_weight() {
		return (isset($this->f_weight)) ? $this->f_weight : -1;
	}
	
	public function __set($name, $val) {
		$reflection = new \ReflectionClass($this);
		
		if ($reflection->hasProperty($name))
			return $this->__call("set".ucfirst($name), array($val));
		
		$this->setAttribute($name, $val);
	}
	
	public function __get($name) {
		$reflection = new \ReflectionClass($this);
		
		if ($reflection->hasProperty($name))
			return $this->__call($name, array());
		
		return $this->getAttribute($name);
	}
	
	public function setAttribute($key, $val, $force = 0) {
		if (key_exists($key, $this->attribute) && $this->attribute[$key] != $val && $force != 1)
			throw new \InvalidArgumentException("Key allready used");
					
		$this->attribute[$key] = $val;
					
		return 1;
	}
	
	public function getAttribute($key = null) {
		return ($key == null) ? $this->attribute: ((key_exists($key, $this->attribute)) ? $this->attribute[$key] : null);
	}
	
	public function attribute() {
		return $this->attribute;
	}
	
	public function payed() {
		return (isset($this->payed)) ? $this->payed : false;
	}
	
	public function setPayed($pVal) {
		if (!empty($pVal) && is_bool($pVal))
			$this->payed = $pVal;
		
		return 1;
	}
	
	public function addListeUser($pVal) {
		if (is_array($pVal))
			foreach ($pVal AS $p)
				$this->addListeUser($p);
		elseif ($pVal instanceof \Library\Entities\user)
			$this->listeUser[] = $pVal;
		
		return 1;
	}
	
	public function listeUser() {
		return (is_array($this->listeUser)) ? $this->listeUser : array();
	}
	
	public function setListeUser($pVal) {
		$this->listeUser = array();
		$this->addListeUser($pVal);
	}
	
	/**
	 * @return \Modules\Presentation\Entities\categorie
	 */
	public function categorie() {
		return (isset($this->categorie)) ? $this->categorie : new \Modules\Presentation\Entities\categorie;
	}
	
	public function setCategorie($pVal) {
		if (is_array($pVal))
			foreach ($pVal AS $p)
				$this->setCategorie($p);
		elseif ($pVal instanceof \Modules\Presentation\Entities\categorie)
			$this->categorie = $pVal;
		
		return 1;
	}
	
	public function setCategorie_id($pVal) {
		if (is_numeric($pVal) && $pVal>0)
			$this->categorie_id = $pVal;
		
		return 1;		
	}
	
	public function categorie_id() {
		return (isset($this->categorie_id)) ? $this->categorie_id : 0;
	}
}

?>