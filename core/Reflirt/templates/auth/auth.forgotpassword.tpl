<h2>Wachtwoord vergeten?</h2>

<br/>
{roundedCornersBlue}
<br/>
<div>
	<p>Vul hieronder je email adres in en wij sturen je het wachtwoord toe</p>
</div>

{if $notfound}
<p style="color: red">Er komt geen gebruiker voor in onze database met het email adres <b>{$smarty.request.email}</b></p>
{/if}


<form action="/" method="post">
	<input type="hidden" name="page" value="auth" />
	<input type="hidden" name="action" value="sendpassword" />
	<br/>
	E-mail: <input type="text" name="email" value="{$smarty.request.email}" />
	<input type="submit" value="Wachtwoord aanvragen" />
</form>
{/roundedCornersBlue}
<div class="arrow">Nog geen lid?  <a href="/?page=subscribe">Registreer je hier</a>
