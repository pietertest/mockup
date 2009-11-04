Agendapunt toevoegen?
<form action="/" method="post">
	<input type="hidden" name="page" value="agenda" />
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="spotid" value="{$smarty.get.id}" />
	<input name="title" /><br/>
	<input type="text" name="datestart" id="datestart" /> <br/>
	 tot <input type="text" name="dateend" id="dateend" /> <br/><br/>
	<textarea name="description"></textarea><br/>
	<input type="submit" value="Agendapunt aanmaken" />
</form> 
{literal}
<script>
$().ready(function(){
	$("#datestart").datePicker({clickInput: true});
	$("#dateend").datePicker({clickInput: true});
	
});
{/literal}
</script>
<br />
<br />
<br />
<br />
<div class="agendas">
	<h1>Coming..</h1>
	{foreach from=$comingagendas item=agenda}
	<div class="agenda_item">
		<b><a href="/?page=agenda&action=view&id={$agenda->getKey()}">{$agenda->get('title')}</a></b>, {$agenda->getHTML("date")}<br/>
		<div style="font-size: 11px;">
			Aangemaakt door <a href="/?page=userprofile&action=view&user={$agenda->get('username')}">{$agenda->get('username')}</a>
		</div>
		<br/>
		{$agenda->get('description')} 
		<br/>
		<br/>
	</div>
	{/foreach}
</div>

<div class="agendas">
	<h1>Afgelopen</h1>
	{foreach from=$passedagendas item=agenda}
	<div class="agenda_item">
		<b><a href="/?page=agenda&action=view&id={$agenda->getKey()}">{$agenda->get('title')}</a></b>, {$agenda->getHTML("date")}<br/>
		<div style="font-size: 11px;">
			Aangemaakt door <a href="/?page=userprofile&action=view&user={$agenda->get('username')}">{$agenda->get('username')}</a>
		</div>
		<br/>
		{$agenda->get('description')} 
		<br/>
	</div>
	{/foreach}
</div>