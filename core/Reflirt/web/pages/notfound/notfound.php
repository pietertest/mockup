<?php

class NotfoundPage extends Page{

	/** @WebAction */
	public function overview() {
		$this->setHeader("Oeps, pagina niet gevonden!");
	}
}
?>