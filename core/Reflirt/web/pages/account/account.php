<?php
include_once PHP_CLASS.'entities/message/Message.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';
include_once PHP_CLASS.'entities/oproep/OproepFavorite.class.php';
include_once PHP_CLASS.'entities/user/UserProfile.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/note/Note.class.php';
include_once PHP_CLASS.'entities/spot/MyNeighborhood.class.php';
include_once PHP_CLASS.'entities/spot/SpotAdmin.class.php';
include_once PHP_CLASS.'entities/oproep/OproepReaction.class.php';
include_once PHP_CLASS.'entities/user/UserFavorite.class.php';
include_once PAGES.'myspots/myspots.php';

/**
 * @Login
 */
class AccountPage extends Page {

	/**
	 * 
	 * @WebAction
	 * Ook mogelijk:
	 *  WebAction(role= "sdf", user="sdf")
	 * 
	 */
	public function overview() {
		// Admin statistics
		$this->setTitle("Mijn overzicht");
		$username = $this->getUser()->getUsername();
		if($username == "pieter") {
			$pq = new PreparedQuery(DEFAULT_DATABASE);
			$pq->setQuery("SELECT count(*) as amount FROM users");
			$rs = $pq->execute();
			$this->put("members", $rs[0]["amount"]);
		}
		
		// Berichten
		$oq = ObjectQuery::buildACS(new Message(), $this->getUser());
		$oq->addConstraint(Constraint::eq("viewed", 0));
		$messages = SearchObject::search($oq);//		$zipcode = $this->getUser()->getString("zipcode");
		$this->put("messages", $messages);
		
		// Favorieten oproepen
		$oq = ObjectQuery::buildACS(new OproepFavorite(), $this->getUser());
		$favoriteOproepen = SearchObject::search($oq);//		$zipcode = $this->getUser()->getString("zipcode");
		$this->put("favoriteOproepen", $favoriteOproepen);
		
		$oq = ObjectQuery::build(new UserFavorite(), $this->getUser());
		$oq->setSearcher(UserFavorite::getOverviewSearcher());
		$favorites = SearchObject::search($oq);
		$this->put("favoriteUsers", $favorites);
		
		//Oproepen
		$oproepen = OproepEntity::getOproepen($this->getUser(),
			OproepEntity::$TYPE_REFLIRT);
			
		// Oproepreacties
		// Edit: dit wordt nu in het template per oproep geladen
		$oq = ObjectQuery::buildACS(new OproepReaction(), $this->getUser());
		$oq->setOrderBy("insertdate DESC");
		$oproepreacties = SearchObject::search($oq, 10);
		
		$lastlogout = $this->getUser()->get("lastlogout");
		
		$oproepenArray = array();
		
		// Nieuwe resultaten sinds login?
		$totalNewReactions = 0;
			
		foreach ($oproepen as $oproep) {
			$temp = array();
			$temp["oproep"] = $oproep;
			$reactions = array();
			
			// Nieuwe reacties sinds login?
			$newReactions = 0;
			
			foreach ($oproepreacties as $reaction) {
				if ($reaction->get("oproepid") == $oproep->getKey()) {
					$tempReaction = array();
					$tempReaction["reaction"] = $reaction;
					$tempReaction["isnew"] = false;
					if (DateUtils::getDateDiffSeconds($lastlogout, $reaction->get("insertdate")) < 0) {
						$newReactions++;
						$totalNewReactions++;
						$tempReaction["isnew"] = true;
					}
					$reactions[] = $tempReaction;
				}
			}
			$temp["reactions"] = $reactions;
			$temp["newReactions"] = $newReactions;
			$oproepenArray[$oproep->getKey()] = $temp;
		}
		
		
		$oproepenArray = array_reverse($oproepenArray);
		// mijn oproepen
		$this->put("totalNewReactions", $totalNewReactions);
		$this->put("oproepen", $oproepenArray);
		
//		if(!empty($zipcode)) {
//			$zipcode = substr($zipcode, 0, 4);
//		}
//		$zipcodematches = MyNeighborhood::getPeople($zipcode, $this->get("cityid"), 20);
//		$zipcodematchesJSON = MyNeighborhood::getInMijnPostcodeJSON($zipcodematches);
		//$mylocations = MyLocation::getMyLocations($this->getUser());
		
//		$myspots = MySpotsPage::getMySpots($this->getUser());
		
		//$reflirtZoekopdrachten = Zoekopdracht::getZoekopdrachten($this->getUser(), Zoekopdracht::$TYPE_REFLIRT);
		//$preflirtZoekopdrachten = Zoekopdracht::getZoekopdrachten($this->getUser(), Zoekopdracht::$TYPE_PREFLIRT);
		
		// Haal spots op waar je beheerder van bent
//		$oq = ObjectQuery::buildACS(new SpotAdmin(), $this->getUser());
//		$adminspots = SearchObject::search($oq);
		
		
		//$this->put("reflirtzoekopdrachten", $reflirtZoekopdrachten);
		//$this->put("preflirtzoekopdrachten", $preflirtZoekopdrachten);
//		$this->put("mylocations", $mylocations);
//		$this->put("myspots", $myspots);
//		$this->put("zipcodematches", $zipcodematches);
//		$this->put("zipcodematchesJSON", $zipcodematchesJSON);
//		$this->put("adminspots", $adminspots);
		
	}
	
	private function getInMijnSpotsMatches($myspots) {
		$postcodeMatches = $this->getInMijnPostcode($this->getUser());
		$results = $this->deDuplicate($postcodeMatches, $myspots);
		return $results;
	}
	
	 
	
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function deletefavuser() {
		$systemid = $this->get("id");
		EntityFactory::deleteEntity(new UserFavorite, $this->getUser(), $systemid);
	}

	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function deletefavadd() {
		$systemid = $this->get("id");
		EntityFactory::deleteEntity(new OproepFavorite, $this->getUser(), $systemid);
	}
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function deleteadd() {
		$systemid = $this->get("id");
		EntityFactory::deleteEntity(new OproepEntity(), $this->getUser(), $systemid);
	}
	
	/**
	 * @WebAction
	 * @Login
	 */
	public function deletenote() {
		$noteid = $this->getString("noteid");
		EntityFactory::deleteEntity(new Note, $this->getUser(), $noteid);
		
		$this->overview();
		$this->setTemplate("overview");
		throw new UserFriendlyMessage("Het bericht is verwijderd");
	}
	
}



?>
