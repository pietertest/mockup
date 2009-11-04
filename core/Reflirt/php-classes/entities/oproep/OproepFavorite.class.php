<?php
include_once PHP_CLASS.'entities/mylocation/MyLocationFactory.class.php';
include_once PHP_CLASS.'entities/location/City.class.php';

class OproepFavorite extends DatabaseEntity {
	
    function __construct() {
    	parent::__construct("oproepfavorite");
    }
    
    public function getOproep() {
    	return $this->loadEntityByForeignKey(new OproepEntity, "oproepid");
    }
    
}
?>