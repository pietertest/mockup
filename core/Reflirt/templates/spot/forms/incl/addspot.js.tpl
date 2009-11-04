<div id="similarcontainer" class="messagecontainer">
	<div class="close"/>
	<div class="spotalreadyexists">Er komt al een spot in het systeem voor met deze gegevens</div>
	<div class="same_label">Overeenkomstige spots:</div>
	<div id="navigation">
		<a nohref id="previous">Vorige</a>
		<a nohref id="next">Volgende</a>
	</div>
	<ul id="similar"></ul>
	<div>
		Weet je zeker dat de spot die je wilt toevoegen hier niet tussenzit? Voeg dan je spot toe! 
		<input type="button" value="Ok, sluiten" onclick="hideSimilar()" />
	</div>  
</div>

<div id="spotnotfound" class="messagecontainer">
	<div class="close"></div>
	<div>Er kan geen lokatie gevonden worden met deze gegevens.</div>
</div>


{literal}<script>
var start = 0;
var end = 1;

$().ready(function(){
	$("#next").click(next);
	$("#previous").click(previous);
	$(".close").click(hideSimilar);
});

function next() {
	start++;
	end++;
	getSimilar();
}

function previous() {
	start--;
	end--;
	getSimilar();
}

function processSimilarSpotData(data) {
	cleanSimilar();
	hideSimilar();
	if(data.items.length > 0) {
		setEnteredInfo(formatEnteredInfoHtml());
		setTotal(data.total);
		initSimilar(data.items);
		paginate(data.total);
		showSimilar();
	}
}

function setTotal(total) {
	var label = total == 1 ? "spot" : "spots";
	$(".total").text(total + " " + label);
}

function setEnteredInfo(html) {
	$("#entered").html(html);
}

function paginate(total) {
	//Previous
	if(start > 0) {
		$("#previous").show();
	} else {
		$("#previous").hide();
	}
	
	//Next
	if(end >= total) {
		$("#next").hide();
	} else {
		$("#next").show();
	}
}

function initSimilar(items) {
	markers = new Array();
	$.each(items, function(index, item) {
		var link = formatSimilarLink(item);
		var point = new GLatLng(item.lat, item.lng);
		var marker = new GMarker(point);
		var infoHtml = formatMapInfoHtml(item);
		GEvent.addListener(marker, 'click', function() {
			marker.openInfoWindowHtml(infoHtml);
		})
		$(link).click(function(){ 
				map.addOverlay(marker);
				marker.openInfoWindowHtml(infoHtml);
				map.panTo(point);
			}).appendTo("#similar");	
	});
}

function cleanSimilar() {
	$("#similar").empty();
}

function hideSimilar() {
	$("#similarcontainer").slideUp("fast");
}

function showSimilar() {
	$("#similarcontainer").slideDown("fast");
}

</script>{/literal}