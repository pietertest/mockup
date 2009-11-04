<?php

/**
 * Wrapper klasse voor Page.class zodat we die in een HtmlComponent kunnen gebruiken
 * @author Pieter
 *
 */

class PageComponent extends HtmlComponent {
	
	private $html;
	
	public function __construct(Page $page) {
		$this->page = $page;
	}
	
	public function doAfterSetters() {
		$this->html = $this->page->getHtml();
	}
	/**
	 * 
	 */
	public function htmlStart() {
		echo "<!-- Page start-->";
		echo $this->html;
	}

	/**
	 * 
	 */
	public function htmlEnd() {
		echo "<!-- Page end -->";
	}


}

?>