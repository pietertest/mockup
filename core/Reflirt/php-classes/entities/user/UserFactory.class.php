<?php

include_once PHP_CLASS.'entities/db/PreparedQuery.class.php';
include_once PHP_CLASS.'entities/user/User.class.php';
include_once PHP_CLASS.'entities/user/SystemUser.class.php';

class UserFactory {
	
	private static $systemUser = null;
	
    function UserFactory() {
    }
    
    public static function getUserBySystemid($systemid) {
    	Utils::assertTrue("Invalid userid: ".$systemid, $systemid);
    	return self::getUser(2, $systemid);
    }

    public static function getUserByLogin($login) {
    	Utils::assertNotEmpty("Invalid userid: ".$login, $login);
    	return self::getUser(1, $login);
    }
    
    private static function getUser($how, $value) {
    	$BY_LOGIN = 1;
    	$BY_SYSTEMID = 2;
    	$constraint = "";
    	switch($how) {
    		case $BY_LOGIN: 
    			$constraint = "WHERE username = '$value'"; 
    			break;
    		case $BY_SYSTEMID: 
    			$constraint = "WHERE users.systemid = '$value'"; 
    			break;
    		default: 
    			throw new Exception("No load method specified");
    	}
    	$pq = new PreparedQuery(DEFAULT_DATABASE);
    	$pq->setQuery("SELECT *, users.systemid AS systemid FROM users " .
    			" LEFT JOIN photo " .
    			" ON users.photoid = photo.systemid " .
    			" LEFT JOIN city ".
				" ON city.systemid = users.cityid ".
				" LEFT JOIN country ".
				" ON country.systemid = city.cicountryid ".
				$constraint);
    			 
    	$rs = $pq->execute();
    	if(!isset($rs[0])){
    		return null;
    	}
    	$user =  new User();
    	$user->putAll($rs[0]);
    	$user->setKey($rs[0]["systemid"]);
    	return $user;
    }

    static function getSystemUser() {
    	return new SystemUser();
    }
    
    static function isSystemUser($user) {
    	return $user instanceof SystemUser;
    }
}
?>