<a href="javascript:history.go(-1);">Terug</a>
<br/>
<br/>


<div class="callDetails">
	<span class="small gray right">Geplaatst op {$oproep->get("insertdate")|date_format:"%d %B %Y"}, in <a href="/?page=zoeken&action=overview&simplesearch=1&category={$oproep->get("category")}">{$oproep->getHtml("category")}</a></span><span>Oproep van <a href="/?page=userprofile&action=view&id={$oproepUser->getKey()}">{$oproepUser->get("username")}</a></span>
	<br/>
	<br/>
	<div class="bigBalloonGray">
		{assign var=user value=$oproep->getUser()}
		<div class="picture" style="background: url({$user->getPhotoUrl()}) no-repeat"></div>
		<h1 class="colored">{$oproep->getTitle()}</h1>
		<div>Waar & wanneer:</div>
		<div><h2>{$oproep->getHtml("onderschrift")}</strong></h2>
	</div>
	
	<div class="quotesLeft"></div>
	<div class="callMessage">
		{$oproep->get("message")}
	</div>
	<div class="quotesRight"></div>
	
	<br/>
	<br/>
	
	<div class="dots"></div>
	<div style="float: left; width: 280px;" class="small">
		{if !$sessionuser}
			<span><a href="/login" id="remember" class="small" title="Onthoudt deze oproep zodat je er later snel naar terug kan. {$oproepUser->get("username")} zal hier niets van merken">In klembord plaatsen</a></span>
				&nbsp;&nbsp;&nbsp;
		{else}
			{if $favorite}
				<span class="gray">Deze oproep staat al in je klembord</span>
			{else}
				<span><a href="javascript:void(0)" id="remember" class="small" title="Onthoudt deze oproep zodat je er later snel naar terug kan. {$oproepUser->get("username")} zal hier niets van merken">In klembord plaatsen</a></span>
				&nbsp;&nbsp;&nbsp;
			{/if}
		{/if}
	</div>
	{*<a href="" class="small" title="Stuur deze oproep door naar een vriend of vriending">Doorsturen naar vriend(in)</a>*}
	<br/>
	<br/>
	Is dit bericht voor jou bedoeld? Stuur <a href="/?page=userprofile&action=view&id={$oproepUser->getKey()}">{$oproepUser->get("username")}</a> dan een berichtje!
	<br/>
	<br/>
	<br/>

	{if $sessionuser}
		{if $oproepReaction}
			<div class="messagediv">
				<div class="warn">Let op, je hebt al een keer op deze oproep gereageerd</div>
			</div>
	
		{/if}
	
		<form action="/" method="post">
			<input type="hidden" name="page" value="oproep" />
			<input type="hidden" name="action" value="reageer" />
			<input type="hidden" name="id" value="{$id}" />
			Bericht:<br/>
			<textarea class="callViewTextarea" name="message"></textarea>
			<br/>
			{submit value="Versturen" class="right"}
		</form>
	{else}
		<a href="/login" >Login op te reageren</a> 
	{/if}
	
</div>

<div class="callDetailsRight">
	
</div>

{if $sessionuser}

	<script type="text/javascript">
	var id = '{$oproep->getKey()}';
	{literal}
	
	$(function(){
		$("#remember").click(function(){
			$.getJSON("/?page=oproep&action=favorite", {id: id}, function(data){
				if(data.success) {
					var parent = $("#remember").parent();
					$("#remember").fadeTo("fast", 0, function() {
						$(this).hide();
						$('<span class="faded">Deze oproep is op je klembord geplaatst!</span>').appendTo(parent).fadeTo("fast", 1);
					});
				} else {
					warn("Er is is mis gegaan, probeer het nog een keer");
				}
			})
		});
	})
	</script>
	{/literal}

{/if}