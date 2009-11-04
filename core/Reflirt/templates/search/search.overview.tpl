{literal}
<script>
$().ready(function() {
	
	$("div.result").hover( function() {
		$(this).addClass("result_mo");
	}, function() {
		$(this).removeClass("result_mo");
	});

	
	// Simple search
	$("a#a_simple").click( function() {
		$("form#form_select_cat").hide();
		$("form#form_simple").show();
		$("a#a_simple").hide();
		$("a#a_select_cat").show();
	});
	// Advanced search
	$("a#a_select_cat").click( function() {
		$("form#form_simple").hide();
		$("form#form_select_cat").show();
		$("a#a_simple").show();
		$("a#a_select_cat").hide();
	}); 
	var showSimpleSearch = {/literal}{if $smarty.get.simplesearch}true{else}false{/if}{literal};
	if(showSimpleSearch) {
		$("a#a_simple").click();
	} else {
		$("a#a_select_cat").click();
	}
	
	$("input#but_volgende").click(function() {
		var cat = $("select#category").val();
		if(cat == "0") {
			alert("Maake een keuze");
			return false;
		}
		window.location = "/?page=search&action=compose&cat=" + cat;
	});
});
</script>
{/literal}

<div class="search_container">

	<div class="search_header" ></div>
	
	<div>
		<div class="tab where">Waar ben je?</div>
		<div class="tab wannajoin">Wanna join me?</div>
		<a href="#" id="a_simple" class="simplesearch">Simpel zoeken</a>
		<a href="#" id="a_select_cat" class="simplesearch" >Uitgebreid zoeken</a>
	</div>
	
	<div style="height: 28px"></div>
	
	<div class="left">
		
		
		<form id="form_select_cat">
			<div class="search_header2">Ik ben op zoek naar mijn flirt</div>
			<div class="search_simplelabel">De onmoeting met mijn flirt valt in de category:</div>
			{html_options name=cat options=$categories id="category" class="search_simpleinput"}
			<input type="button" value="Volgende" id="but_volgende" />
		</form>
		
		<form id="form_simple">
			<input type="hidden" name="page" value="search" />
			<input type="hidden" name="action" value="dosimplesearch" />
			<input type="hidden" name="type" value="1" />
			
			<div class="search_header2">Ik ontmoette mijn flirt in de categorie {html_options name=cat options=$categories id="category"}</div>
			
			<div class="search_simplelabel">Trefwoord:</div>
			<input type="text" class="search_simpleinput" id="simplesearch" name="simplesearch" value="{$simplesearch}"/>
			<input type="submit" value="Zoeken"/></br>
			<div class="search_examplelabel" >Bijvoorbeeld: "disco amsterdam" of "trein Goes"</div>
			
			<!-- Toon alleen: {html_checkboxes name='dsex' options=$sex_checkboxes selected=$dsex cseparator='<br />'} -->
		</form>	
		
	
	</div>
</div>
	
<div class="search_seperator_hor" ></div>
<div class="search_header2">Laatste Zoekopdrachten</div>
	
{foreach from=$lastsearchers item=result}
	{assign var=category value=$result->getString('category')}
	{assign var=shortname value=$result->getShortCatName($category)}
	{include file="search/incl/results.tpl"}
{/foreach}
</div>





