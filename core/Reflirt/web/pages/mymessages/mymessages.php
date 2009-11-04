<?php
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/message/Message.class.php';

/**
 * @Login
 */
class MyMessagesPage extends Page {
	
	public function init() {
		$this->setHeader("Mijn Berichten");
	}
	
	/**
	 * @WebAction
	 */
	public function overview() {
		$oq = ObjectQuery::buildACS(new Message(), $this->getUser());
		$list = SearchObject::search($oq);
		$this->put("messages", $list);
	}
	
	/**
	 * @WebAction
	 */
	public function read() {
		$messageid = $this->getInt("id", -1);
		Utils::assertTrue("messageid == -1", $messageid != -1); 
		$message = EntityFactory::loadEntity(new Message(), $this->getUser(), $messageid);
		$message->put("viewed", true);
		$message->save();
		$this->put("message", $message);	
	}
	
	/**
	 * @WebAction
	 * @Json
	 */
	public function delete() {
		$id = explode(",", $this->get("id"));
		//DebugUtils::debug($id);
		$type = new Message();
		foreach($id as $key=>$value) {
			if(empty($value)) {
				continue;
			}
			EntityFactory::deleteEntity($type, $this->getUser(), $value);
		}
		
	}
	
	
	
}

?>
