<?php
include_once(SERVLETS_DIR.'Servlet.class.php');
include_once PHP_CLASS.'io/JSLogger.class.php';


class LogServlet extends Servlet{
	
	/**
	 * @WebAction
	 */
	public function overview() {
		if(IS_PRODUCTION) {
			return;
		}
		
		$message = $this->get("message");
		JSLogger::errorAndMail($message);
	}
	
}

?>