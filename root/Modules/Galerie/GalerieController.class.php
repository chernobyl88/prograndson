<?php

namespace Modules\Galerie;

if (!defined("EVE_APP"))
	exit();

class GalerieController extends \Library\BackController {
	public function executeIndex(\Library\HTTPRequest $request) {
		if (!($request->existGet("gal_id") && is_numeric($gal_id = $request->dataGet("gal_id"))))
			$gal_id = 0;
		
		$this->page()->addVar("gal", array_filter($this->managers()->getManagersOf("main")->getList(array("parent_id = :pId", "visible = 1"), array(array("key" => ":pId", "val" => $gal_id, "type" => \PDO::PARAM_INT))), function ($a) {
			return $a->setBg_img_id((count($liste_img = $this->managers()->getManagersOf("main_file")->getList(array("galerie_main_id = :pId", "accepted = 1"), array(array("key" => ":pId", "val" => $a->id(), "type" => \PDO::PARAM_INT)))) > 0) ? $liste_img[rand(0, (count($liste_img)-1))]->file_id() : 0);
		}));
		
		$this->page()->addVar("galerie", $this->managers()->getManagersOf("main")->get($gal_id));
		
		$managers = $this->managers();
		$this->page()->addVar("img", $this->managers()->getManagersOf("main_file")->getList(array("galerie_main_id = :pId", "accepted = 1"), array(array("key" => ":pId", "val" => $gal_id, "type" => \PDO::PARAM_INT))));
	}
	
	public function executeGetList(\Library\HTTPRequest $request) {
		if (!($request->existGet("gal_id") && is_numeric($gal_id = $request->dataGet("gal_id"))))
			$gal_id = 0;
		
		if ($request->existGet("concours"))
			$concours = true;
		else
			$concours = false;
		
		$this->page()->addVar("gal", array_filter($this->managers()->getManagersOf("main")->getList(array("parent_id = :pId", "concours = " . (($concours) ? 1 : 0)), array(array("key" => ":pId", "val" => $gal_id, "type" => \PDO::PARAM_INT))), function ($a) {
			return $a->setNbr_sub_gal(count($this->managers()->getManagersOf("main")->getList(array("parent_id = :pId"), array(array("key" => ":pId", "val" => $a->id(), "type" => \PDO::PARAM_INT)))));
		}));
		
		$this->page()->addVar("concours", $concours);
		$this->page()->addVar("parent_id", $gal_id);
	}
	
	public function executeGetListWInner(\Library\HTTPRequest $request) {
		$listeWinner = $this->managers()->getManagersOf("concours_result")->getListLastConcoursWinnerId(($request->existTransfert("pNum")) ? $request->existTransfert("pNum") : 1);
		
		foreach ($listeWinner AS $winner) {
			$winner->setMain_file($this->managers()->getManagersOf("main_file")->get($winner->galerie_main_file_id()));
			
			$winner->setGalerie($this->managers()->getManagersOf("main")->get($winner->galerie_main_id()));
		}
		
		$this->page()->addVar("listeWinner", $listeWinner);
	}
	
	public function executeResult(\Library\HTTPRequest $request) {
		if ($request->existGet("main_id")) {
			$concours = $this->managers()->getManagersOf("main")->get($request->dataGet("main_id"));
			
			if (is_null($concours) || !($concours instanceof \Modules\Galerie\Entities\main) || $concours->concours() == 0 || $concours->show_result() == 0)
				$this->app()->httpResponse()->redirect403();
			
			$listeWinner = $this->managers()->getManagersOf("concours_result")->getList(array("galerie_main_id = :pId"), array(array("key" => ":pId", "val" => $request->dataGet("main_id"), "type" => \PDO::PARAM_INT)), array(array("key" => "rang", "order" => "ASC")));
			
			foreach ($listeWinner AS $winner) {
				$winner->setMain_file($this->managers()->getManagersOf("main_file")->get($winner->galerie_main_file_id()));
				$winner->main_file()->setFile($this->managers()->getManagersOf("file")->get($winner->main_file()->file_id()));
				
				if ($winner->rang() == 1) {
					$winner->main_file()->file()->setUser($this->managers()->getManagersOf("user")->get($winner->main_file()->file()->user_id()));
				}
			}
			$this->page()->addVar("listeWinner", $listeWinner);
			$this->page()->addVar("concours", $concours);
		} else {
			$this->page()->addVar("gal", array_filter($this->managers()->getManagersOf("main")->getList(array("concours = 1", "show_result = 1")), function ($a) {
				return $a->setBg_img_id((count($liste_img = $this->managers()->getManagersOf("main_file")->getList(array("galerie_main_id = :pId", "accepted = 1"), array(array("key" => ":pId", "val" => $a->id(), "type" => \PDO::PARAM_INT)))) > 0) ? $liste_img[rand(0, (count($liste_img)-1))]->file_id() : 0);
			}));
			$this->page()->addVar("title", "Résultats");
			
			$this->setView("baseResult", "Galerie");
		}
	}
	
