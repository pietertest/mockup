<?php
include_once PHP_CLASS . 'entities/location/City.class.php';

class CityFactory {
	
	public static final function getCity($city) {
		$oq = ObjectQuery::buildACS(new City, UserFactory::getSystemUser());
		$oq->addConstraint(Constraint::eq("cicityname", $city));
		return SearchObject::select($oq);
	}
	
}

?>