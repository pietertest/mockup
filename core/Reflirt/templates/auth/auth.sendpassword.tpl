{if $notfound}
	{include file="auth/auth.forgotpassword.tpl"}
{else}
<h2>Je wachtwoord is verzonden</h2>
<br/>
<p>Je wachtwoord is verstuurd naar <b>{$smarty.request.email}</b>. Afhankelijk van de drukte kan het even duren voordat de email aankomt.</p>
<br/>
<div class="arrow"><a href="/?page=noaccess">Inloggen</a>
{/if}
