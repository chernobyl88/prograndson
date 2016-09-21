<?php

namespace Modules\Presentation;

if (!defined("EVE_APP"))
	exit();

class PresentationController extends \Library\BackController {
	
	private $listePresName;
	
	public function executeIndex(\Library\HTTPRequest $request) {
		$mainManager = $this->managers()->getManagersOf("main");
		
		$listePres = $mainManager->getListForUser($this->app()->user()->id());
		
		if (count($listePres) == 1)
			$this->app()->httpResponse()->redirect($this->page()->getVar("rootLang"). "/Presentation/" . $listePres[0]->id() . "/");
		elseif (count($listePres) == 0)
			$this->page()->addVar("valid", false);
		else {
			$this->page()->addVar("valid", true);
			$this->page()->addVar("listePres", $listePres);
		}
		
	}
	
	public function defaultPresItem($key, $val, $type = "elem", $presId = -1) {
		if (!isset($this->listePresName) || empty($this->listePresName))
			$this->listePresName = (count($val)) ? array_flip(array_map(function($p) {return ($p instanceof \Modules\Presentation\Entities\item) ? $p->name() : "";}, $val)) : array();
		
		$elem = (key_exists($key, $this->listePresName)) ? $val[$this->listePresName[$key]] : new \Modules\Presentation\Entities\item(array("item" => $type, "name" => $key, "val" => ""));
		
		if ($elem->item() == "text" && ($elem->key() == "" || (is_numeric($elem->key() && $elem->key() < 1))))
			if ($presId <= 0)
				throw new \InvalidArgumentException("Invalid presentation ID");
			else {
				$textManager = $this->managers()->getManagersOf("texte");
				$itemManager = $this->managers()->getManagersOf("item");
				
				$text = new \Modules\Presentation\Entities\texte();
				$textManager->send($text);

				$elem->setVal($text->id());
				$elem->setPresentation_main_id($presId);
				
				$elem->setTexte($text);
				
				$itemManager->send($elem);
			}
		elseif ($elem->item() == "date" && ($elem->key() == "" || (is_numeric($elem->key() && $elem->key() < 1))))
			if ($presId <= 0)
				throw new \InvalidArgumentException("Invalid presentation ID");
			else {
				$dateManager = $this->managers()->getManagersOf("date");
				$itemManager = $this->managers()->getManagersOf("item");
				
				$date = new \Modules\Presentation\Entities\date();
				$dateManager->send($date);

				$elem->setVal($date->id());
				$elem->setPresentation_main_id($presId);
				
				$elem->setDate($date);
				
				$itemManager->send($elem);
			}	
		
		return $elem;
	}

	public function executeCreate(\Library\HTTPRequest $request) {
		if (!$request->existGet("presentation_id") || empty($request->dataGet("presentation_id")) || is_numeric($request->dataGet("presentation_id")))
			$this->app()->httpResponse()->redirect404();
		
		$mainManager = $this->managers()->getManagersOf("main");
	
		$pres = $mainManager->get($request->dataGet("presentation_id"));
	
		if (empty($pres) || !($pres instanceof \Modules\Presentation\Entities\main && $pres->deleted() == 0))
			$this->app()->httpResponse()->redirect404();
	
		$listePres = $mainManager->getListForUser($this->app()->user()->id());
	
		if (!(in_array($pres->id(), array_map(function($p){return ($p instanceof \Modules\Presentation\Entities\main && $p->deleted() == 0) ? $p->id() : -1;}, $listePres)) || $this->app()->user()->getAdminLvl() >= 5))
			$this->app()->httpResponse()->redirect403();
	
		$itemManager = $this->managers()->getManagersOf("item");
		$itemManager->setDateManager($this->managers()->getManagersOf("date"));
		$listeItem = $itemManager->getFromPres($pres->id(), $this->managers()->getManagersOf("texte"));
	
		$slider = $this->defaultPresItem("slider", $listeItem, "list");
	
		$slider->setPresentation_main_id($pres->id());
	
		if ($slider->id() < 1)
			$itemManager->send($slider);
	
		$galerie = $this->defaultPresItem("galerie", $listeItem, "list");
		$galerie->setPresentation_main_id($pres->id());
	
		if ($galerie->id() < 1)
			$itemManager->send($galerie);
			
		$this->page()->addVar("presentation", $pres);
	
		$fileManager = $this->managers()->getManagersOf("file");
	
		$slider->setListe_elem(array_map(function ($arg) use ($fileManager) {return $fileManager->get($arg->key());}, $slider->liste_elem()));
		$galerie->setListe_elem(array_map(function ($arg) use ($fileManager) {return $fileManager->get($arg->key());}, $galerie->liste_elem()));
	
		$listeCity = array();
	
		$listeDay = array("MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY", "SUNDAY");
	
		$horraire = $this->defaultPresItem("horraire", $listeItem, "list");
	
		if ($horraire->id() < 1) {
			$horraire->setPresentation_main_id($pres->id());
			$itemManager->send($horraire);
		}
	
		foreach ($listeDay AS $day) {
			$filtred = array_filter($horraire->liste_elem(), function ($h) use ($day) { return $h->name() == $day;});
				
			if (count($filtred) == 0) {
				$filtred = new \Modules\Presentation\Entities\item(array("presentation_main_id" => $horraire->presentation_main_id(), "name" => $day, "item" => "list", "liste_id" => $horraire->id()));
	
				$itemManager->send($filtred);
	
				$listeOpen = new \Modules\Presentation\Entities\item(array("name" => "slot", "presentation_main_id" => $horraire->presentation_main_id(), "item" => "list", "liste_id" => $filtred->id()));
	
				$itemManager->send($listeOpen);
	
				$deb = new \Modules\Presentation\Entities\item(array("name" => "deb", "presentation_main_id" => $horraire->presentation_main_id(), "item" => "list", "liste_id" => $listeOpen->id()));
				$fin = new \Modules\Presentation\Entities\item(array("name" => "fin", "presentation_main_id" => $horraire->presentation_main_id(), "item" => "list", "liste_id" => $listeOpen->id()));
	
				$itemManager->send($deb);
				$itemManager->send($fin);
	
				$deb_h = new \Modules\Presentation\Entities\item(array("name" => "h", "presentation_main_id" => $horraire->presentation_main_id(), "item" => "elem", "liste_id" => $deb->id()));
				$deb_m = new \Modules\Presentation\Entities\item(array("name" => "m", "presentation_main_id" => $horraire->presentation_main_id(), "item" => "elem", "liste_id" => $deb->id()));
	
				$itemManager->send($deb_h);
				$itemManager->send($deb_m);
	
				$fin_h = new \Modules\Presentation\Entities\item(array("name" => "h", "presentation_main_id" => $horraire->presentation_main_id(), "item" => "elem", "liste_id" => $fin->id()));
				$fin_m = new \Modules\Presentation\Entities\item(array("name" => "m", "presentation_main_id" => $horraire->presentation_main_id(), "item" => "elem", "liste_id" => $fin->id()));
	
				$itemManager->send($fin_h);
				$itemManager->send($fin_m);
	
				$deb->addListe_elem($deb_h);
				$deb->addListe_elem($deb_m);
	
				$fin->addListe_elem($fin_h);
				$fin->addListe_elem($fin_m);
	
				$listeOpen->addListe_elem($deb);
				$listeOpen->addListe_elem($fin);
				$filtred->addListe_elem($listeOpen);
				$horraire->addListe_elem($filtred);
			}
	
		}
		
		$this->page()->addVar("title_color", $this->defaultPresItem("title_color", $listeItem, "elem"));
		$this->page()->addVar("color", $this->defaultPresItem("color", $listeItem, "elem"));
	
		$this->page()->addVar("accroche", $this->defaultPresItem("accroche", $listeItem, "elem")); //Liste d'element sous forme de String
		$this->page()->addVar("slider_id", $slider->id());
		$this->page()->addVar("slider", $slider->liste_elem()); //Liste d'image sous forme \Library\Entities\file
		$this->page()->addVar("titre", $this->defaultPresItem("titre", $listeItem, "elem")); //titre de la page sous forme de strig
		$this->page()->addVar("left_description", $this->defaultPresItem("left_description", $listeItem, "text", $pres->id())); //Liste de Liste. Chaque sous liste un texte sous forme \Modules\Presentation\Entities\texte, ainsi qu'éventuellement une sous liste de manière recursive
		$this->page()->addVar("right_description", $this->defaultPresItem("right_description", $listeItem, "text", $pres->id())); //Liste de Liste. Chaque sous liste un texte sous forme \Modules\Presentation\Entities\texte, ainsi qu'éventuellement une sous liste de manière recursive
		$this->page()->addVar("logo", $this->defaultPresItem("logo", $listeItem, "img")); //Fichier du logo
		$this->page()->addVar("map", $this->defaultPresItem("map", $listeItem, "img")); //Image de la map
	
		$this->page()->addVar("galerie_id", $galerie->id());
		$this->page()->addVar("galerie", $galerie->liste_elem()); //Liste d'image sous forme \Library\Entities\file
	
		$this->page()->addVar("street", $this->defaultPresItem("street", $listeItem, "elem")); //adresse du magasin
		$this->page()->addVar("city", $this->defaultPresItem("city", $listeItem, "elem")); //ID de la ville du magasin
		$this->page()->addVar("telephone", $this->defaultPresItem("telephone", $listeItem, "elem")); //N° de téléphone du magasin
		$this->page()->addVar("web_site", $this->defaultPresItem("web_site", $listeItem, "elem")); //Le site internet
		$this->page()->addVar("divers_horraire", $this->defaultPresItem("divers_horraire", $listeItem, "text", $pres->id())); //La description complémentaire de l'horraire
		$this->page()->addVar("email", $this->defaultPresItem("email", $listeItem, "elem")); //Email du magasin
		$this->page()->addVar("horraire", $horraire); //Liste des horraires, voir le format
		
		$this->page()->addVar("marques", $this->defaultPresItem("marques", $listeItem, "text", $pres->id())); //Liste des marques
		$this->page()->addVar("key_word", $this->defaultPresItem("key_word", $listeItem, "text", $pres->id())); //Liste des mots clefs
	
		$this->page()->addVar("listeCity", $itemManager->getList(array("name = 'liste_city'")));
	
		$this->page()->addVar("apiKey", $this->app()->appConfig()->getConst("GOOGLE_API_KEY"));
	
	
	}
	
