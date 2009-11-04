<div class="welcomeTop">
	<div class="dude"></div>
	
	<div class="welcomeContainer">
		<div class="welcomeBalloon"><div>Je vindt elkaar hier!</div></div>
		<div class="clear"></div>
		<div class="welcomeText">
			<div>Elkaar te snel <strong>uit het oog verloren</strong>? Of wil je diegene nog iets laten weten?
			Laat een berichtje achter! 
			</div>  
		</div>	
		
		<div class="welcomeSearchLabel">Waar heb je elkaar ontmoet/gezien:</div>
		<div class="welcomeSearchBackground">
			<form action="/" method="get">
				<input type="hidden" name="page" value="zoeken" />
				<input type="hidden" name="action" value="overview" />
				<input type="hidden" name="simplesearch" value="1" />
				
				{round_textfield value=$example width="240" name="q" id="example"} 
				{submit value="Zoeken" class="welcomeSubmit"}
			</form>
		</div>
	</div>
	
	<div class="girl"></div>
	
	<div>Nog geen lid? <a href="/aanmelden">Meld je aan!</a></div>
	
	
	<a href="/?page=zoeken&action=compose"><div class="welcomePlaceAdd"></div></a>
	<div class="welcomeOrLabel">Of</div>
</div>

<div class="clear"></div>

<div class="homeRemeet">
	<h2 class="colored" title="Vind die leuke flirt van gisteren terug!">Laatste oproepen</h2>
	
	<div class="dots"></div>
	
	<a class="noline small right" href="/?page=zoeken&action=overview&simplesearch=1">Door de oproepen bladeren</a>
	<br/>
	<br/>
	{foreach from=$laatsteOproepen item=oproep}
	{assign var=user value=$oproep->getUser()}
		{include file="oproep/incl/oproep.tpl"}
	{/foreach}
</div>
{*
<div class="homePremeet">
	<h2 class="colored left" title="Zoek iemand om samen iets gezelligs mee te gaan doen, zoals een avondje bioscoop of theater">Samen iets leuks doen</h2>
	<div class="homeNew"">Nieuw!</div>
	<div class="clear"></div>
	<div class="dots"></div>
	 
	<a class="noline small right" href="">Door de oproepen bladeren</a>
	<br/>
	<br/>
	<div class="addSmall">
		<div class="addPicture" style=""></div>
		<div class="addTitle"><a href="">Ongeloofelijk maar waar!</a></div>
		<div class="addSummary">Paradiso, Amsterdam, 2 juni 2008</div>
		<div class="addDescription">Ik was er met twee hete blonde damens...</div>
	</div>
	<div class="addSmall">
		<div class="addPicture" style=""></div>
		<div class="addTitle"><a href="">Lekker dansen!</a></div>
		<div class="addSummary">Paradiso, Amsterdam, 2 juni 2008</div>
		<div class="addDescription">Ik was er met twee hete blonde damens...</div>
	</div>
	<div class="addSmall">
		<div class="addPicture" style=""></div>
		<div class="addTitle"><a href="">De treinvertrading</a></div>
		<div class="addSummary">Paradiso, Amsterdam, 2 juni 2008</div>
		<div class="addDescription">Ik was er met twee hete blonde damens...</div>
	</div>
</div>
*}

<div class="homeFlirters">
	<h2 class="colored" title="Vind die leuke flirt van gisteren terug!">Flirters</h2>
	
	<div class="dots"></div>
	<br/>
	<br/>
	{foreach from=$latestUsers item=user}
		<div style="float: left; width: 80px; overflow: hidden; height: 80px; text-align: center; ">
			<a href="{$user->getUrl()}"><div class="addPicture" style="background: url({$user->getPhotoUrl()}) no-repeat; margin-left: 16px; float: left;"></div></a>
			<a class="small" href="/?page=userprofile&action=view&id={$user->getKey()}" >{$user->getUserName()}</a>
		</div>
	{/foreach}
</div>

<div class="homePopularContainer">
	<h2 class="colored left" >Populaire flirtspots</h2>
	<div class="clear"></div>
	<div class="dots"></div>
	<br/>
	<br/>
	<div class="homePopular">
		{foreach from=$populairCategories item=cat}
			{if $cat.spots}
				<strong><a href="/?page=zoeken&action=overview&category={$cat.category}">{$cat.name}</a></strong><span class="small gray"> - {$cat.descr}</span>
				</br>
				{foreach from=$cat.spots item=spot}
					<div class="arrow"><a class="noline" href="/?page=zoeken&action=overview&spotid={$spot.spotid}&category={$spot.category}">{$spot.spotname}{if $spot.cicityname}, {$spot.cicityname}{/if}</a></div>
				{/foreach}
				<br/>
				<br/>
			{/if}
			
		{/foreach}
	</div>

</div>

<div class="clear"></div>
<br/>

{*
<h2>Ga je mee?</h2>
Iemand meenemen naar een concert of een bioscoop? Hier hebben we wat suggestie op een rij gezet
*}

<script type="text/javascript">
var initVal = "{$example|escape:'javascript'}";
{literal}

$(function(){
	//initVal = $("#example").val();
	$("#example").click(function(){
		if($(this).val() == initVal) {
			$(this).val("");
		}
	}).blur(function(){
		if($(this).val() == "") {
			$(this).val(initVal);
		}
	});
	$("#example").parents("form").submit(function(){
		if($("#example").val() == initVal) {
			$("#example").val("");
		}
	});
});
{/literal}
</script> 
