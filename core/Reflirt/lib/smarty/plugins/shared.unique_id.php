<?
$aId = array();

function uniqueId() {
	$id = 'tf_'.rand(100, 10000);
	while(isset($aId[$id])) {
		$id = 'tf_'.rand(100, 10000);
	}
	$aId[$id] = 1;
	return $id; 
}
?>