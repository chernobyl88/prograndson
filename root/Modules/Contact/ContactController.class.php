<?php

namespace Modules\Contact;

if (!defined("EVE_APP"))
	exit();

class ContactController extends \Library\BackController{
	public function executeIndex(\Library\HTTPRequest $request){
	}
	
	public function executeSend(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		$error = array();
		
		if (!($request->existPost("nom") && $request->dataPost("nom") != "")) {
			$message[] = "Vous devez renseigner votre Nom";
			$error[] = "nom";
		}

		if (!($request->existPost("prenom") && $request->dataPost("prenom") != "")) {
			$message[] = "Vous devez renseigner votre Prénom";
			$error[] = "prenom";
		}
		
		if (!($request->existPost("email") && $request->dataPost("email") != "" && \Utils::testEmail($request->dataPost("email")))) {
			$message[] = "Votre E-Mail doit être dans un format correcte";
			$error[] = "email";
		}

		if (!($request->existPost("message") && $request->dataPost("message") != "")) {
			$message[] = "Vous devez inscrire un message";
			$error[] = "message";
		}
		
		if (!count($message)) {
			$mailer = $this->app()->mailer();
			try {
				$mailer->addReciever($this->app()->config()->get("BASE_EMAIL_TO"));
				
				if (!$mailer->setFile(__DIR__ . "/Mail/mailCtct.html"))
					throw new \RuntimeException("File not valid");
				
				$mailer->addFileValue("MAIL_USER_NOM", $request->dataPost("nom"));
				$mailer->addFileValue("MAIL_USER_PRENOM", $request->dataPost("prenom"));
				$mailer->addFileValue("MAIL_USER_EMAIL", $request->dataPost("email"));
				$mailer->addFileValue("MAIL_USER_MESSAGE", $request->dataPost("message"));
				
				$mailer->setSubject("Demande de contact");
				$mailer->setSender($request->dataPost("email"));
				
				if (!$mailer->sendMail())
					throw new \RuntimeException("Erreur à l'envois du message");
				$valid = 1;
			} catch (\Exception $e) {
				$message[] = $e->getMessage();
			}
		}
			
		$this->page()->setIsJson();
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->addVar("error", $error);
	}
}

?>