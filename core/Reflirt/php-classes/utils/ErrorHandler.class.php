<?php

class ErrorHandler {
	private $errno;
	private $errmsg;
	private $filename;
	private $linenum;
	private $vars;
	
    function ErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
		$this->errno = $errno;
		$this->errmsg = $errmsg;
		$this->filename = $filename;
		$this->linenum = $linenum;
		$this->vars = $vars;
    }
    
    function toString() {
	    // timestamp for the error entry
	    $dt = date("Y-m-d H:i:s (T)");
	
	    // define an assoc array of error string
	    // in reality the only entries we should
	    // consider are E_WARNING, E_NOTICE, E_USER_ERROR,
	    // E_USER_WARNING and E_USER_NOTICE
	    $errortype = array (
	                E_ERROR              => 'Error',
	                E_WARNING            => 'Warning',
	                E_PARSE              => 'Parsing Error',
	                E_NOTICE             => 'Notice',
	                E_CORE_ERROR         => 'Core Error',
	                E_CORE_WARNING       => 'Core Warning',
	                E_COMPILE_ERROR      => 'Compile Error',
	                E_COMPILE_WARNING    => 'Compile Warning',
	                E_USER_ERROR         => 'User Error',
	                E_USER_WARNING       => 'User Warning',
	                E_USER_NOTICE        => 'User Notice',
	                E_STRICT             => 'Runtime Notice',
	                'E_RECOVERABLE_ERROR'  => 'Catchable Fatal Error'
	                );
	    // set of errors for which a var trace will be saved
	    $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
	   
	    $err = "<errorentry>\n";
	    $err .= "\t<datetime>" . $dt . "</datetime>\n";
	    $err .= "\t<errornum>" . $this->errno . "</errornum>\n";
	    $err .= "\t<errortype>" . $errortype[$this->errno] . "</errortype>\n";
	    $err .= "\t<errormsg>" . $this->errmsg . "</errormsg>\n";
	    $err .= "\t<scriptname>" . $this->filename . "</scriptname>\n";
	    $err .= "\t<scriptlinenum>" . $this->linenum . "</scriptlinenum>\n";
	
	    if (in_array($this->errno, $user_errors)) {
	        $err .= "\t<vartrace>" . wddx_serialize_value($this->vars, "Variables") . "</vartrace>\n";
	    }
	    $err .= "</errorentry>\n\n";
	   
	    // for testing
	    // echo $err;
	
	    // save to the error log, and e-mail me if there is a critical user error
	    error_log($err, 3, "c:/error.log");
	    /*if ($errno == E_USER_ERROR) {
	        mail("pieterfibbe@gmail.com", "Critical User Error", $err);
	    }*/
	    return $err;
	}
    
}
?>