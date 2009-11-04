	<div style="float: right; width: 400px; xbackground-color: #FFF8CE">
		{*
		<div class="agendas">
			<h1>Agenda</h1>			
			{if !$comingagendas}<span class="noagendas">Geen agendapunten...<a href="#" class="noline">toevoegen</a></span>{/if}
			{foreach from=$comingagendas item=agenda}
				<span style="font-size: 11px">{$agenda->getHTML('dateascalendar')}</span>
				<a class="noline" href="/?page=agenda&action=view&id={$agenda->getKey()}">{$agenda->get('title')}</a>
				<a class="noline" href="/?page=agenda&action=view&id={$agenda->getKey()}">{$agenda->get('description')|truncate:10}</a>
				<br/>
			{/foreach}
			<br/> 
			<br/> 
			
		</div>
				
		<br/>
		<div id="uitvragen" title="Stuur een zoekopdracht naar iemand toe">Iemand mee uitvragen naar Paradiso?</div>
		<div class="in_category">
			Uit <a href="/?page=spotsearch&action=search&q=&type=&spotcategory={$spot->getString('category')}">{$spot->getFriendlyCategoryName()}</a>
		</div>
						{$spot->getNoOfPhotos()} fotos<br/>
		*}
		<div class="small">
			Category: <a href="/?page=spotsearch&action=search&q=&type=&spotcategory={$spot->getString('category')}">{$spot->getFriendlyCategoryName()}</a><br/>
			<br/>
			Gespot: {$spot->getNoOfMembers()} keer<br/>

		</div>
		
	</div>
	
	<div>
		<div class="spot_header">
			<div class="spotted" ><div>{$spot->getNoOfMembers()}</div></div>
			{$spot->getName()|capitalize}
			<span class="route">
				{if $smarty.session.username}
					{if $sessionuser->hasLocation()}
					(<span id="distance" title="Afstand naar deze spot vanaf mijn huis hemelsbreed">, <a nohref id="walk" title="Route vanaf mijn huis">route</a></span>)
					{else}
					(<span class="disabled"><a nohref title="Vul je profiel aan met je woonplaats of postcode om de route te kunnen bekijken">Route</a></span>)
					{/if}
				{else}
					(<span class="disabled"><a nohref title="Log in om de route te bekijken vanaf jouw omgeving">Route</a></span>)
				{/if}
			</span>
		
		</div>
		<div class="label2">{$spot->getHtml("address")}</div>
		
		<br/>
		{if $inmyspots}
			<div class="isinmyspot">Je hebt dit al als spot gemarkeerd</div>
		{else}
			<div class="addspot"><a href="/?page=myspots&action=add&id={$id}">Als mijn spot markeren</a></div>
		{/if}
	</div>
	
<div class="clear" ></div>
	
<div id="tabscontainer" class="tabscontainer" style="width: 800px;">
	<ul>
		<li><a href="/?page=spot&action=people&id={$id}"><span>People</span></a></li>
		{*<li><a href="/?page=spot&action=viewphotos&id={$id}"><span>Fotos</span></a></li>
		<li><a href="/?page=spot&action=agenda&id={$id}"><span>Agenda</span></a></li>*}
	</ul>
</div>

<div id="map" style="float:right; width: 400px; height: 400px"></div>

<script type="text/javascript" src="{$_googlemaps_script}"></script>
<script>
</script> 

{literal}<script>
function getFriendlyDistance(meters) {
	var distanceHtml = meters + " m";
	if(meters > 999) {
		// Km
		var km = (meters / 1000).toFixed(1);
		
		// No "2.0 km", just "2 km" 
		if (( km % (meters / 1000).toFixed(1) ) == 0) {
			km = (meters / 1000).toFixed(0);
		}
		distanceHtml = km + " km";
	}
	return distanceHtml;
}

var currentTab = '{/literal}{$tab}{literal}';
var loaded = new Array();
var q = '{/literal}{$q}{literal}';
var spotLon = '{/literal}{$spot->getString('lng')}{literal}';
var spotLat = '{/literal}{$spot->getString('lat')}{literal}';
var spotPoint = new GLatLng(spotLat, spotLon);
var spotName = '{/literal}{$spot->getName()|escape|capitalize}{literal}';
var spotHTML = '{/literal}{$spot->getHTML('infoHTML')}{literal}';

{/literal}{if $sessionuser}{literal}
var userPoint;
$().ready(function(){
	var userLat = {/literal}{$sessionuser->getString('lat')}{literal};
	var userLng = {/literal}{$sessionuser->getString('lng')}{literal};
	userPoint = new GLatLng(userLat, userLng);
	var meters = userPoint.distanceFrom(spotPoint).toFixed(0);
	$("#distance").prepend(getFriendlyDistance(meters));
});
{/literal}{/if}{literal}

var _tab = '';
var gdir;
$().ready(function() {
	var $tabs = $('#tabscontainer > ul').tabs({
		select: function(ui) { _tab = ui.panel.id; return true;},
		cache: true
	});
	$("#drive").click(drive);
	$("#walk").toggle(walk, clearWalk);
});

function walk() {
		getDirections(G_TRAVEL_MODE_WALKING);
}
function clearWalk() {
	gdir.clear();
	$("#walk").text("toon route");
}

function drive() {
		getDirections(G_TRAVEL_MODE_DRIVING);
}

function onDirectionsLoad() {
	var meters = gdir.getDistance().meters;
	$("#walk").append(" (" + getFriendlyDistance(meters) + ")");
}
function getDirections(travelMode) {
	gdir = new GDirections(peoplemap);
	if(!travelMode) {
		travelMode = G_TRAVEL_MODE_WALKING;
	}
	
	GEvent.addListener(gdir, "load", onDirectionsLoad);
	var opts = {};
   	opts.travelMode = travelMode;
   	var from = userPoint.lat()+ "," + userPoint.lng();
   	var to = spotPoint.lat() + "," + spotPoint.lng();
  	gdir.load("from: "+from+" to: " + to);
}
	 
</script>{/literal}

