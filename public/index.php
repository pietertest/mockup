<?php
@session_start();

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
define("FRAMEWORK_PATH", "C:/Documents and Settings/Pieter/Zend/workspaces/DefaultWorkspace7/framwork/");

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    FRAMEWORK_PATH,
    get_include_path(),
)));

include_once "Framework/FrameworkApplication.class.php";
//include
//include_once "/application/bootstrap.php";

$framework = new FrameworkApplication();

//$framework->setBootstrap(new Bootstrap());

$framework->run();

