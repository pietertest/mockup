<div class="userProfileSummary">	
	
<!-- Photos -->
	<div style="width: 400px;">
		<div class="myphotos">
			<h2>Foto's</h2>
		</div>
		{roundedCornersBlue width="400"}
			{foreach from=$photos item=photo}
				<img src="{$photo->getUrl()}" width="50"/>
			{foreachelse}
				<div class="noItems">(Geen fotos)</div>
			{/foreach}
		{/roundedCornersBlue}
	</div>
	
	<br/>
	
	
	<!-- Messages -->
	<h2>Bericht sturen</h2>
	<br/>
	Laat een bericht achter (alleen {$profileUser->get("username")} kan dit lezen):
	<form action="/" method="post" id="noteForm">
		<input type="hidden" name="page" value="userprofile" />
		<input type="hidden" name="action" value="sendmessage" />
		<input type="hidden" name="id" value="{$profileuser->getKey()}" />
		<textarea name="message" class="messageInput"></textarea>
		<br/>
  		<input type="submit" value="Bericht sturen" class="messageSubmit" />
	</form>
  
</div>

<script type="text/javascript">
{literal}

$(function() {
	ajaxForm("#noteForm", 
		function(data) {
			success("Je bericht is geplaatst");
			$().clearForm("#noteForm");
		}, 
		function(data) {
			warn(data.fail.message);
		}
	);
});
{/literal}
</script>