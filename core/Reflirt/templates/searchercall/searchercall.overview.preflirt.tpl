{if !$searchers_preflirt}
Je hebt nog geen oproepen<br/>
<br/>
<a href="/?page=searchercall&action=intro&type=2">&gt; Nieuw oproep plaatsen</a>
{/if}
{foreach from=$searchers_preflirt item=oproep}
	<div class="oproepen">

	{assign var=category value=$oproep->getString('category')}
	{assign var=shortname value=$oproep->getShortCatName($category)}
	{include file=searchercall/overview/$shortname.overview.tpl}

	{counter assign="resultid"}
	<br/>
	<input type="button" onclick="remove({$oproep->getKey()}, '{$oproep->getString('category')}')" value="Verwijderen" />
	<input type="button" onclick="modify({$oproep->getKey()}, '{$oproep->getString('category')}')" value="Wijzigen" />
	<br/>
	<br/>
	</div>
	
{/foreach}