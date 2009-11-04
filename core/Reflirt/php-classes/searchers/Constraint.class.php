<?php
include_once PHP_CLASS.'searchers/ColumnConstraint.class.php';
include_once PHP_CLASS.'searchers/QueryConstraint.class.php';

abstract class Constraint  {

    static function eq($key, $value) {
    	return new ColumnConstraint($key, "=", $value);
    }

    static function neq($key, $value) {
    	return new ColumnConstraint($key, "!=", $value);
    }

    static function lt($key, $value) {
    	return new ColumnConstraint($key, "<", $value);
    }

    static function let($key, $value) {
    	return new ColumnConstraint($key, "<=", $value);
    }

    static function gt($key, $value) {
    	return new ColumnConstraint($key, ">", $value);
    }

    static function geq($key, $value) {
    	return new ColumnConstraint($key, ">=", $value);
    }

    static function match($keys, $value) {
    	return new ColumnConstraint($keys, "MATCH", $value);
    }

    static function between($key, $small, $big) {
    	return new ColumnConstraint($key, "BETWEEN", array($small,$big));
    }
    
    static function in($key, array $in) {
    	return new ColumnConstraint($key, "IN", $in);
    }

    static function like($key, $value) {
    	return new ColumnConstraint($key, "LIKE", "$value%");
    }
}
?>