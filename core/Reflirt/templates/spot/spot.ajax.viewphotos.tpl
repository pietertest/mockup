<div id="spotphotos">

{foreach from=$photos item=photo}
	<div class="spotphoto">
		{if $photo->getString('descr') == ""}
			{assign var=title value=$photo->getString('orig_filename')}
		{else}
			{assign var=title value=$photo->getString('descr')}
		{/if}			
		<img src="/{$smarty.const.PHOTOS_WEB_DIR}/{$photo->getString('filename')}" title="{$title}" width="90" date="{$photo->getString('insertdate')|date_format:"%d-%m-%y "}"/>
	</div>
{/foreach}
</div>


{literal}
<script>
var dir = "/{/literal}{$smarty.const.PHOTOS_WEB_DIR}/"{literal};
$().ready(function(){
	$(".spotphoto").tooltip({
		
		bodyHandler: function() {
			var src = $(this).find("img").attr("src");
			var date = $(this).find("img").attr("date");
			var title = $(this).find("img").attr("title");
			return '<div style="border: 1px solid #DADADA; padding: 10px;">'+
					'<h3>'+title+'</h3>'+
					'<span class="">'+date+'</span><br/>'+
					'<img src="'+src+'" width="300" />'
				'</div>';
		},
		track: true
	});
	
	$(".spotphoto").hover(function(){
		$(this).addClass("hover");
	}, function() {
		$(this).removeClass("hover");
	});
});
</script>
{/literal}

*}