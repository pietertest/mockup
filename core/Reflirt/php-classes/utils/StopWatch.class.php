<?php

class StopWatch {
	private $name;
	private $start;
	private $end;
    function StopWatch($name) {
    	$this->name = $name;
    }
    
    public function start() {
    	$starttime = microtime();
		$startarray = explode(" ", $starttime);
		$starttime = $startarray[1] + $startarray[0];
		$this->start = $starttime;	
    }
    
    public function end() {
    	$endtime = microtime();
		$endarray = explode(" ", $endtime);
		$endtime = $endarray[1] + $endarray[0];
		$totaltime = $endtime - $this->start;
		$totaltime = round($totaltime,3);
		return "$this->name took: $totaltime ms";
    }
}
?>