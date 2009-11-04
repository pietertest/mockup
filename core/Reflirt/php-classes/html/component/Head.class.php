<?php
include_once PHP_CLASS . 'html/component/HtmlComponent.class.php';

class Head extends HtmlComponent {
	
	private $JAVASCRIPT_INCLUDES = array();
	private $CSS = array();
	private $META_DATA = array();
	
	private $title;
	
	public function addJavascriptInclude($path) {
		array_push($this->JAVASCRIPT_INCLUDES, $path);
	}
	
	public function addCssInclude($path) {
		array_push($this->CSS, $path);
	}
	
	public function addMetaData($key, $value) {
		$this->META_DATA[$key] = $value;
	}
	
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function htmlStart() {
		$head = <<< EOD
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					<title>{$this->title}</title>

EOD;
	
		foreach ($this->META_DATA as $key=>$value) {
			$head .= <<< EOD
					<meta name="$key" content="$value" />

EOD;
		}		
		
		foreach ($this->JAVASCRIPT_INCLUDES as $path) {
			$head .= <<< EOD
					<script type="text/javascript" src="$path" ></script>

EOD;
		}
		
		foreach ($this->CSS as $path) {
			$head .= <<< EOD
					<link rel="stylesheet" type="text/css" href="$path" />

EOD;
		}
		
		echo $head;
		
	}
	
	public function htmlEnd() {
		echo <<<EOD
				</head>

EOD;
	}
	
}

?>