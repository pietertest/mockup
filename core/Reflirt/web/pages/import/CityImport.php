<?php
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/location/Province.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';

class CityImport {
	
	private static $COMMENT_CHAR = "#";
	
	private static $NEDERLAND_SYSTEMID = "1";
	
	private function clean() {
		$pq = new PreparedQuery("reflirt_nieuw");
		$pq->setQuery("DELETE FROM city");
		$pq->execute();
		$pq->setQuery("DELETE FROM province");
		$pq->execute();
		$pq->setQuery("DELETE FROM country");
		$pq->execute();
	}

	public function import() {
		$this->clean();
		$user = UserFactory::getSystemUser();
		
		$PLAATS		= 0;
		$GEMEENTE	= 1;
		$PROVINCIE	= 2;
		
		$row = 1;
		$handle = fopen(BASEDIR."resources/plaatsnamen.csv", "r");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			
			$firstWord = trim($data[0]);
			$isComment = $this->isComment($data);
		    if($isComment || empty($firstWord)) {
		    	continue;
		    }
		    $plaatsnaam	= $data[$PLAATS];
		    $gemeente	= $data[$GEMEENTE]; 
		    $provincie	= $data[$PROVINCIE];
		    
		    DebugUtils::debug("toevoegen: " . $plaatsnaam);
		    
		    $this->insertPlaats($user, $plaatsnaam, $gemeente, $provincie); 
		    $row++;
		}
		fclose($handle);
		
	}
	
	function insertPlaats(User $user, $plaatsnaam, $gemeente, $provincie) {
		$prov = $this->ensureProvince($user, $provincie, self::$NEDERLAND_SYSTEMID);
		
		$city = new City();
		$city->setUser($user);
		$city->put("cicityname", $plaatsnaam);
		$city->put("ciprovinceid", $prov->getKey());
		$city->put("cicountryid", self::$NEDERLAND_SYSTEMID);
		try {
			$city->save();
		} catch(DuplicateException $e) {
			echo $plaatsnaam . " bestaat al<br/>";
		}
	}
	
	function ensureProvince(User $user, $provincie, $countryid) {
//		$provDB = new Province();
//		$provDB->setUser($user);
//		$provDB->put("province", $provincie);
//		$provDB->load();

		$oq = ObjectQuery::buildACS(new Province, $user);
		$oq->addConstraint(Constraint::eq("province", $provincie));
		$oq->addConstraint(Constraint::eq("countryid", $countryid));
		$prov = SearchObject::select($oq);
		
		if($prov == null) {
			$prov = new Province();
			$prov->put("province", $provincie);
			$prov->put("countryid", $countryid);
			$prov->save();	
		}
		return $prov;	
	}
	
	/** @WebAction */
	public function overview() {
	}
	
	private function isComment($data) {
		if(count($data) > 0) {
			$row = trim($data[0]);
			$firstChar = substr($row, 0, 1);
			if ($firstChar == self::$COMMENT_CHAR) {
				return true;
			}
		}
		return false; 
	}
	
}

?>