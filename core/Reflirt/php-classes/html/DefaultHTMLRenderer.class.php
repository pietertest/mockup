<?php

class DefaultHTMLRenderer {

	protected $ent;
    
    function DefaultHTMLRenderer(Entity $ent) {
    	$this->ent = $ent;
    }
    
    function get($what) {
    	return $this->ent->getString($what);
    }
}
?>