<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Database\SQL\Query;

require_once('UKM/Autoloader.php');

if ($_GET)
$pl = new Arrangement(intval(get_option("pl_id")));
$fylke = $pl->get('fylke_id');

// Finn event-id fra GET-variabel
$event_id = $_GET['id'];

// Kjør SQL-query for å finne data om eventen
$sql = new Query("SELECT * FROM `ukm_kalender` WHERE `id` = ".$event_id." LIMIT 1");
$row = $sql->getArray();

if( $row ) {
	$row['title'] = $row['title'];
	$row['description'] = $row['description'];
	$row['location'] = $row['location'];
	$row['varsel'] = $row['varsling'];
	$INFOS['editevent'] = $row;
}

?>