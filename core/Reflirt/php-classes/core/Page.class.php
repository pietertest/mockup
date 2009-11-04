<?php
include_once(PHP_CLASS."entities/mylocation/MyLocationUtils.class.php");
include_once(PHP_CLASS."googlemaps/GoogleMapsUtils.class.php");
include_once(PHP_CLASS."exception/IllegalStateException.class.php");
include_once(PHP_CLASS."core/Feedback.class.php");
include_once(PHP_CLASS."entities/user/UserFactory.class.php");

include_once(PHP_CLASS."html/component/Document.class.php");
include_once(PHP_CLASS."html/component/Head.class.php");
include_once(PHP_CLASS."html/component/Body.class.php");


/**
 * Elke webpagina is een Page.
 */
abstract class Page extends DataSource {
	
	public static $SESSION_TIMEOUT = 1800; // 30 minuten
	
	private $ROOT_TEMPLATE = "main.tpl";

	public $user = null;
	public $feedbacks = array();
	
	private $RESPONSE_CODE_PARAM = "r";

	// If true, main.tpl won't be wrapped around it
	private $isAjax = false; 
	private $isJson = false; 
	
	private $controller = null; 
	private $action = null; 
	private $template = null; 
	
	private $document = null; 
	
	function __construct(DataSource $ds) {
		$this->putAll($ds);
		$this->initUser();
		$this->initFeedbacks();
		$this->init();
	}
	
	protected function init(){}
	
	final function setHeader($title) {
		$this->put("_title", $title);
	}
	
	function fail($message) {
		$this->put("_status", "bad");
		$this->put("_message", $message);
	}
	
	protected function success($message) {
		$this->put("_status", "ok");
		$this->put("_message", $message);
	}
	
	function warn($message) {
		$this->put("_status", "warn");
		$this->put("_message", $message);
	}
	
	function info($message) {
		$this->put("_status", "info");
		$this->put("_message", $message);
	}
	
	protected function initFeedbacks() {}
	
	public function setIsAjax($ajax) {
		$this->isAjax = $ajax;
	}
	
	public function setIsJson($isJson) {
		$this->isJson = $isJson;
	}
	
	function initUser() {
		if(isset($_SESSION['user'])) {
			//$user1 = UserFactory::getUserByLogin("pieter");
			$user = unserialize($_SESSION["user"]);
			//$user = UserFactory::getUserByLogin("pieter");
			$user->put("lastaction", DateUtils::now());
			$user->save();
			Utils::assertTrue("user == null!", $user!=null && $user->getKey() != -1);
			$this->user = $user;
			$this->put("sessionuser", $user);
		}
	}
	
	public final function forwardSuccess($id = null) {
		$url = $this->getSuccessUrl();
		if ($id) {
			$url->addPrarameter("id", $id);
		}
		header("Location: ".$url->toString());
	}
	
	private function getAction() {
		return $this->action;
	}
	
	private function getController() {
		return $this->controller;
	}
	
	/**
	 * Voor het forwarden naar een pagina na een save actie zodat er niet 2
	 * keer gesaved kan worden. 
	 * 
	 * @param action Non-/fullyqualified name bijv. "view", pakt dan de huidige
	 * pagina of "agenda.view" 
	 */
	public function forward($action, $id, $responseCode = null) {
		$page = $this->getController();
		$ding =  explode(".", $action);
		if (count($ding) > 2) {
			throw new PageNotFoundException("[E] forward url must be in the " .
					"form of page.action, caonnot containt more than 1 dot (.)");
		} elseif (count($ding) == 2) {
			$page = $ding[0];
			$action = $ding[1];
		}
		$url = new Url("/");
		$url->addParameter("page", $page);
		$url->addParameter("action", $action);
		$url->addParameter("id", $id);
		if ($responseCode) {
			$url->addParameter($this->RESPONSE_CODE_PARAM, $responseCode);
		}
		header("Location: ".$url->toString());
	}
	
