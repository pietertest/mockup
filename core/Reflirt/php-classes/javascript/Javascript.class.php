<?

class Javascript {
	
	private static $onload = array();
	
	public static function addOnload($js) {
		self::$onload[] = "_onloadListeners[_onloadListeners.length] = '".
				addslashes($js)."';";
	}
	
	public static function getJS() {
		$js = "";
		foreach(self::$onload as $listener) {
			$js .= $listener;
		}
		return $js;
	}
}

?>
