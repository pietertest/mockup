<?php
include_once PHP_CLASS.'entities/spot/Spot.class.php';
//include_once PHP_CLASS.'entities/spot/DiscoSpot.class.php';

class SpotFactory {
	
	public function newInstance($cat) {
		    	Utils::assertNotEmpty("Unknown category", $cat);
    	$spot = null;
    	switch($cat){
    		case Spot::$SPOT_DISCO: 
    			$spot = new DiscoSpot();
    			break;
    		/*
    		case Oproep::$CAT_EET: 
    			$spot = new EetOproep();
    			break;
    		
    		case Oproep::$SPOT_OV_TREIN: 
    			$spot = new OvTreinSpot();
    			break;
    		case Oproep::$CAT_WONEN: 
    			$spot = new WonenSpot();
    			break;
     		case Oproep::$CAT_BUITEN: 
    			$spot = new BuitenOproep();
    			break;
    		case Oproep::$CAT_CULTUREEL:
    			$spot = new CultureelOproep();
    			break;
    		case Oproep::$CAT_EET: 
    			$spot = new EetOproep();
    			break;
    		case Oproep::$CAT_EVENEMENT: 
    			$spot = new EvenementOproep();
    			break;
    		case Oproep::$CAT_OV_BUS: 
    			$spot = new OvBusOproep();
    			break;
    		case Oproep::$CAT_OV_METRO: 
    			$spot = new OvMetroOproep();
    			break;
    		case Oproep::$CAT_OV_TRAM: 
    			$spot = new OvTramOproep();
    			break;
    		case Oproep::$oCAT_OV_TREIN: 
    			$spot = new OvTreinOproep();
    			break;
    		case Oproep::$CAT_PARK: 
    			$spot = new ParkOproep();
    			break;
    		case Oproep::$CAT_RECREATIE: 
    			$spot = new RecreatieOproep();
    			break;
    		case Oproep::$CAT_SCHOOL: 
    			$spot = new SchoolOproep();
    			break;
    		case Oproep::$CAT_WERK: 
    			$spot = new WerkOproep();
    			break;
    		case Oproep::$CAT_WINKEL: 
    			$spot = new WinkelOproep();
    			break;
    		case Oproep::$WONEN: 
    			$spot = new WonenOproep();
    			break;
    		*/
    		default:
    			DebugUtils::debug("Kan bijbehorende categorie niet vinden: ".$cat);
    			return null;
    	}
    	return $spot;
		
	}
    
}


?>