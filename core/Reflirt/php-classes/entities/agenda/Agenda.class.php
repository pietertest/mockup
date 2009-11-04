<?php
include_once PHP_CLASS.'entities/db/DatabaseEntity.class.php';
include_once PHP_CLASS.'entities/agenda/AbstractAgenda.class.php';
include_once PHP_CLASS.'entities/agenda/AgendaReaction.class.php';

class Agenda extends AbstractAgenda {

    function __construct() {
    	parent::__construct("reflirt_nieuw", "agenda");
    }
    
    public function getReactions() {
    	$systemUser = UserFactory::getSystemUser();
    	$oq = ObjectQuery::build(new AgendaReaction, $systemUser);
    	$oq->setSearcher(new AgendaReactionSearcher());
    	$oq->addParameter("agendaid", $this->getKey());
		return SearchObject::search($oq);
    }
}

class AgendaReactionSearcher extends Searcher {
	
	function getFields(DataSource $ds) {
		return "users.*, photo.*, agendareaction.*, users.systemid AS userid";	
	}

	function getTables(DataSource $ds) {
		return " FROM agendareaction " .
				" INNER JOIN users " .
				" ON users.systemid = agendareaction.user".	
				" LEFT JOIN photo " .
				" ON photo.systemid = users.photoid";	
	}
	
	function getFilter(DataSource $ds) {
		$list = new QueryConstraintList();
		$list->addKey("agendaid", $ds->get("agendaid"));
		return $list;	
	}
	
	function getOrderBy(DataSource $ds) {
		return "agendareaction.insertdate DESC";
	} 
}
?>