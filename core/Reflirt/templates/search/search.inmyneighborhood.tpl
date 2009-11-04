<h1>Bij mij in de buurt</h1>

Zoeken bij mij in de buurt:<br/>
<form action="/" >
	<input type="hidden" name="page" value="search" />
	<input type="hidden" name="action" value="inmyneighborhood" />
	
	Postcode:
	<input type="text" name="zipcode" value="{$zipcode}" /> of 
	Stad: <input type="text" value="{$cicityname}" name="cicityname" id="cicityname" /><br/>
	
	<input type="hidden" name="cityid" id="sdcityid" value="{$sdcityid}"/><br/>
	
	<input type="submit" value="Zoeken" />
</form>

{literal}
<script>

$(document).ready(function() {
	$("#cicityname").autocomplete("/servlets/autocomplete/city.php", {
		delay: 150,
		width: "260px",
		max: 10,
		formatItem: formatCityItem,
		formatResult: formatResult,
		selectFirst: false,
		extraParams: {
			countryid: 1
		}
	});
	function formatCityItem(row) {
		return row[0] + " (" + row[1] + ")";
	}
	function formatResult(row) {
		return row[0];
	}
	$("#cicityname").result(function(event, data, formatted) {
		$("#sdcityid").val(data[1]);
	});
});
</script>
{/literal}
{foreach from=$people item=person}
	<div class="result_photo_container">
		<div class="result_photo" style="background: url(/uploaded/photos/{$person->getString('filename')}) 50% no-repeat" /></div>
		<a class="nick" href="/?page=profile&action=view&user={$person->getString('username')}">{$person->getString('username')}</a>
	</div>
{/foreach}