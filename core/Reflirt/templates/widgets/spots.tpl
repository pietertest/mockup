		<div class="spots_tabs"> 
			<div class="header2">{$spottitle}</div>
			<div style="clear: left;" ></div>
			
			<div class="spots">
				{assign var=c value=0}
				{counter assign=$c print=false}
				{foreach from=$myspots item=category key=key}
					{if $c == 0}
				<div class="tab active">{$key} ({$category.aantal})</div>
					{else}
				<div class="tab">{$key} ({$category.aantal})ff</div>
					{/if}
				<div class="locations" id="disco">
					{foreach from=$category.spots item=spot key=key2}
					<div class="location" cat="{$spot.category}" key="{$spot.id}">
					<a class="add" href="/?page=myspots&action=add&id={$spot.id}"></a>{$spot.title} ({$spot.aantal})</div>
					{/foreach}
						{if $smarty.get.page == "myspots"}
					<div class="new_spot"><a class="noline" href="/?page=myspots&action=form&cat={$spot.category}">Nieuw spot toevoegen</a></div>
						{/if}
				</div>
					{counter name=c assign=c}
				{/foreach}
			</div>
		</div>
	
		<!-- Photos -->
		<div class="spots_results" id="spots_results" style="border: 1px solid #E1E1E1;">
			<div class="people">
				<div class="info" id="info">
					<a id="showall" class="showall"></a>
					<a id="label1" class="label1"></a>
					<div id="label2" class="label2"></div>
				</div>
				<div id="spots_pictures" class="spots_pictures" ></div>
			</div>
		</div>

{literal}<script>

$(document).ready(function() {
	//Spots
	$(".location").click(function(){
		var id = this.getAttribute("key");
		var cat = this.getAttribute("cat");
		//$.getJSON("/servlets/users/by_most_populair_spot.php?format=json",
		$.getJSON("/servlets/?servlet=spots&action=people",
		
		{
			id: id,
			cat: cat
		},
        function(data) {
        	fillSpotsResults(data);
    	});
   	});
   	$(".location:first").click();
});

function fillSpotsResults(data){
	$("#spots_pictures").empty();
	var template = data.template;
	$.each(data.items, function(i,item){
		var html = mergeJSON(template,
			{
				photo: item.photo,
				username: item.user
			}
		);
		$(html).appendTo("#spots_pictures");
	});
	$("#label1").html(data.title);
	$("#label2").html(data.addition);
	$("#showall").html("<a class=\"noline\" href=\"/?page=spot&action=view&id="+data.spotid+"\">Toon allen (" + data.nrofresults + ")</a>");
}	

function remove(id, cat) {
	if(confirm("Weet je zeker dat je de oproep wilt verwijderen?")) {
		window.location = "/?page=searchercall&action=delete&id="+id+"&cat="+cat;
	}
}
function modify(id, cat) {
	window.location = "/?page=searchercall&action=modify&id="+id+"&cat="+cat;
}
function gotoResult(id, cat) {
	window.location = "/?page=searchercall&action=view&id=" + id + "&cat=" + cat;
}

function addToBookmarks(id) {
	window.location = "/?page=bookmark&action=add&user=" + id;
}


</script>{/literal}