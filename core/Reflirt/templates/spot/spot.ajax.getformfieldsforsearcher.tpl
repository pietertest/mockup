{foreach from=$fields item=field}
<tr dynamicField="true">
	<td class="label" id="cityLabel">{$field.label}{if $showMandatoryIndicator && $field.mandatory}*{/if}: </td>
	<td>
		{if $entity}
			{assign var=id value=$entity->get($field.idValue)}
			{assign var=value value=$entity->get($field.valueValue)}
		{/if}
		
		{assign var=tempMustMatch value=false}
		
		{if $field.autocomplete}
			{if $field.autocomplete == "city"}
				{* City moet altijd bestaan *}
				{assign var=tempMustMatch value=true}
			{/if}
			{round_autocomplete selectFirst=false mustMatch=$tempMustMatch systemid=$id value=$value id=$field.id name=$field.name width="280" autocomplete=$field.autocomplete autocompleteParams=$field.autocompleteParams resultId=$field.resultId dependsOn=$field.dependsOn makeNew=true}
			{if $field.autocomplete == "spot"}
				<div id="newSpotContainer" class="hidden" style="clear: both">
					Er bestaat nog geen spot met de naam '<strong></strong>'.
					<br/>Wil je deze toevoegen aan het systeem?
					<input type="button" value="Spot toevoegen" id="addNewSpot"/>
				</div>
			{/if}
			
		{else}
			{round_textfield id=$field.id name=$field.name width="280"  }
		{/if}
	</td>
</tr>
{/foreach}

<script type="text/javascript">

{literal}

$(function(){
	$("#addNewSpot").click(function(){
		var spotName = $("[name=spotname]").val();
		makeNewSpot(spotName);
	});

	$("[name=spotname]").onMatch(function(){
		hideNewSpotRequest();
	}).onNoMatch(function(value){
		if(value == "") {
			hideNewSpotRequest();
			return;
		}
		showNewSpotRequest(value);
		//
	});	
});

function hideNewSpotRequest() {
	$("#newSpotContainer").slideUp("slow");
}
function showNewSpotRequest(spotName) {
	$("#newSpotContainer").find("strong:first").text(spotName).end().slideDown("fast");
}

function makeNewSpot(spotName) {
	$.getJSON("/?page=spot&action=addNewSpot", 
		{
			spotname: spotName,
			cityid: function(){ return $("#cityid").val()},
			cat: function(){ return $("#category").val()}
		}, function(data){
			if(data.success) {
				$("[name=spotname]").loadById(data.id);
				hideNewSpotRequest();
			} else if(data.fail) {
				warn(data.fail.message);
			}
		}
	);
	
}




</script>{/literal}	


