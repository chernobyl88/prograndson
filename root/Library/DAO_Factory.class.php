<?php

namespace Library;

if (!defined("EVE_APP"))
	exit();

/**
 * A factory to get the connection.
 * 
 * A factory using the Design Pattern Factory to generates the DAO we need.
 *
 * @copyright ParaGP Swizerland
 * @author Zellweger Vincent
 * @version 1.0
 */
class DAO_Factory{
	/**
	 * The factory itself.
	 * It checks with a switch which DAO is needed and return the db connection corresponding to the DAO needed.
	 * 
	 * @param string $type
	 * 			The type of the DB.
	 * @throws \RuntimeException
	 * 			If the type of the DB is unknown to the system
	 * @return mixed
	 * 			A connection to a specific DB (or anything else)
	 * @static
	 */
	public static function getConnexion($type){
		switch ($type) {
			case "PDO" :
				$db = PDOFactory::getMysqlConnexion();
				break;
			default:
				if (\Library\Application::appConfig()->getConst("LOG"))
					throw new \RuntimeException("Error ID: " . \Library\Application::logger()->log("Error", "Connection", "Error on connection type", __FILE__, __LINE__));
				else
					throw new \RuntimeException("Error on connection type");
		}
		return $db;
	}
}

?>