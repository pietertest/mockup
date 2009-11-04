<?php
include_once PHP_CLASS.'searchers/DefaultSearcher.class.php';

class Note extends DatabaseEntity{
	
	function __construct() {
		parent::__construct("reflirt_nieuw", "note");
    }
    
    public function getDefaultSearcher() {
    	return new DefaultNoteSearcher();
    }
    
    /**@Override*/
    public function getLoadSearcher() {
    	return new NoteLoadSearcher($this);
    }
}

class DefaultNoteSearcher extends DefaultSearcher {
	
	public function getFields(DataSource $ds) {
		return "*, note.systemid AS systemid ";
	}

	function getTables(DataSource $ds) {
		return " FROM note INNER JOIN users ".
			" ON note.sender = users.systemid ".
			" LEFT JOIN photo ".
			" ON users.photoid = photo.systemid ";
	}
	
	function getOrderBy(DataSource $ds) {
		return "note.insertdate DESC";
	}
}

class NoteLoadSearcher extends LoadSearcher {
	
	function getTables(DataSource $ds) {
		return " FROM note INNER JOIN users ".
			" ON note.sender = users.systemid ";
	}
	
}
?>