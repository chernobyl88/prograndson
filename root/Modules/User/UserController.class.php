<?php

namespace Modules\User;

if (!defined("EVE_APP"))
	exit();

class UserController extends \Library\BackController {
	
	public function executeIndex(\Library\HTTPRequest $request) {
		
	}
	
	public function executeListe(\Library\HTTPRequest $request) {
		$userManager = $this->managers()->getManagersOf("user"); 
		
		$listeUser = $userManager->getList(array("validated = 1"));
		
		foreach ($listeUser AS $u) {
			$u->setAttribute($userManager->getAttribute($u->id(), "has_payed"));
		}
		
		$this->page()->addVar("listeUser", $listeUser);
		
		$this->page()->addVar("myUser", $userManager->get($this->app()->user()->id()));
	}
	
	public function executeUser(\Library\HTTPRequest $request) {
		if ($request->existGet("id") && !(is_numeric($request->dataGet("id"))))
			$this->app()->httpResponse()->redirect404();
		
		if ($this->app()->user()->id() == $request->dataGet("id"))
			$this->app()->httpResponse()->redirect403();
		
		$userManager = $this->managers()->getManagersOf("user");
		$groupeManager = $this->managers()->getManagersOf("groupe");
		
		$user = null;
		
		if ($request->existGet("id") && is_numeric($request->dataGet("id")))
			$user = $userManager->get($request->dataGet("id"));
		
		if ($user == null || !($user instanceof \Library\Entities\user))
			$user = new \Library\Entities\user();
		
		$user->setAttr($userManager->getUserAttribute($user->id()));
		
		$parentGroupe = $groupeManager->getFromConst("NORMAL_USER");
		if ($parentGroupe == null)
			$parentGroupe = new \Library\Entities\groupe();
		
		$parentGroupeAdmin = $groupeManager->getFromConst("ADMIN_GROUPE");
		if ($parentGroupeAdmin == null)
			$parentGroupeAdmin = new \Library\Entities\groupe();
		
		$this->app()->httpRequest()->addTransfert("user_id", $user->id());
		
		$groupeInfo = $this->getOtherModuleInformation("Presentation", "listePres");
		$pres = (key_exists("liste_pres", $groupeInfo)) ? $groupeInfo["liste_pres"] : array();
		$inPres = (key_exists("liste_in_pres", $groupeInfo)) ? array_map(function ($arg) {return $arg->id();},$groupeInfo["liste_in_pres"]) : array();
		$cate = (key_exists("liste_cate", $groupeInfo)) ? $groupeInfo["liste_cate"] : array();

		$this->page()->addVar("listeCategorie", $cate);
		
		$this->page()->addVar("listeGroupe", $pres);
		$this->page()->addVar("listeAdmin", $groupeManager->getList(array("parent_id = :pId"), array(array("key" => ":pId", "val" => $parentGroupeAdmin->id(), "type" => \PDO::PARAM_INT))));
		
		$this->page()->addVar("listeInGroupe", $inPres);
		$this->page()->addVar("listeInAdmin", $this->managers()->getManagersOf("user_groupe")->getList(array("user_id = :pId"), array(array("key" => ":pId", "val" => $user->id(), "type" => \PDO::PARAM_INT))));
		
		$this->page()->addVar("cUser", $user);
	}
	
	public function executeDelete(\Library\HTTPRequest $request) {
		if (!($request->existPost("id") && is_numeric($id = $request->dataPost("id"))))
			$this->app()->httpResponse()->redirect403();
		
		$userManager = $this->managers()->getManagersOf("user");
		
		$user = $userManager->get($id);
		
		$user->setValidated(0);
		$user->setLogin(rand() . "_" . $user->login());
		
		$userManager->send($user);		
		
		$this->page()->addVar("valid", 1);
		$this->page()->setIsJson();
	}
	
