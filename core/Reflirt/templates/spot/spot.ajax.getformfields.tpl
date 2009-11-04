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
			{round_autocomplete mustMatch=$tempMustMatch systemid=$id value=$value id=$field.id name=$field.name width="280" autocomplete=$field.autocomplete autocompleteParams=$field.autocompleteParams resultId=$field.resultId dependsOn=$field.dependsOn}
			{if $field.autocomplete == "spot"}
				<div id="newSpotContainer" class="hidden" style="clear: both">
					Deze waarde komt niet vor in het systeem. 
					Je kunt nieuwe waardes toevoegen als je bent ingelogd. 
					Kies een waarde bestaande waarde of laat dit veld leeg.
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
	$("[name=spotname]").onMatch(function(){
		hideNewSpotRequest();
	}).onNoMatch(function(value){
		if(value == "") {
			return;
		}
		showNewSpotRequest(value);
		//
	}).blur(function(){
		if($(this).val() == "") {
			hideNewSpotRequest();
		}
	});	
});

function hideNewSpotRequest() {
	$("#newSpotContainer").slideUp("slow");
}
function showNewSpotRequest(spotName) {
	$("#newSpotContainer").find("strong:first").text(spotName).end().slideDown("fast");
	$("#addNewSpot").click(function(){
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
	});
}




</script>{/literal}	
