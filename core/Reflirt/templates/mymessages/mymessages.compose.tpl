<h1>Niew bericht</h1>
<a href="/Mijn/Berichten">Mijn berichten</a>
<br/>
<br/>
{literal}
<style>
.message_label {
	width: 100px;
}
.message_body {
	width: 400px;
	height: 150px;
}
input.message_field {
	width: 300px;
}

</style>
{/literal}


<form method="post">
	<input type="hidden" name="page" value="message" />
	<input type="hidden" name="action" value="send" />
	
	<span class="message_label">Naar:</span><input type="text" name="rcpt" class="message_field" value="{$rcpt}" /><br/>
	<span class="message_label">Onderwerp:</span><input type="text" name="subject" class="message_field" value="{$subject}" /><br/>
	<br/>
	<span class="message_label">Bericht:</span><br/>
	<textarea class="message_body" name="body">{$body}</textarea><br/>		
	<input type="submit" value="Verzenden" />
</form>