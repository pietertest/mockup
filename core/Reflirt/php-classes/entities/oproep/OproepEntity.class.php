<?php
include_once PHP_CLASS.'entities/mylocation/MyLocationFactory.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';
include_once PHP_CLASS.'entities/oproep/IOproep.class.php';

class OproepEntity extends DatabaseEntity implements IOproep {
	
	public static $TYPE_REFLIRT		= 1;
	public static $TYPE_PREFLIRT	= 2;
	public static $TYPE_SPOT		= 3;

    function __construct() {
    	parent::__construct("reflirt_nieuw", "oproep");
    }
    
    public function getLabel() {
    	throw new IllegalStateException("function getLabel not implemented");
    }
    
    public function validate() {
    	Utils::validateNotEmpty("Vul een geldige title in", $this->get("title"), "title");
		Utils::validateNotEmpty("Kies een categorie", $this->get("category"), "category");
		$sex = $this->getInt("sex", -1);
		Utils::validateTrue("Kies een Geslacht", $sex != -1, "sex");
		
		$startDate = $this->get("startdate");
		$dateRegelmatig = $this->get("regelmatig");
		if(empty($dateRegelmatig)) { 
			if(empty($startDate)) {
				throw new ValidationException("Vul een geldige datum in of kies \"Regelmatig/Vaker\"");
			}
		} else {
			$this->put("startdate", null);
			$this->put("enddate", null);
		}
		
    }
    
    public function getResultaten() {
    	$oq = ObjectQuery::build(new OproepEntity(), UserFactory::getSystemUser());		
		$oq->addParameters($this);
		$oq->addConstraint(Constraint::neq("oproep.user", $this->getUser()->getKey()));
		$oq->addConstraint(Constraint::gt("oproep.insertdate", $_SESSION["lastlogout"]));
		$oq->setSearcher(OproepEntity::getSearcher());
		return SearchObject::search($oq);
    }
    
    public function getReactions() {
    	return $this->getObjectsByForeignKey(new OproepReaction(), "oproepid");
    }
    
    public static function getOproepen(User $user, $type) {
		$oq = ObjectQuery::buildACS(new OproepEntity(), $user);
		$oq->addConstraint(Constraint::eq("type", $type));
		$list = SearchObject::search($oq);
		return $list;
		$list2 = array();
		foreach($list as $key=>$oproep) {
			$type = OproepFactory::getOproep($oproep->getString("category"));
			$o = EntityFactory::loadEntity($type, $user, $oproep->getString("oproepid"));
			$list2[] =$o;
		}
		return $list2;
	}
	
	/**
	 * Of een spot verplicht is om in te vullen. Kan overschreven worde, bijv bij
	 * WonenOproep
	 */
	public function spotIsMandatory() {
		return true;		
	}
	
    public function save() {
    	$this->validate();
    	$fulltext = array();
    	$extraKeywords = $this->getExtraKeywords();
    	$fulltext = array_merge($fulltext, $extraKeywords);
    	
    	if ($this->spotIsMandatory()) {
	    	$spot = $this->getSpot();
	    	$fulltext []= $spot->getName();
	    	
	    	// Haal de cityid van de spot, zodat je niet stiekem een andere cityid mee kan posten
	    	$this->put("cityid", $spot->getCity()->getKey()); 
    	}
    	
    	$fulltext []= $this->getCity()->getName();
    	$fulltext []= $this->get("message");

    	$this->put("keywords", join(" ", $fulltext));
    	parent::save();
    }
    
    
	
	public function getLoadSearcher() {
		return new OproepLoadSearcher($this);
	}
	
	public function getTitle() {
		return $this->get("title");
	}
	
	public function getSpot() {
		return $this->loadEntityByForeignKey(new Spot, "spotid");
	}
	
	public function getCity() {
		return $this->loadEntityByForeignKey(new City, "cityid");
	}
	
	public function getCategory() {
		return $this->get("category");
	}
	
	public function getCategoryLabel() {
		return OproepEntity::$CATEGORIES[$this->getCategory()];
	}
	
	public function getHtmlRenderer() {
		return new OproepHtmlRenderer($this);
	}
	
	public static final function getSearcher() {
		return new OproepSearcher();
	}
	
	public function getUrl() {
		
		return "/?page=oproep&action=view&id=". $this->getKey();
	}
	
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
		
