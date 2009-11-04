<?php
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';

class UserFavorite extends DatabaseEntity {
	
//	public static $TYPE_USER	= 1;
//	public static $TYPE_OPROEP	= 2;

    public function __construct() {
    	parent::__construct("userfavorite");
    }
    
    public static function getOverviewSearcher() {
    	return new UserFavoriteOverviewSearcher();
    }
    
    public function getOtherUser() {
    	$systemid = $this->getInt("otheruser", -1);
    	return UserFactory::getUserBySystemid($systemid);
    }
    
}

class UserFavoriteOverviewSearcher extends Searcher {
	public function getFields(DataSource $ds) {
		return "*, userfavorite.systemid AS systemid ";
	}
	
	public function getTables(DataSource $ds) {
		$select	= 	" FROM userfavorite ".
					" INNER JOIN users ".
					" ON userfavorite.otheruser = users.systemid ".
		 			" LEFT JOIN photo ".
					" ON users.photoid = photo.systemid ";
		return $select;
	}
	
	public function getFilter(DataSource $ds) {
		$list = new QueryConstraintList();
		return $list;
	}
}
?>