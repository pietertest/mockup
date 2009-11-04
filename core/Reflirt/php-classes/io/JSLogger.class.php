<?php
include_once(PHP_CLASS."utils/DateUtils.class.php");
include_once(PHP_CLASS."entities/log/Log.class.php");

class JSLogger {

	private static $logger = null;
	private static $TYPE_INFO = 1;
	private static $TYPE_WARNING = 2;
	private static $TYPE_ERROR = 3;
	private static $FILENAME = null;
	private static $file = null;

    public static function warn($message) {
    	return self::getLogger()->log($message, self::$TYPE_WARNING);    	
    }
    
	public static function errorAndMail($message) {
    	return self::getLogger()->log($message, self::$TYPE_ERROR, true);    	
    }
    
    public static function error($message) {
    	return self::getLogger()->log($message);    	
    }
    
    public static function getLogger() {
    	if (self::$logger == null) {
    		self::$logger = new JSLogger();
    	}
    	return self::$logger;
    }

    function __construct() {
    	self::$FILENAME = $_SERVER["DOCUMENT_ROOT"]."/jslog.log";
    	$this->file = fopen(self::$FILENAME, "a");
    	if (!$this->file) {
    		throw new RuntimeException("Failed to initiate Javascript Logger logger!");
    	}
    }
    
    private function log($message, $type, $mail = IS_PRODUCTION) {
    	
    	
    	$log = new Log();
    	$log->put("errortype", $type);
    	$log->put("source", 2);	
    	$log->put("message", $message);
    	$log->save();
    	
    	$error_id = $log->getKey();
    	$log = DateUtils::now();
    	$log .= " [".$type."]: ".$message."\r\n";
    	fwrite($this->file, $log);
    	if ($mail) {
			self::mail($error_id, $log);    		
    	}
    	return $error_id;
    }
    
    private static final function mail($error_id, $log) {
    	$message = "Er is een belangrijke fout opgetreded. Deze is gelogd onder log <b>#$error_id</b><br/><br/><br/>";
    	$message .= "<br/>======== Log: ==========<br/>";
    	$message .= $log;
    	$message .= "<br/>======== Stacktrace: ==========<br/>";
    	$message .= self::getStackTrace();;
    	@mail("pieterfibbe@gmail.com", $_SERVER["HTTP_HOST"]." log (#$error_id)", $message);
    }
    
    private static final function getStackTrace() {
    	ob_start();
    	echo "<pre>";
    	DebugUtils::debug(debug_backtrace());
    	echo "\n\n\n\nPosted parameters: \n\n";
    	DebugUtils::debug($_REQUEST);
    	echo "\n\n\n\nSERVER parameters: \n\n";
    	DebugUtils::debug($_SERVER);
    	echo "</pre>";
    	$log = ob_get_contents();
    	
    	ob_end_clean();
    	return $log;
    }
    
}
?>