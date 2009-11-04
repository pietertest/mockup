
<div class="right" style="width: 300px;	">
	<h2>Laatste oproepen</h2>
	<div class="dots"></div>
	<br/>
	{foreach from=$laatsteOproepen item=oproep}
		{assign var=user value=$oproep->getUser()}
		{include file="oproep/incl/oproep.tpl"}
	{/foreach}  
	
</div>	

<div id="advanced" class="searchForm">
	<form action="/?page=zoeken&action=save" method="post" id="advancedForm">
		<input type="hidden" name="id" value="{$id}" />
		{roundedCorners} 
		<table width="100%" border="0" class="searchFormTable">
			<tr>
				<td class="label">Titel van de oproep*</td>
				<td>{round_textfield id="title" name="title" value=$title width="280" }</td>
			</tr>
			<tr>
				<td class="label">Ik kwam mijn flirt tegen in:</td>
				<td>{round_select id="category" name="category" options=$categories selected=$category width="280" }</td>
			</tr>
			<tr id="spacer">
				<td colspan="2" >&nbsp;</td>
			<tr>
			
			{include file="spot/spot.ajax.getformfieldsforsearcher.tpl"}
			
			<tr id="newSpot" style="display: none">
				<td colspan="2" >Nieuwe spot toevoegen:</td>
			<tr>
			
			<tr>
				<td class="label">
					<label for="startdate">Datum*:</label>
				</td>
				<td>
					{assign var=formattedStartdate value=$startdate|date_format:"%d-%m-%Y"}
					{round_textfield name="startdate" tabindex="2" id="startdate"  tfClass="date-pick dp-applied" class="date-pick dp-applied" value=$formattedStartdate}
					{if !$startdate && !$enddate}
						{assign var=checkRegelmatig value="checked"}
					{/if}
					<input {$checkRegelmatig} style="margin: 5px 0 0 10px" type="checkbox" name="regelmatig" value="regelmatig" id="regelmatig" /><label for="regelmatig">Regelmatig/vaker</label>
				</td>
			</tr>
			<tr>
				<td>Geslacht:</td>
				<td class="sex">
					<div class="label">Hij/zij: </div>
					
					
					{if $sex == ""}
						{html_radios name="sex" checked="3" options=$checkboxesSex separator="&nbsp;"}
					{else}
						{html_radios name="sex" checked=$sex options=$checkboxesSex separator="&nbsp;"}
					{/if}
				</td>
			</tr>
			<tr>
				<td>Omschrijving van het moment*:</td>
				<td class="message">
					<textarea class="message" name="message">{$message}</textarea>
				</td>
			</tr>
		</table>
		 
		{/roundedCorners}
		<br/>
		<div class="dots"></div>
		<input type="hidden" name="confirmNewSpot" value="0" />
		{submit value="Oproep plaatsen" class="submit" }
	</form>
</div>

<div class="clear"></div>
<script>
var formFieldsUrl = "getFormFieldsForSearcher";
</script>
{include file="zoeken/incl/zoeken.js.tpl"}

<script>

{literal}

$(function() {
	ajaxForm("#advancedForm", function(data) {
		window.location.href = data.newLocation;
	}, function(data) {
		warn(data.fail.message);
		var field = data.fail.field;
		if(field) {
			$(this).find("[name=" + field + "]").focus();
		}
	});
})

{/literal}
</script>
