<div class="result">
	<div style="height: 70px;">
		<div style="float: right; color: gray;">Uit <a href="">{$result->getFriendlyName()}</a></div>
		<div class="result_photo_container">
			<div class="result_photo" style="background: url(/uploaded/photos/{$result->getString('filename')}) 50% no-repeat" /></div>
			<a class="nick" href="/?page=profile&action=view&user={$result->getString('username')}">{$result->getString('username')}</a>
		</div>
		
		<a class="result_header" style="xcolor: #5e5e5e;font-weight: bold;text-decoration: underline">{$result->getString('title')}</a>
		<br/>
		<div>
			<span class="xresult_header" href="javascript:gotoResult('{$result->getKey()}', '{$result->getString('category')}')" >
		{$result->getTitle()}</span>
		</div>
		<br/>
		
		<img src="/images/nl/quotes_open.gif" /> 
		<span style="xcolor: #5e5e5e;;">{$result->getString("descr")}</span>
		<img src="/images/nl/quotes_close.gif" />
		
		<!-- input type="button" value="Bekijk" onclick="gotoResult('{$result->getKey()}', '{$result->getString('category')}')" /-->
		{if $smarty.session.uid}
		<input type="button" value="Onthouden" onclick="addToBookmarks('{$result->getString('username')}')" />
		{/if}
		
	</div>
</div>