	public function executeShow(\Library\HTTPRequest $request) {
		if (!$request->existGet("presentation_id") || empty($request->dataGet("presentation_id")) || is_numeric($request->dataGet("presentation_id")))
			$this->app()->httpResponse()->redirect404();
		
		if ($request->existGet("json") && $request->dataGet("json") == 1) {
			$this->page()->setIsJson();
			$this->page()->addVar("valid", 1);
		}
		
		$mainManager = $this->managers()->getManagersOf("main");
		
		$pres = $mainManager->get($request->dataGet("presentation_id"));
		
		if (empty($pres) || !($pres instanceof \Modules\Presentation\Entities\main && $pres->deleted() == 0))
			$this->app()->httpResponse()->redirect404();
		
		$itemManager = $this->managers()->getManagersOf("item");
		$itemManager->setDateManager($this->managers()->getManagersOf("date"));
		$listeItem = $itemManager->getFromPres($pres->id(), $this->managers()->getManagersOf("texte"));
		
		$lCategorie = $this->managers()->getManagersOf("categorie")->getListFromMain($pres->id());
		
		if (count($lCategorie)) {
			$cate = $lCategorie[count($lCategorie)-1];
		} else {
			$cate = new \Modules\Presentation\Entities\categorie();
		}

		$slider = $this->defaultPresItem("slider", $listeItem, "list");
		
		$slider->setPresentation_main_id($pres->id());
		
		if ($slider->id() < 1)
			$itemManager->send($slider);
		
		$galerie = $this->defaultPresItem("galerie", $listeItem, "list");
		$galerie->setPresentation_main_id($pres->id());
		
		if ($galerie->id() < 1)
			$itemManager->send($galerie);
			
		$this->page()->addVar("presentation", $pres);
	
		$fileManager = $this->managers()->getManagersOf("file");
		
		$slider->setListe_elem(array_map(function ($arg) use ($fileManager) {return $fileManager->get($arg->key());}, $slider->liste_elem()));
		$galerie->setListe_elem(array_map(function ($arg) use ($fileManager) {return $fileManager->get($arg->key());}, $galerie->liste_elem()));
		
		$listeCity = array();
		
		$listeDay = array("MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY", "SUNDAY");
		
		$horraire = $this->defaultPresItem("horraire", $listeItem, "list");
		
		if ($horraire->id() < 1) {
			$horraire->setPresentation_main_id($pres->id());
			$itemManager->send($horraire);
		}
		
		foreach ($listeDay AS $day) {
			$filtred = array_filter($horraire->liste_elem(), function ($h) use ($day) { return $h->name() == $day;});
			
			if (count($filtred) == 0) {
				
				$filtred = new \Modules\Presentation\Entities\item(array("val" => "-1", "item" => "list"));
				
				$horraire->addListe_elem($filtred);
			}
		}

		$listeDay = array();
			
		foreach ($horraire->liste_elem() AS $e)
			$listeDay[$e->id()] = $e->name();
		
		$closed = array();
		$same = array();
		$allDay = array();
		
		foreach ($horraire->liste_elem() AS $h) {
			if ($h->key() == -1)
				$closed[$h->id()] = $h;
			elseif (key_exists($h->key(), $listeDay)) {
				$array = (key_exists($h->key(), $same)) ? $same[$h->key()] : array("same" => array(), "main" => new \Modules\Presentation\Entities\item((array("item" => "list"))));
				$array["same"][$h->id()] = $h;
				$same[$h->key()] = $array;
			} else {
				if ($h->key() == -10)
					$allDay[$h->id()] = $h;
				$array = (key_exists($h->id(), $same)) ? $same[$h->id()] : array("same" => array(), "main" => new \Modules\Presentation\Entities\item((array("item" => "list"))));
				$array["main"] = $h;
				$same[$h->id()] = $array;
			}
		}
		$valid_counter = 0;

		
		do {
			$valid = true;
			$valid_counter++;
			foreach ($same AS $k=>$s) {
				$counter = 0;
				if (key_exists("main", $s) && $s["main"]->id() > 0) {
					$m = $s["main"];
					foreach ($m->liste_elem() AS $slot) {
						$v = true;
						foreach ($slot->liste_elem() AS $h) {
							foreach ($h->liste_elem() AS $t)
								if ($t->name() == "h" && ($t->val() == ""))
									$v = false;
								if ($t->name() == "m" && ($t->val() == 0 || $t->val() == "" || $t->val() == null))
									$t->setVal(0);
						}
						if ($v)
							$counter++;
					}
					
					if ($counter == 0 && $m->key() != -10) {
						$closed = array_merge($closed, array($m), $s["same"]);
						unset($same[$k]);
					}
				} elseif (key_exists("same", $s) && count($s["same"])) {
					$valid = false;
					foreach ($s["same"] AS $e)
						if ($valid_counter > 10) {
							$closed[$e->id()] = $e;
							unset($same[$k]["same"][$e->id()]);
						} else
							if (key_exists($e->key(), $closed)) {
								$closed[$e->id()] = $e;
								unset($same[$k]["same"][$e->id()]);
							} else {
								foreach ($same AS $z=>$t) {
									if (key_exists($e->key(), $t["same"])) {
										$same[$z]["same"][$e->id()] = $e;
										if ($z != $k)
											unset($same[$k]["same"][$e->id()]);
									}
								}
							}
					if (count($same[$k]["same"]) == 0)
						unset($same[$k]);
				}
				
			}
		} while ($valid == false && $valid_counter < 15);
		
		$this->page()->addVar("counter", $valid_counter);
		
		if ($valid_counter >= 15) {
			$closed = $horraire->liste_elem();
			$same = array();
		}
		
		$this->page()->addVar("title_color", $this->defaultPresItem("title_color", $listeItem, "elem"));
		$this->page()->addVar("color", $this->defaultPresItem("color", $listeItem, "elem"));
		
		$this->page()->addVar("accroche", $this->defaultPresItem("accroche", $listeItem, "elem")); //Liste d'element sous forme de String
		$this->page()->addVar("slider_id", $slider->id());
		$this->page()->addVar("slider", $slider->liste_elem()); //Liste d'image sous forme \Library\Entities\file
		$this->page()->addVar("titre", $this->defaultPresItem("titre", $listeItem, "elem")); //titre de la page sous forme de strig
		$this->page()->addVar("left_description", $this->defaultPresItem("left_description", $listeItem, "list")); //Liste de Liste. Chaque sous liste un texte sous forme \Modules\Presentation\Entities\texte, ainsi qu'éventuellement une sous liste de manière recursive
		$this->page()->addVar("right_description", $this->defaultPresItem("right_description", $listeItem, "list")); //Liste de Liste. Chaque sous liste un texte sous forme \Modules\Presentation\Entities\texte, ainsi qu'éventuellement une sous liste de manière recursive
		$this->page()->addVar("logo", $this->defaultPresItem("logo", $listeItem, "img")); //Fichier du logo
		$this->page()->addVar("map", $this->defaultPresItem("map", $listeItem, "img")); //Image de la map

		$this->page()->addVar("galerie_id", $galerie->id());
		$this->page()->addVar("galerie", $galerie->liste_elem()); //Liste d'image sous forme \Library\Entities\file
		
		$this->page()->addVar("street", $this->defaultPresItem("street", $listeItem, "elem")); //adresse du magasin
		$this->page()->addVar("city", $this->defaultPresItem("city", $listeItem, "elem")); //ID de la ville du magasin
		$this->page()->addVar("telephone", $this->defaultPresItem("telephone", $listeItem, "elem")); //N° de téléphone du magasin
		$this->page()->addVar("web_site", $this->defaultPresItem("web_site", $listeItem, "elem")); //Le site internet
		$this->page()->addVar("divers_horraire", $this->defaultPresItem("divers_horraire", $listeItem, "text", $pres->id())); //La description complémentaire de l'horraire
		$this->page()->addVar("email", $this->defaultPresItem("email", $listeItem, "elem")); //Email du magasin
		$this->page()->addVar("horraire", $same); //Liste des horraires, voir le format
		$this->page()->addVar("closed_day", $closed); //Liste des horraires, voir le format
		
		$this->page()->addVar("marques", $this->defaultPresItem("marques", $listeItem, "text", $pres->id())); //Liste des marques
		$this->page()->addVar("key_word", $this->defaultPresItem("key_word", $listeItem, "text", $pres->id())); //Liste des mots clefs
		
		$this->page()->addVar("listeCity", $itemManager->getList(array("name = 'liste_city'")));
		
		$this->page()->addVar("apiKey", $this->app()->appConfig()->getConst("GOOGLE_API_KEY"));
		
		$this->page()->addVar("categorie", $cate);
		
				
	}
	
