<?php

class JoinedEntity extends Entity {

	private $joinedEntities = array();
	
    public function __construct($database, $table) {
    	parent::__construct($database, $table);
    }
    
    function addJoinedEntity(Entity $entity) {
    	$this->joinedEntities[] = $entity;
    }
    
    /** Override */
    /** @WebAction  */
    function save() {
    	$this->putAll($this);
    	$this->setUser($this->getUser());
    	parent::save();
    	
    	Utils::assertTrue("No rootentity specified", count($this->joinedEntities) > 0);
    	foreach($this->joinedEntities as $ent) {
    		$ent->putAll($this);
    		$ent->setUser($this->getUser());
    		$ent->save();
    		//TODO: met meerdere joinedentityes gaat dit niet goed
    		$this->put($this->getForeignKey(), $ent->getKey());
    		parent::save();
    		
    	}
    }
    
    /** Override */
    function load() {
		throw new Exception("Not implemented yet: JoinedEntity.load()");  
    }
}
?>