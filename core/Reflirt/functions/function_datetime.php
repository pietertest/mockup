<?php

/*@version $Id*/

if(!class_exists('DateTime')){
	
	class DateTime {
		private $DAY 	= 2;
		private $MONTH 	= 1;
		private $YEAR 	= 0;
		private $HOUR 	= 3;
		private $MINUTE = 4;
		private $SECOND	= 5;
		private $datetime;
		
		public function __construct($datetime = null) {
			$this->datetime = $datetime;
		}
		
		public function format($format = "Y-m-d H:i:s") {
		$date = $this->discoverDate();
		$d = date($format, 
			mktime($date[$this->HOUR],$date[$this->MINUTE],$date[$this->SECOND], 
				$date[$this->MONTH], $date[$this->DAY], $date[$this->YEAR]));
		return $d;
		}
		
		/**
		 * Een datum kan op 2 manier aangeleverd worde; 1981/10/19 of 19/10/1981
		 */ 
		private function discoverDate() {
			$date = split("[-/ ]", $this->datetime);
			if(strlen($date[$this->YEAR]) == 4) { // Engelse notiate: 1981/10/19
				return $date;
			}
			DebugUtils::debug($date);
			// 'Nederlandse' notatie
			$d = array();
			$d[$this->DAY] 		= $date[0]; 
			$d[$this->MONTH] 	= $date[1]; 
			$d[$this->YEAR] 	= $date[2];
			
			 
			if(isset($date[3])) {
				$time = split("[:]", $date[3]);
			} else {
				$time = array(0,0,0);
			}
			$d[$this->HOUR] 	= $time[0];
			$d[$this->MINUTE] 	= $time[1];
			$d[$this->SECOND] 	= $time[2];
			 
			return $d; 
		}
	}
	
}

?>
