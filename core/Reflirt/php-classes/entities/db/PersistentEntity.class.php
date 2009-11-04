<?php

interface PersistentEntity {

    function getKey();
    
    function save();
}
?>