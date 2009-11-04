<h2 class="pageTitle">Mijn Overzicht</h2>
<div class="accountLeft">
	<div class="account_panel">
		<div class="profile_picture">
			{assign var=profile value=$sessionuser->getProfile()}
			<div style="background: url({$profile->getPhotoUrl()}) 50% no-repeat" ></div>
		</div>
		
		<div class="rest">
			{if $smarty.session.firstlogin}
				Welkom {$sessionuser->getString('username')}!
			{else}
			<div>{t}s_welcome{/t} {$sessionuser->getString('username')}!</div>
			{/if}
			
			{if $sessionuser->getUsername() == "pieter"}
				<br/>
				members: {$members}
				<br/>
			{/if}
			
			{if $oproepen|@count == 0}
				<div class="arrow">Nu een <a href="/?page=zoeken&action=compose" class="noline">oproep plaatsen</a></div>
			{/if}
			{assign var=aantal value=$messages|@count}
			{if $aantal > 0}
				<div class="arrowed">Je hebt <a class="noline" href="?page=mymessages&action=overview">{$aantal} ongelezen {if $aantal == 1}bericht{else}berichten{/if}</a></div>
			{/if}
			
			{* Hint *}
			{if !$sessionuser->hasPhoto()}
			<div class="arrow"><span class="colored">Tip:</span> <a href="/?page=myprofile" class="noline">Plaats een foto</a> voor beter resultaten</div>
			
			{/if}
		</div>
	</div>
	
	<br/>
	<br/>
	

	<h2>
		Mijn Oproepen
		{if $totalNewReactions}
			({$totalNewReactions|@singular:" een nieuwe match":" nieuwe matches"}!<span class="small"> (sinds je laatste login)</span>)
		{/if}
	</h2>
	<div class="dots"></div>
	<div style="margin-top:7px;"><a href="/?page=zoeken&action=compose" class="noline arrow" >Nieuwe oproep plaatsen</a></div>
	<br/>
	</br>
	{foreach from=$oproepen item=item key=key }
	{assign var=oproep value=$item.oproep}
	{assign var=reactions value=$item.reactions}
	<div class="search_result" id="add_{$oproep->getKey()}">
		<div class="panel">
			<div class="search_result_content" >
				<div class="header">
					{assign var=resultaten value=$oproep->getResultaten()}
					
					{if $resultaten|@count}
					<a class="noline new_results" href="/?page=zoeken&action=myresults&id={$oproep->getKey()}">{$resultaten|@singular:" nieuw resultaat":" nieuwe resultaten"}!</a>
					{/if}
					<a class="title" href="/?page=zoeken&action=myresults&id={$oproep->getKey()}">{$oproep->get("title")|truncate:35}</a>
				</div>
				
				{if $item.newReactions}
					<div class="reactionLink" key="{$oproep->getKey()}">{$item.newReactions|@singular:" Nieuw reactie":" Nieuwe reacties"}!</div>
				{elseif $reactions}
					<div class="reactionLink" key="{$oproep->getKey()}">{$reactions|@singular:"  Reactie":" Reacties"}</div>
				{/if}
				<div><span class="descr">{$oproep->getHtml("onderschrift")}</span> <span class="small">in <a href="/?page=zoeken&category={$oproep->getCategory()}" class="noline">{$oproep->getCategoryLabel()}</a></span></div>
			</div>
		</div>
		<div class="nav">
			<a class="noline" href="/?page=zoeken&action=myresults&id={$oproep->getKey()}">Bekijk resultaten</a>
			<span class="separator">|</span>		
			<a class="noline" href="/?page=zoeken&action=edit&id={$oproep->getKey()}">Wijzigen</a>
			<span class="separator">|</span>		
			<a class="noline" href="javascript:deleteAdd({$oproep->getKey()})" key="{$oproep->getKey()}">Verwijderen</a>
		</div>
		
		<br/>
		{if $reactions}
			<div class="reactionsContainer hidden" id="reactions_{$oproep->getKey()}" >	
				{foreach from=$reactions item=item}
					{assign var=reaction value=$item.reaction}
					{assign var=user value=$reaction->getFromUser()}
					{cycle values=" even, odd" assign=$class name=row print=false}
					{cycle name=row assign=class}
					{*
					<tr>
						<td width="60" valign="top" height="60">
							<div style="background: url({$user->getPhotoUrl()}) 50% no-repeat; height: 40px; width: 40px;" ></div>
						</td>
						<td>
							<div class="" style="font-size: 11px; color: gray;">
								{assign var=insertdate value=$reaction->get("insertdate")}							
								{if $item.isnew}<a nohref>Nieuw!</a>{/if}
								Op {$insertdate|date_format:"%d %B %Y %H:%M"} zei <a class="noline small" href="">{$user->get("username")}</a>:
							</div>
							{$reaction->get("message")}
							<br/>
							<br/>
							<a href="/?page=userprofile&action=view&id={$user->getKey()}" class="small noline">Reageren</a>
						</td>
					</tr>
					
					*}
					
					<table border="0" cellpadding="0" cellspacing="0" width="400">
						<tr>
							<td valign="top" width="50">
								<div style="background: url({$user->getPhotoUrl()}) 50% no-repeat; height: 40px; width: 40px; margin-top: 20px; border: 3px solid #F5F5F5;" ></div>
							</td>
							<td width="20">
								<div class="balloonHook{$class}">&nbsp;</div>
							</td>
							<td valign="top">
								{roundedCornersBlue}
								
									<div class="" style="font-size: 11px; color: gray; margin-bottom: 4px;">
										{assign var=insertdate value=$reaction->get("insertdate")}							
										{if $item.isnew}<a nohref>Nieuw!</a>{/if}
										Op {$insertdate|date_format:"%d %B %Y %H:%M"} zei <a class="noline small" href="/?page=userprofile&action=view&id={$user->getKey()}">{$user->get("username")}</a>:
									</div>
									
									<div class="message">{$reaction->get("message")}</div>
								
									<div>
										<a href="/?page=userprofile&action=view&id={$user->getKey()}" class="small noline">Reageren</a>
									</div>
								
								{/roundedCornersBlue}
							</td>
						</tr>
					</table>
				{/foreach}
			</div>
			<div class="clear"></div>
		{/if}
	
	</div>
	{foreachelse}
		Je hebt nog geen oproepen geplaatst. <a href="/?page=zoeken&action=compose" >Nu een oproep plaatsen!</a>
	{/foreach}
