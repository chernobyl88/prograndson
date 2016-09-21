<?php
namespace Library\Entities;

if (!defined("EVE_APP"))
	exit();

class dynamic_page extends \Library\Entity {
	protected $id;
	protected $date_add;
	protected $date_modif;
	protected $page_content;
	protected $date_end;
	protected $visible;
	protected $routes_id;

	CONST INVALID_ID = 1;
	CONST INVALID_CLEF = 1;
	CONST INVALID_VALEUR = 2;
	CONST INVALID_LANG = 3;
	
	public function setPage_content($pVal) {
		$this->page_content = $pVal;
	}
}
?>