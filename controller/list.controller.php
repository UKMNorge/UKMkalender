<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Database\SQL\Query;

require_once('UKM/Autoloader.php');

$pl = new Arrangement(intval(get_option("pl_id")));
$fylke = $pl->get('fylke_id');

$date = new DateTime();
$date->setTime(0,0,0); // Sett tid til 0 i timestamp.
$dato = $date->getTimestamp();

$sql = new Query("SELECT * FROM `ukm_kalender` WHERE `fylke` = ".$fylke." ORDER BY `start`");
$res = $sql->run();
if( $res ) {
	while( $row = Query::fetch( $res ) ) {
		$row['title'] = $row['title'];
		$row['description'] = $row['description'];
		$row['location'] = $row['location'];
		if (strtotime($row['start']) < $dato) { // Hvis gamlere enn nåtid
			$INFOS['calendar_events']['Tidligere'][] = $row;
		}	
		else {
			$INFOS['calendar_events']['Kommende'][] = $row;
		}
	}
}