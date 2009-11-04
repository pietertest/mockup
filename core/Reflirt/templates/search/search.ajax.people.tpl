<div style="float: right; background-color: red; padding: 10px 25px 25px 25px;border: 1px solid #E0E0E0; background-color: #F4F4F4">
	<div class="mapnav"><a href="javascript: zoomPeople()">Zoom all</a></div>
	<div id="peoplemap" style="height: 400px; width: 400px;" ></div>
</div>
<div id="people" ></div>

{literal}<script>

var peoplemap;
var aPeopleLocations = new Array();

$().ready(function(){
	initPeopleMap();
	$.getJSON("/servlets/?servlet=people&action=byname&q="+q+
		"cityid="+$("#cityid").val()+"&zipcode="+$("#zipcode").val(),
       function(data) {
       	fillPeopleResults(data);
   	});
});

function initPeopleHovers(){
	$(".result_photo_container").hover(function(){
		$(this).addClass("spot-hover");
	},function(){
		$(this).removeClass("spot-hover");
	});
}

function fillPeopleResults(data){
	$("#people").empty();
	var template = data.template;
	$.each(data.items, function(i,item){
		
		var html = mergeJSON(template,
			{
				photoid: item.filename,
				username: item.username
			}
		);
		var jHtml = $(html).appendTo("#people");
		if(!peoplemap) {
			return;
		}
		var point = new GLatLng(parseFloat(item.lat), parseFloat(item.lng));
		if (!isValidPoint(point)) {
			return;
		}
		aPeopleLocations[aPeopleLocations.length] = point;
		var marker = new GMarker(point);
		var showInfoWindow = function() {
			marker.openInfoWindowHtml('<img src="/uploaded/photos/'+item.filename+'" width="40"/>'+item.username+'</b><br />Latitude: <b>'+item.lat+'</b><br />Longitude: <b>'+item.lng+'</b>');
            $(".result_photo_container").removeClass("userclicked");
			jHtml.addClass("userclicked");
		};
		GEvent.addListener(marker, 'click', showInfoWindow);
		peoplemap.addOverlay(marker);
		jHtml.css({border: "1px solid #FFFBA8"}).click(showInfoWindow);
	});
	zoomPeople();
	initPeopleHovers();
}

function zoomPeople() {
	var b = aPeopleLocations;
	aPeopleLocations;
	zoomAll(peoplemap, aPeopleLocations);
}
function initPeopleMap() {
	if(peoplemap) {
		log("initPeopleMap: Map already defined");
	} else if (typeof GBrowserIsCompatible != "undefined" && GBrowserIsCompatible()) {
		peoplemap = new GMap2(document.getElementById("peoplemap"));
		peoplemap.addControl(new GSmallMapControl());
		peoplemap.enableScrollWheelZoom();
        peoplemap.enableContinuousZoom();
        var point = new GLatLng(52.373812, 4.890951);
		peoplemap.setCenter(point, 5);
	}
}	

</script>{/literal}

