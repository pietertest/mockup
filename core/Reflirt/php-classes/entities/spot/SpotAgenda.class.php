<?php
include_once PHP_CLASS.'entities/agenda/Agenda.class.php';
include_once PHP_CLASS.'entities/spot/Spot.class.php';

class SpotAgenda extends AbstractAgenda {
	
	private $agenda = null; 
	private $spot = null;
	
	public function __construct() {
		parent::__construct("reflirt_nieuw", "spotagenda");
	}
	
	public function load() {
		parent::load();
		$this->agenda = $this->loadEntityByForeignKey(new Agenda, "agendaid");
		$this->putAll($this->agenda);
	}
	
	public function getReactions() {
		return $this->getRealAgenda()->getReactions();
	}
	
	public function getRealAgenda() {
		Utils::assertTrue("Cannot get agenda on non loaden SpotAgenda", !$this->isNew());
		return $this->agenda;
	}
	
	public function getSpot() {
		if ($this->spot == null) {
			$this->spot = $this->loadEntityByForeignKey(new Spot, "spotid");
		}
		return $this->spot; 
	}
	
	public function save() {
		$this->agenda = new Agenda();
		$this->agenda->setUser($this->getUser());
		$this->agenda->putAll($this);
		$this->agenda->save();
		
		$this->put("agendaid", $this->agenda->getKey());
		parent::save();		
	}
	
	public static function getAgendaSearcher() {
		return new SpotAgendaSearcher();
	}
	
	public function getHtmlRenderer() {
		return new SpotAgendaHtmlRenderer($this);
	}
}
    
class SpotAgendaSearcher extends DefaultSearcher {
	
	function getFields(DataSource $ds) {
		return "spotagenda.*, agenda.*, users.username, users.systemid AS userid, spotagenda.systemid AS systemid";
	}
	function getTables(DataSource $ds) {
		return "FROM spotagenda " .
				"JOIN agenda " .
				"ON spotagenda.agendaid = agenda.systemid ".
				" JOIN users " .
				" ON spotagenda.user = users.systemid ";
	} 

    function getFilter(DataSource $ds) {
    	$list = new QueryConstraintList();
    	$list->addKey("spotid", $ds->getInt("spotid", -1));
    	return $list;
    }
}

class SpotAgendaHtmlRenderer extends DefaultHTMLRenderer {
	
	public function __construct($ent) {
		parent::__construct($ent);
	}
	
	public function get($what) {
		if($what == "date") {
			return "op ".
				$this->ent->getString("start").
				" tot ".
				$this->ent->getString("end")
			;
		} else if ($what=="dateascalendar") {
			$date = $this->ent->getString("start");
			return strtoupper(strftime("%d %b",  strtotime($date))); // 02 SEP
		}		
		return parent::get($what);
	}
	
}