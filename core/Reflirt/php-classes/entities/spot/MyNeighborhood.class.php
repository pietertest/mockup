<?php
include_once PHP_CLASS.'searchers/Constraint.class.php';
class MyNeighborhood {
	
	public function getPeople($zipcode, $cityid, $limit = 9) {
		if(empty($zipcode) && empty($cityid)) {
			return null;
		}
		$user = UserFactory::getSystemUser();
		$oq = ObjectQuery::build(new User(), $user);
		$oq->setSearcher(new DefaultInMijnNeighborhoodSearcher());
		$oq->addParameter("zipcode", $zipcode);
		$oq->addParameter("cityid", $cityid);
		$oq->setLimit($limit);
		return SearchObject::search($oq);
	}
	
	public static function getInMijnPostcodeJSON($users) {
		if (count($users) == 0) {
			return array();
		}
		$neighbours= array();
		foreach($users as $matchedUser) {
			$user = array();
			$user["lat"] = $matchedUser->get("lat");
			$user["lng"] = $matchedUser->get("lng");
			$user["photo"] = $matchedUser->get("filename");
			$user["user"] = $matchedUser->get("username");
			$neighbours[] = $user;
		}
		$json = array();
		$json["neighbours"] = $neighbours;
		$json["template"] = file_get_contents(SERVLETS_TEMPLATE_DIR."/users/userpicture.tpl");
		$json["infohtml"] = file_get_contents(SERVLETS_TEMPLATE_DIR."/users/userinfohtml.tpl");
		return json_encode($json);
	}
	
	private function deDuplicate($postcodeMatches, $myspotsMatches) {
		$results = array();
		
		if($postcodeMatches) {
			foreach ($postcodeMatches as $match1=>$user1) {
				$results[$user1->getKey()] = $user1;
			}
		}
		if ($myspotsMatches) {
			foreach ($myspotsMatches as $spot=>$oproep) {
				$list = $oproep->getMatches();
				if(count($list)) {
					foreach ($list as $match) {
						$username = $match->getString("username");
						$u = UserFactory::getUserByLogin($username);
						$results[$u->getKey()] = $u;
					}
				}
				
			}
		}
		
		return $results;
	}
	
}

class DefaultInMijnNeighborhoodSearcher extends Searcher {
	
	public function getFields(DataSource $ds) {
		return "*, users.systemid AS systemid";
	}
	
	public function getTables(DataSource $ds) {
		$select	= 	" FROM users ".
					" LEFT JOIN photo " .
	    			" ON users.photoid = photo.systemid ".
	    			" LEFT JOIN city " .
	    			" ON users.cityid = city.systemid";
		return $select;
	}
	
	public function getFilter(DataSource $ds) {
		$list = new QueryConstraintList();
		$zipcode = $ds->getString("zipcode");
		if(!empty($zipcode)) {
			$list->addLike("zipcode", $zipcode + "%");
		} else {
			$list->addLike("cityid", $ds->getString("cityid"));
		}
		if (isset($_SESSION['uid'])) {
			$list->addNotKey("users.systemid", $_SESSION['uid']);
		}	
		return $list;
	}
	
	public function getOrderBy(DataSource $ds) {
		return "lastlogin";
	}
		
}


?>
