<h2 id="333">{$oproep->getString('cicityname')}({$oproep->getFriendlyName()})</h2>
 <b>{$oproep->getString('title')}</b><br/>
Omschrijving: {$oproep->getString('descr')}<br/>
	{assign var=results value=$oproep->getMatches()}
	{if !$results} 
		Geen resultaten
	{else}
		<a nohref id="a_{$resultid}"><b>{$results|@count} Results</b></a><br/>
		<div style="background-color: #F0F5FF" class="resultdiv" id="result_{$resultid}"><br/>
		{foreach from=$results item=result}
			
			{assign var=category value=$oproep->getString('category')}
			{assign var=catFriendlyName value=$oproep->getShortCatName()}
			{include file=searchercall/result/$catFriendlyName.result.tpl}
			
			<hr />
		{/foreach}
		</div>
		{literal}
		<script>
			
			// Expand/collapse result
			$("a#a_{/literal}{$resultid}{literal}").click( function() {
				$("#result_{/literal}{$resultid}{literal}").slideToggle("fast");
			});
			
		</script>
		{/literal}
	{/if}