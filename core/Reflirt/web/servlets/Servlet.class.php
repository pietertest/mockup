<?php
//include("../../phpincl/init_smarty_config.php");
include_once PHP_CLASS.'core/DataSource.class.php';

class Servlet extends DataSource{

    function __construct(DataSource $ds) {
    	foreach($ds->getAll() as $key=>$value) {
    		$ds->put($key, addslashes($value));
    	}
    	$this->putAll($ds);
    	$this->validate();
    }
    
    public function go() {
    	$class = get_class($this);
    	$method = $this->getString("action", "overview");
    	if(method_exists($class, $method)) {
    		echo $this->$method();
    	} else {
    		//header("Erro 404");
    		DebugUtils::debug("Pagina bestaat niet!");
    	}
    }
    
    public function validate(){}
     
}


?>