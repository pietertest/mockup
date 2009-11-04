function moveMapToPoint(map, point) {
	if(!map) {
		alertNoMap();
		return;
	}
	map.panTo(point);
}

function alertNoMap() {
	alert("Er is iets fout gegaan waardoor de spots niet op de kaart " +
			"gezet kunnen worden. Ververs de pagina om het nogmaals te proberen.");	
}

function zoomAll(map, aPoints) {
    if(!map) {
    	return;
    }
    bounds = new GLatLngBounds();
    map.setCenter(new GLatLng(0,0),0);
    for (var i = 0; i < aPoints.length; i++) {
    	if(!isValidPoint(aPoints[i])) continue;
		bounds.extend(aPoints[i]);
    }
    map.setZoom(map.getBoundsZoomLevel(bounds));
    var clat = (bounds.getNorthEast().lat() + bounds.getSouthWest().lat()) /2;
    var clng = (bounds.getNorthEast().lng() + bounds.getSouthWest().lng()) /2;
    map.setCenter(new GLatLng(clat,clng));
}

function isValidPoint(point) {
	return parseFloat(point.lat()) && parseFloat(point.lng());
}