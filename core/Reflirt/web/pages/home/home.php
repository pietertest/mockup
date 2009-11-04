<?php
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/message/Message.class.php';
include_once PHP_CLASS.'entities/mylocation/MyLocation.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';
include_once PHP_CLASS.'entities/user/UserUtils.class.php';
include_once PAGES.'myspots/myspots.php';

class HomePage extends Page {
	
	public static $SESSION_TIMEOUT = 1800; // 30 minuten
	
	private static $EXAMPLES = array(
			"Bijv.: \"Paradiso amsterdam\"",
			"Bijv.: \"Dansen bij Jansen\"",
			"Bijv.: \"disco Roosendaal\"",
			"Bijv.: \"Hogeschool van Amsterdam\"");

	/**
	 * @WebAction
	 * Ook mogelijk:
	 *  WebAction(role= "sdf", user="sdf")
	 */
	public function overview() {
		$categories = MyLocation::$CATEGORIES;
		$categories = array_merge_recursive(array("-1" => "Selecteer..."), $categories);
		$this->put("categories", $categories);
		
		// Latest users
		$systemUser = UserFactory::getSystemUser();
		$oq = ObjectQuery::buildACS(new User(), $systemUser);
		$oq->setOrderBy("users.insertdate");
		$oq->addConstraint(Constraint::gt("photoid", 0));
		$users = SearchObject::search($oq);
		shuffle($users);
		$this->put("latestUsers", array_slice($users, 0,4));
//		$latestUsersJSON = $this->getUserMappingJSON($users);
//		$this->put("userdata", json_encode($latestUsersJSON));
		
		
		// Laatste oproepen
		$oq = ObjectQuery::build(new OproepEntity(), UserFactory::getSystemUser(), 25);
		$oq->addParameters($this);
		$oq->setSearcher(OproepEntity::getSearcher());
		$laatsteOproepen = SearchObject::search($oq);
		shuffle($laatsteOproepen);
		$this->put("laatsteOproepen", array_slice($laatsteOproepen, 0, 5));
		
		// Populaire categories
		$pq = new PreparedQuery(DEFAULT_DATABASE);
		$pq->setQuery("SELECT count(*) AS amount, category.descr AS descr, category, category.name
			FROM oproep
			JOIN category
			ON oproep.category = category.systemid
			group by category
			order by amount DESC");
		$populairCategories = $pq->execute();
		
		foreach ($populairCategories as $key=>$cat) {
			$pqCat = new PreparedQuery(DEFAULT_DATABASE);
			$pqCat->setQuery("SELECT *, spot.name AS spotname, city.cicityname , count(*) AS amount FROM oproep
				JOIN spot
				ON oproep.spotid = spot.systemid
				join category
				ON oproep.category = category.systemid
				JOIN city
				ON oproep.cityid = city.systemid
				where oproep.category = " . $cat['category'] . "
				group by spotid
				order by amount DESC");
				$tempSpots = $pqCat->execute();
				if(count($tempSpots) > 0) {
					shuffle($tempSpots);
					$populairCategories[$key]['spots'] = array_slice($tempSpots, 0, 3);
				}
		}
		$this->put("populairCategories", $populairCategories);

		
		// Online Users
		//$this->put("onlineUsers", UserUtils::getOnlineUsers());
		
		
		// Latest Spots
		//$spots = SpotUtils::getLatest(null);
//		$json = array();
//		$json["items"] = $this->toArray($spots);
//		$json["template"] = file_get_contents(SMARTY_TEMPLATE_DIR."spotsearch/templates/spotresult.tpl");
//		$json["spotinfohtml"] = file_get_contents(SMARTY_TEMPLATE_DIR."spotsearch/templates/spotinfohtml.tpl");
//		$this->put("spots", json_encode($json));
//		$this->put("aantalspots", count($spots));
//		
//		$this->put("categories", Spot::getCategories(array("Alle categorien")));
		$this->put("example", self::$EXAMPLES[rand(0, 3)]);
		
		
	}
	
	private function getUserMappingJSON($users) {
		$aUsers = array();
		foreach($users as $user) { 
			$mapping = Utils::doFieldMapping($user, User::$MAPPING);
			if ($user->isOnline()) {
				$mapping["online"] = "yes";	
			} else {
				$mapping["online"] = "no";
			}
			$aUsers[] = $mapping;
		}
		$userJSON = array();
		$userJSON["template"] = file_get_contents(SERVLETS_TEMPLATE_DIR."/users/userpicture.tpl");
		$userJSON["infohtml"] = file_get_contents(SERVLETS_TEMPLATE_DIR."/users/userinfohtml.tpl");
		$userJSON["items"] = $aUsers;
		return $userJSON;
	}
	
	private function toArray($spots) {
		$aSpots = array();
		foreach($spots as $key=>$spot) {
			$aSpot = array();
			$aSpot["spotted"] = $spot->getString("aantal");
			$aSpot["categoryname"] = $spot->getFriendlyCategoryName();
			$aSpot["category"] = $spot->getString("category");
			$aSpot["name"] = $spot->getName();
			$aSpot["id"]= $spot->getKey();
			$aSpot["lat"]= $spot->getString("lat");
			$aSpot["lng"]= $spot->getString("lng");
			$aSpot["cityname"]= $spot->getString("cicityname");
			$aSpots[] = $aSpot;
			//DebugUtils::debug($aSpot);
		}		
		//DebugUtils::debug($aSpots);
		return $aSpots;
	}

}

?>
