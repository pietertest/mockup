<?php

class SpotPhoto extends DatabaseEntity{
	
	public function __construct() {
		parent::__construct("reflirt_nieuw", "spotphoto");
	}
	
	public static function getPhotoSearcher() {
		return new SpotPhotoSearcher();
	}
	
	public function getHtmlRenderer() {
		return new SpotPhotoHtmlRenderer($this);
	}
}
    
class SpotPhotoSearcher extends DefaultSearcher {
	
	function getTables(DataSource $ds) {
		return "FROM spotphoto " .
				"JOIN photo " .
				"ON spotphoto.photoid = photo.systemid";
	} 

    function getFilter(DataSource $ds) {
    	$list = new QueryConstraintList();
    	$list->addKey("spotid", $ds->getInt("spotid", -1));
    	return $list;
    }
}

class SpotPhotoHtmlRenderer extends DefaultHTMLRenderer {
	
	public function __construct($ent) {
		parent::__construct($ent);
	}
	
	public function get($what) {
		if($what == "dateadded") {
			return "Op ".$this->ent->getString("insertdate");
		}		
		return parent::get($what);
	}
	
}