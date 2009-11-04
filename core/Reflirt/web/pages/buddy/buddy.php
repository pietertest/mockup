<?php
include_once PHP_CLASS.'entites/buddy/Buddy.class.php';

/**
 * Deze klasse wordt nog niet gebruikt, de favorites klasse wordt hvoor dit idee gebruikt
 */
class BuddyPage extends Page {
	
	/** @WebAction */
	public function overview() {
		
	}
	
	/** @WebAction
	 *  @Login
	 */
	public function addintro() {
		$buddy = new Buddy();			
	}
	
		
}

?>
