<?php

namespace Modules\Galerie\Entities;

if (!defined("EVE_APP"))
	exit();

class vote extends \Library\Entity {
	protected $id;
	protected $note_total;
	protected $galerie_main_file_id;
	protected $ip_adresse;
	protected $date_vote;
}

?>