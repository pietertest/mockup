<?php

class Body extends HtmlComponent {
	
	private $onload;
	private $onUnload;
	
	/**
	 * 
	 */
	public function htmlStart() {
		echo <<< EOD
			<body onload="$this->onload" onunload="$this->onUnload">
			
EOD;
	
	}

	/**
	 * 
	 */
	public function htmlEnd() {
		echo <<< EOD
</body>

EOD;

	}
	
	public function setOnload($onload) {
		$this->onload = $onload;		
	}
	
	public function setOnUnload($onUnload) {
		$this->onUnload = $onUnload;	
	}
	


}

?>