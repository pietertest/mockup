<a href="javascript:void(0)" class="removeMessage noline">Verwijderen</a>


<br/>
<br/>

<table cellpadding="0" cellspacing="0" border="0" style="width: 700px;">
	<tr>
		<td class="messageTopLeft"><input type="checkbox" id="checkAll" /></td>
		<td class="messageTopMiddle" width="160"><div class="messageTopHeaderAfzender">Afzender</div></td>
		<td class="messageTopMiddle" width="290"><div class="messageTopHeaderOnderwerp">Onderwerp</div></td>
		<td class="messageTopMiddle"><div class="messageTopHeaderDatum">Datum</div></td>
		<td class="messageTopRight" width="25">&nbsp;</td>
	</tr>
{foreach from=$messages item=message}
	{assign var=messageUser value=$message->getOtherUser()}
	{if $message->getString('viewed') > 0}
		{assign var=class_unread value=""}
	{else}
		{assign var=class_unread value=" message_unread"}
	{/if}
	<tr class="messagerow{$class_unread} {cycle values="even,odd"}"  messageid="{$message->getKey()}"  >
		<td class="rowCheckbox"><input type="checkbox" /></td>
		<td class=""><div><a href="{$messageUser->getHtml('profileurl')}" class="noline">{$messageUser->getString('username')}</a></div></td>
		<td><div class="subject">{$message->getSubject()}</div></td>
		<td><div>{$message->getString('insertdate')|date_format:"%d %B %Y %H:%M"}</div></td>
		<td>&nbsp;</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="5" align="center">
			<br/>
			(Je hebt geen berichten)
		</td>
{/foreach}
</table>
<br/>
<div><a href="javascript:void(0)" class="removeMessage">Verwijderen</a></div>
{literal}
<script>
$(function() {
	$("tr.messagerow").mouseover(function(){
		$(this).addClass("messagerow_mo");
	});
	$("tr.messagerow").mouseout(function(){
		$(this).removeClass("messagerow_mo");
	});
	$(".removeMessage").click(function(){
		removeMessages();
	});
	$("tr.messagerow td").filter(":not('.rowCheckbox')").click(function(){
		var messageid = $(this).parent().attr("messageid");
		window.location.href = "/?page=mymessages&action=read&id=" + messageid;
	});
	$("#checkAll").click(function(){
		var check = $(this).attr("checked");
		$("input[type=checkbox]").attr("checked", check);
	});
});

function removeMessages() {
	var checkboxes = $("input[type=checkbox][checked]");
	var amount = checkboxes.size();
	if(amount == 0) {
		alert("Geen berichten geselecteerd om te verwijderen");
		return;
	}
	var label = amount > 1 ? "deze berichten" : "dit bericht";
	
	var aId = new Array(); 
	if(confirm("Weet je zeker dat je " + label + " wilt verwijderen?")){
		var all = checkboxes.each(function() {
			aId[aId.length] = $(this).parents("tr").attr("messageid"); 
		});
		$.getJSON("/?page=mymessages&action=delete", 
			{
				id: aId.join(",")
			}, function(data) {
				processJsonResponse(data, {
					success: function() {
						window.location.reload();
					}
				});
				
			}
		);
	}
}
</script>
{/literal}
