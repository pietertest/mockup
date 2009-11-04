<script type="text/javascript" src="{$_googlemaps_script}"></script>
<h2>Wie, wat, waar, wanneer?</h2>

<form id="searchform" class="searchform" action="?page=search&action=simplesearch" method="get">
	<input type="hidden" name="page" value="{$smarty.get.page}" />
	<input type="hidden" name="action" value="{$smarty.get.action}">
	<input type="hidden" name="tab" id="tab" value="{$smarty.get.tab}">
	
	<!-- Spots -->
	<input type="hidden" id="spotcategory" name="spotcategory" value="{$spotcategory}"/>
	
	<div class="searchBar">
		<input type="hidden" name="hiddenkeyword" id="hiddenkeyword" value="{$q|escape:html}" size="40"/>
		<input type="text" name="q" id="q" maxlength="255" class="searchText" value="{$q|escape:html}"/>
		<input type="submit" value="Zoeken" class="searchSubmit" />
	</div>

	<ul class="ui-tabs-nav">
		<li class="ui-tabs{if $smarty.get.page==spotsearch}-selected{/if}">
			<a style="cursor: pointer;" class="" href="/?page=spotsearch&action=search&q={$q|escape:html}&type={$type}&spotcategory={$spotcategory}"><span>Spots</span></a>
		</li>
		<li class="ui-tabs{if $smarty.get.page==search}-selected{/if}">
			<a style="cursor: pointer;" class="" href="/?page=search&action=people&q={$q|escape:html}&type={$type}"><span>People</span></a>
		</li>
	</ul>
	
	<!-- Tooltip -->
	<div class="hidden">
		<div style="border: 1px solid red;"></div>
	</div>
	

{if !$q}
<script>
{literal}
$(function(){
	$("#q").focus(function(){
		$(this).val("").removeClass("example");
	});
});
</script>
{/literal}


{/if}
