<?php

namespace Library;

if (!defined("EVE_APP"))
	exit();

/**
 * Specific router using PDO
 * 
 * This {@see \Library\Router} is used to get all the different {@see \Library\Route} from a DB using PDO. It has two functions. One to get all the different roads and one to get the default value of those roads.
 *
 * @see \Library\Router
 *
 * @copyright ParaGP Swizerland
 * @author Zellweger Vincent
 * @version 1.0
 */
class Router_PDO extends Router {
	/**
	 * (non-PHPdoc)
	 * @see \Library\Router::setRoutes()
	 */
	public function setRoutes(){
		$dao = PDOFactory::getMysqlConnexion();
		
		$requete = $dao->prepare('SELECT id, url, module, action, vars, admin_lvl, admin_lvl, changeable, page_type, title, description, parent_id, on_menu, only_dyn, user_id, date_crea FROM routes;');
		$requete->execute();
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Route');
		
		$routes = $requete->fetchAll();
		
		foreach($routes AS $route){
			$this->addRoute($route);
		}
		
		return 1;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Library\Router::addDefaultVal()
	 */
	public function addDefaultVal(Route $pRoute) {
		$dao = PDOFactory::getMysqlConnexion();
		
		$requete = $dao->prepare('SELECT `route_key`, `route_val`, `force_val` FROM routes_def_val WHERE route_id = :pId;');
		
		$requete->bindValue(":pId", $pRoute->id(), \PDO::PARAM_INT);
		
		$requete->execute();
		$requete->setFetchMode(\PDO::FETCH_ASSOC);
		
		$listeVal = $requete->fetchAll();
		
		foreach($listeVal AS $val){
			$pRoute->addVarInListe($val["route_key"], $val["route_val"], $val["force_val"]);
		}
		
		return $pRoute;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Library\Router::getDynamicPage()
	 */
	public function getDynamicPage(Route $pRoute) {
		$dao = PDOFactory::getMysqlConnexion();
		
		$requete = $dao->prepare('SELECT id, date_add, date_modif, page_content, date_end, visible, routes_id FROM dynamic_page WHERE routes_id = :pId ORDER BY date_modif DESC LIMIT 0, 1;');
		
		$requete->bindValue(":pId", $pRoute->id(), \PDO::PARAM_INT);
		
		$requete->execute();
		
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\Dynamic_page');
		
		$ret = $requete->fetch();
		
		if ($ret instanceof \Library\Dynamic_page)
			return $ret;
		else
			return new \Library\Dynamic_page();
	}
}

?>