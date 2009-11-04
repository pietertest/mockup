
<div class="search_spots">
	<div class="navigation">
		<a href="/?page=spot&action=addnew">Nieuwe spot aanmaken</a>
	</div>
	  
	<div class="spotcategorie_container">
		Categorie:<input type="text" id="spotcategory_text" name="spotcategory_text" value="{$spotcategory_text}" />
		<ul id="spotcategory_pulldown" class="spotcategory_pulldown">
			<li cat="0" title="Alles categories">Allen</li>
			<li cat="3" title="Zoeken naar schoffies en lekkere wijfen in kroegn en discotheken">Disco/Kroeg</li>
			<li cat="13" title="check die lekkere serveerster">Werk</li>
		</ul>
	</div>
	
	<div class="category"><div>
	

<div class="SearchCriteriaLabel">{$tonen} {if $spotcategory != 0} in category "{$spotcategory_text}"{/if}</div>

<div id="suggestion" style="color: red;" suggestedype="{$suggestedtype}">{$searchsuggestion}</div>

{if $spots|@count == 0 && $q != ""}
Geen spots gevonden...<br/>
<br/>
<a href="/?page=spot&action=addnew">Nieuwe spot aanmaken</a>
{/if}
{if $q == ""}
	<h2>Laatste spots</h2>
	<hr/>
{/if}

<!-- Spots -->
<div id="spotresults" class="spotresults"></div>






</div>


{literal}<script>

var spotmap;
var spotdata = {/literal}{$spots}{literal};
var aSpotLocations = new Array();


$().ready(function() {
	$("#suggestion").css({cursor: "pointer"}).click(function() {
		refreshSearch();
	});
	initCategoryPulldownHover();
	$("li").tooltip({
		delay: 500
	})
	initSpotsMap();
	placeSpotsOnMap(spotdata);
	zoomAll(spotmap, aSpotLocations);
	initSpotHovers(); 
});

function refreshSearch() {
	$.get("http://localhost/?page=spotsearch&action=search",
		{
			q: $("#hiddenkeyword").val(),
			type: $("#suggestion").attr("suggestedype"),
			spotcategory: $("#spotcategory").val()
		}, 
		function(html) {
			$(".search_spots").parent().html(html);
			initSpotHovers();
		},
		"get"
	);
}

function initSpotHovers() {
	$(".spotresult").hover(function(){
		$(this).addClass("spot-hover");
	},function(){
		$(this).removeClass("spot-hover");
	});
}

function initCategoryPulldownHover() {
	$("#spotcategory_pulldown li").hover( function() {
		$(this).addClass("spotcategory-hover");
	}, function() {
		$(this).removeClass("spotcategory-hover");
	});
	$("#spotcategory_pulldown li").click( function() {
		var selectedText = $(this).text();
		var selectedId = $(this).attr("cat");
		
		$("#spotcategory_text").val(selectedText);
		$("#spotcategory").val(selectedId);
		$("#spotcategory_pulldown").hide();
		refreshSearch();
	});
	
	// Hide on click away from pulldown 
	$(document).bind("click", function(e) {
		$clicked = $(e.target);
		if(!$clicked.is("#spotcategory_text") ) {
			$("#spotcategory_pulldown").hide();
		}
	});
	
	// Show on pulldown on clik
	$("#spotcategory_text").click(function(){
		$("#spotcategory_pulldown").show();
	});
}

function placeSpotsOnMap(data){
	$("#spotresults").empty();
	var template = data.template;
	$.each(data.items, function(i,item){
		var html = mergeJSON(template,item);
		var $spot = $(html).appendTo("#spotresults");
			
		if(!spotmap) {
			return;
		}
		var point = new GLatLng(item.lat, item.lng);
		if(!isValidPoint(point)) {
			return;
		}
		aSpotLocations[aSpotLocations.length] = point;
		var marker = new GMarker(point);
		GEvent.addListener(marker, 'click', function() {
            marker.openInfoWindowHtml('<img src="/uploaded/photos/'+item.filename+'" width="40"/>'+item.user+'</b><br />Latitude: <b>'+item.lat+'</b><br />Longitude: <b>'+item.lng+'</b>');
        });
		spotmap.addOverlay(marker);
		$spot.click(function() { moveMapToPoint(spotmap, point); });
	});

}

function initSpotsMap() {
	if (typeof GBrowserIsCompatible != "undefined" && GBrowserIsCompatible()) {
		spotmap = new GMap2(document.getElementById("spotmap"));
		spotmap.addControl(new GSmallMapControl());
		spotmap.enableScrollWheelZoom();
        spotmap.enableContinuousZoom();
        var point = new GLatLng(52.373812, 4.890951);
        spotmap.setCenter(point, 12);
	}
}	


</script>{/literal}

