<?php
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';
include_once PHP_CLASS.'entities/oproep/OproepFactory.class.php';
include_once PHP_CLASS . 'entities/location/CityFactory.class.php';
include_once PHP_CLASS . 'entities/location/CountryFactory.class.php';

class ZoekopdrachtImport {
	
	private $systemUser;
	
	public static $SHORTNAMES = array( // Voor templates, bijv: search.disco.tpl 
		1	 	=> "buiten",	
		2 		=> "cultureel",
		3 		=> "disco",
		4		=> "eet",
		5 		=> "evenement",
		6		=> "ov_bus",
		7		=> "ov_metro",
		8		=> "ov_tram",
		9		=> "ov_trein",
		10		=> "park",
		11		=> "recreatie",
		12		=> "school",
		13		=> "werk",
		14		=> "winkel",
		15		=> "wonen",
		16		=> "hotel"
	);
	
	public static $CAT_BUITEN = 1;
	public static $CAT_CULTUREEL = 2;
	public static $CAT_DISCO = 3;
	public static $CAT_EET = 4;
	public static $CAT_EVENEMENT = 5;
	public static $CAT_OV_BUS = 6;
	public static $CAT_OV_METRO = 7;
	public static $CAT_OV_TRAM = 8;
	public static $CAT_OV_TREIN = 9;
	public static $CAT_PARK = 10;
	public static $CAT_RECREATIE = 11;
	public static $CAT_SCHOOL = 12;
	public static $CAT_WERK = 13;
	public static $CAT_WINKEL = 14;
	public static $CAT_WONEN = 15;
	public static $CAT_HOTEL = 16;
	
	private function startUp() {
		echo "Starting zoekopdrachten import...<br/>";
		$this->systemUser = UserFactory::getSystemUser();
		
		$pq = new PreparedQuery("reflirt_nieuw");
		$pq->setDelete("spot");
		$pq->execute();
		
		$pq->setDelete("oproep");
		$pq->execute();
	}
	
	public function import() {
		$this->startUp();
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * from ZOEKOPDRACHTEN");
		$rs = $pq->execute();
		
		foreach($rs as $oldOproep) {
			$cat = $this->getCategory($oldOproep["CATEGORIE"]);
	    	switch($cat){
	    		case self::$CAT_BUITEN: 
	    			$this->importBuitenOproep($oldOproep);
	    			break;
	    		case self::$CAT_CULTUREEL: 
	    			$this->importCultureelOproep($oldOproep);
	    			break;
	    		case self::$CAT_DISCO: 
	    			$this->importDiscoOproep($oldOproep);
	    			break;
	    		case self::$CAT_EET: 
	    			$this->importEetOproep($oldOproep);
	    			break;
	    		case self::$CAT_EVENEMENT: 
	    			$this->importEvenementOproep($oldOproep);
	    			break;
	    		case self::$CAT_OV_BUS: 
	    			$this->importOvBusOproep($oldOproep);
	    			break;
	    		case self::$CAT_OV_METRO: 
	    			$this->importOvMetroOproep($oldOproep);
	    			break;
	    		case self::$CAT_OV_TRAM: 
	    			$this->importOvTramOproep($oldOproep);
	    			break;
	    		case self::$CAT_OV_TREIN: 
	    			$this->importOvTreinOproep($oldOproep);
	    			break;
	    		case self::$CAT_PARK: 
	    			$this->importParkOproep($oldOproep);
	    			break;
	    		case self::$CAT_RECREATIE: 
	    			//$this->importRecreatieOproep($oldOproep);
	    			break;
	    		case self::$CAT_SCHOOL: 
	    			$this->importSchoolOproep($oldOproep);
	    			break;
	    		case self::$CAT_WERK: 
	    			$this->importWerkOproep($oldOproep);
	    			break;
	    		case self::$CAT_WINKEL: 
	    			$this->importWinkelOproep($oldOproep);
	    			break;
	    		case self::$CAT_WONEN: 
	    			$this->importWonenOproep($oldOproep);
	    			break;
	    		case self::$CAT_HOTEL: 
	    			$this->importHotelOproep($oldOproep);
	    			break;
	    		default:
	    			DebugUtils::debug("Onbekende cateogrie: ".$cat);
	    	}
		}
		
	}
	
