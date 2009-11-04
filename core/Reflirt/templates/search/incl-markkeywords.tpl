<div style="background-color: #FFFDE1; dwidth: 600px; height: 40px; padding: 10px" >
	<input type="checkbox" name="highlight_keywords" id="highlight_keywords"/><label for="highlight_keywords">Markeer zoekwoorden</label>
	{literal}
	<script>
		$("input#highlight_keywords").click( function() {
			$.highlight('DISCO');
			$("#results").each(function() { 
//				$.highlight('{/literal}{$simplesearch}{literal}'); 
				
			});
		});
	</script>
	{/literal}
</div>