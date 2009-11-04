<?php
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/oproep/OproepEntity.class.php';
include_once PHP_CLASS.'entities/oproep/OproepReaction.class.php';
include_once PHP_CLASS.'entities/oproep/OproepFavorite.class.php';
include_once PHP_CLASS.'searchers/Join.class.php';

class OproepPage extends Page {
	
	/**
	 * @WebAction
	 * @Login
	 */
	public function overview() {}
	
/**
	 * @WebAction
	 */
	public function view() {
		
		$systemid = $this->get("id");
		$oproep = EntityFactory::loadEntity(new OproepEntity(), UserFactory::getSystemUser(), $systemid);
		$oproepUser = UserFactory::getUserBySystemid($oproep->get("user"));
		
		$this->put("oproepUser", $oproepUser);
		$this->put("oproep", $oproep);
		
		if ($this->user != null) {
			$oq = ObjectQuery::buildACS(new OproepReaction(), $oproepUser);
			$oq->addConstraint(Constraint::eq("oproepid", $oproep->getKey()));
			$oq->addConstraint(Constraint::eq("fromuser", $this->getUser()->getKey()));
			$this->put("alreadReacted", SearchObject::search($oq, 1) != null);
			
			$oq = ObjectQuery::buildACS(new OproepFavorite(), $this->getUser());
			$oq->addConstraint(Constraint::eq("oproepid", $oproep->getKey()));
			$favorite = SearchObject::select($oq);
			$this->put("favorite", $favorite);
		}
		
		$this->setTitle("Ik zoek mijn flirt: " . $oproep->getHtml("location") . " (" . $oproep->getCategoryLabel() . ") - Reflirt.nl");
		$this->setDescription($oproep->getTitle() . ": " . $oproep->getHtml("onderschrift") . " - " . $oproep->get("message"));
	}
	
	/**
	 * @WebAction
	 * @Login
	 */
	public function reageer() {
		$systemid = $this->get("id");
		$oproep = EntityFactory::loadEntity(new OproepEntity(), UserFactory::getSystemUser(), $systemid);
		Utils::assertNotNull("Kan de oproep niet vinden: ".$systemid, $oproep);
		
		$oproepUser = UserFactory::getUserBySystemid($oproep->get("user"));
		
		$reaction = new OproepReaction();
		$reaction->setUser($oproepUser);
		$reaction->put("fromuser", $this->getUser()->getKey());
		$reaction->put("message", $this->get("message"));
		$reaction->put("oproepid", $oproep->getKey());
		$reaction->save();
		
		$this->forwardSuccess();
	}
	
	/**
	 * @WebAction
	 * @Login
	 */
	public function reageersuccess() {
		$this->setHeader("Oproep bekijken");
		$this->success("Je reactie is geplaatst");
		
	}
	
	/**
	 * @WebAction
	 * @Login
	 * @JSON
	 */
	public function favorite() {
		$systemid = $this->get("id");
		
		$favorite = new OproepFavorite();
		$favorite->setUser($this->getUser());
		$favorite->put("oproepid", $systemid);
		$favorite->save();
	}
	

}

?>