	private function getCategory($oldCat) {
		$catId = array_search($oldCat, self::$SHORTNAMES);
		if(!$catId) {
			throw new Exception("Kan category niet vinden: " . $oldCat); 
		}
		return $catId;
	}
	
	private function initOproep($cat, $rs) {
		$oproep = OproepFactory::getOproep($cat);
		
		$user = UserFactory::getUserByLogin($rs["NICK"]);
		$oproep->setUser($user);
		
		$insertDate = DateUtils::stringToDate($rs["DATUM"]);
		$oproep->putCol("insertdate", $insertDate);
		$lastUpdate = DateUtils::stringToDate($rs["LAATST_GEWIJZIGD"]);
		$oproep->putCol("lastupdate", $lastUpdate);
		
		$startDate = DateUtils::stringToDate($rs["ZOEKDATUM"]);
		if(!DateUtils::isEmptyDate($startDate)) {
			$oproep->putCol("startdate", $startDate);	
		} else {
			$oproep->put("regelmatig", 1);
		}
		
		// Komt niet (eens) voor :)
//		$endDate = DateUtils::stringToDate($rs["EINDDATUM"]);
//		if(!DateUtils::isEmptyDate($endDate)) {
//			$oproep->putCol("enddate", $endDate);	
//		}
		
		// Bij nieuw geld 1 = man, 0 = vrouw
		$sex = $rs["GEZ_GESLACHT"] == "1" ? "0" : "1";
		$oproep->putCol("sex", $sex);
		
		
		$message = $rs["EIGEN_COMMENTAAR"];
		$oproep->putCol("message", $message);
		
		$oproep->putCol("category", $cat);
		
		$title = "Waar ben je?";
		$oproep->putCol("title", $title);
		
		$oproep->putCol("message", $message);
		
		return $oproep;

	}
	
	private function assertSpot($cat, $name, $city) {
		Utils::assertNotNull("city mag hier geen NULL zijn", $city);
		$oq = ObjectQuery::buildACS(new Spot, $this->systemUser);
		$oq->addConstraint(Constraint::eq("category", $cat));
		$oq->addConstraint(Constraint::eq("cityid", $city->getKey()));
		$oq->addConstraint(Constraint::eq("name", $name));
		
		$spot = SearchObject::select($oq);
		if($spot == NULL) {
			$spot = new Spot();
			$spot->setUser($this->systemUser);
			$spot->putCol("category", $cat);
			$spot->putCol("name", $name);
			$spot->putCol("cityid", $city->getKey());
			$spot->save();
		}
		return $spot;		
	}
	
	private function assureCity($countryname, $cityname) {
		$country = CountryFactory::getCountry($countryname);
		if($country == null) {
			$country = new Country();
			$country->setUser($this->systemUser);
			$country->putCol("cocountryname", $countryname);
			$country->save();
		}
		
		$oq = ObjectQuery::buildACS(new City, UserFactory::getSystemUser());
		$oq->addConstraint(Constraint::eq("cicityname", $cityname));
		$oq->addConstraint(Constraint::eq("cicountryid", $country->getKey()));
		$city = SearchObject::select($oq);
		
		if($city == null) {
			$city = new City();
			$city->setUser($this->systemUser);
			$city->putCol("cicityname", $cityname);
			$city->putCol("cicountryid", $country->getKey());
			$city->save();
		}
		
		return $city;
		
	}
	
	
	/**
	 * *************************************************************************
	 * 	Disco
	 * *************************************************************************
	 */
	public function ImportDiscoOproep($rs) {
		$id = $rs["ZOEK_ID"];
		echo "importing disco...$id<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_DISCO, $rs);
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_DISCO WHERE ZOEK_ID = " . $id);
		$discoRs = $pq->execute();
		
		if(count($discoRs) < 1) {
			return;
		}
		
		$countryName	= $discoRs[0]["LAND"];
		$cityName 		= $discoRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$disoName = $discoRs[0]["DISCO_NAAM"];
		
		$disco = $this->assertSpot(OproepEntity::$CAT_DISCO,$disoName, $city);
		
		$oproep->putCol("spotid", $disco->getKey());
		$oproep->save();
	}
	
