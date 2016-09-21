<?php

namespace Modules\News\Entities;

if (!defined("EVE_APP"))
	exit();

class main extends \Library\Entity {
	protected $id;
	protected $user_id;
	protected $title;
	protected $date_crea;
	protected $visible;
	protected $txt_content;
	protected $file_id;
	protected $date_for;
	protected $chapeau;
	
	protected $has_img;
	
	protected $user;
	
	const INVALID_TITLE = 2;
	
	
	public function setUser($pVal) {
		if ($pVal instanceof \Library\Entities\user)
			$this->user = $pVal;
	}
	
	public function user() {
		if (isset ($this->user) && $this->user instanceof \Library\Entities\user)
			return $this->user;
		else
			return new \Library\Entities\user();
	}
	
	public function setHas_img($pVal) {
		switch ($pVal) {
			case 0:
			case 1:
				$this->has_img = $pVal;
				break;
			default:
				$this->has_img = 0;
		}
	}
	
	public function has_img() {
		return (isset($this->has_img)) ? $this->has_img : 0;
	}
}

?>