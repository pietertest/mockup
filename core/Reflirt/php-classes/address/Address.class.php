<?php

class Address {

    private $street;
    private $houseno;
    private $housenoext;
    private $zipcode;
    private $city;
    private $country;
    	
    function Address($street, $houseno, $housnoext, $zipcode, $city, $country = "Nederland") {
    	$this->street = $street;
    	$this->houseno = $houseno;
    	$this->housnoext = $housnoext;
    	$this->zipcode = $zipcode;
    	$this->city = $city;
    	$this->country = $country;
    }
    
    public function getStreet() {
    	return $this->street; 	
    } 
    
    public function getHouseno() {
    	return $this->houseno;
    } 
    
    public function getHousenoExt() {
    	return $this->housenoext;
    } 
    
    public function getZipcode() {
    	return $this->zipcode;
    }
    
    public function getCity() {
    	return $this->city;
    } 

    public function getCountry() {
    	return $this->country;
    } 
}
?>