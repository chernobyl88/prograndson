<?php

namespace Modules\Presentation\Entities;

if (!defined("EVE_APP"))
	exit();

class item extends \Library\Entity {
	protected $id;
	protected $presentation_main_id;
	protected $val;
	protected $item;
	protected $liste_id;
	protected $name;
	
	protected $key;
	
	/**
	 * @var array
	 */
	protected $liste_elem = array();
	
	/**
	 * @var \Modules\Presentation\Entities\texte
	 */
	protected $texte;
	
	/**
	 * @var \Modules\Presentation\Entities\date
	 */
	protected $date;
	
	/**
	 * @var \Library\Entities\file
	 */
	protected $files;
	
	/**
	 * @return int
	 * 
	 * @throws \BadMethodCallException
	 */
	public function key() {
		switch ($this->item) {
			case "text":
			case "img":
			case "list":
			case "date":
				return $this->val;
		}
	}
	
	public function setKey($pVal) {
		
	}
	
	/**
	 * @throws \IllegalArgumentException
	 * 
	 * @return \Library\Entities\file|string|multitype:
	 */
	public function val() {
		if (isset($this->val) && !empty($this->val))
			switch ($this->item) {
				case "text":
					return $this->texte()->val();
				case "date":
					return $this->date()->val();
				case "img":
					if (isset($this->files) && !empty($this->files) && $this->files instanceof \Library\Entities\Files)
						return $this->files;
					else
						throw new \IllegalArgumentException("The file is not defined");
				case "elem":
					return (isset($this->val) && !empty($this->val)) ? $this->val : "";
				case "list":
					return (is_array($this->liste_elem)) ? array("title" => $this->val, "elem" => $this->liste_elem) : array("title" => $this->val, "elem" => array());
				default:
					throw new \IllegalArgumentException("This type is not defined");
			}
		else
			if ($this->item == "elem")
				return "";
			else
				throw new \InvalidArgumentException("Try to get undefined value");
	}
	
	public function setListe_elem(array $listeElem) {
		$this->liste_elem = $listeElem;
	}
	
	public function addListe_elem($elem) {
		if (is_array($elem))
			foreach ($elem AS $e)
				$this->addListe_elem($e);
		else
			$this->liste_elem[] = $elem;
	}
	
	public function liste_elem($key = null) {
		if ($key == null)
			return $this->liste_elem;
		else
			if (key_exists($key, $this->liste_elem))
				return $this->liste_elem[$key];
			else
				return null;
	}
	
	/**
	 * @return \Modules\Presentation\Entities\texte
	 */
	public function texte() {
		if (isset($this->texte) && !empty($this->texte) && $this->texte instanceof \Modules\Presentation\Entities\texte)
			return $this->texte;
		else
			return new \Modules\Presentation\Entities\texte();
	}
	
	public function setTexte(\Modules\Presentation\Entities\texte $pVal) {
		$this->texte = $pVal;
	}
	
	/**
	 * @return \Modules\Presentation\Entities\date
	 */
	public function date() {
		if (isset($this->date) && !empty($this->date) && $this->date instanceof \Modules\Presentation\Entities\date)
			return $this->date;
		else
			return new \Modules\Presentation\Entities\date();
	}
	
	public function setDate($pVal) {
		if ($pVal instanceof \Modules\Presentation\Entities\date) {
			$this->date = $pVal;
		}
	}
	
}

?>