	<div class="spotresult" spotid="{id}" >
	
		<!-- Category -->
		<div  class="in_category">Uit <a class="noline" href="/?page=spotsearch&action=search&q=&type=&spotcategory={category}">{categoryname}</a>	
			<div class="spottedLabel">
				{spotted} keer gespot
				<a href="/?page=myspots&action=add&id={id}" title="Markeren als spot"><img src="/images/global/add.gif" class="noBorder" /></a>
			</div>
		</div>
		
		<!-- Spot -->
		<div class="spot">
			<a class="simplesearch_spot_title noline" href="/?page=spot&action=view&id={id}&cat={category}">
				{name} 
			</a>
			<div class="simplesearch_spot_descr">{cityname}</div>
		</div>
		
		<div class="clear" ></div>
	</div>