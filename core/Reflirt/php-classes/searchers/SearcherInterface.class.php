<?php

interface SearcherInterface {

	function getFields(DataSource $ds);

    function getTables(DataSource $ds);

    function getFilter(DataSource $ds);
    
    function getOrderBy(DataSource $ds);
    
    function getGroupBy(DataSource $ds);
    
}
?>