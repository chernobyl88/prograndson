<?php

namespace Modules\Galerie\Entities;

if (!defined("EVE_APP"))
	exit();

class main extends \Library\Entity {
	protected $id;
	protected $nom;
	protected $date_crea;
	protected $visible;
	protected $user_id;
	protected $parent_id;
	protected $description;
	protected $concours;
	protected $show_result;
	protected $date_result;
	protected $date_fin;
	protected $date_deb;
	
	protected $bg_img_id;
	protected $nbr_sub_gal;
	
	const INVALID_NOM = 1;
	
	public function setBg_img_id($pVal) {
		if (is_numeric($pVal) && $pVal > 0) {
			$this->bg_img_id = $pVal;
			return 1;
		} else
			return 0;
	}
	
	public function bg_img_id() {
		return (isset($this->bg_img_id)) ? $this->bg_img_id : -1;
	}
	
	public function setNbr_sub_gal($pVal) {
		if (is_numeric($pVal) && $pVal >= 0) {
			$this->nbr_sub_gal = $pVal;
			return 1;
		} else
			return 0;
	}
	
	public function nbr_sub_gal() {
		return (isset($this->nbr_sub_gal)) ? $this->nbr_sub_gal : 0;
	}
}

?>