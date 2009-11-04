<?php


class Feedback {
	
	private $code;
	private $type;
	private $template;
	
	public static $FEEDBACK_PATH = "feedback";
	
	public static $TYPE_SUCCESS = "good";
	public static $TYPE_FAIL = "bad";
	public static $TYPE_WARNING = 1;
	public static $TYPE_INFO = 1;
	
	/**
	 * $code Parameter die mee wordt gegeven
	 * $type Type melding: warning, error, info, gedefinieerd in stylesheet
	 * $template Template die getoond moet worden: <page>.feedback.<template>.tpl
	 */
	public function __construct($code, $type, $template) {
		$this->setFeedbackCode($code);
		$this->setType($type);
		$this->setTemplate($template);
	}
	
	public function setFeedbackCode($code) {
		$this->code = $code;
	}
	
	public function setType($type) {
		$this->type = $type;		
	}
	
	public function setTemplate($template) {
		$this->template = $template;
	}

	public function getFeedbackCode() {
		return $this->code;
	}

	public function getType() {
		return $this->type;
	}
	
	public function getTemplate() {
		return $this->template;
	}
	
}
?>