	public static $CATEGORIES = array(
		1	 	=> "Buiten",	
		2 		=> "Cultureel",
		3 		=> "Disco/Kroeg",
		4		=> "Eetgelegenheid",
		5 		=> "Evenement",
		6		=> "Bus (OV)",
		7		=> "Metro (OV)",
		8		=> "Tram (OV)",
		9		=> "Trein (OV)",
		10		=> "(Thema) Park",
		11		=> "Recreatie",
		12		=> "School",
		13		=> "Werk",
		14		=> "Winkel",
		15		=> "Woonomgeving",
		16		=> "Hotel"
	);

	public static $SHORTNAMES = array( // Voor templates, bijv: search.disco.tpl 
		1	 	=> "out",	
		2 		=> "cult",
		3 		=> "disco",
		4		=> "eat",
		5 		=> "event",
		6		=> "bus",
		7		=> "metro",
		8		=> "tram",
		9		=> "trein",
		10		=> "park",
		11		=> "recreation",
		12		=> "school",
		13		=> "work",
		14		=> "shop",
		15		=> "living",
		16		=> "hotel"
	);
}

class OproepHtmlRenderer extends DefaultHTMLRenderer {
	
	public function get($what) {
		if ($what == "onderschrift") {
			return $this->getWaarEnWanneer(true);
		} elseif ($what == "location") {
			return $this->getWaarEnWanneer(false);
		} 
		elseif ($what == "category") {
			$cat = $this->ent->get("category");
			return OproepEntity::$CATEGORIES[$cat];
		}
		parent::get($what);
	}
	
	private function getWaarEnWanneer($includeDate) {
		$result = array();
		$startDate = $this->ent->get("startdate");
		if ($includeDate) {
			if (DateUtils::isEmptyDate($startDate) || empty($startDate)) {
				// Regelmatig/Vaker/Onbekend?
			} else {
				$formatted = DateUtils::formatDateTime($startDate);
				$result[] = $formatted;
				
			}
		}
		$spot = $this->ent->getSpot();
		if ($spot) {
			$result []=  ucwords($spot->getName());
		}
		//echo "Stad: ".DebugUtils::debug($this->ent->getAll());
		$result []= $this->ent->getCity()->getName();
		
		return join(", ", $result);
	}
}


final class OproepSearcher extends Searcher {
	
	public function getFields(DataSource $ds) {
		$keywords = $ds->get("q");
		if (!empty($keywords)) {
		// 	maak van 'disco amsterdam' => '+disco +amsterdam'
			$keywords = preg_replace("/(\w+)/i", "+$1", $keywords);
			return "oproep.*, oproep.systemid AS systemid, MATCH (keywords) against ('".DBUtils::dbEscape($keywords)."' IN BOOLEAN MODE) as relevance";
		}
		return "oproep.*, oproep.systemid AS systemid";
	}
	
	public function getTables(DataSource $ds) {
		$select =	" FROM oproep ";
		$select .=	" INNER JOIN users ";
		$select .= 	" ON oproep.user = users.systemid ";
		return $select;
	}
	
	public function getFilter(DataSource $ds) {
		$list = new QueryConstraintList();
		$keywords = $ds->get("q");

		// maak van 'disco amsterdam' => '+disco +amsterdam'
		$keywords = preg_replace("/(\w+)/i", "+$1", $keywords);
		if (!empty($keywords)) {
			$list->addMatch("keywords", $keywords);
		}
		$sex = $ds->get("sex");
		if(is_array($sex)) {
			if(count($sex) == 1) {
				$list->addKey("users.sex", $sex[0]);
			}
			/* Dit is overbodig:
			else {
				$list->addLike("users.sex", $sex);
			}
			*/
		}
		if(isset($_SESSION['user'])) {
			$user = unserialize($_SESSION['user']);
			$list->addNotKey("users.systemid", $user->getKey());
		}
		$list->addIfKey("spotid", $ds->get("spotid"));
		$list->addIfKey("oproep.cityid", $ds->get("cityid"));
		$startdate = $ds->get("startdate");
		if (!empty($startdate)) {
			$f = new DateFormat();
			$f->setValue($startdate);
			$list->addKey("startdate", $f->parse());
			
		}
		$list->addIfKey("category", $ds->get("category"));
		return $list;
	}
	
	public function getOrderBy(DataSource $ds) {
		$orderBy = "";
		$keywords = $ds->get("q");
		if (!empty($keywords)) {
			$orderBy .= "relevance,";
		}
		//$orderBy .= "photoid DESC , oproep.insertdate DESC";
		$orderBy .= "oproep.insertdate DESC";
		return $orderBy;
	}
	
}
?>