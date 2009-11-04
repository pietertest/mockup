<?php

class OproepUtils {

    
    function OproepUtils() {
    	throw new RuntimeException("No instantation allowed!");
    }
    
    static function getFriendlyCatName($cat) {
		Utils::assertNotEmpty(MyLocation::$CATEGORIES[$cat],
     		"No category: ".$cat, $cat);
    	return MyLocation::$CATEGORIES[$cat];
    }
    
    /** 
     * @param cat Categorie nummer
     * @return Shortname voor gebruikt in templatenaame, bijv. cult, event, etc
     */
    static function getShortCatName($cat) {
		Utils::assertTrue("No shortname: ".$cat, isset(MyLocation::$SHORTNAMES[$cat]));
    	return MyLocation::$SHORTNAMES[$cat];
    }
    
    /**
     * Om een lijst met Zoekopdracht te converten naar Oproepen om ze gebruik 
     * te kunnen maken van de oproepinterface methode
     */
    static function convertToOproep($list) {
    	$oproepen = array();
    	foreach($list as $key=>$oproep) {
    		$cat = $oproep->getString("category");
    		$type = OproepFactory::getOproep($cat);
    		$ent = EntityFactory::loadEntity($type, $oproep->getUser(), $oproep->getString("oproepid"));
    		$oproepen[] = $ent;
    	}
    	return $oproepen;
    }
    
    
    
    
}
?>