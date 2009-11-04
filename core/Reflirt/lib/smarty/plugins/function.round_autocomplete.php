<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {counter} function plugin
 *
 * Type:     function<br>
 * Name:     counter<br>
 * Purpose:  print out a counter value
 * @link http://smarty.php.net/manual/en/language.function.counter.php {counter}
 *       (Smarty online manual)
 * @param array parameters
 * @param Smarty
 * @return string|null
 */


function smarty_function_round_autocomplete($params, &$smarty)
{
	require_once $smarty->_get_plugin_filepath('function','round_textfield');
	$htmlObject = createRoundTextField($params, $smarty);
	
	$html = $htmlObject["html"];
	
	$id = $htmlObject["id"];
    $name = $htmlObject["name"];
    $value = $htmlObject["value"];
    
	if(isset($params['autocomplete'])) {
		$type = $params['autocomplete']; // Bijv. "city";
    	$html .= initAutocomplete(
    		$type, 
    		$id, 
    		$name, 
    		$params
    	);
    } 
            
    return $html;
}

function initAutocomplete($type, $id, $name, $params) {
	
	$options = array();
	
	$autocompleteParams = "";
	
	// resultId: Het id van het hidden field waarin het resultaat komt, dit kan je 
	// nodig hebben zodat je dit als runtime autocompleteParmeter kan meegevens
	// bij een andere autocomplete 
	
 	foreach($params as $_key=>$_value) {
    	switch($_key) {
    		case 'selectFirst':
    		case 'mustMatch':
    		case 'formatItem':
    		case 'formatResult':
    			if(is_bool($_value)) {
   					$options[$_key] = $_value ? "true" : "false"; // Omzetten naar string voor javascript
    			} else {
	    			if(!empty($_value)) { 
	    				$options[$_key] = $_value;
	    			}
    			}
    			break;
    			
    		case 'systemid':
    			if(!empty($_value)) {
	    			$options["id"] = $_value;
    			}
    			break;
    			
    		case 'resultId':
    		case 'dependsOn':
    			$$_key = "\"$_value\"";
    			$options[$_key] = "\"$_value\"";
    			break;
    			 
    		case 'autocompleteParams':
    			$$_key = $_value;
    			break;
    	}
    }
    
	// Check of dit type autocomplete mag
    $allowedTypes = array("city", "spot");
    if (!empty($type) && !in_array($type, $allowedTypes)) {
		throw new Exception("type '$type' not allowed for autocomplete field");
	}
	
	// Dit is het "data" veld in de options van de autocomplete  
	$extraParams = _parseAutocompleteParams($autocompleteParams);
   	$extraParams["servlet"] = "\"autocomplete\"";
	$extraParams["action"] = "\"$type\"";
	
   	
	$jsExtraParams = array();
	foreach($extraParams as $key=>$value) {
		$jsExtraParams[] = $key . ":" . $value;	
	}
	
	// Maak een array om straks in javascript uit te printen
	$jsOptions = array();
	foreach($options as $key=>$value) {
		$jsOptions[] = $key . ":" . $value;	
	}
	
	// Voeg de extraParams toe onder de gelijknamige index 
	$jsOptions[] = "extraParams: {" . join(",\n", $jsExtraParams) . "}";
	
	$javascript = <<<EOD
	<script>
	$(function() {
		
		var jField = $("#$id"); 
		
		var options = {
EOD;
			$javascript .= join(",\n", $jsOptions);
			$javascript .= <<<EOD
		};
			
		jField.eAutocomplete(options);
		
	});
EOD;
	

	$javascript .= "</script>";
	return $javascript;
	
}


function _parseAutocompleteParams($autocompleteParams) {
	$result = array();
	
	$temp = explode("&", $autocompleteParams);
   	foreach($temp as $param) {
   		if(empty($param)) {
   			continue;
   		}
   		$a = explode("=",$param);
		$key = $a[0];
		$value = $a[1];
		
		// Checken of het een runtime param is (naam=runtime:name)
		$runtime = explode("runtime:", $value);
		if(count($runtime) == 1) {
			$value = "\"$value\"";
		} else {
			$value = "function() { return $('#$runtime[1]').val()}";
		}
		$result[$key] = $value;		
   	}
   	return $result;
}
/* vim: set expandtab: */

?>