	public function executeSend(\Library\HTTPRequest $request) {
		/*
		 * $_GET["userType"] contain the type of the user:
		 * 0 => new user that participate to image contest
		 * 1 => new user created by administrator
		 * 		The group list has to be send by client using HTTP POST in a variable called listeGroupe
		//*/
		
		$userManager = $this->managers()->getManagersOf("user");
		$groupeManager = $this->managers()->getManagersOf("groupe");
		
		$valid = 0;
		$error = array();
		
		$listeGroupe = array();
		
		if ($request->existGet("userType"))
			switch ($request->dataGet("userType")) {
				case 0:
					$listeGroupe[] = (count($group = $groupeManager->getList(array())) > 0) ? $group[0]->id() : -1;
					break;
				case 1:
					if ($this->app()->user()->getAdminLvl() > 0)
						$listeGroupe = array_filter((is_array($listeGroupe = $request->dataPost("listeGroupe")) ? $listeGroupe : array()), function ($a) {return is_numeric($a);});
					else
						$error[] = NOT_ALLOWED_USER_ACCES;
					break;
				default:
					$error[] = NOT_ALLOWES_USER_TYPE;
			}
		else
			$error[] = NO_USER_TYPE;
		
		if (count($error) == 0) {
			$listeRequire = array(
				"login",
				"password",
				"civilite",
				"prenom",
				"nom",
				"email",
				"localite",
				"code_postal"
			);
			
			foreach ($listeRequire AS $req)
				if (!($request->existPost($req) && $request->dataPost($req) != ""))
					$error[] = (defined($e = "INVALID_" . strtoupper($req))) ? constant($e) : $e;
	
			if ($request->dataPost("password") != $request->dataPost("conf"))
					$error[] = (defined($e = "INVALID_CONFIRM_PASSWORD")) ? constant($e) : $e;
			
			if (!\Utils::testEmail($request->dataPost("email")))
				$error[] = (defined($e = "INVALID_EMAIL")) ? constant($e) : $e;
			
			if (!((($request->existPost("rue") && $request->dataPost("rue") != "") && ($request->existPost("no_rue") && $request->dataPost("no_rue") != "")) || ($request->existPost("case_postale") && $request->dataPost("case_postale") != "")))
				$error[] = (defined($e = "INVALID_STREET_POSTAL")) ? constant($e) : $e;
		}
		
		if (count($error) == 0) {
			$user = null;
			if ($request->existGet("id"))
				$user = $userManager->get($request->dataGet("id"));
			
			if (!($user instanceof \Library\Entities\user))
				$user = new \Library\Entities\user();
			
			if (!($user->id() > 0 || $request->dataPost("password") != ""))
				$user->setError(\Library\Entities\user::INVALID_PASSWORD);
			
			$user->hydrate($_POST);
			$user->validation_code();
			
			if (!$this->app()->user()->getAdminLvl() > 0)
				$user->setValidated(0);
			
			if (!$user->isError()) {
				
				$checkLogin = $userManager->getList(array("login = :pLogin", "id != :pId"), array(array("key" => ":pId", "val" => $user->id(), "type" => \PDO::PARAM_INT), array("key" => ":pLogin", "val" => $user->login, "type" => \PDO::PARAM_STR)));
				
				if (count($checkLogin) == 0) {
					$userManager->send($user);
					
					if ($user->id() > 0) {
						$listeAdresse = $this->managers()->getManagersOf("adresses")->getList(array("user_id = :pId"), array(array("key" => ":pId", "val" => $user->id(), "type" => \PDO::PARAM_INT)));
						
						if (count($listeAdresse))
							$adresse = $listeAdresse[0];
						else
							$adresse = new \Library\Entities\adresses();
						
						unset($_POST["id"]);
						$adresse->hydrate($_POST);
						
						$this->managers()->getManagersOf("adresses")->send($adresse);
						
						$this->managers()->getManagersOf("user_groupe")->deleteList(array("user_id = :pId"), array(array("key" => ":pId", "val" => $user->id(), "type" => \PDO::PARAM_INT)));
						
						foreach ($listeGroupe AS $groupeId)
							$this->managers()->getManagersOf("user_groupe")->send(new \Library\Entities\user_groupe(array("groupe_id" => $groupeId, "user_id" => $user->id())));
						


						$mail = $this->app()->mailer();
							
						$mail->addReciever($user->email());
						$mail->setFile(__DIR__ . "/Mail/inscrMail_user.html");
						
						$mail->addFileValue("CONSTANT_LOGIN", $user->login());
						$mail->addFileValue("CONSTANT_NAME", $user->nom());
						$mail->addFileValue("CONSTANT_PRENOM", $user->prenom());
						$mail->addFileValue("CONSTANT_EMAIL", $user->email());
						$mail->addFileValue("CONSTANT_MDP", $request->dataPost("password"));
						$mail->addFileValue("CONSTANT_URL", $this->page()->getVar("rootLang") . "/User/Valid/" . $user->id() . "/" . $user->validation_code() . "/");
							
						$mail->setSubject("Confirmation d'inscription");
						$mail->sendMail();
						
						$valid = 1;
					} else
						$error[] = ERROR_ON_INSERTING_USER;
				} else 
					$error[] = ERROR_USED_LOGIN;
			} else
				$error = $user->errors();
			
		} else
			$error = $error;
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $error);
		
