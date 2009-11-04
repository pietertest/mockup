<?php
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/message/Message.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocation.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';
include_once PHP_CLASS.'entities/user/UserUtils.class.php';
include_once PHP_CLASS.'entities/photo/PhotoAlbum.class.php';
include_once PAGES.'myspots/myspots.php';

class ImportPage extends Page {
	
	/**
	 * @WebAction
	 * @Ajax
	 *
	 */
	public function test() {
		$pq = new PreparedQuery("reflirt_oud");
		$pq->setQuery("SELECT * FROM USER limit 1");
		DebugUtils::debug($pq->execute());
		
		$pq2 = new PreparedQuery("reflirt_nieuw");
		$pq2->setQuery("SELECT * FROM USERS limit 1");
		DebugUtils::debug($pq2->execute());
	}
	
	/**
	 * @WebAction
	 * @Ajax
	 */
	public function importCities() {
		include_once 'CityImport.php';
		$importer = new CityImport();
		$importer->import();	
	}
	
	/**
	 * @WebAction
	 * @Ajax
	 */
	public function importUsers() {
		include_once 'UserImport.php';
		$importer = new UserImport();
		$importer->import();	
	}
	
	/**
	 * @WebAction
	 * @Ajax
	 */
	public function importZoekopdrachten() {
		include_once 'ZoekopdrachtImport.php';
		$importer = new ZoekopdrachtImport();
		$importer->import();	
	}
	
	/**
	 * @Webaction
	 */
	public function overview() {
		
	}

}

class OldUser extends DatabaseEntity {
	
	function __construct() {
		parent::__construct("users_oud");
	}
}

class PhotoTemp extends DatabaseEntity {
	
	function __construct() {
		parent::__construct("photo");
	}
}

class OldPhoto extends DatabaseEntity {
	
	function __construct() {
		parent::__construct("fotos_oud");
	}
}


?>
