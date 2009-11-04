<tr>
	<td class="label">Plaats:</td>
	<td>
		{round_textfield name="cityname" id="cityname" value=$smarty.get.city width="280" }
		<input type="hidden" name="cityid" id="cityid" value="" />
	</td>
</tr>
<tr>
	<td class="label">Naam discotheek</td>
	<td>
		{round_textfield name="disconame" id="disconame" value=$smarty.request.disco width="280" }
		<input type="hidden" name="discoid" id="discoid" value="" />
	</td>
</tr>

{literal}
<script language="JavaScript" type="text/javascript">

var previousCityId = "";
$(function(){
	$("#disconame").autocomplete("/servlets/autocomplete/disco.php", {
		delay: 150,
		width: "260px",
		max: 10,
		formatItem: formatDiscoItem,
		formatResult: formatDiscoResult,
		selectFirst: true,
		mustMatch: true,
		extraParams: {
			countryid: 1,
			cityid: function() {return $("#cityid").val()}
		}
	});
	function formatDiscoItem(row) {
		return row[0];
	}
	function formatDiscoResult(row) {
		return row[0];
	}
	$("#disconame").result(function(event, data, formatted) {
		$("#discoid").val(data[1]);
		$("#cityname").val(data[2]);
		$("#cityid").val(data[3]);
		previousCityId = $("#cityid").val();
	});
	
	$("#cityname").blur(function(){
		if ($(this).val() == "") {
			$("#cityid").val("");
			$("#disconame").val("");
			$("#discoid").val("");
		}
		var currentCityId = $("#cityid").val(); 
		if (previousCityId != currentCityId) {
			$("$disconame").flushCache();
			$("#disconame").val("");
			$("#discoid").val("");
		}
	});
});

</script>
{/literal}  

