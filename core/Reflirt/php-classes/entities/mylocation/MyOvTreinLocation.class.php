<?php

include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'entities/oproep/Oproep.class.php';

class MyOvTreinLocation extends Oproep{
	private $fulltextColumns = array();

    function OvTreinOproep() {
    	parent::__construct("reflirt_nieuw", "search_ov_train", Oproep::$CAT_OV_TREIN);
    }
    
    function getTitle() {
    	return "Eindbestemming ".$this->getString("cicityname");
    }
    
    function getOproepLoadSearcher() {
    	return new OvTreinLoadSearcher($this);
    }
    
    function getMatchSearcher() {
    	return new OvTreinMatchSearcher();
    }
    
    function getMatches() {
    	$user = UserFactory::getSystemUser();
    	$searcher = $this->getMatchSearcher();
		$oq = ObjectQuery::build(new OvTreinOproep, $user);
		$oq->addParameters($this);
		$oq->setSearcher($searcher);
		$list = SearchObject::search($oq);
		return $list;
    }
    
    function getDefaultSearcher() {
    	return new DefaultOvTreinSearcher();
    }
    
    function getFulltextColumns(){
    	$columns = array();
    	$columns[] = "cocountryname";
    	$columns[] = "cicityname";
    	return $columns;
    }
    
    /**
     * Implementation of Categorie
     */
    function getExtraKeywords(){
    	$keywords = array();
    	$keywords[] = "trein";
    	return $keywords;
    }
}

class OvTreinMatchSearcher extends Searcher {
	
	public function getFields(DataSource $ds) {
		return "*, search_ov_train.systemid AS systemid, " .
				"zoekopdracht.descr AS descr"; // niet de joined systemid mixen
	}
	
	public function getTables(DataSource $ds) {
		$select	= 	" FROM search_ov_train ";
		//TODO: aparte tabel aanmaken voor eindbestemmingen 
//		$select .=	" INNER JOIN city ";
//		$select .=	" ON disco.dcityid = city.systemid ";
		$select .=	" INNER JOIN city ";
		$select .=	" ON search_ov_train.ovtdest = city.systemid ";
		$select .=	" INNER JOIN zoekopdracht ";
		$select .=	" ON search_ov_train.searchid = zoekopdracht.systemid ";		
		$select .=	" INNER JOIN users ";
		$select .=	" ON zoekopdracht.user = users.systemid ";
		
		// Foto
		$select .=	" LEFT JOIN photo ";
		$select .=	" ON photo.systemid = users.photoid";		
		return $select;
	}
	
	public function getFilter(DataSource $ds) {
		$list = new QueryConstraintList();
		$list->addKey("category", Oproep::$CAT_OV_TREIN);
		$sex = $ds->getString("ovtsex");
		if(is_array($sex)) {
			if(count($sex) == 1) {
				$sex = $sex[0];
			} else {
				$sex = "";
			}
		}
		$list->addIfKey("users.sex", $sex);
		
		$list->addKey("search_ov_train.ovtdest", $ds->getString("ovtdest"));
		$list->addKey("type", $ds->getString("type"));
		if(isset($_SESSION['uid'])) {
			$list->addNotKey("users.systemid", $_SESSION['uid']);
		}
		return $list;
	}
}

class OvTreinLoadSearcher extends LoadSearcher {
	
	public function getTables(DataSource $ds) {
		return " FROM search_ov_train ".
					" LEFT JOIN city ".
					" ON search_ov_train.ovtdest = city.systemid ".
					" INNER JOIN zoekopdracht ".
					" ON search_ov_train.searchid = zoekopdracht.systemid ".		
					" INNER JOIN users ".
					" ON zoekopdracht.user = users.systemid ".
					" LEFT JOIN photo ".
					" ON photo.systemid = users.photoid ";		
	}
}

class DefaultMyOvTreinLocationSearcher extends DefaultOproepSearcher {
	
	public function getTables(DataSource $ds) {
		return " FROM search_ov_train ".
					" LEFT JOIN city ".
					" ON search_ov_train.ovtdest = city.systemid ".
					" INNER JOIN zoekopdracht ".
					" ON search_ov_train.searchid = zoekopdracht.systemid ".		
					" INNER JOIN users ".
					" ON zoekopdracht.user = users.systemid ".
					" LEFT JOIN photo ".
					" ON photo.systemid = users.photoid ";
	}
}


?>