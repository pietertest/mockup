{literal}
<script>
var discoSelected = false;

$(document).ready(function() {
	$("#cicityname").autocomplete("/servlets/autocomplete/city.php", {
		delay: 150,
		width: "260px",
		max: 10,
		formatItem: formatCityItem,
		formatResult: formatResult,
		selectFirst: false,
		extraParams: {
			countryid: function() {	return $("#countryid").val();}
		}
	});
	$("#cicityname").result(function(event, data, formatted) {
		$("#ovtdest").val(data[1]);
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
	var validator = $("form#form_advanced").validate({
		debug: false,
		rules: {
			title: {
				required: true
			},
			descr: {
			
				required: true
			},
			ddisconame: {
				required: false
			},
			cicityname: {
				required: true
			}
		},
		messages: {
			title: "Vul een titel in",
			descr: {
				required: "Vul een omschrijving in"
			}
		}
	});

	$("#countryid").change( function() {
		$("#cicityname").flushCache();
		$("#cicityname").val("");
		$("#ovtdest").val("");
	});
	
	
	
});

function checkDiscoAndCity() {
	if(!$("form#form_advanced").valid()) {
		return false;
	}
	var cityid = $("input#ovtdest").val();
	var cicityname = $("input#cicityname").val();
	if(cityid == "" && cicityname != "") {
		return confirm("De waarde '"+ cicityname + "' komt nog niet voor in ons systeem "+
		"en zal worden toegevoegd.\n\nWeet je dit zeker?");
	}
}

/*
$(function()
{
	$('.date-pick').datePicker()
	$('#date_from').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#date_to').dpSetStartDate(d.addDays(1).asString());
			}
		}
	);
	$('#date_to').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#date_from').dpSetEndDate(d.addDays(-1).asString());
			}
		}
	);
});
*/
	

{/literal}
</script>

<div style="width: 200px; border: 1px solid gray; float: right; position:relative;">
<h1>Laatste zoekopdracht</h1>
{foreach from=$lastrelevantsearchers item=oproep}
	<b>{$oproep->getString('title')}</b> ({$oproep->getFriendlyName()})<br/>
	{$oproep->getString('descr')}
	<hr/>
{/foreach}
</div>
	<form id="form_advanced" onsubmit="return checkDiscoAndCity()">
		<input type="hidden" name="page" value="searchercall" />
		<input type="hidden" name="action" value="save" />
		<input type="hidden" name="cat" value="{$cat}" />
		<input type="hidden" name="type" value="{$type}" />
		<input type="hidden" name="id" value="{$id}" />
	<fieldset id="advanced">
		<legend>Vind je flirt uit de discotheek!</legend>
		<table>
        	<tr>
            	<td valign="top">
                	Land:<br/>
                	{html_options name=countryid options=$countries selected=$country id=countryid}<br/>
                	Plaats:<br/>
					<input type="text" value="{$cicityname}" name="cicityname" id="cicityname" /><br/>
					<!-- 
					<label for="date_from">Start date:</label>
					<input name="date_from" tabindex="2" id="date_from" class="date-pick dp-applied" value="{$date_from}">             			
	            	<label for="date_to">End date:</label>
					<input name="date_to" tabindex="1" id="date_to" class="date-pick dp-applied" value="{$date_to}"><br/>
					 -->
					<br/><br/>
					{if $type!=3}
					Geslacht:<br/>
					{html_options name="ovtsex" options=$select_sex selected=$ovtsex}<br/>	
					Titel:<br/>
					<input type="text" name="title" id="title" value="{$title}"/><br/>
					Omschrijving:<br/>
					<textarea rows="5" cols="40" name="descr">{$descr}</textarea><br/>
					{/if}
					<input type="hidden" name="ovtdest" id="ovtdest" value="{$ovtdest}"/><br/>
					<input type="submit" value="Opslahn" />
				
                </td>
			</tr>
		</table>
	</fieldset>
	</form>
