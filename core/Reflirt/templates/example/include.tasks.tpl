<h2>Wachtende taken:</h2>
{foreach from=$waitingtasks item=task}
<a href="/?page=example&action=deletetask&systemid={$task->getString(systemid)}">x</a> 
<a href="/?page=example&action=updatetask&systemid={$task->getString(systemid)}&status=1">v</a>
<b>Task description:</b> {$task->getString(descr)}<br/>
<br/>
{/foreach}

<h2>Uitgevoerde taken:</h2>
{foreach from=$executedtasks item=task}
<a href="/?page=example&action=deletetask&systemid={$task->getString(systemid)}">x</a> 
<a href="/?page=example&action=updatetask&systemid={$task->getString(systemid)}&status=1">v</a>
<b>Task description:</b> {$task->getString(descr)}<br/>
<br/>
{/foreach}

<hr/>
<h2>Taak toevoegen</h2>
<form action="/">
	<input type="hidden" name="action" value="addtask">
	<input type="hidden" name="page" value="example">
	Omschrijving<br/>
	<textarea name="descr" ></textarea><br/>
	<input type="submit" />	
</form>