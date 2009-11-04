<?php
include_once PHP_CLASS.'io/BaseFile.class.php';
include_once PHP_CLASS.'javascript/Javascript.class.php';


class DebugUtils {


	static function printException($exception) {
		echo '<a name="top" >';
		echo '<div style="background-color: red; color: white; font-weight: bold; font-size: 18px; padding-left: 10px;">';
		echo "Error";
		echo '</div>';
		DebugUtils::debug("Uncaught exception: ".$exception->getMessage(), 0);
		DebugUtils::debug("At ".DebugUtils::getTraceToLines($exception->getTrace()));
		if(defined('_DEBUG') && _DEBUG) {
			DebugUtils::printSource($exception);
		}
	}

	private static function getTraceToLines($trace) {
		$string = "<pre>";
		$current = 1;
		foreach($trace as $line){
			/** Print niet het hele pad, enkel vanaf de DOCUMENT_ROOT */
			$baseDir =  str_replace('web', '',$_SERVER['DOCUMENT_ROOT']);
			$baseDir = str_replace("\\", "/", $baseDir);
			if(!isset($line['file']) || !isset($line['line']) || !isset($line['class'])) {
				continue;
			}
			$filename = str_replace("\\", "/", strtolower($line['file']));

			$package = str_replace($baseDir, "", strtolower($filename));

			$string .= '#'.$current.' '.$package.'('.$line['line'].'): ';
			$string .= ' <a href="#'.$current.'">'.$line['class'].'.'.$line['function'].'('.DebugUtils::getArgs($line['args']).')</a><br />';
			$current++;
		}
		return $string."</pre>";
	}

	private static function printSource($exception) {
		$aTrace = $exception->getTrace();
		self::printTrace($aTrace);
	}
	
	public static function printTrace($aTrace) {
		$current = 1;
		//$aTrace = array_reverse($aTrace);

		// Voor elke file
		foreach($aTrace as $trace) {
			if(isset($trace['class'])) {
				if ($trace['class'] == "" || $trace['class'] == "Smarty") {
					return;
				}
			}
			$errorFile = $trace['file'];
			$errorLine = $trace['line'];

			$anchor = $current - 1;

			if(!file_exists($errorFile)) {
				return "Tijdens het debuggen wordt geprobeerd de file '$errorFile', te openen, maar die bestaat niet. Debuginfo kan niet worden getoond";
			}
			$origSource = highlight_file($errorFile, true);
			$lines = split('<br />', $origSource);

			$currentline = 1;
			$hightlightSource = "";
			$linenumbers = "";

			// Voor elke regel in de file
			foreach($lines as $line){
				$linenumbers .= '<code>'.$currentline.'</code><br/>';

				if($currentline == $errorLine){
					$hightlightSource .= '<a name="'.$current.'" >';
					$hightlightSource .='<div style="background-color: yellow; cursor: pointer;" onclick="window.location.href=\'#'.$anchor.'\'">';
					$hightlightSource .= $line;
					$hightlightSource .= "</div>";

				} else {
					$hightlightSource .= $line."<br />";
				}

				$currentline++;

			}
			$classSource = '<code><table cellspacing="0" cellpadding="0" border="0">';
			$classSource .= '<tr><td style="background-color: #EEF1FB; padding-right: 10px; text-align: right; border-right: 1px solid #BDC9F0;">'.$linenumbers.'</td>';
			$classSource .= '<td>'.$hightlightSource.'</td></tr>';
			$classSource .= '</table></code>';

			$current++;
			if(isset($trace['class'])) {
			echo '<div style="background-color: #4263D2; color: white; font-weight: bold; font-size: 18px; padding-left: 10px;">';
				echo $trace['class'];
			echo '</div>';
			}
			DebugUtils::debug(" ");
			echo $classSource;
			DebugUtils::debug(" ");
		}
	}

	private static function getArgs($args) {
		$string = "";
		$counter = 0;
		foreach($args as $arg) {
			if($counter != 0) {
				$string .= ", ";
			}
			//TODO: Krijgt een futmelding als je een DataSource print ofzo..?
			if(!$arg instanceof DataSource && !$arg instanceof Entity && !$arg instanceof ObjectQuery) {
				$string .= $arg;
			}
		}
		return $string;
	}
	
	/**
	 * Debug info printen in duidelijk leesbare vorm
	 * $what: te debuggen info
	 * $alert: 1 = javascript alert, 0 = niet :)
	 */
	static function debug($what, $alert=0, $comments = ""){
		if($what instanceof DataSource) {
			$what = $what->getFields();
		}
		if($alert){
			$message = "";
			$message .= "alert('".$comments;
			if(is_array($what)){
				$message .= Utils::parse_line($what);
			}
			else{
				$message .=  addslashes($what);
			}
			$message .=  "')";
			Javascript::addOnload($message);
			echo $message;
			return;
		}
		else {
			echo "<pre>";
			echo $comments;
			print_r($what);
			echo "</pre>";
		}
	}
	
	function parse_line($array){
	   $line = "";
	   foreach($array AS $key => $value){
	       if(is_array($value)){
	           $value = "(". Utils::parse_line($value) . ")";
	       }
	       else
	       {
	           $value = urlencode($value);
	       }
	       $line = $line . urlencode($key) . ":" . $value . '\n';
	   }
	   return $line;
	}
	
}

?>