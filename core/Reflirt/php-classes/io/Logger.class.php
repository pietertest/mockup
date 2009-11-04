<?php
include_once(PHP_CLASS."utils/DateUtils.class.php");

class Logger {
	
	private static $logger = null;
	private static $TYPE_WARNING = "Warning";
	private static $TYPE_INFO = "Info";
	private static $TYPE_ERROR = "Error";
	private static $FILENAME = null;
	private static $file = null;

    public static function warn($message, $file, $line) {
    	return self::getLogger()->log($message, self::$TYPE_WARNING, $file, $line);    	
    }
    
	public static function errorAndMail($message, $file, $line) {
    	return self::getLogger()->log($message, self::$TYPE_ERROR, $file, $line, true);    	
    }
    
    public static function error($message, $file, $line) {
    	return self::getLogger()->log($message, self::$TYPE_ERROR, $file, $line);    	
    }
    
    public static function getLogger() {
    	if (self::$logger == null) {
    		self::$logger = new Logger();
    	}
    	return self::$logger;
    }

    function __construct() {
    	self::$FILENAME = $_SERVER["DOCUMENT_ROOT"]."/log.log";
    	$this->file = fopen(self::$FILENAME, "a");
    	if (!$this->file) {
    		throw new RuntimeException("Failed to initiate logger!");
    	}
    }
    
    private function log($message, $type, $file, $line, $mail = IS_PRODUCTION) {
    	$error_id = rand();
    	$log = DateUtils::now();
    	$log .= " [".$type."]: ".$message."\r\n";
    	$log .= $file.", Line: ".$line."\r\n";
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