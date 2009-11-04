<?php
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/user/UserProfile.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';
include_once PHP_CLASS.'entities/location/Country.class.php';
include_once PHP_CLASS.'entities/oproep/OproepUtils.class.php';
include_once PHP_CLASS.'entities/oproep/OproepFactory.class.php';
include_once PHP_CLASS.'entities/oproep/OproepFormFactory.class.php';
include_once PHP_CLASS.'entities/photo/Photo.class.php';
include_once PHP_CLASS.'image/UploadedImage.class.php';
include_once PHP_CLASS.'entities/note/Note.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocation.class.php';
include_once SMARTY_PLUGIN_DIR.'SmartyPaginate.class.php';


class ZoekenPage extends Page {
	
	public function init() {
		$this->setHeader("Op zoek naar je flirt!");
	}
	
	/**
	 * @WebAction
	 */
	public function overview() {
		
		$title = $this->makeTitle();
		$this->setTitle($title);
		global $smarty;
		
		SmartyPaginate::disconnect();
		SmartyPaginate::connect();
		$url = new Url($_SERVER['REQUEST_URI']);
		$url->remove("next");
		SmartyPaginate::setUrl($url->toString());
		
		SmartyPaginate::setLimit(10);
		SmartyPaginate::setNextText("Volgende");
		SmartyPaginate::setPrevText("Vorige");
		
		$oq = $this->getObjectQuery($this);
		$results = SearchObject::search($oq);
		$this->put("searchresults", $results);
		$this->put("noOfResults", $oq->getCountRows());
		
		SmartyPaginate::setTotal($oq->getCountRows());
		SmartyPaginate::assign($smarty);
		
		$this->initLabels();
		
		$cat = $this->get("category");
		if(!empty($cat)) {
			$json = OproepFormFactory::getSearchFields($cat);
			$this->put("fields", $json);
			$this->put("entity", $this);
		}
	}
	/**
	 * 
	 */
	private function makeTitle() {
		$title = "";
		$cat = $this->get("category");
		$spotname = $this->get("spotname");
		$cityname = $this->get("cityname");
		
		$details = array();
		if (!Utils::isEmpty($spotname)) {
			$details[] = $spotname;
		}
		if (!Utils::isEmpty($cityname)) {
			$details[] = $cityname;
		}
		$sDetails = join(", ", $details);
		
		if (!Utils::isEmpty($cat)) {
			if (Utils::isEmpty($sDetails)) {
				$sDetails .= OproepEntity::$CATEGORIES[$cat];
			} else {
				$sDetails .= " ( " . OproepEntity::$CATEGORIES[$cat] . ")";
			}
		}
		
		if (Utils::isEmpty($sDetails)) {
			$title = "Zoeken naar je flirt - Reflirt.nl";
		} else {
			$title = "Zoeken naar je flirt: " . $sDetails . " - Reflirt.nl";
		}
		
		return $title;
	}

	
	/**
	 * Als je komt vanuit Mijn Overzicht
	 * @WebAction
	 * @Login
	 */
	public function myresults() {
		$systemid = $this->get("id");
		$ds = $this->getAllFromSavedOproep($systemid);
		
		global $smarty;
		
		SmartyPaginate::disconnect();
		SmartyPaginate::connect();
		$url = new Url($_SERVER['REQUEST_URI']);
		$url->remove("next");
		SmartyPaginate::setUrl($url->toString());
		
		SmartyPaginate::setLimit(10);
		SmartyPaginate::setNextText("Volgende");
		SmartyPaginate::setPrevText("Vorige");
		
		$oq = $this->getObjectQuery($ds);
		$results = SearchObject::search($oq);
		$this->put("searchresults", $results);
		$this->put("noOfResults", $oq->getCountRows());
		
		SmartyPaginate::setTotal($oq->getCountRows());
		SmartyPaginate::assign($smarty);
		
		$this->initLabels();
		$this->setTemplate("overview");
		$this->putAll($ds);
		
	}
	
	private function getObjectQuery($params) {
		$oq = ObjectQuery::build(new OproepEntity(), UserFactory::getSystemUser());		
		$oq->addParameters($params);
		$oq->setSearcher(OproepEntity::getSearcher());
		$oq->setLimit(SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
		$oq->setCountRows(true);
		return $oq;
	}
	
	
	private function initLabels() {
		$this->put("categories", Spot::getCategories(array("" => "Kies...")));
		$this->put("categoriesSimple", Spot::getCategories(array("" => "Allen")));
		$this->put("catLabels", json_encode(Spot::$CATEGORIES_LABELS));
		$this->put("catShortNames", json_encode(Spot::$SHORTNAMES));
		$this->put("checkboxesSex", array(1 => " Man", 0 => " Vrouw"));
	}
	
	/**
	 * @WebAction
	 * @Login
	 */
	public function compose() {
		$this->initLabels();
		
		// Laatste spots
		$oq = ObjectQuery::build(new OproepEntity(), UserFactory::getSystemUser(), 100);
		$oq->addParameters($this);
		$oq->setSearcher(OproepEntity::getSearcher());
		$laatsteOproepen = SearchObject::search($oq);
		shuffle($laatsteOproepen);
		
		$this->put("laatsteOproepen", array_slice($laatsteOproepen, 0, 5));
	}
	
	
	/** @Ajax */
	public function getform() {
		$cat = $this->getString("category");
		$this->put("shortname", Spot::getShortnameByCategoryNr($cat));
		$this->put("catlabel", Spot::$CATEGORIES[$cat]);	
	}
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function save() {
		$cat = $this->get("category");
		Utils::validateNotEmpty("Kies een category", $cat);
		
		$oproep = OproepFactory::getOproep($cat);
		$oproep->setUser($this->getUser());
		$oproep->putAll($this);
		$oproep->put("category", $this->get("category"));
		$systemid = $this->get("id");
		if (!empty($systemid)) {
			$oproep->setKey($systemid);
		}
		$oproep->save();
		return array("newLocation" => "/?page=zoeken&action=myresults&id=" . $oproep->getKey());
	}
	
	/**
	 * @WebAction
	 */
	public function saved() {
		$this->setHeader("Oproep bewaard");
		$this->success("Je zoekopdracht is bewaard");
	}
	
	/**
	 * @WebAction
	 * @Login
	 */
	public function edit() {
		$this->setHeader("Oproep wijzigen");
		$systemid = $this->get("id");
		
		$this->initLabels();
		$ds = $this->getAllFromSavedOproep($systemid);
		$this->putAll($ds);
	}
	
	private function getAllFromSavedOproep($systemid) {
		$ent = EntityFactory::loadEntity(new OproepEntity, $this->getUser(), $systemid);
		Utils::assertNotNull("Kan de oproep niet vinden", $ent);
		
		$ds = new DataSource();
		$ds->putAll($ent);
		
		$city = $ent->getCity();

		
		$ds->put("cityname", $city->get("cicityname"));
		$ds->put("cityid", $city->getKey());
		$spot = $ent->getSpot();
		
		if ($spot) {
			$ds->put("spotname", $spot->getName());
			$ds->put("spotid", $spot->getKey());
		}
		
		
		$cat = $ent->get("category");
		$json = OproepFormFactory::getSearchFields($cat);
		$ds->put("fields", $json);
		$ds->put("entity", $ent);
		return $ds;
	}
	
	
}

?>
