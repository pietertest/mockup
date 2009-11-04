<h1>Zoekopdrachten overzicht</h1>
<br/>
<br/>
{foreach from=$zoekopdrachten item=zoekopdracht}
	Category: {$zoekopdracht->getString(category)}<br/>
{/foreach}

<br/>
<input type="text" name="category" value="{$category}" />