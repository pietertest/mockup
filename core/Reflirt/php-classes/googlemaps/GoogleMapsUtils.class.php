<?php

class GooglemapsUtils {

	public static $GOOGLEMAPS_VERSION = "2";
	public static $GOOGLEMAPS_URL = "http://maps.google.com/maps?file=api";
	public static $GOOGLEMAPS_KEY_LOCAL = "emeet.nl";
	public static $GOOGLEMAPS_KEYS = array(
		"localhost" => "blaat",
		"hansteeuwen.reflirt.nl" => "ABQIAAAA3n07O4aCSgTfkKjJ2cfY3BRmMXC51QLwpPdj6A3VJ6HpF8QrYxTVZ315DxlF8fBDcBZB_AcrzzgoZw",
		"www.emeet.nl" => "ABQIAAAA3n07O4aCSgTfkKjJ2cfY3BTzeUmB3h9sygexlkWOJiDL_P5pEhTqUCKbdwk_KR8IUIuiDLWtIZQOhA",
		"emeet.nl" => "ABQIAAAA3n07O4aCSgTfkKjJ2cfY3BTzeUmB3h9sygexlkWOJiDL_P5pEhTqUCKbdwk_KR8IUIuiDLWtIZQOhA"
	);
		
		
	
	public static function getScriptUrl() {
		$url = new Url(self::$GOOGLEMAPS_URL);
		$url->addParameter("v", self::$GOOGLEMAPS_VERSION);
		$host = $_SERVER["HTTP_HOST"];
		if(isset(self::$GOOGLEMAPS_KEYS[$host])) {
			$url->addParameter("key", self::$GOOGLEMAPS_KEYS[$host]);
		} else {
			$url->addParameter("key", "nospecified");
		}
		return $url->toString();
	}
}
?>