	public function executeSend(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		$galerie = null;
		if (is_numeric($gId = $request->dataPost("id")))
			$galerie = $this->managers()->getManagersOf("main")->get($gId);
		
		if (!($galerie instanceof \Modules\Galerie\Entities\main))
			$galerie = new \Modules\Galerie\Entities\main(array("parent_id" => (($request->existPost("parent_id")) ? $request->dataPost("parent_id") : 0), "user_id" =>$this->app()->user()->id(), "date_crea" => new \DateTime()));
		
		$galerie->hydrate($_POST);
		
		if (!$request->existPost("visible"))
			$galerie->setVisible(0);
		
		if ($request->existPost("show_result"))
			$galerie->setDate_result(new \DateTime());
		else 
			$galerie->setShow_result(0);
		
		if (!$galerie->isError()) {
			if ($galerie->nom() != "") {
				$this->managers()->getManagersOf("main")->send($galerie);
				
				if ($galerie->id() > 0) {
					$valid = 1;
					$this->page()->addVar("id", $galerie->id);
				} else
					$message[] = "Error on inserting data";
				
			} else {
				$message[] = "Le nom est obligatoir";
			}
		} else {
			foreach ($galerie->errors() AS $e)
				$message[] = (defined($e)) ? constant($e) : $e;
		}

		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		
		$this->page()->setIsJson();
	}
	
	public function executeModif(\Library\HTTPRequest $request) {
		if (!is_numeric($gal_id = $request->dataGet("id")))
			$this->app()->httpResponse()->redirect404();
		
		if ($request->existGet("concours"))
			$concours = true;
		else
			$concours = false;
		
		$galerie = $this->managers()->getManagersOf("main")->get($gal_id);
		
		if (!($galerie instanceof \Modules\Galerie\Entities\main && $galerie->id() > 0))
			$this->app()->httpResponse()->redirect404();
		
		$parent = array();
		
		$son = $galerie;
		while ($son->parent_id() != 0 && !in_array($son, $parent)) {
			$parent[] = ($son = $this->managers()->getManagersOf("main")->get($son->parent_id()));
		}
		$managers = $this->managers();
		$files = array_map(function ($a) use ($managers) {
			return array("gal_img" => $a,
					"file" => ($f = $managers->getManagersOf("file")->get($a->file_id())),
					"user" => $managers->getManagersOf("user")->get($f->user_id()),
					"groupe" => $managers->getManagersOf("groupe")->get($a->galerie_groupe_id()),
					"rank" => (count($ranks = $managers->getManagersOf("concours_result")->getList(array("galerie_main_file_id = :pId"), array(array("key" => ":pId", "val" => $a->id(), "type" => \PDO::PARAM_INT)))) > 0) ? $ranks[0] : new \Modules\Galerie\Entities\concours_result(),
			);
			}, $this->managers()->getManagersOf("main_file")->getList(array("galerie_main_id = :pId"), array(array("key" => ":pId", "val" => $gal_id, "type" => \PDO::PARAM_INT))));
		
		$this->page()->addVar("concours", $concours);
		$this->page()->addVar("galerie", $galerie);
		$this->page()->addVar("files", $files);
		$this->page()->addVar("parent", $parent);
		$this->page()->addVar("listeGroupe", $this->managers()->getManagersOf("groupe")->getList());
	}
	
