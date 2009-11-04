<?php
include_once PHP_CLASS.'entities/mylocation/MyLocationInterface.class.php';
include_once PHP_CLASS.'entities/db/JoinedEntity.class.php';
include_once PHP_CLASS.'searchers/Searcher.class.php';
include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/db/FulltextSearch.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocation.class.php';

abstract class AbstractMyLocation extends DatabaseEntity implements MyLocationInterface, FulltextSearch{
	
	public $category = null;
	
	public function __construct($db, $table, $cat) {
		parent::__construct($db, $table);
		$this->category = $cat;
		$this->setLoadSearcher($this->getMyLocationLoadSearcher());
	}
	
	//TODO: LoadSearcher maken en niet deze methode overschrijven wellicht
	public function load() {
		parent::load();
		
		$rootSystemid = $this->getKey();
		
    	$oproep = EntityFactory::loadEntity(new MyLocation(), $this->getUser(),
    				$this->getString("searchid"));
    	$this->putAll($oproep);
    	$this->setKey($rootSystemid);
	}
	
	function delete() {
		parent::load();
		//DebugUtils::debug($this);
		//DebugUtils::debug("dleeteeeeeeeee".$this->getString("searchid"));
		
		
		EntityFactory::deleteEntity(new  MyLocation(), $this->getUser(),
			$this->getString("searchid"));
		parent::delete();
	}
	
	public function getFriendlyName() {
		$cat = $this->category;
		Utils::assertNotEmpty("category == null", $cat);
		Utils::assertTrue("Unknown category: ".$cat, 
			isset(MyLocation::$CATEGORIES[$cat]));
		return MyLocation::$CATEGORIES[$cat];
	}
	
    /**
     * Alles fulltext kolommen indexeren
     * 
     * @Override
     */
     public function save() {
		$systemid = $this->getKey();
		$oproepEnt = null;
		if($systemid == -1) {
			$oproepEnt = new MyLocation();
			$oproepEnt->putAll($this);
			$oproepEnt->removeKey();
		} else {
			
			parent::save();
//			parent::load();
			$oproepSystemid = $this->getString("searchid");
			$oproepEnt = EntityFactory::loadEntity(new MyLocation, $this->getUser(), $oproepSystemid);
			$oproepEnt->putAll($this);
			$oproepEnt->removeKey();
			$oproepEnt->setKey($oproepSystemid);
		}
		parent::save();
		$cat = $this->getString("cat"); // Wordt dit iet overschreven in Entity.loadBySystemid()?
		Utils::assertTrue("No cat found: ".$cat, isset(MyLocation::$CATEGORIES[$cat]));		
		
		$keywords = $this->getKeywords();
		$oproepEnt->setUser($this->getUser());
		$oproepEnt->put("category", $cat);
		$oproepEnt->put("oproepid", $this->getKey());
		$oproepEnt->put("keywords", $keywords);
//		DebugUtils::debug($oproepEnt->getAll());
		$oproepEnt->save();
		$this->put("searchid", $oproepEnt->getKey());
		parent::save();
     }
     
     public static function getSimpleSearchMatcher() {
     	return new MyLocationSimpleSearchMatchSearcher();
     }
     
     /** Voor het indexeren in de Filltexttabel */
     private function getKeywords() {
     	$aKeywords = $this->getExtraKeywords();
		$keywords = "";
     	if(count($aKeywords)) {
			foreach($aKeywords as $word) {
				$keywords .= $word." ";
			}
     	}
		$keywords .= "|";
		foreach($this->getFulltextColumns() as $column) {
			$keywords .= $this->get($column)." ";
		}
		return $keywords;
     }
    
    /** 
     * @param cat Categorie nummer
     * @return Shortname voor gebruikt in templatenaame, bijv. cult, event, etc
     */
	public function getShortCatName() {
		$cat = $this->getString("category");
		Utils::assertNotEmpty(MyLocation::$SHORTNAMES[$cat],
     		"No shortname: ".$cat, $cat);
    	return MyLocation::$SHORTNAMES[$cat];
    }
    
    public function getFriendlyCatName() {
    	$cat = $this->getString("category");	
		Utils::assertNotEmpty(MyLocation::$CATEGORIES[$cat],
     		"No category: ".$cat, $cat);
    	return MyLocation::$CATEGORIES[$cat];
    }
    
}


/**
 * Matchersearcher voor SimpleSearch
 */
final class MyLocationSimpleSearchMatchSearcher extends Searcher {
	
	public function getFields(DataSource $ds) {
		return "*, MATCH (keywords) against ('".$ds->getString("keywords")."' IN BOOLEAN MODE) as relevance";
	}
	
	public function getTables(DataSource $ds) {
		$select =	" FROM mylocation";
		$select .=	" INNER JOIN users ";
		$select .= 	" ON mylocation.user = users.systemid ";
		return $select;
	}
	
	public function getFilter(DataSource $ds) {
		$list = new QueryConstraintList();
		$list->addMatch("keywords", $ds->getString("keywords"));
		$sex = $ds->getString("sex");
		if(is_array($sex)) {
			if(count($sex) == 1) {
				$list->addKey("sex", $sex[0]);
			}
		}
		if(isset($_SESSION['uid'])) {
			$list->addNotKey("users.systemid", $_SESSION['uid']);
		}
		return $list;
	}
	
	public function getOrderBy(DataSource $ds) {
		return " relevance ";
	}
	
}

/**
 * Deze searcher wordt gebruikt om zoekopdrachten relevant aan de gekozen 
 * category waarop gezocht wordt te tonen in de sidebar. 
 * (dus NIET met de google manier).
 */
abstract class DefaultMyLocationSearcher extends Searcher {
	public function getFields(DataSource $ds) {
		return "*";
	}
	
	public function getFilter(DataSource $ds) {
		$list = new QueryConstraintList();
		$list->addIfKey("category", $ds->getString("category"));
		return $list;		
	}
}

?>