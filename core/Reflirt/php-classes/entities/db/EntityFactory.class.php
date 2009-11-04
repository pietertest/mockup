<?php

class EntityFactory {

    public static function loadEntity(Entity $ent, User $user, $systemid) {
    	$oq = ObjectQuery::buildACS($ent, $user);
    	$oq->addConstraint(Constraint::eq($ent->getTable()->getKeyColumn(), $systemid));
    	return SearchObject::select($oq);
    }
    
    public static function deleteEntity(Entity $ent, User $user, $systemid) {
    	$ent->setUser($user);
    	$ent->setKey($systemid);
    	$ent->delete();
    }
}
?>