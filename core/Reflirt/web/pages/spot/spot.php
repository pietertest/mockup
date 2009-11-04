<?php
include_once PHP_CLASS.'entities/oproep/DiscoOproep.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/tasks/Task.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';
include_once PHP_CLASS.'entities/oproep/OproepFactory.class.php';
include_once PHP_CLASS.'entities/oproep/OproepFormFactory.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';
include_once PHP_CLASS.'entities/location/Country.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/spot/SpotFactory.class.php';
include_once PHP_CLASS.'entities/spot/MySpot.class.php';
include_once PHP_CLASS.'entities/spot/SpotPhoto.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocationUtils.class.php';
include_once PHP_CLASS.'entities/agenda/Agenda.class.php';
include_once PHP_CLASS.'entities/spot/SpotAgenda.class.php';

class SpotPage extends Page {
	
	
	/** @WebAction */
	public function overview() {}
	
	/** @WebAction */
	public function view() {
		$systemid = $this->getString("id");
		$systemUser = UserFactory::getSystemUser();
		$spot = EntityFactory::loadEntity(new Spot, $systemUser, $systemid);
		$this->put("spot", $spot);
		if ( isset($_SESSION["uid"]) ) {
			$oq = ObjectQuery::buildACS(new MySpot(), $this->getUser());
			$oq->addConstraint(Constraint::eq("spotid", $spot->getKey()));
			$inmyspots = SearchObject::select($oq);
			if ($inmyspots) {
				$this->put("inmyspots", true);
			} else {
				$this->put("inmyspots", false);
			}
		}
		// Agendapunt
		$this->put("comingagendas", $spot->getComingAgendas(2));
	}
	
	/**
	 * Fotos bekijken van de betreffende spot.
	 * 
	 * @Ajax
	 */
	public function viewphotos() {
		$this->checkValidSpot();
		$spotSystemid = $this->getInt("id", -1);
		$systemUser = UserFactory::getSystemUser();
		$oq = ObjectQuery::build(new SpotPhoto(), $systemUser);
		$oq->addParameter("spotid", $spotSystemid);
		$oq->setSearcher(SpotPhoto::getPhotoSearcher());
		$photos = SearchObject::search($oq);
		$this->put("photos", $photos);
	}
	
	/**
	 * Gebruikers bekijken uit de betreffende post.
	 * @Ajax
	 */
	public function people() {}
	
	/**
	 * Agenda - Agendapunten bekijken van de spot
	 * 
	 * @Ajax
	 */
	public function agenda() {
		$this->checkValidSpot();
		$spotSystemid = $this->getInt("id", -1);
		$systemUser = UserFactory::getSystemUser();
		$spot = EntityFactory::loadEntity(new Spot, $systemUser, $spotSystemid);
		$this->put("comingagendas", $spot->getComingAgendas(2));
		$this->put("passedagendas", $spot->getPassedAgendas(31));
//		$systemUser = UserFactory::getSystemUser();
//		$oq = ObjectQuery::build(new SpotAgenda(), $systemUser);
//		$oq->addParameter("spotid", $spotSystemid);
//		$oq->setSearcher(SpotAgenda::getAgendaSearcher());
//		$agendas= SearchObject::search($oq);
//		$this->put("agendas", $agendas);
	}
	
	/**
	 * Dit is om via Ajax een spot toe te voegen
	 * @Login
	 * @JSON
	 * 
	 */
	public function addnewspot() {
		$systemUser = UserFactory::getSystemUser();
		$cat = $this->getString("cat");
		$spotname = $this->get("spotname");
		Utils::validateNotEmpty("Een lege waarde is niet toegestaan.". 
			"Vul een naam in voor de spot die je wilt toevoegen", $spotname, "spotname");
		$spot = new Spot();
		$cityid = $this->get("cityid");
		Utils::validateNotEmpty("Kies een plaatsnaam", $cityid, "cityname");
		$spot->put("name", $spotname);
		$spot->put("category", $cat);
		$spot->put("cityid", $cityid);
		$spot->setUser($systemUser);
		$spot->save();
		return array(
			"id"	=> $spot->getKey()
		);
	}
	
	/** @WebAction, @Login */
	public function addnew() {
		//$categories = array_merge_recursive(array("-1" => "Selecteer..."), Spot::$CATEGORIES);
		$this->put("categories", Spot::getCategories(array("Selecteer...")));
	}
	
	/** @Ajax */
	public function getform() {
		$cat = $this->getString("cat");
		$this->put("shortname", Spot::getShortnameByCategoryNr($cat));
		$this->put("catlabel", Spot::$CATEGORIES[$cat]);
	}
	
	/** @WebAction */
	public function submitnew() {
		$systemUser = UserFactory::getSystemUser();
		$cat = $this->getString("cat");
		$user = UserFactory::getSystemUser();
		$spot = new Spot();
		$spot->putAll($this);		
		$spot->put("category", $cat);
		$spot->setUser($systemUser);
		$this->put("spot", $spot);
		try {
			$spot->save();
			$myspot = new MySpot();
			$myspot->setUser($this->getUser());
			$myspot->put("spotid", $spot->getKey());
			$myspot->save();
		} catch (DuplicateException $e) {
			$this->put("address", $spot->getHTMLRenderer()->get("address"));
			$this->setTemplate("alreadyexists");
		}
	}
	
	private function checkValidSpot() {
		$spotSystemid = $this->getInt("id", -1);
		if ($spotSystemid == -1) {
			Logger.warn("Onbekende spot om te bekijken", __FILE__, __LINE__);
			throw new UserFriendlyMessageException("Er is een fout opgetreden. Dit is gelogd.");
		}
	}
	
	/**
	 * @Ajax
	 */
	public function getFormFields() {
		$cat = $this->get("cat");
		$json = OproepFormFactory::getSearchFields($cat);
		
		$this->put("mustMatch", true);
		$this->put("fields", $json);
	}

	/**
	 * @Ajax
	 */
	public function getFormFieldsForSearcher() {
		$cat = $this->get("cat");
		$json = OproepFormFactory::getSearchFields($cat);
		
		$this->put("mustMatch", false);
		$this->put("fields", $json);
		//$this->setTemplate("getFormFields");
	}

}
?>
