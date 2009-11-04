<?php
include_once PHP_CLASS.'core/web/Visitable.class.php';

interface IOproep extends Visitable {
	
	/**
	 * Geef het label terug waarmee deze category getoond wordt
	 *
	 */
	function getLabel();

}

?>