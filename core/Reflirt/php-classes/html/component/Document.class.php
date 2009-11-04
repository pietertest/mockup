<?php
include_once PHP_CLASS . 'html/component/HtmlComponent.class.php';

class Document extends HtmlComponent {
	
	public function htmlStart() {
		echo <<< EOD
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">

EOD;
				
	}
	
	public function htmlEnd() {
		echo <<< EOD
			</html>

EOD;
	}
	
	public function start() {
		$this->callDoAfterSetters();
		$this->writeHtml();
	}
	
	function getHead() {
		return $this->getChild("Head");
	}
	
	function getBody() {
		return $this->getChild("Body");
	}

}

?>