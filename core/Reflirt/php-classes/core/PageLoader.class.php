<?php
include_once PHP_CLASS.'core/DataSource.class.php';
include_once PHP_CLASS.'utils/Utils.class.php';
include_once PHP_CLASS.'utils/StopWatch.class.php';
include_once PHP_CLASS.'core/Page.class.php';
include_once PHP_CLASS.'core/PageComponent.class.php';
include_once PHP_CLASS.'core/PageErrorHandler.class.php';
include_once PHP_CLASS.'javascript/Javascript.class.php';
include_once PHP_CLASS.'lang/Translator.class.php';
include_once PHP_CLASS.'exception/UserFriendlyMessageException.class.php';
include_once PHP_CLASS.'exception/PageNotFoundException.class.php';
include_once PHP_CLASS.'exception/UserFriendlyMessage.class.php';
include_once PHP_CLASS.'annotation/WebAction.class.php';
include_once PHP_CLASS.'annotation/Login.class.php';
include_once PHP_CLASS.'annotation/Ajax.class.php';
include_once PHP_CLASS.'annotation/JSON.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/db/DatabaseEntity.class.php';
include_once PHP_CLASS.'entities/spot/Spot.class.php';
include_once PHP_CLASS.'actionresult/ActionResult.class.php';
include_once PHP_CLASS.'actionresult/ActionResult.class.php';

include_once(LIB.'addendum/annotations.php');
require_once(LIB.'addendum/annotations/annotation_parser.php');

class PageLoader extends DataSource{
	private $ds 			= null;
	private $PARAM_CLASS 	= 'p';
	private $pageHandler 	= null;
	private $PAGES			= 'pages/';

    function __construct(DataSource $ds) {
    	$this->ds = $ds;
    	$this->init();
    }

    function init() {
    	$this->putAll($_REQUEST);
    }
    
    function go() {
		$fileName = $this->getString('page', "home");
    	$action = $this->getString('action', "overview");
    	
    	try {
	    	$package = $this->PAGES.$fileName;
			$phpFile = $package."/".$fileName.".php";
			if(!file_exists($phpFile)) {
				throw new PageNotFoundException("PHP include file niet gevonden: " . $phpFile);
			}
			include_once($phpFile);
	    	
	    	$className = $fileName."Page";
	    	
	    	$page = new $className($this->ds);
	    	
	    	if(!method_exists($className, 'overview')) {
	    		if($fileName != "loginpage") {
		    		throw new Exception('Implement method Overview()!');
	    		}
	    	}
	    	
	    	/* De juiste taal gebruiken */ 
	    	if(isset($_SESSION['_lang_language'])) {
				$this->put("_lang_language", $_SESSION['_lang_language']);
				$this->put("_lang_country", $_SESSION['_lang_country']);
			}
			$this->put("currentPage", $fileName);
			Translator::locale($this);
			
	    	
			if(!method_exists($page, $action)) {
				throw new PageNotFoundException("Pagina (of methode '$action') nicht gefunden, jah!");
	   		}
	   		$annotatedClass = new ReflectionAnnotatedClass($className);
	   		$annotatedMethod = new ReflectionAnnotatedMethod($className, $action);
	   		
	   		$this->checkAuthorization($annotatedClass, $annotatedMethod);
	   						
	   		$errorHandler = new PageErrorHandler($page);
	   		$errorHandler->setErrorPolicy(_DEBUG ? 2 : 1);
	   		
	   		// iets maken van: new PageOperation()
	   		$page->setAction($action);
	   		$page->setController($fileName);
	   		$page->validate();
	   		
			//$page->$action(); // Voer de methode uit
    		$isJSONAnnotated = $annotatedMethod->hasAnnotation("JSON");
			if($isJSONAnnotated) {
				$page->setIsJson(true);
				echo $page->responseJson();	
			} else {
	    		if($annotatedMethod->hasAnnotation("Ajax")) {
	    			$page->setIsAjax(true);
	    			echo $page->getHtml();
	    		}
	    		else {
	    			$doc = new Document();
	    			$page->setDocument($doc);
		    		$head = new Head();
		    		$head->addMetaData("description", "Op zoek naar elkaar! In de buurt, werk, uitgaan, interesses en meer");
					$head->addMetaData("keywords", "emeet, e-meet, terug, vinden, buurt, werk, flirt, uitgaan, bioscoop");
					$head->addMetaData("classification", "emeet, e-meet, terug, vinden, buurt, werk, flirt, uitgaan, bioscoop");
					$head->addMetaData("copyright", "Emeet.nl");
					
					$head->addCssInclude("/css/reset.css");
					$head->addCssInclude("/css/components.css");
					$head->addCssInclude("/css/style.css");
					$head->addCssInclude("/css/jquery.autocomplete.css");
					$head->addCssInclude("/css/jquery.tabs.css");
					$head->addCssInclude("/css/jquery.autocomplete.css");
					$head->addCssInclude("/css/jquery.datepicker.css");
					$head->addCssInclude("/css/jquery.highlight.css");
					
					$head->addJavascriptInclude("/javascript/all.js");
					$head->addJavascriptInclude("/javascript/components/Autocomplete.js");
					$head->addJavascriptInclude("/javascript/components/jquery.tabcomplete.js");
					//$head->addJavascriptInclude("/javascript/main.js.php");
					$head->addJavascriptInclude("/javascript/startup.js");
					$head->addJavascriptInclude("/javascript/onload.js");
					$head->addJavascriptInclude("/javascript/core.js");
		    		
		    		$head->setTitle("Vind je flirt terug! Reflirt.nl");
		    		$doc->add($head);
		    		
					$body = new Body();
					$body->setOnload("init()");
					$body->setOnUnload("bye()");
					
					$doc->add($body);
					
					//$page->setDocument();
					$body->add(new PageComponent($page));
					
					$doc->start();
	    		}
	    		
			}
		}
		catch (UserFriendlyMessageException $e) {
			if(IS_PRODUCTION) {
				$this->logAndForward($e);	
			}
			
			if($isJSONAnnotated) {
				$json = array();
				$error= array();
				$error["message"] = $e->getMessage();			
				$json["fail"] = $error;
				echo json_encode($json);
				exit(1);
			}
			
			$page->put("_message", $e->getMessage());
			$page->response();
			
		}
		catch (UserFriendlyMessage $e) {
			if(IS_PRODUCTION) {
				$this->logAndForward($e);	
			}
			DebugUtils::printException($e);
		}
		catch(ValidationException $e) {
			if($isJSONAnnotated) {
				$json = array();
				$error= array();
				$error["message"] = $e->getMessage();			
				$error["field"] = $e->getField();
				$json["fail"] = $error;
				echo json_encode($json);
				exit(1);
			}
			DebugUtils::printException($e);
		}
		catch(EntityException $e) {
			if(IS_PRODUCTION) {
				$this->logAndForward($e);	
			}
			DebugUtils::printException($e);
		}
		catch (PageNotFoundException $e) {
			if(IS_PRODUCTION) {
				//DebugUtils::debug("IN_PRODUCTION!");
				$this->logAndForward($e);	
			} else {
				DebugUtils::printException($e);
			}
		}
		catch(Exception $e) {
			if(IS_PRODUCTION) {
				$this->logAndForward($e);	
			}
			DebugUtils::printException($e);
		}
    }
    