	public function executeSendSlider(\Library\HTTPRequest $request) {
		if (!($request->existGet("file_id") && is_numeric($request->dataGet("file_id")) && $request->existGet("slide_id") && is_numeric($request->dataGet("slide_id")) && $request->existGet("pres_id") && is_numeric($request->dataGet("pres_id")))) {
			$valid = false;
			$message = MISSING_DATA;
		} else {
		
			$fileManager = $this->managers()->getManagersOf("file");
			$file = $fileManager->get($request->dataGet("file_id"));
			
			if (empty($file) || !($file instanceof \Library\Entities\file)) {
				$valid = false;
				$message = SUCH_FILE_DONT_EXIST;
			} else {
			
				$item = new \Modules\Presentation\Entities\item(array("presentation_main_id" => $request->dataGet("pres_id"), "val" => $file->id(), "item" => "img", "liste_id" => $request->dataGet("slide_id"), "name" => "slider_item"));
				$itemManager = $this->managers()->getManagersOf("item");
				
				$itemManager->send($item);
				
				if ($item->id() < 1) {
					$valid = false;
					$message = ERROR_INSERTING_DATA;
				} else {
					$valid = true;
					$message = "";
				}
					
			}
		}
		

		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->addVar("name", $file->file_pub_name());
		$this->page()->addVar("id", $file->id());
		
		
		$this->page()->setIsJson();
	}
	
	public function executeSendGalerie(\Library\HTTPRequest $request) {
		if (!($request->existGet("file_id") && is_numeric($request->dataGet("file_id")) && $request->existGet("galerie_id") && is_numeric($request->dataGet("galerie_id")) && $request->existGet("pres_id") && is_numeric($request->dataGet("pres_id")))) {
			$valid = false;
			$message = MISSING_DATA;
		} else {
		
			$fileManager = $this->managers()->getManagersOf("file");
			$file = $fileManager->get($request->dataGet("file_id"));
			
			if (empty($file) || !($file instanceof \Library\Entities\file)) {
				$valid = false;
				$message = SUCH_FILE_DONT_EXIST;
			} else {
			
				$item = new \Modules\Presentation\Entities\item(array("presentation_main_id" => $request->dataGet("pres_id"), "val" => $file->id(), "item" => "img", "liste_id" => $request->dataGet("galerie_id"), "name" => "galerie_item"));
				$itemManager = $this->managers()->getManagersOf("item");
				
				$itemManager->send($item);
				
				if ($item->id() < 1) {
					$valid = false;
					$message = ERROR_INSERTING_DATA;
				} else {
					$valid = true;
					$message = "";
				}
					
			}
			
			$this->page()->addVar("name", $file->file_pub_name());
			$this->page()->addVar("id", $file->id());
			$this->page()->addVar("item_id", $item->id());
		}
		

		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		
		
		$this->page()->setIsJson();
	}
	
	public function executeAddDesc(\Library\HTTPRequest $request) {
		if (!($request->existGet("pres_id") && is_numeric($request->dataGet("pres_id"))))
			$this->app()->httpResponse()->redirect404();
		
		$presManager = $this->managers()->getManagersOf("main");
		$pres = $presManager->get($request->dataGet("pres_id"));
		
		if ($pres->id() < 0)
			$this->app()->httpResponse()->redirect404();
		
		$itemManager = $this->managers()->getManagersOf("item");
		
		// Récupération du dernier élément de l'arbre
		$lTexteItem = $itemManager->getLastText($pres->id(), (($request->dataPost("side") == 1) ? "left" : "right")."_description");
		
		//Ajout d'une nouvelle liste sous le dernier élément
		$listeItem = new \Modules\Presentation\Entities\item(array("presentation_main_id" => $pres->id(), "item" => "list", "liste_id" => $lTexteItem->id(), "name" => $lTexteItem->name()));
		
		$itemManager->send($listeItem);
		
		//Création du texte
		$texte = new \Modules\Presentation\Entities\texte();
		
		$this->managers()->getManagersOf("texte")->send($texte);
		
		//Ajout de l'item texte dans son élément
		$texteItem = new \Modules\Presentation\Entities\item(array("presentation_main_id" => $pres->id(), "val" => $texte->id(), "item" => "text", "liste_id" => $listeItem->id(), "name" => $lTexteItem->name()));
		
		$itemManager->send($texteItem);
		
		$this->page()->addVar("texte_id", $texteItem->id());
		$this->page()->addVar("valid", 1);
		
		$this->page()->setIsJson();
	}
	
	public function executeSendPlan(\Library\HTTPRequest $request) {
		if (!($request->existGet("pres_id") && is_numeric($request->dataGet("pres_id")) && $request->existGet("file_id") && is_numeric($request->dataGet("file_id")))) {
			$valid = false;
			$message = MISSING_DATA;
		} else {
		
			$fileManager = $this->managers()->getManagersOf("file");
			$file = $fileManager->get($request->dataGet("file_id"));
			
			if (empty($file) || !($file instanceof \Library\Entities\file)) {
				$valid = false;
				$message = SUCH_FILE_DONT_EXIST;
			} else {
			
				$item = new \Modules\Presentation\Entities\item(array("presentation_main_id" => $request->dataGet("pres_id"), "val" => $file->id(), "item" => "img", "liste_id" => $request->dataGet("slide_id"), "name" => "map"));
				$itemManager = $this->managers()->getManagersOf("item");
				
				$itemManager->send($item);
				
				if ($item->id() < 1) {
					$valid = false;
					$message = ERROR_INSERTING_DATA;
				} else {
					$valid = true;
					$message = "";
				}
			}
			$this->page()->addVar("id", $file->id());
		}
		

		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		
		
		$this->page()->setIsJson();
	}
	
	public function executeModifSlot(\Library\HTTPRequest $request) {
		if (!($request->existGet("slot_id") && is_numeric($request->dataGet("slot_id")))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$liste = $request->dataPost("liste");
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
			
			$slot = $itemManager->get($request->dataGet("slot_id"));
			
			if ($this->app()->user()->getAdminLvl() >= 5) 
				$access = true;
			else {
				$accessManager = $this->managers()->getManagersOf("access");
				$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $slot->presentation_main_id(), "type" => \PDO::PARAM_INT)));
				
