<?php

namespace Modules\Accueil;

if (!defined("EVE_APP"))
	exit();

class AccueilController extends \Library\BackController {
	public function executeIndex(\Library\HTTPRequest $request) {
		$languageManager = $this->managers->getManagersOf("language");

		$this->page->addVar("test", $languageManager->getList(array("id < 3")));
	}
	public function executeActivites(\Library\HTTPRequest $request) {
	}
	public function executeContact(\Library\HTTPRequest $request) {
	}
	public function executeGalerie(\Library\HTTPRequest $request) {
	}
	public function executeResultats(\Library\HTTPRequest $request) {
	}
	public function executeBoutique(\Library\HTTPRequest $request) {
	}
	public function executeDivers(\Library\HTTPRequest $request) {
	}
	public function executeLiens(\Library\HTTPRequest $request) {
	}
	public function executeRapports(\Library\HTTPRequest $request) {
	}
	public function executeStatuts(\Library\HTTPRequest $request) {
	}
	public function executeAgenda(\Library\HTTPRequest $request) {
	}
	public function executeParticiper(\Library\HTTPRequest $request) {
	}
}

?>
