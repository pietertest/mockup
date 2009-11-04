
	<script>
	{$js}

	 

	{if !$smarty.env.IS_PRODUCTION}
		var _env = "DEVELOPMENT";	
	{/if}
	{literal}
	var currentPage = '{/literal}{$page}{literal}';
	
	</script>
{/literal}

	<div id="_loading" class="loading"><img src="/images/global/loading.gif" /> <span></span></div>
	
	<div class="main_head">
	
	<div class="main_headborder"></div>
	
		<div class="main_content">
			<div class="top">
			
			<a href="/?page=home"><div class="logo"></div></a>
			
				<div class="login">
					<!--a href="/?page={$smarty.request.page}&action=locale&l=nl&c=NL" style="font-size: 11px;">Nederlands</a>
					<a href="/?page={$smarty.request.page}&action=locale&l=en&c=EN" style="font-size: 11px;">English</a-->
					{if !$smarty.session.user}
					<div class="mymenu">
						<div class="loginPanel">
							<form action="?page=auth&action=login" method="post" class="login">
									<input class="input_login" type="text" name="username" value="logninaam" id="username">
									<input class="input_login" type="password" name="password" value="" id="wachtwoord"><br/>							
									<input  type="submit" name="name" value="Inloggen" class="loginButton"/>
									<a href="/?page=auth&action=forgotpassword" class="small">Wachtwoord vergeten</a>
								&nbsp;
							</form>
						</div>
					</div>
					{else}
					<div class="mymenu">
						<div class="welcome"><a href="/?page=account&action=overview" class="nohover">{$sessionuser->getString('username')|capitalize}</a> <a class="logout noline" href="/?page=auth&action=logout">(Uitloggen)</a></div>
						<div class="navigation">
							Mijn <a class="noline" href="/?page=account&action=overview">Overzicht</a> |
							{* <a class="noline" href="/?page=myspots&action=overview">Spots</a> | *} 
							<a class="noline" href="/?page=myprofile&action=overview">Profiel</a> | 
							<a class="noline" href="/?page=myphotos&action=overview">Foto's</a> | 
							{* <a class="noline" href="/?page=bookmark&action=overview">Favorieten</a> | *} 
							<a class="noline" href="/?page=mymessages&action=overview">Inbox</a> |
							{*<a class="noline" href="/?page=settings&action=overview">Instellingen</a>*} 
						</div>
					</div>
					{/if}
				</div>
	
				<div class="clear"></div>				
				<div class="top_button_home" id="top_button_home"></div>
				<div class="top_m">
					<div class="top_button_search" id="top_button_search"></div>
					{if !$smarty.session.username}
					<div class="top_button_subscribe" id="top_button_subscribe"></div>
					{/if}
				</div>
				<div class="top_r" ></div>
			</div>
			
			
			<!-- Content -->
			<div class="content">
				<h2>{$_title}</h2>
				<div class="messagediv" id="messageDiv">
				{if $_message}
					<div class="{$_status}">{$_message}</div>
				{/if}
				</div>
				<div style="margin-bottom: 20px;"></div>
				{include file=$template}
			</div>
			
			<div class="clear"></div>
			<div class="footer">&copy; 2009 Reflirt.nl,  info@reflirt.nl</div>
		</div>
					
	</div>

</div>



<div style="display: none">
	<a href="http://www.thuismoeder.nl">emeet.nl</a>
</div>



{if $smarty.const.IS_PRODUCTION}

{literal}
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-2551281-1");
pageTracker._trackPageview();
} catch(err) {}</script>
{/literal}

{/if}
