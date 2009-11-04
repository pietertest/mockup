<div class="agenda">
	<h1>{$agenda->get('title')}</h1>
	<div class="agendainfo">
		<div class="agenda_details_left">
			<img src="http://tbn0.google.com/images?q=tbn:Fb9Xfb07xfU7DM:http://www.deletterenspreken.nl/agenda/images/agenda.jpg" />
		</div>
		<div class="agenda_details_right">
		 	<a href="/?page=spot&action=view&id={$spot->getKey()}">{$spot->getName()}</a><br/>
		 	{$spot->getHTML('address')}
			<br/>
			<br/>			
			{$agenda->get('description')}
		</div>
		<a nohref class="addagenda">Ik ga ook!</a>
		<div class="clear"></div>
	</div>
	
	<form>
	<h2>Reactie plaatsen</h2>
		<input type="hidden" name="page" value="agenda" />
		<input type="hidden" name="action" value="addreaction" />
		<input type="hidden" name="id" value="{$agenda->getKey()}" />
		<textarea name="message"></textarea><br/>
  		<input class="sendmessage" type="submit" value="Bericht plaatsen" />
  	</form>
  
  
	
	<br/>
	
	<div class="agendareactions">
		<h2>Reacties</h2>
		{foreach from=$reactions item=reaction}
			<div class="result_photo_container">
				<div class="result_photo" style="background: url(/uploaded/photos/{$reaction->get('filename')}) 50% no-repeat" >
					<div class="photo_frame"></div>
				</div>
				<a class="nick" href="/?page=userprofile&action=view&user={$reaction->get('username')}">{$reaction->get('username')}</a>
			</div>
			<span class="date">{$reaction->get('insertdate')|date_format:"%d-%m-%y %H:%M"}</span>
			<br/>
			<br/>
			{$reaction->get('message')}
			<div class="clear"></div>
		{/foreach}
	</div>
</div>