	
	<br/>
	{roundedCornersBlue style="width: 200px;" class="myphotospane"}
	<a href="javascript:void(0)" class="addalbum">Nieuw album aanmaken</a>
		<form id="createalbum" method="post">
			<input type="hidden" name="page" value="myphotos" />
			<input type="hidden" name="action" value="createalbum"/>
			<input type="text" name="album" id="album" />
			<input type="submit" value="Aanmaken"/>
			<a href="javascript:void(0)" id="cancelalbum">Annuleren</a>
			<br/>
			<br/>
		</form>
	{/roundedCornersBlue}
		
	
	<div class="clear"></div>
	
	{foreach from=$albums item=albuminfo}
		{assign var=album value=$albuminfo.album}
		{assign var=photos value=$albuminfo.photos}
	
	<table border="0" id="albumTable_{$album->getKey()}">
		<tr>
			<td colspan="2"><h2 class="albumheader">{$album->getString('albumname')}</h2>
			<div class="dots"></div>	
			<br/>
		</td>
		</tr>
		<tr>
			<td>
				<div class="album" id="album_{$album->getKey()}">
				
				{foreach from=$photos item=photo}
						
					{if $photo->getKey() == $profilePictureId}
						<div class="photo profilePicture" photoid="{$photo->getKey()}" >
					{else}
						<div class="photo" photoid="{$photo->getKey()}">
					{/if}
							<img src="{$photo->getUrl()}" title="{$photo->getUrl()}"/>
							<br/>
							<h3>{$photo->getString('descr')}</h3>
							{assign var=checked value="checked"}
							
							<div class="links">
								<a class="small noline" href="javascript:setProfilePicture('{$photo->getKey()}')">Profiel Foto</a>
								<br/>
								<a class="small noline deletephoto"  href="javascript:void(0)" >Verwijderen</a>
							</div>
						</div/>
				{/foreach}
				</div>
			</td>
			<td width="200">
				<div class="albumcontroller">
					<form enctype="multipart/form-data" action="/?page=myphotos&action=upload" method="post" class="pictureForm" >
						<input type="hidden" name="album" value="{$album->getKey()}" />
						Foto uploaden: <input type="file" name="picture" value="Zoeken.." />
						<input type="submit" value="Foto uploaden" />
						<br/>
						<div><a href="javascript: void(0)" class="small noline deleteAlbum">Album verwijderen</a></div>
					</form>
					
				</div>
			</td>
		</tr>
	</table>
	<br/><br/>
	{/foreach}
</form>

{literal}
<script>

$().ready( function() {
	// Nieuw
	$(".deletephoto").click(function() {
		if(confirm("Weet je zeker dat je deze foto wilt verwijderen?")) {
			var $photo = $(this).parents(".photo");
			var id = $photo.attr("photoid");
			$.getJSON("/?page=myphotos&action=deletephoto", {id: id}, function (data) {
				processJsonResponse(data, {
					success: function(data) {
						$photo.hide("slow");
					} 
				});
			});
		}		
	});

	// Delete Album
	$(".deleteAlbum").click(function() {
		if(confirm("Alle fotos in dit album worden ook verwijderd.\n" +
				"Weet je zeker dat je het album wilt verwijderen?")) {
			var $form = $(this).parents("form");
			var id = $form.find("input[name=album]").val();
			$.getJSON("/?page=myphotos&action=deletealbum", {id: id}, function (data) {
				processJsonResponse(data, {
					success: function(data) {
						$("#albumTable_" + id).hide("slow");
					} 
				});
			});
		}		
	});

	$(".pictureForm").ajaxForm({
		 beforeSubmit: function() {
	 		loading("De foto wordt geupload...");
		}, 
	     success:   function(data) {
	     	if(data.success) {
					success("De foto is geplaatst!");
					//alert("#album_" + data.album);
					//$('<img src="' + data.photoUrl + '" />').appendTo("#album_" + data.album);
					window.location.reload();
		        } else if (data.fail){ 
					warn("Oeps! Er is een fout opgetreden bij het bewaren van je wijzigingen: " + data.fail.message);
		        } else { 
					warn("Oeps! Er is een fout opgetreden bij het bewaren van je wijzigingen. Probeer het nog een keer");
		        }
		       	done();
			}, 
	     dataType:  "json"
	 });
	
	// Create new album
	$("#createalbum").hide();
	$(".myphotospane .addalbum").click(function(){
		showCreateAlbum();
		$("#album").focus();
	});
	$("#cancelalbum").click(function(){
		hideCreateAlbum();
	});
	
	
	$(".photo").hover(function(){
		$(this).children(".deletephoto").show();
	},function(){
		$(this).children(".deletephoto").hide();
	});
	$("wdeletephoto").click(function() {
		deletePhoto($(this).parent().attr("photoid"));
	});
});
function deletePhoto(id) {
	if(confirm("Wil je deze foto echt verwijderen?")) {
		window.location.href = "/?page=myphotos&action=deletephoto&id="+id;
	}
}

function deleteAlbum(id) {
	if(confirm("Alle fotos in dit album worden ook verwijderd.\n" +
		"Weet je zeker dat je het album wilt verwijderen?")) {
			window.location.href = "/?page=myphotos&action=deletealbum&albumid="+id;
	}
}

function setProfilePicture(id) {
	window.location.href = "/?page=myphotos&action=setprofilepicture&photoid="+id;
}

function showCreateAlbum() {
	$(".myphotospane .addalbum").hide();
	$("#createalbum").show();
}

function hideCreateAlbum() {
	$(".myphotospane .addalbum").show();
	$("#createalbum").hide();
}
</script>
{/literal}