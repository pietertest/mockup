<?php

interface Map {

    function get($key);

	function remove($key);
	
	function getString($key);

	function put($key, $value);
		
	function putIf($key, $value);

	function getAll();
	
	function getInt($key, $default);
}
?>