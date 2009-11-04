<?php

abstract class HtmlComponent {
	
	private $children = array();
	private $parent;
	
	public function add(HtmlComponent $component) {
		$component->setParent($this);

		$this->children[] = $component;
	}
	
	public function writeHtml() {
		$this->doBeforeHtmlWrite();
		
		$this->htmlStart();
		foreach ($this->children as $child) {
			$child->writeHtml();
		}
		$this->htmlEnd();
	}
	
	final function callDoAfterSetters(){
		foreach ($this->getChildren() as $child) {
			$child->doAfterSetters();
			$child->callDoAfterSetters();
		}
	}
	
	public function doAfterSetters() {
		// Override when neccessary
	}
	
	function doBeforeHtmlWrite() {		
		// Override when neccessary
	}
	
	function getChildren() {
		return $this->children;
	}
	
	function setParent(HtmlComponent $component) {
		$this->parent = $component;
	}
	
	function getDocument() {
		return $this->getParent("Document");
	}
	
	public function getParent($name = null) {
		if ($name == null) {
			return $this->parent;
		}
		$parent = $this->parent;
		while ($parent != null) {
			if (get_class($parent) == $name) {
				return $parent;
			}
			$parent = $parent->getParent();	
		}
		return null;
	}
	
	function getChild($name) {
		Utils::assertNotNull("Child == null", $name);
		foreach ($this->children as $child) {
			if (get_class($child) == $name ) {
				return $child;
			}
		}
		return null;
	}
	
	function getChildRecursive($name) {
		Utils::assertNotNull("Child == null", $name);
		foreach ($this->children as $child) {
			if ($child->getChildRecurive($name) != null ) {
				return $child->getChildRecurive($name);
			}
			if (get_class($child) == $name ) {
				return $child;
			}
		}
		return null;
	}
	
	abstract public function htmlStart();
	abstract public function htmlEnd();

}

?>