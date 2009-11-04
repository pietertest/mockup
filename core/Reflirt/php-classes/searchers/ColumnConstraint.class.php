<?php
include_once PHP_CLASS.'searchers/QueryConstraint.class.php';
class ColumnConstraint implements QueryConstraint {

	private $key = null;
    private $operator = null;
    private $value = array();

	function ColumnConstraint($key, $op, $value) {
		$this->key = $key;
		$this->operator = $op;
		if(is_array($value)) {
			$this->value = $value;
		} else {
			array_push($this->value, $value);
		}
    }
    
    public function getValues() {
    	return $this->value;
    }

   	function toString() {
    	$numValues = count($this->value);
    	if($this->operator == "BETWEEN") {
    		return $this->key." BETWEEN ? AND ?";
    	} else if($this->operator == "IN") {
    		$c = $this->key." IN (";
    		for($i=0; $i < $numValues; $i++){
				$c .= " ? ";
	    		if($i < $numValues - 1 ) {
	    			$c .= ", ";
	    		}
    		}
    		$c .= ")";
    		return $c;
    	} else if($this->operator == "MATCH") {
    		return " MATCH($this->key) AGAINST (? IN BOOLEAN MODE)";
    	}
    	return $this->key. " " . $this->operator . ' ? '  ;
    }
    
}
?>