<?php

namespace Applications\Prograndson;

if (!defined("EVE_APP"))
	exit();

/*
*
* Application de la Plateforme de Publication
*
* @extends Application
*
*/
class PrograndsonApplication extends \Library\Application{
	
	// Crée une application nommée PlatPub
	public function __construct($root){
		$this->name = 'Prograndson';
		
		parent::__construct($root);
	}
	
	// Permet de faire fonctionner l'application
	public function run(){
		$controller = $this->getController();
		
		$controller->execute();
		$this->httpResponse->setPage($controller->page());
		$this->httpResponse->send();
	}
	
}

?>