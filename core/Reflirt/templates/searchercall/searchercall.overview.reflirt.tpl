{if !$searchers_reflirt}
Je hebt nog geen oproepen<br/>
<br/>

{/if}
<a href="/Mijn/Zoeken" class="space-left">&gt; Nieuw oproep plaatsen</a>
<a href="/?page=searchercall&action=intro&type=1">&gt; Nieuw oproep plaatsen</a>
{foreach from=$searchers_reflirt item=oproep}
	<div class="oproepen">

	{counter assign="resultid"}
	{assign var=category value=$oproep->getString('category')}
	{assign var=shortname value=$oproep->getShortCatName($category)}
	{include file=searchercall/overview/$shortname.overview.tpl}

	<br/>
	<input type="button" onclick="remove({$oproep->getKey()}, '{$oproep->getString('category')}')" value="Verwijderen" />
	<input type="button" onclick="modify({$oproep->getKey()}, '{$oproep->getString('category')}')" value="Wijzigen" />
	<br/>
	<br/>
	</div>
	
{/foreach}