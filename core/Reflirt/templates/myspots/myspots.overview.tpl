<h2>Mijn Spots</h2>
Maak je eigen spot aan en zie wie er bij jou nog meer in dezelfde spots bevonden.<br/>
<br/> 
Klik hier om op zoek te gaan naar spots om meer spots te markeren.  
<br/>
<br/>
<br/>
{literal}
<script>
$(document).ready(function() {
	var type = '{/literal}{$type}{literal}';
	$("input#but_volgende").click(function() {
		var cat = $("select#category").val();
		if(cat == "") {
			alert("Maake een keuze");
			return;
		}
		window.location = "/?page=myspots&action=form&cat=" + cat;
	});
});
</script>
{/literal}

{include file="widgets/spots.tpl"}