	public function addFeedback(Feedback $feedback) {
		$index = $feedback->getFeedbackCode();
		if (isset($this->feedbacks[$index])) {
			throw new IllegalStateException("Cannot add feedback with code ".
				$index." again in page ".$this->getController());
		}
		$this->feedbacks[$index] = $feedback;
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function setAction($action) {
		$this->action = $action;
		$this->setTemplate($action); 
	}
	
	public function setController($controller) {
		$this->controller = $controller;
	}
	
	public function getUrl(){
		$url = new Url("/");
		$url->addParameter("page", $this->getController());
		$url->addParameter("action", $this->getAction());
		return $url;
	}
	
	/**
	 * Voor JSON annotated methodes
	 */
	public function responseJson() {
		$json = $this->executeAction();
		if ($json == null) {
			$json = array();
		}
		$json["success"] = true;
		$json["successUrl"] = $this->getSuccessUrl();
		return json_encode($json);
	}
	
	private function getSuccessUrl() {
		$url = $this->getUrl();
		$url->addParameter("action", $this->getAction() . "Success");
		return $url;
	}
	
	/**
	 * Het template laden. Dit mapt op de action. Als de url is:
	 *
	 * 			www.website.com/page=profiel&action=save
	 *
	 * dan wordt de methode 'save' op de klasse Profiel aangeroepen. Vervolgens
	 * wordt de template 'profiel.save.tpl' geladen. De save methode zorgt voor 
	 * de benodigde data.
	 *
	 */
	function getHtml() {
		$this->executeAction();

		$fileName = $this->getController();
    	$action = $this->getAction();
    	
		$path = $fileName.DIRECTORY_SEPARATOR;
		
		
		$this->doBeforeRender();
		if($this->isAjax) {
			$fileName .= ".ajax";  
		}
		
		if(empty($this->template)) {
			
		}

		if(empty($action)) {
			$template = $path.$fileName.'.overview.tpl';
		} else {
			$template = $path.$fileName.'.'.$this->template.'.tpl';
		}
		
		$this->initFeedbackFile($fileName);
		return $this->render(strtolower($template));
    }
    
    private function getTemplate() {
    	return $this->template;
    }
    
    private function executeAction() {
    	$method = $this->action;
    	$meth = array(&$this, $method);
    	return call_user_func($meth);
    }
    
    /**
     * Aangezien je bij ongeveer elke actie een check moet doen of er een id
     * meegepost is kun je hier checken of het goed is meegepost.
     * 
     * @parm throwError Indien true gooit hij een PageNotFoundException
     */
    protected function checkValidId($param, $throwError = true) {
    	$id = $this->getInt($param, -1);
    	if ($id == -1) {
    		if ($throwError) {
    			throw new PageNotFoundException("Pagina niet gevonden");
    		}
    	}
    	return $id;
    }
       
    private function doBeforeRender() {
    	$this->put("js", Javascript::getJS());
    	//TODO: kan dit weg?
    	@$this->put("_currentpage", $_SERVER['HTTP_REFERER']);
    	$this->assignSmartyVars();
    }
    
    private function assignSmartyVars() {
    	$this->put("_googlemaps_script", GoogleMapsUtils::getScriptUrl());
    }
    
    /** @WebAction */
    public function locale() {
		$_SESSION['_lang_language'] = $this->getString("l");
		$_SESSION['_lang_country'] = $this->getString("c");
		$url = new Url($_SERVER['HTTP_REFERER']);
		if($url->getString("action") == "locale") { // Anders gaat ie loopen
			DebugUtils::debug("muhahaha");
		} else {
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
		}
    }
    
    /**
	 * Voor i18n met Smarty
	 */
	function L($message, $params=null){ 
		global $smarty;
		require_once $smarty->_get_plugin_filepath('block','t');
		return smarty_block_t($params, $message, $smarty);
	}
    
    
    /**
     * Als je een ander template wilt (her)gebruiken. Met name te gebruiken 
     * wanneer je iets wilt posten en bij een fout terug op die pagina wilt zijn.
     * Bijvoorbeeld Aanmelden. Je hebt dan methode overview waarin je nog geen
     * validatie wilt doen. Heb dan een methode register waarnaar je het form
     * submit. Dan kun je doen setTemplate('overview') als je een fout wilt tonen
     * bijv. "Gebruikersnaam bestaat al" op dezelfde pagina.
     * 
     * Je hoeft op deze manier geen extra template aan te maken en ook niet te 
     * checken in de overview methode of er een form wordt gepost.
     */
    public function setTemplate($template) {
    	$this->template = $template;	
    }
    
    function setTitle($title) {
    	$this->getDocument()->getHead()->setTitle($title);
    }
    
    function setDescription($description) {
    	$this->getDocument()->getHead()->addMetaData("description", $description);
    }
    
    private function getDocument() {
    	$this->assureIsHtmlPage();
    	return $this->document;
    } 

    public function setDocument(Document $doc) {
    	$this->document = $doc;
    } 
    
    
    private function assureIsHtmlPage() {
    	Utils::assertTrue("Operation not allowed on Ajax/Json pages", 
    		!$this->isAjax && !$this->isJson);
    }
    
    
    /**
     * Het roottemplate setten die de hele pagina omvat (default is 'main.tpl').
     * Te gebruiken bij pagina's die ge-include worden, om met AJAX een 
     * frame effect te verkrijgen.
     */
    public function setRootTemplate($template) {
    	$this->rootTemplate = $template;	
    }
    
    /**
     * Deze methode overriden indien een validatie gedaan moet worden.
     */
    function validate() {}
    
    private function initFeedbackFile($page) {
    	global $smarty;
    	
    	$responseCode = $this->getInt($this->RESPONSE_CODE_PARAM, -1);
    	if ($responseCode == -1) {
    		return;
    	}
    	@$feedback = $this->feedbacks[$responseCode];
    	if(!$feedback) {
    		return;
    	}
    	
    	$feedbackPath = $page.DIRECTORY_SEPARATOR.Feedback::$FEEDBACK_PATH.DIRECTORY_SEPARATOR;
    	$file = $page.".feedback.".$feedback->getTemplate().".tpl";
		$smarty->assign("feedbackfile", $feedbackPath.$file);
		$smarty->assign("feedbackType", $feedback->getType());
    }

    function render($template) {
    	global $smarty;
    	foreach($this->fields as $key=>$value) {
    		$smarty->assign($key, $value);
	    }
    	$this->assignUtilsClasses($smarty);
    	if($this->isAjax) {
    		return $smarty->fetch($template);
    	}
    		
   		$smarty->assign('template', $template);
   		return $smarty->fetch($this->ROOT_TEMPLATE);
    }
    
    private function assignUtilsClasses(Smarty $smarty) {
    	$smarty->assign("locationutils", new MyLocationUtils);
    }
    
}

?>
