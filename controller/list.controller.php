<?php

$pl = new monstring(get_option("pl_id"));
$fylke = $pl->get('fylke_id');

$date = new DateTime();
$date->setTime(0,0,0); // Sett tid til 0 i timestamp.
$dato = $date->getTimestamp();

$sql = new SQL("SELECT * FROM `ukm_kalender` WHERE `fylke` = ".$fylke." ORDER BY `start`");
$res = $sql->run();
if( $res ) {
	while( $row = SQL::fetch( $res ) ) {
		$row['title'] = utf8_encode($row['title']);
		$row['description'] = utf8_encode($row['description']);
		$row['location'] = utf8_encode($row['location']);
		if (strtotime($row['start']) < $dato) { // Hvis gamlere enn nåtid
			$INFOS['calendar_events']['Tidligere'][] = $row;
		}	
		else {
			$INFOS['calendar_events']['Kommende'][] = $row;
		}
	}
}