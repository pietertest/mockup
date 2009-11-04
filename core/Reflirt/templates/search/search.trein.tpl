{if $smarty.get.simplesearch}
	{assign var=showSimpleSearch value="true"}
{else}
	{assign var=showSimpleSearch value="false"}
{/if}
{literal}

<script>
var discoSelected = false;

$(document).ready(function() {
	$("#cicityname").autocomplete("/servlets/autocomplete/city.php", {
		delay: 150,
		width: "260px",
		max: 10,
		formatItem: formatCityItem,
		formatResult: formatResult,
		selectFirst: false,
		extraParams: {
			cicountryid: function() {	return $("#cicountryid").val();}
		}
	});
	$("#cicityname").result(function(event, data, formatted) {
		$("#ovtdest").val(data[1]);
		$("#cicountryid").val(data[3]);
	});
	function formatCityItem(row) {
		return row[0];
	}
	$("#cicountryid").change( function() {
		$("#cicityname").flushCache();
		$("#ddisconame").flushCache();
		$("#cicityname").val("");
		$("#ddisconame").val("");
		$("#ovtdest").val("");
	});
	function formatResult(row) {
		return row[0];
	}
	var validator_advanced = $("#form_advanced").validate({
		rules: {
			ddisconame: {
				required: false
			},
			cicityname: {
				required: true
			}
		},
		messages: {
			title: "Vul een titel in",
			descr: {
				required: "Vul een omschrijving in"
			},
			cicityname: {
				required: "Vul een plaats in"
			}
		},
		errorPlacement: function(error, element) {
			if ( element.is(":radio") )
				error.appendTo( element.parent().next().next() );
			else if ( element.is(":checkbox") )
				error.appendTo ( element.next() );
			else
				error.appendTo( element.parent().next() );
		}
	});
	var validator_simple = $("#form_simple").validate({
		rules: {
			simplesearch: {
				required: true
			}
		},
		messages: {
			simplesearch: "Vul iets in"
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().next() );
		}
	});
	
	// Simple search
	$("a#a_simple").click( function() {
		$("form#form_advanced").hide();
		$("form#form_simple").show();
		$("a#a_simple").hide();
		$("a#a_advanced").show();
	});
	// Advanced search
	$("a#a_advanced").click( function() {
		$("form#form_simple").hide();
		$("form#form_advanced").show();
		$("a#a_simple").show();
		$("a#a_advanced").hide();
	}); 
	
	
	var showSimpleSearch = {/literal}{if $smarty.get.simplesearch == true}true{else}false{/if}{literal};
	if(showSimpleSearch) {
		$("a#a_simple").click();
	} else {
		$("a#a_advanced").click();
	}
	
	$("select#category_advanced").change(function() {
		window.location.href = "?page=search&action=compose&cat="+$("select#category_advanced").val();
	});
	
});

$(function()
{
	$('.date-pick').datePicker()
	$('#date_from').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#date_to').dpSetStartDate(d.addDays(1).asString());
			}
		}
	);
	$('#date_to').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#date_from').dpSetEndDate(d.addDays(-1).asString());
			}
		}
	);
});

	

