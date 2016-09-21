<?php

namespace Modules\News;

if (!defined("EVE_APP"))
	exit();

class NewsController extends \Library\BackController {
	public function executeIndex(\Library\HTTPRequest $request) {
		
		$this->page()->addVar("news", $this->managers()->getManagersOf("main")->getList(array("visible = 1"), array(), array(array("key" => "date_for", "order" => "desc")), 5));
		
		$info = $this->getOtherModuleInformation("Galerie", "getListWInner");
		
		$this->page()->addVar("listeWinner", (key_exists("listeWinner", $info)) ?  $info["listeWinner"]: array());
	}
	
	public function executeShow(\Library\HTTPRequest $request) {
		if (!($request->existGet("news_id") && is_numeric($request->dataGet("news_id"))))
			$this->app()->httpResponse()->redirect404();
		
		$news = $this->managers()->getManagersOf("main")->get($request->dataGet("news_id"));
		
		if (is_null($news) || !($news instanceof \Modules\News\Entities\main) || $news->id() < 1)
			$this->app()->httpResponse()->redirect403();
		
		$this->page()->addVar("news", $news);
	}
	
	public function executeListe(\Library\HTTPRequest $request) {
		$this->page()->addVar("news", $this->managers()->getManagersOf("main")->getList(array("visible = 1"), array(), array(array("key" => "date_for", "order" => "desc")), 5));
	}
	
	public function executeRemove(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (!($request->existGet("news_id") && is_numeric($request->dataGet("news_id"))))
			$this->message[] = ERROR_DATA;
		else {
			$this->managers()->getManagersOf("main")->delete($request->dataGet("news_id"));
			$valid = 1;
		}

		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeAdmin(\Library\HTTPRequest $request) {
		$managers = $this->managers();
		
		$this->page()->addVar("news", array_map(function ($a) use ($managers) {return array("new" => $a, "user" => $managers->getManagersOf("user")->get($a->user_id()));}, $this->managers()->getManagersOf("main")->getList()));
	}
	
	public function executeChangeVisibility(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (is_numeric($id = $request->dataPost("id"))) {
			$news = $this->managers()->getManagersOf("main")->get($id);
			
			if ($news instanceof \Modules\News\Entities\main) {
				switch ($vis = $request->dataPost("visibility")) {
					case 0:
					case 1:
						$news->setVisible($vis);
						
						$this->managers()->getManagersOf("main")->send($news);
						
						$valid = 1;
						break;
					default:
						$message[] = "La valeur de visibilité n'est pas valide";
				}
			} else
				$message[] = "L'ID n'est pas valide";
		} else
			$message[] = "Erreur à la réception de l'ID";
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeDelete(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (is_numeric($id = $request->dataPost("id"))) {
			$news = $this->managers()->getManagersOf("main")->get($id);
			
			if ($news instanceof \Modules\News\Entities\main) {
				$file = $this->managers()->getManagersOf("file")->get($news->file_id());
				
				if ($file instanceof \Library\Entities\file) {
					@unlink($file->file_src() . ((substr($file->file_src(), -1) != "/") ? "/": "") . $file->file_name());
					
					$this->managers()->getManagersOf("file")->delete($file->id());
				}
				
				$this->managers()->getManagersOf("main")->delete($news->id());
				$valid = 1;
			} else
				$message[] = "L'ID n'est pas valide";
		} else
			$message[] = "Erreur à la réception de l'ID";
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeAdd(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (($name = $request->dataPost("title")) != "") {
			if ($request->existPost("id")) {
				if (is_numeric($id = $request->dataPost("id")))
					$news = $this->managers()->getManagersOf("main")->get($id); 
			} else
				$news = new \Modules\News\Entities\main(array("user_id" => $this->app()->user()->id(), "visible" => 0, "date_crea" => new \DateTime()));
			
			if (isset($news) && $news instanceof \Modules\News\Entities\main) {

				if ($request->existPost("file_id") && $request->dataPost("id") != ($old_id = $news->file_id())) {
					$file = $this->managers()->getManagersOf("file")->get($old_id);
					if ($file instanceof \Library\Entities\file) {
						@unlink($file->file_src() . ((substr($file->file_src(), -1) != "/") ? "/" : "") . $file->file_name());
						$this->managers()->getManagersOf("file")->delete($file->id());
					}
				}
					
				$news->hydrate($_POST);
				
				$news->setVisible(($request->existPost("visible")) ? 1 : 0);
				
				if ($news->isError()) {
					$liste = new \ReflectionClass($news);
					
					foreach ($liste->getConstants() AS $key => $val)
						if (in_array($val, $news->errors()))
							$message[] = (constant($key)) ? defined($key) : $key;
				} else {
					
					$this->managers()->getManagersOf("main")->send($news);
					
					$this->page()->addVar("id", $news->id());
					$valid = 1;
				}
			} else
				$message[] = "L'ID n'est pas dans un format correcte";
		} else
			$message[] = "Vous devez inscrire un titre à votre news";
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeModif(\Library\HTTPRequest $request) {
		if (is_numeric($id = $request->dataGet("id"))) {
			$main = $this->managers()->getManagersOf("main")->get($id);
			
			if ($main instanceof \Modules\News\Entities\main) {
				$this->page()->addVar("news", $main);
			} else
				$this->app()->httpResponse()->redirect404();
		} else
			$this->app()->httpResponse()->redirect404();
	}
}

?>