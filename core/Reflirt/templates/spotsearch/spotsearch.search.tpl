{include file="search/incl/search_top.tpl"}


<div class="search">
	<div class="navigation">
		<div class="searchbarEnd"></div> 
		<div style="padding-top: 4px;">
			Categorie:<!--input type="text" id="spotcategory_text" name="spotcategory_text" value="{$spotcategory_text}" /-->
			{html_options options=$categories name=category id="category" selected=$smarty.get.spotcategory }
			Plaats: <input type="text" name="cityname" id="cityname" value="{$smarty.get.cityname|escape:html}"/><input type="hidden" name="cityid" id="cityid" value="{$smarty.get.cityid}" />
			Postcode: <input type="text" name="zipcode" id="zipcode" value="{$smarty.get.zipcode|escape:html}"/><br/>
			
			<div style="display: none" id="spotcategory_pulldown">
				<div class="pulldown_top"></div>
				<ul class="spotcategory_pulldown" id="spotcategory_pulldown_ul">
					<li cat="0" title="Alles categories">Allen</li>
					<li cat="3" title="Zoeken naar schoffies en lekkere wijfen in kroegn en discotheken">Disco/Kroeg</li>
					<li cat="13" title="check die lekkere serveerster">Werk</li>
				</ul>
			</div>
			<div class="category"></div>
		</div>
	</div>
	<br/>
	<div class="SearchCriteriaLabel">{$tonen} {if $spotcategory != 0} in category "{$spotcategory_text}"{/if}</div>
	<br/>
	<div id="suggestion" style="color: red;" suggestedype="{$suggestedtype}" params="{$suggestedparams}">{$searchsuggestion}</div>
	
	<!-- Spots -->
	<div class="spotresults">
		{if $aantalspots==0 && $q != ""}
		Geen spots gevonden...<br/>
		<br/>
		<a href="/?page=spot&action=addnew">Nieuwe spot aanmaken?</a>
		{/if}
		{if $q == ""}
			<h2>Laatste spots</h2>
		{/if}
		<div id="spotresults"></div>
	</div>
	
	<div style="float: right; background-color: red; padding: 10px 25px 25px 25px;border: 1px solid #E0E0E0; background-color: #F4F4F4;margin-top: 50px;">
		<div class="mapnav"><a href="javascript: zoomAllSpots()">Zoom all</a></div>
		<div id="spotmap" style="height: 400px; width: 400px;" ></div>
	</div>
	

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
	initSpotsMap();
	placeSpotsOnMap(spotdata);
	zoomAllSpots();
	initSpotHovers(); 
	
	// Autocompletion for city
	$("#cityname").autocomplete("/servlets/autocomplete/city.php", {
		delay: 150,
		width: "260px",
		max: 9,
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
	$("#cityname").blur(function(){
		if(this.value == "") {
			$("#cityid").val("");
		}
	});
});

function zoomAllSpots() {
	zoomAll(spotmap, aSpotLocations);
}

function refreshSearch() {
	var extraParams = $("#suggestion").attr("params");
	var q = $("#hiddenkeyword").val();
	var url = 'http://localhost/?page=spotsearch&action=search'+
		'&type='+ $("#suggestion").attr("suggestedype") +
		'&spotcategory=' + $("#spotcategory").val();
	if(extraParams) {
		q = "";
	}
	url += "&q=" + q + "&" + extraParams;
	window.location.href = url;
	
	
	/*
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
	*/
}

function initSpotHovers() {
	$(".spotresult").hover(function(){
		$(this).addClass("spot-hover");
	},function(){
		$(this).removeClass("spot-hover");
	});
}

function initCategoryPulldownHover() {
	$("#spotcategory_pulldown_ul li").hover( function() {
		$(this).addClass("spotcategory-hover");
	}, function() {
		$(this).removeClass("spotcategory-hover");
	});
	$("#spotcategory_pulldown_ul li").click( function() {
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
	
	$("#category").change(function() {
		$("#spotcategory").val($(this).val());
		$("#searchform").submit();
	});
}

function placeSpotsOnMap(data){
	$("#spotresults").empty();
	var template = data.template;
	var infohtmltemplate = data.spotinfohtml;
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
		var showInfoWindow = function() {
            marker.openInfoWindowHtml(mergeJSON(infohtmltemplate, item));
        };
		GEvent.addListener(marker, 'click', showInfoWindow); 
		spotmap.addOverlay(marker);
		//$spot.click(function() { moveMapToPoint(spotmap, point); });
		$spot.click(showInfoWindow);
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
{include file="search/incl/search_footer.tpl"}
