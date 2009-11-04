<?php
include_once(SERVLETS_DIR.'Servlet.class.php');
include_once PHP_CLASS.'entities/mylocation/MyLocationFactory.class.php';
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';

class AutocompleteServlet extends Servlet{
	
	public function __construct(DataSource $ds) {
		parent::__construct($ds);
	}
	
	public function catsearch() {
		$cat = $this->getString("cat");	
		$q = $this->getString("q");
		if(empty($cat) || empty($q)) {
			exit();
		}
		$type = MyLocationFactory::newInstance($cat);
		$ds = new DataSource();
		$ds->putAll($this);
		return $type->getAutocompletionData($ds);
	}
	
	public function spot() {
		$q = isset($_GET["q"]) ? DBUtils::dbEscape(strtolower($_GET["q"])) : null;
		$id = isset($_GET["id"]) ? DBUtils::dbEscape($_GET["id"]) : null;
		$format = isset($_GET["format"]) ? $_GET["format"] : "autocomplete";
		
		if (!$q && !$id) return;
		
		$cat = $this->get('cat');
		
		
		$results = "";
		
		if ($id) {
			EntityFactory::loadEntity(new Spot, UserFactory::getSystemUser(), $id);
			$query = "SELECT city.*, spot.* FROM spot JOIN city on cityid = city.systemid WHERE spot.systemid = $id LIMIT 1";
			$results = self::formatSpotResults($query);
		} else {
			$extra = "";
			// Plaats
			if(isset($_GET["cityid"])) {
				if(!empty($_GET["cityid"])){
					$extra .= " AND cityid = ".$_GET["cityid"];		
				}
			}
			
			// Land
			if(isset($_GET["cicountryid"])) {
				$countryid = $_GET["cicountryid"];
				if(!empty($countryid) && $countryid != "-1") {
					$extra .= " AND cicountryid = ".$_GET["cicountryid"];
				}
			}
			
			// Category
			if(!empty($cat)) {
				$extra .= " AND category = ".$cat;
			}
			$query = "SELECT city.*, spot.* FROM spot JOIN city on cityid = city.systemid WHERE spot.name LIKE '$q%' $extra LIMIT 0,10";
			$results = self::formatSpotResults($query);
			
			$allowNew = $this->get("allowNew") == "true";
			if($allowNew) {
				// Uitzetten, gewoon een popup met de vraag tonen
				//$results .= "Anders...|_new";
			}
		}
		
		return $results;
	}
	
	private static final function formatSpotResults($query, $format = "autocomplete") {
		include_once("db.php");
		$rs = mysql_query($query) or die(mysql_error());
		mysql_close($link);
		if($rs == null) {
			return "";	
		}
		$result = "";
		
		while($spot = mysql_fetch_array($rs, MYSQL_ASSOC)) {
			$disconame = $spot['name'];
			$discoid = $spot['systemid'];
			$city = $spot['cicityname'];
			$cityid = $spot['cityid'];
			$countryid = $spot['cicountryid'];
			//if (strpos(strtolower($disconame), $q) !== false) {
				$result .= "$disconame|$discoid|$city|$cityid|$countryid\n";
			//}
		}
		return $result; 
	}
	
	public function city() {
		$q = isset($_GET["q"]) ? DBUtils::dbEscape(strtolower($_GET["q"])) : null;
		$id = isset($_GET["id"]) ? DBUtils::dbEscape($_GET["id"]) : null;
		if (!$q && !$id) return;
		
		
		$query = "";
		
		if ($id) {
			
			// Als de id parameter meegegeven is dan op systemid zoeken
			$pq = new PreparedQuery("reflirt_nieuw");
			$query = "SELECT *, city.systemid AS cityid FROM city JOIN country on city.cicountryid=country.systemid WHERE city.systemid = $id limit 1";
		} else {
			
			// Anders zoeken aan de hand van de parameters
			$extra = "";
			if(isset($_GET["cicountryid"])) {
				$countryid = $_GET["cicountryid"];
				if(!empty($countryid) && $countryid != "-1") {
					$extra .= " AND cicountryid = ".$_GET["cicountryid"];
				}
			}
			$pq = new PreparedQuery("reflirt_nieuw");
			$query = "SELECT *, city.systemid AS cityid FROM city JOIN country on city.cicountryid=country.systemid WHERE cicityname != '(Onbekend)' AND cicityname LIKE '".$q."%' $extra LIMIT 0,10";	
		}
		
		//echo $query;
		$pq->setQuery($query);
		$cities = $pq->execute();
		
		if($cities == null) return;
		
		$result = "";
		
		foreach ($cities as $city=>$value) {
			$countryname = $value['cocountryname'];
			$countryid = $value['cicountryid']; 
			$cityname = $value['cicityname'];
			$cityid = $value['cityid'];
			//if (strpos(strtolower($cityname), $q) !== false) {
		//		echo json_encode(array(
		//			"cityname" 		=> $cityname,
		//			"cityid"		=> $cityid,
		//			"countryname"	=> $countryname,
		//			"countryid"		=> $countryid
		//		));
				$result .= "$cityname|$cityid|$countryname|$countryid\n";
			//}
		}
		return $result;
	}
	
	
}
?>