	/**
	 * *************************************************************************
	 * 	Wonen
	 * *************************************************************************
	 */
	public function importWonenOproep($rs) {
		echo "importing wonen...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_WONEN, $rs);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_WONEN WHERE ZOEK_ID = " . $id);
		$discoRs = $pq->execute();
		
		$countryName	= $discoRs[0]["LAND"];
		$cityName 		= $discoRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$oproep->save();
	}
	
	/**
	 * *************************************************************************
	 * 	School
	 * *************************************************************************
	 */
	public function ImportSchoolOproep($rs) {
		echo "importing disco...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_SCHOOL, $rs);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_SCHOOL WHERE ZOEK_ID = " . $id);
		$schoolRs = $pq->execute();
		
		$countryName	= $schoolRs[0]["LAND"];
		$cityName 		= $schoolRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$schoolName = $schoolRs[0]["SCHOOL_NAAM"];
		
		$school = $this->assertSpot(OproepEntity::$CAT_SCHOOL, $schoolName, $city);
		
		$oproep->putCol("spotid", $school->getKey());
		$oproep->save();
	}

	/**
	 * *************************************************************************
	 * 	Trein
	 * *************************************************************************
	 */
	public function importOvTreinOproep($rs) {
		echo "importing trein...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_OV_TREIN, $rs);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_OV_TREIN WHERE ZOEK_ID = " . $id);
		$treinRs = $pq->execute();
		
		$countryName	= "Nederland";
		$cityName 		= $treinRs[0]["EINDPLAATS"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$oproep->save();
	}
	
	/**
	 * *************************************************************************
	 * 	Tram
	 * *************************************************************************
	 */
	public function importOvTramOproep($rs) {
		echo "importing tram...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_OV_TRAM, $rs);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_OV_TRAM WHERE ZOEK_ID = " . $id);
		$treinRs = $pq->execute();
		
		$countryName	= "Nederland";
		$cityName 		= "(Onbekend)";
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$oproep->save();
	}
	
	/**
	 * *************************************************************************
	 * 	Bus
	 * *************************************************************************
	 */
	public function importOvBusOproep($rs) {
		echo "importing bus...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_OV_BUS, $rs);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_OV_BUS WHERE ZOEK_ID = " . $id);
		$treinRs = $pq->execute();
		
		$countryName	= "Nederland";
		$cityName 		= "(Onbekend)";
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$oproep->save();
	}
	
	 
	
	/**
	 * *************************************************************************
	 * 	Buiten
	 * *************************************************************************
	 */
	public function importBuitenOproep($rs) {
		echo "importing buiten...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_BUITEN, $rs);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_BUITEN WHERE ZOEK_ID = " . $id);
		$buitenRs = $pq->execute();
		
		$countryName	= $buitenRs[0]["LAND"];
		$cityName 		= $buitenRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$oproep->save();
	}
	
