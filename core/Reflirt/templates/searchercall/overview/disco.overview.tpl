<h2 id="333">{$oproep->getString('cicityname')}{if $oproep->getString('ddisconame')}, {$oproep->getString('ddisconame')}{/if} ({$oproep->getFriendlyName()})</h2>
 <b>{$oproep->getString('title')}</b><br/>
Omschrijving: {$oproep->getString('descr')}<br/>
	{assign var=results value=$oproep->getMatches()}
	{if !$results} 
		Geen resultaten
	{else}
		<a nohref id="a_{$resultid}"><b>{$results|@count} Results</b></a><br/>
		<div style="background-color: #F0F5FF" class="resultdiv" id="result_{$resultid}"><br/>
		{foreach from=$results item=result}
			<div class="result_photo" style="background: url(/uploaded/photos/{$result->getString('filename')}) 50% no-repeat" >
				<div style="background: url(/images/global/photo_frame.gif) no-repeat; height: 58px; height: 58px;"></div>
			</div>
			<a class="nick" href="/?page=profile&action=view&user={$result->getString('username')}">{$result->getString('username')}</a>
			</div>
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