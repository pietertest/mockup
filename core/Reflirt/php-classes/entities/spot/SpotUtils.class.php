<?php

class SpotUtils {

    function SpotUtils() {
    }
    
    public function getNoOfMembers($spotid) {
    	$pq = new PreparedQuery("reflirt_nieuw");
		$pq->setQuery("SELECT COUNT(*) AS aantal FROM myspot where spotid = ".$spotid);
		$rs = $pq->execute();
		return $rs[0]["aantal"];
    }
    
    public function getNoOfPhotos($spotid) {
    	$pq = new PreparedQuery("reflirt_nieuw");
		$pq->setQuery("SELECT COUNT(*) AS aantal FROM spotphoto where spotid = ".$spotid);
		$rs = $pq->execute();
		return $rs[0]["aantal"];
    }
    
    public static function getMostPopular() {
    	$pq = new PreparedQuery("reflirt_nieuw");
		$query = "SELECT COUNT(*) AS aantal, spot.*, city.cicityname ".
				" FROM spot ".
				" LEFT JOIN myspot".
				" ON myspot.spotid = spot.systemid" .
				" LEFT JOIN city ON city.systemid = spot.cityid ".
				" GROUP BY spotid".
				" ORDER BY aantal DESC".
				" LIMIT 10";
		$pq->setQuery($query);
		$rs = $pq->execute(__FILE__, __LINE__, false);
		$systemUser = UserFactory::getSystemUser();
		return SearchObject::convert($rs, new Spot(), $systemUser);
    }
    
    public static function getLatest($cat) {
    	$pq = new PreparedQuery("reflirt_nieuw");
		$query = "SELECT spot.systemid as s, COUNT(myspot.systemid) AS aantal, spot.*, city.cicityname " .
				"FROM spot " .
				"LEFT JOIN myspot " .
				"ON myspot.spotid = spot.systemid " .
				"LEFT JOIN city " .
				"ON city.systemid = spot.cityid ";
		if ($cat) {
			$query .= " WHERE category = '".DBUtils::dbEscape($cat)."' ";
		}
		$query .= "GROUP BY spot.systemid " .
				"ORDER BY s DESC " .
				"LIMIT 10";
			
		$pq->setQuery($query);
		$rs = $pq->execute(__FILE__, __LINE__, false);
		$systemUser = UserFactory::getSystemUser();
		return SearchObject::convert($rs, new Spot(), $systemUser);
    }
    
}
?>