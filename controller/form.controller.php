<?php

if ($_GET)
$pl = new monstring(get_option("pl_id"));
$fylke = $pl->get('fylke_id');


// Finn event-id fra GET-variabel
$event_id = $_GET['id'];
var_dump($fylke);
var_dump($event_id);
echo "<br />";
// Kjør SQL-query for å finne data om eventen
$sql = new SQL("SELECT * FROM `ukm_kalender` WHERE `id` = ".$event_id." LIMIT 1");
$res = $sql->run();

var_dump($sql);
echo "<br />";
var_dump($res);

if( $res ) {
	$row = mysql_fetch_assoc( $res );
	$row['title'] = utf8_encode($row['title']);
	$row['description'] = utf8_encode($row['description']);
	$row['location'] = utf8_encode($row['location']);

	$INFOS['editevent'] = $row;

	
}

?>