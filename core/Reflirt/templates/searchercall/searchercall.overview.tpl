<h1>Mijn Zoekopdrachten</h1>


<span style="background-color: #F0F5FF;font-size: 18px;" id="reflirt"><a nohref>Reflirt</a></span>
<span style="background-color: #F0F5FF;font-size: 18px;" id="preflirt">Preflirt</span>

{literal}<script>
$().ready( function() {
	$("div#searcher_preflirt").hide();
	$("span#reflirt").click( function() {
		$("div#searcher_reflirt").show();
		$("div#searcher_preflirt").hide();
	});
	$("span#preflirt").click( function() {
		$("div#searcher_reflirt").hide();
		$("div#searcher_preflirt").show();
	});
});
</script>{/literal}

<div id="searcher_reflirt">
{include file=searchercall/searchercall.overview.reflirt.tpl}
</div>
<div id="searcher_preflirt">
{include file=searchercall/searchercall.overview.preflirt.tpl}
</div>

{literal}
	<script>
      
      // Expand/Collapse result
      $(document).ready(function() {
        $('div.oproepen > div').hide(); 
        $('div.oproepen > h2').click(function() {
				$(this).siblings(".resultdiv").slideToggle("fast");
				//$("h2").not($(this)).siblings(".resultdiv").slideUp("fast");
	       });
      });
      
		// Go to result profile	
		function gotoResult(id, cat) {
			window.location = "/?page=searchercall&action=view&id=" + id + "&cat=" + cat;
		}
		
		function addBuddy(id) {
			window.location = "/?page=buddy&action=addintro&id=" + id;
		}
		
		function addToBookmarks(id) {
			window.location = "/?page=bookmark&action=add&user=" + id;
		}
		
		function remove(id, cat) {
			if(confirm("Weet je zeker dat je de oproep wilt verwijderen?")) {
				window.location = "/?page=searchercall&action=delete&id="+id+"&cat="+cat;
			}
		}
		function modify(id, cat) {
			window.location = "/?page=searchercall&action=modify&id="+id+"&cat="+cat;
		}
		
	</script>
	{/literal}
