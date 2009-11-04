<div style="float: right; width: 400px; height: 400px;background-color: red; padding: 25px;border: 1px solid #E0E0E0; background-color: #F4F4F4">
	<a nohref class="MapSpotName" />
	<div id="map_people" class="SpotMap" ></div>
</div>
<div id="spots_pictures"></div>


{literal}<script>

$().ready(function() {
	//Spots
	$.getJSON("/servlets/?servlet=spots&action=people&id={/literal}{$id}{literal}",
       function(data) {
       	fillSpotsResults(data);
   	});
});
var locations = new Array();
var map;

function fillSpotsResults(data){
	$("#spots_pictures").empty();
	var template = data.template;
	$(".MapSpotName").text(spotName).addClass("noline").click(moveMapToSpot);
	initPeopleMap();
	$.each(data.items, function(i,item){
		var html = mergeJSON(template,
			{
				photo: item.photo,
				username: item.user
			}
		);
		var latlng = new Array();
		latlng['lat'] = item.lat;
		latlng['lng'] = item.lng;
		locations[locations.length] = latlng;
		var point = new GLatLng(item.lat, item.lng);
		var marker = new GMarker(point);
		GEvent.addListener(marker, 'click', function() {
            marker.openInfoWindowHtml('<img src="/uploaded/photos/'+item.filename+'" width="40"/>'+item.user+'</b><br />Latitude: <b>'+item.lat+'</b><br />Longitude: <b>'+item.lng+'</b>');
        });
		map.addOverlay(marker);
		//link = '<a href="#" onclick="moveMapTo('+index+')">'+element.name+'</a><br />';
        //$('p#location_list').append(link);
		$(html).click(function() { moveMapTo(i); }).appendTo("#spots_pictures");
	});
	zoomAll();
}

function moveMapToSpot() {
	moveMapToPoint(new GLatLng(spotLat, spotLon));
}

function moveMapToPoint(point) {
	map.panTo(point);
}
function moveMapTo(index) {
	map.panTo(new GLatLng(locations[index].lat, locations[index].lng));
}

function initPeopleMap() {
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map_people"));
		map.addControl(new GSmallMapControl());
		var spotPoint = new GLatLng(spotLat, spotLon);
		map.setCenter(spotPoint, 12);
		var marker = new GMarker(spotPoint);
		GEvent.addListener(marker, 'click', function() {
            marker.openInfoWindowHtml(spotName);
        });
		map.addOverlay(marker);
	}
}	

function zoomAll() {
    bounds = new GLatLngBounds();
    map.setCenter(new GLatLng(0,0),0);
    for (var i = 0; i < locations.length; i++) {
		bounds.extend(new GLatLng(locations[i].lat, locations[i].lng));
    }
    map.setZoom(map.getBoundsZoomLevel(bounds));
    var clat = (bounds.getNorthEast().lat() + bounds.getSouthWest().lat()) /2;
    var clng = (bounds.getNorthEast().lng() + bounds.getSouthWest().lng()) /2;
    map.setCenter(new GLatLng(clat,clng));
}
</script>{/literal}
