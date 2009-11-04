	<div class="addBig">
		<div class="addPaddingWrapper">
			<div class="addPictureContainer">
				<div class="addPicture" style="background: url({$user->getPhotoUrl()}) no-repeat;"></div>
				<div class="addNick"><a class="small noline" href="{$user->getHtml("profileurl")}">{$user->getUserName()}</a></div>
			</div>
			
			<div class="addTitle">
				<span class="small gray right" style="text-align: right;">
					<a class="noline" href="/?page=zoeken&action=overview&simplesearch=1&category={$oproep->getCategory()}">{$oproep->getCategoryLabel()}</a>
					<br/>
					{$oproep->get("insertdate")|date_format:"%d %b %Y"}
				</span>
				<span class="addTitle"><a href="/?page=oproep&action=view&id={$oproep->getKey()}"><strong>{$oproep->getTitle()}</strong></a></span></div>
			<div class="addSummary"><strong>{$oproep->getHtml("onderschrift")}</strong></div>
			{if $oproep->get("message")}
				<div class="addDescription">{$oproep->get("message")|truncate:200} <a href="{$oproep->getUrl()}" class="noline">meer...</a></div>
			{/if}
			<br/>
			
		</div>
	</div>
	<div class="clear" ></div>