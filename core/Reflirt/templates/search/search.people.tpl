{include file="search/incl/search_top.tpl"}



<div class="search">
	<div class="navigation">
		<div class="searchbarEnd"></div>
		<div style="padding-top: 9px;">
			Plaats: <input type="text" name="cityname" id="cityname" value="{$smarty.get.cityname}"/><input type="hidden" name="cityid" id="cityid" value="{$smarty.get.cityid}" />
			Postcode: <input type="text" name="zipcode" id="zipcode" value="{$smarty.get.zipcode}"/><br/>
			<h2>{$tonen} {if $spotcategory != 0} in category "{$spotcategory_text}"{/if}</h2>
		</div>
	</div>
</div>
<div id="people" class="SpotSearchPictures" >
	<img src="/images/global/loading.gif" id="loading"/>
	<div class="noresults" style="display: none">Niks gevonden...</div>
</div>

<div class="clear"></div>	


<span class="MapButton" id="mapbutton" >Kaart</span>
<a id="zoomall" class="small" nohref>Zoom iederen</a>
<div class="mapcontainer">
		<div class="showmaplabel" style="display: none;"> Kaart tonen</div>
		<div id="peoplemap" class="SpotMap"></div>
</div>



{literal}<script>

var peoplemap;
var aPeopleLocations = new Array();
var q= '{/literal}{$q}{literal}';

function toggleMap() {
	$("#peoplemap").toggle();
	$(".showmaplabel").toggle();
}

$().ready(function(){
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
	
	initPeopleMap();
	$.getJSON("/servlets/?servlet=people&action=byname&q="+q +
	"&cityid="+$("#cityid").val()+"&zipcode="+$("#zipcode").val(),
       function(data) {
       	if(data.items.length == 0) {
			$("#loading").hide();
			$(".noresults").show();
       	} else {
       		fillPeopleResults(data);
       	}
   	});
   	
   	$("#zoomall").click(zoomPeople);
   	$("a").css({cursor: "pointer"});
   	$("#mapbutton").click(toggleMap);
   	$(".showmaplabel").click(toggleMap);   	
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
	var infohtmltemplate = data.infohtml;
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
		var jHtml = $(html).appendTo("#people");
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