				$access = false;
				foreach ($listeAccess AS $a)
					$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
			}
			
			if ($access) {
				$dao = \Library\Managers::getDao("PDO");
				$dao->beginTransaction();
				
				try {
					$timeSlot = $itemManager->getTimeSlot($slot->id());
					
					foreach ($timeSlot AS $s) {
						$i = $itemManager->get($s->id);
						
						if (!(array_key_exists($s->name, $liste)))
							throw new \InvalidArgumentException("Donnée dans un format incorrecte", 2);
						
						$n = $s->name;
						$$n = $liste[$s->name];
						$i->setVal($liste[$s->name]);
						
						$itemManager->send($i);
					}
					
// 					if (!(isset($deb_h) && isset($deb_m) && isset($fin_h) && isset($fin_m) && ($deb_h < $fin_h || ($fin_h == $deb_h && $deb_m < $fin_m))))
// 						throw new \InvalidArgumentException("Problème de DB", 3);
					
					$dao->commit();
					$valid = 1;
					
				} catch (\Exception $e) {
					$dao->rollBack();
					$valid = 0;
					$message = "";
					switch ($e->getCode()) {
						case 1:
							$message = NOT_VALID_ID; 
							break;
						case 2:
						case 3:
							$message = INVALID_DATA_FORMAT . " - " . $e->getCode(); 
							break;
						default:
							$message = $e->getMessage();
					}
					
				}
				
				
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeAddSlot(\Library\HTTPRequest $request) {
		if (!($request->existGet("day_id") && is_numeric($request->dataGet("day_id")))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
			
			$slot = $itemManager->get($request->dataGet("day_id"));
			
			$accessManager = $this->managers()->getManagersOf("access");
			
			if ($this->app()->user()->getAdminLvl() >= 5)
				$access = true;
			else {
				$accessManager = $this->managers()->getManagersOf("access");
				$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $slot->presentation_main_id(), "type" => \PDO::PARAM_INT)));
			
				$access = false;
				foreach ($listeAccess AS $a)
					$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
			}
			
			if ($access) {


				$listeOpen = new \Modules\Presentation\Entities\item(array("name" => "slot", "presentation_main_id" => $slot->presentation_main_id(), "item" => "list", "liste_id" => $request->dataGet("day_id")));
				
				$itemManager->send($listeOpen);
				
				$deb = new \Modules\Presentation\Entities\item(array("name" => "deb", "presentation_main_id" => $slot->presentation_main_id(), "item" => "list", "liste_id" => $listeOpen->id()));
				$fin = new \Modules\Presentation\Entities\item(array("name" => "fin", "presentation_main_id" => $slot->presentation_main_id(), "item" => "list", "liste_id" => $listeOpen->id()));
				
				$itemManager->send($deb);
				$itemManager->send($fin);
				
				$deb_h = new \Modules\Presentation\Entities\item(array("name" => "h", "presentation_main_id" => $slot->presentation_main_id(), "item" => "elem", "liste_id" => $deb->id()));
				$deb_m = new \Modules\Presentation\Entities\item(array("name" => "m", "presentation_main_id" => $slot->presentation_main_id(), "item" => "elem", "liste_id" => $deb->id()));
				
				$itemManager->send($deb_h);
				$itemManager->send($deb_m);
				
				$fin_h = new \Modules\Presentation\Entities\item(array("name" => "h", "presentation_main_id" => $slot->presentation_main_id(), "item" => "elem", "liste_id" => $fin->id()));
				$fin_m = new \Modules\Presentation\Entities\item(array("name" => "m", "presentation_main_id" => $slot->presentation_main_id(), "item" => "elem", "liste_id" => $fin->id()));
				
				$itemManager->send($fin_h);
				$itemManager->send($fin_m);
				
				$this->page()->addVar("slot_id", $listeOpen->id());
				$valid = 1;
				
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
		
	}
	
	public function executeRemoveSlot(\Library\HTTPRequest $request) {
		if (!($request->existGet("slot_id") && is_numeric($request->dataGet("slot_id")))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
			
			$slot = $itemManager->get($request->dataGet("slot_id"));
			

			if ($this->app()->user()->getAdminLvl() >= 5)
				$access = true;
			else {
				$accessManager = $this->managers()->getManagersOf("access");
				$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $slot->presentation_main_id(), "type" => \PDO::PARAM_INT)));
					
				$access = false;
				foreach ($listeAccess AS $a)
					$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
			}
			
			if ($access) {
				
				$itemManager->delete($slot->id());
				$valid = 1;
				
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeCloseDay(\Library\HTTPRequest $request) {
		if (!($request->existGet("day_id") && is_numeric($request->dataGet("day_id")) && $request->existPost("checked"))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
			
			$slot = $itemManager->get($request->dataGet("day_id"));
			
			$accessManager = $this->managers()->getManagersOf("access");
					
			if ($this->app()->user()->getAdminLvl() >= 5)
				$access = true;
			else {
				$accessManager = $this->managers()->getManagersOf("access");
				$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $slot->presentation_main_id(), "type" => \PDO::PARAM_INT)));
					
				$access = false;
				foreach ($listeAccess AS $a)
					$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
			}
			
			if ($access) {
				if ($request->dataPost("checked") == "true")
					if ($request->existPost("allDay") && $request->dataPost("allDay") == "true")
						$slot->setVal("-10"); //TODO
					else
						$slot->setVal("-1");
				else
					$slot->setVal("0");
				
				
				$itemManager->send($slot);
				
				$valid = 1;
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeSameDay(\Library\HTTPRequest $request) {
		if (!($request->existGet("day_id") && is_numeric($request->dataGet("day_id")) && $request->existPost("for_day") && is_numeric($request->dataPost("for_day")))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
			
			$slot = $itemManager->get($request->dataGet("day_id"));
			
			if ($this->app()->user()->getAdminLvl() >= 5)
				$access = true;
			else {
				$accessManager = $this->managers()->getManagersOf("access");
				$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $slot->presentation_main_id(), "type" => \PDO::PARAM_INT)));
					
				$access = false;
				foreach ($listeAccess AS $a)
					$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
			}
			
			if ($access) {
				$slot->setVal($request->dataPost("for_day"));
				
				$itemManager->send($slot);
				
				$valid = 1;
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeChangeDesc(\Library\HTTPRequest $request) {
		if (!($request->existGet("text_id") && is_numeric($request->dataGet("text_id")) && $request->existPost("txt"))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
			
			$slot = $itemManager->get($request->dataGet("text_id"));
			
			$main = $this->managers()->getManagersOf("main")->get($slot->presentation_main_id());
			
			if ($main->type() == 0 || $main->type() == 1) {
				
				if ($this->app()->user()->getAdminLvl() >= 5)
					$access = true;
				else {
					$accessManager = $this->managers()->getManagersOf("access");
					$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $slot->presentation_main_id(), "type" => \PDO::PARAM_INT)));
						
					$access = false;
					foreach ($listeAccess AS $a)
						$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
				}
				
			} else {
				$access = true;
			}
			
			if ($access) {
				$textManager = $this->managers()->getManagersOf("texte");
				
				$texte = $textManager->get($slot->key());
				
				if (!($texte instanceof \Modules\Presentation\Entities\texte))
					$texte = new \Modules\Presentation\Entities\texte();
				
				$texte->setVal($request->dataPost("txt"));
				
				$textManager->send($texte);
				
				$valid = 1;
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeModifItem(\Library\HTTPRequest $request) {
		if (!($request->existGet("pres_id") && is_numeric($request->dataGet("pres_id")) && $request->existPost("name") && $request->existPost("val"))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
			
			$main = $this->managers()->getManagersOf("main")->get($request->dataGet("pres_id"));
			
			if ($main->type() == 0 || $main->type() == 1) {
				
				if ($this->app()->user()->getAdminLvl() >= 5)
					$access = true;
				else {
					$accessManager = $this->managers()->getManagersOf("access");
					$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $request->dataGet("pres_id"), "type" => \PDO::PARAM_INT)));
				
					$access = false;
					foreach ($listeAccess AS $a)
						$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
				}
			} else {
				$access = true;
			}
			
			if ($access) {
				if ($request->dataPost("name") == "date_event" || $request->dataPost("name") == "end_date") {
					$dateFormat = \Utils::getDateFormat($this->app()->user()->getLanguage());
				}
				
				
				if ($request->dataPost("name") == "email" && !\Utils::testEmail($request->dataPost("val"))) {
					$valid = 0;
					$message = INVALID_EMAIL;
				} elseif($request->dataPost("name") == "telephone" && !\Utils::testPhon($request->dataPost("val"))) {
					$valid = 0;
					$message = INVALID_PHON;
				} elseif($request->dataPost("name") == "color" && preg_match('/^\#(([0-9]|[ABCDEFabcdef]){6}|([0-9]|[ABCDEFabcdef]){3})$/', $request->dataPost("val")) == 0) {
					$valid = 0;
					$message = INVALID_COLOR;
				} else {
					if ($request->dataPost("name") == "main_title") {
						$mainManager = $this->managers()->getManagersOf("main");
						
						$main = $mainManager->get($request->dataGet("pres_id"));
						$main->setNom($request->dataPost("val"));
						
						$mainManager->send($main);
					} elseif($request->dataPost("name") == "published") {
						$mainManager = $this->managers()->getManagersOf("main");
						
						$main = $mainManager->get($request->dataGet("pres_id"));
						$main->setPublished($request->dataPost("val"));
						
						$mainManager->send($main);
					} else {
						$item = $itemManager->getList(array("presentation_main_id = :pId", "name = :pName"), array(array("key" => ":pId", "val" => $request->dataGet("pres_id"), "type" => \PDO::PARAM_INT), array("key" => ":pName", "val" => $request->dataPost("name"), "type" => \PDO::PARAM_STR)));
						
						$item = (count($item) > 0) ? $item[0] : new \Modules\Presentation\Entities\item(array("presentation_main_id" => $request->dataGet("pres_id"), "name" => $request->dataPost("name"), "item" => "elem"));
						
						if ($item->item() == "text") {
							$textManager = $this->managers()->getManagersOf("texte");
							
							$text = $textManager->get($item->key());
							
							if (!($text instanceof \Modules\Presentation\Entities\texte))
								$text = new \Modules\Presentation\Entities\texte();
							
							$text->setVal($request->dataPost("val"));
							
							$textManager->send($text);
							
							$item->setVal($text->id());
							
							$itemManager->send($item);
						} elseif ($item->item() == "date") {
							$dateManager = $this->managers()->getManagersOf("date");
							
							$date = $dateManager->get($item->key());
							
							if (!($date instanceof \Modules\Presentation\Entities\date))
								$date = new \Modules\Presentation\Entities\date();
							
							$date->setVal(\DateTime::createFromFormat($dateFormat[1], $request->dataPost("val")));
							$dateManager->send($date);
							
							$item->setVal($date->id());
							
							$itemManager->send($item);
							
						} else {
							$item->setVal($request->dataPost("val"));
							
							$itemManager->send($item);
						}
					}
					
					$valid = 1;
					
				}
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeSendLogo(\Library\HTTPRequest $request) {
		if (!($request->existGet("pres_id") && is_numeric($request->dataGet("pres_id")) && $request->existPost("logo"))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
						
			if ($this->app()->user()->getAdminLvl() >= 5)
				$access = true;
			else {
				$accessManager = $this->managers()->getManagersOf("access");
				$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $request->dataGet("pres_id"), "type" => \PDO::PARAM_INT)));
			
				$access = false;
				foreach ($listeAccess AS $a)
					$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
			}
			
			if ($access) {
				$split = explode("/", $request->dataPost("logo"));
				$num = $split[count($split)-2];
				
				if (!is_numeric($num) || $num < 1) {
					$valid = 0;
					$message = INVALID_IMG;
				} else {
					$listeItem = $itemManager->getList(array("presentation_main_id = :pId", "name = 'logo'"), array(array("key" => ":pId", "val" => $request->dataGet("pres_id"), "type" => \PDO::PARAM_INT)));
					
					$item = (count($listeItem) > 0) ? $listeItem[0] : new \Modules\Presentation\Entities\item(array("presentation_main_id" => $request->dataGet("pres_id"), "name" => "logo", "item" => "img"));
					
					$item->setVal($num);
					
					$itemManager->send($item);
					$valid = 1;
				}
				
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeSendMap(\Library\HTTPRequest $request) {
		if (!($request->existGet("pres_id") && is_numeric($request->dataGet("pres_id")) && $request->existPost("map"))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
						
			if ($this->app()->user()->getAdminLvl() >= 5)
				$access = true;
			else {
				$accessManager = $this->managers()->getManagersOf("access");
				$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $request->dataGet("pres_id"), "type" => \PDO::PARAM_INT)));
					
				$access = false;
				foreach ($listeAccess AS $a)
					$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
			}
			
			if ($access) {
				$split = explode("/", $request->dataPost("map"));
				$num = $split[count($split)-2];
				
				if (!is_numeric($num) || $num < 1) {
					$valid = 0;
					$message = INVALID_IMG;
				} else {
					$listeItem = $itemManager->getList(array("presentation_main_id = :pId", "name = 'map'"), array(array("key" => ":pId", "val" => $request->dataGet("pres_id"), "type" => \PDO::PARAM_INT)));
					
					$item = (count($listeItem) > 0) ? $listeItem[0] : new \Modules\Presentation\Entities\item(array("presentation_main_id" => $request->dataGet("pres_id"), "name" => "map", "item" => "img"));
					
					$item->setVal($num);
					
					$itemManager->send($item);
					$valid = 1;
				}
				
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeDelTxt(\Library\HTTPRequest $request) {
		if (!($request->existGet("txt_id") && is_numeric($request->dataGet("txt_id")))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
			
			$accessManager = $this->managers()->getManagersOf("access");
			$item = $itemManager->get($request->dataGet("txt_id"));
				
			if ($this->app()->user()->getAdminLvl() >= 5)
				$access = true;
			else {
				$accessManager = $this->managers()->getManagersOf("access");
				$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $item->presentation_main_id(), "type" => \PDO::PARAM_INT)));
					
				$access = false;
				foreach ($listeAccess AS $a)
					$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
			}
			
			if ($access) {
				
				$this->managers()->getManagersOf("texte")->delete($item->key());
				$itemManager->delete($item->id());
				
				$valid = 1;
				$message = "";
				
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeDelImg(\Library\HTTPRequest $request) {
		if (!($request->existGet("img_id") && is_numeric($request->dataGet("img_id")))) {
			$valid = 0;
			$message = MISSING_DATA;
		} else {
			
			$userManager = $this->managers()->getManagersOf("user");
			
			$itemManager = $this->managers()->getManagersOf("item");
			$item = $itemManager->getList(array("item = 'img'", "val = :pId"), array(array("key" => ":pId", "val" => $request->dataGet("img_id"), "type" => \PDO::PARAM_INT)));
			
			$access = false;
			
			if (count($item)) {
				$i = $item[0];	
				
				if ($this->app()->user()->getAdminLvl() >= 5)
					$access = true;
				else {
					$accessManager = $this->managers()->getManagersOf("access");
					$listeAccess = $accessManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $i->presentation_main_id(), "type" => \PDO::PARAM_INT)));
						
					$access = false;
					foreach ($listeAccess AS $a)
						$access = $access || $userManager->isInGroup($this->app()->user()->id(), $a->groupe_id());
				}
			}
			
			if ($access) {
				foreach ($item AS $i) {
				$itemManager->delete($i->id());
					
				}
				$this->managers()->getManagersOf("file")->delete($request->dataGet("img_id"));
				
				$valid = 1;
				
			} else {
				$valid = 0;
				$message = NO_VALID_ACCESS;
			}
			
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", (!$valid) ? $message : "");
		
		$this->page()->setIsJson();
	}
	
	public function executeGetList (\Library\HTTPRequest $request) {
		$presManager = $this->managers()->getManagersOf("main");

		$this->page()->addVar("liste", $presManager->getList(array("`type` = 1 OR `type` = 0", "published = 1", "deleted = 0")));
		$this->page()->addVar("valid", 1);
		$this->page()->setIsJson();
	}
	
	public function executeListePres(\Library\HTTPRequest $request) {
		$this->page()->addVar("liste_pres", $this->managers()->getManagersOf("main")->getList(array("`type` = 1 OR `type` = 0", "deleted = 0")));
		$this->page()->addVar("liste_cate", $this->managers()->getManagersOf("categorie")->getList());
		
		if ($request->existTransfert("user_id")) {
			$this->page()->addVar("liste_in_pres", $this->managers()->getManagersOf("main")->getListOfCommerce($request->dataTransfert("user_id")));
		}
	}
	
	public function executeAddPres(\Library\HTTPRequest $request) {
		if (!($request->existTransfert("nom") && $request->existTransfert("type") && $request->existTransfert("groupe_id") && $request->existTransfert("cate"))) {
			$this->page()->addVar("error", 1);
			$this->page()->addVar("valid", false);
		} else {
			$mainManager = $this->managers()->getManagersOf("main");
			$accessManager = $this->managers()->getManagersOf("access");
			
			$main = new \Modules\Presentation\Entities\main(array("nom" => $request->dataTransfert("nom"), "type" => $request->dataTransfert("type")));
			$mainManager->send($main);
			
			if ($main->id() > 0) {
				$access = new \Modules\Presentation\Entities\access(array("presentation_main_id" => $main->id(), "groupe_id" => $request->dataTransfert("groupe_id")));
				
				$this->managers()->getManagersOf("access")->send($access);
				
				if ($access->id() > 0) {
					if (($cate_id = $request->dataTransfert("cate")) > 0) {
						$mainManager->addToCategorie($main->id(), $cate_id);
					}
					
					$this->page()->addVar("valid", true);
				} else {
					$this->page()->addVar("error", 3);
					$this->page()->addVar("valid", false);
				}
			} else {
				$this->page()->addVar("error", 2);
				$this->page()->addVar("valid", false);
			}
		}
	}
	
	public function executeGetGroupFromPresId(\Library\HTTPRequest $request) {
		if (!($request->existTransfert("main_id") && is_numeric($request->dataTransfert("main_id")) && $request->existTransfert("user_id") && is_numeric($request->dataTransfert("user_id")))) {
			$this->page()->addVar("valid", false);
		} else {
			$accessManager = $this->managers()->getManagersOf("access");
			
			
			
			
			
// 			$listeGroupe = $accessManager->getGroupeIdFromPres($request->dataTransfert("main_id"));
			
// 			if (count($listeGroupe) == 0) {
// 				$mainManager = $this->managers()->getManagersOf("main");
// 				$main = $mainManager->get($request->dataTransfert("main_id"));
				
// 				if (!$main instanceof \Modules\Presentation\Entities\main && $main->deleted() == 0) {
// 					$this->page()->addVar("valid", false);
// 				} else {
// 					$groupeManager = $this->managers()->getManagersOf("groupe");
						
// 					$parentGroupe = $groupeManager->getFromConst("NORMAL_USER");
// 					if ($parentGroupe == null)
// 						$parentGroupe = new \Library\Entities\groupe();

// 					$groupe = new \Library\Entities\groupe(array("txt_cst" => rand(), "def_val" => $main->nom(), "parent_id" => $parentGroupe->id()));
					
// 					$groupeManager->send($groupe);
					
// 					if ($groupe->id() > 0) {
// 						$accessManager->send(new \Modules\Presentation\Entities\access(array("groupe_id" => $groupe->id(), "presentation_main_id" => $main->id())));
// 						$listeGroupe[] = $groupe->id();
// 					} else 
// 						$this->page()->addVar("valid", false);
// 				}
				
// 				if (count($listeGroupe)) {
// 					$this->page()->addVar("listeGroupe", $listeGroupe);
// 					$this->page()->addVar("valid", true);
// 				}
// 			}
		}
	}
	
	public function executeRemoveListeGroupeAccess(\Library\HTTPRequest $request) {
		if ($request->existTransfert("liste_groupe") && is_array($request->dataTransfert("liste_groupe")) && count($request->dataTransfert("liste_groupe")))
			$this->managers()->getManagersOf("access")->deleteList(array("groupe_id IN (" . implode(", ", array_map(function ($a) {return $a->id();} , $request->dataTransfert("liste_groupe"))) . ")"));
		
	}
	
	public function executeInsertGroupAccess(\Library\HTTPRequest $request) {
		if ($request->existTransfert("main_id") && is_numeric($request->dataTransfert("main_id")) && $request->existTransfert("groupe_id") && is_numeric($request->dataTransfert("groupe_id")))
			$this->managers()->getManagersOf("access")->send(new \Modules\Presentation\Entities\access(array("groupe_id" => $request->dataTransfert("groupe_id"), "presentation_main_id" => $request->dataTransfert("main_id"))));
	}
	
	public function executeListe_categorie(\Library\HTTPRequest $request) {
		$this->page()->addVar("liste_categorie", $this->managers()->getManagersOf("categorie")->getList());
	}
	
	public function executeSearch(\Library\HTTPRequest $request) {
		if (!($request->existPost("search")))
			$this->app()->httpResponse()->redirect404();
		
		$listePres = $this->managers()->getManagersOf("main")->search(
			\Utils::protect($request->dataPost("search")),
			array(
				array("type" => "main", "weight" => 100),
				array("type" => "cate", "weight" => 30),
				array("type" => "txt", "weight" => 30, "name" => "key_word"),
				array("type" => "item", "weight" => 15, "name" => "accroche"),
				array("type" => "txt", "weight" => 15, "name" => "marques"),
				array("type" => "item", "weight" => 10, "name" => "titre"),
				array("type" => "txt", "weight" => 1, "name" => "right_description"),
				array("type" => "txt", "weight" => 1, "name" => "left_description")
			),
			(($request->existPost("length") && is_numeric($request->dataPost("length"))) ? $request->dataPost("length") : 5),
			(($request->existPost("type") && is_array($request->dataPost("type"))) ? $request->dataPost("type") : array(0, 1)));

		if ($request->existPost("get_cate"))
			foreach ($listePres AS $pres)
				$pres->setCategorie($this->managers()->getManagersOf("categorie")->get($pres->categorie_id()));
		
		if ($request->existPost("getElem"))
			if (is_array($request->dataPost("getElem"))) {
				foreach ($request->dataPost("getElem") AS $e)
					if (is_string($e))
						foreach ($listePres AS $pres)
							$pres->$e = $this->managers()->getManagersOf("item")->getList(array("name = :pName", "presentation_main_id = :pId"), array(array("key" => ":pName", "val" => $e, "type" => \PDO::PARAM_STR), array("key" => ":pId", "val" => $pres->id(), "type" => \PDO::PARAM_INT)));
			} else {
				if (is_string($e = $request->dataPost("getElem")))
					foreach ($listePres AS $pres)
						$pres->$e = $this->managers()->getManagersOf("item")->getList(array("name = :pName", "presentation_main_id = :pId"), array(array("key" => ":pName", "val" => $e, "type" => \PDO::PARAM_STR), array("key" => ":pId", "val" => $pres->id(), "type" => \PDO::PARAM_INT)));
			}
			
		$this->page()->addVar("liste_pres", $listePres);
		$this->page()->addVar("valid", 1);
		$this->page()->setIsJson();
	}
	
	public function executeSearchBox(\Library\HTTPRequest $request) {
		$listeMagas = $this->managers()->getManagersOf("main")->getList(array("type = 0 OR type = 1", "published = 1", "deleted = 0"));
		$lPres = array();
		
		foreach ($listeMagas AS $m) {
			$logo = $this->managers()->getManagersOf("item")->getList(array("name = 'logo'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $m->id(),"type" => \PDO::PARAM_INT)));

			if (count($logo)) {
				$m->logo = $logo[count($logo)-1];
				$lPres[] = $m;
			}
		}
		$listEvent = $this->managers()->getManagersOf("main")->getList(array("type = 0", "published = 1", "deleted = 0"));
		$itemManager = $this->managers()->getManagersOf("item");
		
		$dateManager = $this->managers()->getManagersOf("date");
		
		foreach ($listEvent AS $event) {
			$couv = $itemManager->getList(array("name = 'cover_img'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
			if (count($couv))
 				$event->img = $couv[0];
			else
				$event->img = null;
			
			$date = $itemManager->getList(array("name = 'date_event'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
			$end_date = $itemManager->getList(array("name = 'end_date'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
			
			if (count($date)) {
				$event->date = $dateManager->get($date[0]->key());
				if (count($end_date)) {
					$event->end_date = $dateManager->get($end_date[0]->key());
				}
			}
		} 
		
		$listeActu = $this->managers()->getManagersOf("main")->getList(array("type = 1", "published = 1", "deleted = 0"), array(), array(array("key" => "id", "order" => "DESC")));
		
		foreach ($listeActu AS $event) {
			$date = $itemManager->getList(array("name = 'date_event'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
			$end_date = $itemManager->getList(array("name = 'end_date'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
			
			if (count($date)) {
				$event->date = $dateManager->get($date[0]->key());
				if (count($end_date)) {
					$event->end_date = $dateManager->get($end_date[0]->key());
				}
			}
		} 
		
		$this->page()->addVar("listeLogo", $lPres);
		$this->page()->addVar("listeEvent", $listEvent);
		$this->page()->addVar("listeActu", $listeActu);
	}
	
	public function executeSendMail(\Library\HTTPRequest $request) {
		$message = array();
		$valid = 0;
		
		$sendedManager = $this->managers()->getManagersOf("sended_mail");
		$listeSended = $sendedManager->getList(array("date_sent > NOW() - INTERVAL 5 MINUTE", "IP = '" . $this->app()->user()->getIP() . "'"));
		
		if (count($listeSended) > 5)
			$message[] = TOO_MUCH_SENDED_MESSAGE;
		
		if (!(	$request->existPost("nom")
				&& $request->existPost("email") && !empty($request->dataPost("email"))
				&& $request->existPost("message") && !empty($request->dataPost("message"))))
			$message[] = INVALID_SENT_VALUE;
		elseif (!\Utils::testEmail($request->dataPost("email")))
			$message[] = INVALID_EMAIL;
		
		$mailer = $this->app()->mailer();
		$mail = NULL;
		
		if ($request->existPost("id") && is_numeric($request->dataPost("id")) && $request->dataPost("id") > 0) {
			$lMail = $this->managers()->getManagersOf("item")->getList(array("presentation_main_id = :pMainId", "name = 'email'"), array(array("key" => ":pMainId", "val" => $request->dataPost("id"), "type" => \PDO::PARAM_INT)));
			
			if (count($lMail))
				$mail = $lMail[0]->val();
			else
				$message[] = ERROR_NO_EMAIL_FOR_USER;
		} else
			if (($mail = $this->app()->config()->get("DEFAULT_EMAIL")) === NULL)
				$message[] = ERROR_NO_DEFAULT_EMAIL;
			
		if ($mail !== NULL && count($message) == 0) {
			
			try {

				$sended = new \Modules\Presentation\Entities\sended_mail(array("used_mail" => $request->dataPost("email"), "date_sent" => new \DateTime(), "ip" => $this->app()->user()->getIP(), "presentation_main_id" => ($request->existPost("id") && is_numeric($request->dataPost("id")) && $request->dataPost("id") > 0) ? $request->dataPost("id"): NULL));
				$sendedManager->send($sended);
			
				if ($mailer->addReciever($mail) == 0)
					throw new \InvalidArgumentException("Erreur pour la réception");
				
				if ($mailer->setFile(__DIR__ . "/mail/contact.html") == 0)
					throw new \InvalidArgumentException("Erreur de fichier");
	
				$mailer->addFileValue("MAIL_CONTACT_MAIL", $request->dataPost("email"));
				$mailer->addFileValue("MAIL_CONTACT_NAME", (!empty($request->dataPost("nom"))) ? $request->dataPost("nom") : NO_VALUE);
				$mailer->addFileValue("MAIL_CONTACT_MESSAGE", $request->dataPost("message"));
				
				if ($mailer->setSubject(DEFAULT_CONTACT_SUBJECT) == 0)
					throw new \InvalidArgumentException("Erreur de sujet");
						
				$mailer->sendMail();
				
				$valid = 1;
			} catch (\Exception $e) {
				$message[] = $e->getMessage();
			}
		}
		
		$this->page()->setIsJson();
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
	}
	
	public function executeAddEvent(\Library\HTTPRequest $request) {
		if (!($request->existPost("event_name")))
			$this->app()->httpResponse()->redirect404();
		
		$main = new \Modules\Presentation\Entities\main(array("nom" => $request->dataPost("event_name"), "type" => (($request->existGet("actualite") == 1) ? 1 : 0), "date_crea" => new \DateTime(), "published" => 0));
		
		$this->managers()->getManagersOf("main")->send($main);
		
		$this->page()->addVar("valid", 1);
		$this->page()->addVar("id", $main->id());
		
		$this->page()->setIsJson();
	}
	
	public function executeNewEvent(\Library\HTTPRequest $request) {
		if (!($request->existGet("event_id") && is_numeric($request->dataGet("event_id"))))
			$this->app()->httpResponse()->redirect404();
		
		$isEvent = !($request->existGet("actualite") && $request->dataGet("actualite") == 1);
		
		$mainManager = $this->managers()->getManagersOf("main");
		
		
		$main = $mainManager->get($request->dataGet("event_id"));
		
		if ($main == null || !($main instanceof \Modules\Presentation\Entities\main && $main->deleted() == 0))
			$this->app()->httpResponse()->redirect404();
		
		$itemManager = $this->managers()->getManagersOf("item");
		
		$itemManager->setDateManager($this->managers()->getManagersOf("date"));

		$listeItem = $itemManager->getFromPres($main->id(), $this->managers()->getManagersOf("texte"));
		
		$this->page()->addVar("event", $main);
		
		$this->page()->addVar("isEvent", $isEvent);
	
		$this->page()->addVar("cover_img", $this->defaultPresItem("cover_img", $listeItem, "img")); //Image de couverture de l'événement
		$this->page()->addVar("date_event", $this->defaultPresItem("date_event", $listeItem, "date", $main->id())); //Date de l'événement au format \DateTime
		$this->page()->addVar("end_date", $this->defaultPresItem("end_date", $listeItem, "date", $main->id())); //Date de l'événement au format \DateTime
		$this->page()->addVar("base_txt", $this->defaultPresItem("base_txt", $listeItem, "text", $main->id())); //Texte de base
		if ($isEvent)
			$this->page()->addVar("list_information", $this->defaultPresItem("list_information", $listeItem, "list")); //Retourne la liste des éléments de l'événement
		else
			$this->page()->addVar("url_page", $this->defaultPresItem("url_page", $listeItem)); // URL vers la page de présentation
	}
	
	public function executeAddEventElem(\Library\HTTPRequest $request) {
		if (!($request->existPost("type")))
			$this->app()->httpResponse()->redirect404();
		
		$typeVal = ($request->existPost("event") && $request->dataPost("event")) ? 0 : 1;
		
		$main = $this->managers()->getManagersOf("main")->get($request->dataGet("pres_id"));
		
		if ($main == null || !($main instanceof \Modules\Presentation\Entities\main && $main->deleted() == 0) || $main->type != $typeVal)
			$this->app()->httpResponse()->redirect403();
		
		$itemManager = $this->managers()->getManagersOf("item");
		$item = $itemManager->getLastList($request->dataGet("pres_id"));
		$listeItem = $itemManager->getList(array("liste_id = :pId"), array(array("key" => ":pId", "val" => $item->id(), "type" => \PDO::PARAM_INT)));
		
		if ($item->id() < 1)
			$item->setName("list_information"); 
			
		
		if (count($listeItem))
			$item = new \Modules\Presentation\Entities\item(array("liste_id" => $item->id(), "presentation_main_id" => $main->id(),"item" => "list", "name" => "tail"));
		
		if ($item->id() < 1) {
			$itemManager->send($item);
		}
		
		switch ($request->dataPost("type")) {
			case "para":
				$textManager = $this->managers()->getManagersOf("texte");
				$text = new \Modules\Presentation\Entities\texte();
				
				$textManager->send($text);
				
				$elem = new \Modules\Presentation\Entities\item(array("name" => "head", "liste_id" => $item->id(), "presentation_main_id" => $request->dataGet("pres_id"), "item" => "text", "val" => $text->id()));
				break;
			case "img":
				$elem = new \Modules\Presentation\Entities\item(array("name" => "head", "liste_id" => $item->id(), "presentation_main_id" => $request->dataGet("pres_id"), "item" => "img"));
				
				break;
			case "slider":
				$elem = new \Modules\Presentation\Entities\item(array("name" => "head", "liste_id" => $item->id(), "presentation_main_id" => $request->dataGet("pres_id"), "item" => "list"));
				break;
			default:
				$this->app()->httpResponse()->redirect403();
		}
		
		$itemManager->send($elem);
		
		$this->page()->addVar("valid", 1);
		$this->page()->addVar("item", $elem);
		
		$this->page()->setIsJson();
	}
	
	public function executeSendCouv(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		$mainManager = $this->managers()->getManagersOf("main");
		
		$main = $mainManager->get($request->dataGet("pres_id"));
		
		if (!$main instanceof \Modules\Presentation\Entities\main && $main->deleted() == 0) {
			$message[] = INVALID_ID;
		} else {
			if ($main->type() == 0 || $main->type() == 1) {
				$itemManager = $this->managers()->getManagersOf("item");
				
				$fileManager = $this->managers()->getManagersOf("file");
				
				$file = $fileManager->get($request->dataGet("file_id"));
				
				if ($file instanceof \Library\Entities\file) {
					$lItem = $itemManager->getList(array("presentation_main_id = :pId", "name= 'cover_img'"), array(array("key" => ":pId", "val" => $main->id(), "type" => \PDO::PARAM_INT)));
					
					if (count($lItem))
						$item = $lItem[0];
					else 
						$item = new \Modules\Presentation\Entities\item(array("presentation_main_id" => $main->id(), "name" => "cover_img", "item" => "img"));
					
					if ($item->key() > 0 && $item->key() != $file->id()) {
						$file = $fileManager->get($item->key());
						
						if ($file instanceof \Library\Entities\file) {
							$fileManager->delete($file->id());
							unlink($file->file_src() . $file->file_name());
						}
					}
					
					$item->setVal($request->dataGet("file_id"));
					
					$itemManager->send($item);
					
					$valid = 1;
					$this->page()->addVar("id", $item->key());
				} else {
					$message[] = NOT_VALID_FILE;
				}
			} else {
				$message[] = NOT_VALID_EVENT;
			}
		}
		
		$this->page()->setIsJson();
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
	}
	
	public function executeSendImg(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (!(is_numeric($request->dataGet("item_id")) && $request->existPost("img")))
			$message[] = INVALID_DATA_SENT;
		
		$itemManager = $this->managers()->getManagersOf("item");
		
		$item = $itemManager->get($request->dataGet("item_id"));
		
		if (!($item instanceof \Modules\Presentation\Entities\item) || $item->item != "img")
			$message[] = INVALID_ITEM_ID;
		
		$presManager = $this->managers()->getManagersOf("main");
		
		$pres = $presManager->get($item->presentation_main_id());
		
		if (!($pres instanceof \Modules\Presentation\Entities\main && $pres->deleted() == 0))
			$message[] = INCONSISTANT_DATA;
		
		if ($pres->type == 0 || $pres->type == 1) {
			$fileA = explode("-", $request->dataPost("img"));
			$fileId = substr($fileA[count($fileA)-1], 0, -4);
			
			if (!is_numeric($fileId))
				$message[] = INVALID_FILE;
			else {
				
				$fileManager = $this->managers()->getManagersOf("file");
				
				$file = $fileManager->get($fileId);
				
				if ($file->id() != $item->key() && $item->key() > 0) {
					$f = $fileManager->get($item->key());
					
					if ($f instanceof \Library\Entities\file) {
						$fileManager->delete($f->id());
						if (file_exists($f->file_src() . $f->file_name()))
						unlink($f->file_src() . $f->file_name());
					}
				}
				
				$item->setVal($file->id());
				
				$itemManager->send($item);
				
				$valid = 1;
				$this->page()->addVar("id", $item->id());
			}
		} else {
			$message[] = INVALID_PRES;
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		
		$this->page()->setIsJson();
	}
	
	public function executeDeletePres(\Library\HTTPRequest $request) {
		$message = array();
		$valid = 0;
		if (!is_numeric($request->dataGet("pres_id")))
			$message[] = INVALID_ID;
		else {
			$mainManager = $this->managers()->getManagersOf("main");
			
			$main = $mainManager->get($request->dataGet("pres_id"));
			
			if ($main instanceof \Modules\Presentation\Entities\main && $main->deleted() == 0) {
				$itemManager = $this->managers()->getManagersOf("item");
				
				$listItem = $itemManager->getList(array("presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $main->id(), "type" => \PDO::PARAM_INT)));
				
				foreach ($listItem AS $item) {
					$this->removeItem($item);
				}
				
				$mainManager->deleteFromCategorie($main->id());
				
				$mainManager->delete($main->id());
			}
			
			$valid = 1;
		}

		$this->page()->addVar("message", $message);
		$this->page()->addVar("valid", $valid);
		
		$this->page()->setIsJson();
	}
	
	public function executeListe(\Library\HTTPRequest $request) {	
		$mainManager = $this->managers()->getManagersOf("main"); 	
		$listePres = $mainManager->getList(array("type = 0 OR type = 1", "deleted = 0"));
		
		$listePayed = array_map(function ($arg) {return $arg->presentation_main_id();},$this->managers()->getManagersOf("payement")->getList(array("YEAR(date_payement) = YEAR(CURDATE())")));
		
		$userManager = $this->managers()->getManagersOf("user");
		
		foreach ($listePres AS $pres) {
			$pres->setPayed(in_array($pres->id, $listePayed));
			$listeUser = $mainManager->getListeUserForPres($pres->id());
			
			if (count($listeUser)) {
				$pres->setListeUser($userManager->getList(array("id IN (" . implode(", ", array_map(function ($arg){return $arg["id"];}, $listeUser)) . ")")));
			}
			
			$pres->setCategorie($this->managers()->getManagersOf("categorie")->getListFromMain($pres->id()));
		}
		
		$this->page()->addVar("liste_categorie", $this->managers()->getManagersOf("categorie")->getList());
		
		$this->page()->addVar("listePres", $listePres);
	}
	
	public function executeChangeCategorie(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (!($request->existPost("pres_id") && is_numeric($request->dataPost("pres_id")) && $request->existPost("cate_id") && is_numeric($request->dataPost("cate_id"))))
			$message[] = ERROR_ON_RETRIEVING_DATA;
		else {
		
			$mainManager = $this->managers()->getManagersOf("main");
			$categorie = $this->managers()->getManagersOf("categorie")->get($request->dataPost("cate_id"));
			
			$pres = $mainManager->get($request->dataPost("pres_id"));
		
			if (!($pres instanceof \Modules\Presentation\Entities\main && $pres->deleted() == 0 && $categorie instanceof \Modules\Presentation\Entities\categorie))
				$message[] = INVALID_ID;
			else {
				$mainManager->deleteFromCategorie($pres->id());
				$mainManager->addToCategorie($pres->id(), $categorie->id());
				
				$valid = 1;
			}
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		
		$this->page()->setIsJson();
	}
	
	public function executeChangeStatus(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (!($request->existPost("id") && is_numeric($request->dataPost("id")) && $request->existPost("changed")))
			$message[] = ERROR_ON_RETRIEVING_DATA;
		else {
			$mainManager = $this->managers()->getManagersOf("main");
			
			$main = $mainManager->get($request->dataPost("id"));
			
			if ($main instanceof \Modules\Presentation\Entities\main && $main->deleted() == 0) {
				$main->setPublished($request->dataPost("changed"));
				$mainManager->send($main);
				$valid = 1;
			} else
				$message[] = INVALID_ID;
		}
		
		$this->page()->addVar("message", $message);
		$this->page()->addVar("valid", $valid);
		
		$this->page()->setIsJson();
	}
	
	public function executeChangePayement(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (!($request->existPost("id") && is_numeric($request->dataPost("id")) && $request->existPost("changed")))
			$message[] = ERROR_ON_RETRIEVING_DATA;
		else {
			$mainManager = $this->managers()->getManagersOf("main");
			
			$main = $mainManager->get($request->dataPost("id"));
			
			if ($main instanceof \Modules\Presentation\Entities\main && $main->deleted() == 0) {
				switch ($request->dataPost("changed")) {
					case 0:
						$this->managers()->getManagersOf("payement")->deleteList(array("YEAR(date_payement) = YEAR(CURDATE())", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $main->id(), "type" => \PDO::PARAM_INT)));
						break;
					case 1:
						$this->managers()->getManagersOf("payement")->send(new \Modules\Presentation\Entities\payement(array("presentation_main_id" => $main->id, "date_payement" => new \DateTime())));
						break;
				}
				$valid = 1;
			} else
				$message[] = INVALID_ID;
		}
		
		$this->page()->addVar("message", $message);
		$this->page()->addVar("valid", $valid);
		
		$this->page()->setIsJson();
	}
	
	public function executeShowEvent(\Library\HTTPRequest $request) {
		if (!($request->existGet("pres_id") && is_numeric($request->dataGet("pres_id"))))
			$this->app()->httpResponse()->redirect404();
		
		$isEvent = !($request->existGet("actualite") && $request->dataGet("actualite") == 1);
		
		$mainManager = $this->managers()->getManagersOf("main");
		
		
		$main = $mainManager->get($request->dataGet("pres_id"));
		
		if ($main == null || !($main instanceof \Modules\Presentation\Entities\main && $main->deleted() == 0) || !($main->type() == 0 || $main->type() == 1))
			$this->app()->httpResponse()->redirect404();
		
		$itemManager = $this->managers()->getManagersOf("item");
		
		$itemManager->setDateManager($this->managers()->getManagersOf("date"));

		$listeItem = $itemManager->getFromPres($main->id(), $this->managers()->getManagersOf("texte"));
		
		$this->page()->addVar("event", $main);
		
		$this->page()->addVar("isEvent", $isEvent);
	
		$this->page()->addVar("cover_img", $this->defaultPresItem("cover_img", $listeItem, "img")); //Image de couverture de l'événement
		$this->page()->addVar("date_event", $this->defaultPresItem("date_event", $listeItem, "date", $main->id())); //Date de l'événement au format \DateTime
		$this->page()->addVar("end_date", $this->defaultPresItem("end_date", $listeItem, "date", $main->id())); //Date de l'événement au format \DateTime
		$this->page()->addVar("base_txt", $this->defaultPresItem("base_txt", $listeItem, "text", $main->id())); //Texte de base
		
		if ($isEvent)
			$this->page()->addVar("list_information", $this->defaultPresItem("list_information", $listeItem, "list")); //Retourne la liste des éléments de l'événement
		else
			$this->page()->addVar("url_page", $this->defaultPresItem("url_page", $listeItem)); // URL vers la page de présentation
	}
	
	public function executeSearchCate(\Library\HTTPRequest $request) {
		$this->page()->addVar("listeCate", $this->managers()->getManagersOf("categorie")->getList(array(), array(), array(array("key" => "default_name", "order" => "ASC"))));
		$this->page()->addVar("pres_type", ($request->existGet("pres_type")) ? array($request->dataGet("pres_type")) : array(1, 0));
		$this->page()->addVar("base_search", ($request->existPost("base_search")) ? $request->dataPost("base_search") : "");
	}
	
	public function removeItem($item) {
		$itemManager = $this->managers()->getManagersOf("item");
		
		if ($item instanceof \Modules\Presentation\Entities\item) {
			switch ($item->item()) {
				case "text":
					$this->managers()->getManagersOf("texte")->delete($item->key());
					break;
				case "date":
					$this->managers()->getManagersOf("date")->delete($item->key());
					break;
				case "img":
					$file = $this->managers()->getManagersOf("file")->get($item->key());
					if ($file instanceof \Library\Entities\file) {
						if (file_exists($file->file_src() . $file->file_name())){
							@unlink($file->file_src() . $file->file_name());
						}
						$this->managers()->getManagersOf("file")->delete($item->key());
					}
				case "list":
					$liste = $itemManager->getList(array("liste_id = :pId"), array(array("key" => ":pId", "val" => $item->id(), "type" => \PDO::PARAM_INT)));
					foreach ($liste AS $i)
						$this->removeItem($i);
					break;
			}
			
			$itemManager->delete($item->id());
		}
	}
	
	public function executeRemoveItem(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		if (!is_numeric($request->dataGet("item_id")))
			$message[] = ERROR_ON_RETRIEVING_DATA;
		else {
			$itemManager = $this->managers()->getManagersOf("item");
			$item = $itemManager->get($request->dataGet("item_id"));
			
			if (!($item instanceof \Modules\Presentation\Entities\item))
				$message[] = INVALID_ID;
			else {
				$main = $this->managers()->getManagersOf("main")->get($item->presentation_main_id());
				
				if (!($main instanceof \Modules\Presentation\Entities\main && $main->deleted() == 0 && ($main->type() == 0 || $main->type() == 1)))
					$message[] = ERROR_NOT_EVENT;
				else {
					
					$this->removeItem($item);
					
					$valid = 1;
				}
			}
		}
		
		$this->page()->addVar("message", $message);
		$this->page()->addVar("valid", $valid);
		
		$this->page()->setIsJson();
	}
	
	public function executeLoadActivite(\Library\HTTPRequest $pRequest) {
		$this->page()->addVar("valid", 1);
		
		$formatDate = \Utils::getDateFormat(\Utils::getFormatLanguage($this->app()->user()->getLanguage()));
		$managers = $this->managers();
		$itemManager = $this->managers()->getManagersOf("item");
		$itemManager->setDateManager($this->managers()->getManagersOf("date"));
		$this->page()->addVar("activite", array_filter($this->managers()->getManagersOf("main")->getList(array("published = 1")), function ($a) use ($managers, $itemManager, $formatDate) {
			return $a->setAttribute("date", (
					count($listeItem = $managers->getManagersOf("item")->getList(array("name = 'date_event'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $a->id(), "type" => \PDO::PARAM_INT)))) > 0
					&& ($item = $listeItem[0]) instanceof \Modules\Presentation\Entities\item
					&& ($date = $managers->getManagersOf("date")->get($item->key())) instanceof \Modules\Presentation\Entities\date) ? \Utils::formatDate($date->val(), $formatDate[1]) : "-");
		}));
		
		$this->page()->setIsJson();
	}
	
	public function executeEvent(\Library\HTTPRequest $pRequest) {
		
		$isEvent = !($pRequest->existGet("actualite") && $pRequest->dataGet("actualite") == 1);
		
		$mainManager = $this->managers()->getManagersOf("main");
		$itemManager = $this->managers()->getManagersOf("item");
		$dateManager = $this->managers()->getManagersOf("date");
		$texteManager = $this->managers()->getManagersOf("texte");
		$fileManager = $this->managers()->getManagersOf("file");
		
		$listeEvent = $mainManager->getListDated((($isEvent) ? 0 : 1), !($this->app()->user()->getAdminLvl() > 5));
		
		$listeNotShown = array();
		$listeDated = array();
		
		foreach ($listeEvent AS $event) {
			$date = $itemManager->getList(array("name = 'date_event'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
			$end_date = $itemManager->getList(array("name = 'end_date'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
			
			$link = $this->managers()->getManagersOf("item")->getList(array("name = 'url_page'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
			
			if (count($link)) {
				$event->link = $link[0];
			}
			
			
			
			if (count($date)) {
				$event->date = $dateManager->get($date[0]->key());
				if (count($end_date)) {
					$event->end_date = $dateManager->get($end_date[0]->key());
				}
				
				$img = $itemManager->getList(array("name = 'cover_img'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
				
				if (count($img)) {
					$event->img = $fileManager->get($img[0]->key());
				} else {
					$event->img = null;
				}
				
				$base_txt = $itemManager->getList(array("name = 'base_txt'", "presentation_main_id = :pId"), array(array("key" => ":pId", "val" => $event->id(), "type" => \PDO::PARAM_INT)));
				
				if (count($base_txt)) {
					$event->base_txt = $texteManager->get($base_txt[0]->key());
				} else {
					$event->base_txt = null;
				}
				
				$listeDated[] = $event;
			} else 
				$listeNotShown[] = $event;
			
		}
		
		$this->page()->addVar("listeEvent", $listeDated);
		$this->page()->addVar("listeNotDated", $listeNotShown);
		$this->page()->addVar("isAdmin", $this->app()->user()->getAdminLvl() > 5);
		$this->page()->addVar("isEvent", $isEvent);
	}
	
	public function executeLiens(\Library\HTTPRequest $request) {
		$managers = $this->managers()->getManagersOf("item");
		$this->page()->addVar("listeLiens", array_map(function ($a) use ($managers) {return array(
				"url" => (count($link = $managers->getList(array("name = 'liens_url'", "liste_id = " . $a->id()))) > 0) ? $link[0]->val() : "",
				"name" => (count($name = $managers->getList(array("name = 'liens_name'", "liste_id = " . $a->id()))) > 0) ? $name[0]->val() : "",
				"elem" => $a
		);}, $this->managers()->getManagersOf("item")->getList(array("name = 'liste_liens'"))));
	}
	
	public function executeSendLiens(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		if (!($request->existPost("url") && ($url = $request->dataPost("url")) != "" && \Utils::testUrl($url)))
			$message[] = "L'URL n'est pas dans un format correcte";
		
		if (!($request->existPost("name") && ($name = $request->dataPost("name")) != ""))
			$message[] = "Merci d'entrer une valeur comme nom du liens";
		
		if (count($message) == 0) {
			$item = new \Modules\Presentation\Entities\item(array("name" => "liste_liens", "item" => "list"));
			
			$this->managers()->getManagersOf("item")->send($item);
			
			if ($item->id() > 0) {
				$url = $this->managers()->getManagersOf("item")->send(new \Modules\Presentation\Entities\item(array("name" => "liens_url", "item" => "elem", "val" => $url, "liste_id" => $item->id())));
				$name = $this->managers()->getManagersOf("item")->send(new \Modules\Presentation\Entities\item(array("name" => "liens_name", "item" => "elem", "val" => $name, "liste_id" => $item->id())));
				
				$valid = 1;
			} else {
				$message[] = "Error on DB insertion";
			}
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
	
	public function executeRemoveLiens(\Library\HTTPRequest $request) {
		$valid = 0;
		$message = array();
		
		if (!is_numeric($id = $request->dataPost("id")))
			$message[] = "L'ID n'est pas valide";
		else {
			$this->managers()->getManagersOf("item")->deleteList(array("id = :pId OR liste_id = :pId"), array(array("key" => ":pId", "val" => $id, "type" => \PDO::PARAM_INT)));
			
			$valid = 1;
		}
		
		$this->page()->addVar("valid", $valid);
		$this->page()->addVar("message", $message);
		$this->page()->setIsJson();
	}
}

?>