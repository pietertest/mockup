<?php

interface MediaEntity extends PersistentEntity {
	
	/**
	 * @return BaseFile 
	 *
	 */
	function getFile();
	
}

?>