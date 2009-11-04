<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Reflirt.nl</title>
<style type="text/css" media="all">
  @import url("/css/style.css");
</style>

{literal}
<link rel="stylesheet" type="text/css" href="/css/main.css.php" />

<script type="text/javascript" src="javascript/main.js.php" />

{php}
$bla = array({1} => "Man", {2} => "Vrouw");
print_r $bla;

{/php}


<script>
{/literal}{$js}{literal}
</script>

<script>
function init() {
	for(listener in _onloadListeners) {
	 eval(_onloadListeners[listener]);
 	}
}

$(document).ready(function() {
	$("#username").focus(function() {
		$(this).val("");
	});
	$("#wachtwoord").focus(function() {
		$(this).val("");
		//$(this).attr("type", "password");
	});
});

</script>
{/literal}
</head>
<body onload="init()">

<a href="/?page={$smarty.request.page}&action=locale&l=nl&c=NL">Nederlands</a>
<a href="/?page={$smarty.request.page}&action=locale&l=en&c=EN">English</a>

<br/>

<div class="centered" style="border: 1px solid black;">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="3" class="top" align="left">
				<div class="login_top">&nbsp;
	
				</div>
				
				<form action="?page=auth&action=login" method="post" class="login_bottom">
					{if !$smarty.session.username}
						<input type="text" name="username" value="logninaam..." id="username">
						<input type="password" name="password" value="" id="wachtwoord">
						<input type="submit" name="name" value="Inloggen" />
					{else}
						{t}s_hoi{/t} {$smarty.session.username}! (<a href="?page=auth&action=logout">{t}s_logout{/t})</a> | <a href="?page=settings&action=overview">{t}s_settings{/t} </a>  
					{/if}
					&nbsp;
				</form>
				{if $feedbackType}
					<div class="messagediv {$feedbackType}">
						{$feedbackMessage}	
					</div>
				{/if}
				
				<div class="navigation">
					<a href="/?page=home" class="space-right">Home</a>
					|<a href="/?page=search" class="space-left">Zoeken</a>
					{if $smarty.session.username}
					|<a href="/?page=searchercall" class="space-left">Mijn Zoekopdrachten</a>
					|<a href="/?page=searchercall&action=intro" class="space-left">Nieuwe zoekopdracht</a>
					|<a href="/?page=account" class="space-left">Overzicht</a>
					|<a href="/?page=profile" class="space-left">Profiel</a>
					|<a href="/?page=photos" class="space-left">Foto's</a>
					|<a href="/?page=bookmark" class="space-left">Bookmarks</a>
					{else}
					|<a href="/?page=subscribe" class="space-left">Aanmelden</a>
					{/if}
				</div>
		
			</td>
		</tr>
			
		<tr>
			<td class="table_left"></td>
			<td class="table_middle" align="left">{include file=$template}</td>
			<td class="table_right"></td>
		</tr>
		
		<tr>
			<td colspan="3" align="center">FOOT</td>
		</tr>
	</table>
</div>
