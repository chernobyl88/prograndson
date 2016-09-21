<?php

namespace Library\Page;

if (!defined("EVE_APP"))
	exit();

/**
 * Page that returns a CSV from a given element
 * 
 * The needed attributes are
 * 
 * - data : the data that has to be converted into a csv file
 * 		The data has to be a 2 dimentional array, the inner array has to have same key for each ellement
 * 
 * The optional attributes are
 * 
 * - name : the name of the csv file
 * 
 * @see \Library\Page\Page
 * 
 * @copyright ParaGP Swizerland
 * @author Zellweger Vincent
 * @version 1.0
 */
class PageCsv extends Page {
	/**
	 * (non-PHPdoc)
	 * @see \Library\Page\Page::generate()
	 */
	public function generate(){
		
		if (!key_exists("data", $this->attribute) || !is_array($data = $this->attribute["data"]))
			if (\Library\Application::appConfig()->getConst("LOG"))
				throw new \InvalidArgumentException("Error ID: " . \Library\Application::logger()->log("Error", "Page", "The data has to be an array to fill the CSV file", __FILE__, __LINE__));
			else
				throw new \InvalidArgumentException("The data has to be an array to fill the CSV file");

		$this->app->httpResponse()->addHeader('Content-type: text/csv');
		$this->app->httpResponse()->addHeader('Expires: 0');
		$this->app->httpResponse()->addHeader('Pragma: no-cache');
		$this->app->httpResponse()->addHeader("Content-Transfer-Encoding: UTF-8");
		$this->app->httpResponse()->addHeader('Content-Disposition: attachment; filename=' . ((key_exists("name", $this->attribute) && ($name = $this->attribute["name"]) && !empty($name)) ? $name . ((strtolower(substr($name, -4)) != ".csv") ? ".csv" : "") : "file.csv"));
		
		$return = "";
		
		foreach ($data AS $line) {
			if ($return == "") {
				$key = array_keys($line);
				
				$return .= implode(":",$key) . "\n";
			}
			if (count($key)) {
				foreach ($key AS $k)
					if (key_exists($k, $line))
						$return .= str_replace(array("\n", "\t", ",", ":"), "", $line[$k]) . ":";
					else
						$return .= ":";
				$return = substr($return, 0, -1) . "\n";
			}
		}
		
		return $return; 
	}
}

?>