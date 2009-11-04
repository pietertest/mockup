<script type="text/javascript" src="{$_googlemaps_script}"></script>
<h2>Zoeken</h2>

<form id="searchform" action="?page=search&action=simplesearch" method="get">
	<input type="hidden" name="page" value="search" />
	<input type="hidden" name="action" value="simplesearch">
	<input type="hidden" name="tab" id="tab" value="{$tab}">
	
	<!-- Spots -->
	<input type="hidden" id="spotcategory" name="spotcategory" value="{$spotcategory}"/>
	
	
	Trefwoord:<br/>
	<input type="text" name="q" id="q" value="{$q}" size="40"/>
	<input type="hidden" name="hiddenkeyword" id="hiddenkeyword" value="{$q}" size="40"/>
	<input type="submit" value="Zoeken" name="s" />
</form>

<div id="tabscontainer" class="tabscontainer">
	<a href="/?page=search&action=simplesearch&tab=spots&q={$q}">Spots</a>
	<a href="/?page=search&action=people&tab=people&q={$q}">People</a>
	<ul>
	    <li><a href="/?page=spotsearch&action=search&q={$q}&spotcategory={$spotcategory}"><span>Spots</span></a></li>
	    <li><a href="/?page=search&action=people&q={$q}"><span>People</span></a></li>
	</ul>
</div>
<div id="contents2"></div>

<span style="color: red"></span>
{literal}<script>

var currentTab = '{/literal}{$tab}{literal}';
var loaded = new Array();
var q = '{/literal}{$q}{literal}';
var _tab = '';


$().ready(function() {
	$("#searchform").submit(function() {
		var inputValues = "";
		$("input").each(function(){
			inputValues += $(this).val();
		});
		var url = "/?page=search&action=simplesearch&" + 
			"spotcategory="+$("#spotcategory").val()+"&q="+$("#q").val();
		if (_tab != "") {
			url += "#" + _tab;	
		}
		window.location.href= url;
		return false;
	});
	var $tabs = $('#tabscontainer > ul').tabs({
		select: function(ui) { _tab = ui.panel.id; return true;},
		idPrefix: "tab",
		cache: true
	});
});


</script>{/literal}