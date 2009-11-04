<div class="header2">Nieuwe Spot plaatsen</div>
{literal}
<script>
$(document).ready(function() {
	var type = '{/literal}{$type}{literal}';
	$("input#but_volgende").click(function() {
		var cat = $("select#category").val();
		if(cat == "") {
			alert("Maake een keuze");
			return;
		}
		window.location = "/?page=myspots&action=form&cat=" + cat;
	});
});
</script>
{/literal}
{html_options name=foo options=$categories id="category" selected=$cat}
<input type="button" value="Volgende" id="but_volgende" />