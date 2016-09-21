<?php

namespace Modules\Galerie\Entities;

if (!defined("EVE_APP"))
	exit();

class visite extends \Library\Entity {
	protected $id;
	protected $galerie_main_file_id;
	protected $ip_adresse;
	protected $date_visite;
}

?>