    private function checkAuthorization($annotatedClass, $annotatedMethod) {
    	if($annotatedClass->hasAnnotation("Login")) {
    		if(!isset($_SESSION['user'])) {
    			//ActionResult::header("noacces", false, "/?page=home&action=overview" );
    			header("Location: /?page=noaccess");
    		}
    	}
    	//Alleen toegang als het een WebAction methode is
		if(!$annotatedMethod->hasAnnotation('WebAction') && !$annotatedMethod->hasAnnotation('Ajax') 
				&& !$annotatedMethod->hasAnnotation('JSON')) {
			throw new Exception('Geen WebAction: '.$annotatedMethod->getName());
		}
		
		$user = @unserialize($_SESSION['user']);
		
		// Allen toegang als je ingelogd bent
			$annLogin = $annotatedClass->getAnnotation("Login");
		
		if(!$annLogin) {
			$annLogin = $annotatedMethod->getAnnotation("Login");
		}
		if($annLogin) {
			if(!$user) {
    			header("Location: /?page=noaccess");
    		}
			$role = $annLogin->role;
			if ($role == "Werknemer" && $user->get("iswerknemer") != "1") {
				header("Location: /?page=noaccess&action=werknemer");						
			}elseif ($role == "Sollicitant" && $user->get("iswerknemer") != "0") {
				header("Location: /?page=noaccess&action=werkzoekenden");						
			}
    	}
    	
    }
    private function logAndForward($e) {
    	ob_start();
    	echo "<pre>";
    	@DebugUtils::debug($e->getMessage());
    	DebugUtils::debug($e);
    	echo "\n\n\n\nPosted parameters: \n\n";
    	DebugUtils::debug($_REQUEST);
    	echo "</pre>";
    	$log = ob_get_contents();
    	
    	ob_end_clean();
    	@mail("pieterfibbe@gmail.com", "Reflirt.nl log", $log);
    	//header("Location: /?page=error&action=error");
    	//header("Status: 404");
    	header("Location: /?page=notfound");
    	
    }
}
?>