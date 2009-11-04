<?php

class SearchObject {

	static function search(ObjectQuery $searcher) {
		$rs = $searcher->execute();
		$aObjects = SearchObject::convert($rs, $searcher->getTarget(), $searcher->getUser());
		return $aObjects;
    }
    
    static function select(ObjectQuery $searcher) {
    	$result = $searcher->execute();
    	if(count($result) > 1 ) {
    		
    		throw new RuntimeException("More than one result found in " 
    						. " SearcObject::select: " . $searcher->getLastQuery());
    	}
    	if(count($result) == 0) {
    		return null;
    	}
    	
		$aObjects = SearchObject::convert($result, $searcher->getTarget(), $searcher->getUser());
		if(	count($aObjects)) {
			return $aObjects[0];
		}
		return null;
    }

    /**
     * Een resultset converten naar entiteiten
     */
    public static function convert($rs, $clazz, User $user) {
    	$aObjects = array();
    	foreach($rs as $line) {
//    	while($line = mysql_fetch_array($rs, MYSQL_ASSOC)) {
//			$ds = new DataSource();
			$entity = new $clazz();
			$entity->setUser($user);
			$entity->putAll($line);
			$systemid = $entity->getString("systemid");
			$entity->setKey($systemid);
			$aObjects[] = $entity;
    	}
    	return $aObjects;
    }
}
?>