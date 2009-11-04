{include file=search/search.compose.tpl}


{literal}
<script>
$().ready( function() {
	
	$("div.result").hover( function() {
		$(this).addClass("result_mo");
	}, function() {
		$(this).removeClass("result_mo");
	});
});
</script>
{/literal}
<div id="results">
{if !$searchresults} geen resultaten {/if}
{foreach from=$searchresults item=result}
	{assign var=category value=$result->getString('category')}
	{assign var=shortname value=$result->getShortCatName($category)}
	{include file="search/incl/results.tpl"}
{/foreach}
{literal}
</div>
<script>
// Go to result profile
		function gotoResult(id, cat) {
			window.location = "/?page=searchercall&action=view&id=" + id + "&cat=" + cat;
		}
		
		function addToBookmarks(id) {
			window.location = "/?page=bookmark&action=add&user=" + id;
		}
</script>
{/literal}