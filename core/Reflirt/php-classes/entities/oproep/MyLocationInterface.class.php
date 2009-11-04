<?php
/**
 * Interface voor een categorie, bijv DiscoOproep.
 */
interface MyLocationInterface {

    /**
     * De standaard searcher die wordt gebruikt als je vanuit het standaard
     * zoekscherm gaat zoeken via de uitgebreide manier (dus niet via de 
     * 'google' manier). Misschien moet de naam anders..:)
     */
   public function getMyLocationLoadSearcher();
    
    /**
	 * Deze zoeker wordt gebruikt om te matcheb vanuit het zoekscherm waarbij
	 * NIET de fultext methode de 'google' methode) wordt gebruikt
 	*/
    public function getMatchSearcher();
    
    /**
     * Voor het kunnen ophalen van matches vanuit templatecode
     */
    public function getMatches();
    
    /**
	 * Deze searcher wordt gebruikt om zoekopdrachten relevant aan de gekozen 
	 * category waarop gezocht wordt te tonen in de sidebar. 
	 * (dus NIET met de google manier).
	 */
    public function getDefaultSearcher();
    
    /**
     * Geeft een titel terug, bijv.: "Paradiso, Amsterdam" of "Eindbestemming Vlissingen"
     */
    public function getTitle();
    
    /**
     * Geeft een datgene wat bij de titel goort terug, bijv.: "Amsterdam" bij Paradiso, 
     * of "Jan van galenstraat" bij Albert Heijn
     */
    public function getAddition();
    
    /**
     * Geeft een searcher terug die de meest populaire locations zoekt
     */
	public function getMostPopulairSearcher();
	
	/**
	 * Om generiek labels te kunnen printen in een opsomming van de meest 
	 * populaire spots zoals "Paradiso (20)" en "Alto (21)" heb je de kolomnaam
	 * nodig van - in dit geval - de discotheek (sddiscoid).
	 */
	public static function getSpotColumn();
}
?>