<script src="{$_googlemaps_script}" type="text/javascript"></script>
<h2>Nieuwe spot toevoegen</h2>

Als je de spot niet kunt vinden die je zoekt dan kun je een nieuwe spot aanmaken.
<br/> 
Selecteer een category: {html_options options=$categories id=category name=cat}

<div id="formdiv" class="formdiv"></div>

{literal}
<script language="JavaScript" type="text/javascript">

var map = null;
var geocoder = null;

$().ready(function() {
	$("#category").change(function() {
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
{/literal}

<div style="width: 100%;background-color: red; padding: 25px;border: 1px solid #E0E0E0; background-color: #F4F4F4">
	<div id="map" class="NewSpotMap" ></div>
</div>  
  