{/literal}
</script>
<div class="search_container">

	<div class="search_header" ></div>
	
	<div>
		<div class="tab where">Waar ben je?</div>
		<div class="tab wannajoin">Wanna join me?</div>
		<a href="#" id="a_simple" class="simplesearch">Simpel zoeken</a>
		<a href="#" id="a_advanced" class="simplesearch" >Uitgebreid zoeken</a>
	</div>
	
	<div style="height: 28px"></div>
	
	<div class="left">
				
		<form id="form_simple">
			<input type="hidden" name="page" value="search" />
			<input type="hidden" name="action" value="dosimplesearch" />
			<input type="hidden" name="type" value="1" />
			
			<div class="search_header2">Ik ontmoette mijn flirt in de categorie {html_options name=cat options=$categories id="category" selected=$cat}</div>			
			
			<div class="search_simplelabel">Trefwoord:</div>
			<input type="text" class="search_simpleinput" id="simplesearch" name="simplesearch" value="{$simplesearch}"/>
			<input type="submit" value="Zoeken"/>
			<div class="search_examplelabel" >Bijvoorbeeld: "disco amsterdam" of "trein Goes"</div>
			<!-- Toon alleen: {html_checkboxes name='dsex' options=$sex_checkboxes selected=$dsex cseparator='<br />'} -->
		</form>
		
		<form id="form_advanced">
			<input type="hidden" name="page" value="search" />
			<input type="hidden" name="action" value="dosearch" />
			<input type="hidden" name="cat" value="{$cat}" />
			<input type="hidden" name="type" value="1" />
			
			<div class="search_header2">Ik ontmoette mijn flirt in de categorie {html_options name=cat options=$categories id="category_advanced" selected=$cat}</div>
			
			<table border="0" cellpadding="0" cellspacing="0">
	        	<tr>
	        		<td valign="top">
	                	<label class="search_advancedlabel">Eindbestemming:</label><br/>
						<input class="search_advancedinput" type="text" tabindex="2" value="{$cicityname}" name="cicityname" id="cicityname" />
						<input type="hidden" name="ovtdest" id="ovtdest" value="{$ovtdest}"/>
	        		</td>
	        		<td valign="top">
	        			<label class="search_advancedlabel">Land:</label><br/>
	        			{html_options class="search_advancedinput" name="cicountryid" options=$countries selected=$cicountryid id="cicountryid" style="width: 150px"}
	        		</td>
	            	<td valign="top">
						<label class="search_advancedlabel" for="date_from">In de periode van:</label><br/>
						<input name="date_from" tabindex="2" id="date_from" class="date-pick dp-applied search_advancedinput" value="{$date_from}">             			
	                </td>
	                <td>
	               	<label class="search_advancedlabel" for="date_to">tot:</label><br/>
						<input name="date_to" tabindex="1" id="date_to" class="date-pick dp-applied search_advancedinput" value="{$date_to}"><br/>
	                </td>
	        		<td valign="top">
	        			<label class="search_advancedlabel" >Geslacht:</label><br/>
	        			{html_options style="margin-top:8px;" name="ovtsex" options=$sex_options selected=$ovtsex id="ovtsex" }
	        		</td>
					<td colspan="2" valign="bottom">
						<input type="submit" value="Zoeken" />
					</td>
	        		
				</tr>
			</table>
		</form>
	</div>

	<div class="clear" ></div>
	
</div>

<div class="search_seperator_hor" ></div>

{literal}
<style>
div.sidebar {
	width: 250px;
	float: right;
	position:relative;
	margin-top: 30px;
}
div.joinme_header {
	background: #FEC71E url(/images/nl/wanna_join_me.gif) 0 0 no-repeat;
	height: 38px;
}
div.joinme_subheader {
	backtround-color: #FDF5DB;
}
</style>
{/literal}
{include file="search/incl-markkeywords.tpl"}
<div class="sidebar" >
	<div class="joinme_header"></div>
	<div class="joinme_subheader"></div>

{foreach from=$lastrelevantsearchers item=oproep}
	<div class="item">
		<div class="result_photo_container">
			<div class="result_photo" style="background: url(/uploaded/photos/{$oproep->getString('filename')}) 50% no-repeat" /></div>
			<a class="nick" href="/?page=profile&action=view&user={$oproep->getString('username')}">{$oproep->getString('username')}</a>
		</div>
		<div><a class="result_header">{$oproep->getString('ddisconame')} ({$oproep->getString('cicityname')})</a></div>
		<span style="color: gray; font-size: 11px;">{$oproep->getString('title')}</b></span>
		<div class="result_in_cat"><span class="result_in">In: </span><a class="result_cat">{$oproep->getFriendlyName()}</></div>
		
		<hr/>
	</div>
{/foreach}
</div>


	
	