	public function executeAddImage(\Library\HTTPRequest $request){
		$valid = 0;
		$message = array();
		
		if (!is_numeric($gal_id = $request->dataPost("galerie_id")) || !($galerie = $this->managers()->getManagersOf("main")->get($gal_id)) instanceof \Modules\Galerie\Entities\main)
			$message[] = "ID de la galerie non valide";
		else {	
			if (!($this->app()->user()->getAdminLvl() > 0 || ($galerie->visible() && $galerie->concours() && $galerie->date_deb() <= ($cDate = new \DateTime()) && $galerie->date_fin() >= $cDate)))
				$message[] = "Vous n'êtes pas autorisé à ajouter une image dans cette section";
			else {
				if (!is_array($listeId = $request->dataPost("listeId")))
					$message[] = "La liste d'image n'est pas valide";
				else {	
					if (!($request->existPost("groupe_id") && is_numeric($groupeId = $request->dataPost("groupe_id")) && $this->managers()->getManagersOf("groupe")->get($groupeId) instanceof \Modules\Galerie\Entities\groupe))
						$groupeId = 0;
					
					foreach ($request->dataPost("listeId") AS $fileId) {
						$file = $this->managers()->getManagersOf("file")->get($fileId);
						
						if ($request->existPost("user_id") && is_numeric($request->existPost("user_id")) && $this->app()->user()->getAdminLvl() > 5) {
							$file->setUser_id($request->dataPost("user_id"));
							$this->managers()->getManagersOf("file")->send($file);
						}
						
						$request->addTransfert("fileId", $fileId);
						$this->getOtherModuleInformation("Upload", "getMini");
						
						$this->managers()->getManagersOf("main_file")->send(new \Modules\Galerie\Entities\main_file(array("galerie_main_id" => $galerie->id(), "file_id" => $fileId, "galerie_groupe_id" => $groupeId)));
					}
					
					$valid = 1;
				}
			}
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeChangeImgName (\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (!is_numeric($fileId = $request->dataGet("fileId")) || !(($file = $this->managers()->getManagersOf("file")->get($request->dataGet("fileId"))) instanceof \Library\Entities\file))
			$message[] = "Error on getting file ID";
		else {
			if (!($file->user_id() == $this->app()->user()->id() || $this->app()->user()->getAdminLvl() > 5))
				$message[] = "You are not allowed to modify this image name";
			else {
				$oldName = $file->file_pub_name();
				$file->setFile_pub_name($request->dataPost("name"));
				
				if (($newName = $file->file_pub_name()) != "" && $newName != $oldName)
					$this->managers()->getManagersOf("file")->send($file);
				
				$valid = 1;
			}
		}	
			
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeRemoveImg (\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (!is_numeric($fileId = $request->dataGet("fileId")) || !(($mainFile = $this->managers()->getManagersOf("main_file")->get($request->dataGet("fileId"))) instanceof \Modules\Galerie\Entities\main_file))
			$message[] = "Error on getting file ID";
		else {
			if (!($file = $this->managers()->getManagersOf("file")->get($mainFile->file_id())) instanceof \Library\Entities\file)
				$message[] = "The file is not valid";
			else {
				if (!($file->user_id() == $this->app()->user()->id() || $this->app()->user()->getAdminLvl() > 5))
					$message[] = "You are not allowed to modify this image name";
				else {
					$this->managers()->getManagersOf("main_file")->delete($mainFile->id());
					$this->managers()->getManagersOf("file")->delete($file->id());
					
					@unlink($file->file_src() . ((substr($file->file_src(), 0, 1) != "/") ? "/": "") . $file->file_name());
					@unlink($file->file_src() . ((substr($file->file_src(), 0, 1) != "/") ? "/": "") . "min_" . $file->file_name());
					
					$valid = 1;
				}
			}
		}	
			
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeShowImg(\Library\HTTPRequest $request) {
		if (!(is_numeric($imgId = $request->dataGet("imgId")) && ($img = $this->managers()->getManagersOf("main_file")->get($imgId)) instanceof \Modules\Galerie\Entities\main_file))
			$this->app()->httpResponse()->redirect404();
		
		if (count($this->managers()->getManagersOf("visite")->getList(array("galerie_main_file_id = :pId", "ip_adresse = :pIp", "date_visite >= date_sub(NOW(), interval 1 hour)"), array(array("key" => ":pId", "val" => $img->id(), "type" => \PDO::PARAM_INT), array("key" => ":pIp", "val" => $this->app()->user()->getIP(), "type" => \PDO::PARAM_STR)))) == 0)
			$this->managers()->getManagersOf("visite")->send(new \Modules\Galerie\Entities\visite(array("date_visite" => new \DateTime(), "ip_adresse" => $this->app()->user()->getIP(), "galerie_main_file_id" => $img->id())));
		
		$this->page()->addVar("img", $img);
		$this->page()->addVar("file", ($file = $this->managers()->getManagersOf("file")->get($img->file_id())));
		$this->page()->addVar("author", $this->managers()->getManagersOf("user")->get($file->user_id()));
		$this->page()->addVar("groupe", ($img->galerie_groupe_id() > 0) ? $this->managers()->getManagersOf("groupe")->get($img->galerie_groupe_id()): null);
		$this->page()->addVar("visites", $this->managers()->getManagersOf("visite")->getList(array("galerie_main_file_id = :pId"), array(array("key" => ":pId", "val" => $img->id(), "type" => \PDO::PARAM_INT))));
		$votes = $this->managers()->getManagersOf("vote")->getList(array("galerie_main_file_id = :pId"), array(array("key" => ":pId", "val" => $img->id(), "type" => \PDO::PARAM_INT)));
		
		$this->page()->addVar("voteScore", (count($votes) == 0) ? 0 : (array_sum(array_map(function ($a) {return $a->note_total();}, $votes)) / count($votes)));
		$this->page()->addVar("votes", $votes);
		
		$this->page()->setNoTemplate();
	}
	
	public function executeVote(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (!(is_numeric($imgId = $request->dataGet("imgId"))))
			$message[] = "L'ID de l'image n'est pas valide";
		else {
			$vote = $request->dataPost("vote");
			if (is_numeric($vote)){
				if ($vote < 0)
					$vote = 0;
				elseif ($vote > 5)
					$vote = 5;
				
				if (count($this->managers()->getManagersOf("vote")->getList(array("galerie_main_file_id = :pId", "ip_adresse = :pIp"), array(array("key" => ":pId", "val" => $imgId, "type" => \PDO::PARAM_INT), array("key" => ":pIp", "val" => $this->app()->user()->getIP(), "type" => \PDO::PARAM_STR)))) == 0) {
					$this->managers()->getManagersOf("vote")->send(new \Modules\Galerie\Entities\vote(array("note_total" => $vote, "ip_adresse" => $this->app()->user()->getIP(), "date_vote" => new \DateTime(), "galerie_main_file_id" => $imgId)));
					$valid = 1;
				} else {
					$message[] = "Vous avez déjà voté pour cette image";
				}
			} else {
				$message[] = "Votre vote n'a pas une valeur valide";
			}
		}

		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeCg(\Library\HTTPRequest $request) {
		
	}
	
	public function executeParticiper(\Library\HTTPRequest $request) {
		$this->page()->addVar("concours", $this->managers()->getManagersOf("main")->getList(array("concours = 1", "date_deb < NOW()", "date_fin > NOW()", "visible = 1")));
		$this->page()->addVar("listeGroupe", $this->managers()->getManagersOf("groupe")->getList());
		$this->page()->addVar("listeUser", $this->managers()->getManagersOf("user")->getList(array(), array(), array(array("key" => "nom", "order" => "ASC"), array("key" => "prenom", "order" => "ASC"))));
	}
	
	public function executeValidSelect(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		if (is_numeric($mfId = $request->dataPost("id"))) {
			$mainFile = $this->managers()->getManagersOf("main_file")->get($mfId);
			
			if ($mainFile instanceof \Modules\Galerie\Entities\main_file) {
				switch ($request->dataPost("val")) {
					case 0:
					case 1:
						$mainFile->setAccepted($request->dataPost("val"));
						
						$this->managers()->getManagersOf("main_file")->send($mainFile);
						
						$valid = 1;
						break;
					default:
						$message[] = INVALID_VALUE;
				}
			} else
				$message[] = INVALID_ID;
		} else
			$message[] = INVALID_ID;
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeRankSelect(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		if (is_numeric($mfId = $request->dataPost("id"))) {
			$mainFile = $this->managers()->getManagersOf("main_file")->get($mfId);
			
			if ($mainFile instanceof \Modules\Galerie\Entities\main_file) {
				$this->managers()->getManagersOf("concours_result")->deleteList(array("galerie_main_file_id = :pId"), array(array("key" => ":pId", "val" => $mainFile->id(), "type" => \PDO::PARAM_INT)));
				
				switch ($request->dataPost("val")) {
					case 0:
						$valid = 1;
						break;
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
						$this->managers()->getManagersOf("concours_result")->send(new \Modules\Galerie\Entities\concours_result(array("galerie_main_id" => $mainFile->galerie_main_id(), "galerie_main_file_id" => $mainFile->id(), "rang" => $request->dataPost("val"))));
						$valid = 1;
						break;
					default:
						$message[] = INVALID_VALUE;
				}
			} else
				$message[] = INVALID_ID;
		} else
			$message[] = INVALID_ID;
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
}

?>