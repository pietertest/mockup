
<div id="spots_pictures" class="SpotSearchPictures" ><img src="/images/global/loading.gif" /></div>

<div class="clear"></div>	
<span class="MapButton" id="mapbutton" >Kaart</span>
<span class="pointer" id="spotPointer">
	<img src="/images/global/marker_spot.png" width="10"/>
		<a id="spotname" nohref class="MapSpotName" > 
	</a>&nbsp;
</span>
<a id="zoomall" class="small" nohref>Zoom iederen</a>	<a id="zoominclspot" nohref class="small">(incl. spot)</a>

<div class="mapcontainer" >
		<div class="showmaplabel" style="display: none;"> Kaart tonen</div>
		<div id="peoplemap" class="SpotMap"></div>
</div>


{literal}<script>

var peoplemap;
var aPeopleLocations = new Array();
var spotMarker;

$(document).ready(function() {
	
	$.getJSON("/servlets/?servlet=spots&action=people&id={/literal}{$id}{literal}",
       function(data) {
       	fillSpotsResults(data);
   	});
   	$("#zoomall").click(function() {zoomAll(peoplemap, aPeopleLocations);});
   	$("#zoominclspot").click(zoomInclSpot);
   	$("a").css({cursor: "pointer"});
   	
   	$("#mapbutton").click(toggleMap);
   	$(".showmaplabel").click(toggleMap);   			
});

function toggleMap() {
	$("#peoplemap").toggle();
	$(".showmaplabel").toggle();
}

function zoomInclSpot() {
	var spots = aPeopleLocations.slice();
	spots[spots.length] = new GLatLng(spotLat, spotLon);
	zoomAll(peoplemap, spots);
}

function fillSpotsResults(data){
	$("#spots_pictures").empty();
	var template = data.template;
	var infohtmltemplate = data.infohtml;
	$("#spotname").text(spotName).parent().click(function() {
		//moveMapToPoint(peoplemap, new GLatLng(spotLat, spotLon));
		showInfoHTML();
	});
	initPeopleMap();
	$.each(data.items, function(i,item){
		var point = new GLatLng(item.lat, item.lng);
		var onmap = false;
		var nick_class_addition = "";
		if (isValidPoint(point)) {
			onmap = true;
			nick_class_addition = "onmap";
		}		
		var html = mergeJSON(template,
			{
				photoid: item.photo,
				username: item.user,
				nick_class_addition: nick_class_addition
			}
		);
		var jHtml = $(html).appendTo("#spots_pictures");
		if(!peoplemap) {
			return;
		}
		if (!onmap) {
			return;
		}
		
		aPeopleLocations[aPeopleLocations.length] = point;
		var point = new GLatLng(item.lat, item.lng);
		var marker = new GMarker(point);
		var showInfoWindow = function() {
			var infohtml = mergeJSON(infohtmltemplate ,
				{
					username: item.user,
					photoid: item.photo
				});
			marker.openInfoWindowHtml(infohtml);
			$(".result_photo_container").each(function() {
				$(this).removeClass("selected").children(":first").children(":first").removeClass("selected");
			});
			jHtml.addClass("selected");
			jHtml.children(":first").children(":first").addClass("selected");
			jHtml.children("a").addClass("selected");
		}
		GEvent.addListener(marker, 'click', showInfoWindow);
		peoplemap.addOverlay(marker);
		jHtml.click(showInfoWindow);
	});
	//zoomAll(peoplemap, aPeopleLocations);
	zoomInclSpot();
	$("#spotPointer").click();
}

function initPeopleMap() {
	if (typeof GBrowserIsCompatible != "undefined" && GBrowserIsCompatible()) {
		peoplemap = new GMap2(document.getElementById("peoplemap"));
		peoplemap.addControl(new GSmallMapControl());
		peoplemap.enableScrollWheelZoom();
        peoplemap.enableContinuousZoom();
		var spotPoint = new GLatLng(spotLat, spotLon);
		peoplemap.setCenter(spotPoint, 12);
		spotMarker = new GMarker(spotPoint, new GIcon(G_DEFAULT_ICON, "/images/global/marker_spot.png"));
		GEvent.addListener(spotMarker, 'click', showInfoHTML);
		peoplemap.addOverlay(spotMarker);
	}
}	

function showInfoHTML() {
	spotMarker.openInfoWindowHtml(spotHTML);
}
</script>{/literal}
