<h2>Profiel van <a href="/?page=userprofile&action=view&id={$profileUser->getKey()}" class="noline">{$profileuser->get('username')|capitalize}</a> </h2>
<br/>
<div class="account_panel">
	<div class="profile_picture">
		<div style="background: url({$photoUrl}) 50% no-repeat" ></div>
	</div>
	
	<div class="rest">
		{if $profileUser->profileIsEmpty()}
			{$profileUser->getUsername()} heeft geen profiel ingevuld. <br/>
			{*<a href="" class="small noline">Vraag of {$profileUser->getUsername()} zijn/haar profiel aannvult</a>*}  
		{else}
			<div>
				{if $profileUser->getString('firstname') || $profileUser->getString('lastname')}
					{$profileUser->getString('firstname')} {$profileUser->getString('lastname')}
				{/if}
			</div>
			<br/>
			
			{assign var=city value=$profileUser->getCity()}
			{if $city}
			<div class="arrowed"><strong>Woonplaats:</strong> {$city->getName()}</a></div>
			{/if}
			{assign var=birthdate value=$profileUser->get("birthdate")}
			{if $birthdate}
			<div class="arrowed"><strong>Geboren: </strong> {$birthdate|date_format:"%d %B %Y"}</div>
			{/if}
		{/if}
		
	</div>
</div>	
	
	<div class="userProfileMenu arrowed">
		{if $favorite}
			<span class="gray">{$profileUser->getUsername()} staat al in je klembord</span>
		{else}
			<span><a href="javascript:void(0)" id="remember" class="small noline" title="Plaats {$profileUser->getUsername()} in je klembord, {$profileUser->getUsername()} zal hier niks van merken">In klembord plaatsen</a></span>
		{/if}
	</div>
	
	<div class="clear"></div>

	<br/>
    <br/>
	
	<div "tabs" style="width: 600px;">
	<div id="tabs" style="width: 600px;">
        <ul>
            <li><a id="summaryTab" href="/?page=userprofile&action=summary&id={$id}"><span>Overzicht</span></a></li>
            <li><a id="photosTab" href="/?page=userprofile&action=photos&id={$id}"><span>Fotos</span></a></li>
        </ul>
    </div>
    
		
<script>

var id = '{$profileUser->getKey()}';
var profile_username = "{$profileuser->getString('username')}";
var profile_userid = "{$profileuser->getKey()}";

{literal}
$().ready(function() {
	$('#tabs').tabs({ 
		remote: true,
		cache: true 
	});
	$("#remember").click(function(){
		$.getJSON("/?page=userprofile&action=remember", {id: id}, function(data){
			if(data.success) {
				var parent = $("#remember").parent();
				$("#remember").fadeTo("fast", 0, function() {
					$(this).hide();
					$('<span class="faded">In klembord geplaatst!</span>').appendTo(parent).fadeTo("fast", 1);
				});
			} else {
				scrollToTop();
				warn("Er is is mis gegaan, probeer het nog een keer");
			}
		})
	});
});

</script>{/literal}


