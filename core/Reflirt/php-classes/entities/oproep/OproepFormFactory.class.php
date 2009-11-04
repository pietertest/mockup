<?php
/*
include_once PHP_CLASS.'entities/oproep/DiscoOproep.class.php';
include_once PHP_CLASS.'entities/oproep/CultureelOproep.class.php';
include_once PHP_CLASS.'entities/oproep/EetOproep.class.php';
include_once PHP_CLASS.'entities/oproep/ParkOproep.class.php';
include_once PHP_CLASS.'entities/oproep/SchoolOproep.class.php';
include_once PHP_CLASS.'entities/oproep/WerkOproep.class.php';
include_once PHP_CLASS.'entities/oproep/WinkelOproep.class.php';
include_once PHP_CLASS.'entities/oproep/RecreatieOproep.class.php';
include_once PHP_CLASS.'entities/oproep/WonenOproep.class.php';
include_once PHP_CLASS.'entities/oproep/HotelOproep.class.php';
include_once PHP_CLASS.'entities/oproep/EvenementOproep.class.php';
include_once PHP_CLASS.'entities/oproep/BuitenOproep.class.php';


include_once PHP_CLASS.'entities.oproep.EvenementOproep.class.php';
include_once PHP_CLASS.'entities.oproep.OvBusOproep.class.php';
include_once PHP_CLASS.'entities.oproep.OvMetroOproep.class.php';
include_once PHP_CLASS.'entities.oproep.OvTramOproep.class.php';

*/
class OproepFormFactory {

	public static function getSearchFields($cat){
    	Utils::assertNotEmpty("Unknown category", $cat);
    	
    	switch($cat){
    		case OproepEntity::$CAT_DISCO: 
    			return self::cityAndSpot(array("label" => "Naam disco/kroeg"));
    			break;
    		
    		case OproepEntity::$CAT_BUITEN: 
    			return array(self::city());
    			break;
    		
    		case OproepEntity::$CAT_CULTUREEL:
    			return self::cityAndSpot(array("label" => "Coort cultureel uitje"));
    			break;
    		
    		case OproepEntity::$CAT_EET: 
    			return self::cityAndSpot(array("label" => "Naam eetgelegenheid"));
    			break;
    			
    		case OproepEntity::$CAT_EVENEMENT: 
    			return self::cityAndSpot(array("label" => "Evenement"));
    			break;

    		case OproepEntity::$CAT_OV_TREIN: 
    			return array(self::city(array("label" => "Eindplaats trein"))); 
    			break;
    			
    		case OproepEntity::$CAT_PARK: 
    			return self::cityAndSpot(array("label" => "Naam (Thema)Park"));
    			break;
    			
    		case OproepEntity::$CAT_RECREATIE: 
//    			return array(self::city(), self::selectbox(array(
//						"label"			=> "Soort recreatie/spot:",
//    					"options"		=> RecreatieOproep::getSports(),
//						"name"			=> "sport",
//						"mandatory"		=> true,
//						"id"			=> "sport"
//					)
//				));
				return array(self::city());
    			break;
    			
    		case OproepEntity::$CAT_SCHOOL: 
    			return self::cityAndSpot(array("label" => "Naam school/instelling"));
    			break;
    			
    		case OproepEntity::$CAT_WERK: 
    			return self::cityAndSpot(array("label" => "Bedrijf"));
    			break;
    			
    		case OproepEntity::$CAT_WINKEL: 
    			return self::cityAndSpot(array("label" => "Winkel(keten)"));
    			break;
    			
    		case OproepEntity::$CAT_WONEN: 
    			return array(self::city());
    			break;
    			
    		case OproepEntity::$CAT_HOTEL:
    			return self::cityAndSpot(array("label" => "Hotel/hostel"));
    			break;
    			
    		case OproepEntity::$CAT_OV_BUS: 
    			return array(self::city(array("label" => "(Eind)plaats bus")));
    			break;
    		case OproepEntity::$CAT_OV_METRO: 
    			return array(self::city());
    			break;
    		case OproepEntity::$CAT_OV_TRAM:
    			return array(self::city()); 
    			break;
    		
    		case Oproep::$WONEN: 
    			$category = new WonenOproep();
    			break;

    			default:
    			throw new IllegalStateException("Kan bijbehorende categorie niet vinden: ".$cat);
    	}
    }
    
    private function cityAndSpot($options) {
    	
    	$defaults = array(
			"label"					=> $options["label"],
			"name"					=> "spotname",
			"mandatory" 			=> true,
			"autocomplete"			=> "spot",
 			"autocompleteParams"	=> "cityid=runtime:cityid&cat=runtime:category",
 			"dependsOn"				=> "cityname",
 			"idValue"				=> "spotid",
 			"valueValue"			=> "spotname",
 			"resultId"				=> "spotid"
		);
		
    	return array(
    		self::city(),
    		array_merge($defaults, $options)
  		);
    }
    
	private static final function city($options = array()) {

		$defaults = array(
			"label"			=> "Plaats",
			"name"			=> "cityname",
			"mandatory"		=> true,
			"autocomplete"	=> "city",
			"resultId"		=> "cityid",
			"id"			=> "cityname",
			"idField"		=> "cityid",
			"idValue"		=> "cityid",
 			"valueValue"	=> "cityname",
		);
		
		return array_merge($defaults, $options);
	}
	
	private static final function selectBox($options = array()) {

		$defaults = array(
			"type"			=> "selectbox",
		);
		
		return array_merge($defaults, $options);
	}
}
?>