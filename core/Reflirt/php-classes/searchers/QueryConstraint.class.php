<?php

interface QueryConstraint {
	function toString();
	
	function getValues();
}
?>