<?php
include_once(SERVLETS_DIR.'Servlet.class.php');
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';

class PhotosServlet extends Servlet{
	
	public function __construct(DataSource $ds) {
		parent::__construct($ds);
	}
	
	public function byuser() {
		$limitNr = $this->getInt("limit", -1);
		$limit = "";
		if($limitNr != -1) {
				$limit = " LIMIT ".$limitNr;
		}
		$query = "SELECT *, systemid AS id FROM photo where user = '".$this->getString("userid")."' ".$limit;
		$pq = new PreparedQuery("reflirt_nieuw");
		$pq->setQuery($query);
		$html = file_get_contents(SERVLETS_TEMPLATE_DIR."photos/photo.tpl");
		$a = array();
		$a["html"] = $html;
		$a["items"] = $pq->execute();
		echo json_encode($a);
	}
	
	/**
	 * @param buffer Aantal volgende op te halen url om te bufferen
	 * @param userid
	 * @param photoid Vanaf welke photoid moet gehaal worden. -1 Geeft de eerste
	 * @param direction Fotos hoger of lager dan @param photoid
	 * @param userid
	 */ 
	public function next() {
		$photoId = $this->getString("photoid");
		$photoId = empty($photoId) ? 0 : $photoId;
		$buffer = $this->getString("buffer");
		$query = "SELECT systemid AS id, filename FROM photo WHERE user = '".$this->getString("userid")."'  AND photo.systemid > ".$photoId." ORDER BY photo.systemid  LIMIT ".$buffer;
		$pq = new PreparedQuery("reflirt_nieuw");
		$pq->setQuery($query);

		$images = $pq->execute();
		$a = array();
		if($images[0] != null) {
			$a["id"] = $images[0]['id'];
			$a["filename"] = $images[0]['filename'];
			unset($images[0]);
		}
		if (count($images) > 0) {
			$a["next"] = array_shift($images);
		} else {
			$a["next"] = null;
		}
		echo json_encode($a);
	}
}
?>