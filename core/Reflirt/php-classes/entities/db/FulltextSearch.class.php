<?php

/**
 * Wanneer deze interface geimplementeerd wordt betekent dit dat de entiteit
 * geindexeerd dient te worden in de fulltext kolom van de zoekopdracht tabel. 
 * Welke gegevens van de entiteit geindexeerd moeten worden moet aangegeven
 * worden met annotaions, bijv:
 * 
 * @FulltextColumns ("LAND,PLAATSNAAM,DISCO_NAAM");
 * 
 */
interface FulltextSearch {   
	
	/**
     * Kolommen die geindexeerd moeten worden in de Fulltext tabel
     */
    function getFulltextColumns();
    
    /**
     * extra keywords om op te matchen
     */
	function getExtraKeywords();
 
}
?>