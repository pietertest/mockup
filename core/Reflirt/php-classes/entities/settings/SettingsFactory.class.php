<?php
include_once(PHP_CLASS.'entities/settings/SettingsEntity.class.php');

class SettingsFactory {

    static function saveSetting($user, $property, $value) {
    	$setting = new SettingsEntity();
    	$setting->setUser($user);
    	$setting->put("property", $property);
    	$setting->put("value", $value);
    	$setting->save();
    }
    
    static function deleteSetting($user, $property) {
    	$setting = new SettingsEntity();
    	$setting->setUser($user);
    	$setting->put("property", $property);
    	$setting->delete();
    }
    
    static function getSetting($user, $property) {
    	$setting = new SettingsEntity();
    	$setting->setUser($user);
    	$setting->put("property", $property);
    	$setting->load();
    	return $setting->getString('value');
    }
    
}
?>