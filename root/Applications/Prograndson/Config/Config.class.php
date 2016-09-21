<?php

namespace Applications\Prograndson\Config;

if (!defined("EVE_APP"))
	exit();

/*
*
* Application de la Plateforme de Publication
*
* @extends Application
*
*/
class Config extends \Library\AppConfig{
	
	const LOG = false;
	
	const BDD_HOST = "localhost";
	const BDD_NAME = "new_prograndson";
	const BDD_USER = "root";
	const BDD_PASSWORD = "root";
	
	const MAX_ADMIN_LVL = 10;
	
	const DAO = "PDO";
	
	const DOWNLOAD_FOLDER = '\\Upload\\';
}

?>