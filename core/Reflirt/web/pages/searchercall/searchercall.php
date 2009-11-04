<?php
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';
include_once PHP_CLASS.'entities/oproep/OproepFactory.class.php';
include_once PHP_CLASS.'entities/oproep/OproepUtils.class.php';
include_once PHP_CLASS.'entities/oproep/DiscoOproep.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/tasks/Task.class.php';
include_once PHP_CLASS.'entities/zoekopdracht/Zoekopdracht.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';
include_once PHP_CLASS.'entities/location/Country.class.php';
include_once PHP_CLASS.'utils/StopWatch.class.php';

/**
 * @Login
 */
class SearchercallPage extends Page {

	
	/** 
	 * @WebAction
	 */ 
	public function overview() {
		$oproepen_reflirt = Zoekopdracht::getZoekopdrachten($this->getUser(), Zoekopdracht::$TYPE_REFLIRT);
		$oproepen_preflirt = Zoekopdracht::getZoekopdrachten($this->getUser(), Zoekopdracht::$TYPE_PREFLIRT);
		
		$this->put("searchers_reflirt", $oproepen_reflirt);
		$this->put("searchers_preflirt", $oproepen_preflirt);
	}
	
	/**
	 * Scherm waar je keuze moet maken welke categorie
	 */
	/** @WebAction */
	public function intro() {
		$categories = Oproep::$CATEGORIES;
		$categories = array_merge_recursive(array("-1" => "Selecteer..."), $categories);
		$this->put("categories", $categories);
	}
	
	/** Scherm waar je een opdracht aanmaakt, dus NIET het saven ervan */
	/** @WebAction */
	public function create() {
		$cat = $this->getString("cat");
		
		$shortName = OproepUtils::getShortCatName($cat);
		$friendlyName = OproepUtils::getFriendlyCatName($cat);

		$this->put("categoryname", $friendlyName);
		$this->put("countries", Country::createCountryPulldownArray());
		$this->put("country", "1");
		$this->put("lastrelevantsearchers", $this->getLastSearchersByCat($cat));
		$this->put("shortname", $shortName);
		$this->put("select_sex", Utils::getArrayForSex());
	}
	
	/** Actie na het aanmaken/wijzigen van een opdracht */
	/** @WebAction */
	public function save() {
		$cat = $this->getString("cat");
		
		$systemid = $this->getString("id");
		Utils::assertTrue("Onbekende categorie: ".$cat, isset(Oproep::$CATEGORIES[$cat]));
		$oproep = OproepFactory::getOproep($cat);
		if(!Utils::isEmpty($systemid)) {
			$oproep->setKey($systemid);
		}
		$oproep->putAll($this->getFields());
		
		$oproep->setUser($this->getUser());
		$oproep->save();
		header("location: /?page=searchercall&action=created&id=".$oproep->getKey()."&cat=".$cat);
	}
	
	/** Lijkt me duidelijk..  */
	/** @WebAction */
	function delete() {
		$systemid = $this->getString("id");
		$cat = $this->getString("cat");
		$oproep = OproepFactory::getOproep($cat);
		EntityFactory::deleteEntity($oproep, $this->getUser(),
			$systemid);
		$this->overview();
//		
//		$oq = ObjectQuery::buildACS(new zoekopdracht(), $this->getUser());
//		$list = SearchObject::search($oq);
//		$this->put("searchers", $list);
		$this->setTemplate("overview");
		throw new UserFriendlyMessage("De oproep is verwijderd");
	}
	
	
	/**
	 * Wijzigen van een opdracht
	 * @WebAction
	 */ 
	public function modify() {
		$systemid = $this->getString("id");
		$cat = $this->getString("cat");
		$type = OproepFactory::getOproep($cat);
		
		$oproep = EntityFactory::loadEntity($type, $this->getUser(), $systemid);
		
		$this->putAll($oproep);
		
		$shortName = $oproep->getShortCatName();
		$friendlyName = $oproep->getFriendlyCatName();
		
		$this->put("select_sex", Utils::getArrayForSex());
		$this->put("dsex", $oproep->getString("dsex"));
		$this->put("lastrelevantsearchers", $this->getLastSearchersByCat($cat));
		//$this->put("newtemplate", "searchercall/searchercall.form.".$shortName.".tpl");
		$this->put("shortname", $oproep->getShortCatName($cat));
		$this->put("categoryname", $friendlyName);
	}
	
	
	/**
	 * 	Nadat een opdract is aangemaakt/gewijzgd
	 *  @WebAction 
	 */
	public function created() {
		$cat = $this->getString('cat');
		$oproep = OproepFactory::getOproep($cat);
		$oproep->setKey($this->getString("id"));
		$oproep->setUser($this->getUser());
		$oproep->load();
		
		$this->put("oproep", $oproep);
	}
	
	
	/**
	 * Laatste zoekopdrachten van de category waar de gebruiker zich nu in 
	 * bevind tijdesn het aanmaken/wijzigen 
	 */	
	private function getLastSearchersByCat($cat) {
		$oproep = OproepFactory::getOproep($cat);
		$oq = ObjectQuery::build($oproep, $this->getUser());
		$oq->setSearcher($oproep->getDefaultSearcher());
		$oq->addParameter("table", $oproep->getTable()->getTableName());
		$oq->addParameter("category", $cat);
		$list = SearchObject::search($oq);
		return $list;
	}
	
	/**
	 * Bekijken van een opdracht van iemand anders, hoogstwaarschijnlijk kom je 
	 * hier na het klikken op de knop "Bekijk" bij zoekresultaten.
	 * @WebAction
	 */
	public function view() {
		$cat = $this->getString("cat");	
		$user = UserFactory::getSystemUser();
		$systemid = $this->getString("id");
		$type = OproepFactory::getOproep($cat);
		$ent = EntityFactory::loadEntity($type, $user, $systemid);
		$this->put("oproep", $ent);
	}
	
	 
	
}

?>
