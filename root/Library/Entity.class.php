<?php

namespace Library;

if (!defined("EVE_APP"))
	exit();


/**
 * The parent class of the entity.
 * 
 * The entities are the representation in PHP of the data that are saved on the server and that we need to work with.
 * 
 * This method provides the most generic method used by the Entities. They could be overrided to modify their work or to protect some integrity.
 * 
 * @copyright ParaGP Swizerland
 * @author Zellweger Vincent
 * @version 1.0
 * @abstract
 */
abstract class Entity {
	
	/**
	 * An instance of {@see \DateTime} used in form to check the difference of time
	 * between that a form has be created and the data are provided to the entity.
	 * 
	 * In general it could be use to block some critical modification if the time is too long
	 * 
	 * @var \DateTime
	 */
	protected $checkTime;
	
	/**
	 * A list of error provided when we try to send data to the entity and that the data
	 * are not valid
	 * 
	 * @var string[]
	 */
	protected $errors = array();
	
	protected static $app;
	
	/**
	 * Constructor of the Entities
	 * When gived with an array of data, the constructor try to {@see \Library\Entitiy::hydrate()}
	 * himself with this data.
	 * 
	 * @param mixed[]
	 */
	public function __construct(array $data = array()){
		if(!empty($data)){
			$this->hydrate($data);
		}
	}

	public static function pushApplication(\Library\Application $pApp) {
		self::$app = $pApp;
	}
	
	public static function getApplication() {
		return self::$app;
	}
	
	/**
	 * Indicate if an {@see \Library\Entity} is new or if it has be given by
	 * a {@see \Library\Manager}
	 * 
	 * @return boolean
	 */
	public function isNew(){
		return empty($this->id);
	}
	
	//Getter de l'objet
	/**
	 * Return the list of the error in the {@see \Library\Entity}
	 * 
	 * @return astring[]
	 */
	public final function errors(){
		return $this->errors;
	}
	
	/**
	 * Return whether or not theyr are som error on the {@see \Library\Entity}
	 * @return boolean
	 */
	
	public function isError(){
		return count($this->errors) != 0;
	}
	
	/**
	 * Add an error at the end of the list of error
	 * 
	 * @param String $pVal
	 *				The error value
	 */
	public function setError($pVal) {
		if(!in_array($pVal, $this->errors)){
			$this->errors[] = $pVal;
		}
	} 
	
	/**
	 * set the error list to empty list
	 */
	public function errorsInit(){
		$this->errors = array();
	}
	
	/**
	 * Give back the last error
	 * @return String
	 */
	public function lastError(){
		return $this->errors[(count($this->errors)-1)];
	}
	
	//Setter de l'objet
	
	/**
	* Function that hydrate the {@see \Library\Entity}
	* 
	* For each data in the parameter, the function try to set the data
	* in a coresponding parameter of the {@see \Library\Entities\}
	* 
	* @params $data
	* 			An array where all the key should be a parameter name
	* 			and all the value are the value that we want for this
	* 			parameters.
	* 
	* @return boolean
	* 		Whether or not all the value have been set.
	* 		If false, then the error of insertion are in the {@see \Library\Entity::$errors}
	* 
	* @throws \RuntimeException
	* 		If we try to set a value on a parameter that don't exist or that doesn't have his own
	* 		setter.
	*/
	public function hydrate(array $data){
		
		$nbError = count($this->errors());
		
		$obj = new \ReflectionObject($this);
		
		foreach($data AS $key=>$value) {
			$method = 'set' . ucfirst($key);
			if ($obj->hasProperty($key) || $obj->hasMethod($method))
				$this->$method($value);
		}
		
		return $this->errors() == $nbError;
	}
	
	
	/**
	 * Setter for the CheckTime
	 * Try to add a new value to the checkTime. If the value is a number or a string,
	 * we try to create a new \DateTime
	 * 
	 * @see \DateTime
	 * 
	 * @param string|numeric|\DateTime|mixed $pVal
	 * @return number
	 */
	public function setCheckTime($pVal) {
		if (is_string($pVal) || is_numeric($pVal)) {
			$this->checkTime = @new \DateTime($pVal);
		} elseif ($pVal instanceof \DateTime) {
			$this->checkTime = $pVal;
		}
		return 1;
	}
	
	/**
	 * Give the current checkTime
	 * Try to get the CheckTime. If the checktime exist but is in a bad format, try to create a
	 * new \DateTime with his parameter
	 * 
	 * @see \DateTime
	 * 
	 * @return \DateTime|NULL
	 */
	public function checkTime() {
		if (isset($this->checkTime))
			if (!($this->checkTime instanceof \DateTime))
				$this->checkTime = @new \DateTime($this->dateTime);
		return $this->checkTime;
	}
	
