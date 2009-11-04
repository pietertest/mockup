<?php
include_once PHP_CLASS.'core/DataSource.class.php';

class Url extends DataSource {
	private $url = "";

    function Url($url = null) {
    	if($url) {
    		$this->setUrl($url);
    	}
    }

    private function parse() {
    	$parts = explode("?", $this->url);
    	if(count($parts) < 2 ) {
    		return;
    	}
    	$firstpart = $parts[0];
    	$querypart = $parts[1];

    	$params = explode("&", $querypart);
    	foreach($params as $param) {
    		$a = explode("=",$param);
    		$key = "";
    		$value = "";
    		if(count($a) > 0) {
    			$key = $a[0];
    		}
    		if(count($a) > 1) {
				$value = $a[1];
    		}
    		$this->put($key, $value);
    	}
    }

    function setUrl($url) {
		$this->url = $url;
		$this->parse();
    }

    public function addParameter($key, $value) {
    	$this->put($key, $value);
    }

    public function getFirstPart() {
    	$this->parse();
    	$u = explode("?", $this->url);
    	return $u[0];
    }

    public function getQueryPart() {
    	$querypart = "";
    	$first = true;
    	foreach($this->fields as $key=>$value) {
    		if($first){
    			$querypart .= $key."=".$value;
    			$first = false;
    			continue;
    		}
    		$querypart .= "&$key=$value";
    	}
    	return $querypart;
    }

    public function toString() {
//		$url = "http://";
		$url = "";
		$url .= $this->getFirstPart();
		$querypart = $this->getQueryPart();
		if(!empty($querypart)) {
			$url .= "?".$querypart;
		}
		return $url;
    }
}
?>