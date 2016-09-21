<?php

namespace Library;

if (!defined("EVE_APP"))
	exit();

/**
 * Class that represents the information about a page that has been modified by an user
 *
 * @copyright ParaGP Swizerland
 * @author Zellweger Vincent
 * @version 1.0
 */
class Dynamic_page{
	
	/**
	 * The ID of the dynamic page
	 * 
	 * @var int
	 */
	protected $id;
	
	/**
	 * The date when the page has been added
	 * 
	 * @var \DateTime
	 */
	protected $date_add;
	
	/**
	 * The date of the last modification.
	 * 
	 * @var string
	 */
	protected $date_modif;
	
	/**
	 * Content of the page that has been provided by the user.
	 * 
	 * @var String
	 */
	protected $page_content;
	
	/**
	 * Date when the page has to stop beeing used
	 * 
	 * @var \DateTime
	 */
	protected $date_end;
	
	/**
	 * Say if a page has to be visible or not
	 * 
	 * @var int
	 */
	protected $visible;
	
	/**
	 * ID of the route that correspond to this dynamic page
	 * 
	 * @var int
	 */
	protected $routes_id;
	
	/**
	 * Constructor of the road.
	 * Sets automatically all the different informations about the road
	 * 
	 * @param mixed[] $options
	 */
	public function __construct(array $options = array()){
		if(!empty($options)){
			$this->hydrate($options);
		}
	}
	
	/**
	 * Function that checks whether there exists a setter for the different keys provided in the array in parameter or not and sets the different attribute with the value
	 * 
	 * @param mixed[] $option
	 */
	public function hydrate(array $option){
		
		foreach($option AS $key=>$value){
			$method = 'set' . ucfirst($key);
			if(is_callable(array($this, $method))){
				$this->$method($value);
			}
		}
	}
	
	/**
	 * Setter of the date time
	 * 
	 * @param string $date
	 */
	public function setDate_add($date) {
		$this->date_add = new \DateTime($date);
	}
	
	/**
	 * Setter of the ID
	 * 
	 * @param int $pVal
	 * @throws \IllegalArgumentException
	 * 			If the ID is not numeric
	 */
	public function setId($pVal){
		if(!is_numeric($pVal) || empty($pVal))
				if (\Library\Application::appConfig()->getConst("LOG"))
					throw new \IllegalArgumentException("Error ID: " . \Library\Application::logger()->log("Error", "Route", "L'identifiant doit être un nombre.", __FILE__, __LINE__));
				else
					throw new \IllegalArgumentException("L'identifiant doit être un nombre.");
				
		$this->id = $pVal;
	}
	
	/**
	 * Setter of the date of modification
	 * 
	 * @param string $date
	 */
	public function setDate_modif($date){
		$this->date_modif = new \DateTime($date);
	}
	
	/**
	 * Setter of the content of te page
	 * 
	 * @param string $url
	 */
	public function setPage_content($content){
		$this->page_content = $content;
	}
	
	/**
	 * Setter of the date of end
	 * 
	 * @param string $date
	 */
	public function setDate_end($date){
		if (!is_null($date))
			$this->date_end = new \DateTime($date);
		else 
			$this->date_end = null;
	}
	
	/**
	 * Setter for the visibility
	 * 
	 * @param int $visible
	 */
	public function setVisible($visible){
		switch ($visible) {
			case 0:
			case 1:
				$this->visible = $visible;
				break;
			default:
				$this->visible = 1;
		}
	}
	
	/**
	 * Setter for the route identifier
	 * 
	 * @param int $var
	 */
	public function setRoutes_id($var) {
		if (is_numeric($var) && $var > 0)
			$this->routes_id = $var;
	}
	
	/**
	 * getter of id
	 * 
	 * @return int
	 */
	public function id(){
		return $this->id;
	}
	
	/**
	 * getter of the adding date
	 * 
	 * @return \DateTime
	 */
	public function date_add() {
		if (!($this->date_add instanceof \DateTime))
			$this->date_add = new \DateTime($this->date_add);
		
		return $this->date_add;
	}
	
	/**
	 * getter of the modified date
	 * 
	 * @return \DateTime
	 */
	public function date_modif() {
		if (!($this->date_modif instanceof \DateTime))
			$this->date_modif = new \DateTime($this->date_modif);
		
		return $this->date_modif;
	}
	
	/**
	 * getter of the content of the page
	 * 
	 * @return String
	 */
	public function page_content() {
		if (isset($this->page_content))
			return $this->page_content;
		else
			return "";
		return $this->url;
	}
	
	/**
	 * getter of the date of the end of dynamic page show
	 * 
	 * @return \DateTime|null
	 */
	public function date_end(){
		if (isset($this->date_end)) {
			if (!($this->date_end instanceof \DateTime))
				$this->date_end = new \DateTime($this->date_end);
			
			return $this->date_end;
		} else {
			return null;
		}
	}
	
	/**
	 * getter of the visibility
	 * 
	 * @return int
	 */
	public function visible(){
		switch ($this->visible) {
			case 0:
			case 1:
				return $this->visible;
			default:
				return 0;
		}
	}
	
	/**
	 * getter of the route id
	 * 
	 * @return int
	 */
	public function routes_id(){
		if (isset($this->routes_id))
			return $this->routes_id;
		else
			return 0;
	}
	
	/**
	 * return if the dynamic page has to replace the page or not
	 * 
	 * @return boolean
	 */
	public function showable() {
		return $this->visible() == 1 && ($this->date_end() == null || $this->date_end() < new \DateTime());
	}
}

?>