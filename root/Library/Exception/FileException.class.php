<?php

namespace Library\Exception;

if (!defined("EVE_APP"))
	exit();

/**
 * A class that checks all the exceptions conserning the access. It gives some informations on deconnection, time finished and so on.
 * 
 * @copyright ParaGP Swizerland
 * @author Zellweger Vincent
 * @version 1.0
 */
class FileException extends \Exception {
	
	/**
	 * The data type is not a valid one
	 * @var int
	 */
	const INVALID_DATA_TYPE = 1;
	
	/**
	 * Don't have the permission to write the file in the destination folder 
	 * @var int
	 */
	const UNWRITABLE_FOLDER = 2;
	
	/**
	 * Error on inserting the file in db
	 * @var int
	 */
	const ERROR_DB_INSERTION = 3;
	
	/**
	 * Error during upload
	 * @var int
	 */
	const UPLOAD_ERROR = 4;
}

?>