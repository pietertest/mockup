eeeeDit is <b>search.tpl in catdisco</b>
<br/>
<br/>
<form action="/" method="post">
	Entity: <input type="text" name="page" value="{$smarty.request.page}" />
	Action: <input type="text" name="action" value="save" />
	
	Datum van: <input type="date_start"><br/>
	Datum tot: <input type="date_end"><br/>
	
	Land: <input type="text" name="country"><br/>
	Plaats: <input type="text" name="cityname"><br/>
	Disco: <input type="text" name="disco"><br/>
	Omschrijving: <textarea name="descr"></textarea><br/>
	<input type="submit" value="Bewaar" />
</form>


