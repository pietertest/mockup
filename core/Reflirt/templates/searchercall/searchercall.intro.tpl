{if $type==3}
<h1>Nieuwe Spot plaatsen</h1>
{else}
<h1>Nieuwe oproep plaatsen</h1>
{/if}
{literal}
<script>
$(document).ready(function() {
	var type = '{/literal}{$type}{literal}';
	type = (type == '') ? 1 : type;
	$("input#but_volgende").click(function() {
		var cat = $("select#category").val();
		if(cat == "") {
			alert("Maake een keuze");
			return;
		}
		window.location = "/?page=searchercall&action=create&type="+type+"&cat=" + cat;
	});
});
</script>
{/literal}
{html_options name=foo options=$categories id="category" selected=$cat}
<input type="button" value="Volgende" id="but_volgende" />