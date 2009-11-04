<?php
include_once PHP_CLASS.'entities/mylocation/MyLocationFactory.class.php';
include_once PHP_CLASS.'entities/db/DatabaseEntity.class.php';

class MyLocation extends DatabaseEntity {

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
		16		=> "hotel",
	);
    
    function __construct() {
    	parent::__construct("reflirt_nieuw", "mylocation");
    }
    
    public static function getMyLocations(User $user) {
		$oq = ObjectQuery::buildACS(new MyLocation(), $user);
		$list = SearchObject::search($oq);
		$list2 = array();
		foreach($list as $key=>$mylocation) {
			$type = MyLocationFactory::newInstance($mylocation->getString("category"));
			$o = EntityFactory::loadEntity($type, $user, $mylocation->getString("oproepid"));
			$list2[] =$o;
		}
		return $list2;
	}
}

?>