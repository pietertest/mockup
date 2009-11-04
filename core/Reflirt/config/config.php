<?php define("DEBUG_QUERY", false);
define("DEBUG_SMARTY_TEMPLATES", false);

/*************************************************
	Constants
**************************************************/

define("PROGRAM_VERSION", 		"1.0");

define("SHOW_ERRORS",			1);
define("_DEBUG", 				true);

/** General **/
define("BASEDIR", 				realpath($_SERVER["DOCUMENT_ROOT"]."/..")."/");
define("LIB",					BASEDIR."lib/");
define("PAGES",					BASEDIR."/web/pages/");
define("PHOTOS",				BASEDIR."web/uploaded/photos/");
define("WEB",					BASEDIR."/web/");
define("UPLOAD",				BASEDIR."uploaded/");
define("ENABLE_CACHE",			true);
define("CACHEDIR",				BASEDIR."cache/");

/** Smarty constants **/
define("SMARTY",				LIB."smarty/Smarty-2.6.2/");
define("SMARTY_DIR",			SMARTY."libs/");
define("SMARTY_PLUGIN_DIR",		LIB."smarty/plugins/");
define("SMARTY_COMPILE_DIR",	SMARTY."templates_c/");
define("SMARTY_TEMPLATE_DIR",	BASEDIR."templates/");

/** Servlets constants **/
define("SERVLETS_DIR", 			WEB."/servlets/");
define("SERVLETS_TEMPLATE_DIR",	SMARTY_TEMPLATE_DIR."servlets/");

define("PHP_CLASS", 			BASEDIR."php-classes/");
define("PHP_CLASS_TEST",		BASEDIR."web/test/");

/** Database constants **/
define("DB_SERVER",				"localhost");
define("DB_USER",				"reflirt_user");
define("DB_PASSWORD",			"reflirt_pass123");
define("DEFAULT_DATABASE",		"reflirt_nieuw");
define("DATABASE_XML",			BASEDIR."config/database.xml");
define("DATABASE_XML_SCHEMA",	BASEDIR."config/database.tld");

// Libs constants
define("PHP_MAILER", 			BASEDIR."lib/phpMailer_v2.1.0beta2/");
define("ADDENDUM", 				LIB."addendum/");





/*************************************************
	Debugging
**************************************************/

if ((in_array($_SERVER["HTTP_HOST"], array("www.reflirt.nl")))) {
	define("IS_PRODUCTION", true);
} else {
	define("IS_PRODUCTION", true);
}

if(!defined("_DEBUG") || (defined("_DEBUG") && !_DEBUG)) {
//	echo "------------------------------";
//	echo "  Error log not implemented yet! ";
//	echo "------------------------------";
}



/*************************************************
	Localization
**************************************************/

/* Set locale to Dutch */
setlocale(LC_ALL, 'nld_nld');
date_default_timezone_set("Europe/Amsterdam");



/*************************************************
	Error handling
**************************************************/

function assert_failure($file, $line, $message) {
	throw new IllegalStateException($file, $line, $message);
}

assert_options(ASSERT_ACTIVE,		true);
assert_options(ASSERT_BAIL,			false);
assert_options(ASSERT_WARNING,		false);
assert_options(ASSERT_QUIET_EVAL,	true);
assert_options(ASSERT_CALLBACK, 	'assert_failure');

ini_set("display_errors", SHOW_ERRORS);

if(!_DEBUG){
	set_exception_handler("exception_handler");
//	set_error_handler("exception_handler");
//	set_error_handler("cust_error_handler");
}

function exception_handler($exception) {
	DebugUtils::printException($exception);
}



/*************************************************
	Home made function voor als een functie niet beschikbaar is in php
**************************************************/

include(BASEDIR."/functions/all.php");




/*************************************************
	Smarty
**************************************************/
require(SMARTY_DIR.'Smarty.class.php');

$smarty = new Smarty;

$smarty->template_dir = SMARTY_TEMPLATE_DIR;
$smarty->compile_dir = SMARTY_COMPILE_DIR;
//$smarty->plugins_dir = SMARTY_PLUGIN_DIR; // Wordt al geset met deze waarde in Smarty zelf
//$smarty->config_dir = '../configs/';
//$smarty->cache_dir = '../Smarty-2.6.2/cache/';
//$smarty->caching = 0;
//$smarty->compile_check = true;



class Config {
	private static $classPath 		= null;
	private static $documentRoot	= null;

    public static function getClassPath() {
        if (null == self::$classPath) {
            self::$classPath = realpath(self::getDocumentRoot()."/../php-classes");
        }
        return self::$classPath;
    }

    public static function getDocumentRoot() {
    	if(self::$documentRoot == null) {
    		self::$documentRoot = $_SERVER["DOCUMENT_ROOT"];
    	}
    	return self::$documentRoot;
    }
}
?>