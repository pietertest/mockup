<?php

class UserUtils {

    public static final function getSimilarSpots(User $user1, User $user2) {
    	$spots1 = $user1->getSpots();
    	$spots2 = $user2->getSpots();
    	$same = array();
    	foreach ($spots1 as $key1=>$spot1) {
    		foreach ($spots2 as $key2=>$spot2) {
    			if ($spot1->getKey() == $spot2->getKey()) {
    				$same[] = $spot1;
    			} 	
    		}
    	}
    	
    	return $same;
    }
    
    public static function getOnlineUsers($max = 10) {
    	$systemUser = UserFactory::getSystemUser();
		$oq = ObjectQuery::buildDS(new User(), $systemUser);
		
		$lastActionMustBeBiggerThan = DateUtils::getDateTime(mktime() - Page::$SESSION_TIMEOUT);
		
		$oq->addConstraint(Constraint::gt("lastaction", $lastActionMustBeBiggerThan));
		return SearchObject::search($oq);
    }
}
?>