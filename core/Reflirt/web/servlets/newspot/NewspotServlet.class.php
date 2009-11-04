<?php
include_once(SERVLETS_DIR.'Servlet.class.php');
include_once PHP_CLASS.'entities/user/UserFactory.class.php';
include_once PHP_CLASS.'entities/db/EntityFactory.class.php';
include_once PHP_CLASS.'entities/spot/MySpot.class.php';
include_once PHP_CLASS.'searchers/ACSearcher.class.php';
include_once PHP_CLASS.'searchers/ObjectQuery.class.php';
include_once PHP_CLASS.'searchers/SearchObject.class.php';
include_once PHP_CLASS.'entities/oproep/OproepFactory.class.php';

class NewspotServlet extends Servlet{
	
	public function __construct(DataSource $ds) {
		parent::__construct($ds);
	}
	
	public function newspot() {
//		$cat = $this->get("cat");
//		$oproep = OproepFactory::getOproep($cat);
//		return $oproep->getAddSpotHtml();
		$categories = Spot::$CATEGORIES; 
		$html = <<<EOD
		
		
		<script src="http://maps.google.com/intl/en_ALL/mapfiles/151e/maps2.api/main.js" type="text/javascript"></script>
<h2>Nieuwe spot toevoegen</h2>

Als je de spot niet kunt vinden die je zoekt dan kun je een nieuwe spot aanmaken.
<br/> 
Selecteer een category: <select id="dd" name="cat">
<option value="0" label="Selecteer...">Selecteer...</option>
<option value="1" label="Buiten">Buiten</option>
<option value="2" label="Cultureel">Cultureel</option>
<option value="3" label="Disco/Kroeg">Disco/Kroeg</option>
<option value="4" label="Eetgelegenheid">Eetgelegenheid</option>
<option value="5" label="Evenement">Evenement</option>
<option value="9" label="Trein (OV)">Trein (OV)</option>
<option value="10" label="(Thema) Park">(Thema) Park</option>
<option value="11" label="Recreatie">Recreatie</option>
<option value="12" label="School">School</option>
<option value="13" label="Werk">Werk</option>
<option value="14" label="Winkel">Winkel</option>
<option value="15" label="Woonomgeving">Woonomgeving</option>
<option value="16" label="Hotel/Hostel">Hotel/Hostel</option>
</select>

<div id="formdiv" class="formdiv"></div>


<script language="JavaScript" type="text/javascript">

var map = null;
var geocoder = null;

$().ready(function() {
	alert(1);	
$("#dd").change(function() {
		$.get("/?page=spot&action=getform", 
		{ 
			cat: $(this).val()
		},
		function(data) {
			$("#formdiv").html(data);
		});
	});
	initMap();
}); 

function initMap() {
	map = new GMap2(document.getElementById("map"));
	var point = new GLatLng(52.373812, 4.890951);
	map.setCenter(point, 12);
	geocoder = new GClientGeocoder();
}

</script>

<div style="width: 100%;background-color: red; padding: 25px;border: 1px solid #E0E0E0; background-color: #F4F4F4">
	<div id="map" class="NewSpotMap" ></div>
</div>  
  
EOD;
	return $html;
	}
}
?>