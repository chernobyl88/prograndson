<?php

namespace Modules\Galerie\Entities;

if (!defined("EVE_APP"))
	exit();

class concours_result extends \Library\Entity {
	protected $id;
	protected $galerie_main_id;
	protected $galerie_main_file_id;
	protected $rang;
	
	protected $main_file;
	protected $galerie;
	
	protected $date_result;
	
	/**
	 * 
	 * @param unknown $pVal
	 */
	function setMain_file($pVal) {
		if ($pVal instanceof \Modules\Galerie\Entities\main_file)
			$this->main_file = $pVal;
	}
	
	/**
	 * 
	 * @return \Modules\Galerie\Entities\main_file
	 */
	function main_file() {
		if ($this->main_file instanceof \Modules\Galerie\Entities\main_file)
			return $this->main_file;
		
		return new \Modules\Galerie\Entities\main_file();
	}
	
	/**
	 * 
	 * @param unknown $pVal
	 */
	function setGalerie($pVal) {
		if($pVal instanceof \Modules\Galerie\Entities\main)
			$this->galerie = $pVal;
	}
	
	/**
	 * 
	 * @return \Modules\Galerie\Entities\main
	 */
	function galerie() {
		if ($this->galerie instanceof \Modules\Galerie\Entities\main)
			return $this->galerie;
		
		return new \Modules\Galerie\Entities\main();
	}
}
?>