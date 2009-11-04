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
	$("#ddisconame").autocomplete("/servlets/autocomplete/disco.php", {
		delay: 150,
		width: "260px",
		max: 10,
		formatItem: formatDiscoItem,
		formatResult: formatResult,
		selectFirst: false,
		extraParams: {
			sdcityid: 	function() { return $("#sdcityid").val();},
			countryid:	function() { return $("#countryid").val();}
		}
	});
	$("#ddisconame").result(function(event, data, formatted) {
		$("#sddiscoid").val(data[1]);
		$("#cicityname").val(data[2]);
		$("#sdcityid").val(data[3]);
		$("#countryid").val(data[4]);
		discoSelected = true;
	});
	$("#ddisconame").keyup(function() {
		discoSelected = false;
	});
	$("#ddisconame").blur(function() {
		if(!discoSelected) {
			$("#sddiscoid").val("");
			/*
			$("#cicityname").val("");
			$("#sdcityid").val("");
			$("#countryid").val("");
			*/
		}
	});
	$("#cicityname").result(function(event, data, formatted) {
		alert(data[2]);
		$("#sdcityid").val(data[2]);
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
			ddisconame: {
				required: false
			},
			cicityname: {
				required: true
			}
		},
		messages: {
		}
	});

	$("#countryid").change( function() {
		$("#cicityname").flushCache();
		$("#ddisconame").flushCache();
		$("#cicityname").val("");
		$("#ddisconame").val("");
		$("#sdcityid").val("");
		$("#sddiscoid").val("");
	});
	
	
	
});

function checkDiscoAndCity() {
	if(!$("form#form_advanced").valid()) {
		return false;
	}
	var discoid = $("input#sddiscoid").val();
	var disconame = $("input#ddisconame").val();
	if(discoid == "" && disconame != "") {
		return confirm("De waarde '"+ disconame + "' komt nog niet voor in ons systeem "+
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
		<input type="hidden" name="page" value="myspots" />
		<input type="hidden" name="action" value="save" />
		<input type="hidden" name="cat" value="{$cat}" />

	<fieldset id="advanced">
		<legend>Voeg een nieuwe spot toe</legend>
		<table>
        	<tr>
            	<td valign="top">
                	Land:<br/>
                	{html_options name=countryid options=$countries selected=$country id=countryid}<br/>
                	Plaats:<br/>
					<input type="text" value="{$cicityname}" name="cicityname" id="cicityname" /><br/>
					Disco/kroeg<br/>
					<input type="text" xtabindex="4" value="{$ddisconame}" name="ddisconame" id="ddisconame"/><br/>
					
					<!-- 
					<label for="date_from">Start date:</label>
					<input name="date_from" tabindex="2" id="date_from" class="date-pick dp-applied" value="{$date_from}">             			
	            	<label for="date_to">End date:</label>
					<input name="date_to" tabindex="1" id="date_to" class="date-pick dp-applied" value="{$date_to}"><br/>
					 -->
					<br/><br/>
					<input type="hidden" name="sddiscoid" id="sddiscoid" value="{$sddiscoid}"/><br/>
					<input type="hidden" name="sdcityid" id="sdcityid" value="{$sdcityid}"/><br/>
					<input type="submit" value="Opslahn" />
				
                </td>
			</tr>
		</table>
	</fieldset>
	</form>