/**
	 * *************************************************************************
	 * 	Evenement
	 * *************************************************************************
	 */
	public function importEvenementOproep($rs) {
		echo "importing evenement...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_EVENEMENT, $rs);
		$oproep->setSkipImportValidation(true);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_EVENEMENT WHERE ZOEK_ID = " . $id);
		$buitenRs = $pq->execute();
		
		$countryName	= $buitenRs[0]["LAND"];
		$cityName 		= $buitenRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
//		$evenement = $this->assertSpot(OproepEntity::$CAT_EVENEMENT, "(Onbekend)", $city);
//		
//		$oproep->putCol("spotid", $evenement->getKey());
		
		$oproep->save();
	}	
	
	/**
	 * *************************************************************************
	 * 	Eetgelegenheid
	 * *************************************************************************
	 */
	public function importEetOproep($rs) {
		echo "importing eetgelegenheid...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_EET, $rs);
		$oproep->setSkipImportValidation(true);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_EET WHERE ZOEK_ID = " . $id);
		$buitenRs = $pq->execute();
		
		$countryName	= $buitenRs[0]["LAND"];
		$cityName 		= $buitenRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		//$evenement = $this->assertSpot(OproepEntity::$CAT_EET, "(Onbekend)", $city);
		
		//$oproep->putCol("spotid", $evenement->getKey());
		
		$oproep->save();
	}
	
	/**
	 * *************************************************************************
	 * 	Park
	 * *************************************************************************
	 */
	public function importParkOproep($rs) {
		echo "importing park...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_PARK, $rs);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_PARK WHERE ZOEK_ID = " . $id);
		$buitenRs = $pq->execute();
		
		$countryName	= $buitenRs[0]["LAND"];
		$cityName 		= $buitenRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$parkName = $buitenRs[0]["PARK_NAAM"];
		
		$evenement = $this->assertSpot(OproepEntity::$CAT_PARK, $parkName, $city);
		
		$oproep->putCol("spotid", $evenement->getKey());
		
		$oproep->save();
	}

	/**
	 * *************************************************************************
	 * 	Werk
	 * *************************************************************************
	 */
	public function importWerkOproep($rs) {
		echo "importing werk...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_WERK, $rs);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_WERK WHERE ZOEK_ID = " . $id);
		$buitenRs = $pq->execute();
		
		$countryName	= $buitenRs[0]["LAND"];
		$cityName 		= $buitenRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$parkName = $buitenRs[0]["WERK_NAAM"];
		
		$evenement = $this->assertSpot(OproepEntity::$CAT_WERK, $parkName, $city);
		
		$oproep->putCol("spotid", $evenement->getKey());
		
		$oproep->save();
	}
	

	/**
	 * *************************************************************************
	 * 	Cultureel
	 * *************************************************************************
	 */
	public function importCultureelOproep($rs) {
		echo "importing cutureel...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_CULTUREEL, $rs);
		$oproep->setSkipImportValidation(true);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_CULTUREEL WHERE ZOEK_ID = " . $id);
		$buitenRs = $pq->execute();
		
		$countryName	= $buitenRs[0]["LAND"];
		$cityName 		= $buitenRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
//		$parkName = $buitenRs[0]["CULTUREEL_SOORT"];
//		
//		$evenement = $this->assertSpot(OproepEntity::$CAT_CULTUREEL, $parkName, $city);
//		
//		$oproep->putCol("spotid", $evenement->getKey());
		
		$oproep->save();
	}

	/**
	 * *************************************************************************
	 * 	Metro
	 * *************************************************************************
	 */
	public function importOvMetroOproep($rs) {
		echo "importing metro...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_OV_METRO, $rs);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_OV_METRO WHERE ZOEK_ID = " . $id);
		$buitenRs = $pq->execute();
		
		$countryName	= $buitenRs[0]["LAND"];
		$cityName 		= $buitenRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
		$oproep->save();
	}

	/**
	 * *************************************************************************
	 * 	Winkel
	 * *************************************************************************
	 */
	public function importWinkelOproep($rs) {
		echo "importing winkel...<br/>";
		$oproep = $this->initOproep(OproepEntity::$CAT_WINKEL, $rs);
		$oproep->setSkipImportValidation(true);
		$id = $rs["ZOEK_ID"];
		
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM CAT_WINKEL WHERE ZOEK_ID = " . $id);
		$buitenRs = $pq->execute();
		
		$countryName	= $buitenRs[0]["LAND"];
		$cityName 		= $buitenRs[0]["PLAATSNAAM"];
		
		$city = $this->assureCity($countryName, $cityName);
		$oproep->putCol("cityid", $city->getKey());
		
//		$parkName = $buitenRs[0]["WINKEL_SOORT"];
//		
//		$evenement = $this->assertSpot(OproepEntity::$CAT_WINKEL, $parkName, $city);
//		
//		$oproep->putCol("spotid", $evenement->getKey());
		
		$oproep->save();
	}
	
	
}



?>