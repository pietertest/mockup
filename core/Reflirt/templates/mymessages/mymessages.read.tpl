<div class="arrowBack"><a href="/?page=mymessages&action=overview">Terug naar inbox</a></div>
<br/>
<div class="leftPanel">
	<div style="padding: 10px;">
		<div style="background-color: #FFF3D9; margin-bottom: 10px; line-height: 20px;padding: 10px;">
			{assign var=otherUser value=$message->getOtherUser()}
			<span class="compose_label">Van:</span><span> <a href="/?page=userprofile&action=view&id={$otherUser->getKey()}">{$otherUser->getUsername()}</a></span><br/>
			<span class="compose_label">Onderwerp:</span><span> <b>{$message->getSubject()}</b></span><br/>
			<span class="compose_label">Datum:</span><span> {$message->getString('insertdate')|date_format:"%d %B %Y %H:%M"}</span><br/>
			<br/>
			<span><a href="javascript:void(0)" class="reply">Antwoorden</a>&nbsp;&nbsp; <a href="">Verwijderen</a></span>
		</div>
		
	<div style="padding: 10px;">
		{$message->getString('message')} <br/><br/><br/>
		<div class="dots"></div>
		<span><a href="javascript:void(0)" class="reply">Antwoorden</a>&nbsp;&nbsp; <a href="">Verwijderen</a></span>
		
		<br/>
		<br/>
		
		<div class="messageReplyForm hidden" >
			<form id="replyForm" action="/?page=userprofile&action=sendmessage&id={$otherUser->getKey()}" method="post">
				<textarea id="replyText" style="width: 100%; height: 200px" name="message"></textarea>
				{submit value="Verzenden" class="right" style="margin-top: 5px;"}
			</form>
		</div>
	</div>		
</div>

<script type="text/javascript">
{literal}
$(function(){
	var defaultValue = "Bericht...";
	$("#replyText").val(defaultValue).focus(function(){
			if ($(this).val() == defaultValue) {
			$(this).val("");
		}
	});
	$(".reply").click(function(){
		$("#replyForm").parent().show();
		
	});
	ajaxForm("#replyForm", 
		function(data) {
			success("Je bericht is geplaatst");
			clearForm("#noteForm");
		}, 
		function(data) {
			warn(data.fail.message);
		}
	);
})

{/literal}
</script>