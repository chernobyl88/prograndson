<?php

namespace Modules\Dynamic_page;

if (!defined("EVE_APP"))
	exit();

class Dynamic_pageController extends \Library\BackController {
	
	/**
	 * The current dynamic page that has to be used
	 * 
	 * @var \Library\Dynamic_page
	 */
	protected $dyn_page;
	
	/**
	 * Setter for the given dynamic page
	 * 
	 * @param \Library\Dynamic_page $page
	 */
	public function setDyn_page(\Library\Dynamic_page $page) {
		$this->dyn_page = $page;
	}
	
	/**
	 * Method that implement a dynamic page that has been provided by an administrator
	 * 
	 * @param \Library\HTTPRequest $request
	 * 
	 * @throws \InvalidArgumentException
	 * 			If the dynamic page is not set
	 */
	public function executeIndex(\Library\HTTPRequest $request) {
		if (!isset($this->dyn_page) || is_null($this->dyn_page))
			throw new \InvalidArgumentException();
		
		$this->page()->addVar("page_content", $this->dyn_page()->page_content());
	}
	
	public function executeListPage(\Library\HTTPRequest $request) {
		$routeManager = $this->managers()->getManagersOf("routes");
		
		$listeRoute = $routeManager->getList(array("changeable = 1"));
		
		foreach ($listeRoute AS $route)
			$listeRoute->setParent_route($routeManager->get($route->parent_id()));
		
		$this->page()->addVar("listeRoute", $listeRoute);
	}
	
	private function getSubRoute($pRoute_id, \Library\Manager $manager, $sub = "-") {
		$subGroupe = $manager->getList(array("parent_id = :pId", "on_menu = 1"), array(array("key" => ":pId", "val" => $pRoute_id, "type" => \PDO::PARAM_INT)));
		
		$ret = array();
		
		foreach ($subGroupe AS $elem) {
			if (!($elem->title() == null || strtolower($elem->title()) == "null"))
				$ret[] = array(
					"id" => $elem->id(),
					"name" => $sub . " " . $elem->title(),
					"sub" => $this->getSubRoute($elem->id(), $manager, $sub . "-")
				);
		}
		
		return $ret;
	} 
	
	public function executeModif(\Library\HTTPRequest $request) {
		if (!($request->existGet("route_id") && is_numeric($request->dataGet("route_id"))))
			$this->app()->httpResponse()->redirect404();
		
		$routeManager = $this->managers()->getManagersOf("routes");
		
		$route = $routeManager->get($request->dataGet("route_id"));
		
		if (!($route instanceof \Library\Entities\routes))
			$this->app()->httpResponse()->redirect404();
		
		if ($route->changeable() == 0)
			$this->app()->httpResponse()->redirect403();
		
		$dynamicManager = $this->managers()->getManagersOf("dynamic_page");
		
		$listeRoute = $routeManager->getList(array("parent_id = 0 OR on_menu = 0"));
		
		$noGroupe = array();
		$listeGroupe = array();
		
		foreach ($listeRoute AS $route) {
			if ($route->title != null && strtolower($route->title()) != "null")
				if ($route->on_menu == 1) {
					$listeGroupe[] = array(
						"id" => $route->id(), 
						"name" => $route->title(),
						"sub" => $this->getSubRoute($route->id(), $routeManager)
					);	
				} else
					$noGroupe[] = array(
						"id" => $route->id(), 
						"name" => $route->title(),
						"sub" => array()
					);	
		}
		
		$this->page()->addVar("liste_img", $this->managers()->getManagersOf("file")->getList(array("dynamic = 1")));
		$this->page()->addVar("liste_groupe", $listeGroupe);
		$this->page()->addVar("no_groupe", $noGroupe);
		
		$this->page()->addVar("cRoute", $route);
		
		$this->page()->addVar("page", $dynamicManager->getDynamicFromRoute($route->id()));
	}
	
	public function executeAddPage(\Library\HTTPRequest $request) {
		if (!($request->existPost("name")))
			$this->app()->httpResponse()->redirect404();
		
		$route = new \Library\Entities\routes(array("url" => "/Dyn/".rand()."html", "title" => $request->dataPost("name"), "changeable" => 1, "page_type" => "html", "only_dyn" => 1, "user_id" => $this->app()->user()->id(), "date_crea" => new \DateTime()));
		
		$this->managers()->getManagersOf("routes")->send($route);
		
		if ($route->id() > 0) {
			$this->page()->addVar("valid", 1);
			$this->page()->addVar("id", $route->id());
		} else {
			$this->page()->addVar("valid", 0);
			$this->page()->addVar("message", "Error on DB insertion");
		}
		
		$this->page()->setIsJson();
	}
}

?>