</div>


<div class="width: 300px;">
	<div class="accountFavorites">
		<h2>Klembord voor gebruikers</h2>
		<div class="dots"></div>
		<br/>
		{foreach from=$favoriteUsers item=bookmark}
			{assign var=user value=$bookmark->getOtherUser()}
			<div class="favoriteUser">
				<img src="{$user->getPhotoUrl()}" width="50" height="50"/><br/>
				<a class="small noline" href="/?page=userprofile&action=view&id={$user->getKey()}">{$user->getUsername()}</a>
				<div class="delete"><a key="{$bookmark->getKey()}" class="small noline" href="javascript:void(0)">Verwijderen</a></div>
			</div>
		{foreachelse}
			<div class="small center">
				(Je hebt geen favorieten)
				<br/><br/>
				<span class="gray">Hier kun je andere gebruikers plaatsen, zodat je er snel bij kan.Dit kan via de "Onthouden" link op zijn/haar profielpagina. Hij/zij zal hier niets van merken.</span>
			</div>
		{/foreach}
	</div>
	<div class="accountFavorites">
	<br/>
	<br/>
					
		<h2>Klembord voor oproepen</h2>
		<div class="dots"></div>
		<br/>
		{foreach from=$favoriteOproepen item=fav}
			{assign var=oproep value=$fav->getOproep()}		
			{assign var=user value=$oproep->getUser()}
			
			
			<div class="favoriteOproep">
				{include file="oproep/incl/oproep.tpl"}
				<div class="delete"><a key="{$fav->getKey()}" class="small noline" href="javascript:void(0)">Verwijderen</a></div>
			</div>
			
			<div class="clear"></div>
		{foreachelse}
			<div class="small center">
				(Je hebt geen favorieten oproepen) <br/><br/>
				<span class="gray">Hier kun je oproepen van andere gebruikers plaatsen, zodat je er later weer snel bij kan. Dit kan via de link "Onthouden" in de oproep.)</span>
			</div>
		{/foreach}
	</div>
	
</div>



	
<script>
{literal}
$().ready(function(){
	jQuery(".reactionLink").click(function(){
		var id = $(this).attr("key");
		$("#reactions_" + id).toggle("fast");
	});

	$(".favoriteUser .delete > a").click(function(){
		$link = $(this);
		$.getJSON("/?page=account&action=deletefavuser", {id: $link.attr("key")}, function(data) {
			processJsonResponse(data, {
				success: function(data){
					$link.parents(".favoriteUser").fadeTo("fast", 0, function() {$(this).hide("fast");});	
				} 
			});
		});
	});

	$(".favoriteOproep .delete > a").click(function(){
		$link = $(this);
		$.getJSON("/?page=account&action=deletefavadd", {id: $link.attr("key")}, function(data) {
			processJsonResponse(data, {
				success: function(data){
					$link.parents(".favoriteOproep").fadeTo("fast", 0, function() {$(this).hide("fast");});	
				} 
			});
		});
	});
});

function deleteAdd(id) {
	var $add = $("#add_" + id);
	if(confirm("Weet je zeker dat je deze oproep wilt verwijderen?")) {
		$.getJSON("/?page=account&action=deleteadd", {id: id}, function(data) {
			$add.slideUp("slow");
		});
	}
}
	

{/literal}

</script>