	/**
	 * Generic setter
	 * Used when we try to use an attribute like a public attribute and not using the
	 * setValue() provided. It will just call the setter.
	 * 
	 * @param string $name
	 * @param mixed $val
	 * 
	 * @return int
	 */
	public function __set($name, $val) {
		$methName = "set" . ucfirst($name);
		
		return $this->$methName($val);
	}
	
	/**
	 * Generic Getter used when try to get the value of a protected attribute instead of using
	 * the provided get function. It will just call the getter.
	 * 
	 * @param unknown $name
	 * 			The name of the attribute
	 * 
	 * @return mixed
	 * 		The value of the data
	 */
	public function __get($name) {
		return $this->$name();
	}
	
	/**
	 * This magic method is used to avoid the creation of all the getter and all the setter of the Entities
	 * 
	 * Instead of creating all the setter and getter, each time we try to set/get aa attribute, the
	 * application will get the DataTyper of the Entity, check if the provided attribute is in the DataType
	 * and if yes if the different attribute are working.
	 * 
	 * In the case of the setter, the application will check if the attribute exist and if the different data provided in
	 * the parameter match with the type of the attribute. Then it will set the value to the attribute.
	 * 
	 * In the case of the getter, the application will check if the attribute exist and if the data already has a value. If
	 * yes, then it return the value. If the attribute exist but it has no value, then the application will return a generic
	 * value (default value of the attribute in the DataType or generic default value).
	 * 
	 * @method int set[Attribute]($value) setter for the different attribute given the {@see \DataTyper_Manager} of the model
	 * @method mixed [attribute]() getter for the different attribute given the {@see \DataTyper_Manager} of the model
	 * 
	 * @param string $name
	 * @param mixed[] $pVal
	 * 
	 * @throws \RuntimeException
	 * 				Throw an exception if the argument dosen't exist in the DataType or in the object.
	 * 				An other case of throwing such exception is when it is not a set neither a get.
	 * 
	 * @return number|mixed
	 * 				The used object if set is used. It could be used to link the set properties. If a get
	 * 				is used, the needed value (registred in the object or by default)
	 */
	public function __call($name, $pVal) {
		
		$setter = false;
		$varName = $name;
		
		if (strtolower(substr($name, 0, 3)) == "set" && count($pVal) != 0) {
			$setter = true;
			$varName = lcfirst(substr($name, 3));
		} elseif (substr($name, 0, 3) != "set" && (count($pVal) == 0 || (count($pVal) == 1 && isset($pVal["cst"])))) {
			$varName = $name;
		} else {
			if (\Library\Application::appConfig()->getConst("LOG")) {
				throw new \RuntimeException("Error ID: " . \Library\Application::logger()->log("Error", "Entity", "Call to undefined method " . get_class($this) . "::" . $name . "()", __FILE__, __LINE__));
			} else {
				throw new \RuntimeException("Call to undefined method " . get_class($this) . "::" . $name . "() in " . __FILE__ . " on line " . __LINE__);
			}
		}
		
		if (property_exists($this, $varName)) {
			
			$class = new \ReflectionClass($this);
			$classPart = explode("\\", $class->getName());
			
			$info = \Library\DataTyper::getDataType($classPart[count($classPart)-3], $classPart[count($classPart)-1]);
			
			if ($info != null && key_exists($varName, $info)) {
				$info = $info[$varName];
					
				$type = $info["type"];
				
				if (strpos($type, "varchar") !== FALSE) {
					$type = "str";
					$default = "";
				} elseif($type == "text") {
					$type = "str";
					$default = "";
				} elseif($type == "tinyint(1)") {
					$type = "int";
					$default = 0;
				} elseif (strpos($type, "int") !== FALSE) {
					if (strpos($varName, "_id") !== FALSE || $varName == "id") {
						$type = "id";
						$default = 0;
					} else {
						$type = "int";
						$default = -1;
					}
				} elseif ($type == "datetime" | $type = "date") {
					$type = "date";
					$default = new \DateTime();
				} elseif (strpos($type, "enum") !== FALSE) {
					$type = "enum";
					$listeData = array();
					$listeData = explode("', '", substr($type, 6, -2));
					
					if (count($listeData)) {
						$default = $listeData[0];
					} else {
						if (\Library\Application::appConfig()->getConst("LOG")) {
							throw new \RuntimeException("Error ID: " . \Library\Application::logger()->log("Error", "Entity", "No data allowed for the propreties " . $varName . " - Type " . $type, __FILE__, __LINE__));
						} else {
							throw new \RuntimeException("No data allowed for the propreties " . $varName . " - Type " . $type);
						}
					}
				
				} else {
					$type = "str";
					$default = "";
				}
				
				if ($info["null"] == "YES") {
					$default = null;
				}
				
				if ($info["default"] != null) {
					$default = $info["default"];
				}
				
				$constName = "INVALID_".strtoupper($varName);
				
				$myClass = new \ReflectionClass($this);
				
				if ($myClass->hasConstant($constName)) {
					$necessary = true;
					$error_code = $myClass->getConstant($constName);
				} else {
					$necessary = false;
				}
				
				if ($setter) { //TODO ensure that it work with the $this return, excpecially with the parent::__call used for example in \Library\Entities\user
					if ($default === NULL && $pVal[0] == NULL) {
						$this->$varName = null;
						return $this;
					}
					
					switch ($type) {
						case "int":
							if (count($pVal) == 1 && is_numeric($pVal[0]) && (!empty($pVal[0]) || $pVal[0] === '0' || $pVal[0] === 0)) {
								$this->$varName = $pVal[0];
								return $this;
							}
							break;
						case "id":
							if (count($pVal) == 1 && is_numeric($pVal[0]) && (!empty($pVal[0]) || $pVal[0] === 0 || $pVal[0] === '0') && $pVal[0] > -1) {
								$this->$varName = $pVal[0];
								return $this;
							}
							break;
						case "date":
							if (count($pVal) == 3 && is_numeric($pval[0]) && is_numeric($pVal[1]) && is_numeric($pVal[2]) && $pVal[0] > 0 && $pVal[0] <= 31 && $pVal[1] > 0 && $pVal[1] <= 12) {
								try {
									$this->$varName = \DateTime::createFromFormat("d-m-Y", $pVal[0]."-".$pVal[1]."-".$pVal[2]);
								} catch (\Exception $e) {
								}
							} elseif (count($pVal) == 1)
								if (is_string($pVal[0])) {
									try {
										$info = \Utils::getDateFormat(self::$app->user()->getLanguage());
										$this->$varName = \DateTime::createFromFormat($info[1], $pVal[0]);
										if ($this->$varName == false)
											throw new \InvalidArgumentException("Data format not valid");
									} catch (\Exception $e) {
										try {
											$this->$varName = new \DateTime($pVal[0]);
										} catch (\Exception $e) {
										}
									}
									return $this;
								} elseif ($pVal[0] instanceof \DateTime) {
									$this->$varName = $pVal[0];
									return $this;
								}
							break;
						case "enum":
							if (count($pVal) == 1 && in_array($pVal[0], $listeData)) {
								$this->$varName = $pVal[0];
								return $this;
							}
							break;
						case "str":
						default:
							if (count($pVal) == 1 && is_string($pVal[0]) && !empty($pVal[0])) {
								$this->$varName = \Utils::protect($pVal[0]);
								return $this;
							}
					}
					
					if ($necessary) {
						$this->setError($error_code);
						return $this;
					} else {
						$this->$varName = $default;
						return $this;
					}
				} else {
					if (isset($this->$varName)) {
						if ($type == "enum" && !in_array($this->$varName, $listeData)) {
							return $listeData[0];
						} elseif ($type == "date" && !($this->$varName instanceof \DateTime)) {
							$date = new \DateTime($this->$varName);
							
							if (!($date instanceof \DateTime))
								$date = new \DateTime();
							
							$this->$varName = $date;
						} elseif (substr($varName, 0, 3) == "cst" && (!key_exists("cst", $pVal) || $pVal["cst"] == 0)) {
							if (defined($this->$varName)) 
								return constant($this->$varName);
							
							if (($langVal = self::$app->language()->get($this->$varName, self::$app->httpRequest()->languageUser())) != null)
								return $langVal;
							
						}
						return $this->$varName;
					} else {
						return $default;
					}
				}
			} else {
				if (\Library\Application::appConfig()->getConst("LOG")) {
					throw new \RuntimeException("Error ID: " . \Library\Application::logger()->log("Error", "Entity", "The parameters [" . $varName . "] is not in the BDD representation of the Entity", __FILE__, __LINE__));
				} else {
					throw new \RuntimeException("The parameters [" . $varName . "] is not in the BDD representation of the Entity");
				}
			}
			
		} else {
			if (\Library\Application::appConfig()->getConst("LOG")) {
				throw new \RuntimeException("Error ID: " . \Library\Application::logger()->log("Error", "Entity", "The properties " . $varName . " is not part of the Entity [" . get_class($this) . "] or the Entity is not in the BDD representation", __FILE__, __LINE__));
			} else {
				throw new \RuntimeException("The properties " . $varName . " is not part of the Entity [" . get_class($this) . "] or the Entity is not in the BDD representation");
			}
			
		}
	}
}

?>