		$this->page()->setIsJson();
	}
	
	public function executeMe(\Library\HTTPRequest $request) {
		$this->page()->addVar("me", $this->managers()->getManagersOf("user")->get($this->app()->user()->id()));
	}
	
	public function executeSendMe(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		if (!($request->existPost("old_pswd") && $request->existPost("nom") && $request->existPost("prenom") && $request->existPost("email") && $request->existPost("password") && $request->existPost("conf")))
			$message[] = ERROR_ON_RETRIEVING_DATA;
		else {
			$userManager = $this->managers()->getManagersOf("user");
			
			$user = $userManager->get($this->app()->user()->id());
			
			if (\Utils::hash($request->dataPost("old_pswd"), $user->password()) == $user->password()) {
				
				if ($request->dataPost("conf") == $request->dataPost("password_conf"))
					$message[] = ERROR_ON_CONFIRM_PASS;
				
				$user->hydrate($_POST);
				
				if ($user->isError()) {
					$reflexion = new \ReflectionClass($user);
					
					foreach ($user->errors() AS $e)
						foreach ($reflexion->getConstants() AS $cst => $val)
							if ($e == $val)
								$message[] = (defined($cst)) ? constant($cst) : $cst;
				} else {
					$userManager->send($user);
					$valid = 1;
				}
			} else
				$message[] = ERROR_ON_OLD_PASSWORD;
		}

		$this->page()->addVar("message", $message);
		$this->page()->addVar("valid", $valid);
		
		$this->page()->setIsJson();
	}
	
	public function executeValidate(\Library\HTTPRequest $request) {
		if (!(is_numeric($request->dataGet("user_id"))))
			$this->app()->httpResponse()->redirect403();
		
		$listeUser = $this->managers()->getManagersOf("user")->getList(array("validation_code = :pCode", "id = :pId"), array(array("key" => ":pCode", "val" => $request->dataGet("user_code"), "type" => \PDO::PARAM_STR), array("key" => ":pId", "val" => $request->dataGet("user_id"), "type" => \PDO::PARAM_INT)));
		$valid = 0;
		$message = "";
		
		if (count($listeUser)) {
			$user = $listeUser[0];
			if ($user->validated() != 1) {
				$user->setValidated(1);
				
				$this->managers()->getManagersOf("user")->send($user);
				
				try {
					$mail = $this->app()->mailer();
					
					if (!$mail->addReciever($user->email()))
						throw new \InvalidArgumentException("User e-mail not valid");
					
					if (!$mail->setFile(__DIR__ . "/Mail/confMail_user.html"))
						throw new \InvalidArgumentException("User file not valid");
							
					if (!$mail->setSubject("Validation de votre inscription"))
						throw new \InvalidArgumentException("User subject not valid");
					
					if (!$mail->sendMail())
						throw new \InvalidArgumentException("Error on sending user e-mail");
					
					$valid = 1;
				} catch (\Exception $e) {
					$message = $e->getMessage();
				}	
			} else
				$valid = 1;
		} else {
			$message = "Aucun compte ne correspond à ces informations";
				
		}

		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
	}
}

?>