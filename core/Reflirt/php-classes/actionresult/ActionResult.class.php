<?php
include_once PHP_CLASS.'io/PropertyParser.class.php';
include_once PHP_CLASS.'internet/Url.class.php';
/**
 * De klasse bepaalt aan de hand van wat voor actie er heeft plaats gevonden
 * welke melding getoond moet worden, INDIEN dit et geval is. Ditword
 * bepaald aan de hand van twee parameters (n, r);
 *
 * Bijvoorbeeld:
 *
 *  Bij een verkeerde login wordt de gebruiker naar de volgende
 * url gestuurd: /?page=home&action=overview&n=login&r=bad
 *
 * In actionresultmessages.props zet je de volgende meldingen:
 *
 * action.login.good. NL = "Het is gelukt"
 * action.login.bad.NL = "Inloggen is lekker niet gelukt"
 *
 */

class ActionResult {

	private static $PARAMETER_ACTION_NAME = "n";
	private static $PARAMETER_ACTION_RESULT = "r";
	private static $PARAMETER_ACTION_RESULT_GOOD = "good";
	private static $PARAMETER_ACTION_RESULT_BAD = "bad";

//	public static function setMessage(DataSource $ds) {
//		global $smarty;
//		$actionName = $ds->getString(self::$PARAMETER_ACTION_NAME);
//		$result = $ds->getString(self::$PARAMETER_ACTION_RESULT);
//		if(empty($actionName) || empty($result)) {
//			return;
//		}
//		$page = $ds->getString("page");
//
//		$message = self::getActionMessage($page, $actionName, $result);
//
//		$smarty->assign("action_result", $result);
//		$smarty->assign("action_message", $message);
//	}

	private static function getActionMessage($page, $actionName, $result) {
		// We doen nog niks met page
		$country = "NL";
		$language = "nl";
		$line = $actionName.".".$result.".".$language;

		$file = PAGES.$page."_".$country."_".$language.".props";
		DebugUtils::debug("message: " .$message);
		
		$message = PropertyParser::getValue($file, $line);
		return "test string";
	}

	public static function header($actionName, $succeeded, $url = null, $params = null) {
		$result = $succeeded ? self::$PARAMETER_ACTION_RESULT_GOOD : self::$PARAMETER_ACTION_RESULT_BAD;
		if($url == null) {
			$url = $_SERVER["REMOTE_ADDR"].$_SERVER["REQUEST_URI"];
		}

		$u = new Url($url);
		$u->addParameter(self::$PARAMETER_ACTION_NAME, $actionName);
		$u->addParameter(self::$PARAMETER_ACTION_RESULT, $result);
		header("Location: ".$u->toString());
	}

	public static function message($actionName, $succeeded) {
		global $smarty;
		$result = $succeeded ? self::$PARAMETER_ACTION_RESULT_GOOD : self::$PARAMETER_ACTION_RESULT_BAD;
		$smarty->assign(self::$PARAMETER_ACTION_NAME, $actionName);
		$smarty->assign(self::$PARAMETER_ACTION_RESULT, $result);

	}


}
?>