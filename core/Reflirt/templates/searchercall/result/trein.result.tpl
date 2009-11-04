<div style="height: 70px;">
	<div style="float: right; color: gray;">Uit <a href="">{$result->getFriendlyName()}</a></div>
	<div class="result_photo_container">
		<div class="result_photo" style="background: url(/uploaded/photos/{$result->getString('filename')}) 50% no-repeat" /></div>
		<a class="nick" href="/?page=profile&action=view&user={$result->getString('username')}">{$result->getString('username')}</a>
	</div>
	
	
	<div><a class="result_header" href="javascript:gotoResult('{$result->getKey()}', '{$result->getString('category')}')" >
	{$result->getString('cicityname')}</a></div>
	
	
	<img src="/images/nl/quotes_open.gif" /> 
	<span style="color: #5e5e5e;">{$result->getString("descr")}</span>
	<img src="/images/nl/quotes_close.gif" />
	<br/>

	<input type="button" value="Bekijk" onclick="gotoResult('{$result->getKey()}', '{$result->getString('category')}')" />
	<input type="button" value="Toevoegen" onclick="addToBookmarks('{$result->getString('username')}')" />
	
	
</div>
