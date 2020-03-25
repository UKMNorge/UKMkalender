<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Database\SQL\Delete;
use UKMNorge\Database\SQL\Query;

require_once('UKM/Autoloader.php');

$pl = new Arrangement(intval(get_option("pl_id")));
$fylke = $pl->get('fylke_id');

// Finn event-id fra GET-variabel
$event_id = $_GET['id'];

if ($event_id) {
	// Kjør SQL-query for å finne data om eventen
	$sql = new Query("SELECT * FROM `ukm_kalender` WHERE `id` = ".$event_id);
	$res = $sql->run();

	if( $res ) {
		$row = Query::fetch( $res );
		// Sjekk at fylke på event matcher fylke som vil slette.
		if ($fylke != $row['fylke']) {
			$INFOS['message'] = array('level'=>'danger', 'header'=>'Feilet!','body'=>'Du kan ikke slette events som ikke tilhører deg.');
		} 
		else {	
			// Kjør SQL-query for å slette data
			$sql = new Delete('ukm_kalender', array('id' => $event_id));
			$res = $sql->run();

			if ($res == TRUE) {
				$INFOS['message'] = array('level'=>'success', 'header'=>'Suksess!','body'=>'Event slettet.');
			}
			else {
				$INFOS['message'] = array('level'=>'danger', 'header'=>'Feilet!','body'=>'Klarte ikke slette event.');
			}
		}
	}
}
else {
	$INFOS['message'] = array('level'=>'danger', 'header'=>'Feilet!','body'=>'Event ID mangler.');
}

?>