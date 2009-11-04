<script language="JavaScript" type="text/javascript">
var simpleSearch = {if $simplesearch}true{else}false{/if};
{literal}
 $(function() {
 	$("#showSimple").click(showSimple); 
 	$("#showAdvanced").click(showAdvanced);
 	if (simpleSearch) {
 		$("#simple").show();
 	} else {
 		$("#advanced").show();
 	}
 });
 
 function showSimple() {
 	$form = $("#advanced").hide();
 	$form.clearForm();
 	$("#simple").show();
 	$("#category").val();
}
 
 function showAdvanced() {
 	$form = $("#simple").hide();
 	$form.clearForm();
 	$("#advanced").show();
 }
</script>
{/literal}

{if $action == "myresults"}
	<div class="arrowBack"><a href="/?page=account">Terug naar mijn overzicht</a></div>
	<br/>
{/if}
  
<div id="simple" class="searchForm" style="display: none;">
	<form action="/" method="get">
		<input type="hidden" name="page" value="zoeken" />
		<input type="hidden" name="action" value="overview" />
		<input type="hidden" name="simplesearch" value="1" />
		{roundedCorners}
		<table class="searchFormTable">
			<tr>
				<td class="label">
					Trefwoord:
				</td>
				<td>
					{round_textfield name="q" value="$q" width="280" id="example" }
				 </td>
			</tr>
			<tr>
				<td class="label">Zoeken in categorie:</td>
				<td>
					{round_select id="categorySimple" name="category" options=$categoriesSimple selected=$category width="280" }
				</td>
			</tr>
		</table>
		{/roundedCorners}
	
		{submit value="Zoeken" class="submit" }
		<div class="toggler"><img src="/images/global/arrow_down.gif" /> <a href="javascript:void(0)" id="showAdvanced" class="noline small">Uitgebreid zoeken</a></div>
	</form>
</div>
  
<div id="advanced" class="searchForm" style="display: none;">
	<form action="/" method="get" id="advancedForm">
		<input type="hidden" name="page" value="zoeken" />
		<input type="hidden" name="action" value="overview" />
		{roundedCorners} 
		<table width="100%" border="0" class="searchFormTable">
			<tr>
				<td class="label">Ik kwam mijn flirt tegen in:</td>
				<td>{round_select id="category" name="category" options=$categories selected=$category width="280" }</td>
			</tr>
			
			<tr id="spacer">
				<td colspan="2" >&nbsp;</td>
			<tr>
			
			{include file="spot/spot.ajax.getformfields.tpl"}

			<tr>
				<td class="label">
					<label for="startdate">Datum:</label>
				</td>
				<td>
					
					{assign var=formattedStartdate value=$startdate|date_format:"%d-%m-%Y"}
					{round_textfield name="startdate" tabindex="2" id="startdate"  tfClass="date-pick dp-applied" class="date-pick dp-applied" value=$formattedStartdate}
					{if !$startdate && !$enddate}
						{assign var=checkRegelmatig value="checked"}
					{/if}
					<input {$checkRegelmatig} style="margin: 5px 0 0 10px" type="checkbox" name="regelmatig" value="regelmatig" id="regelmatig" /><label for="regelmatig">Regelmatig/vaker</label>
				</td>
			</tr>
			<tr>
				<td>Geslacht:</td>
				<td>
					{html_checkboxes name="sex" selected=$sex options=$checkboxesSex separator="&nbsp;"} 
				</td>
			</tr>
		</table>
		
		{/roundedCorners}
		{submit value="Zoeken" class="submit" }
		<div class="toggler"><img src="/images/global/arrow_up.gif" /> <a href="javascript:void(0)" id="showSimple" class="noline small">Terug naar eenvoudig zoeken</a></div>
	</form>
</div>

<div class="clear"></div>

<script>
var formFieldsUrl = "getFormFields";
</script>
{include file="zoeken/incl/zoeken.js.tpl"}

<div class="results">
	<div class="dots"></div>
	<br/>
	<ul class="paginate search">
		{paginate_prev class="paginatePrev" prefix="<li>"} 
		{paginate_middle prefix="<li>" suffix="" class="paginateMiddle" page_limit=5 format="page"}
		{paginate_next class="paginateNext" prefix="<li>"}
	</ul>
	
	<h2 class="colored">
	{if $paginate.total > 0}
		{$paginate.total|@singular:" Resultaat":" Resultaten"} ({$paginate.first} - {$paginate.last} getoond)
	{else}
		0 Resultaten
	{/if} 
	</h2>
	
	<br/>
	<br/>
	
	{foreach from=$searchresults item=oproep}
		{assign var=user value=$oproep->getUser()}
		{include file="oproep/incl/oproep.tpl"}
	{foreachelse}
		<div class="center">
			(Geen resultaten) 
			<a href="/?page=zoeken&action=compose"><div class="welcomePlaceAdd"></div></a>
		</div>
	{/foreach}
</div>




