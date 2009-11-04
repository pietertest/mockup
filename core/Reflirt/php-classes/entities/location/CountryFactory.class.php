<?php
include_once PHP_CLASS . 'entities/location/Country.class.php';
class CountryFactory {

	public static final function getCountry($countrname) {
		$oq = ObjectQuery::buildACS(new Country, UserFactory::getSystemUser());
		$oq->addConstraint(Constraint::eq("cocountryname", $countrname));
		return SearchObject::select($oq);
	}
}

?>