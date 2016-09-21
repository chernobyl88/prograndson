<?php

namespace Library;

if (!defined("EVE_APP"))
	exit();
//TODO: revoke it and use {@see \Library\Entities\routes} instead
/**
 * Class that represents one of the different links possible on the application.
 * 
 * It is a kind of Entity.
 * 
 *
 * @copyright ParaGP Swizerland
 * @author Zellweger Vincent
 * @version 1.0
 */
class Route{
	
	/**
	 * The ID of the route
	 * 
	 * @var int
	 */
	protected $id;
	
	/**
	 * The view that we want for this road
	 * 
	 * @var string
	 */
	protected $action;
	
	/**
	 * The module on which the view and the controller are for this road
	 * 
	 * @var string
	 */
	protected $module;
	
	/**
	 * A regular expression that represent the road
	 * 
	 * @var String
	 */
	protected $url;
	
	/**
	 * The key of the different variables linked to this road. The different keys are separeted by coma
	 * 
	 * @var string
	 */
	protected $vars;
	
	/**
	 * List with key=>value returned when the URI has ben compared with the URI of the roads, from first to last, and the different values have been returned
	 * The comparition stops when the correct road has been found
	 * 
	 * @var mixed[]
	 */
	protected $varsListe = array();
	
	/**
	 * Level of administration needed to go on this page
	 * 
	 * @var int
	 */
	protected $admin_lvl;
	
	/**
	 * Indication to know if a page could be changed. It mean that this page could
	 * (or not) be dynamicly changed.
	 * 
	 * @var int
	 */
	protected $changeable;
	
	/**
	 * Default type of the page. Used to know what kind of error to return on error.
	 * 
	 * The default error should be
	 * html => error 404
	 * json => valid = 0
	 * xml => vlaid = 0
	 * img => error 404
	 * file => error 404
	 * 
	 * by dafault: html
	 * @var string
	 */
	protected $page_type;
	
	/**
	 * Title of the route
	 * 
	 * A title that represent well the route and that can be used to indicate the route
	 * 
	 * This information is shown on the menu if used
	 * This information is shown on the dynamic page table
	 * 
	 * default: NULL
	 * 
	 * @var String
	 */
	protected $title;
	
	/**
	 * Description of the route
	 * 
	 * A description that can give more information than the title, for example if two title are the same
	 * 
	 * This information is shown in the dynamic page table
	 * 
	 * default: NULL
	 * 
	 * @var string
	 */
	protected $description;
	
	/**
	 * ID of the parent route
	 * 
	 * This ID is usefull if the route is on the menu to know the hierarchy of the different menu ellement
	 * 
	 * default: 0
	 * 
	 * @var int
	 */
	protected $parent_id;
	
	/**
	 * Bit that indicate if a route is on the menu or not
	 * 
	 * default: 0
	 * 
	 * @var 0|1
	 */
	protected $on_menu;
	
	/**
	 * Bit that indicate if a route is only dynamic or not
	 * 
	 * It mean that this route has been created by an administrator to shown only
	 * a content that it has provided. This page has no default content.
	 * 
	 * default: 0
	 * 
	 * @var 0|1
	 */
	protected $only_dyn;
	
	/**
	 * ID of the user that has created the specific route.
	 * 
	 * This information is usefull if the only_dyn bit is set to one.
	 * This information is shown nowhere unless in the DB table. It could be usefull
	 * to find who did some mistake (or worste)
	 * 
	 * @var int
	 */
	protected $user_id;
	
	/**
	 * Date of the creation of the route
	 * 
	 * @var \DateTime
	 */
	protected $date_crea;
	
	/**
	 * Last dynamic page of the route if it exist
	 * 
	 * @var \Library\Dynamic_page
	 */
	protected $dynamic_page;
	
	
	
	const DEFAULT_CHANGEABLE = 0;
	const DEFAULT_PAGE_TYPE = "html";
	
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
	 * Returns whether there is variable for this road or not
	 * 
	 * @return boolean
	 */
	public function hasVars(){
		return !empty($this->vars);
	}
	
