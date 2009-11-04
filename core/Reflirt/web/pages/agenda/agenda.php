<?
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/bookmark/Bookmark.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/agenda/Agenda.class.php';
include_once PHP_CLASS.'entities/agenda/AbstractAgenda.class.php';
include_once PHP_CLASS.'entities/spot/SpotAgenda.class.php';
include_once PHP_CLASS.'entities/db/EntityUtils.class.php';

class AgendaPage extends Page {
	
	private $FEEDBACK_SUCCESSFULLY_SAVED = 1;
	private $FEEDBACK_SUCCESSFULLY_ADDED_REACTION = 2;
	
	// Override
	protected function initFeedbacks() {
		$this->addFeedback(new Feedback(
			$this->FEEDBACK_SUCCESSFULLY_SAVED, Feedback::$TYPE_SUCCESS, "successfullysaved"));
		$this->addFeedback(new Feedback(
			$this->FEEDBACK_SUCCESSFULLY_ADDED_REACTION, Feedback::$TYPE_SUCCESS, "successfullyaddedreaction"));
	}
	
	/**
	 * @WebAction
	 */
	public function view(){
		$systemid = $this->checkValidId("id");
		$systemUser = UserFactory::getSystemUser();
		$agenda = EntityFactory::loadEntity(new SpotAgenda, $systemUser, $systemid);
		$this->put("agenda", $agenda);
		$this->put("spot", $agenda->getSpot());
		$this->put("reactions", $agenda->getReactions());
	}
	
	/**
	 * @Login
	 * @WebAction
	 */
	public function addreaction() {
		$systemid = $this->checkValidId("id");
		$systemUser = UserFactory::getSystemUser();
		$agenda = EntityFactory::loadEntity(new SpotAgenda, $systemUser, $systemid);
		$id = $agenda->getRealAgenda()->getKey();
		$reaction = new AgendaReaction();
		$reaction->setUser($this->getUser());
		$reaction->put("agendaid", $id);
		$reaction->put("message", $this->getString("message"));
		$reaction->save();
		$this->forward("agenda.view", $systemid, $this->FEEDBACK_SUCCESSFULLY_ADDED_REACTION);
	}
	
	/**
	 * @WebAction
	 * @Login
	 */
	public function save(){
		$ent = EntityUtils::save(new SpotAgenda, $this->getUser(), $this);
		$this->forward("agenda.view", $ent->getKey(), $this->FEEDBACK_SUCCESSFULLY_SAVED);
	}
	
	
	/**
	 * @WebAction
	 */
	public function overview(){
		throw new PageNotFoundException("pagina niet gevonden");
	}
}
?>