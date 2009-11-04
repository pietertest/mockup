<form method="post" id="newspotform" >

	<input type="hidden" name="page" value="spot" />
	<input type="hidden" name="action" value="submitnew" />
	<input type="hidden" name="lat" />
	<input type="hidden" name="lng" />
	<input type="hidden" name="cat" value="{$cat}" />
	
	<table border="0" width="100%">
		{include file=spot/forms/addnew.$shortname.tpl}
		<tr>
			<td colspan="2">
				{include file="spot/forms/incl/addspot.js.tpl"}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				
				<input type="hidden" name="lat" value="" />
				<input type="hidden" name="lng" value="" />
				
				<input type="button" onclick="getCoords()" value="Zet op de kaart" />
				<input type="submit" disabled="true" id="submitbutton"`value="Spot opslaan"/>
			</td>
		</tr>
	</table>

{literal}
<script language="JavaScript" type="text/javascript">

$().ready(function() {
	$("#newspotform").change(function() {
		$(":submit", $("#newspotform")).attr("disabled", true);
	});
	$("#cityname").autocomplete("/servlets/autocomplete/city.php", {
		delay: 150,
		width: "260px",
		max: 10,
		formatItem: formatCityItem,
		formatResult: formatResult,
		selectFirst: true,
		mustMatch: true,
		extraParams: {
			countryid: 1
		}
	});
	function formatCityItem(row) {
		return row[0];
	}
	function formatResult(row) {
		return row[0];
	}
	$("#cityname").result(function(event, data, formatted) {
		$("#cityid").val(data[1]);
	});
});

function getCoords() {
	var enteredInfo = formatEnteredInfoHtml();
	var address = getAddress();
	if (geocoder) {
		var p = geocoder.getLatLng(
        	address ,
			function(point) {
				if(!point) {
					$("[name=lat]").val("");
					$("[name=lng]").val("");
					//alert("Er kan geen lokatie gevonden worden met deze gegevens.");
					$("#spotnotfound").show("fast");
					return;	
				} else {
					map.setCenter(point, 13);
					map.clearOverlays();
					var marker = new GMarker(point);
					map.addOverlay(marker);
					marker.openInfoWindowHtml(enteredInfo);
					$("[name=lat]").val(point.lat());
					$("[name=lng]").val(point.lng());
					$(":submit", $("#newspotform")).attr("disabled", false);
				}
			}
		);
	} else {
		//Map not initialised
		alert('Er is iets verkeerd gegegaan. Herlaad de pagina en probeer het nog eens.');
		return null;
	}
}

/*********In volgende release (Addres van google verkregen invullen) **********/

function addAddressToMap(response) {
	map.clearOverlays();
	if (!response || response.Status.code != 200) {
		alert("Er kan geen lokatie gevonden worden met deze gegevens.");
	} else {
		debugger;
		place = response.Placemark[0];
		point = new GLatLng(place.Point.coordinates[1],
		place.Point.coordinates[0]);
		marker = new GMarker(point);
		map.addOverlay(marker);
		marker.openInfoWindowHtml(formatGMapResponse(place));
		$(":submit", $("#newspotform")).attr("disabled", false);
		/*
		map.setCenter(point, 13);
		map.clearOverlays();
		var marker = new GMarker(point);
		map.addOverlay(marker);
		marker.openInfoWindowHtml(enteredInfo);
		$(":submit", $("#newspotform")).attr("disabled", false);
		*/
	}
}

function _getCoords() {
	var enteredInfo = formatEnteredInfoHtml();
	var address = getAddress();
	if (geocoder) {
		var p = geocoder.getLocations(address, addAddressToMap);
	} else {
		alert('Er is iets verkeerd gegegaan. Herlaad de pagina en probeer het nog eens.');
		return null;
	}
}

/*******************************End: In volgende release **********************/

</script>
{/literal}

</form>