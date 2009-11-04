<?php
include_once PHP_CLASS.'searchers/Constraint.class.php';

class QueryConstraintList implements QueryConstraint {

	private $TYPE_AND = 1;
    private $TYPE_OR = 1;
    private $type = null;

    protected $constraints = array();
    protected $values = array();

    function add(QueryConstraint $constraint) {
    	$this->constraints[] = $constraint;
    }

    function addKey($key, $value) {
    	$this->add(Constraint::eq($key, $value));
    }

    function addIfKey($key, $value) {
    	if($this->ifCondition($value)) {
    		$this->add(Constraint::eq($key, $value));
    	}
    }
    
    function addKeyIn($key, array $value) {
    	if($this->ifCondition($value)) {
    		$this->add(Constraint::in($key, $value));
    	}
    }
    
    function addNotKey($key, $value) {
    	if($this->ifCondition($key)) {
    		$this->add(Constraint::neq($key, $value));
    	}
    }
    
    function addMatch($key, $value) {
    	if($this->ifCondition($key)) {
    		$this->add(Constraint::match($key, $value));
    	}
    }
    
    function addLike($key, $value) {
    	if(count($value) > 0) {
    		$this->add(Constraint::like($key, $value));
    	}
    }

    private function ifCondition($s) {
    	if(Utils::isEmpty($s)) {
    		return false;
    	}
    	return true;
    }

    function addConstraints(Array $list) {
   		throw new IllegalStateException("Gebruik add(Constraint) ipv van deze metode");
    	$this->constraints = array_merge($this->constraints, $list);
    }
    
    function toString() {
    	$s = "";
    	$counter = 0;
    	$num_items = count($this->constraints);
    	$first = true;
    	foreach($this->constraints as $constraint) {
    		$counter++;
    		$temp = $constraint->toString();
    		if(empty($temp)) {
    			continue;
    		}
			$s .= $temp;
			if($counter < $num_items) {
				$s .= " AND ";
			}
    	}
    	return $s;
    }
    
    public function getValues() {
    	$result = array();
    	foreach($this->constraints as $constraint) {
    		$result = array_merge($result, $constraint->getValues());
    	}
    	return $result;
    }
}
?>