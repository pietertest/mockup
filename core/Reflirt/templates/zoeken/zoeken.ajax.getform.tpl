	<table border="0" width="100%" class="searchFormTable">
		{include file=zoeken/forms/zoeken.$shortname.tpl}
		<tr>
			<td colspan="2">
				{include file="spot/forms/incl/addspot.js.tpl"}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				
				<input type="hidden" name="lat" value="" />
				<input type="hidden" name="lng" value="" />
				
				<input type="button" onclick="getCoords()" value="Zet op de kaart" />
				<input type="submit" disabled="true" id="submitbutton"`value="Spot opslaan"/>
			</td>
		</tr>
	</table>

{literal}
<script language="JavaScript" type="text/javascript">

$().ready(function() {
	$("#cityname").autocomplete("/servlets/autocomplete/city.php", {
		delay: 150,
		width: "260px",
		max: 10,
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
		return row[0].replace(/(<.+?>)/gi, '');
	}
	$("#cityname").result(function(event, data, formatted) {
		$("#cityid").val(data[1]);
		$(this).focus();
	});
});

</script>
{/literal}

</form>