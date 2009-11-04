<?php
/* @version $Id: UserEntity.class.php,v 1.1 2007/03/04 16:46:19 pieter Exp $ */


class Country extends DatabaseEntity{

	function __construct() {
		parent::__construct("reflirt_nieuw", "country");
    }
    
     
     public static function createCountryPulldownArray() {
    	$user = UserFactory::getSystemUser();
    	$oq = ObjectQuery::buildACS(new Country(), $user);
    	$list = SearchObject::search($oq);
    	
    	$countries = array("-1" => "Selecteer...");
    	foreach($list as $key=>$country) {
    		$id = $country->getKey();
    		$countries["$id"] = $country->getString("cocountryname");
    	}
    	return $countries;
    	
    }
}
?>