	/**
	* Checks if the parameter matches with the url of the road. If there is no variable and the parameter and the road are the same, then this road matches. If the patern matches with the URI provided in parameter, then this road matches. Else the road doesn't match
	*
	* @param string $url
	* 			The uri to test
	*
	* @return false|string[]
	* 				The table with the value of all the different variables if the road matches. False if the road doesn't match.
	*
	*/
	public function matchUrl($url){
		$aUrl = explode('?', $url);
		$aUrl = explode('#', $aUrl[0]);
		$url = $aUrl[0];
		
		if ($this->hasVars() && $this->url == $url)
			return array(); 
		
		if(preg_match('`^' . $this->url . '$`', $url, $matches)){
			unset($matches[0]);
			return $matches;
		}else{
			return false;
		}
	}
	
	/**
	 * Setter of the action
	 * 
	 * @param string $action
	 */
	public function setAction($action){
		if(is_string($action)){
			$this->action = $action;
		}
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
	 * Setter of the url
	 * 
	 * @param string $module
	 */
	public function setModule($module){
		if(is_string($module)){
			$this->module = $module;
		}
	}
	
	/**
	 * Setter of the url
	 * 
	 * @param string $url
	 */
	public function setUrl($url){
		if(is_string($url)){
			$this->url = $url;
		}
	}
	
	/**
	 * Setter of the varsListe.
	 * Replace the current varsListe by the new value in parameter
	 * 
	 * @param string[] $varsListe
	 */
	public function setVarsListe(array $varsListe){
		$this->varsListe = $varsListe;
	}
	
	/**
	 * Setter for vars
	 * 
	 * @param string $vars
	 */
	public function setVars(array $vars){
		$this->vars = $vars;
	}
	
	/**
	 * Setter for the changeable bit
	 * 
	 * @param int $var
	 */
	public function setChangeable($var) {
		switch ($var) {
			case 0:
			case 1:
				$this->changeable = $var;
				break;
			default:
				$this->changeable = self::DEFAULT_CHANGEABLE;
		}
	}
	
	/**
	 * Setter for the default page type
	 * 
	 * @param string $var
	 */
	public function setPage_type($var) {
		if ($var != "" && file_exists(__DIR__ . "Page/Page" . ucfirst($var) . ".class.php"))
			$this->page_type = $var;
		else
			$this->page_type = self::DEFALUT_PAGE_TYPE;
	}
	
	public function setTtitle($pTitle) {
		if (!empty($pTitle))
			$this->title = $pTitle;
		else
			$this->title = NULL;
	}
	
	public function setDescription($pDesc) {
		if (!empty($pDesc))
			$this->description = $pDesc;
		else
			$this->description = NULL;
		$this->description = $pDesc;
	}
	
	public function setParent_id ($pId) {
		if (is_numeric($pId) && $pId >= 0)
			$this->parent_id = $pId;
		else
			$this->parent_id = 0;
	}
	
	public function setOn_menu($bit) {
		switch ($bit) {
			case 0:
			case 1:
				$this->on_menu = $bit;
				break;
			default:
				$this->on_menu = 0;
		}
	}
	
	public function setOnly_dyn($bit) {
		switch ($bit) {
			case 0:
			case 1:
				$this->only_dyn = $bit;
				break;
			default:
				$this->only_dyn = 0;
		}	
	}
	
	public function setUser_id ($pId) {
		if (is_numeric($pId) && $pId >= 0)
			$this->user_id = $pId;
		else
			$this->user_id = NULL;
	}
	
	public function setDate_crea($date) {
		if (!empty($date))
			$this->date_crea = new \DateTime($date);
		else
			$this->date_crea = NULL;
	}
	
	/**
	 * Set the last dynamic page
	 * 
	 * @param \Library\Dynamic_page $page
	 */
	public function setDynamic_page($page) {
		if (is_array($page))
			foreach ($page AS $p)
				$this->setDynamic_page($p);
		elseif (
				$page instanceof \Library\Dynamic_page &&
				$page->routes_id() == $this->id &&
				(is_null($this->dynamic_page) || $page->date_modif() > $this->dynamic_page->date_modif())
				)
			$this->dynamic_page = $page;
	}
	
	/**
	 * Method to add one var at the end of the varsListe. 
	 * Add a new variable specified by his key and his value at the end of the variable list
	 * 
	 * If the key already exists, it can't replace id since force is not set to 1 to force the replacement. By default the value is not replaced.
	 * 
	 * It is allowed to add key that are not in the vars
	 * 
	 * @param string $key
	 * @param mixed $val
	 * @param number $force
	 * 
	 * @return number
	 */
	public function addVarInListe($key, $val, $force = 0) {
		if (key_exists($key, $this->varsListe) && $force == 0) {
			trigger_error("Value already exist, impossible to replace whitout force");
			return 0;
		}
		
		$this->varsListe[$key] = $val;
		return 1;
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
	 * getter of action
	 * 
	 * @return String
	 */
	public function action(){
		return $this->action;
	}
	
	/**
	 * getter of module
	 * 
	 * @return String
	 */
	public function module(){
		return $this->module;
	}
	
	/**
	 * getter of url
	 * 
	 * @return String
	 */
	public function url(){
		return $this->url;
	}
	
	/**
	 * getter of varsListe
	 * 
	 * @return mixed[]
	 */
	public function varsListe(){
		return $this->varsListe;
	}
	
	/**
	 * getter of the keys.
	 * The keys are transformed into an array by exploding on the coma
	 * 
	 * @return string[]
	 */
	public function vars(){
		
		return array_map(function($arg) {
			return trim($arg);
		}, explode(',', $this->vars));
	}
	
	/**
	 * getter of admin_lvl
	 * 
	 * @return int
	 * @deprecated
	 */
	public function admin_lvl(){
		return $this->admin_lvl;
	}
	
	/**
	 * return the default changeable status of the page
	 * 
	 * @return number
	 */
	public function changeable() {
		if (isset($this->changeable))
			return $this->changeable;
		else
			return self::DEFAULT_CHANGEABLE;
	}
	
	/**
	 * return the default page type
	 * 
	 * @return string
	 */
	public function page_type() {
		if (isset($this->page_type))
			return $this->page_type;
		else 
			return self::DEFAULT_PAGE_TYPE;
			
	}
	
	public function title() {
		if (isset($this->title) && !empty($this->title)) {
			return $this->title;
		} else {
			return "";
		}
	}
	
	public function description() {
		if (isset($this->description) && !empty($this->description)) {
			return $this->description;
		} else {
			return "";
		}
		
	}
	
	public function parent_id() {
		if (isset($this->parent_id) && !empty($this->parent_id)) {
			return $this->parent_id;
		} else {
			return "";
		}
	}
	
	public function on_menu() {
		return ($this->on_menu == 0 || $this->on_menu == 1) ? $this->on_menu : 0;
	}
	
	public function only_dyn() {
		return ($this->only_dyn == 0 || $this->only_dyn == 1) ? $this->only_dyn : 0;
	}
	
	public function user_id() {
		if (isset($this->parent_id) && !empty($this->parent_id))
			return $this->parent_id;
		else
			return 0;		
	}
	
	public function date_crea() {
		if (isset($this->date_crea) && !empty($this->date_crea)) {
			if (!($this->date_crea instanceof \DateTime))
				$this->date_crea = new \DateTime($this->date_crea);
			
			return $this->date_crea;
		} else
			return NULL;
	}
	
	
	/**
	 * return the current dynamic page
	 * 
	 * @return \Library\Dynamic_page
	 */
	public function dynamic_page() {
		if (isset($this->dynamic_page) && $this->dynamic_page instanceof \Library\Dynamic_page)
			return $this->dynamic_page;
		else
			return new \Library\Dynamic_page();
	}
	
	/**
	 * Return whether or not this road need a connection. It mean that the
	 * admin_lvl is greater than 0
	 * 
	 * @return boolean
	 */
	public function needConnection() {
		return $this->admin_lvl > 0;
	}
	
	/**
	 * Given a specific admin_lvl say whether this admin_lvl grant the access to this road or not. It means that the admin_lvl is greater to or equals the road admin_lvl
	 * 
	 * @param int $admin_lvl
	 * @return boolean
	 * @deprecated
	 */
	public function allowed($admin_lvl) {
		if (is_numeric($admin_lvl) && $admin_lvl >= $this->admin_lvl) {
			return true;
		} else {
			return false;
		}
	}
}

?>