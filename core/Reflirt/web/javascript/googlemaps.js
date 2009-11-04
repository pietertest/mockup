
geocoder = new GClientGeocoder();


function distance() {
	var point1;
	var point2;
	geocoder.getLatLng("lumeijstraat 13, 1056vs, amsterdam,nederland", function(point) {
		if (!point) {
	    	alert('Kan geen eenduidige locatie vinde, probeer een spcifieker adres.');
	     } else {
	      debugger;
	      point1 = new GPoint(point.y, point.x)
	      point1 = point;
		}
	});

	geocoder = new GClientGeocoder();
	geocoder.getLatLng("lumeijstraat 30, 1056vs, amsterdam,nederland", function(point) {
		if (!point) {
	    	alert('Locatie niet in Google Maps gevonden');
	     } else {
	      point2 = point;
		}
	});
	alertDistance(point1, point2);
}

function alertDistance(point1, point2) {
//	var R = 6371; // km
//	var d = Math.acos(Math.sin(point1.x)*Math.sin(point2.x) +
//                  Math.cos(point1.x)*Math.cos(point2.x) *
//                  Math.cos(point2.y-point1.y)) * R;
//	alert('De afstand tussen de punten is ongeveer ' + d + ' km');
}

var previousMarker = null;

var icon = new GIcon({
	"image": "/images/m2.png",
	"iconAnchor": new GPoint(0,0),
	"infoWindowAnchor" : new GPoint(0,0),
	"printShadow" : "m2_shadow.png"});

function getCoords(address) {
	geocoder = new GClientGeocoder();
	geocoder.getLatLng(address, function(point) {
		if (!point) {
	    	alert('Kan geen eenduidige locatie vinden, probeer een spcifieker adres.');
	     } else {
				//alert(waarde + "=" + point[waarde]);
				//moveMapToPoint(point);
				map.setCenter(new GLatLng(point.lat(), point.lng()), 13);
				
				if(previousMarker != null) {
					map.removeOverlay(previousMarker);
				}
				var marker = new GMarker(new GPoint(point.x, point.y), icon);
				map.addOverlay(marker);
				previousMarker = marker;

        		//GEvent.addListener(marker, 'click', function() {
            	//	marker.openInfoWindowHtml('<img src="moeder.jpg" width="40" />Gerda van Bolstok (45)<br/>2 kinderen<br/>lng: ' + point.lng() + '<br/>lang: ' + point.lat());
        		//});
        		//addLocation(address, point);
        		//link = '<a href="#" onclick="moveMapTo('+(locations.length - 1)+')">'+address+'</a><br />';
        		// $('p#location_list').append(link);
	     }
	});
}

function addLocation(address, point) {
		locations[locations.length] = {"0":"Jan","name":"Jan","1":point.lat(),"latitude":point.lat(),"2":point.lng(),"longitude":point.lng(),"3":"2007-12-11 00:00:00","created":"2007-12-11 00:00:00"};
		//locations[locations.length].latidue = [{name: address, latitude: point.x, longitude: point.y}];
}

var clickHandler;
var map;
var lat;
var lng;
var locations;
var bounds;

$(document).ready(function() {
    if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map"));
        map.addControl(new GLargeMapControl());
        map.addControl(new GMapTypeControl());
        map.enableScrollWheelZoom();
        map.enableContinuousZoom();
        map.setCenter(new GLatLng(0, 0), 13);
        $.get('http://localhost/temp/googlemaps/locations.php', processLocations );
    }

    $('a#new_location').click(function() {
        $('a#new_location').hide();
        $('div#info').show('slow');
        clickHandler = GEvent.addListener(map, "click", function(marker, point) {
            setNewMarker(point);
        });
    });

    $('a#save').click(function() {
        $('div#formular').hide('slow');
        $.post('http://localhost/temp/googlemaps/locations.php',
               { type: 'upload',
                 name: $('form').find('input').get(0).value,
                 latitude: lat,
                 longitude: lng
               },
               processLocations );
    });

    $('a#cancel').click(function() {
        $('div#formular').hide('slow');
    });

    $('a#zoom_show_all').click(function() {
        zoomShowAll();
    });
});

function setNewMarker(point) {
    $('div#formular').show('slow');
    $('a#new_location').show();
    $('div#info').hide();
    lat = point.lat();
    lng = point.lng();
    $('div#formular').find('p:nth-of-type(0) ').html('Latitude=<b>'+lat+'</b>, Longitude=<b>'+lng+'</b>');
    $('div#formular').show();
    GEvent.removeListener(clickHandler);
}

function processLocations(content) {
    eval("locations = "+content);
    $('p#location_list').html('');
    locations.forEach(function(element, index, array) {
        var marker = new GMarker(new GLatLng(element.latitude, element.longitude), {title: element.name});
        map.addOverlay(marker);
        GEvent.addListener(marker, 'click', function() {
            marker.openInfoWindowHtml('Name: <b>'+element.name+'</b><br />Latitude: <b>'+element.latitude+'</b><br />Longitude: <b>'+element.longitude+'</b>');
        });
        link = '<a href="#" onclick="moveMapTo('+index+')">'+element.name+'</a><br />';
        $('p#location_list').append(link);
    });
    zoomShowAll();
}

function moveMapToPoint(point) {
	map.panTo(point);
}
function moveMapTo(index) {
    map.panTo(new GLatLng(locations[index].latitude, locations[index].longitude));
}

function zoomShowAll() {
    bounds = new GLatLngBounds();
    map.setCenter(new GLatLng(0,0),0);

    locations.forEach(function(elemet, id, array) {
        bounds.extend(new GLatLng(locations[id].latitude, locations[id].longitude));
    });
    map.setZoom(map.getBoundsZoomLevel(bounds));
    var clat = (bounds.getNorthEast().lat() + bounds.getSouthWest().lat()) /2;
    var clng = (bounds.getNorthEast().lng() + bounds.getSouthWest().lng()) /2;
    map.setCenter(new GLatLng(clat,clng));
}