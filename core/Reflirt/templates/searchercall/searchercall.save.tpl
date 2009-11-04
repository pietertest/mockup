{literal}
<script type='text/javascript' src='/javascript/jquery/jquery.datepicker.js'></script>
<script type='text/javascript' src='/javascript/jquery/date.js'></script>
<script type='text/javascript' src='/javascript/jquery/jquery.dimensions.js'></script>
<script>
var discoSelected = false;

$(document).ready(function() {
	$("input#but_volgende").click(function() {
		var cat = $("select#category").val();
		if(cat == "") {
			alert("Maake een keuze");
			return;
		}
		window.location = "/?page=search&action=compose&cat=" + cat;
	});
	
	$("#city").autocomplete("autocomplete/city.php", {
		delay: 150,
		width: "260px",
		max: 10,
		formatItem: formatCityItem,
		formatResult: formatResult,
		selectFirst: false,
	});
	$("#disco").autocomplete("autocomplete/disco.php", {
		delay: 150,
		width: "260px",
		max: 10,
		formatItem: formatDiscoItem,
		formatResult: formatResult,
		selectFirst: false
	});
	$("#disco").result(function(event, data, formatted) {
		$("#discoid").val(data[1]);
		discoSelected = true;
		if($("#city").val() == "") {
			$("#city").val(data[2]);
			$("#cityid").val(data[3]);
		}
	});
	$("#disco").keyup(function() {
		discoSelected = false;
	});
	$("#disco").blur(function() {
		if(!discoSelected) {
			$("#discoid").val("");
			$("#city").val("");
			$("#cityid").val("");
			
		}
	});
	$("#city").result(function(event, data, formatted) {
		$("#cityid").val(data[2]);
	});
	function formatDiscoItem(row) {
		return row[0] + " (" + row[2] + ")";
	}
	function formatCityItem(row) {
		return row[0] + " (" + row[1] + ")";
	}
	function formatResult(row) {
		return row[0];
	}
});

$(function()
{
	$('.date-pick').datePicker()
	$('#start-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#end-date').dpSetStartDate(d.addDays(1).asString());
			}
		}
	);
	$('#end-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#start-date').dpSetEndDate(d.addDays(-1).asString());
			}
		}
	);
});

	

{/literal}
</script>

<div style="width: 200px; border: 1px solid gray; float: right; position:relative;">
<h1>Laatste zoekopdracht</h1>
{foreach from=$lastrelevantsearchers item=oproep}
	<b>{$oproep->getString('title')}</b> ({$oproep->getFriendlyName()})<br/>
	{$oproep->getString('descr')}
{/foreach}
</div>

<form method="post">
	<input type="text" name="page" value="seachercall" />
	<input type="text" name="action" value="save" />
	
	
	
	<fieldset>
		<legend>Vind je flirt uit de discotheek!</legend>
		<table>
        	<tr>
            	<td valign="top">
                	
					
                	Land:<br/>
                	{html_options name=foo options=$countries selected=$country id="country"}<br/>
                	Plaats:<br/>
					<input type="text" xtabindex="3" value="{$smarty.request.city}" name="cityname" id="cityname" /><br/>
					Disco/kroeg<br/>
					<input type="text" xtabindex="4" value="{$smarty.request.disco}" name="disco" id="disco"/><br/>
					<label for="start-date">Start date:</label>
					<input name="start-date" tabindex="2" id="start-date" class="date-pick dp-applied" value="{$smarty.request.start-date}">             			
	            	<label for="end-date">End date:</label>
					<input name="end-date" tabindex="1" id="end-date" class="date-pick dp-applied" value="{$smarty.request.end-date}"><br/>
					<br/><br/>
					Titel:<br/>
					<input type="text" name="title" id="title" value="{$smarty.request.title}"/><br/>
					Omschrijving:<br/>
					<textarea rows="5" cols="40" name="descr">{$smarty.request.descr}</textarea><br/>
					<input type="hidden" name="discoid" id="discoid"/><br/>
					<input type="hidden" name="cityid" id="cityid"/><br/>
					<input type="submit" value="Opslahn" />	
                </td>
			</tr>
		</table>
	</fieldset>
	
</form>