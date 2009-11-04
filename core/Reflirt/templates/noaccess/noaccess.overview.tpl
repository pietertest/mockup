<h2>Geen toegang</h2>
	
<div class="warn">Om deze pagina te kunnen zien dien je ingelogd te zijn.</div>
		
<br/>

{roundedCornersBlue style="width: 315px; float: left"}
	<form action="?page=auth&action=login" method="post" class="loginForm">
		<h2>Inloggen</h2>
		<br/>	
		<table border="0" >
			<tr>
				<td class="label">Gebruikernaams:</td>
				<td class="value" ><input class="input_login" type="text" name="username" id="username"></td>
			</tr>
			<tr>
				<td class="label">Wachtwoord:</td>
				<td class="value"><input class="input_login" type="password" name="password" id="wachtwoord"></td>
			</tr>
			<tr>
				<td colspan="2" align="right">							
					<input type="submit" name="name" value="Inloggen" />
					<br/>
					<a class="small" href="/?page=auth&action=forgotpassword">Wachtwoord vergeten?</a>
					<br/>
					<br/>
				</td>
			</tr>
		</table>
	</form>
{/roundedCornersBlue}

{roundedCornersBlue style="float: right; width: 570px;"}
<h2>Nog geen lid?</h2>
<br/>
{include file="subscribe/incl/subscribe.incl.tpl"}
{/roundedCornersBlue}