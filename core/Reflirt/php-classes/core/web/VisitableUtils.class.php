<?php

class VisitableUtils {
	
	/**
	 * Geeft een url terug voor de representatie van deze klasse 
	 */
	public static function getUrl(Visitable $visitable) {
		$id = $visitable->getKey();
		Utils::assertTrue("Entity has to be loaded to visist", $id != -1);
		
		$url = new Url();
		
		throw new IllegalStateException("not yet implemented!");
		
	}
}

?>