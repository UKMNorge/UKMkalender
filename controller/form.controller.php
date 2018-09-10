<?php

if ($_GET)
$pl = new monstring(get_option("pl_id"));
$fylke = $pl->get('fylke_id');

// Finn event-id fra GET-variabel
$event_id = $_GET['id'];

// Kjør SQL-query for å finne data om eventen
$sql = new SQL("SELECT * FROM `ukm_kalender` WHERE `id` = ".$event_id." LIMIT 1");
$res = $sql->run();

if( $res ) {
	$row = SQL::fetch( $res );
	$row['title'] = utf8_encode($row['title']);
	$row['description'] = utf8_encode($row['description']);
	$row['location'] = utf8_encode($row['location']);
	$row['varsel'] = $row['varsling'];
	$INFOS['editevent'] = $row;
}

?>