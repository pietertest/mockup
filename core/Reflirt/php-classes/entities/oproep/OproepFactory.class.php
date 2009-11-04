<?php
include_once PHP_CLASS.'entities/oproep/BuitenOproep.class.php';
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
include_once PHP_CLASS.'entities/oproep/OvTreinOproep.class.php';
include_once PHP_CLASS.'entities/oproep/OvTramOproep.class.php';
include_once PHP_CLASS.'entities/oproep/OvMetroOproep.class.php';
include_once PHP_CLASS.'entities/oproep/OvBusOproep.class.php';

//include_once PHP_CLASS.'entities/mylocation/MyDiscoLocation.class.php';
//include_once PHP_CLASS.'entities/mylocation/MyOvTreinLocation.class.php';
//include_once PHP_CLASS.'entities/mylocation/MyOvTreinLocation.class.php';
//include_once PHP_CLASS.'entities/mylocation/MyWonenLocation.class.php';
/*


include_once PHP_CLASS.'entities.oproep.EvenementOproep.class.php';
include_once PHP_CLASS.'entities.oproep.OvBusOproep.class.php';
include_once PHP_CLASS.'entities.oproep.OvMetroOproep.class.php';
include_once PHP_CLASS.'entities.oproep.OvTramOproep.class.php';




*/
class OproepFactory {

	public static function getLocation($cat){
    	Utils::assertNotEmpty("Unknown category", $cat);
    	$category = null;
    	switch($cat){
    		case MyLocation::$CAT_DISCO: 
    			$category = new MyDiscoLocation();
    			break;
    		/*
    		case MyLocation::$CAT_OV_TREIN: 
    			$category = new MyOvTreinLocation();
    			break;
    		case MyLocation::$CAT_WONEN: 
    			$category = new MyWonenLocation();
    			break;
     		case Oproep::$CAT_BUITEN: 
    			$category = new BuitenOproep();
    			break;
    		case Oproep::$CAT_CULTUREEL:
    			$category = new CultureelOproep();
    			break;
    		case Oproep::$CAT_EET: 
    			$category = new EetOproep();
    			break;
    		case Oproep::$CAT_EVENEMENT: 
    			$category = new EvenementOproep();
    			break;
    		case Oproep::$CAT_OV_BUS: 
    			$category = new OvBusOproep();
    			break;
    		case Oproep::$CAT_OV_METRO: 
    			$category = new OvMetroOproep();
    			break;
    		case Oproep::$CAT_OV_TRAM: 
    			$category = new OvTramOproep();
    			break;
    		case Oproep::$oCAT_OV_TREIN: 
    			$category = new OvTreinOproep();
    			break;
    		case Oproep::$CAT_PARK: 
    			$category = new ParkOproep();
    			break;
    		case Oproep::$CAT_RECREATIE: 
    			$category = new RecreatieOproep();
    			break;
    		case Oproep::$CAT_SCHOOL: 
    			$category = new SchoolOproep();
    			break;
    		case Oproep::$CAT_WERK: 
    			$category = new WerkOproep();
    			break;
    		case Oproep::$CAT_WINKEL: 
    			$category = new WinkelOproep();
    			break;
    		case Oproep::$WONEN: 
    			$category = new WonenOproep();
    			break;
    		*/
    		default:
    			DebugUtils::debug("Kan bijbehorende categorie niet vinden: ".$cat);
    			return null;
    	}
    	return $category;
    }
    
    static function getOproepLabel($catName){
    	return MyLocationFactory::newInstance($catName)->getLabel(); 
    }
    
    
    public static function getOproep($catName){
    	$category = null;
    	switch($catName){
    		case OproepEntity::$CAT_BUITEN: 
    			$category = new BuitenOproep();
    			break;
    		case OproepEntity::$CAT_CULTUREEL: 
    			$category = new CultureelOproep();
    			break;
    		case OproepEntity::$CAT_DISCO: 
    			$category = new DiscoOproep();
    			break;
    		case OproepEntity::$CAT_EET: 
    			$category = new EetOproep();
    			break;
    		case OproepEntity::$CAT_EVENEMENT: 
    			$category = new EvenementOproep();
    			break;
    		case OproepEntity::$CAT_OV_BUS: 
    			$category = new OvBusOproep();
    			break;
    		case OproepEntity::$CAT_OV_METRO: 
    			$category = new OvMetroOproep();
    			break;
    		case OproepEntity::$CAT_OV_TRAM: 
    			$category = new OvTramOproep();
    			break;
    		case OproepEntity::$CAT_OV_TREIN: 
    			$category = new OvTreinOproep();
    			break;
    		case OproepEntity::$CAT_PARK: 
    			$category = new ParkOproep();
    			break;
    		case OproepEntity::$CAT_RECREATIE: 
    			$category = new RecreatieOproep();
    			break;
    		case OproepEntity::$CAT_SCHOOL: 
    			$category = new SchoolOproep();
    			break;
    		case OproepEntity::$CAT_WERK: 
    			$category = new WerkOproep();
    			break;
    		case OproepEntity::$CAT_WINKEL: 
    			$category = new WinkelOproep();
    			break;
    		case OproepEntity::$CAT_WONEN: 
    			$category = new WonenOproep();
    			break;
    		case OproepEntity::$CAT_HOTEL: 
    			$category = new HotelOproep();
    			break;
    		default:
    			DebugUtils::debug("Kan bijbehorende categorie niet vinden: ".$catName);
    			return null;
    	}
    	return $category;
    }
}
?>